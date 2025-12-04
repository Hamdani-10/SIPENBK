@extends('layouts.main-guru')

@section('title', 'Jadwal BK')

@section('content')

    {{-- Section Tambah Jadwal --}}
    <section class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-b from-white via-blue-100 to-white py-20 px-4">

        {{-- Judul --}}
        <div class="mb-10 text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold text-blue-600 drop-shadow-sm">Buat Jadwal BK</h2>
            <p class="mt-2 text-gray-600 text-base md:text-lg">Isi data untuk membuat jadwal bimbingan konseling bersama siswa dan orang tua siswa</p>
        </div>

        {{-- Card --}}
        <div class="w-full max-w-5xl bg-white p-15 rounded-xl shadow-lg grid grid-cols-1 md:grid-cols-2 gap-10">

            {{-- Gambar --}}
            <div class="hidden md:block">
                <img src="{{ asset('assets/GURU-JADWAL.png') }}" alt="BK Illustration" class="w-full h-auto rounded-xl">
            </div>

            {{-- Form --}}
            <form x-data="{ kategori: '', open: false, search: '', siswaList: {{ Js::from($siswa) }}, daftarOrtu: {{ Js::from($daftarOrtu) }}, id_siswa: '', 
                get selectedOrtu(){
                    if (this.kategori === 'Pemanggilan Orang Tua') {
                        const siswa = this.daftarOrtu.find(s => s.id == this.id_siswa);
                        return siswa ? `${siswa.nama_ortu} (${siswa.nama_siswa})` : '';
                    }
                    return '';
                }
            }" 
            action="{{ route('guru.jadwal.store') }}" method="POST" class="space-y-5 relative">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama Guru BK --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Nama Guru BK</label>
                        <input type="text" value="{{ $guru->nama_guru_bk }}" readonly
                            class="w-full px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-lg">
                        <input type="hidden" name="id_guru_bk" value="{{ $guru->id }}">
                    </div>

                    {{-- Nama Siswa --}}
                    <div class="relative">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Nama Siswa</label>
                        <input type="text" placeholder="Ketik nama siswa..." x-model="search"
                            @focus="open = true" @click.away="open = false"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-t-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        <div x-show="open" x-transition
                            class="absolute left-0 right-0 border border-t-0 border-gray-300 rounded-b-lg max-h-40 overflow-y-auto bg-white shadow z-10 w-full">
                            <template x-for="s in siswaList.filter(s => s.nama_siswa.toLowerCase().includes(search.toLowerCase()))" :key="s.id">
                                <div @click="id_siswa = s.id; search = s.nama_siswa; open = false"
                                    class="px-4 py-2 cursor-pointer hover:bg-blue-100 text-sm text-gray-700">
                                    <span x-text="s.nama_siswa"></span>
                                </div>
                            </template>
                            <input type="hidden" name="id_siswa" :value="id_siswa">
                        </div>
                    </div>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label for="tanggal" class="block mb-2 text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                </div>

                {{-- Waktu --}}
                <div>
                    <label for="waktu" class="block mb-2 text-sm font-medium text-gray-700">Waktu</label>
                    <select name="waktu" id="waktu"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                        <option value="" disabled selected>Pilih Waktu</option>
                        @foreach(['09:00', '10:00', '11:00', '12:00', '13:00'] as $jam)
                            <option value="{{ $jam }}">{{ $jam }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="kategori" class="block mb-2 text-sm font-medium text-gray-700">Kategori</label>
                    <select name="kategori" id="kategori" x-model="kategori"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        <option value="Bimbingan Masalah Pribadi">Bimbingan Masalah Pribadi</option>
                        <option value="Bimbingan Sosial">Bimbingan Sosial</option>
                        <option value="Bimbingan Belajar">Bimbingan Belajar</option>
                        <option value="Bimbingan Karir">Bimbingan Karir</option>
                        <option value="Pemanggilan Orang Tua">Pemanggilan Orang Tua</option>
                    </select>
                </div>

                {{-- Nama Orang Tua --}}
                <div x-data="{ kategori: $parent.kategori, daftarOrtu: {{ Js::from($daftarOrtu) }}, idSiswa: '', // asumsi siswa dipilih sebelumnya
                    get selectedOrtu() {
                        if (this.kategori === 'Pemanggilan Orang Tua') {
                            const siswa = this.daftarOrtu.find(s => s.id == this.idSiswa);
                            return siswa ? `${siswa.nama_ortu} (${siswa.nama_siswa})` : '';
                        }
                        return '';
                    }
                }" x-init="$watch('idSiswa', value => console.log('Siswa:', value))">
                    <label for="nama_ortu" class="block mb-2 text-sm font-medium text-gray-700">Nama Orang Tua</label>
                
                    <!-- Dropdown jika kategori Pemanggilan Orang Tua -->
                    <template x-if="kategori === 'Pemanggilan Orang Tua'">
                        <select name="nama_ortu" id="nama_ortu"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none bg-white" 
                            :value="selectedOrtu" readonly>
                            <option x-text="selectedOrtu"></option>
                        </select>
                    </template>

                    <!-- Input readonly jika bukan kategori Pemanggilan -->
                    <template x-if="kategori !== 'Pemanggilan Orang Tua'">
                        <input type="text" name="nama_ortu" id="nama_ortu"
                            :value="selectedOrtu"
                            readonly
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100">
                    </template>
                </div>
                
                {{-- Notifikasi --}}
                <div class="flex items-center">
                    <input type="checkbox" name="notifikasi" id="notifikasi" value="1"
                        class="mr-2 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="notifikasi" class="text-sm text-gray-700">Aktifkan Notifikasi ke Siswa / Ortu</label>
                </div>

                {{-- Tombol Simpan --}}
                <div class="pt-1">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-md shadow-sm transition duration-200">
                        Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </section>

    {{-- Modal Sukses --}}
    @if (session('success'))
    <div x-data="{ open: true }" x-show="open" x-transition class="fixed inset-0 flex items-center justify-center z-50 bg-white/40 backdrop-blur-sm">
        <div @click.away="open = false" class="bg-white rounded-xl shadow-xl p-8 max-w-md w-full text-center">
            <div class="text-green-500">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-3">Berhasil!</h3>
            <p class="text-gray-600 mb-4">{{ session('success') }}</p>
            <button @click="open = false" class="mt-3 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Tutup
            </button>
        </div>
    </div>
    @endif
    {{-- Section Table --}}
    <section x-data="{showModal: false, selectedJadwal:null }" x-init="$watch('showModal', value => { if(value) feather.replace()})" class="min-h-screen bg-gradient-to-b from-white via-blue-100 to-white py-24 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="mb-10 text-center">
                <h2 class="text-3xl md:text-4xl font-extrabold text-blue-600 drop-shadow-sm">Daftar Jadwal BK</h2>
                <p class="mt-2 text-gray-600 text-base md:text-lg">Kelola jadwal bimbingan konseling yang sudah dibuat</p>
            </div>
            {{-- Search & Filter --}}
            <div class="overflow-x-auto bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-5">
                <form method="GET" action="{{ route('guru.jadwal.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                        {{-- Search --}}
                        <div class="col-span-1">
                            <label for="search" class="block text-sm font-semibold text-gray-700 mb-1">Pencarian</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <i data-feather="search" class="w-4 h-4"></i>
                                </span>
                                <input type="text" id="search" name="search" value="{{ request('search') }}"
                                    placeholder="Cari berdasarkan nama siswa"
                                    class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none shadow-sm transition" />
                            </div>
                        </div>

                        {{-- Filter Kategori --}}
                        <div class="col-span-1">
                            <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-1">Filter Kategori</label>
                            <select name="kategori" id="kategori"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                                <option value="">Semua Kategori</option>
                                @foreach ($kategoriList as $kategori)
                                    <option value="{{ $kategori }}" {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                        {{ $kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tombol --}}
                        <div class="flex gap-2 justify-start md:justify-end mt-4 md:mt-0">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl shadow transition">
                                <i data-feather="filter" class="w-4 h-4"></i> Filter
                            </button>
                            <a href="{{ route('guru.jadwal.index') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-xl shadow transition">
                                <i data-feather="rotate-ccw" class="w-4 h-4"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            {{-- Tabel --}}
            <div class="overflow-x-auto bg-white shadow-md rounded-xl p-6">
                <table class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-700">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Nama Siswa</th>
                            <th class="px-6 py-3 font-semibold">Tanggal</th>
                            <th class="px-6 py-3 font-semibold">Waktu</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                            <th class="px-6 py-3 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($jadwal as $item)
                            <tr class="hover:bg-blue-50 transition duration-150">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->siswa->nama_siswa }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $item->formatted_tanggal }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $item->formatted_waktu }} WIB</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'tersedia' => 'bg-green-100 text-green-800',
                                            'menunggu' => 'bg-yellow-100 text-yellow-800',
                                            'selesai' => 'bg-blue-100 text-blue-800',
                                            'dibatalkan' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">

                                        {{-- Tombol Detail --}}
                                        <button 
                                            @click="selectedJadwal = {...{{ Js::from($item) }},
                                                nama_ortu: '{{ $item->siswa->nama_ortu ?? '-' }}',
                                                nama_siswa: '{{ $item->siswa->nama_siswa ?? '-' }}',
                                                no_wa_siswa: '{{ $item->siswa->no_whatsapp_siswa ?? '-' }}',
                                                no_wa_ortu: '{{ $item->siswa->no_whatsapp_ortu ?? '-' }}'
                                            }; showModal = true"
                                            class="inline-flex items-center gap-1 text-xs text-white bg-sky-500 hover:bg-sky-600 font-semibold px-3 py-1.5 rounded-full shadow-md transition-all hover:scale-105">
                                            <i data-feather="eye" class="w-4 h-4"></i> Detail
                                        </button>

                                        <!-- Modal Detail Jadwal -->
                                        <div x-cloak x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-white/3 backdrop-blur-sm transition-all duration-300 ease-out pointer-events-none">
                                            <div @click.away="showModal = false" class="bg-white w-full max-w-lg sm:mx-0 mx-4 p-6 rounded-xl shadow-xl pointer-events-auto relative z-50 overflow-y-auto max-h-[90vh]">
                                                <div class="flex justify-between items-center mb-4">
                                                    <h2 class="text-xl font-bold text-blue-600">Detail Jadwal BK</h2>
                                                    <button @click="showModal = false" class="text-gray-500 hover:text-gray-700">
                                                        <i data-feather="x" class="w-5 h-5"></i>
                                                    </button>
                                                </div>
                                                
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-800 dark:text-gray-200">
                                                    <div class="flex flex-col">
                                                        <span class="text-gray-500 font-medium">Nama Guru BK :</span>
                                                        <span class="font-semibold" x-text="selectedJadwal.guru_bk?.nama_guru_bk ?? '-'"></span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-gray-500 font-medium">Nama Siswa :</span>
                                                        <span class="font-semibold" x-text="selectedJadwal.siswa?.nama_siswa ?? '-'"></span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class=" text-gray-500 font-medium">Tanggal :</span>
                                                        <span class="semibold" x-text="dayjs(selectedJadwal.tanggal).format('DD MMMM YYYY')"></span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-gray-500 font-medium">Waktu :</span>
                                                        <span class="semibold" x-text="selectedJadwal.waktu"></span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-gray-500 font-medium">Kategori :</span>
                                                        <span class="semibold" x-text="selectedJadwal.kategori"></span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-gray-500 font-medium">Status :</span>
                                                        <span x-text="selectedJadwal.status" class="inline-block w-fit px-2 py-1 mt-1 rounded-full text-xs font-semibold":class="{'bg-green-100 text-green-700': selectedJadwal.status === 'tersedia', 'bg-red-100 text-red-700': selectedJadwal.status === 'bentrok', 'bg-blue-100 text-blue-700': selectedJadwal.status === 'selesai'}"></span>
                                                    </div>
                                                    <div class="flex flex-col" x-show="selectedJadwal.kategori === 'Pemanggilan Orang Tua'">
                                                        <span class="text-gray-500 font-medium">Nama Orang Tua :</span>
                                                        <span class="semibold" x-text="selectedJadwal.nama_ortu || '-'"></span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-gray-500 font-medium">No Wa Guru BK :</span>
                                                        <span class="semibold" x-text="selectedJadwal.guru_bk?.display_whatsapp_guru_bk ?? '-'"></span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class=" text-gray-500 font-medium">No WA Siswa :</span>
                                                        <span class="semibold" x-text="selectedJadwal.siswa?.display_whatsapp_siswa ?? '-'"></span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-gray-500 font-medium">No WA Orang Tua :</span>
                                                        <span class="semibold" x-text="selectedJadwal.siswa?.display_whatsapp_ortu ?? '-'"></span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-gray-500 font-medium">Dibuat Oleh :</span>
                                                        <span class="semibold" x-text="selectedJadwal.dibuat_oleh == 'guru' ? 'Guru BK' : (selectedJadwal.dibuat_oleh == 'siswa' ? 'Siswa' : 'Admin')"></span>
                                                    </div>
                                                </div>
                                                <div class="mt-6 text-right">
                                                    <button @click="showModal = false"
                                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                                        Tutup
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Tombol Edit --}}
                                        <div x-data="{ openEdit: false }" class="inline-block">
                                            <button @click="openEdit = true"
                                                class="inline-flex items-center gap-1 text-xs text-white bg-yellow-400 hover:bg-yellow-500 font-semibold px-4 py-2 rounded-full shadow-md transition-all hover:scale-105"
                                                title="Edit Jadwal">
                                                <i data-feather="edit" class="w-4 h-4"></i> Edit
                                            </button>

                                            {{-- Modal --}}
                                            <div x-show="openEdit" x-init="feather.replace()" x-transition.opacity x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
                                                <div @click.outside="openEdit = false" x-transition.scale.80 class="p-6 rounded-2xl shadow-2xl w-full max-w-2xl mx-4 space-y-6 bg-white border border-gray-100">

                                                    {{-- Header --}}
                                                    <div class="flex items-center justify-between">
                                                        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                                                            <i data-feather="edit-3" class="w-5 h-5 text-blue-600"></i>
                                                            Edit Jadwal BK
                                                        </h2>
                                                        <button @click="openEdit = false" class="text-gray-500 hover:text-red-500 transition">
                                                            <i data-feather="x" class="w-6 h-6"></i>
                                                        </button>
                                                    </div>

                                                    {{-- Form --}}
                                                    <form x-data="{ kategori: '{{ $item->kategori}}' }" action="{{ route('guru.jadwal.update', $item->id) }}" method="POST" class="space-y-4">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                            {{-- Nama Siswa --}}
                                                            <div>
                                                                <label class="text-sm font-semibold text-gray-700 mb-1 block">Nama Siswa</label>
                                                                <div class="flex items-center border rounded-md bg-gray-100 px-3 py-2.5 shadow-sm">
                                                                    <i data-feather="user" class="w-4 h-4 text-gray-400 mr-2"></i>
                                                                    <input type="text" readonly value="{{ $item->siswa->nama_siswa }}" class="w-full bg-transparent text-gray-800 text-sm focus:outline-none" />
                                                                </div>
                                                            </div>

                                                            {{-- Tanggal --}}
                                                            <div>
                                                                <label class="text-sm font-semibold text-gray-700 mb-1 block">Tanggal</label>
                                                                <div class="flex items-center border rounded-lg px-3 py-2.5 shadow-sm">
                                                                    <i data-feather="calendar" class="w-4 h-4 text-gray-400 mr-2"></i>
                                                                    <input type="date" name="tanggal" value="{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}"
                                                                        class="w-full bg-transparent text-gray-800 text-sm focus:outline-none" required />
                                                                </div>
                                                            </div>

                                                            {{-- Waktu --}}
                                                            <div>
                                                                <label class="text-sm font-semibold text-gray-700 mb-1 block">Waktu</label>
                                                                <div class="flex items-center border rounded-lg px-3 py-2.5 shadow-sm">
                                                                    <i data-feather="clock" class="w-4 h-4 text-gray-400 mr-2"></i>
                                                                    <select name="waktu" class="w-full bg-transparent text-gray-800 text-sm focus:outline-none" required>
                                                                        <option value="" disabled>Pilih Waktu</option>
                                                                        @foreach(['09:00', '10:00', '11:00', '12:00', '13:00'] as $jam)
                                                                            <option value="{{ $jam }}" {{ $item->waktu == $jam ? 'selected' : '' }}>{{ $jam }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            {{-- Kategori --}}
                                                            <div>
                                                                <label class="text-sm font-semibold text-gray-700 mb-1 block">Kategori</label>
                                                                <div class="flex items-center border rounded-lg px-3 py-2.5 shadow-sm">
                                                                    <i data-feather="tag" class="w-4 h-4 text-gray-400 mr-2"></i>
                                                                    <select name="kategori" x-model="kategori" class="w-full bg-transparent text-gray-800 text-sm focus:outline-none" required>
                                                                        <option value="" disabled>Pilih Kategori</option>
                                                                        @foreach(['Bimbingan Masalah Pribadi', 'Bimbingan Masalah Sosial', 'Bimbingan Masalah Belajar', 'Bimbingan Karier', 'Pemanggilan Orang Tua'] as $kategori)
                                                                            <option value="{{ $kategori }}" {{ $item->kategori == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Nama Orang Tua --}}
                                                        <div>
                                                            <label class="text-sm font-semibold text-gray-700 mb-1 block">Nama Orang Tua</label>
                                                            <div class="flex items-center border rounded-lg px-3 py-2.5 shadow-sm bg-gray-100">
                                                                <i data-feather="users" class="w-4 h-4 text-gray-400 mr-2"></i>
                                                                <input type="text" x-bind:readonly="kategori !== 'Pemanggilan Orang Tua'"
                                                                    x-bind:value="kategori === 'Pemanggilan Orang Tua' ? '{{ $item->siswa->nama_ortu }}' : ''"
                                                                    class="w-full bg-transparent text-gray-800 text-sm focus:outline-none cursor-not-allowed">
                                                            </div>
                                                        </div>

                                                        {{-- Tombol --}}
                                                        <div class="flex justify-end gap-3 pt-2">
                                                            <button @click="openEdit = false" type="button"
                                                                class="flex items-center gap-1.5 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 text-sm font-medium transition">
                                                                <i data-feather="x-circle" class="w-4 h-4"></i> Batal
                                                            </button>
                                                            <button type="submit"
                                                                class="flex items-center gap-1.5 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm font-medium transition">
                                                                <i data-feather="save" class="w-4 h-4"></i> Simpan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        {{--Tombol Hapus --}}
                                        <x-modal-hapus action="{{ route('guru.jadwal.destroy', $item->id) }}" id="{{ $item->id }}" title="Hapus Jadwal?" message="Apakah Anda yakin ingin menghapus jadwal ini?" buttonText="Hapus" buttonClass="bg-red-500 hover:bg-red-600" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-500">Belum ada jadwal yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{-- Pagination --}}
                @if($jadwal->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $jadwal->links('vendor.pagination.tailwind-modern') }}
                    </div>
                @endif
            </div>
        </div>
    </section>
    {{-- End Section table --}}

    <script>
        dayjs.locale('id');
    </script>
    
@endsection
