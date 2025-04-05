<?php

namespace App\Services;

use App\Models\Timetables;
use App\Models\Course;
use App\Models\Room;
use App\Models\SessionType;
use App\Models\StudentGroup;
use App\Models\LecturerAvailability;
use App\Models\ScheduleSession;
use App\Models\SessionRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Exception;

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

    public function generateTimetable($year, $term): array
    {
        $studentGroups = StudentGroup::with('courses.sessionTypes')->get();
        $rooms = Room::active()->get();
        $lecturerAvailabilities = LecturerAvailability::all()->groupBy('lecturer_id');

        $this->initializeAvailability($rooms, $lecturerAvailabilities);

        foreach ($studentGroups as $group) {
            $timetable = Timetables::create([
                'id' => Str::uuid(),
                'student_group_id' => $group->id,
                'year' => $year,
                'term' => $term,
                'start_date' => now(),
            ]);

            foreach ($group->courses as $course) {
                foreach ($course->sessionTypes as $sessionType) {
                    $this->scheduleSession($group, $course, $sessionType, $timetable);
                }
            }
        }

        return $this->timetable;
    }

    protected function initializeAvailability($rooms, $lecturerAvailabilities): void
    {
        foreach ($rooms as $room) {
            $this->roomAvailability[$room->id] = array_fill(0, 7, array_fill(0, 24, true));
        }

        foreach ($lecturerAvailabilities as $lecturerId => $availabilities) {
            foreach ($availabilities as $availability) {
                $dayIndex = $this->getDayIndex($availability->day);
                for ($hour = $availability->start_time; $hour < $availability->end_time; $hour++) {
                    $this->lecturerAvailability[$lecturerId][$dayIndex][$hour] = true;
                }
            }
        }
    }

    protected function getDayIndex($day): int
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        return array_search(strtolower($day), $days);
    }

    protected function scheduleSession($group, $course, $sessionType, $timetable): void
    {
        $room = $this->findAvailableRoom($sessionType);
        $lecturerId = $this->findAvailableLecturer($course, $sessionType);

        if ($room && $lecturerId) {
            $startTime = $this->findAvailableTimeSlot($room, $lecturerId);

            if ($startTime !== null) {
                $endTime = $startTime + 1;
                $day = 'monday';

                ScheduleSession::create([
                    'id' => Str::uuid(),
                    'session_type_id' => $sessionType->id,
                    'course_id' => $course->id,
                    'timetable_id' => $timetable->id,
                    'room_id' => $room->id,
                    'start_time' => sprintf('%02d:00:00', $startTime),
                    'end_time' => sprintf('%02d:00:00', $endTime),
                    'day' => $day,
                    'status' => 'approved',
                ]);

                $this->updateRoomAvailability($room, $day, $startTime, $endTime);
                $this->updateLecturerAvailability($lecturerId, $day, $startTime, $endTime);
            } else {
                $this->logConflict($group, $course, $sessionType, 'No available time slot');
            }
        } else {
            $reason = !$room ? 'No available room' : 'No available lecturer';
            $this->logConflict($group, $course, $sessionType, $reason);
        }
    }

    protected function logConflict($group, $course, $sessionType, $reason): void
    {
        SessionRequest::create([
            'id' => Str::uuid(),
            'student_group_id' => $group->id,
            'course_id' => $course->id,
            'session_type_id' => $sessionType->id,
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

        $room = $newRoomId ? Room::find($newRoomId) : $this->findAvailableRoom($sessionType);
        $lecturerId = $newLecturerId ? $newLecturerId : $this->findAvailableLecturer($course, $sessionType);
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

            $this->updateRoomAvailability($room, 'monday', $timeSlot, $timeSlot + 1);
            $this->updateLecturerAvailability($lecturerId, 'monday', $timeSlot, $timeSlot + 1);
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

    protected function updateRoomAvailability($room, $day, $startTime, $endTime): void
    {
        $dayIndex = $this->getDayIndex($day);
        for ($hour = $startTime; $hour < $endTime; $hour++) {
            $this->roomAvailability[$room->id][$dayIndex][$hour] = false;
        }
    }

    protected function updateLecturerAvailability($lecturerId, $day, $startTime, $endTime): void
    {
        $dayIndex = $this->getDayIndex($day);
        for ($hour = $startTime; $hour < $endTime; $hour++) {
            $this->lecturerAvailability[$lecturerId][$dayIndex][$hour] = false;
        }
    }

    protected function findAvailableRoom($sessionType): ?Room
    {
        foreach ($this->roomAvailability as $roomId => $availability) {
            $room = Room::where('id', $roomId)->first();
            if ($room && $room->capacity >= $sessionType->required_capacity && $room->has_equipment($sessionType->required_equipment)) {
                return $room;
            }
        }
        return null;
    }

    protected function findAvailableLecturer($course, $sessionType): ?string
    {
        foreach ($this->lecturerAvailability as $lecturerId => $availability) {
            $lecturer = User::find($lecturerId);
            if ($lecturer && $lecturer->canTeach($course) && $lecturer->isAvailableForSessionType($sessionType)) {
                return $lecturerId;
            }
        }
        return null;
    }
}
