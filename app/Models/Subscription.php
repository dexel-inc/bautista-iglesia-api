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

    public function isDisabled(): bool
    {
        return !is_null($this->disabled_at);
    }

    public function isEnabled(): bool
    {
        return is_null($this->disabled_at);
    }

    public function disable(): void
    {
        $this->disabled_at = now();
        $this->save();
    }

    public function enable(): void
    {
        $this->disabled_at = null;
        $this->save();
    }

    public function toggle(): void
    {
        if ($this->isDisabled()) {
            $this->enable();
        } else {
            $this->disable();
        }
    }
}
