@extends('layouts.main')

@section('title', 'Edit Siswa')
@section('page-title', 'Edit Siswa')
@section('page-icon', 'user')

@section('content')

    {{-- Breadcrumb Navigation --}}
    <nav class="flex items-center text-sm text-gray-500 mb-6 space-x-2">
        <a href="{{ route('admin.siswa.index') }}" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">
            <i data-feather="users" class="w-4 h-4 inline-block mr-1"></i> Siswa
        </a>
        <span>/</span>
        <span class="text-gray-700 font-semibold">Edit Siswa</span>
    </nav>    

    {{-- Form Container --}}
    <div class="bg-white p-6 md:p-8 rounded-2xl max-w-4xl mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Form Edit Siswa</h2>

        <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Akun Siswa --}}
            <div class="mb-4 md:mb-0">
                <label class="block mb-2 text-sm font-medium text-gray-700">Akun Siswa</label>
                <input type="text" value="{{ $siswa->user->name }} ({{ $siswa->user->email }})" readonly
                    class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-sm text-gray-500 cursor-not-allowed mb-4" disabled>
            </div>

            {{-- NISN --}}
            <div class="mb-4 md:mb-0">
                <label class="block mb-2 text-sm font-medium text-gray-700">NISN</label>
                <input type="text" name="nisn" value="{{ old('nisn', $siswa->nisn) }}"
                    class="w-full px-4 py-3 border {{ $errors->has('nisn') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-gray-500 text-sm mb-4"
                    placeholder="NISN Siswa">
                @error('nisn')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama Siswa --}}
            <div class="mb-4 md:mb-0">
                <label class="block mb-2 text-sm font-medium text-gray-700">Nama Siswa</label>
                <input type="text" name="nama_siswa" value="{{ old('nama_siswa', $siswa->nama_siswa) }}"
                    class="w-full px-4 py-3 border {{ $errors->has('nama_siswa') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-gray-500 text-sm mb-4"
                    placeholder="Nama Lengkap">
                @error('nama_siswa')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kelas --}}
            <div class="mb-4 md:mb-0">
                <label class="block mb-2 text-sm font-medium text-gray-700">Kelas</label>
                <input type="text" name="kelas" value="{{ old('kelas', $siswa->kelas) }}"
                    class="w-full px-4 py-3 border {{ $errors->has('kelas') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-gray-500 text-sm mb-4"
                    placeholder="Kelas">
                @error('kelas')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- No WhatsApp Siswa --}}
            <div class="mb-4 md:mb-0">
                <label class="block mb-2 text-sm font-medium text-gray-700">No WhatsApp Siswa</label>
                <input type="text" name="no_whatsapp_siswa" value="{{ old('no_whatsapp_siswa', $siswa->no_whatsapp_siswa) }}"
                    class="w-full px-4 py-3 border {{ $errors->has('no_whatsapp_siswa') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-gray-500 text-sm mb-4"
                    placeholder="Contoh: 6281234567890">
                @error('no_whatsapp_siswa')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama Orang Tua --}}
            <div class="mb-4 md:mb-0">
                <label class="block mb-2 text-sm font-medium text-gray-700">Nama Orang Tua</label>
                <input type="text" name="nama_ortu" value="{{ old('nama_ortu', $siswa->nama_ortu) }}"
                    class="w-full px-4 py-3 border {{ $errors->has('nama_ortu') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-gray-500 text-sm mb-4"
                    placeholder="Nama Orang Tua">
                @error('nama_ortu')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- No WhatsApp Orang Tua --}}
            <div class="mb-4 md:mb-0">
                <label class="block mb-2 text-sm font-medium text-gray-700">No WhatsApp Orang Tua</label>
                <input type="text" name="no_whatsapp_ortu" value="{{ old('no_whatsapp_ortu', $siswa->no_whatsapp_ortu) }}"
                    class="w-full px-4 py-3 border {{ $errors->has('no_whatsapp_ortu') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-gray-500 text-sm mb-4"
                    placeholder="Contoh: 6281234567890">
                @error('no_whatsapp_ortu')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Foto Siswa --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Siswa</label>
                <input type="file" name="foto" accept="image/*"
                    class="w-full border px-4 py-2.5 rounded-lg text-sm file:bg-blue-600 file:text-white file:py-1 file:px-4 file:rounded file:border-0 hover:file:bg-blue-700 {{ $errors->has('foto') ? 'border-red-500' : 'border-gray-300' }}">
                @error('foto')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                @if ($siswa->foto)
                    <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto Lama" class="w-24 h-24 rounded-lg mt-2 object-cover">
                @else
                    <p class="text-gray-500 text-xs mt-1">Tidak ada foto yang diunggah.</p>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex flex-col md:flex-row justify-between items-center pt-6 space-y-3 md:space-y-0 md:space-x-4">
                <button type="submit" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold transition duration-200 w-full md:w-auto">
                    <i data-feather="save" class="w-4 h-4"></i>
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.siswa.index') }}" class="flex items-center justify-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg text-sm font-semibold transition duration-200 w-full md:w-auto">
                    <i data-feather="arrow-left" class="w-4 h-4"></i>
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
