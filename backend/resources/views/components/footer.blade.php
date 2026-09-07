<footer class="bg-[#17324D] text-white/80 pt-12 pb-8 border-t border-[#234566]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8 pb-8 border-b border-white/10">
            <!-- Brand & Mission -->
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-xl bg-[#F05D5E] flex items-center justify-center text-white shadow-sm font-black text-lg">
                        AI
                    </div>
                    <div>
                        <span class="text-lg font-bold text-white tracking-tight block">
                            Emergency Response System
                        </span>
                        <span class="text-xs text-teal-300 font-medium tracking-wide uppercase">
                            Multimodal AI Assistance
                        </span>
                    </div>
                </div>
                <p class="text-sm text-white/70 max-w-md leading-relaxed mb-4">
                    A next-generation emergency triage and coordination platform combining description text, visual media, and geospatial coordinates for rapid risk prioritization and responder deployment.
                </p>
                <div class="bg-white/5 rounded-lg p-3 border border-white/10 max-w-md">
                    <p class="text-xs text-amber-200 flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span><strong>Emergency Notice:</strong> In immediate, severe life hazard situations, always call national dispatch (911 / 998) without delay.</span>
                    </p>
                </div>
            </div>

            <!-- Quick Navigation -->
            <div>
                <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-3">System Navigation</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">About the Project</a></li>
                    <li><a href="{{ route('how-it-works') }}" class="hover:text-white transition-colors">How It Works</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contact & Support</a></li>
                </ul>
            </div>

            <!-- Portals -->
            <div>
                <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-3">Access Portals</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Citizen Login</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Citizen Registration</a></li>
                    <li><a href="{{ route('citizen.reports.create') }}" class="text-[#F05D5E] font-semibold hover:underline">Report an Incident</a></li>
                    <li><a href="{{ route('responder.login') }}" class="text-teal-300 font-semibold hover:underline">Responder Portal</a></li>
                </ul>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-white/50 gap-4">
            <p>&copy; {{ date('Y') }} Multimodal AI Emergency Response System. University Capstone Project.</p>
            <div class="flex items-center gap-6">
                <span>Designed for Calmer, Faster Emergency Response</span>
            </div>
        </div>
    </div>
</footer>
