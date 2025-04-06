<?php

namespace App\Services;

use App\Models\Timetables;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\Room;
use App\Models\SessionType;
use App\Models\StudentGroup;
use App\Models\LecturerAvailability;
use App\Models\ScheduleSession;
use App\Models\SessionRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Date;

class TimetableSV extends BaseService
{
    protected $timetable = [];
    protected $roomAvailability = [];
    protected $lecturerAvailability = [];

    protected function getQuery()
    {
        return Timetables::query();
    }

    public function getAllTimetables(array $params = []): mixed
    {
        return $this->getAll($params);
    }

    public function getTimetableById(string $id): mixed
    {
        return $this->getByGlobalId($id, $this->getQuery());
    }

    public function generateTimetable($params): array
    {
        $year = $params['generation'];
        $term = $params['term'];
        $courseIds = $params['courses'];
        $startDate = $params['start_date'];

        // Format start date
        $startDate = Date::createFromFormat('Y-m-d', $startDate)->format('Y-m-d');

        // Get only selected courses
        $selectedCourses = Course::whereIn('id', $courseIds)->get();

        // Initialize availabilities
        $rooms = Room::get();
        $lecturerAvailabilities = LecturerAvailability::all()->groupBy('lecturer_id');
        $this->initializeAvailability($rooms, $lecturerAvailabilities);

        // Fallback to all users with role "user" if no lecturer availability
        if (empty($this->lecturerAvailability)) {
            $lecturers = User::where('role', 'user')->get();
        }

        $this->timetable = [];

        $studentGroups = StudentGroup::where('generation_year', '=', $year)
            ->get();

        $sessionTypes = SessionType::all();

        // Create one timetable per group
        foreach ($studentGroups as $group) {
            $timetable = Timetables::create([
                'id' => Str::uuid(),
                'student_group_id' => $group->id,
                'year' => $year,
                'term' => $term,
                'start_date' => $startDate,
            ]);

            // Schedule all selected courses for this timetable
            foreach ($selectedCourses as $course) {
                foreach ($sessionTypes as $sessionType) {
                    $this->scheduleSession($course, $sessionType, $timetable);
                }
            }
        }

        return $this->timetable;
    }

    // Modified to remove $group parameter since it's accessible via timetable
    protected function scheduleSession($course, $sessionType, $timetable): void
    {
        // Try scheduling on each day of the week
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

        foreach ($days as $day) {
            // Check daily session limit
            $existingSessions = ScheduleSession::where('timetable_id', $timetable->id)
                ->where('day', $day)
                ->count();

            if ($existingSessions >= 4) {
                continue; // Skip this day, already at max sessions
            }

            // Find available timeslot
            $availableSlot = $this->findAvailableTimeSlotForDay($timetable->id, $day);
            if ($availableSlot) {
                $startTime = $availableSlot;
                $endTime = $startTime + 1.5; // 1.5 hours (90 minutes)

                // Format times for database check
                $formattedStartTime = $this->formatTime($startTime);
                $formattedEndTime = $this->formatTime($endTime);

                // Find an available room by checking for existing bookings
                $room = $this->findAvailableRoomForTimeSlot($day, $formattedStartTime, $formattedEndTime);

                $courseUser = CourseUser::where('course_id', $course->id)->get();
                $courseUserId = $courseUser->random()->id;
                // Create session with course_user_id
                ScheduleSession::create([
                    'id' => Str::uuid(),
                    'timetable_id' => $timetable->id,
                    'course_user_id' => $courseUserId,
                    'room_id' => $room->id,
                    'day' => $day,
                    'start_time' => $formattedStartTime,
                    'end_time' => $formattedEndTime,
                    'status' => 'approved',
                    'session_type_id' => $sessionType->id,
                ]);
                return; // Successfully scheduled
            }
        }
    }

    /**
     * Find a room that isn't already booked during this time slot
     */
    protected function findAvailableRoomForTimeSlot($day, $startTime, $endTime): ?Room
    {
        // Get all active rooms
        $availableRooms = Room::where('is_active', true)
            ->whereDoesntHave('scheduleSessions', function ($query) use ($day, $startTime, $endTime) {
                $query->where('day', $day)
                    ->where(function ($q) use ($startTime, $endTime) {
                        // Check for overlapping sessions
                        $q->where(function ($inner) use ($startTime, $endTime) {
                            $inner->where('start_time', '<', $endTime)
                                ->where('end_time', '>', $startTime);
                        });
                    });
            })
            ->inRandomOrder()
            ->first();

        return $availableRooms;
    }

    // Simplified to use timetable_id directly
    protected function findAvailableTimeSlotForDay($timetableId, $day): ?float
    {
        $existingSessions = ScheduleSession::where('timetable_id', $timetableId)
            ->where('day', $day)
            ->get();

        // Standard time slots with 15-minute breaks
        $possibleSlots = [
            8.5,   // 08:30 - 10:00
            10.25, // 10:15 - 11:45
            13.0,  // 13:00 - 14:30
            14.75  // 14:45 - 16:15
        ];

        // Filter out slots that are already taken
        foreach ($existingSessions as $session) {
            $sessionStart = $this->timeToDecimal($session->start_time);
            $key = array_search($sessionStart, $possibleSlots);
            if ($key !== false) {
                unset($possibleSlots[$key]);
            }
        }

        return !empty($possibleSlots) ? reset($possibleSlots) : null;
    }

