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
        $count = 1;
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

        $this->timetable = [];

        $studentGroups = StudentGroup::where('generation_year', '=', $year)->get();
        $sessionTypes = SessionType::all();

        // Create one timetable per group
        foreach ($studentGroups as $group) {
            // Reset group-specific variables
            $courseUserMap = []; // Map to track assigned users for each course
            $courseDayTrack = []; // Track courses scheduled for specific days

            $timetable = Timetables::create([
                'id' => Str::uuid(),
                'student_group_id' => $group->id,
                'year' => $year,
                'term' => $term,
                'start_date' => $startDate,
            ]);
            $count++;
            // Schedule all selected courses for this timetable
            foreach ($selectedCourses as $course) {
                // Assign a user to the course if not already assigned
                if (!isset($courseUserMap[$course->id])) {
                    $courseUserMap[$course->id] = CourseUser::where('course_id', $course->id)->inRandomOrder()->first();
                }
                $courseUser = $courseUserMap[$course->id];
                foreach ($sessionTypes as $sessionType) {
                    $scheduled = false;

                    // Iterate over days to find a valid day for scheduling
                    foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday'] as $day) {
                        // Schedule the session
                        $this->scheduleSession($count, $course, $sessionType, $timetable, $courseUser);

                        // Track the day for this course
                        $courseDayTrack[$course->id][] = $day;
                        $scheduled = true;
                        break; // Stop once the course is scheduled for a valid day
                    }

                    // If the course couldn't be scheduled, log a conflict
                    if (!$scheduled) {
                        $this->logConflict($group->id, $course->id, $sessionType->id, 'No available day for scheduling');
                    }
                }
            }

            // Add the generated timetable to the list
            $this->timetable[] = $timetable;
        }

        return $this->timetable;
    }

    protected function scheduleSession($counter, $course, $sessionType, $timetable, $courseUser): void
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        // Get the current count for this course (this should be scoped by timetable)
        $scheduledSessionsCount = ScheduleSession::where('timetable_id', $timetable->id)
            ->where('course_user_id', $courseUser->id)
            ->count();

        // If required sessions already scheduled, do nothing.
        if ($scheduledSessionsCount >= $course->credit) {
            return;
        }

        if ($course->credit == 1) {
            // For 1-credit courses, always schedule a session in Cosmos Hall.
            $cosmosHall = Room::where('name', 'like', 'Cosmos Hall')->first();
            if ($cosmosHall) {
                // Choose a fixed slot. You may decide to always use the first available slot.
                $fixedSlot = ['day' => 'wednesday', 'start_time' => '08:30:00', 'end_time' => '10:00:00'];
                ScheduleSession::create([
                    'id' => Str::uuid(),
                    'timetable_id' => $timetable->id,
                    'course_user_id' => $courseUser->id,
                    'room_id' => $cosmosHall->id,
                    'day' => $fixedSlot['day'],
                    'start_time' => $fixedSlot['start_time'],
                    'end_time' => $fixedSlot['end_time'],
                    'status' => 'approved',
                    'session_type_id' => $sessionType->id,
                ]);
                // Increment the counter so that the course is marked as scheduled for this timetable.
                $scheduledSessionsCount++;
            } else {
                // Optionally log a conflict or handle the situation if Cosmos Hall doesn't exist.
                $this->logConflict($timetable->student_group_id, $course->id, $sessionType->id, 'Cosmos Hall not available');
            }
            // Always return here so that the fixed slot logic for 1-credit courses does not continue.
            return;
        }

        // For 2- or 3-credit courses:
        $isTheoryScheduled = false;
        foreach ($days as $day) {
            if ($day === 'wednesday') {
                continue;
            }
            // Check if this day already has many sessions in the current timetable.
            $existingSessions = ScheduleSession::where('timetable_id', $timetable->id)
                ->where('day', $day)
                ->count();
            if ($existingSessions >= 4) {
                continue;
            }

            $availableSlot = $this->findAvailableTimeSlotForDay($timetable->id, $day);
            if (!$availableSlot) {
                continue;
            }

            $startTime = $availableSlot;
            $endTime = $startTime + 1.5;
            $formattedStartTime = $this->formatTime($startTime);
            $formattedEndTime = $this->formatTime($endTime);

            $room = $this->findAvailableRoomForTimeSlot($timetable->id, $day, $formattedStartTime, $formattedEndTime);
            if (!$room) {
                continue;
            }

            $currentSessionType = 'Lab';
            if ($course->credit == 3 && !$isTheoryScheduled) {
                $currentSessionType = 'Theory';
                $isTheoryScheduled = true;
            }

            ScheduleSession::create([
                'id' => Str::uuid(),
                'timetable_id' => $timetable->id,
                'course_user_id' => $courseUser->id,
                'room_id' => $room->id,
                'day' => $day,
                'start_time' => $formattedStartTime,
                'end_time' => $formattedEndTime,
                'status' => 'approved',
                'session_type_id' => SessionType::where('name', $currentSessionType)->first()->id,
            ]);
            $scheduledSessionsCount++;

            if ($scheduledSessionsCount >= $course->credit) {
                // Instead of returning immediately, you might log the fact and break out of day loop
                break;
            }
        }
    }

    /**
     * Modified to scope the search to the current timetable
     */
    protected function findAvailableRoomForTimeSlot($timetableId, $day, $startTime, $endTime): ?Room
    {
        $availableRooms = Room::where('is_active', true)
            ->where(function ($query) use ($timetableId, $day, $startTime, $endTime) {
                // Always allow Cosmos Hall regardless of existing sessions.
                $query->whereRaw("`name` COLLATE utf8mb4_unicode_ci like ?", ['Cosmos Hall'])
                    ->orWhereDoesntHave('scheduleSessions', function ($q) use ($timetableId, $day, $startTime, $endTime) {
                        $q->where('timetable_id', $timetableId)
                            ->where('day', $day)
                            ->where(function ($subQ) use ($startTime, $endTime) {
                                $subQ->where('start_time', '<', $endTime)
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
}
