<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $content
 * @property int $rating
 */
class Testimony extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'rating'
    ];
}
