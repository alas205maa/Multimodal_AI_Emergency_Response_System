@extends('layouts.citizen')

@section('title', 'Report Emergency — Multimodal AI Emergency Response')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Calm, Clear Screen Header -->
    <div class="border-b border-[#E2E7E5] pb-4">
        <div class="flex items-center gap-3">
            <span class="w-9 h-9 rounded-xl bg-[#F05D5E] text-white flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </span>
            <div>
                <h1 class="text-2xl font-extrabold text-[#17324D] tracking-tight">
                    Report an Emergency
                </h1>
                <p class="text-xs sm:text-sm text-[#687781]">
                    Stay calm. Complete the 3 simple steps below. Our response system prioritizes your distress call instantly.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Emergency Reporting Form -->
    <form id="emergency-report-form" action="{{ route('citizen.reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- SECTION 1: Text Description -->
        <div class="card p-6 bg-white border border-[#E2E7E5]">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-lg bg-[#17324D] text-white flex items-center justify-center text-xs font-bold">
                        1
                    </span>
                    <label for="description" class="text-base font-bold text-[#17324D] m-0">
                        Describe what is happening <span class="text-[#F05D5E]">*</span>
                    </label>
                </div>
                <span class="text-xs font-semibold text-[#1F8A89]">Required</span>
            </div>

            <textarea 
                id="description" 
                name="description" 
                rows="4" 
                required 
                class="form-textarea" 
                placeholder="Explain clearly: What happened? What is the current danger? Are there people injured or trapped? Are flames, smoke, water, or collapsed structures visible?">{{ old('description') }}</textarea>

            <div class="mt-3 p-3 rounded-lg bg-[#F7F8F6] border border-[#E2E7E5] text-xs text-[#687781] leading-relaxed">
                <strong>Helpful tips:</strong> Mention approximate number of people in danger, floor levels if inside a building, vehicle types if a road crash, and any flammable or electrical hazards in sight.
            </div>

            @error('description')
                <p class="text-xs text-[#C73E3E] font-semibold mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- SECTION 2: Emergency Image Upload -->
        <div class="card p-6 bg-white border border-[#E2E7E5]">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-lg bg-[#17324D] text-white flex items-center justify-center text-xs font-bold">
                        2
                    </span>
                    <h2 class="text-base font-bold text-[#17324D] m-0">
                        Emergency Image
                    </h2>
                </div>
                <span class="text-xs text-[#687781]">Recommended</span>
            </div>

            <p class="text-xs text-[#687781] mb-4">
                A photograph helps computer-vision models verify fire plumes, structural damage, water depth, and vehicle impacts.
            </p>

            <!-- Hidden File Input -->
            <input type="file" id="image-file-input" name="image" accept="image/*" class="hidden">

            <!-- Drag & Drop Zone -->
            <div id="image-drop-zone" class="border-2 border-dashed border-[#E2E7E5] rounded-xl p-6 text-center bg-[#F7F8F6] hover:border-[#1F8A89] transition-colors cursor-pointer">
                <!-- Empty Placeholder State -->
                <div id="image-upload-placeholder" class="space-y-3">
                    <div class="w-12 h-12 rounded-full bg-slate-200 text-[#17324D] flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-[#17324D] block">
                            Drag and drop incident photo here
                        </span>
                        <span class="text-xs text-[#687781] block mt-0.5">
                            Supports JPG, PNG, WEBP (Max 10MB)
                        </span>
                    </div>
                    <div class="flex items-center justify-center gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('image-file-input').click()" class="btn btn-outline btn-sm font-semibold text-xs">
                            Browse Files
                        </button>
                        <button type="button" onclick="document.getElementById('image-file-input').setAttribute('capture','environment'); document.getElementById('image-file-input').click()" class="btn btn-secondary btn-sm font-semibold text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Take Photo
                        </button>
                    </div>
                </div>

                <!-- Image Preview State -->
                <div id="image-preview-container" class="hidden space-y-3">
                    <div class="relative max-w-sm mx-auto rounded-lg overflow-hidden border border-[#E2E7E5] shadow-sm">
                        <img id="image-preview-img" src="" alt="Emergency Photo Preview" class="w-full h-48 object-cover">
                    </div>
                    <div class="flex items-center justify-center gap-3">
                        <button type="button" id="replace-image-btn" class="btn btn-outline btn-sm text-xs font-semibold">
                            Replace Image
                        </button>
                        <button type="button" id="remove-image-btn" class="btn btn-destructive btn-sm text-xs font-semibold">
                            Remove Image
                        </button>
                    </div>
                </div>
            </div>

            @error('image')
                <p class="text-xs text-[#C73E3E] font-semibold mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- SECTION 3: Emergency Location -->
        <div class="card p-6 bg-white border border-[#E2E7E5]">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-lg bg-[#17324D] text-white flex items-center justify-center text-xs font-bold">
                        3
                    </span>
                    <h2 class="text-base font-bold text-[#17324D] m-0">
                        Emergency Location <span class="text-[#F05D5E]">*</span>
                    </h2>
                </div>
                <span class="text-xs font-semibold text-[#1F8A89]">Required</span>
            </div>

            <!-- Map Canvas Placeholder Area -->
            <div class="map-canvas-placeholder h-44 rounded-xl border border-[#E2E7E5] mb-4 flex flex-col items-center justify-center text-center p-4">
                <div class="w-10 h-10 rounded-full bg-[#17324D] text-white flex items-center justify-center shadow-md mb-2">
                    <svg class="w-5 h-5 text-[#F05D5E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-[#17324D] block">
                    Interactive Geospatial Coordinates Pin
                </span>
                <span id="location-status-text" class="text-xs text-[#687781] block mt-1">
                    Click "Use My Current Location" to retrieve GPS coordinates automatically.
                </span>
            </div>

            <!-- Use My Current Location Trigger -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <button type="button" id="detect-location-btn" class="btn btn-navy text-xs sm:text-sm font-semibold">
                    <svg class="w-4 h-4 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3A8.994 8.994 0 0013 3.06V1h-2v2.06A8.994 8.994 0 003.06 11H1v2h2.06A8.994 8.994 0 0011 20.94V23h2v-2.06A8.994 8.994 0 0020.94 13H23v-2h-2.06zM12 19c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z"/></svg>
                    Use My Current Location
                </button>

                <div id="location-status-badge" class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-[#687781] border border-slate-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                    Ready to detect
                </div>
            </div>

            <!-- Latitude & Longitude Inputs -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="latitude" class="form-label text-xs font-semibold">Latitude</label>
                    <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude', '24.713556') }}" required class="form-input text-xs font-mono">
                    @error('latitude')
                        <p class="text-xs text-[#C73E3E] font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="longitude" class="form-label text-xs font-semibold">Longitude</label>
                    <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude', '46.675295') }}" required class="form-input text-xs font-mono">
                    @error('longitude')
                        <p class="text-xs text-[#C73E3E] font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- SECTION 4: Review Emergency Summary Before Submission -->
        <div class="card p-6 bg-[#F7F8F6] border border-[#E2E7E5]">
            <h3 class="text-sm font-bold text-[#17324D] uppercase tracking-wider mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#1F8A89]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Review Emergency Information
            </h3>

            <div class="space-y-3 text-xs">
                <div class="bg-white p-3.5 rounded-lg border border-[#E2E7E5]">
                    <span class="font-bold text-[#687781] block mb-1">Description:</span>
                    <p id="review-description" class="text-[#243746] italic">
                        No description entered yet...
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="bg-white p-3.5 rounded-lg border border-[#E2E7E5]">
                        <span class="font-bold text-[#687781] block mb-1">Image Status:</span>
                        <span id="review-image-status" class="text-[#243746]">No image attached</span>
                    </div>
                    <div class="bg-white p-3.5 rounded-lg border border-[#E2E7E5]">
                        <span class="font-bold text-[#687781] block mb-1">GPS Coordinates:</span>
                        <span id="review-location-coords" class="font-mono text-[#243746]">24.713556, 46.675295</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
            <a href="{{ route('citizen.dashboard') }}" class="btn btn-outline w-full sm:w-auto text-sm">
                Cancel
            </a>

            <!-- Primary CTA: Coral #F05D5E -->
            <button type="submit" id="submit-report-btn" class="btn btn-primary btn-lg w-full sm:w-auto font-extrabold shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Submit Emergency Report
            </button>
        </div>
    </form>
</div>

<!-- Submission Loading State Overlay -->
<div id="submission-loading-overlay" class="modal-backdrop hidden">
    <div class="modal-dialog p-8 bg-white text-center max-w-sm">
        <div class="w-12 h-12 rounded-full border-4 border-[#F05D5E] border-t-transparent animate-spin mx-auto mb-4"></div>
        <h3 class="text-lg font-bold text-[#17324D] mb-1">Submitting your emergency report...</h3>
        <p class="text-xs text-[#687781] leading-relaxed">
            Encrypting distress telemetry, uploading photographic attachments, and initiating automated AI triage. Please do not close this window.
        </p>
    </div>
</div>
@endsection
