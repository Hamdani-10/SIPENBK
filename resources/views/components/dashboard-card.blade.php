/**
 * Copyright (c) 2025 Hamdani Kevin
 * This project is part of the SIPENBK Counseling Scheduling System.
 * All rights reserved.
 */


@props(['icon', 'label', 'value', 'color' => 'blue'])

@php
$variants = [
    'blue' => [
        'text' => 'text-blue-700',
        'bgIcon' => 'bg-[#dbeafe]',
        'bgCard' => 'from-[#f0f9ff] via-white to-[#dbeafe]',
        'ring' => 'ring-white/40',
    ],
    'emerald' => [
        'text' => 'text-emerald-700',
        'bgIcon' => 'bg-[#d1fae5]',
        'bgCard' => 'from-[#ecfdf5] via-white to-[#d1fae5]',
        'ring' => 'ring-white/40',
    ],
    'amber' => [
        'text' => 'text-amber-700',
        'bgIcon' => 'bg-[#fef3c7]',
        'bgCard' => 'from-[#fffbeb] via-white to-[#fef3c7]',
        'ring' => 'ring-white/40',
    ],
    'rose' => [
        'text' => 'text-rose-700',
        'bgIcon' => 'bg-[#ffe4e6]',
        'bgCard' => 'from-[#fff1f2] via-white to-[#ffe4e6]',
        'ring' => 'ring-white/40',
    ],
];

$variant = $variants[$color] ?? $variants['blue'];
@endphp

<div class="bg-gradient-to-br {{ $variant['bgCard'] }} border border-white/30 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 backdrop-blur-md p-4 sm:p-5 md:p-6">
    <div class="flex flex-col sm:flex-row items-center sm:items-start sm:justify-between gap-3 sm:gap-4 md:gap-6">
        <div class="p-3 rounded-xl {{ $variant['bgIcon'] }} {{ $variant['text'] }} shadow-md flex items-center justify-center ring-1 ring-inset {{ $variant['ring'] }}">
            <i data-feather="{{ $icon }}" class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8"></i>
        </div>
        <div class="text-center sm:text-left">
            <p class="text-xs sm:text-[11px] md:text-sm font-semibold uppercase tracking-wider {{ $variant['text'] }}">{{ $label }}</p>
            <h2 class="text-xl sm:text-2xl md:text-3xl font-bold {{ $variant['text'] }}">{{ $value }}</h2>
        </div>
    </div>
</div>
