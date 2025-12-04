@extends('layouts.main-siswa')

@section('title', 'Beranda Siswa')

@section('content')

    {{-- Section Hero --}}
    <section class="py-50 lg:py-3.5 bg-gradient-to-b from-white via-blue-50 to-blue-100">
        <div class="w-full flex flex-col-reverse lg:flex-row items-center gap-16 lg:gap-24 px-4 md:px-8 lg:px-20">
            <div class="w-full lg:w-1/2 space-y-6">
                <h1 id="text-jalan" class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                    Halo, {{ Auth::user()->name }} 👋
                </h1>
                <p class="text-lg text-gray-700">Pantau jadwal, konsultasi, dan lihat hasil bimbingan dengan mudah dari satu tempat.</p>
                <div class="flex flex-wrap gap-4 pt-4">
                    <a href="{{route('siswa.jadwal.index')}}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-full transition duration-300 shadow">
                        Jadwal BK
                    </a>
                    <a href="{{route('siswa.rekap.index')}}" class="border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-semibold px-6 py-3 rounded-full transition duration-300 shadow">
                        Rekap Hasil
                    </a>
                </div>
            </div>
            <div class="w-full lg:w-1/2">
                <img src="{{ asset('assets/BACK-SISWA.png') }}" alt="Ilustrasi Siswa" class="w-full max-w-md mx-auto drop-shadow-2xl">
            </div>
        </div>
    </section>
    {{-- End Section Hero --}}
    
    {{-- Section Keunggulan --}}
    <section class="max-w-7xl mx-auto mt-24 px-4 md:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Kenapa Gunakan SIPENBK?</h2>
            <p class="text-gray-600 text-lg">Sistem pintar untuk mendukung proses bimbingan siswa secara online dan terstruktur</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- WhatsApp Reminder --}}
            <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transition">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/IC-WA.png') }}" alt="WA Reminder" class="w-12 h-12">
                </div>
                <h3 class="text-xl font-semibold text-green-600 mb-2">Pengingat via WhatsApp</h3>
                <p class="text-gray-600 text-sm">Jangan lewatkan jadwal konsultasi, notifikasi langsung ke WhatsApp kamu dan orang tuamu.</p>
            </div>

            {{-- Akses Mudah --}}
            <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transition">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/IC-USER.png') }}" alt="User Friendly" class="w-12 h-12">
                </div>
                <h3 class="text-xl font-semibold text-blue-600 mb-2">Akses Mudah & Aman</h3>
                <p class="text-gray-600 text-sm">Login dengan akun siswa dan akses fitur sesuai kebutuhanmu dengan aman.</p>
            </div>

            {{-- Konsultasi Terjadwal --}}
            <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transition">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/IC-DATA.png') }}" alt="Konsultasi Terjadwal" class="w-12 h-12">
                </div>
                <h3 class="text-xl font-semibold text-indigo-600 mb-2">Konsultasi Terjadwal</h3>
                <p class="text-gray-600 text-sm">Ikuti sesi bimbingan sesuai jadwal yang sudah dibuat oleh guru BK.</p>
            </div>

            {{-- Rekap Otomatis --}}
            <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-xl transition">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/IC-LAPORAN.png') }}" alt="Laporan Otomatis" class="w-12 h-12">
                </div>
                <h3 class="text-xl font-semibold text-amber-600 mb-2">Rekap Otomatis</h3>
                <p class="text-gray-600 text-sm">Lihat hasil bimbingan dan catatan penting dari sesi BK dalam tampilan yang rapi.</p>
            </div>
        </div>
    </section>
    {{-- End Section Keunggulan --}}

    <script>
        window.userName = @json($namaSiswa ?? 'namaSiswa');
    </script>
@endsection
