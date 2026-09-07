@extends('layouts.public')

@section('title', 'Multimodal AI Emergency Response System — Rapid & Intelligent Incident Triage')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-b from-[#17324D] to-[#1d3d5d] text-white py-20 sm:py-28 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 text-teal-300 text-xs font-semibold uppercase tracking-wider mb-6 border border-white/15">
                <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                Next-Generation Incident Coordination
            </div>

            <!-- Headline -->
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white mb-6 leading-tight sm:leading-none">
                Faster, Smarter Emergency Reporting for Safer Communities.
            </h1>

            <!-- Supporting Text -->
            <p class="text-base sm:text-xl text-white/80 font-normal mb-8 leading-relaxed">
                When seconds matter, describe what is happening, attach photos of the scene, and share your exact location. Our multimodal system analyzes hazards instantly to dispatch help with unprecedented precision.
            </p>

            <!-- Dual CTAs -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary btn-lg w-full sm:w-auto font-bold shadow-lg">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Report Emergency
                </a>
                <a href="{{ route('how-it-works') }}" class="btn btn-secondary btn-lg w-full sm:w-auto font-semibold">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    How It Works
                </a>
            </div>

            <!-- Live Safety Metrics Strip -->
            <div class="grid grid-cols-3 gap-4 pt-12 mt-12 border-t border-white/10 text-center">
                <div>
                    <span class="block text-2xl sm:text-3xl font-extrabold text-white">3-in-1</span>
                    <span class="text-xs text-white/60 font-medium">Multimodal Ingestion</span>
                </div>
                <div>
                    <span class="block text-2xl sm:text-3xl font-extrabold text-teal-300">Under 60s</span>
                    <span class="text-xs text-white/60 font-medium">Triage Classification</span>
                </div>
                <div>
                    <span class="block text-2xl sm:text-3xl font-extrabold text-[#F05D5E]">24 / 7</span>
                    <span class="text-xs text-white/60 font-medium">Continuous Readiness</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Flow Section: Report -> Analyze -> Prioritize -> Respond -->
<section class="py-16 sm:py-24 bg-white border-b border-[#E2E7E5]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-bold text-[#1F8A89] uppercase tracking-wider block mb-2">
                Coordinated Architecture
            </span>
            <h2 class="text-2xl sm:text-4xl font-extrabold text-[#17324D] tracking-tight">
                How Emergency Response Operates
            </h2>
            <p class="text-sm sm:text-base text-[#687781] mt-3">
                A continuous, seamless operational pipeline connecting citizen distress calls with field responders.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
            <!-- Step 1: Report -->
            <div class="card p-6 relative flex flex-col items-start hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-[#17324D] flex items-center justify-center font-bold text-lg mb-4">
                    1
                </div>
                <h3 class="text-lg font-bold text-[#17324D] mb-2">Report</h3>
                <p class="text-sm text-[#687781] leading-relaxed">
                    Citizens submit what they witness using clear text descriptions, on-site photographs, and automatic GPS positioning.
                </p>
            </div>

            <!-- Step 2: Analyze -->
            <div class="card p-6 relative flex flex-col items-start hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-[#1F8A89] flex items-center justify-center font-bold text-lg mb-4">
                    2
                </div>
                <h3 class="text-lg font-bold text-[#17324D] mb-2">Analyze</h3>
                <p class="text-sm text-[#687781] leading-relaxed">
                    Parallel text and computer-vision pipelines extract critical hazard entities, visible dangers, and situational indicators.
                </p>
            </div>

            <!-- Step 3: Prioritize -->
            <div class="card p-6 relative flex flex-col items-start hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-[#C73E3E] flex items-center justify-center font-bold text-lg mb-4">
                    3
                </div>
                <h3 class="text-lg font-bold text-[#17324D] mb-2">Prioritize</h3>
                <p class="text-sm text-[#687781] leading-relaxed">
                    Multimodal fusion assigns an urgency score and definitive risk level (Critical, High, Medium, Low) without guesswork.
                </p>
            </div>

            <!-- Step 4: Respond -->
            <div class="card p-6 relative flex flex-col items-start hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-[#3A9D6E] flex items-center justify-center font-bold text-lg mb-4">
                    4
                </div>
                <h3 class="text-lg font-bold text-[#17324D] mb-2">Respond</h3>
                <p class="text-sm text-[#687781] leading-relaxed">
                    Command centers instantly view geo-located incidents, dispatch tailored response units, and keep citizens informed.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Three Main Inputs Section -->
<section class="py-16 sm:py-24 bg-[#F7F8F6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-bold text-[#1F8A89] uppercase tracking-wider block mb-2">
                Multimodal Triangulation
            </span>
            <h2 class="text-2xl sm:text-4xl font-extrabold text-[#17324D] tracking-tight">
                Three Essential Inputs
            </h2>
            <p class="text-sm sm:text-base text-[#687781] mt-3">
                Traditional dispatch relies solely on verbal descriptions. By joining three distinct signals, our system eliminates ambiguity.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Input 1: Description -->
            <div class="card p-8 bg-white border border-[#E2E7E5] flex flex-col">
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-[#17324D] flex items-center justify-center mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#17324D] mb-3">Text Description</h3>
                <p class="text-sm text-[#687781] leading-relaxed mb-4">
                    Contextual narrative written directly by the witness. Captures current dangers, trapped victims, evolving hazards, and immediate life threats in natural language.
                </p>
                <div class="mt-auto pt-4 border-t border-slate-100 text-xs font-semibold text-[#1F8A89]">
                    Natural Language Processing
                </div>
            </div>

            <!-- Input 2: Image -->
            <div class="card p-8 bg-white border border-[#E2E7E5] flex flex-col">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-[#1F8A89] flex items-center justify-center mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#17324D] mb-3">Emergency Image</h3>
                <p class="text-sm text-[#687781] leading-relaxed mb-4">
                    Direct photographic evidence uploaded from a camera or device. Allows visual confirmation of structural damage, smoke plumes, vehicle collisions, or flood depth.
                </p>
                <div class="mt-auto pt-4 border-t border-slate-100 text-xs font-semibold text-[#1F8A89]">
                    Computer Vision Verification
                </div>
            </div>

            <!-- Input 3: Location -->
            <div class="card p-8 bg-white border border-[#E2E7E5] flex flex-col">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-[#F05D5E] flex items-center justify-center mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#17324D] mb-3">Precise Location</h3>
                <p class="text-sm text-[#687781] leading-relaxed mb-4">
                    High-accuracy GPS coordinates detected with one click. Guides dispatch units along the fastest accessible route directly to the incident's center.
                </p>
                <div class="mt-auto pt-4 border-t border-slate-100 text-xs font-semibold text-[#1F8A89]">
                    Geospatial Mapping & Routing
                </div>
            </div>
        </div>
    </div>
</section>

<!-- System Benefits Section -->
<section class="py-16 sm:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-xs font-bold text-[#1F8A89] uppercase tracking-wider block mb-2">
                    Key Advantages
                </span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-[#17324D] tracking-tight mb-6">
                    Why Multimodal AI Transforms Emergency Management
                </h2>
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-9 h-9 rounded-lg bg-emerald-50 text-[#3A9D6E] flex items-center justify-center shrink-0 mt-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-[#17324D]">Faster Reporting</h4>
                            <p class="text-sm text-[#687781] leading-relaxed">
                                Intuitive, low-stress forms allow witnesses under extreme adrenaline to submit vital information in seconds.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-9 h-9 rounded-lg bg-teal-50 text-[#1F8A89] flex items-center justify-center shrink-0 mt-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-[#17324D]">Multimodal Emergency Analysis</h4>
                            <p class="text-sm text-[#687781] leading-relaxed">
                                Cross-examining text and images eliminates false alarms and flags critical discrepancies immediately.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-9 h-9 rounded-lg bg-amber-50 text-[#E6A23C] flex items-center justify-center shrink-0 mt-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-[#17324D]">Objective Risk Prioritization</h4>
                            <p class="text-sm text-[#687781] leading-relaxed">
                                Incidents are ranked dynamically by threat severity, ensuring life-critical situations receive top operational focus.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-9 h-9 rounded-lg bg-slate-100 text-[#17324D] flex items-center justify-center shrink-0 mt-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-[#17324D]">Location-Aware Response</h4>
                            <p class="text-sm text-[#687781] leading-relaxed">
                                Field units receive exact GPS pins alongside nearby civil defense stations and optimal transit routes.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visual Preview Card -->
            <div class="card p-6 sm:p-8 bg-[#F7F8F6] border border-[#E2E7E5] shadow-md">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-[#E2E7E5]">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[#C73E3E]"></span>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#17324D]">Active Triage Preview</span>
                    </div>
                    <x-risk-badge risk="Critical" />
                </div>

                <div class="space-y-4">
                    <div class="bg-white p-4 rounded-xl border border-[#E2E7E5]">
                        <span class="text-xs text-[#687781] block mb-1 font-semibold">Incident Type</span>
                        <span class="text-base font-bold text-[#17324D]">Commercial Warehouse Structure Fire</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white p-3 rounded-xl border border-[#E2E7E5]">
                            <span class="text-[11px] text-[#687781] block font-semibold">Urgency Score</span>
                            <span class="text-lg font-extrabold text-[#C73E3E]">9.6 / 10</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-[#E2E7E5]">
                            <span class="text-[11px] text-[#687781] block font-semibold">AI Confidence</span>
                            <span class="text-lg font-extrabold text-[#1F8A89]">97%</span>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-[#E2E7E5]">
                        <span class="text-xs text-[#687781] block mb-1 font-semibold">Automated Dispatch Recommendation</span>
                        <span class="text-sm font-semibold text-[#17324D] block">
                            3 Fire Engines, 1 Aerial Ladder, 2 Paramedic Ambulances
                        </span>
                        <span class="text-xs text-emerald-700 mt-1 block">
                            Civil Defense Station #19 (2.1 km away) — ETA 5 mins
                        </span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-[#E2E7E5] flex items-center justify-between">
                    <span class="text-xs text-[#687781]">Live status: En Route</span>
                    <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary btn-sm font-bold">
                        Try Reporting Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Final Call to Action -->
<section class="py-16 bg-[#17324D] text-white text-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-4xl font-extrabold mb-4">
            Ready to Support Emergency Coordination?
        </h2>
        <p class="text-base text-white/70 max-w-xl mx-auto mb-8">
            Create an account to track your submitted reports in real-time, or file an emergency alert immediately.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary btn-lg w-full sm:w-auto font-bold shadow-md">
                Report Emergency Now
            </a>
            <a href="{{ route('register') }}" class="btn btn-secondary btn-lg w-full sm:w-auto font-semibold">
                Create Free Citizen Account
            </a>
        </div>
    </div>
</section>
@endsection
