<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScheduleSession extends Model
{
    use HasFactory, UuidTrait;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $fillable = ['timetable_id', 'course_user_id', 'room_id', 'day', 'start_time', 'end_time', 'status', 'session_type_id', 'created_at'];

    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function sessionType()
    {
        return $this->belongsTo(SessionType::class, 'session_type_id');
    }
}
