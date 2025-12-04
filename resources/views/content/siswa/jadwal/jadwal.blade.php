@extends('layouts.main-siswa')

@section('title', 'Jadwal Bimbingan Konseling Siswa')

@section('content')
{{-- Section Tambah Jadwal --}}
<section class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-b from-white via-blue-100 to-white py-20 px-4">
    {{-- Judul --}}
    <div class="text-center mb-10">
        <h2 class="text-3xl md:text-4xl font-extrabold text-blue-600 drop-shadow-sm">Buat Jadwal BK</h2>
        <p class="mt-2 text-gray-600 text-base md:text-lg">
            Isi data untuk membuat jadwal bimbingan konseling bersama Guru BK. <br>
            <span class="mt-2 text-gray-600 text-base md:text-lg">Jam konsultasi bimbingan konseling: 09.00 – 13.00</span>
        </p>
    </div>
   {{-- Card --}}
    <div class="w-full max-w-5xl bg-white p-15 rounded-xl shadow-lg grid grid-cols-1 md:grid-cols-2 gap-10">
        {{-- Gambar --}}
        <div class="hidden md:block">
            <img src="{{ asset('assets/HOME-1.png') }}" alt="Jadwal Illustration" class="w-full h-auto rounded-xl">
        </div>

        {{-- Form --}}
        <form action="{{ route('siswa.jadwal.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama Siswa --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Nama Siswa</label>
                    <input type="text" value="{{$siswa->nama_siswa}}" readonly
                        class="w-full px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-lg">
                    <input type="hidden" name="id_siswa" value="{{ auth()->user()->id }}">
                </div>

                {{-- Nama Guru BK --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Nama Guru BK</label>
                    <select name="id_guru_bk" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        <option value="" disabled selected>Pilih Guru BK</option>
                        @foreach($guru_bk as $guru)
                            <option value="{{ $guru->id }}">{{ $guru->nama_guru_bk }}</option>
                        @endforeach
                    </select>
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

            {{-- Kategori (tanpa "Pemanggilan Orang Tua") --}}
            <div>
                <label for="kategori" class="block mb-2 text-sm font-medium text-gray-700">Kategori</label>
                <select name="kategori" id="kategori"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    <option value="Bimbingan Masalah Pribadi">Bimbingan Masalah Pribadi</option>
                    <option value="Bimbingan Sosial">Bimbingan Sosial</option>
                    <option value="Bimbingan Belajar">Bimbingan Belajar</option>
                    <option value="Bimbingan Karir">Bimbingan Karir</option>
                </select>
            </div>

            {{-- Notifikasi --}}
            <div class="flex items-center">
                <input type="checkbox" name="notifikasi" id="notifikasi" value="1"
                    class="mr-2 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="notifikasi" class="text-sm text-gray-700">Aktifkan Notifikasi ke Saya dan Guru BK</label>
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
</section>
{{-- End Tambah Jadwal --}}

{{--Section Lihat Jadwal Siswa --}}
<section
    x-data="{ showModal: false, selectedJadwal: {} }"
    class="min-h-screen bg-gradient-to-b from-white via-blue-50 to-white py-20 px-4"
>
    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-blue-600 drop-shadow-sm">Jadwal Bimbingan Konseling</h2>
        <p class="mt-2 text-gray-600 text-lg max-w-2xl mx-auto">
            Lihat kembali jadwal bimbingan konseling yang telah kamu buat.
        </p>
    </div>
    {{-- Card --}}
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
        @forelse($jadwal as $item)
            <div class="bg-gradient-to-br from-white via-blue-50 to-white rounded-2xl border border-blue-100 shadow-md hover:shadow-xl hover:ring-1 hover:ring-blue-300 transition-all duration-300">
                <div class="p-6 space-y-5">

                    {{-- Atas: Tanggal dan Waktu --}}
                    <div class="flex justify-between items-center text-sm font-medium text-blue-700">
                        <div class="flex items-center gap-2">
                            <div class="bg-blue-100 p-2 rounded-full">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 7V3M16 7V3M3 11h18M5 19h14a2 2 0 002-2V7H3v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="bg-blue-100 p-2 rounded-full">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            {{ $item->formatted_waktu ?? '-' }} WIB
                        </div>
                    </div>

                    {{-- Detail --}}
                    <div class="text-sm text-gray-700 space-y-3 pt-1">
                        <div class="flex gap-1">
                            <span class="text-gray-500 min-w-[140px]">👤 Nama Siswa:</span>
                            <span class="font-semibold">{{ $item->siswa->nama_siswa ?? '-' }}</span>
                        </div>
                        <div class="flex gap-1">
                            <span class="text-gray-500 min-w-[140px]">🎯 Tujuan:</span>
                            <span class="font-semibold">{{ $item->tujuan ?? '-' }}</span>
                        </div>
                        <div class="flex gap-1">
                            <span class="text-gray-500 min-w-[140px]">👨‍🏫 Nama Guru BK:</span>
                            <span class="font-semibold">{{ $item->guruBk->nama_guru_bk ?? '-' }}</span>
                        </div>
                        <div class="flex gap-1">
                            <span class="text-gray-500 min-w-[140px]">🏷️ Kategori:</span>
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">
                                {{ $item->kategori ?? '-' }}
                            </span>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="pt-4">
                        <button 
                            @click="selectedJadwal = {...{{ Js::from($item) }},
                                nama_ortu: '{{ $item->siswa->nama_ortu ?? '-' }}',
                                nama_siswa: '{{ $item->siswa->nama_siswa ?? '-' }}',
                                no_wa_siswa: '{{ $item->siswa->no_whatsapp_siswa ?? '-' }}',
                                no_wa_ortu: '{{ $item->siswa->no_whatsapp_ortu ?? '-' }}'
                            }; showModal = true"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-600 rounded-lg transition-all"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Lihat Detail
                        </button>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500 text-lg">
                <p>Belum ada jadwal bimbingan konseling untuk kamu saat ini.</p>
            </div>
        @endforelse
    </div>
    {{-- Pagination --}}
    @if($jadwal->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $jadwal->links('vendor.pagination.tailwind-modern') }}
        </div>
    @endif

    {{-- Modal Detail Jadwal (Responsive) --}}
    <div x-cloak x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-white/30 backdrop-blur-sm transition-all duration-300 ease-out">
        <div @click.away="showModal = false" class="bg-white w-full max-w-2xl mx-4 sm:mx-auto p-6 sm:p-8 rounded-2xl shadow-xl relative z-50 overflow-y-auto max-h-[90vh]">
            {{-- Header --}}
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg sm:text-xl font-bold text-blue-600">Detail Jadwal BK</h2>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <i data-feather="x" class="w-5 h-5"></i>
                </button>
            </div>

            {{-- Konten --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm sm:text-[15px] text-gray-800">
                <div>
                    <span class="text-gray-500 font-medium">Nama Guru BK:</span>
                    <div class="font-semibold" x-text="selectedJadwal.guru_bk?.nama_guru_bk ?? '-'"></div>
                </div>
                <div>
                    <span class="text-gray-500 font-medium">Nama Siswa:</span>
                    <div class="font-semibold" x-text="selectedJadwal.siswa?.nama_siswa ?? '-'"></div>
                </div>
                <div>
                    <span class="text-gray-500 font-medium">Tanggal:</span>
                    <div class="font-semibold" x-text="dayjs(selectedJadwal.tanggal).format('DD MMMM YYYY')"></div>
                </div>
                <div>
                    <span class="text-gray-500 font-medium">Waktu:</span>
                    <div class="font-semibold" x-text="selectedJadwal.waktu"></div>
                </div>
                <div>
                    <span class="text-gray-500 font-medium">Kategori:</span>
                    <div class="font-semibold" x-text="selectedJadwal.kategori"></div>
                </div>
                <div>
                    <span class="text-gray-500 font-medium">Status:</span>
                    <div class="inline-block w-fit px-2 py-1 mt-1 rounded-full text-xs font-semibold"
                        x-text="selectedJadwal.status"
                        :class="{
                            'bg-green-100 text-green-700': selectedJadwal.status === 'tersedia',
                            'bg-red-100 text-red-700': selectedJadwal.status === 'bentrok',
                            'bg-blue-100 text-blue-700': selectedJadwal.status === 'selesai'
                        }"></div>
                </div>
                <template x-if="selectedJadwal.kategori === 'Pemanggilan Orang Tua'">
                    <div>
                        <span class="text-gray-500 font-medium">Nama Orang Tua:</span>
                        <div class="font-semibold" x-text="selectedJadwal.nama_ortu ?? '-'"></div>
                    </div>
                </template>
                <div>
                    <span class="text-gray-500 font-medium">No WA Guru BK:</span>
                    <div class="font-semibold" x-text="selectedJadwal.guru_bk?.display_whatsapp_guru_bk ?? '-'"></div>
                </div>
                <div>
                    <span class="text-gray-500 font-medium">No WA Siswa:</span>
                    <div class="font-semibold" x-text="selectedJadwal.siswa?.display_whatsapp_siswa ?? '-'"></div>
                </div>
                <div>
                    <span class="text-gray-500 font-medium">No WA Orang Tua:</span>
                    <div class="font-semibold" x-text="selectedJadwal.siswa?.display_whatsapp_ortu ?? '-'"></div>
                </div>
                <div>
                    <span class="text-gray-500 font-medium">Dibuat Oleh:</span>
                    <div class="font-semibold" x-text="selectedJadwal.dibuat_oleh == 'guru' ? 'Guru BK' : (selectedJadwal.dibuat_oleh == 'siswa' ? 'Siswa' : 'Admin')"></div>
                </div>
            </div>

            {{-- Tombol Tutup --}}
            <div class="mt-6 text-right">
                <button @click="showModal = false" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</section>
{{-- End Jadwal Siswa --}}
@endsection