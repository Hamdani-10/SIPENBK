@extends('layouts.main')

@section('title', 'Tambah Panduan')
@section('page-title', 'Tambah Panduan')
@section('page-icon', 'file-plus')

@section('content')
    {{-- Breadcrumb Navigation --}}
    <nav class="flex items-center text-sm text-gray-500 mb-6 space-x-2">
        <a href="{{ route('admin.panduan.index') }}" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">
            <i data-feather="file-text" class="w-4 h-4 inline-block mr-1"></i> Panduan
        </a>
        <span>/</span>
        <span class="text-gray-700 font-semibold">Tambah Panduan</span>
    </nav>

    {{-- Card --}}
    <div class="bg-white p-6 rounded-2xl border border-gray-100">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Form Tambah Panduan</h2>

        <form action="{{ route('admin.panduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Judul --}}
            <div>
                <label for="judul" class="block mb-2 text-sm font-medium text-gray-700">Judul Panduan</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul') }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                @error('judul') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Isi Panduan --}}
            <div>
                <label for="isi_panduan" class="block mb-2 text-sm font-medium text-gray-700">Isi Panduan</label>
                <textarea name="isi_panduan" id="isi_panduan" rows="5"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 resize-none focus:outline-none"
                    required>{{ old('isi_panduan') }}</textarea>
                @error('isi_panduan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Menuntukan panduan untuk role --}}
            <div class="">
                <label for="role" class="block mb-2 text-sm font-medium text-gray-700">Panduan Untuk</label>
                <select name="role" id="role" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-400 focus:outline-none resize-none">
                    <option value="guru" {{ old('role', $panduan->role ?? '') === 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ old('role', $panduan->role ?? '') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
            </div>

            {{-- Gambar --}}
            <div class="mb-4">
                <label for="gambar" class="block mb-2 text-sm font-medium text-gray-700">
                    Unggah Gambar (Opsional)
                </label>
                <input type="file" name="gambar" id="gambar"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                <p class="text-xs text-gray-500 mt-1">
                    Format gambar yang didukung: JPG, PNG, atau JPEG. Ukuran maksimal 2MB.
                </p>
                @error('gambar')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>


            {{-- Actions --}}
            <div class="flex flex-col md:flex-row justify-between items-center pt-6 space-y-3 md:space-y-0 md:space-x-4">
                <button type="submit"
                    class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold transition duration-200 w-full md:w-auto">
                    <i data-feather="save" class="w-4 h-4"></i>
                    Simpan Panduan
                </button>
                <a href="{{ route('admin.panduan.index') }}"
                    class="flex items-center justify-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg text-sm font-semibold transition duration-200 w-full md:w-auto">
                    <i data-feather="arrow-left" class="w-4 h-4"></i>
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
