<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\UuidTrait;
use App\Models\StudentGroup;

class Timetables extends Model
{
    use HasFactory, UuidTrait;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'student_group_id',
        'year',
        'term',
        'start_date',
    ];

    public function studentGroup()
    {
        return $this->belongsTo(StudentGroup::class, 'student_group_id');
    }

    public function scheduleSessions()
    {
        return $this->hasMany(ScheduleSession::class, 'timetable_id');
    }

    public function requests()
    {
        return $this->hasMany(SessionRequest::class, 'timetable_id');
    }
}
