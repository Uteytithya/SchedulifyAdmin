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

    public function timetables()
    {
        return $this->hasMany(Timetables::class, 'group_id');
    }
}
