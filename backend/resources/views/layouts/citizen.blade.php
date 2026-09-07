@extends('layouts.app')

@section('layout_content')
<!-- Citizen Application Header -->
<header class="bg-[#17324D] text-white sticky top-0 z-40 shadow-sm border-b border-[#234566]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">
            <!-- Brand Logo -->
            <div class="flex items-center gap-8">
                <a href="{{ route('citizen.dashboard') }}" class="flex items-center gap-3 text-white no-underline">
                    <div class="w-10 h-10 rounded-xl bg-[#F05D5E] flex items-center justify-center text-white shadow-md font-extrabold text-lg">
                        AI
                    </div>
                    <div class="leading-tight">
                        <span class="text-base sm:text-lg font-bold tracking-tight block">
                            Emergency Response
                        </span>
                        <span class="text-[11px] text-teal-300 font-semibold tracking-wider uppercase block">
                            Citizen Portal
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-white/80">
                    <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                    <a href="{{ route('citizen.dashboard') }}" class="hover:text-white transition-colors {{ request()->routeIs('citizen.dashboard') ? 'text-white font-bold' : '' }}">My Dashboard</a>
                    <a href="{{ route('citizen.reports.index') }}" class="hover:text-white transition-colors {{ request()->routeIs('citizen.reports.*') && !request()->routeIs('citizen.reports.create') ? 'text-white font-bold' : '' }}">My Reports</a>
                    <a href="{{ route('citizen.notifications') }}" class="relative hover:text-white transition-colors {{ request()->routeIs('citizen.notifications') ? 'text-white font-bold' : '' }}">
                        Notifications
                        <span class="absolute -top-1.5 -right-3 w-4 h-4 rounded-full bg-[#F05D5E] text-white text-[10px] font-bold flex items-center justify-center">
                            2
                        </span>
                    </a>
                </nav>
            </div>

            <!-- Citizen Actions -->
            <div class="flex items-center gap-3">
                <!-- Most visible CTA: Report Emergency using Coral #F05D5E -->
                <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary text-xs sm:text-sm font-bold shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Report Emergency</span>
                </a>

                <!-- Profile Dropdown / Link -->
                <a href="{{ route('citizen.profile') }}" class="hidden sm:flex items-center gap-2 p-1.5 rounded-xl hover:bg-white/10 text-white transition-colors text-sm font-medium">
                    <div class="w-8 h-8 rounded-full bg-teal-600 text-white font-bold flex items-center justify-center text-xs">
                        FA
                    </div>
                    <span class="hidden lg:inline text-xs font-semibold">Profile</span>
                </a>

                <!-- Logout Trigger -->
                <button type="button" data-modal-target="logout-modal" class="p-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-colors" title="Logout">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>

                <!-- Mobile Menu Button -->
                <button type="button" onclick="document.getElementById('mobile-citizen-menu').classList.toggle('hidden')" class="md:hidden p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div id="mobile-citizen-menu" class="hidden md:hidden py-4 border-t border-white/10 space-y-3">
            <a href="{{ route('home') }}" class="block text-sm py-1 text-white/80 hover:text-white">Public Home</a>
            <a href="{{ route('citizen.dashboard') }}" class="block text-sm py-1 text-white/80 hover:text-white">My Dashboard</a>
            <a href="{{ route('citizen.reports.index') }}" class="block text-sm py-1 text-white/80 hover:text-white">My Reports</a>
            <a href="{{ route('citizen.notifications') }}" class="block text-sm py-1 text-white/80 hover:text-white">Notifications</a>
            <a href="{{ route('citizen.profile') }}" class="block text-sm py-1 text-white/80 hover:text-white">Citizen Profile</a>
            <div class="pt-3 border-t border-white/10">
                <button type="button" data-modal-target="logout-modal" class="btn btn-outline btn-sm !text-white !border-white/30 w-full">
                    Logout
                </button>
            </div>
        </div>
    </div>
</header>

<main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @yield('content')
</main>

<x-footer />
@endsection
