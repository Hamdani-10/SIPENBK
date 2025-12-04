@extends('layouts.main')

@section('title', 'Dashboard - Panduan')
@section('page-title', 'Daftar Panduan')
@section('page-icon', 'book-open')

@section('content')
{{-- Header --}}
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Daftar Panduan</h2>
    <a href="{{ route('admin.create-panduan') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow transition-all">
        <i data-feather="file-plus" class="w-4 h-4"></i>
        Tambah Panduan
    </a>
</div>


{{-- Table --}}
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wide">
            <tr>
                <th class="px-5 py-4 whitespace-nowrap">Judul</th>
                <th class="px-5 py-4 whitespace-nowrap">Isi Panduan</th>
                <th class="px-5 py-4 whitespace-nowrap">Gambar</th>
                <th class="px-5 py-4 text-center whitespace-nowrap">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($panduan as $item)
            <tr class="hover:bg-blue-50 transition duration-200">
                <td class="px-5 py-4 font-semibold text-gray-900 max-w-xs truncate">{{ $item->judul }}</td>
                <td class="px-5 py-4 text-gray-700 max-w-xs truncate">{{ Str::limit(strip_tags($item->isi_panduan), 80) }}</td>
                <td class="px-5 py-4">
                    @if ($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar Panduan" class="h-16 rounded shadow">
                    @else
                        <span class="text-gray-400 italic">Tidak ada gambar</span>
                    @endif
                </td>
                <td class="px-5 py-4">
                <div class="flex justify-center gap-2 flex-nowrap">
                    <a href="{{ route('admin.edit-panduan', $item->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-400 hover:bg-amber-500 text-white rounded-md text-xs font-medium transition shadow-sm">
                        <i data-feather="edit" class="w-4 h-4"></i>
                        Edit
                    </a>
                    <x-delete-button :id="$item->id" :action="route('admin.panduan.destroy', $item->id)" />
                </div>
            </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-5 py-6 text-center text-gray-500">
                    Tidak ada data panduan ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>        
    </table>
</div>

{{-- Modal Sukses --}}
@if (session('success'))
    <div x-data="{ open: true }" x-show="open" x-transition.opacity.duration.300ms
         class="fixed inset-0 flex items-center justify-center z-50 bg-black/30 backdrop-blur-sm px-4">
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
