@extends('layouts.main-siswa')

@section('title', 'Panduan')

@section('content')

@if ($panduan->isNotEmpty())
    @foreach ($panduan->take(2) as $index => $item) {{-- Ambil hanya 2 panduan --}}
    <section class="py-24 lg:py-32 bg-gradient-to-b from-white via-blue-50 to-white">
        <div class="w-full flex flex-col-reverse lg:flex-row {{ $index % 2 === 1 ? 'lg:flex-row-reverse' : '' }} items-center gap-16 px-4 md:px-8 lg:px-20">
            
            {{-- Konten Teks --}}
            <div class="w-full lg:w-1/2 space-y-4">
                <h2 class="text-2xl md:text-5xl font-extrabold text-gray-900 leading-tight text-center lg:text-left">
                    {{ $item->judul }}
                </h2>
                <div class="prose prose-base text-gray-700 max-w-none text-justify">
                    {!! $item->isi_panduan !!}
                </div>
            </div>

            {{-- Gambar Panduan --}}
            <div class="w-full lg:w-1/2">
                @if ($item->gambar)
                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar Panduan" class="w-full max-w-md mx-auto drop-shadow-2xl rounded-xl">
                @else
                    <div class="w-full max-w-md mx-auto bg-gray-100 h-60 flex items-center justify-center rounded-xl shadow-inner">
                        <span class="text-gray-400 italic">Gambar tidak tersedia</span>
                    </div>
                @endif
            </div>

        </div>
    </section>
    @endforeach
@endif

@endsection
