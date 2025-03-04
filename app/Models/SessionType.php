<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SessionType extends Model
{
    use HasFactory, UuidTrait;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'created_at'];

    public function sessions()
    {
        return $this->hasMany(ScheduleSession::class, 'session_type_id');
    }
}
