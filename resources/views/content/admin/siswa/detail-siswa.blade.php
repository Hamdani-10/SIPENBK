@extends('layouts.main')

@section('title', 'Dashboard - Detail Siswa')
@section('page-title', 'Detail Siswa')
@section('page-icon', 'user')

@section('content')
{{-- Back Button --}}
<div class="mb-6">
    <a href="{{ route('admin.siswa.index') }}" 
       class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 text-sm font-semibold">
        <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali ke Siswa
    </a>
</div>

{{-- Detail Card --}}
<div class="bg-white p-6 rounded-2xl space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- NISN --}}
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">NISN</h3>
            <p class="text-gray-800 font-medium">{{ $siswa->nisn }}</p>
        </div>
        {{-- Nama Siswa --}}
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Nama Siswa</h3>
            <p class="text-gray-800 font-medium">{{ $siswa->nama_siswa }}</p>
        </div>
        {{-- Kelas --}}
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Kelas</h3>
            <p class="text-gray-800 font-medium">{{ $siswa->kelas }}</p>
        </div>
        {{-- Nomor WhatsApp Siswa --}}
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">No. WhatsApp Siswa</h3>
            <p class="text-gray-800 font-medium">{{ $siswa->display_whatsapp_siswa ?? '-' }}</p>
        </div>
        {{-- Nama Orang Tua --}}
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Nama Orang Tua</h3>
            <p class="text-gray-800 font-medium">{{ $siswa->nama_ortu ?? '-' }}</p>
        </div>
        {{-- Nomor WhatsApp Orang Tua --}}
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">No. WhatsApp Orang Tua</h3>
            <p class="text-gray-800 font-medium">{{ $siswa->display_whatsapp_ortu ?? '-' }}</p>
        </div>
        {{-- Foto --}}
        <div class="col-span-1 md:col-span-2">
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Foto Siswa</h3>
            @if ($siswa->foto)
                <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto Siswa" class="w-40 h-40 rounded-full object-cover object-top border-4 border-white shadow-sm">
            @else
                <p class="text-gray-800 font-medium">Tidak ada foto siswa</p>
            @endif
        </div>
    </div>
</div>

@endsection