<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Room extends Model
{
    protected $fillable = [
        'title',
        'description',
        'room_code',
        'is_public',
        'allow_anonymous',
        'category',
        'color_badge',
        'expires_at',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'allow_anonymous' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function pendingSubmissions(): HasMany
    {
        return $this->hasMany(Submission::class)->where('status', 'pending');
    }

    public function approvedSubmissions(): HasMany
    {
        return $this->hasMany(Submission::class)->where('status', 'approved');
    }

    public function getParticipantUrl(): string
    {
        return url('/r/' . $this->room_code);
    }

    public function getAdminUrl(): string
    {
        return url('/r/' . $this->room_code . '/admin');
    }
}
