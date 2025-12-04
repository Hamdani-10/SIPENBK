@extends('layouts.main-guru')

@section('title', 'Data Siswa')

@section('content')

{{-- Hero Section --}}
<section class="min-h-screen flex items-center bg-gradient-to-b from-white via-blue-100 to-white py-24">

    <div class="flex flex-col-reverse lg:flex-row items-center gap-16 px-6 md:px-100 lg:px-20 w-full">
        {{-- Konten teks --}}
        <div class="w-full lg:w-1/2 space-y-6">
            <h1 class="text-3xl md:text-4xl font-extrabold text-blue-600 drop-shadow-sm">
                Menu Siswa
            </h1>
            <p class="text-lg text-gray-700">
                Di halaman ini Anda dapat melihat informasi lengkap siswa seperti nama, kelas, kontak, serta orang tua siswa. 
                Gunakan fitur ini untuk mempermudah pemantauan dan komunikasi.
            </p>
            <div class="flex flex-wrap gap-4 pt-4">
                <a href="#tabel-siswa" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-full transition duration-300 shadow">
                    Lihat Daftar Siswa
                </a>
                <a href="{{ route('guru.dashboard.index' )}}" class="border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-semibold px-6 py-3 rounded-full transition duration-300 shadow">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    
        {{-- Gambar siswa --}}
        <div class="w-full lg:w-1/2">
            <img src="{{ asset('assets/ILUS-SISWA-1.jpg') }}" alt="Ilustrasi Data Siswa" class="w-full max-w-md mx-auto drop-shadow-2xl">
        </div>
    </div>
</section>
{{-- end section hero --}}

