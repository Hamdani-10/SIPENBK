@extends('layouts.main')

@section('title', 'Dashboard - Jadwal BK')
@section('page-title', 'Jadwal BK')
@section('page-icon', 'calendar')

@section('content')
{{-- Header --}}
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <h2 class="text-2xl font-bold text-gray-800">Daftar Jadwal BK</h2>
    <a href="{{ route('admin.create-jadwal') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow transition whitespace-nowrap">
        <i data-feather="calendar" class="w-4 h-4"></i> Tambah Jadwal BK
    </a>
</div>

{{-- Search & Filter --}}
<form method="GET" action="{{ route('admin.jadwal.index') }}"
      class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
    {{-- Search Nama Siswa --}}
    <div class="w-full md:w-1/3 relative">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama siswa..."
               class="w-full border border-gray-300 pl-10 pr-4 py-2.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-sm placeholder-gray-400"/>
        <i data-feather="search" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
    </div>

    {{-- Filter Kategori --}}
    <div class="flex gap-3 items-center">
        <select name="kategori"
                class="border border-gray-300 px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 shadow-sm"
                onchange="this.form.submit()">
            <option value="">Filter Kategori</option>
            <option value="Bimbingan Masalah Pribadi" {{ request('kategori') == 'Bimbingan Masalah Pribadi' ? 'selected' : '' }}>Bimbingan Masalah Pribadi</option>
            <option value="Bimbingan Sosial" {{ request('kategori') == 'Bimbingan Sosial' ? 'selected' : '' }}>Bimbingan Sosial</option>
            <option value="Bimbingan Belajar" {{ request('kategori') == 'Bimbingan Belajar' ? 'selected' : '' }}>Bimbingan Belajar</option>
            <option value="Bimbingan Karir" {{ request('kategori') == 'Bimbingan Karir' ? 'selected' : '' }}>Bimbingan Karir</option>
            <option value="Pemanggilan Orang Tua" {{ request('kategori') == 'Pemanggilan Orang Tua' ? 'selected' : '' }}>Pemanggilan Orang Tua</option>
        </select>

        {{-- Reset Filter --}}
        <a href="{{ route('admin.jadwal.index') }}"
        class="flex items-center bg-gray-100 hover:bg-gray-200 px-3 py-2.5 rounded-lg shadow-sm transition">
            <i data-feather="x" class="w-4 h-4 text-gray-600"></i>
        </a>
    </div>
</form>

{{-- Table --}}
<div class="overflow-x-auto bg-white rounded-lg">
    <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wide">
            <tr>
                <th class="px-5 py-4">Nama Siswa</th>
                <th class="px-5 py-4">Tanggal</th>
                <th class="px-5 py-4">Waktu</th>
                <th class="px-5 py-4">Status</th>
                <th class="px-5 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($jadwals as $jadwal)
                <tr class="hover:bg-green-50 transition duration-200">
                    <td class="px-5 py-4 font-medium text-gray-900">{{ $jadwal->nama_siswa }}</td>
                    <td class="px-5 py-4 text-gray-700">{{$jadwal->formatted_tanggal}}</td>
                    <td class="px-5 py-4 text-gray-700">{{ $jadwal->formatted_waktu }} WIB</td>
                    <td class="px-5 py-4">
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
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex justify-center gap-2">
                            {{-- Detail Jadwal --}}
                            <a href="{{ route('admin.jadwal.show', $jadwal->id) }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-sky-500 hover:bg-sky-600 text-white rounded-md text-xs font-medium shadow-sm transition">
                                <i data-feather="eye" class="w-4 h-4"></i> Detail
                            </a>
                            {{-- Edit Jadwal --}}
                            <a href="{{ route('admin.edit-jadwal', $jadwal->id) }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-400 hover:bg-amber-500 text-white rounded-md text-xs font-medium shadow-sm transition">
                                <i data-feather="edit" class="w-4 h-4"></i> Edit
                            </a>
                            <x-delete-button :id="$jadwal->id" :action="route('admin.jadwal.destroy', $jadwal->id)" />
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-6 text-gray-500">Tidak ada jadwal ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{-- Pagination --}}
    @if($jadwals instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-12 flex justify-center">
            {{ $jadwals->links('vendor.pagination.tailwind-modern') }}
        </div>
    @endif
</div>

{{-- Modal Sukses --}}
@if (session('success'))
<div x-data="{ open: true }" x-show="open" x-transition class="fixed inset-0 flex items-center justify-center z-50 bg-white/40 backdrop-blur-sm">
    <div @click.away="open = false" class="bg-white rounded-xl shadow-xl p-8 max-w-md w-full text-center">
        <div class="text-green-500">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-3">Berhasil!</h3>
        <p class="text-gray-600 mb-4">{{ session('success') }}</p>
        <button @click="open = false" class="mt-3 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Tutup
        </button>
    </div>
</div>
@endif

<script>
    feather.replace();
</script>
@endsection