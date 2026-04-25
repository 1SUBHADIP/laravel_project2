@extends('layout')

@section('title', 'All Notifications')
@section('breadcrumb', 'Notifications')

@section('content')
<div class="bg-card border border-slate-800 rounded-xl overflow-hidden shadow-sm">
    <div class="p-6 border-b border-slate-800 flex items-center justify-between">
        <h2 class="text-lg font-bold text-white flex items-center gap-2">
            <i class="fas fa-bell text-accent"></i>
            Notifications
        </h2>
        @if($notifications->count() > 0)
            <form action="{{ route('notifications.clear-all') }}" method="POST" class="inline" id="clearAllForm">
                @csrf
                <button type="button" 
                        onclick="if(confirm('Are you sure you want to clear all notifications?')) document.getElementById('clearAllForm').submit();"
                        class="px-4 py-2 bg-red-600/10 text-red-400 hover:bg-red-600/20 rounded-lg transition-colors text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-trash-alt"></i>
                    Clear All
                </button>
            </form>
        @endif
    </div>

    <div class="divide-y divide-slate-800">
        @forelse($notifications as $notification)
            <div class="p-4 sm:p-6 hover:bg-slate-800/30 transition-colors {{ !$notification['read'] ? 'bg-slate-800/10' : '' }}">
                <div class="flex items-start gap-4">
                    <div class="shrink-0 mt-1">
                        <i class="{{ $notification['icon'] }} {{ $notification['color'] }} text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="text-base font-semibold text-white">{{ $notification['title'] }}</h3>
                            <span class="text-xs text-slate-400 whitespace-nowrap">{{ $notification['time'] }}</span>
                        </div>
                        <p class="text-slate-300 text-sm mb-3">{{ $notification['message'] }}</p>
                        
                        <div class="flex items-center gap-4">
                            @if($notification['action_url'])
                                <a href="{{ $notification['action_url'] }}" class="text-sm text-primary hover:text-primary-400 transition-colors flex items-center gap-1 font-medium">
                                    <span>View Details</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            @endif
                            
                            @if(!$notification['read'])
                                <form action="{{ route('notifications.mark-read') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $notification['id'] }}">
                                    <button type="submit" class="text-sm text-slate-400 hover:text-white transition-colors flex items-center gap-1">
                                        <i class="fas fa-check"></i>
                                        <span>Mark as read</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @if(!$notification['read'])
                        <div class="w-2 h-2 bg-accent rounded-full shrink-0 mt-2" title="Unread"></div>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bell-slash text-2xl text-slate-500"></i>
                </div>
                <h3 class="text-lg font-medium text-white mb-2">No notifications found</h3>
                <p class="text-slate-400">You're all caught up! There are no notifications to display at this time.</p>
                <div class="mt-6">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition-colors">
                        <i class="fas fa-home"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="p-4 border-t border-slate-800">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
