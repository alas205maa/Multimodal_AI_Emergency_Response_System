@extends('layouts.app')

@section('title', 'Citizen Login — Emergency Response System')

@section('layout_content')
<div class="min-h-screen flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 bg-[#F7F8F6]">
    <div class="max-w-4xl w-full mx-auto grid grid-cols-1 md:grid-cols-2 rounded-2xl overflow-hidden shadow-xl border border-[#E2E7E5] bg-white">
        <!-- Visual Brand Side Panel -->
        <div class="bg-[#17324D] text-white p-8 sm:p-12 flex flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <a href="{{ route('home') }}" class="flex items-center gap-3 text-white no-underline mb-8">
                    <div class="w-10 h-10 rounded-xl bg-[#F05D5E] flex items-center justify-center text-white font-extrabold text-lg shadow-sm">
                        AI
                    </div>
                    <div>
                        <span class="text-lg font-bold block leading-tight">Emergency Response</span>
                        <span class="text-[11px] text-teal-300 font-semibold uppercase tracking-wider block">Citizen Access</span>
                    </div>
                </a>

                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight mb-4">
                    Fast Emergency Assistance.
                </h2>
                <p class="text-sm text-white/75 leading-relaxed mb-6">
                    Sign in to track your emergency reports, receive critical status notifications, and communicate directly with response teams.
                </p>

                <div class="space-y-3 text-xs text-teal-200">
                    <div class="flex items-center gap-2">
                        <span>✓</span> Real-time progress updates on dispatched units
                    </div>
                    <div class="flex items-center gap-2">
                        <span>✓</span> Verified location coordinate tracking
                    </div>
                    <div class="flex items-center gap-2">
                        <span>✓</span> Comprehensive personal emergency log
                    </div>
                </div>
            </div>

            <!-- Emergency Quick CTA -->
            <div class="mt-8 pt-6 border-t border-white/10 relative z-10">
                <p class="text-xs text-white/60 mb-2">Need to report an immediate emergency right now?</p>
                <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary btn-sm font-bold w-full">
                    Report Without Logging In
                </a>
            </div>
        </div>

        <!-- Login Form Panel -->
        <div class="p-8 sm:p-12 flex flex-col justify-center">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-[#17324D]">Sign In</h1>
                <p class="text-sm text-[#687781] mt-1">Enter your credentials to access your dashboard</p>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', 'citizen@emergency-system.sa') }}" required autofocus class="form-input" placeholder="you@example.com">
                    @error('email')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="form-label mb-0">Password</label>
                        <a href="{{ route('forgot-password') }}" class="text-xs font-semibold text-[#1F8A89] hover:underline">
                            Forgot Password?
                        </a>
                    </div>
                    <input type="password" id="password" name="password" value="password123" required class="form-input" placeholder="••••••••">
                    @error('password')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-slate-300 text-[#1F8A89] focus:ring-[#1F8A89]">
                    <label for="remember" class="ml-2 text-sm text-[#687781]">Remember Me</label>
                </div>

                <div>
                    <button type="submit" class="btn btn-navy w-full py-2.5 font-bold shadow-sm">
                        Sign In
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-[#E2E7E5] text-center text-xs text-[#687781]">
                Don't have an account yet? 
                <a href="{{ route('register') }}" class="font-bold text-[#1F8A89] hover:underline">Create Account</a>
            </div>

            <div class="mt-3 text-center text-xs">
                <a href="{{ route('responder.login') }}" class="text-slate-400 hover:text-[#17324D] transition-colors">
                    Emergency Responder / Commander Portal &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
