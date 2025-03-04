<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentGroup extends Model
{
    use HasFactory, UuidTrait;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'generation_year', 'department', 'created_at'];

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'group_id');
    }
}
