@extends('layouts.main')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')
@section('page-icon', 'user')

@section('content')
    {{-- Breadcrumb Navigation --}}
    <nav class="flex flex-wrap items-center text-sm text-gray-500 mb-6 space-x-2">
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-700 font-medium transition-colors flex items-center">
            <i data-feather="users" class="w-4 h-4 inline-block mr-1"></i> Pengguna
        </a>
        <span>/</span>
        <span class="text-gray-700 font-semibold">Edit Pengguna</span>
    </nav>    

    {{-- Form Container --}}
    <div class="bg-white p-6 sm:p-8 rounded-2xl max-w-4xl mx-auto">
        <h2 class="text-lg sm:text-2xl font-semibold text-gray-800 mb-6">Form Edit Pengguna</h2>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div>
                <label class="block mb-2 text-xs sm:text-sm font-medium text-gray-700">Nama</label>
                <input 
                    type="text" 
                    name="name" 
                    value="{{ $user->name }}" 
                    class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-gray-500 text-xs sm:text-sm"
                    placeholder="Nama Pengguna"
                >
            </div>

            {{-- Email --}}
            <div>
                <label class="block mb-2 text-xs sm:text-sm font-medium text-gray-700">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ $user->email }}" 
                    readonly 
                    class="w-full px-3 sm:px-4 py-2.5 sm:py-3 bg-gray-100 text-gray-500 border border-gray-300 rounded-lg cursor-not-allowed text-xs sm:text-sm" 
                    placeholder="Email Pengguna"
                >
            </div>

            {{-- Role --}}
            <div>
                <label class="block mb-2 text-xs sm:text-sm font-medium text-gray-700">Role</label>
                <select 
                    name="role" 
                    class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-xs sm:text-sm"
                >
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ $user->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row justify-between items-center pt-6 space-y-3 sm:space-y-0 sm:space-x-4">
                <button type="submit" class="flex items-center justify-center gap-2 w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition duration-200">
                    <i data-feather="save" class="w-4 h-4"></i>
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}" class="flex items-center justify-center gap-2 w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2.5 rounded-lg text-sm font-semibold transition duration-200">
                    <i data-feather="arrow-left" class="w-4 h-4"></i>
                    Kembali
                </a>
            </div>
        </form>
    </div>
@endsection
