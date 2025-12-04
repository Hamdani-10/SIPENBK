<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPENBK')</title>

    {{-- Tailwind --}}
    @vite('resources/css/app.css')

    {{-- Feather Icons --}}
    <script src="https://unpkg.com/feather-icons"></script>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Custom JS --}}
    @vite('resources/js/delete-popup.js')
    @vite('resources/js/semua.js')

    {{-- Alpine.js --}}
    <script src="https://unpkg.com/alpinejs@3.13.5/dist/cdn.min.js" defer></script>
</head>
<body x-cloak x-data="{ sidebarOpen: false, showLogoutModal: false}" class="bg-gray-100 font-sans antialiased h-screen">

    {{-- Overlay Mobile --}}
    <div 
        class="fixed inset-0 bg-opacity-50 z-20 md:hidden"
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition.opacity
    ></div>

    <div class="flex h-full">

        {{-- SIDEBAR --}}
        <aside 
            class="fixed z-30 inset-y-0 left-0 w-64 bg-blue-600 text-white transform md:relative md:translate-x-0 transition-transform duration-300 ease-in-out"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="p-6 flex flex-col justify-between h-full">
                <div>
                    {{-- Logo --}}
                    <div class="flex items-center space-x-3 mb-6">
                        <img src="{{ asset('assets/LOGO-SMA.png') }}" alt="Logo" class="h-10 w-10 object-contain" />
                        <h2 class="text-2xl font-bold tracking-wide">SIPENBK</h2>
                    </div>

                    {{-- User Info (Mobile Only) --}}
                    <div class="mb-4 md:hidden flex items-center gap-3 border-t border-blue-400 pt-4">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=0D8ABC&color=fff" 
                             class="w-10 h-10 rounded-full object-cover shadow-sm">
                        <span class="text-white font-medium">{{ Auth::user()->name }}</span>
                    </div>

                    <h3 class="uppercase text-xs text-blue-200 font-semibold mb-2 tracking-wide">Menu</h3>

                    {{-- Menu List --}}
                    <nav>
                        @php
                            $menus = [
                                ['route' => 'admin.dashboard.index', 'icon' => 'home', 'label' => 'Beranda'],
                                ['route' => 'admin.jadwal.index', 'icon' => 'calendar', 'label' => 'Jadwal BK'],
                                ['route' => 'admin.rekap.index', 'icon' => 'clipboard', 'label' => 'Laporan BK'],
                                ['route' => 'admin.guru_bk.index', 'icon' => 'users', 'label' => 'Guru BK'],
                                ['route' => 'admin.siswa.index', 'icon' => 'users', 'label' => 'Siswa'],
                                ['route' => 'admin.users.index', 'icon' => 'user', 'label' => 'Pengguna'],
                                ['route' => 'admin.panduan.index', 'icon' => 'book', 'label' => 'Panduan'],
                            ];
                        @endphp

                        <ul class="space-y-2">
                            @foreach ($menus as $menu)
                                <li>
                                    <a href="{{ route($menu['route']) }}"
                                       class="flex items-center space-x-3 px-4 py-2 rounded-lg transition-all duration-200
                                       {{ request()->routeIs($menu['route']) ? 'bg-white text-blue-700 font-semibold' : 'hover:bg-blue-500' }}">
                                        <i data-feather="{{ $menu['icon'] }}" class="w-5 h-5"></i>
                                        <span>{{ $menu['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>

                {{-- Logout --}}
                <button type="button" @click="showLogoutModal = true" 
                    class="w-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition">
                    <i data-feather="log-out" class="w-4 h-4"></i>
                    <span>Logout</span>
                </button>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 flex flex-col min-h-0 overflow-hidden">

            {{-- HEADER --}}
            <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center border-b border-gray-200">
                <h1 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                    <i data-feather="@yield('page-icon', 'home')" class="w-5 h-5 text-blue-600"></i>
                    @yield('page-title')
                </h1>

                {{-- Hamburger + Avatar (Desktop Only) --}}
                <div class="flex items-center gap-3">
                    {{-- Hamburger di pojok kanan mobile --}}
                    <button 
                        class="md:hidden text-blue-600"
                        @click="sidebarOpen = !sidebarOpen"
                        aria-label="Toggle Sidebar"
                    >
                        <i data-feather="menu" class="w-6 h-6"></i>
                    </button>

                    {{-- Avatar Desktop --}}
                    <div class="hidden md:flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=0D8ABC&color=fff" 
                             class="w-10 h-10 rounded-full object-cover shadow-sm">
                        <span class="text-gray-700 font-medium text-base">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </header>

            {{-- PAGE CONTENT --}}
            <section class="flex-1 overflow-y-auto p-6 bg-gray-100">
                <div class="bg-white rounded-xl shadow p-6">
                    @yield('content')
                </div>
            </section>
        </main>
    </div>

    <!-- Modal Konfirmasi Logout -->
    <div x-cloak>
        <div x-show="showLogoutModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/10 backdrop-blur-sm" x-transition>
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-red-100 rounded-full">
                        <i data-feather="log-out" class="w-6 h-6 text-red-600"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Konfirmasi Logout</h2>
                </div>
                <p class="text-gray-600 mb-5">Apakah Anda yakin ingin logout dari sistem?</p>
                <div class="flex justify-end space-x-3">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 text-sm font-medium transition">
                            Ya, logout
                        </button>
                    </form>
                    <button @click="showLogoutModal = false"
                        class="px-4 py-2 rounded-lg text-gray-600 border border-gray-300 hover:bg-gray-100 text-sm font-medium transition">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>feather.replace();</script>
</body>
</html>
