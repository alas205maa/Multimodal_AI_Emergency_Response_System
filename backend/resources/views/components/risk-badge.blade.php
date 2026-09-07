@props(['risk' => 'Medium'])

@php
    $normalized = strtolower(trim($risk));
    $classes = match($normalized) {
        'critical' => 'bg-[#FDF2F2] text-[#C73E3E] border-[#F8B4B4]',
        'high' => 'bg-[#FFF5F2] text-[#E06B3C] border-[#FDCBAA]',
        'medium' => 'bg-[#FEF9EC] text-[#B26A00] border-[#FCE4A6]',
        'low' => 'bg-[#F0FDF4] text-[#3A9D6E] border-[#BBF7D0]',
        default => 'bg-[#FEF9EC] text-[#B26A00] border-[#FCE4A6]',
    };

    $label = ucfirst($risk);
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider rounded-full border {{ $classes }}">
    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
    {{ $label }} Risk
</span>
