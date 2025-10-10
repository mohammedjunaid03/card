<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient_type',
        'recipient_id',
        'title',
        'message',
        'data',
        'type',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
    ];

    protected $attributes = [
        'type' => 'info',
        'is_read' => false,
    ];

    // Relationships
    public function recipient()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, $userType, $userId)
    {
        return $query->where('recipient_type', $userType)
                     ->where('recipient_id', $userId);
    }

    // Helpers
    public function markAsRead()
    {
        $this->is_read = true;
        $this->save();
    }
}
