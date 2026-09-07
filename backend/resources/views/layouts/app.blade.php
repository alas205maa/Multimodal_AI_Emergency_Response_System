<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Multimodal AI Emergency Response System')</title>
    <meta name="description" content="@yield('meta_description', 'Intelligent, multimodal citizen emergency reporting and real-time responder prioritization system.')">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Core Styles & Vite Assets -->
    @if (file_exists(public_path('build/manifest.json')) || app()->environment('local'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        /* Standalone resilient fallback styles */
        :root {
            --color-brand-navy: #17324D;
            --color-brand-teal: #1F8A89;
            --color-cta-coral: #F05D5E;
            --color-critical-red: #C73E3E;
            --color-risk-high: #E06B3C;
            --color-risk-medium: #E6A23C;
            --color-risk-low: #3A9D6E;
            --color-bg-page: #F7F8F6;
            --color-bg-card: #FFFFFF;
            --color-text-primary: #243746;
            --color-text-secondary: #687781;
            --color-border: #E2E7E5;
        }
        body {
            background-color: #F7F8F6;
            color: #243746;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 0;
        }
    </style>

    @stack('styles')
</head>
<body class="h-full flex flex-col antialiased">
    @yield('layout_content')

    <!-- Universal Logout Confirmation Modal -->
    <div id="logout-modal" class="modal-backdrop hidden" role="dialog" aria-modal="true" aria-labelledby="logout-modal-title">
        <div class="modal-dialog p-6 bg-white rounded-2xl shadow-xl border border-[#E2E7E5] max-w-md w-full mx-4">
            <div class="flex items-center gap-3 text-[#17324D] mb-4">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-[#17324D]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <div>
                    <h3 id="logout-modal-title" class="text-lg font-bold text-[#17324D]">Confirm Logout</h3>
                    <p class="text-xs text-[#687781]">End your current session</p>
                </div>
            </div>

            <p class="text-sm text-[#687781] mb-6">
                Are you sure you want to log out of the Emergency Response System? Any unsaved report drafts will be discarded.
            </p>

            <div class="flex items-center justify-end gap-3">
                <button type="button" data-modal-close class="btn btn-outline text-sm">
                    Cancel
                </button>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-navy text-sm">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
