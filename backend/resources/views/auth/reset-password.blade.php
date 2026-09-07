@extends('layouts.app')

@section('title', 'Set New Password — Emergency Response System')

@section('layout_content')
<div class="min-h-screen flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 bg-[#F7F8F6]">
    <div class="max-w-md w-full mx-auto card p-8 sm:p-10 bg-white border border-[#E2E7E5] shadow-xl">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-[#17324D] no-underline mb-4">
                <div class="w-10 h-10 rounded-xl bg-[#F05D5E] flex items-center justify-center text-white font-extrabold text-lg shadow-sm">
                    AI
                </div>
                <span class="text-lg font-bold">Emergency Response</span>
            </a>
            <h1 class="text-2xl font-bold text-[#17324D]">Set New Password</h1>
            <p class="text-xs sm:text-sm text-[#687781] mt-1">Choose a strong, secure password for your account</p>
        </div>

        <form action="{{ route('reset-password.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required class="form-input">
            </div>

            <div>
                <label for="password" class="form-label">New Password</label>
                <input type="password" id="password" name="password" required autofocus class="form-input" placeholder="Minimum 8 characters">
                @error('password')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="form-input" placeholder="Re-enter new password">
            </div>

            <div class="pt-2">
                <button type="submit" class="btn btn-secondary w-full py-2.5 font-bold shadow-sm">
                    Update Password & Sign In
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
