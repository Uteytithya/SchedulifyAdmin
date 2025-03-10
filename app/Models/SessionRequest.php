<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\UuidTrait;

class SessionRequest extends Model
{
    use HasFactory, UuidTrait;

    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'session_type_id' => 'string',
        'course_id' => 'string',
        'timetable_id' => 'string',
        'requested_date' => 'date',
        'new_start_time' => 'datetime:H:i:s',
        'new_end_time' => 'datetime:H:i:s',
        'reason' => 'string',
        'status' => 'string',
        'request_type' => 'string',
        'created_at' => 'datetime',
    ];
    protected $fillable = ['user_id', 'session_type_id', 'course_id', 'timetable_id', 'requested_date', 'new_start_time', 'new_end_time', 'reason', 'request_type', 'status', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sessionType()
    {
        return $this->belongsTo(SessionType::class, 'session_type_id');
    }

    public function timetable()
    {
        return $this->belongsTo(Timetables::class, 'timetable_id');
    }
}
