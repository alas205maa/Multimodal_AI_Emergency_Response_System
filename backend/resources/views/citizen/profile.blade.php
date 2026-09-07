@extends('layouts.citizen')

@section('title', 'Citizen Profile — Multimodal AI Emergency Response')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header -->
    <div class="border-b border-[#E2E7E5] pb-4">
        <h1 class="text-2xl font-extrabold text-[#17324D] tracking-tight">
            Citizen Profile & Security
        </h1>
        <p class="text-xs sm:text-sm text-[#687781] mt-1">
            Manage your personal contact details and security credentials.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Profile Card Summary -->
        <div class="space-y-6">
            <div class="card p-6 bg-white border border-[#E2E7E5] text-center">
                <div class="w-20 h-20 rounded-full bg-teal-600 text-white font-extrabold text-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                    {{ strtoupper(substr($user->name ?? 'C', 0, 2)) }}
                </div>
                <h3 class="text-lg font-bold text-[#17324D]">{{ $user->name ?? 'Citizen Member' }}</h3>
                <p class="text-xs text-[#687781] mt-0.5">{{ $user->email ?? 'citizen@example.com' }}</p>
                <div class="mt-4 pt-4 border-t border-[#E2E7E5]">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Verified Citizen Account
                    </span>
                </div>
            </div>
        </div>

        <!-- Forms: Edit Profile & Change Password -->
        <div class="md:col-span-2 space-y-6">
            <!-- Edit Profile -->
            <div class="card p-6 sm:p-8 bg-white border border-[#E2E7E5]">
                <h3 class="text-base font-bold text-[#17324D] mb-4 pb-2 border-b border-[#E2E7E5]">
                    Personal Information
                </h3>
                <form action="{{ route('citizen.profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="form-label text-xs">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required class="form-input">
                    </div>

                    <div>
                        <label for="email" class="form-label text-xs">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required class="form-input">
                    </div>

                    <div class="pt-2 text-right">
                        <button type="submit" class="btn btn-secondary btn-sm font-semibold">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="card p-6 sm:p-8 bg-white border border-[#E2E7E5]">
                <h3 class="text-base font-bold text-[#17324D] mb-4 pb-2 border-b border-[#E2E7E5]">
                    Change Account Password
                </h3>
                <form action="{{ route('citizen.profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="form-label text-xs">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required class="form-input">
                    </div>

                    <div>
                        <label for="password" class="form-label text-xs">New Password</label>
                        <input type="password" id="password" name="password" required class="form-input" placeholder="Minimum 8 characters">
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label text-xs">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="form-input">
                    </div>

                    <div class="pt-2 text-right">
                        <button type="submit" class="btn btn-navy btn-sm font-semibold">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
