@extends('layouts.citizen')

@section('title', 'Emergency Report Submitted — Status Received')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 py-6 sm:py-10">
    <!-- Success Confirmation Card -->
    <div class="card p-8 sm:p-10 bg-white border border-[#E2E7E5] shadow-xl text-center">
        <!-- Success Icon -->
        <div class="w-16 h-16 rounded-full bg-emerald-100 text-[#3A9D6E] flex items-center justify-center mx-auto mb-6 shadow-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-[#3A9D6E] text-xs font-bold uppercase tracking-wider mb-2 border border-emerald-200">
            Triage In Progress
        </span>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#17324D] tracking-tight mb-2">
            Emergency Report Submitted
        </h1>

        <p class="text-sm text-[#687781] max-w-md mx-auto mb-8 leading-relaxed">
            Your incident report has been securely registered in the central system. First responders and automated multimodal AI pipelines have been alerted.
        </p>

        <!-- Incident Metadata Breakdown -->
        <div class="card p-6 bg-[#F7F8F6] border border-[#E2E7E5] text-left space-y-4 mb-8">
            <div class="flex items-center justify-between pb-3 border-b border-[#E2E7E5]">
                <span class="text-xs font-semibold text-[#687781]">Report ID:</span>
                <span class="text-base font-extrabold text-[#17324D] font-mono">#{{ $reportId }}</span>
            </div>

            <div class="flex items-center justify-between pb-3 border-b border-[#E2E7E5]">
                <span class="text-xs font-semibold text-[#687781]">Submission Time:</span>
                <span class="text-sm font-semibold text-[#243746]">{{ $submissionTime }}</span>
            </div>

            <div class="flex items-center justify-between pb-3 border-b border-[#E2E7E5]">
                <span class="text-xs font-semibold text-[#687781]">Current Status:</span>
                <x-status-badge status="Received" />
            </div>

            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#687781]">Reported Location:</span>
                <span class="text-xs font-mono font-bold text-[#17324D]">
                    {{ $report->latitude ?? '24.713556' }}, {{ $report->longitude ?? '46.675295' }}
                </span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('citizen.reports.show', $reportId) }}" class="btn btn-primary btn-lg font-bold w-full sm:w-auto shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Track Report
            </a>
            <a href="{{ route('citizen.dashboard') }}" class="btn btn-outline btn-lg font-semibold w-full sm:w-auto">
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
