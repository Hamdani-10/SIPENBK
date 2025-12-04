<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Pengguna Admin</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center px-4" x-data>

    <div class="w-full max-w-md bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl">
        <div class="mb-6 text-center">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Tambah Admin</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Lengkapi Data Admin</p>
        </div>

        {{-- Alert Semua Error --}}
        @if ($errors->any())
            <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Tambah Pengguna --}}
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                <input type="text" name="name" required
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300 dark:border-gray-700' }} rounded-xl bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input type="email" name="email" required
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300 dark:border-gray-700' }} rounded-xl bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- role --}}
            <input type="hidden" name="role" value="admin">

            {{-- Konfirmasi Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            {{-- Tombol --}}
            <div class="pt-3">
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl transition-all shadow">
                    Simpan Pengguna
                </button>
            </div>
        </form>

        <div class="text-center mt-5">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline">
                ← Kembali ke daftar pengguna
            </a>
        </div>
    </div>
</body>
</html>
