@extends('layouts.public')

@section('title', 'About — Multimodal AI Emergency Response System')

@section('content')
<!-- Header Banner -->
<section class="bg-[#17324D] text-white py-14 sm:py-20 border-b border-[#234566]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-xs font-bold text-teal-300 uppercase tracking-widest block mb-2">
            System Overview & Mission
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight">
            About the Emergency Response System
        </h1>
        <p class="text-base sm:text-lg text-white/70 max-w-2xl mx-auto mt-4">
            Bridging citizen distress reporting with artificial intelligence to optimize response speed and operational clarity.
        </p>
    </div>
</section>

<!-- Mission & Rationale -->
<section class="py-16 bg-white border-b border-[#E2E7E5]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div>
                <span class="text-xs font-bold text-[#1F8A89] uppercase tracking-wider block mb-2">The Challenge</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#17324D] mb-4">
                    Why Traditional Emergency Triage Needs Evolution
                </h2>
                <p class="text-sm text-[#687781] leading-relaxed mb-4">
                    Emergency call centers handle thousands of high-stress telephone calls daily. Callers are often in shock, struggling to articulate exact locations, hazardous materials, or casualty counts.
                </p>
                <p class="text-sm text-[#687781] leading-relaxed">
                    By providing an intelligent digital channel where text, photographic evidence, and precise GPS telemetry are fused simultaneously, emergency responders receive verified ground truth before wheels roll.
                </p>
            </div>

            <div class="card p-6 bg-[#F7F8F6] border border-[#E2E7E5]">
                <h3 class="text-base font-bold text-[#17324D] mb-3">Key Project Objectives</h3>
                <ul class="space-y-3 text-sm text-[#243746]">
                    <li class="flex items-start gap-2">
                        <span class="text-[#1F8A89] font-bold">✓</span>
                        <span>Reduce reporting latency through streamlined, accessible mobile interfaces.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-[#1F8A89] font-bold">✓</span>
                        <span>Eliminate geographical confusion with single-tap location detection.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-[#1F8A89] font-bold">✓</span>
                        <span>Extract structured hazards from unstructured narrative statements.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-[#1F8A89] font-bold">✓</span>
                        <span>Rank incident priorities objectively to allocate finite response resources.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Dual Roles: Citizen & Responder -->
<section class="py-16 bg-[#F7F8F6]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-12">
            <span class="text-xs font-bold text-[#1F8A89] uppercase tracking-wider block mb-2">Two Interlocking Roles</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-[#17324D]">
                Connecting Citizens and First Responders
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Citizen Card -->
            <div class="card p-8 bg-white border border-[#E2E7E5] flex flex-col">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-[#1F8A89] flex items-center justify-center font-bold text-lg mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#17324D] mb-2">Citizen Reporter</h3>
                <p class="text-sm text-[#687781] leading-relaxed mb-6">
                    Acts as the eyes and ears on the scene. Submits descriptions, photos, and location coordinates via an intuitive, low-stress interface and tracks progress from dispatch to final resolution.
                </p>
                <div class="mt-auto">
                    <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary btn-sm font-bold">
                        Report an Incident
                    </a>
                </div>
            </div>

            <!-- Responder Card -->
            <div class="card p-8 bg-white border border-[#E2E7E5] flex flex-col">
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-[#17324D] flex items-center justify-center font-bold text-lg mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#17324D] mb-2">Emergency Commander / Responder</h3>
                <p class="text-sm text-[#687781] leading-relaxed mb-6">
                    Operates inside a dedicated Command & Control Center. Views live prioritized incidents, reviews AI multimodal analysis, evaluates geospatial routes, and coordinates field teams.
                </p>
                <div class="mt-auto">
                    <a href="{{ route('responder.login') }}" class="btn btn-navy btn-sm font-bold">
                        Access Operations Portal
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
