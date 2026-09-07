@extends('layouts.responder')

@section('title', 'Commander Profile — Central Command')
@section('page_title', 'Responder Profile')
@section('page_subtitle', 'Authorized personnel identity, service credentials, and station jurisdiction')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Officer Badge Card -->
        <div class="space-y-6">
            <div class="card p-6 bg-white border border-[#E2E7E5] text-center">
                <div class="w-20 h-20 rounded-2xl bg-[#17324D] text-white font-black text-2xl flex items-center justify-center mx-auto mb-4 shadow-md border-2 border-teal-500/40">
                    TM
                </div>
                <h3 class="text-lg font-bold text-[#17324D]">{{ $responder->name }}</h3>
                <p class="text-xs text-[#1F8A89] font-bold mt-0.5">{{ $responder->role }}</p>
                <span class="inline-block mt-2 font-mono text-xs px-2.5 py-1 rounded bg-slate-100 text-[#17324D] font-bold">
                    Badge: {{ $responder->badge_id }}
                </span>

                <div class="mt-6 pt-4 border-t border-[#E2E7E5] text-left space-y-2.5 text-xs">
                    <div>
                        <span class="text-[#687781] block">Assigned Station:</span>
                        <strong class="text-[#17324D]">{{ $responder->station }}</strong>
                    </div>
                    <div>
                        <span class="text-[#687781] block">Department:</span>
                        <strong class="text-[#17324D]">{{ $responder->department }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile & Credentials -->
        <div class="md:col-span-2 space-y-6">
            <!-- Information Update -->
            <div class="card p-6 sm:p-8 bg-white border border-[#E2E7E5]">
                <h3 class="text-base font-bold text-[#17324D] mb-4 pb-2 border-b border-[#E2E7E5]">
                    Officer Contact Details
                </h3>

                <form action="{{ route('responder.profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="form-label text-xs">Commander Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $responder->name) }}" required class="form-input">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="form-label text-xs">Official Gov Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $responder->email) }}" required class="form-input">
                        </div>
                        <div>
                            <label for="phone" class="form-label text-xs">Direct Comms Hotline</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $responder->phone) }}" required class="form-input">
                        </div>
                    </div>

                    <div class="pt-2 text-right">
                        <button type="submit" class="btn btn-secondary btn-sm font-semibold">
                            Save Officer Details
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="card p-6 sm:p-8 bg-white border border-[#E2E7E5]">
                <h3 class="text-base font-bold text-[#17324D] mb-4 pb-2 border-b border-[#E2E7E5]">
                    Rotate Security Key
                </h3>

                <form action="{{ route('responder.profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="form-label text-xs">Current Security Key</label>
                        <input type="password" id="current_password" name="current_password" required class="form-input">
                    </div>

                    <div>
                        <label for="password" class="form-label text-xs">New High-Entropy Key</label>
                        <input type="password" id="password" name="password" required class="form-input" placeholder="Minimum 12 characters recommended">
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label text-xs">Confirm New Key</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="form-input">
                    </div>

                    <div class="pt-2 text-right">
                        <button type="submit" class="btn btn-navy btn-sm font-semibold">
                            Update Security Key
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
