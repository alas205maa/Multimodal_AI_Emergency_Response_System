@props(['status' => 'Received'])

@php
    $normalized = strtolower(trim($status));
    $classes = match($normalized) {
        'received', 'pending' => 'bg-[#EEF2F6] text-[#17324D] border-[#D5DFE8]',
        'analyzing' => 'bg-[#E6F5F5] text-[#1F8A89] border-[#B8E4E3]',
        'assigned' => 'bg-[#EFF6FF] text-[#2563EB] border-[#BFDBFE]',
        'in progress', 'inprogress' => 'bg-[#FFF7ED] text-[#C2410C] border-[#FED7AA]',
        'resolved' => 'bg-[#F0FDF4] text-[#15803D] border-[#BBF7D0]',
        'cancelled' => 'bg-[#F3F4F6] text-[#4B5563] border-[#E5E7EB]',
        default => 'bg-[#EEF2F6] text-[#17324D] border-[#D5DFE8]',
    };

    $label = match($normalized) {
        'pending' => 'Received',
        'inprogress' => 'In Progress',
        default => ucfirst($status),
    };
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full border {{ $classes }}">
    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
    {{ $label }}
</span>
