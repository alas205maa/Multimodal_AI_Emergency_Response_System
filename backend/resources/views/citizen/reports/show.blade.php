@extends('layouts.citizen')

@section('title', 'Emergency Report #' . $report->id . ' — Details & Tracking')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    <!-- Breadcrumb & Back Link -->
    <div class="flex items-center justify-between">
        <a href="{{ route('citizen.reports.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#1F8A89] hover:underline">
            &larr; Back to My Reports
        </a>
        <span class="text-xs text-[#687781]">Report submitted {{ $report->time_ago }}</span>
    </div>

    <!-- Header Card: Emergency Report #[ID] -->
    <div class="card p-6 sm:p-8 bg-white border border-[#E2E7E5]">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[#E2E7E5]">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-[#17324D] font-mono">
                        Emergency Report #{{ $report->id }}
                    </h1>
                </div>
                <p class="text-sm font-semibold text-[#243746]">
                    {{ $report->emergency_type ?? 'Unclassified Incident' }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <x-risk-badge :risk="$report->risk_level ?? 'Medium'" />
                <x-status-badge :status="$report->status ?? 'Received'" />
            </div>
        </div>

        <!-- Visual Status Timeline: Submitted -> Received -> Analyzing -> Assigned -> In Progress -> Resolved -->
        <div class="pt-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#687781] mb-4">
                Operational Lifecycle Timeline
            </h3>

            @php
                $stages = ['Submitted', 'Received', 'Analyzing', 'Assigned', 'In Progress', 'Resolved'];
                $currentStatus = $report->status ?? 'Received';
                $currentIndex = array_search($currentStatus, $stages);
                if ($currentIndex === false) {
                    $currentIndex = 1; // default to received
                }
            @endphp

            @if (strtolower($currentStatus) === 'cancelled')
                <div class="p-4 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 text-sm flex items-center gap-2">
                    <span class="font-bold text-[#C73E3E]">Notice:</span> This emergency report was cancelled.
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-6 gap-2 text-center">
                    @foreach ($stages as $index => $stage)
                        @php
                            $isCompleted = $index < $currentIndex;
                            $isActive = $index === $currentIndex;
                            $isUpcoming = $index > $currentIndex;
                        @endphp
                        <div class="flex flex-col items-center p-2 rounded-lg {{ $isActive ? 'bg-amber-50/70 border border-amber-200' : ($isCompleted ? 'bg-emerald-50/50' : 'opacity-60') }}">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold mb-1.5 {{ $isCompleted ? 'bg-[#3A9D6E] text-white' : ($isActive ? 'bg-[#F05D5E] text-white' : 'bg-slate-200 text-slate-600') }}">
                                @if ($isCompleted)
                                    ✓
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </div>
                            <span class="text-[11px] font-bold {{ $isActive ? 'text-[#F05D5E]' : ($isCompleted ? 'text-[#3A9D6E]' : 'text-[#687781]') }}">
                                {{ $stage }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left 2 Cols: Citizen Input & Location -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Citizen Input Section -->
            <div class="card p-6 bg-white border border-[#E2E7E5]">
                <h2 class="text-base font-bold text-[#17324D] mb-4 pb-2 border-b border-[#E2E7E5] flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#1F8A89]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Citizen Input Details
                </h2>

                <div class="space-y-4">
                    <div>
                        <span class="text-xs font-semibold text-[#687781] block mb-1">Witness Description:</span>
                        <div class="p-4 rounded-xl bg-[#F7F8F6] border border-[#E2E7E5] text-sm text-[#243746] leading-relaxed">
                            {{ $report->description }}
                        </div>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-[#687781] block mb-1">Uploaded Image Evidence:</span>
                        @if ($report->image)
                            <div class="rounded-xl overflow-hidden border border-[#E2E7E5] max-w-md">
                                <img src="{{ asset('storage/' . $report->image) }}" alt="Report evidence image" class="w-full h-56 object-cover">
                            </div>
                        @else
                            <div class="p-4 rounded-xl bg-[#F7F8F6] border border-[#E2E7E5] text-xs text-[#687781] flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>No photograph was attached to this submission.</span>
                            </div>
                        @endif
                    </div>

                    <div class="text-xs text-[#687781] pt-2">
                        <span>Submitted on: <strong>{{ $report->formatted_date ?? $report->time_ago }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Location Section -->
            <div class="card p-6 bg-white border border-[#E2E7E5]">
                <h2 class="text-base font-bold text-[#17324D] mb-4 pb-2 border-b border-[#E2E7E5] flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#F05D5E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    Incident Location
                </h2>

                <!-- Map Placeholder -->
                <div class="map-canvas-placeholder h-52 rounded-xl border border-[#E2E7E5] mb-4 flex flex-col items-center justify-center text-center p-4">
                    <div class="w-10 h-10 rounded-full bg-[#17324D] text-white flex items-center justify-center shadow-md mb-2">
                        <svg class="w-5 h-5 text-[#F05D5E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span class="text-xs font-bold text-[#17324D] block font-mono">
                        {{ $report->latitude }}, {{ $report->longitude }}
                    </span>
                    <span class="text-xs text-[#687781] block mt-1">
                        {{ $report->location_name ?? 'Reported Coordinates' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs font-mono">
                    <div class="p-2.5 rounded-lg bg-[#F7F8F6] border border-[#E2E7E5]">
                        <span class="text-[#687781] block">Latitude:</span>
                        <strong class="text-[#17324D]">{{ $report->latitude }}</strong>
                    </div>
                    <div class="p-2.5 rounded-lg bg-[#F7F8F6] border border-[#E2E7E5]">
                        <span class="text-[#687781] block">Longitude:</span>
                        <strong class="text-[#17324D]">{{ $report->longitude }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right 1 Col: AI Analysis Card -->
        <div class="space-y-6">
            <!-- AI Analysis UI Card -->
            <div class="card p-6 bg-white border border-teal-200 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between pb-3 border-b border-teal-100 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                        <h3 class="text-sm font-bold text-[#17324D] uppercase tracking-wider">
                            AI Multimodal Analysis
                        </h3>
                    </div>
                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-teal-50 text-teal-700 border border-teal-200">
                        S1/S2/S3 Ready
                    </span>
                </div>

                <p class="text-xs text-[#687781] mb-5 leading-relaxed">
                    Automated computational triage output synthesized from cross-modal hazard analysis.
                </p>

                <div class="space-y-4">
                    <div>
                        <span class="text-xs font-semibold text-[#687781] block mb-1">Emergency Type</span>
                        <span class="text-sm font-extrabold text-[#17324D] block">
                            {{ $report->emergency_type ?? 'Analyzing...' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 rounded-lg bg-[#F7F8F6] border border-[#E2E7E5]">
                            <span class="text-[10px] font-semibold text-[#687781] block">Final Score</span>
                            <span class="text-lg font-black text-[#C73E3E] font-mono">
                                {{ $report->final_score ?? 'N/A' }} <span class="text-xs font-normal text-slate-400">/ 10</span>
                            </span>
                        </div>
                        <div class="p-3 rounded-lg bg-[#F7F8F6] border border-[#E2E7E5]">
                            <span class="text-[10px] font-semibold text-[#687781] block">Confidence</span>
                            <span class="text-lg font-black text-[#1F8A89] font-mono">
                                {{ $report->confidence ?? 0 }}%
                            </span>
                        </div>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-[#687781] block mb-1">Risk Level</span>
                        <x-risk-badge :risk="$report->risk_level ?? 'Medium'" />
                    </div>

                    <div class="pt-2">
                        <span class="text-xs font-semibold text-[#687781] block mb-1">Final Decision</span>
                        <div class="p-3 rounded-lg bg-teal-50/60 border border-teal-200 text-xs font-bold text-teal-900 leading-snug">
                            {{ $report->final_decision ?? 'Standard Dispatch Protocol Active' }}
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-[#E2E7E5] text-[11px] text-slate-400 italic">
                    AI values will update automatically as future backend neural inference engines (S1, S2, S3) are integrated.
                </div>
            </div>

            <!-- Need Immediate Help Card -->
            <div class="card p-6 bg-[#17324D] text-white">
                <h4 class="text-sm font-bold mb-2">Condition Escalating?</h4>
                <p class="text-xs text-white/75 mb-4 leading-relaxed">
                    If life danger or fire spread accelerates at your scene, notify dispatch immediately.
                </p>
                <a href="tel:911" class="btn btn-primary btn-sm w-full font-bold">
                    Call National Dispatch (911)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
