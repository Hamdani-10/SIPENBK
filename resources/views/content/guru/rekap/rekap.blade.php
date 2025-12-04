@extends('layouts.main-guru')

@section('title', 'Rekap Hasil BK')

@section('content')
{{-- Section Tambah Rekap --}}
<section class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-b from-white via-blue-100 to-white py-20 px-4">
    {{-- Judul --}}
    <div class="mb-10 text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold text-blue-600 drop-shadow-sm">Laporan Bimbingan Konseling</h2>
        <p class="mt-2 text-gray-600 text-base md:text-lg">Silakan isi Laporan BK dan kehadiran siswa/orangtua</p>
    </div>

    {{-- Card --}}
    <div class="w-full max-w-5xl bg-white p-10 rounded-xl shadow-lg grid grid-cols-1 md:grid-cols-2 gap-5">

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

        {{-- Form Input --}}
        <form action="{{ route('guru.rekap.store') }}" method="POST" class="space-y-5" x-data="{ kategori: '', kehadiranOrtu: '' }" 
              @change="$event.target.name === 'id_jadwal' ? kategori = $event.target.selectedOptions[0].dataset.kategori : null">
            @csrf
            {{-- Guru BK --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Guru BK</label>
                <input type="text" value="{{ auth()->user()->name }}" readonly
                    class="w-full bg-gray-100 px-4 py-2.5 border border-gray-300 rounded-lg">
                <input type="hidden" name="id_guru_bk" value="{{ $guru->id }}">
            </div>

            {{-- Dropdown Jadwal --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Jadwal</label>
                <select name="id_jadwal" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                    <option value="">Pilih Jadwal</option>
                    @foreach($jadwalList->filter(fn($j) => !(isset($rekap) ? $rekap->pluck('id_jadwal')->contains($j->id) : false)) as $jadwal)
                        <option value="{{ $jadwal->id }}" data-kategori="{{ $jadwal->kategori }}">
                            {{ $jadwal->siswa->nama_siswa }} - {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }} ({{ $jadwal->kategori }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Kehadiran --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kehadiran Siswa</label>
                    <select name="kehadiran_siswa" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                        <option value="">Pilih Kehadiran</option>
                        <option value="1">Hadir</option>
                        <option value="0">Tidak Hadir</option>
                    </select>
                </div>

                <div x-show="kategori === 'Pemanggilan Orang Tua'" x-transition>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kehadiran Orang Tua</label>
                    <select x-model="kehadiranOrtu" :required="kategori === 'Pemanggilan Orang Tua'" name="kehadiran_ortu"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                        <option value="">Pilih Kehadiran</option>
                        <option value="1">Hadir</option>
                        <option value="0">Tidak Hadir</option>
                    </select>
                </div>
            </div>

            {{-- Hasil BK --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hasil BK</label>
                <textarea name="hasil_bk" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"
                    placeholder="Tulis hasil bimbingan..."></textarea>
            </div>

            {{-- Catatan Tambahan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                <textarea name="catatan_tambahan" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"
                    placeholder="Opsional..."></textarea>
            </div>

            {{-- Tombol --}}
            <div class="pt-1">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-md shadow-sm transition duration-200">
                    Simpan Laporan
                </button>
            </div>
        </form>

        {{-- Gambar --}}
        <div class="hidden md:block mt-10">
            <img src="{{ asset('assets/ILUS-SISWA-2.png') }}" alt="Rekap BK" class="w-full h-auto rounded-xl">
        </div>
    </div>
</section>


{{-- Section Table Rekap --}}
<section x-data="{openModal: null}" class="min-h-screen bg-gradient-to-b from-white via-blue-100 to-white py-24 px-6">
    {{-- Judul --}}
    <div class="max-w-7xl mx-auto">
        <div class="mb-10 text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold text-blue-600 drop-shadow-sm">Laporan Bimbingan Konseling</h2>
            <p class="mt-2 text-gray-600 text-base md:text-lg">Berikut adalah daftar laporan bimbingan konseling yang telah tercatat berdasarkan jadwal yang tersedia.</p>
        </div>
        {{-- Search & Filter --}}
        <div class="overflow-x-auto bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
            <form method="GET" action="{{ route('guru.rekap.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                    {{-- Search Nama Siswa --}}
                    <div>
                        <label for="search" class="block text-sm font-semibold text-gray-700 mb-1">Cari Nama Siswa</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i data-feather="search" class="w-4 h-4"></i>
                            </span>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Contoh: Budi Santoso"
                                class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none shadow-sm transition" />
                        </div>
                    </div>

                    {{-- Filter Jadwal --}}
                    <div>
                        <label for="jadwal" class="block text-sm font-semibold text-gray-700 mb-1">Filter Jadwal</label>
                        <select name="jadwal" id="jadwal"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
                            <option value="">Pilih Jadwal</option>
                            @foreach ($jadwalList as $jadwal)
                                <option value="{{ $jadwal->id }}"
                                    {{ request('jadwal') == $jadwal->id ? 'selected' : '' }}>
                                    {{ $jadwal->siswa->nama_siswa ?? '-' }} - {{ $jadwal->kategori }}
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
                        <a href="{{ route('guru.rekap.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-xl shadow transition">
                            <i data-feather="rotate-ccw" class="w-4 h-4"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>        
        <div class="overflow-x-auto bg-white shadow-md rounded-xl p-6">
            <table class="min-w-full table-auto whitespace-nowrap text-sm text-left text-gray-700">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="px-6 py-4 text-left">Siswa</th>
                        <th class="px-6 py-4 text-left">Tanggal</th>
                        <th class="px-6 py-4 text-left">Siswa</th>
                        <th class="px-6 py-4 text-left">Orang Tua</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    {{-- Data Rekap --}}
                    @forelse($rekap as $r)
                        <tr class="hover:bg-blue-50 transition duration-150">
                            {{-- Nama Siswa --}}
                            <td class="px-6 py-4 text-gray-700">{{ $r->jadwal->siswa->nama_siswa }}</td>
                            {{-- Tanggal --}}
                            <td class="px-6 py-4 text-gray-700">{{ \Carbon\Carbon::parse($r->jadwal->tanggal)->format('d M Y') }}</td>
                            {{-- Kehadiran Siswa --}}
                            <td class="px-6 py-4 text-gray-700">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $r->kehadiran_siswa ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $r->kehadiran_siswa ? 'Hadir' : 'Tidak Hadir' }}
                                </span>
                            </td>

                            {{-- Kehadiran Ortu --}}
                            <td class="px-6 py-4 text-gray-700">
                                @if ($r->jadwal->kategori === 'Pemanggilan Orang Tua')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                        {{ $r->kehadiran_ortu ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $r->kehadiran_ortu ? 'Hadir' : 'Tidak Hadir' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-500">
                                        Tidak Berlaku
                                    </span>
                                @endif
                            </td>
                            
                            {{-- Aksi --}}
                            <td class="inline-block flex-wrap justify-center gap-1 px-6 py-4 text-center space-x-1">
                                <a href="{{ route('guru.rekap.cetak-pdf', $r->id) }}"
                                target="_blank"
                                class="inline-flex items-center gap-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-full text-xs font-medium transition shadow-sm">
                                    <i data-feather="printer" class="w-4 h-4"></i>
                                    Cetak 
                                </a>
                                <button @click="openModal = 'detail-{{ $r->id }}'"class="inline-flex items-center gap-1 text-xs text-white bg-sky-500 hover:bg-sky-600 font-semibold px-3 py-2 rounded-full shadow-md transition-all hover:scale-105"> <i data-feather="eye" class="w-4 h-4"></i>Detail</button>
                                <button @click="openModal = 'edit-{{ $r->id }}'" class="inline-flex items-center gap-1 text-xs text-white bg-yellow-400 hover:bg-yellow-500 font-semibold px-4 py-2 rounded-full shadow-md transition-all hover:scale-105"> <i data-feather="edit" class="w-4 h-4"></i>Edit</button>
                                {{-- Hapus --}}
                                <x-modal-hapus id="delete-{{ $r->id }}" action="{{ route('guru.rekap.destroy', $r->id) }}" title="Hapus Laporan BK?" message="Apakah Anda yakin ingin menghapus rekap ini?" buttonText="Hapus" buttonClass="bg-red-500 hover:bg-red-600" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-gray-500">Belum ada data laporan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Modal Detail --}}
        @foreach($rekap as $r)
        <div x-show="openModal === 'detail-{{ $r->id }}'" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-white/3 backdrop-blur-sm transition-all duration-300 ease-out pointer-events-none">
            <div @click.away="openModal = null" class="bg-white w-full max-w-lg sm:mx-0 mx-4 p-6 rounded-xl shadow-xl pointer-events-auto relative z-50 overflow-y-auto max-h-[80vh]">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-blue-600">Detail Laporan BK</h2>
                    <button @click="openModal = null" class="text-gray-500 hover:text-gray-700">
                        <i data-feather="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-800 dark:text-gray-200">
                    <div class="flex flex-col">
                        <span class="text-gray-500 font-medium">Nama Siswa :</span>
                        <span class="font-semibold">{{ $r->jadwal->siswa->nama_siswa ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-gray-500 font-medium">Nama Guru BK :</span>
                        <span class="font-semibold">{{ $r->jadwal->guruBk->nama_guru_bk ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-gray-500 font-medium">Kategori :</span>
                        <span class="semibold">{{ $r->jadwal->kategori ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-gray-500 font-medium">Tanggal :</span>
                        <span class="semibold">{{ \Carbon\Carbon::parse($r->jadwal->tanggal)->format('d M Y') }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-gray-500 font-medium">Hadir Siswa :</span>
                        <span class="semibold">{{ $r->kehadiran_siswa ? 'Hadir' : 'Tidak Hadir' }}</span>
                    </div>
                    <div class="flex flex-col" 
                        @if ($r->jadwal->kategori !== 'Pemanggilan Orang Tua') x-show="false" @endif>
                        <span class="text-gray-500 font-medium">Hadir Orang Tua :</span>
                        <span class="semibold">{{ $r->kehadiran_ortu ? 'Hadir' : 'Tidak Hadir' }}</span>
                    </div>
                    <div class="flex flex-col sm:col-span-2">
                        <span class="text-gray-500 font-medium mb-1">Hasil BK :</span>
                        <div class="py-1.5 pb-2 px-3 border border-gray-300 rounded-md bg-gray-50 text-sm text-gray-800 
                                    max-h-40 min-h-[60px] overflow-y-auto whitespace-pre-line break-words leading-relaxed">
                            {{ $r->hasil_bk ?? '-' }}
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:col-span-2 mt-4">
                        <span class="text-gray-500 font-medium mb-1">Catatan Tambahan :</span>
                        <div class="py-1.5 px-3 border border-gray-300 rounded-md bg-gray-50 text-sm text-gray-800 
                                    max-h-40 min-h-[60px] overflow-y-auto whitespace-pre-line break-words leading-relaxed">
                            {{ $r->catatan_tambahan ?? '-' }}
                        </div>
                    </div>                      
                </div>

                <div class="mt-6 text-right">
                    <button @click="openModal = null"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
        
        {{-- Modal Edit Rekap BK --}}
        <div x-show="openModal === 'edit-{{ $r->id }}'" x-cloak x-data="{ kategori: '{{ $r->jadwal->kategori }}' }"x-init="feather.replace()" @keydown.escape.window="openModal = null"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm overflow-y-auto py-10">
            <div @click.away="openModal = null"
            class="p-6 rounded-2xl shadow-2xl w-full max-w-2xl mx-4 space-y-6 bg-white border border-gray-100">
            
            {{-- Header Modal --}}
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                    <i data-feather="edit-3" class="w-5 h-5 text-blue-600"></i>
                    Edit Laporan BK
                </h2>
                <button @click="openModal = null" class="text-gray-500 hover:text-red-500 transition">
                    <i data-feather="x" class="w-6 h-6"></i>
                </button>
            </div>
            
            {{-- Form --}}
            <form action="{{ route('guru.rekap.update', $r->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
            
                {{-- Baris 1: Nama Guru BK & Jadwal BK --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Guru BK</label>
                        <div class="flex items-center border rounded-lg bg-gray-100 px-3 py-2.5 shadow-sm">
                            <i data-feather="user" class="w-4 h-4 text-gray-400 mr-2"></i>
                            <input type="text" value="{{ auth()->user()->name }}" readonly
                                class="w-full bg-transparent text-gray-800 text-sm focus:outline-none">
                            <input type="hidden" name="id_guru_bk" value="{{ $guru->id }}">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jadwal Bimbingan</label>
                        <div class="flex items-center border rounded-lg bg-gray-100 px-3 py-2.5 shadow-sm">
                            <i data-feather="calendar" class="w-4 h-4 text-gray-400 mr-2"></i>
                            <input type="text"
                                value="{{ $r->jadwal->siswa->nama_siswa }} - {{ \Carbon\Carbon::parse($r->jadwal->tanggal)->format('d M Y') }} ({{ $r->jadwal->kategori }})"
                                readonly class="w-full bg-transparent text-gray-800 text-sm focus:outline-none cursor-not-allowed">
                            <input type="hidden" name="id_jadwal" value="{{ $r->id_jadwal }}">
                        </div>
                    </div>
                </div>
            
                {{-- Baris 2: Kehadiran Siswa & Orang Tua --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kehadiran Siswa</label>
                        <select name="kehadiran_siswa" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                            <option value="">Pilih Kehadiran</option>
                            <option value="1" {{ $r->kehadiran_siswa == 1 ? 'selected' : '' }}>Hadir</option>
                            <option value="0" {{ $r->kehadiran_siswa == 0 ? 'selected' : '' }}>Tidak Hadir</option>
                        </select>
                    </div>
                    @if($r->jadwal->kategori === 'Pemanggilan Orang Tua')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kehadiran Orang Tua</label>
                        <select name="kehadiran_ortu" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                            <option value="">Pilih Kehadiran</option>
                            <option value="1" {{ $r->kehadiran_ortu == 1 ? 'selected' : '' }}>Hadir</option>
                            <option value="0" {{ $r->kehadiran_ortu == 0 ? 'selected' : '' }}>Tidak Hadir</option>
                        </select>
                    </div>
                    @endif
                </div>
            
                {{-- Baris 3: Hasil BK --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hasil BK</label>
                    <textarea name="hasil_bk" rows="3"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"
                        placeholder="Tulis hasil bimbingan...">{{ old('hasil_bk', $r->hasil_bk) }}</textarea>
                </div>
            
                {{-- Baris 4: Catatan Tambahan --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan Tambahan</label>
                    <textarea name="catatan_tambahan" rows="2"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"
                        placeholder="Opsional...">{{ old('catatan_tambahan', $r->catatan_tambahan) }}</textarea>
                </div>
            
                {{-- Tombol --}}
                <div class="flex justify-end gap-3 pt-2">
                    <button type="submit"
                        class="flex items-center gap-1.5 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm font-medium transition">
                        <i data-feather="save" class="w-4 h-4"></i> Simpan
                    </button>
                    <button type="button" @click="openModal = null"
                        class="flex items-center gap-1.5 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 text-sm font-medium transition">
                        <i data-feather="x-circle" class="w-4 h-4"></i> Batal
                    </button>
                </div>
            </form>            
        </div>
    </div>
    @endforeach
</div>
</section>

@endsection
