@extends('layouts.app')

@section('layout_content')
<div class="h-full flex flex-col md:flex-row min-h-screen bg-[#F7F8F6]">
    <!-- Mobile Backdrop -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/50 z-40 hidden md:hidden"></div>

    <!-- Desktop & Mobile Responder Sidebar: Deep Navy #17324D -->
    <aside id="responder-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#17324D] text-white flex flex-col transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0 md:static md:inset-auto md:shrink-0 border-r border-[#234566]">
        <!-- Brand Header -->
        <div class="h-20 flex items-center px-6 gap-3 border-b border-white/10">
            <div class="w-10 h-10 rounded-xl bg-[#F05D5E] flex items-center justify-center text-white font-extrabold shadow-md text-lg">
                OPS
            </div>
            <div>
                <span class="text-sm font-bold tracking-tight text-white block">
                    Emergency Operations
                </span>
                <span class="text-[10px] text-teal-300 font-semibold uppercase tracking-wider block">
                    Commander Portal
                </span>
            </div>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 px-3 py-6 space-y-1.5 overflow-y-auto">
            <div class="px-3 pb-2 text-[11px] font-bold text-white/40 uppercase tracking-wider">
                Real-Time Triage
            </div>

            <!-- Dashboard -->
            <a href="{{ route('responder.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('responder.dashboard') ? 'bg-[#1F8A89] text-white shadow-sm' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span>Dashboard</span>
            </a>

            <!-- Emergencies -->
            <a href="{{ route('responder.emergencies.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('responder.emergencies.*') ? 'bg-[#1F8A89] text-white shadow-sm' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span class="flex-1">Emergencies</span>
                <span class="px-1.5 py-0.5 text-[10px] font-bold rounded-full bg-[#C73E3E] text-white">
                    LIVE
                </span>
            </a>

            <!-- Emergency Map -->
            <a href="{{ route('responder.map') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('responder.map') ? 'bg-[#1F8A89] text-white shadow-sm' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                <span>Emergency Map</span>
            </a>

            <!-- Analytics -->
            <a href="{{ route('responder.analytics') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('responder.analytics') ? 'bg-[#1F8A89] text-white shadow-sm' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Analytics</span>
            </a>

            <!-- Notifications -->
            <a href="{{ route('responder.notifications') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('responder.notifications') ? 'bg-[#1F8A89] text-white shadow-sm' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <span class="flex-1">Notifications</span>
                <span class="w-2 h-2 rounded-full bg-[#F05D5E]"></span>
            </a>

            <div class="pt-4 px-3 pb-2 text-[11px] font-bold text-white/40 uppercase tracking-wider">
                Management
            </div>

            <!-- Profile -->
            <a href="{{ route('responder.profile') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('responder.profile') ? 'bg-[#1F8A89] text-white shadow-sm' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Profile</span>
            </a>

            <!-- Settings -->
            <a href="{{ route('responder.settings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('responder.settings') ? 'bg-[#1F8A89] text-white shadow-sm' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Settings</span>
            </a>
        </nav>

        <!-- Officer Footer & Logout -->
        <div class="p-4 border-t border-white/10 bg-[#132a41]">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-lg bg-teal-700 text-white font-bold flex items-center justify-center text-xs">
                    TM
                </div>
                <div class="truncate">
                    <span class="text-xs font-bold text-white block truncate">Capt. Tariq Mansoor</span>
                    <span class="text-[10px] text-white/50 block">HQ Operations Lead</span>
                </div>
            </div>
            <button type="button" data-modal-target="logout-modal" class="btn btn-outline btn-sm !w-full !text-white/80 !border-white/20 hover:!bg-white/10 justify-start">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </button>
        </div>
    </aside>

    <!-- Main Workspace -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Responder Topbar -->
        <header class="h-20 bg-white border-b border-[#E2E7E5] px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle-btn" type="button" class="md:hidden p-2 rounded-lg text-[#243746] hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-base sm:text-xl font-bold text-[#17324D] leading-tight">
                        @yield('page_title', 'Emergency Operations Dashboard')
                    </h1>
                    <p class="text-xs text-[#687781] hidden sm:block">
                        @yield('page_subtitle', 'Real-time incident ingestion, multimodal AI telemetry, and unit dispatch')
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 sm:gap-6">
                <!-- Live Operations Clock -->
                <div class="hidden lg:flex items-center gap-2 bg-[#F7F8F6] px-3.5 py-1.5 rounded-lg border border-[#E2E7E5] text-xs font-semibold text-[#17324D]">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span id="responder-live-clock">00:00:00</span>
                    <span class="text-slate-400">|</span>
                    <span class="text-[#687781]">{{ date('M d, Y') }}</span>
                </div>

                <!-- Notifications Button -->
                <a href="{{ route('responder.notifications') }}" class="relative p-2 rounded-lg text-[#687781] hover:text-[#17324D] hover:bg-slate-100 transition-colors" title="Notifications">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-[#C73E3E]"></span>
                </a>

                <!-- Profile Link -->
                <a href="{{ route('responder.profile') }}" class="flex items-center gap-2 text-xs font-bold text-[#17324D] hover:text-[#1F8A89] transition-colors">
                    <div class="w-8 h-8 rounded-full bg-[#17324D] text-white flex items-center justify-center text-xs">
                        TM
                    </div>
                    <span class="hidden sm:inline">Capt. Mansoor</span>
                </a>
            </div>
        </header>

        <!-- Dynamic Body Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
            @if (session('status'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
@endsection
