<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $message
 * @property string $image
 * @property string $contact_email
 * @property string $contact_name
 * @property string $disable_at
 * @property ?integer $order
 */
class Missionary extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'image',
        'contact_name',
        'contact_email',
        'order',
        'disable_at'
    ];

    protected $casts = [
        'disable_at' => 'datetime',
    ];
}
