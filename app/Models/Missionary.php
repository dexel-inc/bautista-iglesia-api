<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Missionary extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'image',
        'disable_at'
    ];

    protected $casts = [
        'disable_at' => 'datetime',
    ];
}
