<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\UuidTrait;

class StudentGroup extends Model
{
    use HasFactory, UuidTrait;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'generation_year', 'department', 'created_at'];
    protected $table = 'students_groups'; // Explicitly set the table name

    public function timetable()
    {
        return $this->hasOne(Timetables::class, 'student_group_id');
    }

    public function courseUser()
    {
        return $this->hasMany(CourseUser::class, 'student_group_id');
    }
}
