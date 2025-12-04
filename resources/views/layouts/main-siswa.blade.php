<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Siswa')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- JS Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/locale/id.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Icon & Fonts --}}
    <script src="https://unpkg.com/feather-icons"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600&display=swap" rel="stylesheet">

    {{-- Styling --}}
    @vite('resources/css/app.css')
    @vite('resources/js/semua.js')

    {{-- Utility --}}
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body x-cloak class="bg-white min-h-screen font-[Poppins] antialiased" x-data="{ open: false, showLogoutModal: false }">

    {{-- Navbar Siswa --}}
    <nav id="navbar" x-data="{ open: false }" class="w-full z-50 fixed top-0 left-0 bg-transparent py-4 md:py-6 transition-all duration-300">
        <div class="max-w-7xl mx-auto flex items-center justify-between px-4 md:px-8">
            {{-- Logo --}}
            <div class="flex items-center space-x-2 text-xl md:text-2xl font-bold">
                <img src="{{ asset('assets/LOGO-SMA.png') }}" alt="Logo" class="h-8 md:h-10 w-8 md:w-10 object-contain" loading="lazy">
                <span class="text-blue-600 tracking-tight">SIPEN<span class="text-gray-900">BK</span></span>
            </div>

            {{-- Menu Desktop --}}
            <div class="hidden md:flex items-center space-x-6 text-sm font-medium">
                @php
                    $navItems = [
                        ['label' => 'Beranda', 'route' => 'siswa.dashboard.index'],
                        ['label' => 'Jadwal BK', 'route' => 'siswa.jadwal.index'],
                        ['label' => 'Laporan BK', 'route' => 'siswa.rekap.index'],
                        ['label' => 'Panduan', 'route' => 'siswa.panduan.index'],
                    ];
                @endphp
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                        class="{{ Route::is($item['route']) ? 'text-blue-600 relative after:absolute after:bottom-[-6px] after:left-0 after:w-full after:h-0.5 after:bg-blue-500' : 'text-gray-600 hover:text-blue-600 transition' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- Dropdown Profil Desktop --}}
            <div x-data="{ open: false }" class="relative hidden md:block">
                <button @click="open = !open" @keydown.escape="open = false" @click.outside="open = false"
                    class="flex items-center gap-3 bg-white px-4 py-2 border border-gray-200 md:px-5 md:py-3 rounded-full transition-all duration-300 focus:outline-none">
                    <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-300">
                        <img src="{{ Auth::user()->siswa && Auth::user()->siswa->foto 
                            ? asset('storage/' . Auth::user()->siswa->foto) 
                            : asset('assets/profile.png') }}"
                            alt="Foto Siswa" class="w-full h-full object-cover object-top" loading="lazy"
                            onerror="this.src='{{ asset('assets/default-user.png') }}'">
                    </div>
                    <div class="text-left hidden md:block">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->siswa->nama_user ?? 'Nama tidak tersedia' }}</p>
                        <p class="text-xs text-gray-500">Siswa</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-600 ml-auto transition-transform duration-300"
                        :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Menu Dropdown --}}
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-1"
                    class="absolute right-0 z-50 mt-2 w-48 bg-white border border-gray-200 rounded-lg overflow-hidden"
                    style="display: none;">
                    <div class="py-2">
                        <a href="{{route('siswa.profil.index')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>
                        <form method="POST">
                            <button type="button" @click="showLogoutModal = true"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 border-t border-gray-100 flex items-center gap-2">
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Tombol Hamburger --}}
            <button @click="open = !open" class="md:hidden focus:outline-none z-50 ml-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Overlay --}}
        <div class="fixed inset-0 z-30" x-show="open" x-transition.opacity @click="open = false" x-cloak></div>

        {{-- Sidebar Mobile --}}
        <div class="fixed top-0 left-0 h-full w-4/5 max-w-xs bg-white shadow-2xl transform transition-transform duration-300 ease-in-out z-40 flex flex-col justify-between rounded-r-2xl border-r border-gray-200"
            :class="{ 'translate-x-0': open, '-translate-x-full': !open }" x-cloak>
            <div class="p-6 space-y-6">
                <div class="flex items-center space-x-3 text-xl font-extrabold">
                    <img src="{{ asset('assets/LOGO-SMA.png') }}" alt="Logo" class="h-10 w-10 object-contain drop-shadow-md" loading="lazy">
                    <span class="text-blue-600 tracking-tight">SIPEN<span class="text-gray-900">BK</span></span>
                </div>

                {{-- Profil Mobile --}}
                <div class="flex items-center gap-3 bg-gray-100 rounded-xl px-4 py-3 shadow-inner">
                    <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-300 shadow-sm">
                        <img src="{{ Auth::user()->siswa && Auth::user()->siswa->foto 
                            ? asset('storage/' . Auth::user()->siswa->foto) 
                            : asset('assets/default-user.png') }}"
                            alt="Foto Siswa" class="w-full h-full object-cover object-top" loading="lazy"
                            onerror="this.src='{{ asset('assets/profile.png') }}'">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-gray-800">{{ Auth::user()->siswa->nama_user ?? 'Nama tidak tersedia' }}</span>
                        <a href="{{route('siswa.profil.index')}}" class="text-xs text-blue-600 hover:underline font-medium">Profil Saya</a>
                    </div>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Menu</p>
                    <div class="flex flex-col space-y-3 text-sm font-medium">
                        @foreach ($navItems as $item)
                            <a href="{{ route($item['route']) }}"
                            class="{{ Route::is($item['route']) ? 'text-blue-600 font-semibold bg-blue-50 px-3 py-2 rounded-md' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100 px-3 py-2 rounded-md transition-all' }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Logout Mobile --}}
            <div class="p-6 border-t border-gray-100">
                <form method="POST">
                    <button type="button" @click="showLogoutModal = true"
                        class="w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-5 py-2 rounded-full shadow-lg transition">
                        <i data-feather="log-out" class="w-4 h-4"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>
    {{-- End Navbar Siswa --}}

    {{-- Content --}}
    <main class="w-full pt-10 px-4 md:px-8 lg:px-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-300 py-10 mt-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-25">
                <div class="flex items-start space-x-3 mt-15">
                    <img src="{{ asset('assets/LOGO-SMA.png') }}" alt="SIPENBK Logo" class="h-10 w-10 object-contain">
                    <span class="text-white text-lg font-semibold tracking-tight pt-1">SIPEN<span class="text-blue-400">BK</span></span>
                </div>

                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">TENTANG</h3>
                    <p class="text-sm leading-relaxed">
                        SIPENBK adalah sistem informasi bimbingan konseling untuk memudahkan pengelolaan data siswa dan kegiatan BK secara digital dan efisien.
                    </p>
                </div>

                <div>
                    <h3 class="text-white font-semibold text-lg mb-4">MENU</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('siswa.dashboard.index') }}" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="{{route('siswa.jadwal.index')}}" class="hover:text-white transition">Jadwal BK</a></li>
                        <li><a href="{{route('siswa.rekap.index')}}" class="hover:text-white transition">Laporan BK</a></li>
                        <li><a href="{{route('siswa.panduan.index')}}" class="hover:text-white transition">Panduan Sistem</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-10 pt-6 text-center">
                <p class="text-sm">&copy; {{ date('Y') }} SIPENBK HMDEV. All rights reserved.</p>
            </div>
        </div>
    </footer>
    {{-- End Footer --}}

    {{-- Modal Logout --}}
    <div @keydown.escape.window="showLogoutModal = false">
        <div class="fixed inset-0 bg-black/10 bg-opacity-50 z-50 backdrop-blur-sm" x-show="showLogoutModal" x-transition.opacity x-cloak></div>

        <div class="fixed inset-0 flex items-center justify-center z-50"
             x-show="showLogoutModal" x-transition x-cloak
             @after-enter="feather.replace()">
            <div class="bg-white rounded-xl shadow-xl w-11/12 max-w-sm p-6">
                <div class="flex items-center mb-4 gap-3">
                    <div class="p-2 bg-red-100 rounded-full">
                        <i data-feather="log-out" class="w-6 h-6 text-red-600"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Konfirmasi Logout</h2>
                </div>
                <p class="text-sm text-gray-600 mb-6">Apakah kamu yakin ingin logout dari sistem ini?</p>
                <div class="flex justify-end space-x-3">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md text-sm transition">
                            Ya, Logout
                        </button>
                    </form>
                    <button @click="showLogoutModal = false"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-sm transition">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Feather Icons & Scroll Behavior --}}
    <script>
        feather.replace();

        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.classList.add('bg-white', 'shadow-md');
                navbar.classList.remove('bg-transparent');
            } else {
                navbar.classList.remove('bg-white', 'shadow-md');
                navbar.classList.add('bg-transparent');
            }
        });
    </script>
</body>
</html>
