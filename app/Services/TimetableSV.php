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

        
        $startDate = Date::createFromFormat('Y-m-d', $startDate)->format('Y-m-d');

        
        $selectedCourses = Course::whereIn('id', $courseIds)->get();

        
        $rooms = Room::get();
        $lecturerAvailabilities = LecturerAvailability::all()->groupBy('lecturer_id');
        $this->initializeAvailability($rooms, $lecturerAvailabilities);

        $this->timetable = [];

        $studentGroups = StudentGroup::where('generation_year', '=', $year)->get();
        $sessionTypes = SessionType::all();

        
        foreach ($studentGroups as $group) {
            
            $courseUserMap = []; 
            $courseDayTrack = []; 

            $timetable = Timetables::create([
                'id' => Str::uuid(),
                'student_group_id' => $group->id,
                'year' => $year,
                'term' => $term,
                'start_date' => $startDate,
            ]);
            $count++;
            
            foreach ($selectedCourses as $course) {
                
                if (!isset($courseUserMap[$course->id])) {
                    $courseUserMap[$course->id] = CourseUser::where('course_id', $course->id)->inRandomOrder()->first();
                }
                $courseUser = $courseUserMap[$course->id];
                foreach ($sessionTypes as $sessionType) {
                    $scheduled = false;

                    
                    foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday'] as $day) {
                        
                        $this->scheduleSession($count, $course, $sessionType, $timetable, $courseUser);

                        
                        $courseDayTrack[$course->id][] = $day;
                        $scheduled = true;
                        break; 
                    }

                    
                    if (!$scheduled) {
                        $this->logConflict($group->id, $course->id, $sessionType->id, 'No available day for scheduling');
                    }
                }
            }

            
            $this->timetable[] = $timetable;
        }

        return $this->timetable;
    }

    protected function scheduleSession($counter, $course, $sessionType, $timetable, $courseUser): void
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        
        $scheduledSessionsCount = ScheduleSession::where('timetable_id', $timetable->id)
            ->where('course_user_id', $courseUser->id)
            ->count();

        
        if ($scheduledSessionsCount >= $course->credit) {
            return;
        }

        if ($course->credit == 1) {
            
            $cosmosHall = Room::where('name', 'like', 'Cosmos Hall')->first();
            if ($cosmosHall) {
                
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
                ScheduleSession::create([
                    'id' => Str::uuid(),
                    'timetable_id' => $timetable->id,
                    'course_user_id' => $courseUser->id,
                    'room_id' => $cosmosHall->id,
                    'day' => $fixedSlot['day'],
                    'start_time' => '10:15:00',
                    'end_time' => '11:45:00',
                    'status' => 'approved',
                    'session_type_id' => $sessionType->id,
                ]);
                
                $scheduledSessionsCount++;
            } else {
                
                $this->logConflict($timetable->student_group_id, $course->id, $sessionType->id, 'Cosmos Hall not available');
            }
            
            return;
        }

        
        $isTheoryScheduled = false;
        foreach ($days as $day) {
            if ($day === 'wednesday') {
                continue;
            }
            
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

    
    protected function findAvailableTimeSlotForDay($timetableId, $day): ?float
    {
        $existingSessions = ScheduleSession::where('timetable_id', $timetableId)
            ->where('day', $day)
            ->get();

        
        $possibleSlots = [
            8.5,   
            10.25, 
            13.0,  
            14.75  
        ];

        
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
        
        foreach ($rooms as $room) {
            $this->roomAvailability[$room->id] = array_fill(0, 7, array_fill(0, 24, true));
        }

        
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
