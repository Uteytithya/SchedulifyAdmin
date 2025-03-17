<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\UuidTrait;

class Room extends Model
{
    use HasFactory, UuidTrait;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = ['name', 'floor', 'capacity', 'is_active'];


    public function sessions()
    {
        return $this->hasMany(ScheduleSession::class, 'room_id');
        
    }
}
