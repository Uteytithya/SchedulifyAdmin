<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\UuidTrait;

class ScheduleSession extends Model
{
    use HasFactory, UuidTrait;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'timetable_id',
        'course_user_id',
        'room_id',
        'day',
        'start_time',
        'end_time',
        'status',
        'session_type_id',
        'created_at'
    ];

    public function timetable()
    {
        return $this->belongsTo(Timetables::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function sessionType()
    {
        return $this->belongsTo(SessionType::class, 'session_type_id');
    }

    public function requests()
    {
        return $this->hasMany(SessionRequest::class, 'session_id');
    }

    public function courseUser()
    {
        return $this->belongsTo(CourseUser::class, 'course_user_id');
    }

    // Add this relationship to get the course through course_user
    public function course()
    {
        return $this->hasOneThrough(
            Course::class,
            CourseUser::class,
            'id', // Foreign key on CourseUser table
            'id', // Foreign key on Course table
            'course_user_id', // Local key on ScheduleSession table
            'course_id' // Local key on CourseUser table
        );
    }

    // Add this relationship to get the lecturer through course_user
    public function lecturer()
    {
        return $this->hasOneThrough(
            User::class,
            CourseUser::class,
            'id', // Foreign key on CourseUser table
            'id', // Foreign key on User table
            'course_user_id', // Local key on ScheduleSession table
            'user_id' // Local key on CourseUser table
        );
    }
}
