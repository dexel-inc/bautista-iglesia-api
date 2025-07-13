<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $email
 * @property string $phone
 * @property string $name
 */
class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'phone',
        'name'
    ];
}
