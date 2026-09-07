@extends('layouts.citizen')

@section('title', 'Notifications — Citizen Portal')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between pb-4 border-b border-[#E2E7E5]">
        <div>
            <h1 class="text-2xl font-extrabold text-[#17324D] tracking-tight">
                Notifications
            </h1>
            <p class="text-xs sm:text-sm text-[#687781] mt-1">
                Updates regarding your reported incidents, triage statuses, and unit dispatches.
            </p>
        </div>
        <button type="button" onclick="alert('All notifications marked as read.');" class="btn btn-outline btn-sm text-xs">
            Mark all as read
        </button>
    </div>

    <div class="space-y-3">
        @forelse ($notifications as $notification)
            <div class="card p-5 bg-white border border-[#E2E7E5] flex items-start gap-4 {{ !$notification->is_read ? 'border-l-4 border-l-[#1F8A89]' : '' }}">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ !$notification->is_read ? 'bg-teal-50 text-[#1F8A89]' : 'bg-slate-100 text-slate-500' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <h4 class="text-sm font-bold text-[#17324D] truncate">
                            {{ $notification->title }}
                        </h4>
                        <span class="text-xs text-[#687781] shrink-0 font-medium">
                            {{ $notification->time_ago }}
                        </span>
                    </div>
                    <p class="text-xs text-[#687781] leading-relaxed mb-2">
                        {{ $notification->message }}
                    </p>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-semibold">
                            {{ $notification->type }}
                        </span>
                        @if ($notification->report_id)
                            <a href="{{ route('citizen.reports.show', $notification->report_id) }}" class="font-bold text-[#1F8A89] hover:underline">
                                View Report #{{ $notification->report_id }} &rarr;
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <x-empty-state 
                title="No notifications" 
                message="You are completely caught up! New incident updates will appear here."
            />
        @endforelse
    </div>
</div>
@endsection
