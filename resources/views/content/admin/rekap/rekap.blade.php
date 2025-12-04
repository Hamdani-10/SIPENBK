@extends('layouts.main')

@section('title', 'Dashboard - Laporan BK')
@section('page-title', 'Laporan BK')
@section('page-icon', 'clipboard')

@section('content')
{{-- Header --}}
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Daftar Laporan BK</h2>
    <a href="{{ route('admin.create-rekap') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow transition-all">
        <i data-feather="file-plus" class="w-4 h-4"></i>
        Tambah Laporan BK
    </a>
</div>

{{-- Pencarian --}}
<form method="GET" action="{{ route('admin.rekap.index') }}" class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
    <div class="w-full md:w-1/3 relative">
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}"
            placeholder="Cari nama siswa atau guru..." 
            class="w-full border border-gray-300 pl-10 pr-4 py-2.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm placeholder-gray-400 shadow-sm"
        />
        <i data-feather="search" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
    </div>
</form>

{{-- Tabel --}}
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wide">
            <tr>
                <th class="px-5 py-4">Kategori</th>
                <th class="px-5 py-4">Nama Siswa</th>
                <th class="px-5 py-4">Guru BK</th>
                <th class="px-5 py-4">Hasil BK</th>
                <th class="px-5 py-4 text-center">Siswa</th>
                <th class="px-5 py-4 text-center">Ortu</th>
                <th class="px-5 py-4">Catatan</th>
                <th class="px-5 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($rekap as $item)
            <tr class="hover:bg-blue-50 transition duration-200">
                <td class="px-5 py-4 text-gray-800">{{ $item->jadwal->kategori }}</td>
                <td class="px-5 py-4 text-gray-800">{{ $item->jadwal->siswa->nama_siswa }}</td>
                <td class="px-5 py-4 text-gray-800">{{ $item->guruBk->nama_guru_bk }}</td>
                <td class="px-5 py-4 text-gray-800">{{ Str::limit($item->hasil_bk, 50) }}</td>
                <td class="px-5 py-4 text-center">
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                        {{ $item->kehadiran_siswa ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $item->kehadiran_siswa ? 'Hadir' : 'Tidak Hadir' }}
                    </span>
                </td>
                <td class="px-5 py-4 text-center">
                    @if ($item->jadwal->kategori === 'Pemanggilan Ortu')
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                            {{ $item->kehadiran_ortu ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            {{ $item->kehadiran_ortu ? 'Hadir' : 'Tidak Hadir' }}
                        </span>
                    @else
                        <span class="text-gray-400 italic text-xs">Tidak Berlaku</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-gray-800">{{ Str::limit($item->catatan_tambahan, 50) }}</td>
                <td class="px-5 py-4">
                    <div class="flex justify-center gap-2">
                        <a href="{{ route('admin.rekap.cetak-pdf', $item->id) }}"
                        target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-md text-xs font-medium transition shadow-sm">
                            <i data-feather="printer" class="w-4 h-4"></i>
                            Cetak 
                        </a>
                        <a href="{{ route('admin.edit-rekap', $item->id) }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-400 hover:bg-amber-500 text-white rounded-md text-xs font-medium transition shadow-sm">
                            <i data-feather="edit" class="w-4 h-4"></i>
                            Edit
                        </a>
                        <x-delete-button :id="$item->id" :action="route('admin.rekap.destroy', $item->id)" />
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-5 py-6 text-center text-gray-500">
                    Tidak ada data laporan ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{-- Pagination --}}
        @if ($rekap->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $rekap->links('vendor.pagination.tailwind-modern') }}
            </div>
        @endif
</div>

{{-- Modal Sukses --}}
@if (session('success'))
    <div x-data="{ open: true }" x-show="open" x-transition.opacity.duration.300ms
        class="fixed inset-0 flex items-center justify-center z-50 bg-black/30 backdrop-blur-sm">
        <div @click.away="open = false"
            class="bg-white dark:bg-gray-800 text-gray-800 dark:text-white rounded-xl shadow-xl p-8 max-w-md w-full text-center transition-all transform scale-100">
        <div class="text-green-500">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
            <h3 class="text-xl font-bold mb-3">Berhasil!</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-4">{{ session('success') }}</p>
                <button @click="open = false"
                    class="mt-3 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Tutup
                </button>
        </div>
    </div>
@endif

<script>
    feather.replace();
</script>
@endsection
