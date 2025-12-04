@extends('layouts.main')

@section('title', 'Edit Guru BK')
@section('page-title', 'Edit Guru BK')
@section('page-icon', 'edit')

@section('content')

{{-- Breadcrumb --}}
<nav class="flex items-center text-sm text-gray-500 mb-6 space-x-2">
    <a href="{{ route('admin.guru_bk.index') }}" class="text-blue-600 hover:text-blue-700 font-medium flex items-center">
        <i data-feather="users" class="w-4 h-4 mr-1"></i> Guru BK
    </a>
    <span>/</span>
    <span class="text-gray-700 font-semibold">Edit Guru BK</span>
</nav>

{{-- Form Card --}}
<div class="bg-white p-6 sm:p-8 rounded-2xl max-w-4xl mx-auto">
    <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-6">Form Edit Guru BK</h2>

    <form action="{{ route('admin.guru_bk.update', $guruBk->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Akun User --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Akun Guru</label>
            <input type="text" value="{{ $guruBk->user->name }} ({{ $guruBk->user->email }})" disabled
                class="w-full bg-gray-100 border border-gray-300 text-gray-500 text-sm px-4 py-2.5 rounded-lg cursor-not-allowed">
        </div>

        {{-- Nama Guru BK --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="nama_guru_bk" value="{{ old('nama_guru_bk', $guruBk->nama_guru_bk) }}"
                class="w-full border px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 {{ $errors->has('nama_guru_bk') ? 'border-red-500' : 'border-gray-300' }}">
            @error('nama_guru_bk')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Jabatan --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
            <input type="text" name="jabatan" value="{{ old('jabatan', $guruBk->jabatan) }}"
                class="w-full border px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 {{ $errors->has('jabatan') ? 'border-red-500' : 'border-gray-300' }}">
            @error('jabatan')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- No WhatsApp --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">No WhatsApp</label>
            <input type="text" name="no_whatsapp_guru_bk" value="{{ old('no_whatsapp_guru_bk', $guruBk->no_whatsapp_guru_bk) }}"
                class="w-full border px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 {{ $errors->has('no_whatsapp_guru_bk') ? 'border-red-500' : 'border-gray-300' }}">
            @error('no_whatsapp_guru_bk')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Foto --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Guru BK</label>
            <input type="file" name="foto" accept="image/*"
                class="w-full border px-4 py-2.5 rounded-lg text-sm file:bg-blue-600 file:text-white file:py-1 file:px-4 file:rounded file:border-0 hover:file:bg-blue-700 {{ $errors->has('foto') ? 'border-red-500' : 'border-gray-300' }}">
            @error('foto')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            @if ($guruBk->foto)
                <img src="{{ asset('storage/' . $guruBk->foto) }}" alt="Foto Lama" class="w-24 h-24 rounded-lg mt-2 object-cover">
            @endif
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6">
            <button type="submit"
                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg flex items-center gap-2">
                <i data-feather="save" class="w-4 h-4"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.guru_bk.index') }}"
                class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-6 py-2.5 rounded-lg flex items-center gap-2">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    feather.replace();
</script>
@endpush
