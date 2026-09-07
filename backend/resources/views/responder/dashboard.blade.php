@extends('layouts.responder')

@section('title', 'Emergency Operations Dashboard — Central Command')
@section('page_title', 'Emergency Operations Dashboard')
@section('page_subtitle', 'Live incident stream, AI hazard classification, and multi-agency response dispatch')

@section('content')
<div class="space-y-6">
    <!-- Top Information Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-[#687781] pb-2">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
            <span class="font-bold text-[#17324D]">Network Status: Operational</span>
            <span>&bull;</span>
            <span>Live Sync Active</span>
        </div>
        <div>
            <span>Regional Zone: <strong>Central Sector (Riyadh Metropolitan)</strong></span>
        </div>
    </div>

    <!-- Prominent Critical Emergency Alert Banner (Deep Red #C73E3E, Solid, Never Flash) -->
    @if ($criticalIncident)
        <x-alert-banner :emergency="$criticalIncident" />
    @endif

    <!-- Statistics Cards: Active Emergencies, Critical, High Risk, Pending Analysis, Resolved Today -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Active Emergencies -->
        <div class="card p-5 bg-white border border-[#E2E7E5]">
            <span class="text-xs font-semibold text-[#687781] block mb-1">Active Incidents</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-[#17324D] font-mono">{{ $stats['active_emergencies'] }}</span>
                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-[#17324D]">
                    Real-time
                </span>
            </div>
        </div>

        <!-- Critical -->
        <div class="card p-5 bg-white border border-rose-200 bg-rose-50/20">
            <span class="text-xs font-semibold text-[#C73E3E] block mb-1">Critical Severity</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-[#C73E3E] font-mono">{{ $stats['critical'] }}</span>
                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#FDF2F2] text-[#C73E3E]">
                    P1 Priority
                </span>
            </div>
        </div>

        <!-- High Risk -->
        <div class="card p-5 bg-white border border-orange-200">
            <span class="text-xs font-semibold text-[#E06B3C] block mb-1">High Risk</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-[#E06B3C] font-mono">{{ $stats['high_risk'] }}</span>
                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#FFF5F2] text-[#E06B3C]">
                    P2 Priority
                </span>
            </div>
        </div>

        <!-- Pending Analysis -->
        <div class="card p-5 bg-white border border-[#E2E7E5]">
            <span class="text-xs font-semibold text-[#687781] block mb-1">Pending Analysis</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-[#1F8A89] font-mono">{{ $stats['pending_analysis'] }}</span>
                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-teal-50 text-[#1F8A89]">
                    AI Triage
                </span>
            </div>
        </div>

        <!-- Resolved Today -->
        <div class="card p-5 bg-white border border-[#E2E7E5] col-span-2 lg:col-span-1">
            <span class="text-xs font-semibold text-[#687781] block mb-1">Resolved Today</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-[#3A9D6E] font-mono">{{ $stats['resolved_today'] }}</span>
                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-[#3A9D6E]">
                    Concluded
                </span>
            </div>
        </div>
    </div>

    <!-- Dominant Live Operation Section: Large Emergency Map and Latest Emergencies -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
        <!-- Large Emergency Map (7 cols) -->
        <div class="xl:col-span-7 card p-6 bg-white border border-[#E2E7E5]">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#E2E7E5]">
                <div class="flex items-center gap-2.5">
                    <span class="w-3 h-3 rounded-full bg-[#F05D5E]"></span>
                    <h3 class="text-base font-bold text-[#17324D]">
                        Tactical Geospatial Map
                    </h3>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-[#687781] font-medium hidden sm:inline">Active Sector: Central</span>
                    <a href="{{ route('responder.map') }}" class="text-xs font-bold text-[#1F8A89] hover:underline">
                        Full Map &rarr;
                    </a>
                </div>
            </div>

            <!-- Map Graphic Area -->
            <div class="map-canvas-placeholder h-80 sm:h-96 rounded-xl border border-[#E2E7E5] relative p-4 flex flex-col justify-between overflow-hidden">
                <!-- Map Legend -->
                <div class="self-start bg-white/90 backdrop-blur-xs p-3 rounded-lg border border-[#E2E7E5] shadow-xs space-y-1.5 text-[11px]">
                    <span class="font-bold text-[#17324D] block mb-1">Incident Markers</span>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#C73E3E]"></span>
                        <span class="text-[#243746]">Critical Threat</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#E06B3C]"></span>
                        <span class="text-[#243746]">High Hazard</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#E6A23C]"></span>
                        <span class="text-[#243746]">Medium / Standard</span>
                    </div>
                </div>

                <!-- Simulated Plotted Incident Markers with Interactive Details -->
                <div class="absolute top-1/4 left-1/3">
                    <a href="{{ route('responder.emergencies.show', 1042) }}" class="relative group block" title="Incident #1042">
                        <span class="flex h-5 w-5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#C73E3E] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-5 w-5 bg-[#C73E3E] text-white text-[10px] font-bold items-center justify-center border-2 border-white shadow-md">!</span>
                        </span>
                        <!-- Hover Popup -->
                        <div class="hidden group-hover:block absolute bottom-6 left-1/2 -translate-x-1/2 w-48 bg-[#17324D] text-white text-xs p-2.5 rounded-lg shadow-xl z-20 pointer-events-none">
                            <span class="font-extrabold block text-rose-300">#1042 — Critical Fire</span>
                            <span class="text-[10px] text-slate-300">Warehouse Structure Fire</span>
                        </div>
                    </a>
                </div>

                <div class="absolute top-1/2 right-1/4">
                    <a href="{{ route('responder.emergencies.show', 1041) }}" class="relative group block" title="Incident #1041">
                        <span class="flex h-4 w-4">
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-[#E06B3C] text-white text-[9px] font-bold items-center justify-center border border-white shadow">2</span>
                        </span>
                    </a>
                </div>

                <div class="absolute bottom-1/4 left-1/2">
                    <a href="{{ route('responder.emergencies.show', 1040) }}" class="relative group block" title="Incident #1040">
                        <span class="flex h-4 w-4">
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-[#E6A23C] text-white text-[9px] font-bold items-center justify-center border border-white shadow">3</span>
                        </span>
                    </a>
                </div>

                <!-- Bottom Coordinates Telemetry -->
                <div class="self-end bg-white/90 backdrop-blur-xs px-3 py-1.5 rounded-md border border-[#E2E7E5] text-[10px] font-mono text-[#687781]">
                    GPS Mesh: 24.7136° N, 46.6753° E | Zoom Level: 14.5x
                </div>
            </div>
        </div>

        <!-- Latest Emergencies Table (5 cols) -->
        <div class="xl:col-span-5 card p-6 bg-white border border-[#E2E7E5]">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#E2E7E5]">
                <h3 class="text-base font-bold text-[#17324D]">
                    Latest Emergency Ingestion
                </h3>
                <a href="{{ route('responder.emergencies.index') }}" class="text-xs font-bold text-[#1F8A89] hover:underline">
                    View All ({{ $allReports->count() }})
                </a>
            </div>

            <div class="space-y-3 overflow-y-auto max-h-[26rem] pr-1">
                @foreach ($latestEmergencies as $emergency)
                    <div class="p-3.5 rounded-xl border border-[#E2E7E5] hover:border-[#1F8A89] transition-all bg-[#F7F8F6] space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-bold text-xs text-[#17324D]">#{{ $emergency->id }}</span>
                            <div class="flex items-center gap-1.5">
                                <x-risk-badge :risk="$emergency->risk_level ?? 'Medium'" />
                                <x-status-badge :status="$emergency->status ?? 'Received'" />
                            </div>
                        </div>

                        <div class="text-xs font-bold text-[#243746]">
                            {{ $emergency->emergency_type ?? 'Unspecified' }}
                        </div>

                        <p class="text-xs text-[#687781] line-clamp-1">
                            {{ $emergency->description }}
                        </p>

                        <div class="flex items-center justify-between text-[11px] pt-1.5 border-t border-[#E2E7E5]/70">
                            <span class="text-[#687781]">{{ $emergency->time_ago }}</span>
                            <a href="{{ route('responder.emergencies.show', $emergency->id) }}" class="font-bold text-[#1F8A89] hover:underline">
                                Inspect Details &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
