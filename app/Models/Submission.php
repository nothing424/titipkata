<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    protected $fillable = [
        'room_id',
        'name',
        'content',
        'status',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function getDisplayName(): string
    {
        return $this->name ?: 'Anonim';
    }

    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'pending' => '#FFE066',
            'approved' => '#25D366',
            'rejected' => '#FF7A6B',
            'posted' => '#A8D0F0',
            default => '#FFE066',
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'posted' => 'Diposting',
            default => 'Pending',
        };
    }
}
