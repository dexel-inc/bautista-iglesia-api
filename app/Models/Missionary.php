<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $message
 * @property string $image
 * @property string $disable_at
 */
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
