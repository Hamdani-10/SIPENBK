@extends('layouts.main')

@section('title', 'Edit Panduan')
@section('page-title', 'Edit Panduan')
@section('page-icon', 'edit')

@section('content')
    {{-- Breadcrumb Navigation --}}
    <nav class="flex items-center text-sm text-gray-500 mb-6 space-x-2">
        <a href="{{ route('admin.panduan.index') }}" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">
            <i data-feather="file-text" class="w-4 h-4 inline-block mr-1"></i> Panduan
        </a>
        <span>/</span>
        <span class="text-gray-700 font-semibold">Edit Panduan</span>
    </nav>

    {{-- Card --}}
    <div class="bg-white p-8 rounded-2xl  max-w-4xl mx-auto">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Form Edit Panduan</h2>

        <form action="{{ route('admin.panduan.update', $panduan) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Judul --}}
            <div>
                <label for="judul" class="block mb-2 text-sm font-medium text-gray-700">Judul Panduan</label>
                <input type="text" name="judul" id="judul"
                    value="{{ old('judul', $panduan->judul) }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-400 focus:outline-none"
                    required>
                @error('judul')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Isi Panduan --}}
            <div>
                <label for="isi_panduan" class="block mb-2 text-sm font-medium text-gray-700">Isi Panduan</label>
                <textarea name="isi_panduan" id="isi_panduan" rows="6"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-400 focus:outline-none resize-none"
                    required>{{ old('isi_panduan', $panduan->isi_panduan) }}</textarea>
                @error('isi_panduan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Menuntukan panduan untuk role --}}
            <div class="">
                <label for="role" class="block mb-2 text-sm font-medium text-gray-700">Panduan Untuk</label>
                <select name="role" id="role" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-400 focus:outline-none resize-none">
                    <option value="guru" {{ old('role', $panduan->role ?? '') === 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ old('role', $panduan->role ?? '') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
            </div>
            

            {{-- Upload Gambar --}}
            <div>
                <label for="gambar" class="block mb-2 text-sm font-medium text-gray-700">Gambar (Opsional)</label>
                <input type="file" name="gambar" id="gambar"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                @error('gambar')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                @if ($panduan->gambar)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                        <img src="{{ asset('storage/' . $panduan->gambar) }}" alt="Gambar Panduan"
                            class="h-24 rounded-lg shadow-md border border-gray-200">
                    </div>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col md:flex-row justify-between items-center pt-6 space-y-3 md:space-y-0 md:space-x-4">
                <button type="submit"
                    class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold transition duration-200 w-full md:w-auto">
                    <i data-feather="save" class="w-4 h-4"></i>
                    Simpan Perubahan
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
