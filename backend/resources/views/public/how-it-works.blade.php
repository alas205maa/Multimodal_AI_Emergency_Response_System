@extends('layouts.public')

@section('title', 'How It Works — Multimodal AI Emergency Response System')

@section('content')
<!-- Header Banner -->
<section class="bg-[#17324D] text-white py-14 sm:py-20 border-b border-[#234566]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-xs font-bold text-teal-300 uppercase tracking-widest block mb-2">
            Step-by-Step Flow
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight">
            How The System Works
        </h1>
        <p class="text-base sm:text-lg text-white/70 max-w-2xl mx-auto mt-4">
            From the moment an incident is observed to field unit arrival and resolution: an 8-step synchronized lifecycle.
        </p>
    </div>
</section>

<!-- 8 Steps Timeline -->
<section class="py-16 sm:py-24 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-8 relative before:absolute before:inset-0 before:left-6 sm:before:left-8 before:h-full before:w-0.5 before:bg-[#E2E7E5]">
            <!-- Step 1: Sign in -->
            <div class="relative flex items-start gap-6 group">
                <div class="w-12 sm:w-16 h-12 sm:h-16 rounded-2xl bg-[#17324D] text-white font-extrabold text-lg sm:text-xl flex items-center justify-center shrink-0 shadow-md relative z-10">
                    01
                </div>
                <div class="card p-6 bg-white border border-[#E2E7E5] flex-1">
                    <span class="text-xs font-bold text-[#1F8A89] uppercase tracking-wider block mb-1">Citizen Step</span>
                    <h3 class="text-lg font-bold text-[#17324D] mb-1">Sign In or Access Fast Report</h3>
                    <p class="text-sm text-[#687781] leading-relaxed">
                        Access the platform from any mobile device or browser. Existing accounts provide persistent incident history and live updates.
                    </p>
                </div>
            </div>

            <!-- Step 2: Submit emergency info -->
            <div class="relative flex items-start gap-6 group">
                <div class="w-12 sm:w-16 h-12 sm:h-16 rounded-2xl bg-[#17324D] text-white font-extrabold text-lg sm:text-xl flex items-center justify-center shrink-0 shadow-md relative z-10">
                    02
                </div>
                <div class="card p-6 bg-white border border-[#E2E7E5] flex-1">
                    <span class="text-xs font-bold text-[#1F8A89] uppercase tracking-wider block mb-1">Citizen Step</span>
                    <h3 class="text-lg font-bold text-[#17324D] mb-1">Initiate Emergency Report</h3>
                    <p class="text-sm text-[#687781] leading-relaxed">
                        Click the prominent coral <span class="font-bold text-[#F05D5E]">"Report Emergency"</span> button to enter the low-stress reporting interface designed for high-adrenaline situations.
                    </p>
                </div>
            </div>

            <!-- Step 3: Send description -->
            <div class="relative flex items-start gap-6 group">
                <div class="w-12 sm:w-16 h-12 sm:h-16 rounded-2xl bg-[#17324D] text-white font-extrabold text-lg sm:text-xl flex items-center justify-center shrink-0 shadow-md relative z-10">
                    03
                </div>
                <div class="card p-6 bg-white border border-[#E2E7E5] flex-1">
                    <span class="text-xs font-bold text-[#1F8A89] uppercase tracking-wider block mb-1">Citizen Step</span>
                    <h3 class="text-lg font-bold text-[#17324D] mb-1">Describe What Is Happening</h3>
                    <p class="text-sm text-[#687781] leading-relaxed">
                        Provide a clear description of the incident. Prompts guide the user to note people involved, visible smoke/flames, trapped victims, or hazardous materials.
                    </p>
                </div>
            </div>

            <!-- Step 4: Upload image -->
            <div class="relative flex items-start gap-6 group">
                <div class="w-12 sm:w-16 h-12 sm:h-16 rounded-2xl bg-[#17324D] text-white font-extrabold text-lg sm:text-xl flex items-center justify-center shrink-0 shadow-md relative z-10">
                    04
                </div>
                <div class="card p-6 bg-white border border-[#E2E7E5] flex-1">
                    <span class="text-xs font-bold text-[#1F8A89] uppercase tracking-wider block mb-1">Citizen Step</span>
                    <h3 class="text-lg font-bold text-[#17324D] mb-1">Attach Photographic Evidence</h3>
                    <p class="text-sm text-[#687781] leading-relaxed">
                        Snap a photo directly with the mobile camera or upload an existing image. Instant image preview confirms the upload before sending.
                    </p>
                </div>
            </div>

            <!-- Step 5: Share location -->
            <div class="relative flex items-start gap-6 group">
                <div class="w-12 sm:w-16 h-12 sm:h-16 rounded-2xl bg-[#17324D] text-white font-extrabold text-lg sm:text-xl flex items-center justify-center shrink-0 shadow-md relative z-10">
                    05
                </div>
                <div class="card p-6 bg-white border border-[#E2E7E5] flex-1">
                    <span class="text-xs font-bold text-[#1F8A89] uppercase tracking-wider block mb-1">Citizen Step</span>
                    <h3 class="text-lg font-bold text-[#17324D] mb-1">Share Exact GPS Location</h3>
                    <p class="text-sm text-[#687781] leading-relaxed">
                        Tap "Use My Current Location". High-accuracy device coordinates are retrieved and plotted onto the emergency map canvas with feedback states.
                    </p>
                </div>
            </div>

            <!-- Step 6: System analyzes -->
            <div class="relative flex items-start gap-6 group">
                <div class="w-12 sm:w-16 h-12 sm:h-16 rounded-2xl bg-teal-600 text-white font-extrabold text-lg sm:text-xl flex items-center justify-center shrink-0 shadow-md relative z-10">
                    06
                </div>
                <div class="card p-6 bg-teal-50/40 border border-teal-200 flex-1">
                    <span class="text-xs font-bold text-teal-800 uppercase tracking-wider block mb-1">System Intelligence</span>
                    <h3 class="text-lg font-bold text-[#17324D] mb-1">Multimodal AI Analyzes the Emergency</h3>
                    <p class="text-sm text-[#687781] leading-relaxed">
                        Text NLP and Computer Vision analyze the input simultaneously. Fusion generates an overall urgency score (0–10), classified risk level, and recommended response assets.
                    </p>
                </div>
            </div>

            <!-- Step 7: Responders receive -->
            <div class="relative flex items-start gap-6 group">
                <div class="w-12 sm:w-16 h-12 sm:h-16 rounded-2xl bg-[#C73E3E] text-white font-extrabold text-lg sm:text-xl flex items-center justify-center shrink-0 shadow-md relative z-10">
                    07
                </div>
                <div class="card p-6 bg-rose-50/40 border border-rose-200 flex-1">
                    <span class="text-xs font-bold text-rose-700 uppercase tracking-wider block mb-1">Responder Coordination</span>
                    <h3 class="text-lg font-bold text-[#17324D] mb-1">Responders Receive Prioritized Intelligence</h3>
                    <p class="text-sm text-[#687781] leading-relaxed">
                        Command centers see the critical incident pop up on the live map and incident board. Appropriate units (Civil Defense, Paramedics, Police) are dispatched with optimized routes.
                    </p>
                </div>
            </div>

            <!-- Step 8: Citizen tracks -->
            <div class="relative flex items-start gap-6 group">
                <div class="w-12 sm:w-16 h-12 sm:h-16 rounded-2xl bg-[#3A9D6E] text-white font-extrabold text-lg sm:text-xl flex items-center justify-center shrink-0 shadow-md relative z-10">
                    08
                </div>
                <div class="card p-6 bg-emerald-50/40 border border-emerald-200 flex-1">
                    <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block mb-1">Full Transparency</span>
                    <h3 class="text-lg font-bold text-[#17324D] mb-1">Citizen Tracks Report Status in Real Time</h3>
                    <p class="text-sm text-[#687781] leading-relaxed">
                        The reporter tracks progress through a visual timeline: <em>Submitted &rarr; Received &rarr; Analyzing &rarr; Assigned &rarr; In Progress &rarr; Resolved</em>, providing reassurance when it matters most.
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-16 pt-8 border-t border-[#E2E7E5]">
            <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary btn-lg font-bold shadow-md">
                Begin Emergency Report
            </a>
        </div>
    </div>
</section>
@endsection
