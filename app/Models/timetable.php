<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\StudentGroup;

class timetable extends Model
{
    use HasFactory, UuidTrait;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $fillable = ['group_id', 'year', 'start_date', 'created_at'];

    public function studentGroup()
    {
        return $this->belongsTo(StudentGroup::class, 'group_id');
    }

    public function sessions()
    {
        return $this->hasMany(ScheduleSession::class, 'timetable_id');
    }

    public function requests()
    {
        return $this->hasMany(SessionRequest::class, 'timetable_id');
    }
}
