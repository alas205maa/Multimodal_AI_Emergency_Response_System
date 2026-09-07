@extends('layouts.responder')

@section('title', 'Emergency #' . $emergency->id . ' — Incident Commander Triage')
@section('page_title', 'Incident #' . $emergency->id)
@section('page_subtitle', 'Multimodal analysis, citizen report telemetry, and field asset dispatch')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    <!-- Top Action Bar & Incident Status Changer -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('responder.emergencies.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#1F8A89] hover:underline">
            &larr; Back to Emergency Registry
        </a>

        <!-- Status Changer Form -->
        <form action="{{ route('responder.emergencies.update-status', $emergency->id) }}" method="POST" class="flex items-center gap-2 bg-white p-2 rounded-xl border border-[#E2E7E5] shadow-xs">
            @csrf
            <label for="status-select" class="text-xs font-bold text-[#17324D] whitespace-nowrap pl-2">
                Operational Status:
            </label>
            <select id="status-select" name="status" class="form-select text-xs !py-1 !px-2 border-slate-200">
                <option value="Received" {{ $emergency->status === 'Received' ? 'selected' : '' }}>Received</option>
                <option value="Analyzing" {{ $emergency->status === 'Analyzing' ? 'selected' : '' }}>Analyzing</option>
                <option value="Assigned" {{ $emergency->status === 'Assigned' ? 'selected' : '' }}>Assigned</option>
                <option value="In Progress" {{ $emergency->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Resolved" {{ $emergency->status === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="Cancelled" {{ $emergency->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm text-xs font-semibold">
                Update
            </button>
        </form>
    </div>

    <!-- Incident Header Card: Emergency #[ID] -->
    <div class="card p-6 sm:p-8 bg-white border border-[#E2E7E5] shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-[#E2E7E5]">
            <div>
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-[#17324D] font-mono tracking-tight">
                        Emergency #{{ $emergency->id }}
                    </h1>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-slate-100 text-[#17324D] font-mono">
                        PRIORITY 1
                    </span>
                </div>
                <h2 class="text-lg font-bold text-[#243746]">
                    {{ $emergency->emergency_type ?? 'Incident' }}
                </h2>
                <span class="text-xs text-[#687781] block mt-1">
                    First reported {{ $emergency->time_ago }} &bull; Coordinates: {{ $emergency->latitude }}, {{ $emergency->longitude }}
                </span>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <x-risk-badge :risk="$emergency->risk_level ?? 'Medium'" />
                <x-status-badge :status="$emergency->status ?? 'Received'" />
            </div>
        </div>

        <!-- Quick Telemetry Summary Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-6 text-xs">
            <div>
                <span class="text-[#687781] block mb-0.5">Reported Location</span>
                <strong class="text-[#17324D] truncate block">{{ $emergency->location_name ?? 'Location available' }}</strong>
            </div>
            <div>
                <span class="text-[#687781] block mb-0.5">Composite Score</span>
                <strong class="text-sm font-extrabold text-[#C73E3E] font-mono">{{ $emergency->final_score ?? '9.2' }} / 10</strong>
            </div>
            <div>
                <span class="text-[#687781] block mb-0.5">AI Confidence</span>
                <strong class="text-sm font-extrabold text-[#1F8A89] font-mono">{{ $emergency->confidence ?? 94 }}%</strong>
            </div>
            <div>
                <span class="text-[#687781] block mb-0.5">Reporter</span>
                <strong class="text-[#17324D] block">{{ $emergency->citizen_name ?? 'Verified Citizen' }}</strong>
            </div>
        </div>
    </div>

    <!-- MULTIMODAL ANALYSIS SECTION (Dominant & Prominent) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-[#1F8A89]"></span>
                <h2 class="text-lg font-extrabold text-[#17324D]">
                    Multimodal AI Analysis
                </h2>
            </div>
            <span class="text-xs text-[#687781]">Integration Pipeline: S1 (Text) + S2 (Vision) + S3 (Fusion)</span>
        </div>

        <!-- VISUALLY DOMINANT: Final Fusion / Decision Card -->
        <div class="card p-6 sm:p-8 bg-gradient-to-br from-white to-teal-50/30 border-2 border-[#1F8A89] shadow-md">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 mb-6 border-b border-teal-200">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-100 text-teal-800 text-xs font-bold uppercase tracking-wider mb-2">
                        ★ Final Fusion Decision
                    </span>
                    <h3 class="text-xl sm:text-2xl font-extrabold text-[#17324D]">
                        {{ $emergency->fusion_decision['final_type'] ?? $emergency->emergency_type }}
                    </h3>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <span class="text-[11px] text-[#687781] block font-semibold">Final Urgency Score</span>
                        <span class="text-2xl sm:text-3xl font-black text-[#C73E3E] font-mono">
                            {{ $emergency->fusion_decision['final_score'] ?? $emergency->final_score }}
                        </span>
                    </div>
                    <x-risk-badge :risk="$emergency->risk_level ?? 'Medium'" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div class="p-4 rounded-xl bg-white border border-[#E2E7E5]">
                    <span class="text-xs font-bold text-[#687781] uppercase tracking-wider block mb-2">
                        Automated Operational Decision
                    </span>
                    <p class="text-sm font-bold text-[#17324D] leading-relaxed">
                        {{ $emergency->fusion_decision['final_decision'] ?? $emergency->final_decision }}
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-white border border-[#E2E7E5]">
                    <span class="text-xs font-bold text-[#687781] uppercase tracking-wider block mb-2">
                        Recommended Dispatch Package
                    </span>
                    <p class="text-sm font-semibold text-[#1F8A89] leading-relaxed">
                        {{ $emergency->fusion_decision['recommended_units'] ?? '3 Primary Units Assigned' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- 2 Side-by-Side Modality Cards: Text Analysis & Image Analysis -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Text Analysis Card -->
            <div class="card p-6 bg-white border border-[#E2E7E5]">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-[#E2E7E5]">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-[#17324D] flex items-center justify-center font-bold text-xs">
                            NLP
                        </div>
                        <h4 class="text-sm font-bold text-[#17324D]">Text Analysis (S1)</h4>
                    </div>
                    <span class="text-xs font-mono font-bold text-[#1F8A89]">
                        {{ $emergency->text_analysis['confidence'] ?? '95%' }} Conf.
                    </span>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-[#687781] block mb-1">Urgency Score:</span>
                        <span class="font-mono font-bold text-sm text-[#C73E3E]">
                            {{ $emergency->text_analysis['urgency_score'] ?? '9.4 / 10' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-[#687781] block mb-1">Extracted Information:</span>
                        <div class="p-3 rounded-lg bg-[#F7F8F6] text-[#243746] leading-relaxed border border-[#E2E7E5]">
                            {{ $emergency->text_analysis['extracted_info'] ?? 'Hazards identified in distress narrative.' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Analysis Card -->
            <div class="card p-6 bg-white border border-[#E2E7E5]">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-[#E2E7E5]">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 text-[#1F8A89] flex items-center justify-center font-bold text-xs">
                            CV
                        </div>
                        <h4 class="text-sm font-bold text-[#17324D]">Image Analysis (S2)</h4>
                    </div>
                    <span class="text-xs font-mono font-bold text-[#1F8A89]">
                        {{ $emergency->image_analysis['confidence'] ?? '92%' }} Conf.
                    </span>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-[#687781] block mb-1">Detected Emergency Type:</span>
                        <span class="font-bold text-sm text-[#17324D]">
                            {{ $emergency->image_analysis['detected_type'] ?? 'Visual hazard signature' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-[#687781] block mb-1">Computer Vision Summary:</span>
                        <div class="p-3 rounded-lg bg-[#F7F8F6] text-[#243746] leading-relaxed border border-[#E2E7E5]">
                            {{ $emergency->image_analysis['image_info'] ?? 'Visual markers processed by convolutional classifier.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CITIZEN REPORT & GEOSPATIAL LOCATION GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Citizen Report Card -->
        <div class="card p-6 bg-white border border-[#E2E7E5]">
            <h3 class="text-base font-bold text-[#17324D] mb-4 pb-2 border-b border-[#E2E7E5] flex items-center gap-2">
                <svg class="w-5 h-5 text-[#1F8A89]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Citizen Distress Input
            </h3>

            <div class="space-y-4 text-xs">
                <div>
                    <span class="text-[#687781] block mb-1 font-semibold">Incident Description:</span>
                    <div class="p-4 rounded-xl bg-[#F7F8F6] border border-[#E2E7E5] text-sm text-[#243746] leading-relaxed">
                        {{ $emergency->description }}
                    </div>
                </div>

                <div>
                    <span class="text-[#687781] block mb-1 font-semibold">Scene Photograph:</span>
                    @if ($emergency->image)
                        <div class="rounded-xl overflow-hidden border border-[#E2E7E5]">
                            <img src="{{ asset('storage/' . $emergency->image) }}" alt="Scene image" class="w-full h-52 object-cover">
                        </div>
                    @else
                        <div class="p-4 rounded-xl bg-[#F7F8F6] border border-[#E2E7E5] text-slate-500">
                            No photograph uploaded by citizen. Text & location analysis used exclusively.
                        </div>
                    @endif
                </div>

                <div class="pt-2 text-[#687781]">
                    Submission Time: <strong>{{ $emergency->formatted_date ?? $emergency->time_ago }}</strong>
                </div>
            </div>
        </div>

        <!-- Emergency Location & S4 Geospatial Section -->
        <div class="card p-6 bg-white border border-[#E2E7E5]">
            <h3 class="text-base font-bold text-[#17324D] mb-4 pb-2 border-b border-[#E2E7E5] flex items-center gap-2">
                <svg class="w-5 h-5 text-[#F05D5E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                Geospatial Incident Location
            </h3>

            <!-- Large Map Area -->
            <div class="map-canvas-placeholder h-56 rounded-xl border border-[#E2E7E5] mb-4 flex flex-col items-center justify-center text-center p-4 relative">
                <div class="w-10 h-10 rounded-full bg-[#17324D] text-white flex items-center justify-center shadow-md mb-2">
                    <svg class="w-5 h-5 text-[#F05D5E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-xs font-mono font-bold text-[#17324D] block">
                    {{ $emergency->latitude }}, {{ $emergency->longitude }}
                </span>
                <span class="text-xs text-[#687781] block mt-1">
                    {{ $emergency->location_name ?? 'Reported Coordinates' }}
                </span>
            </div>

            <!-- Geospatial S4 Reserved Slots -->
            <div class="space-y-2.5 text-xs">
                <div class="p-3 rounded-lg bg-[#F7F8F6] border border-[#E2E7E5] flex items-center justify-between">
                    <span class="text-[#687781]">Nearest Civil Defense Station:</span>
                    <strong class="text-[#17324D]">{{ $emergency->geospatial_info['nearest_station'] ?? 'Station #14 Central (1.8 km)' }}</strong>
                </div>
                <div class="p-3 rounded-lg bg-[#F7F8F6] border border-[#E2E7E5] flex items-center justify-between">
                    <span class="text-[#687781]">Estimated Rapid ETA:</span>
                    <strong class="text-emerald-700 font-bold">{{ $emergency->geospatial_info['estimated_eta'] ?? '4 - 6 minutes' }}</strong>
                </div>
                <div class="p-3 rounded-lg bg-[#F7F8F6] border border-[#E2E7E5] flex items-center justify-between">
                    <span class="text-[#687781]">Recommended Transit Route:</span>
                    <strong class="text-[#17324D]">{{ $emergency->geospatial_info['route'] ?? 'Via King Fahd Express Rd' }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
