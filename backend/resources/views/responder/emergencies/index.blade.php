@extends('layouts.responder')

@section('title', 'Emergencies Incident Triage — Central Command')
@section('page_title', 'Emergency Incidents Triage')
@section('page_subtitle', 'Live incident registry with multi-parameter filtering, severity scores, and dispatch controls')

@section('content')
<div class="space-y-6">
    <!-- Filter & Search Controls Bar -->
    <div class="card p-5 bg-white border border-[#E2E7E5]">
        <form action="{{ route('responder.emergencies.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label for="search" class="sr-only">Search</label>
                <div class="relative">
                    <input type="text" id="search" name="search" value="{{ $currentSearch }}" placeholder="Search ID, type, street, description..." class="form-input text-xs pl-8">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Risk Filter -->
            <div>
                <label for="risk" class="sr-only">Risk Level</label>
                <select id="risk" name="risk" class="form-select text-xs">
                    <option value="">All Risk Levels</option>
                    <option value="Critical" {{ $currentRisk === 'Critical' ? 'selected' : '' }}>Critical (Red)</option>
                    <option value="High" {{ $currentRisk === 'High' ? 'selected' : '' }}>High (Orange)</option>
                    <option value="Medium" {{ $currentRisk === 'Medium' ? 'selected' : '' }}>Medium (Amber)</option>
                    <option value="Low" {{ $currentRisk === 'Low' ? 'selected' : '' }}>Low (Green)</option>
                </select>
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

            <!-- Type Filter -->
            <div>
                <label for="type" class="sr-only">Emergency Type</label>
                <select id="type" name="type" class="form-select text-xs">
                    <option value="">All Types</option>
                    <option value="Fire & Smoke" {{ $currentType === 'Fire & Smoke' ? 'selected' : '' }}>Fire & Smoke</option>
                    <option value="Traffic Collision" {{ $currentType === 'Traffic Collision' ? 'selected' : '' }}>Traffic Collision</option>
                    <option value="Flash Flood" {{ $currentType === 'Flash Flood' ? 'selected' : '' }}>Flash Flood</option>
                    <option value="Structural Collapse" {{ $currentType === 'Structural Collapse' ? 'selected' : '' }}>Structural Collapse</option>
                    <option value="Medical Emergency" {{ $currentType === 'Medical Emergency' ? 'selected' : '' }}>Medical Emergency</option>
                    <option value="Hazardous Material" {{ $currentType === 'Hazardous Material' ? 'selected' : '' }}>Hazardous Material</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-secondary btn-sm w-full font-semibold">
                    Filter
                </button>
                @if ($currentSearch || $currentRisk || $currentStatus || $currentType)
                    <a href="{{ route('responder.emergencies.index') }}" class="btn btn-outline btn-sm text-xs" title="Reset Filters">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Emergencies Table Card -->
    <div class="card p-6 bg-white border border-[#E2E7E5]">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#E2E7E5]">
            <span class="text-xs font-bold text-[#687781] uppercase tracking-wider">
                Showing {{ $reports->count() }} active incidents
            </span>
            <div class="flex items-center gap-2">
                <span class="text-xs text-[#687781]">Sort:</span>
                <span class="text-xs font-bold text-[#17324D]">Severity (High &rarr; Low)</span>
            </div>
        </div>

        @if ($reports->isEmpty())
            <x-empty-state 
                title="No emergency records match criteria" 
                message="Adjust the search string or filter parameters to view other emergency incidents."
                actionText="Reset All Filters"
                actionUrl="{{ route('responder.emergencies.index') }}"
            />
        @else
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-[#E2E7E5] text-xs font-bold uppercase text-[#687781] tracking-wider">
                            <th class="py-3 px-3">ID</th>
                            <th class="py-3 px-3">Type</th>
                            <th class="py-3 px-3">Risk Level</th>
                            <th class="py-3 px-3">Final Score</th>
                            <th class="py-3 px-3">Location</th>
                            <th class="py-3 px-3">Timestamp</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E7E5]">
                        @foreach ($reports as $emergency)
                            <tr class="hover:bg-slate-50/70 transition-colors {{ $emergency->risk_level === 'Critical' ? 'bg-rose-50/15' : '' }}">
                                <td class="py-3.5 px-3 font-mono font-bold text-[#17324D]">
                                    #{{ $emergency->id }}
                                </td>
                                <td class="py-3.5 px-3 font-semibold text-[#243746]">
                                    {{ $emergency->emergency_type ?? 'Incident' }}
                                </td>
                                <td class="py-3.5 px-3">
                                    <x-risk-badge :risk="$emergency->risk_level ?? 'Medium'" />
                                </td>
                                <td class="py-3.5 px-3 font-mono font-bold {{ $emergency->risk_level === 'Critical' ? 'text-[#C73E3E]' : 'text-[#243746]' }}">
                                    {{ $emergency->final_score ?? '—' }} <span class="text-[10px] text-slate-400 font-normal">/ 10</span>
                                </td>
                                <td class="py-3.5 px-3 text-xs text-[#687781] max-w-xs truncate" title="{{ $emergency->location_name }}">
                                    {{ $emergency->location_name ?? 'Coordinates Available' }}
                                </td>
                                <td class="py-3.5 px-3 text-xs text-[#687781]">
                                    {{ $emergency->time_ago }}
                                </td>
                                <td class="py-3.5 px-3">
                                    <x-status-badge :status="$emergency->status ?? 'Received'" />
                                </td>
                                <td class="py-3.5 px-3 text-right">
                                    <a href="{{ route('responder.emergencies.show', $emergency->id) }}" class="btn btn-navy btn-sm text-xs font-semibold">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile / Tablet Card View -->
            <div class="lg:hidden space-y-4">
                @foreach ($reports as $emergency)
                    <div class="p-4 rounded-xl border border-[#E2E7E5] bg-[#F7F8F6] space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-extrabold text-sm text-[#17324D]">#{{ $emergency->id }}</span>
                            <div class="flex items-center gap-1.5">
                                <x-risk-badge :risk="$emergency->risk_level ?? 'Medium'" />
                                <x-status-badge :status="$emergency->status ?? 'Received'" />
                            </div>
                        </div>

                        <div>
                            <span class="block text-sm font-bold text-[#243746]">{{ $emergency->emergency_type ?? 'Incident' }}</span>
                            <span class="block text-xs text-[#687781] mt-0.5">{{ $emergency->location_name ?? 'Location available' }}</span>
                            <p class="text-xs text-[#687781] line-clamp-2 mt-2 leading-relaxed">
                                {{ $emergency->description }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-[#E2E7E5] text-xs">
                            <span class="font-mono font-bold text-[#17324D]">Score: {{ $emergency->final_score ?? '—' }} / 10</span>
                            <a href="{{ route('responder.emergencies.show', $emergency->id) }}" class="btn btn-navy btn-sm text-xs font-semibold">
                                Inspect Incident &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
