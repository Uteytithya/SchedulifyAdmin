<?php

namespace App\Services;

use App\Models\Timetables;
use App\Models\Course;
use App\Models\Room;
use App\Models\SessionType;
use App\Models\StudentGroup;
use App\Models\LecturerAvailability;
use App\Models\ScheduleSession;
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

    public function getAllTimetables(array $params = array())
    {
        try {
            $timetable = $this->getAll($params);
            return $timetable;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function getTimetableById(String $id)
    {
        try {
            $timetable = $this->getByGlobalId($id, $this->getQuery());
            return $timetable;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }


    public function generateTimetable($year, $term)
    {
        // Step 1: Fetch data
        $studentGroups = StudentGroup::all();
        $courses = Course::all();
        $sessionTypes = SessionType::all();
        $rooms = Room::where('is_active', true)->get();
        $lecturerAvailabilities = LecturerAvailability::all();

        // Step 2: Initialize availability
        $this->initializeAvailability($rooms, $lecturerAvailabilities);

        // Step 3: Generate timetable for each student group
        foreach ($studentGroups as $group) {
            $timetable = Timetables::create([
                'id' => Str::uuid(),
                'student_group_id' => $group->id,
                'year' => $year,
                'term' => $term,
                'start_date' => now(),
            ]);

            foreach ($courses as $course) {
                foreach ($sessionTypes as $sessionType) {
                    $this->scheduleSession($group, $course, $sessionType, $timetable);
                }
            }
        }

        return $this->timetable;
    }

    protected function initializeAvailability($rooms, $lecturerAvailabilities)
    {
        // Initialize room availability
        foreach ($rooms as $room) {
            $this->roomAvailability[$room->id] = [];
        }

        // Initialize lecturer availability
        foreach ($lecturerAvailabilities as $availability) {
            $this->lecturerAvailability[$availability->lecturer_id][$availability->day][] = [
                'start_time' => $availability->start_time,
                'end_time' => $availability->end_time,
            ];
        }
    }

    protected function scheduleSession($group, $course, $sessionType, $timetable)
    {
        // Find an available room and lecturer
        $room = $this->findAvailableRoom();
        $lecturer = $this->findAvailableLecturer($course);

        if ($room && $lecturer) {
            // Schedule the session
            ScheduleSession::create([
                'id' => Str::uuid(),
                'session_type_id' => $sessionType->id,
                'course_id' => $course->id,
                'timetable_id' => $timetable->id,
                'room_id' => $room->id,
                'start_time' => '09:00:00', // Example start time
                'end_time' => '10:00:00', // Example end time
                'day' => 'monday', // Example day
                'status' => 'approved',
            ]);

            // Update availability
            $this->updateRoomAvailability($room);
            $this->updateLecturerAvailability($lecturer);
        }
    }

    protected function findAvailableRoom()
    {
        // Example logic to find an available room
        foreach ($this->roomAvailability as $roomId => $availability) {
            if (empty($availability)) {
                return Room::find($roomId);
            }
        }

        return null;
    }

    protected function findAvailableLecturer($course)
    {
        // Example logic to find an available lecturer for the course
        $lecturer = LecturerAvailability::where('course_id', $course->id)->first();
        return $lecturer ? $lecturer->lecturer_id : null;
    }

    protected function updateRoomAvailability($room)
    {
        // Mark the room as unavailable for the scheduled time
        $this->roomAvailability[$room->id][] = ['09:00:00', '10:00:00']; // Example time
    }

    protected function updateLecturerAvailability($lecturer)
    {
        // Mark the lecturer as unavailable for the scheduled time
        // Example logic
    }
}
