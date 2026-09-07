@extends('layouts.citizen')

@section('title', 'Citizen Dashboard — Multimodal AI Emergency Response')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header & Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#17324D] tracking-tight">
                Welcome back, {{ $userName }}
            </h1>
            <p class="text-sm text-[#687781] mt-1">
                Monitor your reported incidents and get immediate assistance when safety hazards occur.
            </p>
        </div>
        <div>
            <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary font-bold shadow-md w-full sm:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Report Emergency
            </a>
        </div>
    </div>

    <!-- Prominent Emergency Assistance Action Card -->
    <div class="card p-6 sm:p-8 bg-gradient-to-r from-[#17324D] to-[#1c3e60] text-white border-none shadow-lg relative overflow-hidden">
        <div class="max-w-xl relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-500/30 text-rose-200 text-xs font-bold uppercase tracking-wider mb-3">
                <span class="w-2 h-2 rounded-full bg-[#F05D5E] animate-ping"></span>
                Urgent Action
            </span>
            <h2 class="text-xl sm:text-2xl font-extrabold text-white mb-2">
                Need Emergency Assistance?
            </h2>
            <p class="text-sm text-white/80 leading-relaxed mb-6">
                Are you witnessing an active fire, collision, medical emergency, or severe infrastructure hazard? Submit an incident report right away.
            </p>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary btn-lg font-bold shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Report Emergency
                </a>
                <span class="text-xs text-white/60">
                    Average automated AI triage under 60 seconds
                </span>
            </div>
        </div>

        <div class="hidden lg:block absolute -right-6 -bottom-8 opacity-10 text-white pointer-events-none">
            <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.5L19.5 19h-15L12 5.5zM11 10v4h2v-4h-2zm0 6v2h2v-2h-2z"/></svg>
        </div>
    </div>

    <!-- Summary Metrics Cards: Total, Pending, In Progress, Resolved -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Total Reports -->
        <div class="card p-5 sm:p-6 bg-white border border-[#E2E7E5]">
            <span class="text-xs font-semibold text-[#687781] block mb-1">Total Reports</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-[#17324D]">{{ $stats['total_reports'] }}</span>
                <span class="w-8 h-8 rounded-lg bg-slate-100 text-[#17324D] flex items-center justify-center text-xs font-bold">
                    All
                </span>
            </div>
        </div>

        <!-- Pending / Received -->
        <div class="card p-5 sm:p-6 bg-white border border-[#E2E7E5]">
            <span class="text-xs font-semibold text-[#687781] block mb-1">Pending Analysis</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-[#1F8A89]">{{ $stats['pending'] }}</span>
                <span class="w-8 h-8 rounded-lg bg-teal-50 text-[#1F8A89] flex items-center justify-center text-xs font-bold">
                    ⏳
                </span>
            </div>
        </div>

        <!-- In Progress -->
        <div class="card p-5 sm:p-6 bg-white border border-[#E2E7E5]">
            <span class="text-xs font-semibold text-[#687781] block mb-1">In Progress</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-[#E06B3C]">{{ $stats['in_progress'] }}</span>
                <span class="w-8 h-8 rounded-lg bg-orange-50 text-[#E06B3C] flex items-center justify-center text-xs font-bold">
                    🚨
                </span>
            </div>
        </div>

        <!-- Resolved -->
        <div class="card p-5 sm:p-6 bg-white border border-[#E2E7E5]">
            <span class="text-xs font-semibold text-[#687781] block mb-1">Resolved</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-[#3A9D6E]">{{ $stats['resolved'] }}</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-[#3A9D6E] flex items-center justify-center text-xs font-bold">
                    ✓
                </span>
            </div>
        </div>
    </div>

    <!-- Recent Reports Section -->
    <div class="card p-6 bg-white border border-[#E2E7E5]">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-[#E2E7E5]">
            <div>
                <h3 class="text-lg font-bold text-[#17324D]">Recent Emergency Reports</h3>
                <p class="text-xs text-[#687781]">Showing your most recently submitted incidents</p>
            </div>
            <a href="{{ route('citizen.reports.index') }}" class="text-xs font-bold text-[#1F8A89] hover:underline">
                View All Reports &rarr;
            </a>
        </div>

        @if ($recentReports->isEmpty())
            <x-empty-state 
                title="No emergency reports yet" 
                message="You have not submitted any incidents. Use the Report Emergency button whenever assistance is needed."
                actionText="Report Emergency"
                actionUrl="{{ route('citizen.reports.create') }}"
            />
        @else
            <!-- Desktop Table View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-[#E2E7E5] text-xs font-bold uppercase text-[#687781] tracking-wider">
                            <th class="py-3 px-3">Report ID</th>
                            <th class="py-3 px-3">Emergency Type</th>
                            <th class="py-3 px-3">Date</th>
                            <th class="py-3 px-3">Risk Level</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E7E5]">
                        @foreach ($recentReports as $report)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3.5 px-3 font-bold text-[#17324D]">
                                    #{{ $report->id }}
                                </td>
                                <td class="py-3.5 px-3 font-medium text-[#243746]">
                                    {{ $report->emergency_type ?? 'Incident' }}
                                </td>
                                <td class="py-3.5 px-3 text-xs text-[#687781]">
                                    {{ $report->formatted_date ?? $report->time_ago }}
                                </td>
                                <td class="py-3.5 px-3">
                                    <x-risk-badge :risk="$report->risk_level ?? 'Medium'" />
                                </td>
                                <td class="py-3.5 px-3">
                                    <x-status-badge :status="$report->status ?? 'Received'" />
                                </td>
                                <td class="py-3.5 px-3 text-right">
                                    <a href="{{ route('citizen.reports.show', $report->id) }}" class="btn btn-outline btn-sm text-xs font-semibold">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="sm:hidden space-y-4">
                @foreach ($recentReports as $report)
                    <div class="p-4 rounded-xl border border-[#E2E7E5] bg-[#F7F8F6] space-y-2.5">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-sm text-[#17324D]">#{{ $report->id }}</span>
                            <x-status-badge :status="$report->status ?? 'Received'" />
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-[#243746]">{{ $report->emergency_type ?? 'Incident' }}</span>
                            <span class="block text-xs text-[#687781]">{{ $report->time_ago }}</span>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-[#E2E7E5]">
                            <x-risk-badge :risk="$report->risk_level ?? 'Medium'" />
                            <a href="{{ route('citizen.reports.show', $report->id) }}" class="text-xs font-bold text-[#1F8A89]">
                                View Details &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
