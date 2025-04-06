<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
class CourseUser extends Pivot
{
    protected $table = 'course_user';

    protected $fillable = [
        'id',
        'course_id',
        'user_id',// Example: Add any additional fields if they exist in the migration
    ];

    public $timestamps = false; // Set to true if the pivot table includes timestamps

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scheduleSessions()
    {
        return $this->hasMany(ScheduleSession::class, 'course_user_id');
    }
}
