<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LecturerAvailability extends Model
{
    use HasFactory, UuidTrait;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'day', 'start_time', 'end_time', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
