<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SessionRequest extends Model
{
    use HasFactory, UuidTrait;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'session_type_id', 'course_id', 'timetable_id', 'requested_date', 'new_start_time', 'new_end_time', 'reason', 'status', 'created_at'];

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
        return $this->belongsTo(Timetable::class, 'timetable_id');
    }
}
