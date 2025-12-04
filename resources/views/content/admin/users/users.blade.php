@extends('layouts.main')

@section('title', 'Dashboard - Pengguna')
@section('page-title', 'Data Pengguna')
@section('page-icon', 'user')

@section('content')
{{-- Header --}}
<div class="flex flex-wrap items-center justify-between mb-6 gap-3">
    <h2 class="flex-grow text-xl sm:text-2xl font-bold text-gray-800 tracking-tight min-w-0">
        Daftar Pengguna
    </h2>

    {{-- Tombol Tambah Pengguna dengan Dropdown --}}
    <div x-data="{ open: false }" class="relative inline-block text-left">
        <button @click="open = !open" type="button"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-4 py-2 rounded-xl text-sm sm:text-base font-semibold shadow transition-all">
            <i data-feather="user-plus" class="w-4 h-4 sm:w-5 sm:h-5"></i>
            Tambah Pengguna
            <svg class="-mr-1 ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        {{-- Dropdown --}}
        <div x-show="open" @click.away="open = false" x-transition
            class="absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20">
            <div class="py-1 text-sm text-gray-700">
                <a href="{{ route('admin.users.create.admin') }}" class="block px-4 py-2 hover:bg-gray-100">Admin</a>
                <a href="{{ route('admin.users.create.guru') }}" class="block px-4 py-2 hover:bg-gray-100">Guru BK</a>
                <a href="{{ route('admin.users.create.siswa') }}" class="block px-4 py-2 hover:bg-gray-100">Siswa</a>
            </div>
        </div>
    </div>
</div>


{{-- Filter & Search --}}
<form method="GET" action="{{ route('admin.users.index') }}"
      class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
    <div class="w-full md:w-1/3 relative">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari pengguna..."
            class="w-full border border-gray-300 pl-9 pr-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base placeholder-gray-400 shadow-sm"
        />
        <i data-feather="search" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
    </div>

    <div class="flex flex-wrap gap-3 items-center">
        {{-- Role --}}
        <select name="role"
            class="border border-gray-300 px-3 py-2 rounded-lg text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm"
            onchange="this.form.submit()">
            <option value="">Filter Role</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru BK</option>
            <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
        </select>

        {{-- Sort --}}
        <select name="sort"
            class="border border-gray-300 px-3 py-2 rounded-lg text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm"
            onchange="this.form.submit()">
            <option value="">Urutkan</option>
            <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Nama A-Z</option>
            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Nama Z-A</option>
        </select>

        {{-- Reset --}}
        <a href="{{ route('admin.users.index') }}"
            class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-lg transition duration-150 shadow-sm">
            <i data-feather="x" class="w-4 h-4 text-gray-600"></i>
        </a>
    </div>
</form>


{{-- Table --}}
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wide">
            <tr>
                <th class="px-5 py-4">Nama</th>
                <th class="px-5 py-4">Email</th>
                <th class="px-5 py-4">Role</th>
                <th class="px-5 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
            <tr class="hover:bg-blue-50 transition duration-200">
                <td class="px-5 py-4 font-semibold text-gray-900">{{ $user->name }}</td>
                <td class="px-5 py-4 text-gray-700">{{ $user->email }}</td>
                <td class="px-5 py-4">
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                        {{ $user->role === 'admin' ? 'bg-red-100 text-red-600' : ($user->role === 'guru' ? 'bg-yellow-100 text-yellow-600' : 'bg-green-100 text-green-600') }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td class="px-5 py-4">
                    <div class="flex justify-center gap-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-400 hover:bg-amber-500 text-white rounded-md text-xs font-medium transition shadow-sm">
                            <i data-feather="edit" class="w-4 h-4"></i>
                            Edit
                        </a>

                        <x-delete-button :id="$user->id" :action="route('admin.users.destroy', $user->id)" />
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-5 py-6 text-center text-gray-500">
                    Tidak ada data pengguna ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{-- Pagination --}}
    @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-12 flex justify-center">
            {{ $users->links('vendor.pagination.tailwind-modern') }}
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
