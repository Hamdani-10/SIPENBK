<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tambah Pengguna Siswa</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center px-4" x-data>
    <div class="w-full max-w-md bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl mx-auto mt-10">
        <div class="mb-6 text-center">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Tambah Siswa</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Lengkapi data siswa</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300 dark:border-gray-700' }} rounded-xl bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300 dark:border-gray-700' }} rounded-xl bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- NISN --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NISN</label>
                <input type="text" name="nisn" value="{{ old('nisn') }}" required
                    class="w-full px-4 py-2 border {{ $errors->has('nisn') ? 'border-red-500' : 'border-gray-300 dark:border-gray-700' }} rounded-xl bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                @error('nisn')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kelas --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelas</label>
                <input type="text" name="kelas" value="{{ old('kelas') }}" required
                    class="w-full px-4 py-2 border {{ $errors->has('kelas') ? 'border-red-500' : 'border-gray-300 dark:border-gray-700' }} rounded-xl bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                @error('kelas')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- No WhatsApp Siswa --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No WhatsApp Siswa</label>
                <input type="text" name="no_whatsapp_siswa" value="{{ old('no_whatsapp_siswa') }}" placeholder="format nomor 6281 bukan 081" required
                    class="w-full px-4 py-2 border {{ $errors->has('no_whatsapp_siswa') ? 'border-red-500' : 'border-gray-300 dark:border-gray-700' }} rounded-xl bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                @error('no_whatsapp_siswa')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama Orang Tua --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Orang Tua</label>
                <input type="text" name="nama_ortu" value="{{ old('nama_ortu') }}" required
                    class="w-full px-4 py-2 border {{ $errors->has('nama_ortu') ? 'border-red-500' : 'border-gray-300 dark:border-gray-700' }} rounded-xl bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                @error('nama_ortu')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- No WhatsApp Orang Tua --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No WhatsApp Orang Tua</label>
                <input type="text" name="no_whatsapp_ortu" value="{{ old('no_whatsapp_ortu') }}" placeholder="format nomor 6281 bukan 081" required
                    class="w-full px-4 py-2 border {{ $errors->has('no_whatsapp_ortu') ? 'border-red-500' : 'border-gray-300 dark:border-gray-700' }} rounded-xl bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                @error('no_whatsapp_ortu')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- role --}}
            <input type="hidden" name="role" value="siswa">

            {{-- Foto --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Foto</label>
                <input type="file" name="foto"
                    class="w-full px-3 py-2.5 border {{ $errors->has('foto') ? 'border-red-500' : 'border-gray-300 dark:border-gray-700' }} rounded-lg focus:ring-blue-400 file:mr-2 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Maks 2MB.</p>
                @error('foto')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300 dark:border-gray-700' }} rounded-xl bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-2 border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-300 dark:border-gray-700' }} rounded-xl bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                @error('password_confirmation')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Simpan --}}
            <div class="pt-3">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl shadow">
                    Simpan Siswa
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
