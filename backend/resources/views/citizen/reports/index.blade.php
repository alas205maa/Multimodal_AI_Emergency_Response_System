@extends('layouts.citizen')

@section('title', 'My Emergency Reports — Citizen Portal')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#17324D] tracking-tight">
                My Emergency Reports
            </h1>
            <p class="text-sm text-[#687781] mt-1">
                Comprehensive record of all incident reports you have submitted.
            </p>
        </div>
        <div>
            <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary font-bold shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                New Emergency Report
            </a>
        </div>
    </div>

    <!-- Search & Filters Bar -->
    <div class="card p-4 sm:p-5 bg-white border border-[#E2E7E5]">
        <form action="{{ route('citizen.reports.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label for="search" class="sr-only">Search</label>
                <div class="relative">
                    <input type="text" id="search" name="search" value="{{ $currentSearch }}" placeholder="Search by ID, keyword, hazard..." class="form-input text-xs pl-8">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="sr-only">Status</label>
                <select id="status" name="status" class="form-select text-xs">
                    <option value="">All Statuses</option>
                    <option value="Received" {{ $currentStatus === 'Received' ? 'selected' : '' }}>Received</option>
                    <option value="Analyzing" {{ $currentStatus === 'Analyzing' ? 'selected' : '' }}>Analyzing</option>
                    <option value="Assigned" {{ $currentStatus === 'Assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="In Progress" {{ $currentStatus === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Resolved" {{ $currentStatus === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="Cancelled" {{ $currentStatus === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Risk Filter -->
            <div>
                <label for="risk" class="sr-only">Risk Level</label>
                <select id="risk" name="risk" class="form-select text-xs">
                    <option value="">All Risk Levels</option>
                    <option value="Critical" {{ $currentRisk === 'Critical' ? 'selected' : '' }}>Critical</option>
                    <option value="High" {{ $currentRisk === 'High' ? 'selected' : '' }}>High</option>
                    <option value="Medium" {{ $currentRisk === 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="Low" {{ $currentRisk === 'Low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>

            <!-- Submit Filter Button -->
            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-secondary btn-sm w-full font-semibold">
                    Filter
                </button>
                @if ($currentSearch || $currentStatus || $currentRisk || $currentType)
                    <a href="{{ route('citizen.reports.index') }}" class="btn btn-outline btn-sm text-xs" title="Clear Filters">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Reports Results Table -->
    <div class="card p-6 bg-white border border-[#E2E7E5]">
        @if ($reports->isEmpty())
            <x-empty-state 
                title="No results match your filters" 
                message="Try adjusting your search criteria or clear active filters."
                actionText="View All Reports"
                actionUrl="{{ route('citizen.reports.index') }}"
            />
        @else
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-[#E2E7E5] text-xs font-bold uppercase text-[#687781] tracking-wider">
                            <th class="py-3 px-3">Report ID</th>
                            <th class="py-3 px-3">Date</th>
                            <th class="py-3 px-3">Emergency Type</th>
                            <th class="py-3 px-3">Risk Level</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E7E5]">
                        @foreach ($reports as $report)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-4 px-3 font-mono font-extrabold text-[#17324D]">
                                    #{{ $report->id }}
                                </td>
                                <td class="py-4 px-3 text-xs text-[#687781]">
                                    {{ $report->formatted_date ?? $report->time_ago }}
                                </td>
                                <td class="py-4 px-3 font-semibold text-[#243746]">
                                    {{ $report->emergency_type ?? 'Incident' }}
                                </td>
                                <td class="py-4 px-3">
                                    <x-risk-badge :risk="$report->risk_level ?? 'Medium'" />
                                </td>
                                <td class="py-4 px-3">
                                    <x-status-badge :status="$report->status ?? 'Received'" />
                                </td>
                                <td class="py-4 px-3 text-right">
                                    <a href="{{ route('citizen.reports.show', $report->id) }}" class="btn btn-outline btn-sm text-xs font-semibold">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View Transformation -->
            <div class="md:hidden space-y-4">
                @foreach ($reports as $report)
                    <div class="p-4 rounded-xl border border-[#E2E7E5] bg-[#F7F8F6] space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-extrabold text-sm text-[#17324D]">#{{ $report->id }}</span>
                            <x-status-badge :status="$report->status ?? 'Received'" />
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-[#243746]">{{ $report->emergency_type ?? 'Incident' }}</span>
                            <span class="block text-xs text-[#687781] mt-0.5">{{ $report->formatted_date ?? $report->time_ago }}</span>
                            <p class="text-xs text-[#687781] line-clamp-2 mt-2 leading-relaxed">
                                {{ $report->description }}
                            </p>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-[#E2E7E5]">
                            <x-risk-badge :risk="$report->risk_level ?? 'Medium'" />
                            <a href="{{ route('citizen.reports.show', $report->id) }}" class="btn btn-outline btn-sm text-xs font-semibold">
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
