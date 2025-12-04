@extends('layouts.main-guru')

@section('title', 'Panduan')

@section('content')

@if ($panduan->isNotEmpty())
    @foreach ($panduan as $index => $item)
    <section class="py-24 lg:py-32 bg-gradient-to-b {{ $index % 2 === 0 ? 'from-white via-blue-100 to-white' : 'from-white via-blue-100 to-white' }}">
        <div class="w-full flex flex-col-reverse lg:flex-row {{ $index % 2 === 1 ? 'lg:flex-row-reverse' : '' }} items-center gap-16 px-4 md:px-8 lg:px-20">
            
            {{-- Teks --}}
            <div class="w-full lg:w-1/2 space-y-6">
                <h1 class="text-2xl md:text-5xl font-extrabold text-gray-900 leading-tight text-center lg:text-left">
                    {{ $item->judul }}
                </h1>
                <div class="prose prose-lg text-gray-700 max-w-none text-justify">
                    {!! $item->isi_panduan !!}
                </div>
            </div>

            {{-- Gambar --}}
            <div class="w-full lg:w-1/2">
                @if ($item->gambar)
                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="Ilustrasi Panduan" class="w-full max-w-md mx-auto drop-shadow-2xl rounded-xl">
                @else
                    <div class="w-full max-w-md mx-auto bg-gray-100 h-64 flex items-center justify-center rounded-xl shadow-inner">
                        <span class="text-gray-400 italic">Gambar panduan tidak tersedia</span>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @endforeach
@endif

@endsection
