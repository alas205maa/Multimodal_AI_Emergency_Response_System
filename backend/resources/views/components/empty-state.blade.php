@props([
    'title' => 'No records found',
    'message' => 'There is no information currently available in this view.',
    'actionText' => null,
    'actionUrl' => null,
])

<div class="card p-8 sm:p-12 text-center flex flex-col items-center justify-center max-w-lg mx-auto">
    <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center text-[#687781] mb-4">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
    </div>
    <h3 class="text-base sm:text-lg font-bold text-[#17324D] mb-1">
        {{ $title }}
    </h3>
    <p class="text-sm text-[#687781] max-w-sm mb-6 leading-relaxed">
        {{ $message }}
    </p>

    @if ($actionText && $actionUrl)
        <a href="{{ $actionUrl }}" class="btn btn-secondary text-sm">
            {{ $actionText }}
        </a>
    @endif
</div>
