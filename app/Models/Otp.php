<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table = 'otps';
    protected $fillable = [
        'id',
        'name',
        'mobile',
        'otp',
        'expires_at',
        'created_at',
        'updated_at'
    ];
}
