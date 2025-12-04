@extends('layouts.main')

@section('title', 'Dashboard - Detail Jadwal')
@section('page-title', 'Detail Jadwal')
@section('page-icon', 'calendar')

@section('content')
{{-- Back Button --}}
<div class="mb-6">
    <a href="{{ route('admin.jadwal.index') }}" 
       class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 text-sm font-semibold">
        <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali ke Jadwal
    </a>
</div>

{{-- Detail Card --}}
<div class="bg-white p-6 rounded-2xl space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Nama Siswa --}}
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Nama Siswa</h3>
            <p class="text-gray-800 font-medium">{{ $jadwal->nama_siswa }}</p>
        </div>

        {{-- Nomor WhatsApp Siswa --}}
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">No. WhatsApp Siswa</h3>
            <p class="text-gray-800 font-medium">{{ $jadwal->siswa->display_whatsapp_siswa ?? '-' }}</p>
        </div>

        {{-- Nama Orang Tua dan No WA Orang Tua (jika kategori Pemanggilan Orang Tua) --}}
        @if (strtolower($jadwal->kategori) === 'pemanggilan orang tua')
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Nama Orang Tua</h3>
                <p class="text-gray-800 font-medium">{{ $jadwal->nama_ortu ?? ($jadwal->siswa->nama_ortu ?? '-') }}</p>
            </div>
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">No. WhatsApp Orang Tua</h3>
                <p class="text-gray-800 font-medium">{{ $jadwal->no_whatsapp_ortu ?? ($jadwal->siswa->no_whatsapp_ortu ?? '-') }}</p>
            </div>
        @endif

        {{-- Nama Guru BK --}}
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Nama Guru BK</h3>
            <p class="text-gray-800 font-medium">{{ $jadwal->guruBk->nama_guru_bk ?? '-' }}</p>
        </div>

        {{-- Nomor WhatsApp Guru BK --}}
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">No. WhatsApp Guru BK</h3>
            <p class="text-gray-800 font-medium">{{ $jadwal->guruBk->displayWhatsappGuruBk ?? '-' }}</p>
        </div>

        {{-- Tanggal --}}
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Tanggal</h3>
            <p class="text-gray-800 font-medium">{{ $jadwal->formatted_tanggal }}</p>
        </div>

        {{-- Waktu --}}
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Waktu</h3>
            <p class="text-gray-800 font-medium">{{ $jadwal->formatted_waktu }} WIB</p>
        </div>

        {{-- Kategori --}}
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Kategori</h3>
            <span class="inline-block px-3 py-1 rounded-full bg-indigo-100 text-indigo-800 text-xs font-medium">
                {{ ucfirst($jadwal->kategori) }}
            </span>
        </div>

        {{-- Status --}}
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Status</h3>
            @php
                $statusColors = [
                    'tersedia' => 'bg-green-100 text-green-800',
                    'menunggu' => 'bg-yellow-100 text-yellow-800',
                    'selesai' => 'bg-blue-100 text-blue-800',
                    'dibatalkan' => 'bg-red-100 text-red-800',
                ];
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$jadwal->status] ?? 'bg-gray-100 text-gray-800' }}">
                {{ ucfirst($jadwal->status) }}
            </span>
        </div>

        {{-- Keterangan (opsional) --}}
        @if ($jadwal->keterangan)
            <div class="md:col-span-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Keterangan</h3>
                <p class="text-gray-800">{{ $jadwal->keterangan }}</p>
            </div>
        @endif
    </div>
</div>

<script>
    feather.replace();
</script>
@endsection
