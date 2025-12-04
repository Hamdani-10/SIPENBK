<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased">

<div class="min-h-screen flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-3xl bg-white rounded-3xl shadow-2xl p-8 md:p-12 space-y-8">

        <!-- Judul -->
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Profil Siswa</h2>
            <p class="text-sm text-gray-500 mt-2">Lihat dan perbarui foto profilmu</p>
        </div>

        <!-- Notifikasi sukses -->
        @if (session('success'))
            <div class="bg-green-100 text-green-800 border border-green-300 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Profil -->
        <form action="{{ route('siswa.profil.update', $siswa->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" value="{{ $siswa->nama_siswa }}" readonly class="w-full border border-gray-300 rounded-xl px-4 py-2 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Kelas</label>
                    <input type="text" value="{{ $siswa->kelas }}" readonly class="w-full border border-gray-300 rounded-xl px-4 py-2 bg-gray-100">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">No. WhatsApp Siswa</label>
                    <input type="text" value="{{ $siswa->no_whatsapp_siswa }}" readonly class="w-full border border-gray-300 rounded-xl px-4 py-2 bg-gray-100">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Nama Orang Tua</label>
                    <input type="text" value="{{ $siswa->nama_ortu }}" readonly class="w-full border border-gray-300 rounded-xl px-4 py-2 bg-gray-100">
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-1 text-sm font-medium text-gray-700">No. WhatsApp Orang Tua</label>
                    <input type="text" value="{{ $siswa->no_whatsapp_ortu }}" readonly class="w-full border border-gray-300 rounded-xl px-4 py-2 bg-gray-100">
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Foto Profil</label>
                    @if ($siswa->foto)
                        <div class="mb-4 flex items-center gap-4">
                            <img src="{{ asset('storage/' . $siswa->foto) }}" class="w-24 h-24 object-cover rounded-full border-2 border-gray-300">
                            <span class="text-gray-500 text-sm">Foto saat ini</span>
                        </div>
                    @endif
                    <input type="file" name="foto" required class="block w-full text-sm text-gray-600 file:py-2 file:px-6 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('foto')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tombol -->
            <div class="pt-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2 rounded-xl shadow-md transition duration-200">
                    Perbarui Foto
                </button>

                <a href="{{ route('siswa.dashboard.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-8 py-2 rounded-xl shadow-md transition duration-200">
                    ← Kembali ke Beranda
                </a>
            </div>
        </form>

    </div>
</div>

</body>
</html>
