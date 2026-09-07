@extends('layouts.public')

@section('title', 'Contact Support — Multimodal AI Emergency Response System')

@section('content')
<!-- Header Banner -->
<section class="bg-[#17324D] text-white py-14 sm:py-16 border-b border-[#234566]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-xs font-bold text-teal-300 uppercase tracking-widest block mb-2">
            Inquiries & Assistance
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
            Contact Project Team
        </h1>
        <p class="text-sm sm:text-base text-white/70 max-w-xl mx-auto mt-3">
            Have questions about the system architecture, integration, or academic research? Reach out to our development team.
        </p>
    </div>
</section>

<section class="py-16 bg-[#F7F8F6]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Contact Info Sidebar -->
            <div class="space-y-6">
                <div class="card p-6 bg-white border border-[#E2E7E5]">
                    <h3 class="text-base font-bold text-[#17324D] mb-3">Project Office</h3>
                    <p class="text-sm text-[#687781] leading-relaxed mb-4">
                        Department of Computer Science & Artificial Intelligence, Emergency Systems Lab.
                    </p>
                    <div class="space-y-3 text-sm text-[#243746]">
                        <div class="flex items-center gap-2.5">
                            <span class="text-[#1F8A89]">📍</span>
                            <span>Riyadh, Saudi Arabia</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="text-[#1F8A89]">✉️</span>
                            <span>emergency-ai@university.edu.sa</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="text-[#1F8A89]">☎️</span>
                            <span>+966 11 467 0000</span>
                        </div>
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs leading-relaxed">
                    <strong>Critical Note:</strong> This contact form is for general and technical inquiries only. Do not use this form to report an active, immediate life emergency. Use the <a href="{{ route('citizen.reports.create') }}" class="font-bold underline text-[#C73E3E]">Emergency Reporting Form</a> or dial 911 / 998 directly.
                </div>
            </div>

            <!-- Contact Form -->
            <div class="md:col-span-2">
                <div class="card p-8 bg-white border border-[#E2E7E5]">
                    <h2 class="text-xl font-bold text-[#17324D] mb-6">Send an Inquiry</h2>

                    @if (session('contact_status'))
                        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
                            {{ session('contact_status') }}
                        </div>
                    @endif

                    <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Inquiry recorded. The project team will get back to you shortly.');" class="space-y-5">
                        @csrf
                        <div>
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" id="name" name="name" required class="form-input" placeholder="e.g., Dr. Abdulaziz Al-Saud">
                        </div>

                        <div>
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" name="email" required class="form-input" placeholder="name@domain.com">
                        </div>

                        <div>
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" id="subject" name="subject" required class="form-input" placeholder="e.g., AI Multimodal Fusion Evaluation Question">
                        </div>

                        <div>
                            <label for="message" class="form-label">Message</label>
                            <textarea id="message" name="message" rows="5" required class="form-textarea" placeholder="Detail your question, feedback, or collaboration interest..."></textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="btn btn-secondary w-full sm:w-auto font-semibold">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
