@extends('layouts.responder')

@section('title', 'Operations Stream Alerts — Central Command')
@section('page_title', 'Operational Notifications & Dispatch Stream')
@section('page_subtitle', 'Triage alerts, incident escalations, unit acknowledgements, and system telemetry')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between pb-4 border-b border-[#E2E7E5]">
        <div>
            <h2 class="text-xl font-extrabold text-[#17324D]">
                Command Notifications
            </h2>
            <p class="text-xs text-[#687781]">High-priority situational alerts requiring operational attention</p>
        </div>
        <button type="button" onclick="alert('All operational alerts marked as acknowledged.');" class="btn btn-outline btn-sm text-xs font-semibold">
            Acknowledge All
        </button>
    </div>

    <div class="space-y-3">
        @forelse ($notifications as $notification)
            @php
                $isCritical = str_contains($notification->category, 'Critical');
                $isNew = str_contains($notification->category, 'New');
            @endphp
            <div class="card p-5 bg-white border border-[#E2E7E5] flex items-start gap-4 {{ $isCritical ? 'border-l-4 border-l-[#C73E3E] bg-rose-50/10' : ($isNew ? 'border-l-4 border-l-[#1F8A89]' : '') }}">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $isCritical ? 'bg-rose-100 text-[#C73E3E]' : ($isNew ? 'bg-teal-50 text-[#1F8A89]' : 'bg-slate-100 text-slate-600') }}">
                    @if ($isCritical)
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $isCritical ? 'bg-[#FDF2F2] text-[#C73E3E]' : 'bg-slate-100 text-[#17324D]' }}">
                                {{ $notification->category }}
                            </span>
                            <h4 class="text-sm font-bold text-[#17324D] truncate">
                                {{ $notification->title }}
                            </h4>
                        </div>
                        <span class="text-xs text-[#687781] shrink-0 font-medium">
                            {{ $notification->time_ago }}
                        </span>
                    </div>

                    <p class="text-xs text-[#687781] leading-relaxed mb-2">
                        {{ $notification->message }}
                    </p>

                    @if ($notification->emergency_id)
                        <div class="pt-1">
                            <a href="{{ route('responder.emergencies.show', $notification->emergency_id) }}" class="btn btn-navy btn-sm text-xs font-semibold !py-1">
                                Inspect Emergency #{{ $notification->emergency_id }} &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <x-empty-state 
                title="No active notifications" 
                message="All priority telemetry and triage signals have been reviewed."
            />
        @endforelse
    </div>
</div>
@endsection
