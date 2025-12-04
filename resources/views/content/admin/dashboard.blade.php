@extends('layouts.main')

@section('title', 'Dashboard - Beranda Admin')
@section('page-title', 'Beranda')

@section('content')

    {{-- Ringkasan Data --}}
    <div 
        x-data="{ show: false }" 
        x-init="setTimeout(() => show = true, 100)" 
        x-show="show"
        x-transition:enter="transition-all ease-in-out duration-1000"
        x-transition:enter-start="opacity-0 -translate-y-5 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
    >
        <x-dashboard-card icon="users" label="Jumlah Siswa" value="{{ $totalSiswa }}" color="blue" />
        <x-dashboard-card icon="user-check" label="Guru BK" value="{{ $totalGuruBK }}" color="emerald" />
        <x-dashboard-card icon="calendar" label="Jadwal Hari Ini" value="{{ $jadwalHariIni }}" color="amber" />
        <x-dashboard-card icon="shield" label="Total Pengguna" value="{{ $totalUsers }}" color="rose" />
    </div>

    {{-- Section Selamat Datang --}}
    <div 
        x-data="{ show: false }" 
        x-init="setTimeout(() => show = true, 500)" 
        x-show="show"
        x-transition:enter="transition-all ease-in-out duration-1000"
        x-transition:enter-start="opacity-0 translate-y-10 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        class="relative bg-white rounded-3xl overflow-hidden shadow-lg ring-1 ring-gray-200 mb-10"
    >
        <div class="absolute inset-0">
            <img src="{{ asset('assets/bg-pattern-modern.svg') }}" alt="Pattern" class="w-full h-full object-cover opacity-20">
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-6 p-10 bg-gradient-to-br from-indigo-600 via-blue-600 to-indigo-700 text-white rounded-3xl">
            {{-- Text --}}
            <div 
                x-data="{ show: false }" 
                x-init="setTimeout(() => show = true, 800)" 
                x-show="show"
                x-transition:enter="transition-all ease-in-out duration-1000"
                x-transition:enter-start="opacity-0 -translate-x-5 scale-95"
                x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                class="flex-1 max-w-xl"
            >
                <h1 id="greeting" class="text-4xl font-extrabold leading-tight mb-3 drop-shadow-sm">
                    Selamat Datang, {{ Auth::user()->name }} 👋
                </h1>
                <p class="text-lg text-blue-100 mb-6">
                    Dashboard ini dirancang untuk mempermudah pengelolaan bimbingan konseling secara efisien dan real-time melalui sistem <span class="font-semibold">SIPENBK</span>.
                </p>
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center gap-2 bg-white text-indigo-700 font-semibold px-3 py-2 rounded-full shadow hover:bg-indigo-50 transition-all duration-200 text-sm sm:px-5 sm:py-3 sm:text-base">
                    <i data-feather="settings" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    Kelola Data Pengguna
                </a>
            </div>

            {{-- Ilustrasi Carousel --}}
            <div 
                x-data="{ show: false }" 
                x-init="setTimeout(() => show = true, 1000)" 
                x-show="show"
                x-transition:enter="transition-all ease-in-out duration-1000"
                x-transition:enter-start="opacity-0 translate-x-5 scale-95"
                x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                class="flex-1"
            >
                <div x-data="{ current: 0, images: [
                    '{{ asset('assets/HOME-1.png') }}',
                    '{{ asset('assets/HOME-2.png') }}'
                ] }" class="relative w-full max-w-sm mx-auto">
                    <img :src="images[current]" class="rounded-2xl shadow-lg w-full h-auto transition-all duration-500 ease-in-out" alt="Ilustrasi Slide">

                    <button @click="current = (current - 1 + images.length) % images.length"
                        class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-700 p-2 rounded-full shadow">
                        <i data-feather="chevron-left" class="w-5 h-5"></i>
                    </button>

                    <button @click="current = (current + 1) % images.length"
                        class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-700 p-2 rounded-full shadow">
                        <i data-feather="chevron-right" class="w-5 h-5"></i>
                    </button>

                    <div class="absolute bottom-3 left-1/2 transform -translate-x-1/2 flex gap-2">
                        <template x-for="(image, index) in images" :key="index">
                            <div :class="{'bg-white': current === index, 'bg-white/50': current !== index}"
                                class="w-2.5 h-2.5 rounded-full transition-all duration-300"></div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.userName = @json(Auth::user()->name);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            feather.replace();
        });
    </script>
@endsection
