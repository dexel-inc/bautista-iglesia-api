<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $type
 * @property string $title
 * @property string $description
 * @property string $image
 */
class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'description',
        'image'
    ];
}
