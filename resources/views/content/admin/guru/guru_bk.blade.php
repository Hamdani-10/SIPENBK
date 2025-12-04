@extends('layouts.main')

@section('title', 'Dashboard - Guru BK')
@section('page-title', 'Guru BK')
@section('page-icon', 'users')

@section('content')
{{-- Header --}}
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Daftar Guru BK</h2>
</div>

{{-- Tabel Guru BK --}}
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wide">
            <tr>
                <th class="px-5 py-4">Nama</th>
                <th class="px-5 py-4">Jabatan</th>
                <th class="px-5 py-4">No WhatsApp</th>
                <th class="px-5 py-4">Foto</th>
                <th class="px-5 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($guru_bk as $guru)
            <tr class="hover:bg-blue-50 transition duration-200">
                <td class="px-5 py-4 font-semibold text-gray-900">{{ $guru->nama_guru_bk ?? '-' }}</td>
                <td class="px-5 py-4 text-gray-700">{{ $guru->jabatan ?? '-' }}</td>
                <td class="px-5 py-4 text-gray-700">{{ $guru->display_whatsapp_guru_bk ?? '-' }}</td>
                <td class="px-5 py-4">
                    @if ($guru->foto)
                        <img src="{{ asset('storage/' . $guru->foto) }}" alt="Foto Guru BK"
                             class="w-14 h-14 object-cover object-top border-2 border-white rounded-full">
                    @else
                        <span class="text-gray-800">Tidak ada foto</span>
                    @endif
                <td class="px-5 py-4">
                    <div class="flex justify-center gap-2">
                        <a href="{{ route('admin.edit-gurubk', $guru->id) }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-400 hover:bg-amber-500 text-white rounded-md text-xs font-medium transition shadow-sm">
                            <i data-feather="edit" class="w-4 h-4"></i>
                            Edit
                        </a>

                        <x-delete-button :id="$guru->id" :action="route('admin.guru_bk.destroy', $guru->id)" />
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-5 py-6 text-center text-gray-500">
                    Tidak ada data guru BK ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
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
