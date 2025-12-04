@extends('layouts.main')

@section('title', 'Edit Jadwal')
@section('page-title', 'Edit Jadwal')
@section('page-icon', 'edit-3')

@section('content')
    {{-- Breadcrumb --}}
    <nav class="flex items-center text-sm text-gray-500 mb-6 space-x-2">
        <a href="{{ route('admin.jadwal.index') }}" class="text-blue-600 hover:text-blue-700 font-medium transition">
            <i data-feather="calendar" class="w-4 h-4 inline-block mr-1"></i> Jadwal BK
        </a>
        <span>/</span>
        <span class="text-gray-700 font-semibold">Edit Jadwal BK</span>
    </nav>

    {{-- Form Card --}}
    <div class="bg-white p-6 rounded-2xl border border-gray-100">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Form Edit Jadwal BK</h2>

        <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Siswa --}}
            <div>
                <label for="id_siswa" class="block mb-2 text-sm font-medium text-gray-700">Nama Siswa</label>
                <select name="id_siswa" id="id_siswa" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                    <option value="" disabled>Pilih Siswa</option>
                    @foreach ($siswa as $item)
                        <option value="{{ $item->id }}" {{ old('id_siswa', $jadwal->id_siswa) == $item->id ? 'selected' : '' }}>{{ $item->nama_siswa }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Nama Guru BK --}}
            <div>
                <label for="id_guru_bk" class="block mb-2 text-sm font-medium text-gray-700">Guru BK</label>
                <select name="id_guru_bk" id="id_guru_bk" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                    <option value="" disabled>Pilih Guru BK</option>
                    @foreach ($guruBk as $guru)
                        <option value="{{ $guru->id }}" {{ old('id_guru_bk', $jadwal->id_guru_bk) == $guru->id ? 'selected' : '' }}>{{ $guru->user->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal --}}
            @php
                $tanggal = old('tanggal', \Carbon\Carbon::parse($jadwal->tanggal)->format('Y-m-d'));
            @endphp

            <div>
                <label for="tanggal" class="block mb-2 text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
            </div>

            {{-- Waktu --}}
            <div>
                <label for="waktu" class="block mb-2 text-sm font-medium text-gray-700">Waktu</label>
                <select name="waktu" id="waktu" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                    <option value="" disabled>Pilih Waktu</option>
                    @foreach (['09:00','10:00','11:00','12:00','13:00'] as $time)
                        <option value="{{ $time }}" {{ old('waktu', \Carbon\Carbon::parse($jadwal->waktu)->format('H:i')) == $time ? 'selected' : '' }}>{{ $time }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Kategori --}}
            <div>
                <label for="kategori" class="block mb-2 text-sm font-medium text-gray-700">Kategori</label>
                <select name="kategori" id="kategori" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                    <option value="" disabled>Pilih Kategori</option>
                    @foreach ([
                        'Bimbingan Masalah Pribadi',
                        'Bimbingan Sosial',
                        'Bimbingan Belajar',
                        'Bimbingan Karir',
                        'Pemanggilan Orang Tua'
                    ] as $kat)
                        <option value="{{ $kat }}" {{ old('kategori', $jadwal->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Nama Orang Tua --}}
            <div>
                <label for="nama_ortu" class="block mb-2 text-sm font-medium text-gray-700">Nama Orang Tua</label>
                <input type="text" name="nama_ortu" id="nama_ortu" 
                       class="w-full px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
                       value="{{ old('nama_ortu', $jadwal->nama_ortu) }}" placeholder="Nama Orang Tua Otomatis Terisi">
            </div>

            {{-- Notifikasi --}}
            <div class="flex items-center">
                <input type="checkbox" name="notifikasi" id="notifikasi" value="1" 
                       class="mr-2 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                       {{ old('notifikasi', $jadwal->notifikasi) ? 'checked' : '' }}>
                <label for="notifikasi" class="text-sm text-gray-700">Aktifkan Notifikasi</label>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col md:flex-row justify-between items-center pt-6 space-y-3 md:space-y-0 md:space-x-4">
                <button type="submit" 
                        class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold transition duration-200 w-full md:w-auto">
                    <i data-feather="save" class="w-4 h-4"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.jadwal.index') }}" 
                   class="flex items-center justify-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg text-sm font-semibold transition duration-200 w-full md:w-auto">
                    <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
