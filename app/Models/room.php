<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class room extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'floor', 'capacity', 'is_active', 'created_at'];

    public function sessions()
    {
        return $this->hasMany(ScheduleSession::class, 'room_id');
    }
}
