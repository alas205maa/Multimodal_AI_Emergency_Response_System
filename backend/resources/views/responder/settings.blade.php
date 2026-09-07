@extends('layouts.responder')

@section('title', 'Operations Settings — Central Command')
@section('page_title', 'System & Operations Settings')
@section('page_subtitle', 'Configure terminal behavior, audio alert triggers, map styles, and emergency escalations')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <form action="{{ route('responder.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- SECTION 1: Notifications & Alert Preferences -->
        <div class="card p-6 bg-white border border-[#E2E7E5]">
            <h3 class="text-base font-bold text-[#17324D] mb-4 pb-2 border-b border-[#E2E7E5]">
                Alert & Triage Triggers
            </h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm font-semibold text-[#17324D] block">Audible Tone for Critical Incidents</span>
                        <span class="text-xs text-[#687781] block">Sound priority alert frequency upon P1 critical fire or collapse detection</span>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 rounded border-slate-300 text-[#1F8A89]">
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <div>
                        <span class="text-sm font-semibold text-[#17324D] block">Critical Alert Banner Flashing</span>
                        <span class="text-xs text-[#687781] block">Keep banner solid without flashing (strict accessibility compliance)</span>
                    </div>
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                        Enforced Off
                    </span>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <div>
                        <span class="text-sm font-semibold text-[#17324D] block">SMS Escalations to Duty Chief</span>
                        <span class="text-xs text-[#687781] block">Dispatches SMS broadcast if critical incident remains unacknowledged > 2 mins</span>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 rounded border-slate-300 text-[#1F8A89]">
                </div>
            </div>
        </div>

        <!-- SECTION 2: Display & Map Preferences -->
        <div class="card p-6 bg-white border border-[#E2E7E5]">
            <h3 class="text-base font-bold text-[#17324D] mb-4 pb-2 border-b border-[#E2E7E5]">
                Display & Map Rendering
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="form-label text-xs">Automatic Live Refresh Interval</label>
                    <select class="form-select text-xs">
                        <option value="5">Every 5 seconds</option>
                        <option value="15" selected>Every 15 seconds (Recommended)</option>
                        <option value="30">Every 30 seconds</option>
                        <option value="60">Every 60 seconds</option>
                    </select>
                </div>

                <div>
                    <label class="form-label text-xs">Tactical Map Contrast Mode</label>
                    <select class="form-select text-xs">
                        <option value="high-contrast" selected>High Contrast Operations Grid</option>
                        <option value="satellite">Satellite Hybrid Overlay</option>
                        <option value="topographic">Topographic Elevation Mesh</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- SECTION 3: Account & Session Control -->
        <div class="card p-6 bg-white border border-[#E2E7E5]">
            <h3 class="text-base font-bold text-[#17324D] mb-4 pb-2 border-b border-[#E2E7E5]">
                Terminal Session & Security
            </h3>

            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm font-semibold text-[#17324D] block">End Operational Session</span>
                    <span class="text-xs text-[#687781] block">Log out of the tactical commander workstation</span>
                </div>
                <button type="button" data-modal-target="logout-modal" class="btn btn-outline btn-sm font-semibold text-xs">
                    Logout Terminal
                </button>
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-navy font-bold shadow-sm">
                Save Operational Preferences
            </button>
        </div>
    </form>
</div>
@endsection
