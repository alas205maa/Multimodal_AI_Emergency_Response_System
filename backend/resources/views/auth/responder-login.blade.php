@extends('layouts.app')

@section('title', 'Emergency Response Portal — Authorized Access')

@section('layout_content')
<div class="min-h-screen flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 bg-[#13283D]">
    <div class="max-w-md w-full mx-auto card p-8 sm:p-10 bg-white border border-slate-200 shadow-2xl">
        <!-- Security Shield & Portal Branding -->
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-[#17324D] text-white mx-auto flex items-center justify-center font-black text-xl mb-4 shadow-lg border-2 border-teal-500/40">
                <svg class="w-7 h-7 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-[#17324D] text-xs font-bold uppercase tracking-wider mb-2">
                Emergency Response Portal
            </div>
            <h1 class="text-2xl font-bold text-[#17324D]">Command Center Access</h1>
            <p class="text-xs text-[#687781] mt-1">Authorized civil defense, paramedic & police operations personnel</p>
        </div>

        <form action="{{ route('responder.login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="badge_id" class="form-label">Service Badge ID / Officer Email</label>
                <input type="text" id="badge_id" name="badge_id" value="EMR-8842" required autofocus class="form-input" placeholder="e.g. EMR-8842 or commander@ops.gov.sa">
            </div>

            <div>
                <label for="password" class="form-label">Security Key</label>
                <input type="password" id="password" name="password" value="secretKey123" required class="form-input" placeholder="••••••••••••">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center text-[#687781]">
                    <input type="checkbox" checked class="w-4 h-4 rounded border-slate-300 text-[#1F8A89]">
                    <span class="ml-2">Keep station terminal active</span>
                </label>
                <span class="text-teal-700 font-semibold">2FA Enforced</span>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn btn-navy w-full py-2.5 font-bold shadow-md">
                    Access Operations Dashboard &rarr;
                </button>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-[#E2E7E5] text-center text-xs text-[#687781]">
            <p class="text-[11px] text-slate-400 mb-2">Access is logged and audited for emergency operational integrity.</p>
            <a href="{{ route('login') }}" class="font-bold text-[#1F8A89] hover:underline">Switch to Citizen Portal</a>
        </div>
    </div>
</div>
@endsection
