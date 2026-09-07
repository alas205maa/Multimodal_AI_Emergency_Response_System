@extends('layouts.app')

@section('title', 'Forgot Password — Emergency Response System')

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
            <h1 class="text-2xl font-bold text-[#17324D]">Reset Password</h1>
            <p class="text-xs sm:text-sm text-[#687781] mt-1">Enter your email and we'll dispatch a secure recovery link</p>
        </div>

        @if (session('status'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('forgot-password.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="form-input" placeholder="citizen@example.com">
                @error('email')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <button type="submit" class="btn btn-navy w-full py-2.5 font-bold shadow-sm">
                    Send Reset Link
                </button>
            </div>
        </form>

        <div class="mt-6 pt-6 border-t border-[#E2E7E5] text-center text-xs text-[#687781]">
            Remember your credentials? 
            <a href="{{ route('login') }}" class="font-bold text-[#1F8A89] hover:underline">Return to Login</a>
        </div>
    </div>
</div>
@endsection
