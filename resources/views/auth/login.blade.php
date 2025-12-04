<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPENBK</title>

    @vite('resources/css/app.css')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-cover bg-center flex flex-col min-h-screen" style="background-image: url('{{ asset('assets/LOGIN-BG.png') }}');">

    <div class="flex flex-col md:flex-row bg-white/5 backdrop-blur-md rounded-2xl shadow-lg overflow-hidden w-full max-w-4xl mx-auto my-auto">
        <!-- Kiri - Gambar (Desktop Saja) -->
        <div class="hidden md:block md:w-1/2">
            <img src="{{ asset('assets/BACK-LOGIN.png') }}" alt="Ilustrasi Siswa" class="object-cover h-full w-full">
        </div>

        <!-- Kanan - Form Login -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 bg-white/60 md:bg-transparent">
            <div class="w-full max-w-sm">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/LOGO-SMA.png') }}" alt="Logo SIPENBK" class="w-20 md:w-28">
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-6">SIPENBK</h2>
                @if (session('error'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
                        class="mb-4 bg-red-100 text-red-700 px-4 py-2 rounded-xl border border-red-300 shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif
                <form id="form-login" action="{{ route('auth.verify') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Input Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i data-feather="mail" class="w-5 h-5"></i>
                        </span>
                        <input type="email" name="email" placeholder="Masukkan email"
                            class="w-full pl-10 pr-4 py-2 bg-white/90 text-gray-800 rounded-xl border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            required>
                    </div>
                </div>

                <!-- Input Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i data-feather="lock" class="w-5 h-5"></i>
                        </span>
                        <input type="password" name="password" placeholder="Masukkan password"
                            class="w-full pl-10 pr-4 py-2 bg-white/90 text-gray-800 rounded-xl border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            required>
                    </div>
                </div>

                {{-- <!-- Link Lupa Password -->
                <div class="text-right -mt-2 mb-3">
                    <a href="{{route('password.request')}}" class="underline inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-800 transition-all duration-200 ease-in-out group">
                        Lupa password
                        <i data-feather="help-circle" class="w-4 h-4 text-blue-500 group-hover:text-blue-800 transition duration-200"></i>
                    </a>
                </div> --}}

                <!-- Tombol Login -->
                <button type="submit"
                    id="btn-login"
                    class="w-full py-2.5 px-4 bg-blue-600 text-white font-semibold rounded-xl flex items-center justify-center gap-2 hover:bg-blue-700 shadow-md hover:shadow-lg transition-all duration-200">
                    <i data-feather="log-in" id="icon-login" class="w-5 h-5"></i>
                    <span id="text-login">Login</span>
                </button>
            </form>

            </div>
        </div>
    </div>

    <footer class="bg-blue-600 text-white py-2 mt-4 text-sm">
        <marquee behavior="scroll" direction="left" scrollamount="5">
            Selamat datang di Sistem Informasi Penjadwalan Bimbingan Konseling (SIPENBK) SMA Negeri 1 Kwanyar —
            Sistem ini dirancang untuk memudahkan siswa dalam mengatur jadwal BK, mengakses informasi, dan berkomunikasi langsung dengan guru BK melalui WhatsApp. |
            Pastikan data kamu selalu up-to-date dan jadwal konsultasi tepat waktu. |
            SIPENBK &copy; HAMDEV2025 — Menghubungkan siswa, guru dan orang tua dengan teknologi ✨📚
        </marquee>
    </footer>    

<script>
    feather.replace()

    const form = document.getElementById('form-login');
    form.addEventListener('submit', function (e) {
        // Cek validitas form
        if (!form.checkValidity()) {
            // Kalau form tidak valid, browser akan handle
            return;
        }

        // Ganti ikon jadi spinner
        const icon = document.getElementById('icon-login');
        const text = document.getElementById('text-login');

        icon.outerHTML = `
            <svg class="w-5 h-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l5-5-5-5v4a10 10 0 100 20v-2a8 8 0 01-8-8z"></path>
            </svg>
        `;
        text.textContent = 'Loading...';
    });
</script>

</body>
</html>
