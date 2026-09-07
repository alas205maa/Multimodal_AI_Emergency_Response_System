@extends('layouts.responder')

@section('title', 'Emergency Tactical Map — Central Operations')
@section('page_title', 'Tactical Incident Map')
@section('page_subtitle', 'Live spatial distribution of emergency reports, risk clusters, and station dispatch routes')

@section('content')
<div class="h-[calc(100vh-9.5rem)] flex flex-col space-y-4">
    <!-- Filter Strip -->
    <div class="card p-3.5 bg-white border border-[#E2E7E5] shrink-0">
        <form action="{{ route('responder.map') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ $currentSearch }}" placeholder="Filter map incidents..." class="form-input text-xs py-1.5">
            </div>

            <div class="w-36">
                <select name="risk" class="form-select text-xs py-1.5">
                    <option value="">All Risks</option>
                    <option value="Critical" {{ $currentRisk === 'Critical' ? 'selected' : '' }}>Critical</option>
                    <option value="High" {{ $currentRisk === 'High' ? 'selected' : '' }}>High</option>
                    <option value="Medium" {{ $currentRisk === 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="Low" {{ $currentRisk === 'Low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>

            <div class="w-36">
                <select name="status" class="form-select text-xs py-1.5">
                    <option value="">All Statuses</option>
                    <option value="Received" {{ $currentStatus === 'Received' ? 'selected' : '' }}>Received</option>
                    <option value="Analyzing" {{ $currentStatus === 'Analyzing' ? 'selected' : '' }}>Analyzing</option>
                    <option value="Assigned" {{ $currentStatus === 'Assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="In Progress" {{ $currentStatus === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Resolved" {{ $currentStatus === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
            </div>

            <button type="submit" class="btn btn-secondary btn-sm text-xs font-semibold py-1.5">
                Apply
            </button>
            @if ($currentSearch || $currentRisk || $currentStatus)
                <a href="{{ route('responder.map') }}" class="btn btn-outline btn-sm text-xs py-1.5">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Two-Column Desktop Layout -->
    <div class="flex-1 grid grid-cols-1 lg:grid-cols-12 gap-4 min-h-0 overflow-hidden">
        <!-- Left: Incident Feed List (4 cols) -->
        <div class="lg:col-span-4 card bg-white border border-[#E2E7E5] flex flex-col min-h-0 overflow-hidden">
            <div class="p-3.5 border-b border-[#E2E7E5] bg-[#F7F8F6] flex items-center justify-between shrink-0">
                <span class="text-xs font-bold text-[#17324D] uppercase tracking-wider">
                    Plotted Incidents ({{ $emergencies->count() }})
                </span>
                <span class="text-[11px] text-[#687781]">Click card to inspect</span>
            </div>

            <div class="flex-1 overflow-y-auto p-3 space-y-2.5">
                @forelse ($emergencies as $item)
                    <div class="p-3 rounded-xl border border-[#E2E7E5] hover:border-[#1F8A89] transition-all bg-white hover:bg-slate-50/70 space-y-1.5 cursor-pointer" onclick="document.getElementById('marker-popup-{{ $item->id }}')?.scrollIntoView({behavior: 'smooth'});">
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-bold text-xs text-[#17324D]">#{{ $item->id }}</span>
                            <x-risk-badge :risk="$item->risk_level ?? 'Medium'" />
                        </div>
                        <h4 class="text-xs font-bold text-[#243746] truncate">
                            {{ $item->emergency_type ?? 'Incident' }}
                        </h4>
                        <span class="text-[11px] text-[#687781] block truncate">
                            {{ $item->location_name }}
                        </span>
                        <div class="flex items-center justify-between pt-1 border-t border-slate-100 text-[10px]">
                            <span class="text-slate-400 font-mono">{{ $item->latitude }}, {{ $item->longitude }}</span>
                            <x-status-badge :status="$item->status ?? 'Received'" />
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-[#687781]">
                        No incidents match criteria.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Large Interactive Map Canvas (8 cols) -->
        <div class="lg:col-span-8 card bg-white border border-[#E2E7E5] flex flex-col min-h-0 overflow-hidden relative">
            <div class="map-canvas-placeholder flex-1 relative overflow-hidden p-6 flex flex-col justify-between">
                <!-- Top Map Legend & Layer Controls -->
                <div class="flex items-start justify-between z-10">
                    <div class="bg-white/95 backdrop-blur-xs p-3.5 rounded-xl border border-[#E2E7E5] shadow-sm space-y-2 text-xs">
                        <span class="font-bold text-[#17324D] block border-b border-slate-100 pb-1">Map Legend</span>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#C73E3E] border border-white"></span>
                            <span class="text-[#243746]">Critical Emergency (P1)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#E06B3C] border border-white"></span>
                            <span class="text-[#243746]">High Risk (P2)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#E6A23C] border border-white"></span>
                            <span class="text-[#243746]">Medium Risk (P3)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#3A9D6E] border border-white"></span>
                            <span class="text-[#243746]">Low Risk (P4)</span>
                        </div>
                    </div>

                    <div class="bg-white/95 backdrop-blur-xs px-3 py-1.5 rounded-lg border border-[#E2E7E5] text-xs font-bold text-[#17324D]">
                        GPS Mode: High-Precision Grid
                    </div>
                </div>

                <!-- Simulated Interactive Incident Marker Popup (Supporting ID, Type, Risk, Status, Time, View Details) -->
                @php $featured = $emergencies->first(); @endphp
                @if ($featured)
                    <div id="marker-popup-{{ $featured->id }}" class="self-center my-auto max-w-sm w-full bg-white p-5 rounded-2xl border border-teal-300 shadow-xl z-20 transition-all">
                        <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#C73E3E] animate-ping"></span>
                                <span class="font-mono font-bold text-xs text-[#17324D]">#{{ $featured->id }} Selected Pin</span>
                            </div>
                            <x-risk-badge :risk="$featured->risk_level ?? 'Medium'" />
                        </div>

                        <h4 class="text-sm font-bold text-[#17324D] mb-1">
                            {{ $featured->emergency_type ?? 'Structure Fire' }}
                        </h4>
                        <p class="text-xs text-[#687781] line-clamp-2 mb-3 leading-relaxed">
                            {{ $featured->description }}
                        </p>

                        <div class="grid grid-cols-2 gap-2 text-[11px] mb-4 bg-[#F7F8F6] p-2.5 rounded-lg border border-[#E2E7E5]">
                            <div>
                                <span class="text-[#687781] block">Status:</span>
                                <x-status-badge :status="$featured->status ?? 'Received'" />
                            </div>
                            <div>
                                <span class="text-[#687781] block">Reported:</span>
                                <strong class="text-[#243746]">{{ $featured->time_ago }}</strong>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-mono text-slate-400">{{ $featured->latitude }}, {{ $featured->longitude }}</span>
                            <a href="{{ route('responder.emergencies.show', $featured->id) }}" class="btn btn-navy btn-sm text-xs font-bold">
                                View Full Details &rarr;
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Coordinates Bottom Bar -->
                <div class="self-end bg-white/90 backdrop-blur-xs px-3 py-1.5 rounded-lg border border-[#E2E7E5] text-[10px] font-mono text-[#687781]">
                    Riyadh Metropolitan Grid &bull; Lat: 24.7136 &bull; Lng: 46.6753 &bull; Accuracy: &plusmn;3m
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