{{-- Section Tabel Siswa --}}
<section id="tabel-siswa" class="min-h-screen px-6 lg:px-10 space-y-6 mt-25 bg-gradient-to-b from-white via-blue-100 to-white">

    {{-- Judul --}}
    <div class="text-center max-w-3xl mx-auto mt-16 mb-8 space-y-3 px-4">
        <h2 class="text-4xl font-bold text-blue-600 tracking-tight drop-shadow-sm">Manajemen Data Siswa</h2>
        <p class="text-gray-600 text-base md:text-lg leading-relaxed">
            Kelola dan pantau informasi lengkap siswa. Gunakan pencarian dan filter untuk menemukan data lebih cepat dan akurat.
        </p>
    </div>

    {{-- Search & Filter --}}
    <div class="overflow-x-auto bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <form method="GET" action="{{ route('guru.siswa.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                {{-- Search --}}
                <div class="col-span-1">
                    <label for="search" class="block text-sm font-semibold text-gray-700 mb-1">Pencarian</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i data-feather="search" class="w-4 h-4"></i>
                        </span>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Cari berdasarkan nama atau NISN..."
                            class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none shadow-sm transition" />
                    </div>
                </div>

                {{-- Filter --}}
                <div class="col-span-1">
                    <label for="kelas" class="block text-sm font-semibold text-gray-700 mb-1">Filter Kelas</label>
                    <select name="kelas" id="kelas"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                                {{ $kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="flex gap-2 justify-start md:justify-end mt-4 md:mt-0">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl shadow transition">
                        <i data-feather="filter" class="w-4 h-4"></i> Filter
                    </button>
                    <a href="{{ route('guru.siswa.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-xl shadow transition">
                        <i data-feather="rotate-ccw" class="w-4 h-4"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
    {{-- Tabel --}}
    <div class="overflow-x-auto bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <table class="min-w-full table-auto divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-gray-600 font-semibold uppercase sticky top-0 z-10 text-xs">
                <tr>
                    <th class="px-6 py-4 text-left">NISN</th>
                    <th class="px-6 py-4 text-left">Nama</th>
                    <th class="px-6 py-4 text-left">Kelas</th>
                    <th class="px-6 py-4 text-left">WA Siswa</th>
                    <th class="px-6 py-4 text-left">Orang Tua</th>
                    <th class="px-6 py-4 text-left">WA Ortu</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($siswa as $index => $item)
                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-blue-50 transition-colors duration-200">
                        <td class="px-6 py-4">{{ $item->nisn }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $item->nama_siswa }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block max-w-full truncate bg-blue-100 text-blue-700 px-3 py-1 text-xs md:text-sm rounded-full text-center">
                                {{ $item->kelas }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $item->display_whatsapp_siswa }}</td>
                        <td class="px-6 py-4">{{ $item->nama_ortu }}</td>
                        <td class="px-6 py-4">{{ $item->display_whatsapp_ortu }}</td>
                        <td class="px-6 py-4 text-center space-x-2 whitespace-nowrap">

                            {{-- Tombol Edit --}}
                            <div x-data="{ openEdit: false }" class="inline-block">
                                <button @click="openEdit = true"
                                    class="inline-flex items-center gap-1 text-xs text-white bg-yellow-400 hover:bg-yellow-500 font-semibold px-4 py-2 rounded-full shadow-md transition-all hover:scale-105"
                                    title="Edit Data Siswa">
                                    <i data-feather="edit" class="w-4 h-4"></i> <span>Edit</span>
                                </button>

                                {{-- Modal Edit --}}
                                <div x-show="openEdit" x-transition.opacity x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
                                    <div @click.outside="openEdit = false" x-transition.scale.80
                                        class="p-6 rounded-2xl shadow-2xl w-full max-w-2xl mx-4 space-y-6 bg-white border border-gray-100">

                                        {{-- Header Modal --}}
                                        <div class="flex items-center justify-between">
                                            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                                                <i data-feather="edit-3" class="w-5 h-5 text-blue-600"></i>
                                                Edit Siswa
                                            </h2>
                                            <button @click="openEdit = false" class="text-gray-500 hover:text-red-500 transition">
                                                <i data-feather="x" class="w-6 h-6"></i>
                                            </button>
                                        </div>

                                        {{-- Form --}}
                                        <form action="{{ route('guru.siswa.update', $item->id) }}" method="POST" class="space-y-4">
                                            @csrf
                                            @method('PUT')

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                @php
                                                    $fields = [
                                                        ['name' => 'nama_siswa', 'label' => 'Nama', 'icon' => 'user'],
                                                        ['name' => 'nisn', 'label' => 'NISN', 'icon' => 'hash'],
                                                        ['name' => 'kelas', 'label' => 'Kelas', 'icon' => 'layers'],
                                                        ['name' => 'no_whatsapp_siswa', 'label' => 'WA Siswa', 'icon' => 'smartphone'],
                                                        ['name' => 'nama_ortu', 'label' => 'Nama Ortu', 'icon' => 'users'],
                                                        ['name' => 'no_whatsapp_ortu', 'label' => 'WA Ortu', 'icon' => 'phone-call'],
                                                    ];
                                                @endphp

                                                @foreach ($fields as $field)
                                                    <div class="relative">
                                                        <label class="block text-xs text-gray-500 font-semibold mb-1 text-left">
                                                            {{ $field['label'] }}
                                                        </label>
                                                        <div class="relative">
                                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                                                <i data-feather="{{ $field['icon'] }}" class="w-4 h-4"></i>
                                                            </span>
                                                            <input 
                                                                name="{{ $field['name'] }}" 
                                                                value="{{ $item[$field['name']] ?? '' }}"
                                                                @if(Str::startsWith($field['name'], 'no_whatsapp'))
                                                                    placeholder="Contoh: 6281234567890"
                                                                    pattern="62[0-9]{9,13}"
                                                                    title="Gunakan format internasional, misalnya: 6281234567890"
                                                                @else
                                                                    placeholder="Masukkan {{ strtolower($field['label']) }}..."
                                                                @endif
                                                                class="w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none transition shadow-sm"
                                                                required 
                                                            />
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            {{-- Tombol --}}
                                            <div class="flex justify-end gap-3 pt-4">
                                                <button @click="openEdit = false" type="button"
                                                    class="flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 text-sm font-medium transition">
                                                    <i data-feather="x-circle" class="w-4 h-4"></i> Batal
                                                </button>
                                                <button type="submit"
                                                    class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm font-medium transition">
                                                    <i data-feather="save" class="w-4 h-4"></i> Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                {{-- End Modal Edit --}}
                            </div>

                            {{-- Tombol Hapus --}}
                            <x-modal-hapus :open="$item->id" :action="route('guru.siswa.destroy', $item->id)" />

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-400 text-sm italic">
                            <i data-feather="info" class="w-5 h-5 inline-block mr-1"></i> Tidak ada data siswa yang tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{-- Pagination --}}
        @if ($siswa->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $siswa->links('vendor.pagination.tailwind-modern') }}
            </div>
        @endif
    </div>
</section>

{{-- End Section --}}

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
    document.addEventListener('DOMContentLoaded', function () {
        feather.replace();
    });
</script>
@endsection
