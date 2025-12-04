@extends('layouts.main-guru')

@section('title', 'Beranda Guru')

@section('content')

    {{-- Section Hero --}}
    <section class="py-50 lg:py-3.5 bg-gradient-to-b from-white via-blue-50 to-blue-100">
        <div class="w-full flex flex-col-reverse lg:flex-row items-center gap-16 lg:gap-24 px-4 md:px-8 lg:px-20">
            <div class="w-full lg:w-1/2 space-y-6">
                <h1 id="text-running" class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                    Selamat Datang, {{ Auth::user()->name }} 👋
                </h1>
                <p class="text-lg text-gray-700">Kelola jadwal, pantau siswa, dan laporan kegiatan bimbingan konseling dengan lebih mudah dan efisien.</p>
                <div class="flex flex-wrap gap-4 pt-4">
                    <a href="{{ route('guru.jadwal.index')}}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-full transition duration-300 shadow">
                        Jadwal BK
                    </a>
                    <a href="{{ route('guru.siswa.index')}}" class="border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-semibold px-6 py-3 rounded-full transition duration-300 shadow">
                        Siswa
                    </a>
                </div>
            </div>
            <div class="w-full lg:w-1/2">
                <img src="{{ asset('assets/home-guru.png') }}" alt="Ilustrasi Guru" class="w-full max-w-md mx-auto drop-shadow-2xl">
            </div>
        </div>
    </section>
    {{-- End Section Hero --}}
    
    {{-- Section Keunggulan --}}
    <section class="max-w-7xl mx-auto mt-24 px-4 md:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Keunggulan SIPENBK</h2>
            <p class="text-gray-600 text-lg">Solusi digital terintegrasi untuk layanan Bimbingan dan Konseling di sekolah</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- Integrasi WhatsApp --}}
            <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transition">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/IC-WA.png') }}" alt="Integrasi WhatsApp" class="w-12 h-12">
                </div>
                <h3 class="text-xl font-semibold text-green-600 mb-2">Terintegrasi WhatsApp</h3>
                <p class="text-gray-600 text-sm">Notifikasi jadwal langsung ke siswa dan orang tua melalui WhatsApp.</p>
            </div>

            {{-- Data Siswa --}}
            <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transition">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/IC-DATA.png') }}" alt="Data Terpusat" class="w-12 h-12">
                </div>
                <h3 class="text-xl font-semibold text-blue-600 mb-2">Data Terpusat</h3>
                <p class="text-gray-600 text-sm">Kelola data siswa, guru BK, jadwal, dan laporan secara terpusat dan efisien.</p>
            </div>

            {{-- Akses Multi-Role --}}
            <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transition">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/IC-USER.png') }}" alt="Multi Role" class="w-12 h-12">
                </div>
                <h3 class="text-xl font-semibold text-indigo-600 mb-2">Akses Multi-Role</h3>
                <p class="text-gray-600 text-sm">Sistem aman dengan peran admin, guru BK, dan siswa. Hak akses disesuaikan.</p>
            </div>

            {{-- Rekap & Laporan --}}
            <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transition">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/IC-LAPORAN.png') }}" alt="Laporan" class="w-12 h-12">
                </div>
                <h3 class="text-xl font-semibold text-amber-600 mb-2">Rekap & Laporan Otomatis</h3>
                <p class="text-gray-600 text-sm">Export laporan bimbingan konseling, dan aktivitas konseling dalam sekali klik.</p>
            </div>
        </div>
    </section>
    {{-- End Section Keunggulan --}}

{{-- untuk memanggil nama guru --}}
<script>
    window.userName = @json($namaGuru);
</script>
@endsection
