@props(['emergency' => null])

@if ($emergency)
<div class="critical-alert-banner p-4 sm:p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border border-[#ab3333]">
    <div class="flex items-start gap-3.5">
        <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center shrink-0 text-white mt-0.5">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <div class="flex flex-wrap items-center gap-2 mb-1">
                <span class="bg-white text-[#C73E3E] text-xs font-extrabold uppercase px-2 py-0.5 rounded tracking-wide">
                    Critical Emergency
                </span>
                <span class="text-xs text-white/80 font-medium">
                    Immediate attention required
                </span>
            </div>
            <h4 class="text-base sm:text-lg font-bold text-white tracking-tight">
                Incident #{{ $emergency->id }} — {{ $emergency->emergency_type ?? 'Structure Fire & Life Threat' }}
            </h4>
            <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs text-white/90 mt-1">
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    {{ $emergency->location_name ?? 'Riyadh Central Sector' }}
                </span>
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $emergency->time_ago ?? 'Just now' }}
                </span>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-2 self-stretch sm:self-auto shrink-0">
        <a href="{{ route('responder.emergencies.show', $emergency->id) }}" class="btn bg-white text-[#C73E3E] hover:bg-slate-50 font-bold px-4 py-2.5 rounded-lg text-sm shadow-sm transition-all text-center w-full sm:w-auto">
            View Emergency
            <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>
</div>
@endif
