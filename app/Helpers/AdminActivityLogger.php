<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminActivityLogger
{
    /**
     * Create an admin activity notification
     */
    public static function log($type, $title, $message, $actionUrl = null)
    {
        $notificationId = 'admin_' . uniqid() . '_' . time();

        $icons = [
            'create' => 'fas fa-plus-circle',
            'update' => 'fas fa-edit',
            'delete' => 'fas fa-trash',
            'restore' => 'fas fa-undo',
            'general' => 'fas fa-cog'
        ];

        $colors = [
            'create' => 'text-green-400',
            'update' => 'text-blue-400',
            'delete' => 'text-red-400',
            'restore' => 'text-yellow-400',
            'general' => 'text-slate-400'
        ];

        try {
            DB::table('notifications')->insert([
                'notification_id' => $notificationId,
                'type' => 'info',
                'title' => $title,
                'message' => $message,
                'icon' => $icons[$type] ?? $icons['general'],
                'color' => $colors[$type] ?? $colors['general'],
                'action_url' => $actionUrl,
                'is_read' => false,
                'is_dismissed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the application
            Log::error('Failed to create admin activity notification: ' . $e->getMessage());
        }
    }
}
