@extends('layouts.app')

@section('layout_content')
<!-- Public Navigation Bar -->
<header class="bg-[#17324D] text-white sticky top-0 z-40 shadow-sm border-b border-[#234566]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-white no-underline">
                <div class="w-10 h-10 rounded-xl bg-[#F05D5E] flex items-center justify-center text-white shadow-md font-extrabold text-lg">
                    AI
                </div>
                <div class="leading-tight">
                    <span class="text-base sm:text-lg font-bold tracking-tight block">
                        Emergency Response
                    </span>
                    <span class="text-[11px] text-teal-300 font-semibold tracking-wider uppercase block">
                        Multimodal AI System
                    </span>
                </div>
            </a>

            <!-- Desktop Links -->
            <nav class="hidden lg:flex items-center gap-6 text-sm font-medium text-white/80">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors {{ request()->routeIs('home') ? 'text-white font-bold' : '' }}">Home</a>
                <a href="{{ route('about') }}" class="hover:text-white transition-colors {{ request()->routeIs('about') ? 'text-white font-bold' : '' }}">About</a>
                <a href="{{ route('how-it-works') }}" class="hover:text-white transition-colors {{ request()->routeIs('how-it-works') ? 'text-white font-bold' : '' }}">How It Works</a>
                <a href="{{ route('contact') }}" class="hover:text-white transition-colors {{ request()->routeIs('contact') ? 'text-white font-bold' : '' }}">Contact</a>
            </nav>

            <!-- Actions -->
            <div class="hidden sm:flex items-center gap-3">
                <a href="{{ route('login') }}" class="btn btn-outline text-xs sm:text-sm !bg-transparent !text-white !border-white/20 hover:!bg-white/10">
                    Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-secondary text-xs sm:text-sm">
                    Register
                </a>
                <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary text-xs sm:text-sm font-bold shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Report Emergency
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="sm:hidden flex items-center gap-2">
                <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary btn-sm font-bold">
                    Report
                </a>
                <button type="button" onclick="document.getElementById('mobile-public-menu').classList.toggle('hidden')" class="p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div id="mobile-public-menu" class="hidden sm:hidden py-4 border-t border-white/10 space-y-3">
            <a href="{{ route('home') }}" class="block text-sm py-1 text-white/80 hover:text-white">Home</a>
            <a href="{{ route('about') }}" class="block text-sm py-1 text-white/80 hover:text-white">About</a>
            <a href="{{ route('how-it-works') }}" class="block text-sm py-1 text-white/80 hover:text-white">How It Works</a>
            <a href="{{ route('contact') }}" class="block text-sm py-1 text-white/80 hover:text-white">Contact</a>
            <div class="pt-3 border-t border-white/10 flex items-center gap-2">
                <a href="{{ route('login') }}" class="btn btn-outline btn-sm !text-white !border-white/30 flex-1">Login</a>
                <a href="{{ route('register') }}" class="btn btn-secondary btn-sm flex-1">Register</a>
            </div>
        </div>
    </div>
</header>

<main class="flex-1">
    @yield('content')
</main>

<x-footer />
@endsection
