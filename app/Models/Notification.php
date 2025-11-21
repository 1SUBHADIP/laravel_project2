<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'notification_id',
        'type',
        'title',
        'message',
        'icon',
        'color',
        'action_url',
        'is_read',
        'is_dismissed',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_dismissed' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_dismissed', false);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Helper methods
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function dismiss()
    {
        $this->update(['is_dismissed' => true]);
    }

    public function getTimeAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
