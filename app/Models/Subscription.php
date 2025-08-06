<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $email
 */
class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'disabled_at'
    ];

    protected $casts = [
        'disabled_at' => 'datetime',
    ];

    public function isEnabled(): bool
    {
        return is_null($this->disabled_at);
    }
}