    protected function initializeAvailability($rooms, $lecturerAvailabilities): array
    {
        // Initialize room availability
        foreach ($rooms as $room) {
            $this->roomAvailability[$room->id] = array_fill(0, 7, array_fill(0, 24, true));
        }

        // Initialize lecturer availability
        if (count($lecturerAvailabilities) > 0) {
            foreach ($lecturerAvailabilities as $lecturerId => $availabilities) {
                foreach ($availabilities as $availability) {
                    $dayIndex = $this->getDayIndex($availability->day);
                    for ($hour = $availability->start_time; $hour < $availability->end_time; $hour++) {
                        $this->lecturerAvailability[$lecturerId][$dayIndex][$hour] = true;
                    }
                }
            }
            return $this->lecturerAvailability;
        } else {
            return [];
        }
    }

    protected function getDayIndex($day): int
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        return array_search(strtolower($day), $days);
    }

    protected function timeToDecimal($time): float
    {
        if (is_string($time)) {
            list($hours, $minutes) = explode(':', $time);
            return (float)$hours + ((float)$minutes / 60);
        }
        return $time;
    }

    protected function formatTime($decimalTime): string
    {
        $hours = floor($decimalTime);
        $minutes = round(($decimalTime - $hours) * 60);
        return sprintf('%02d:%02d:00', $hours, $minutes);
    }

    protected function calculateSessionDuration($course, $sessionType): int
    {
        // Logic to calculate duration based on course credits and session type
        // For example: Lectures might be 2 hours, labs 3 hours, etc.
        if ($sessionType->name === 'Lecture') {
            return min(2, $course->credit);
        } elseif ($sessionType->name === 'Lab') {
            return min(3, $course->credit * 1.5);
        }
        return 1; // Default 1 hour
    }

    protected function logConflict($groupId, $courseId, $sessionTypeId, $reason): void
    {
        SessionRequest::create([
            'id' => Str::uuid(),
            'student_group_id' => $groupId,
            'course_id' => $courseId,
            'session_type_id' => $sessionTypeId,
            'reason' => $reason,
            'status' => 'unresolved',
        ]);
    }

    public function resolveConflict($conflictId, $newRoomId = null, $newLecturerId = null, $newTimeSlot = null): void
    {
        $conflict = SessionRequest::findOrFail($conflictId);

        $group = StudentGroup::find($conflict->student_group_id);
        $course = Course::find($conflict->course_id);
        $sessionType = SessionType::find($conflict->session_type_id);

        $room = $newRoomId ? Room::find($newRoomId) : Room::where('is_active', '=', '1')->inRandomOrder()->first();
        $lecturerId = $newLecturerId ?? User::where('role', 'user')
            ->whereHas('lecturerAvailability', function ($query) use ($room, $conflict) {
                $query->where('day', 'monday')
                    ->where('start_time', '<=', $conflict->new_start_time)
                    ->where('end_time', '>=', $conflict->new_end_time);
            })
            ->inRandomOrder()
            ->first();
        $timeSlot = $newTimeSlot ?? $this->findAvailableTimeSlot($room, $lecturerId);

        if ($room && $lecturerId && $timeSlot !== null) {
            ScheduleSession::create([
                'id' => Str::uuid(),
                'session_type_id' => $sessionType->id,
                'course_id' => $course->id,
                'timetable_id' => $conflict->timetable_id,
                'room_id' => $room->id,
                'start_time' => sprintf('%02d:00:00', $timeSlot),
                'end_time' => sprintf('%02d:00:00', $timeSlot + 1),
                'day' => 'monday',
                'status' => 'approved',
            ]);

            $this->updateRoomAvailability($room);
            $conflict->update(['status' => 'resolved']);
        } else {
            throw new Exception('Unable to resolve conflict');
        }
    }

    protected function findAvailableTimeSlot($room, $lecturerId): ?int
    {
        for ($day = 0; $day < 7; $day++) {
            for ($hour = 9; $hour < 17; $hour++) {
                if (
                    $this->roomAvailability[$room->id][$day][$hour] ?? false &&
                    $this->lecturerAvailability[$lecturerId][$day][$hour] ?? false
                ) {
                    return $hour;
                }
            }
        }
        return null;
    }

    protected function updateRoomAvailability($room): void
    {
        Room::where('id', '=', $room->id)
            ->update(['is_active' => false]);
    }


    protected function isRoomAvailable($roomId, $startTime, $endTime): bool
    {
        $dayIndex = 0; // Assuming Monday for simplicity
        for ($hour = $startTime; $hour < $endTime; $hour++) {
            if (!($this->roomAvailability[$roomId][$dayIndex][$hour] ?? false)) {
                return false;
            }
        }
        return true;
    }
}
