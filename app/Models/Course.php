<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\UuidTrait;

class Course extends Model
{
    use HasFactory, UuidTrait;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'credit'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'course_users', 'course_id', 'user_id');
    }
}
