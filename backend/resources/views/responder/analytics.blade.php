@extends('layouts.responder')

@section('title', 'Analytics & Operations Metrics — Central Command')
@section('page_title', 'Operational Intelligence & Analytics')
@section('page_subtitle', 'Quantitative triage distributions, risk trends, and dispatch efficiency metrics')

@section('content')
<div class="space-y-6">
    <!-- Filter Strip -->
    <div class="card p-4 bg-white border border-[#E2E7E5]">
        <div class="flex flex-wrap items-center justify-between gap-3 text-xs">
            <div class="flex flex-wrap items-center gap-2">
                <span class="font-bold text-[#17324D]">Analytics Period:</span>
                <button type="button" class="px-3 py-1.5 rounded-lg bg-[#17324D] text-white font-semibold">Past 30 Days</button>
                <button type="button" class="px-3 py-1.5 rounded-lg bg-[#F7F8F6] text-[#687781] hover:text-[#17324D] border border-[#E2E7E5]">This Quarter</button>
                <button type="button" class="px-3 py-1.5 rounded-lg bg-[#F7F8F6] text-[#687781] hover:text-[#17324D] border border-[#E2E7E5]">Year to Date</button>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-[#687781]">Region: <strong>All Metropolitan Zones</strong></span>
            </div>
        </div>
    </div>

    <!-- Top KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="card p-6 bg-white border border-[#E2E7E5]">
            <span class="text-xs font-semibold text-[#687781] block mb-1">Average Response Latency</span>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-[#1F8A89] font-mono">{{ $avgResponseTime }}</span>
                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                    &darr; 1.2m vs Q2
                </span>
            </div>
            <p class="text-xs text-[#687781] mt-2">From verified report submission to first unit on scene</p>
        </div>

        <div class="card p-6 bg-white border border-[#E2E7E5]">
            <span class="text-xs font-semibold text-[#687781] block mb-1">Incident Resolution Rate</span>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-[#3A9D6E] font-mono">{{ $resolutionRate }}</span>
                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                    Optimal
                </span>
            </div>
            <p class="text-xs text-[#687781] mt-2">Successful triage and emergency containment</p>
        </div>

        <div class="card p-6 bg-white border border-[#E2E7E5]">
            <span class="text-xs font-semibold text-[#687781] block mb-1">AI Classification Confidence</span>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-[#17324D] font-mono">{{ $aiConfidenceAvg }}</span>
                <span class="text-xs font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-md border border-teal-200">
                    S1–S3 Fused
                </span>
            </div>
            <p class="text-xs text-[#687781] mt-2">Agreement between text narrative and vision signatures</p>
        </div>
    </div>

    <!-- Charts Grid: Emergencies Over Time & Emergency Types -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Emergencies Over Time (Monthly Trend) -->
        <div class="card p-6 bg-white border border-[#E2E7E5]">
            <div class="flex items-center justify-between pb-3 mb-6 border-b border-[#E2E7E5]">
                <div>
                    <h3 class="text-base font-bold text-[#17324D]">Emergencies Over Time</h3>
                    <p class="text-xs text-[#687781]">Total verified incident intake per month</p>
                </div>
                <span class="text-xs font-bold text-[#1F8A89]">Trend: Normalized</span>
            </div>

            <!-- SVG Bar Chart -->
            <div class="h-64 flex items-end justify-between gap-4 pt-8 px-4 pb-2 border-b border-[#E2E7E5]">
                @foreach ($monthlyTrend as $point)
                    @php $heightPercent = min(100, ($point['count'] / 240) * 100); @endphp
                    <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end group">
                        <span class="text-xs font-mono font-bold text-[#17324D] opacity-0 group-hover:opacity-100 transition-opacity">
                            {{ $point['count'] }}
                        </span>
                        <div class="w-full max-w-[3rem] bg-[#17324D] hover:bg-[#1F8A89] rounded-t-lg transition-all" style="height: {{ $heightPercent }}%;"></div>
                        <span class="text-xs font-semibold text-[#687781]">{{ $point['month'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Emergency Types Distribution -->
        <div class="card p-6 bg-white border border-[#E2E7E5]">
            <div class="flex items-center justify-between pb-3 mb-6 border-b border-[#E2E7E5]">
                <div>
                    <h3 class="text-base font-bold text-[#17324D]">Emergency Types Breakdown</h3>
                    <p class="text-xs text-[#687781]">Categorized by Primary Hazard Domain</p>
                </div>
                <span class="text-xs font-bold text-[#687781]">Top: Fire & Smoke</span>
            </div>

            <div class="space-y-4 pt-2">
                @foreach ($byType as $typeName => $count)
                    @php $percent = round(($count / 100) * 100); @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold mb-1">
                            <span class="text-[#243746]">{{ $typeName }}</span>
                            <span class="font-mono text-[#17324D] font-bold">{{ $count }}% ({{ $count }} cases)</span>
                        </div>
                        <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-[#1F8A89]" style="width: {{ $percent }}%;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Risk Distribution & Status Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Risk Distribution -->
        <div class="card p-6 bg-white border border-[#E2E7E5]">
            <div class="flex items-center justify-between pb-3 mb-6 border-b border-[#E2E7E5]">
                <div>
                    <h3 class="text-base font-bold text-[#17324D]">Risk Level Distribution</h3>
                    <p class="text-xs text-[#687781]">Severity tiers determined by AI fusion engine</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                <!-- Critical -->
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200">
                    <span class="text-xs font-extrabold uppercase text-[#C73E3E] block mb-1">Critical</span>
                    <span class="text-2xl font-black text-[#C73E3E] font-mono">{{ $byRisk['Critical'] }}%</span>
                    <span class="text-[10px] text-rose-700 block mt-1">P1 Immediate</span>
                </div>

                <!-- High -->
                <div class="p-4 rounded-xl bg-orange-50 border border-orange-200">
                    <span class="text-xs font-extrabold uppercase text-[#E06B3C] block mb-1">High</span>
                    <span class="text-2xl font-black text-[#E06B3C] font-mono">{{ $byRisk['High'] }}%</span>
                    <span class="text-[10px] text-orange-700 block mt-1">P2 Priority</span>
                </div>

                <!-- Medium -->
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200">
                    <span class="text-xs font-extrabold uppercase text-[#B26A00] block mb-1">Medium</span>
                    <span class="text-2xl font-black text-[#B26A00] font-mono">{{ $byRisk['Medium'] }}%</span>
                    <span class="text-[10px] text-amber-700 block mt-1">P3 Standard</span>
                </div>

                <!-- Low -->
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200">
                    <span class="text-xs font-extrabold uppercase text-[#3A9D6E] block mb-1">Low</span>
                    <span class="text-2xl font-black text-[#3A9D6E] font-mono">{{ $byRisk['Low'] }}%</span>
                    <span class="text-[10px] text-emerald-700 block mt-1">P4 Minor</span>
                </div>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="card p-6 bg-white border border-[#E2E7E5]">
            <div class="flex items-center justify-between pb-3 mb-6 border-b border-[#E2E7E5]">
                <div>
                    <h3 class="text-base font-bold text-[#17324D]">Status Pipeline Distribution</h3>
                    <p class="text-xs text-[#687781]">Incident progression through operations lifecycle</p>
                </div>
            </div>

            <div class="space-y-3">
                @foreach ($byStatus as $statusName => $val)
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <x-status-badge :status="$statusName" />
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-32 sm:w-48 h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full bg-[#17324D]" style="width: {{ $val * 2.5 }}%;"></div>
                            </div>
                            <span class="font-mono font-bold text-[#17324D] w-8 text-right">{{ $val }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
