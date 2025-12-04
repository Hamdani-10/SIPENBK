@extends('layouts.main-siswa')

@section('title', 'Rekap Hasil BK Siswa')

@section('content')
<section x-data="{ openModal: false, hasil: '', catatan: '' }" class="min-h-screen bg-gradient-to-b from-white via-blue-50 to-white py-20 px-4">
    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-blue-600 drop-shadow-sm">Rekap Hasil Bimbingan Konseling</h2>
        <p class="mt-2 text-gray-600 text-lg max-w-2xl mx-auto">
            Lihat kembali hasil sesi BK yang telah kamu jalani bersama Guru BK.
        </p>
    </div>

    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
    @forelse($rekap as $item)
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
                        {{ \Carbon\Carbon::parse($item->jadwal->tanggal ?? '')->format('d M Y') }}
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="bg-blue-100 p-2 rounded-full">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        {{ $item->jadwal->formatted_waktu ?? '-' }} WIB
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
                        <span class="font-semibold">{{ $item->jadwal->tujuan ?? '-' }}</span>
                    </div>
                    <div class="flex gap-1">
                        <span class="text-gray-500 min-w-[140px]">👨‍🏫 Nama Guru BK:</span>
                        <span class="font-semibold">{{ $item->jadwal->guruBk->nama_guru_bk ?? '-' }}</span>
                    </div>
                    <div class="flex gap-1">
                        <span class="text-gray-500 min-w-[140px]">🏷️ Kategori:</span>
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">
                            {{ $item->jadwal->kategori ?? '-' }}
                        </span>
                    </div>
                    <div class="flex gap-1">
                        <span class="text-gray-500 min-w-[140px]">📌 Kehadiran Siswa:</span>
                        <span class="font-semibold {{ $item->kehadiran_siswa ? 'text-green-600' : 'text-red-600' }}">
                            {{ $item->kehadiran_siswa ? 'Hadir' : 'Tidak Hadir' }}
                        </span>
                    </div>
                    @if(strtolower($item->jadwal->kategori ?? '') === 'pemanggilan orang tua')
                        <div class="flex gap-1">
                            <span class="text-gray-500 min-w-[140px]">👨‍👩‍👧 Kehadiran Ortu:</span>
                            <span class="font-semibold {{ $item->kehadiran_ortu ? 'text-green-600' : 'text-red-600' }}">
                                {{ $item->kehadiran_ortu ? 'Hadir' : 'Tidak Hadir' }}
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Tombol --}}
                <div class="pt-4">
                    <button 
                        @click="openModal = true; hasil = `{!! addslashes($item->hasil_bk ?? '-') !!}`; catatan = `{!! addslashes($item->catatan_tambahan ?? '-') !!}`;"
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
            <p>Belum ada rekap hasil bimbingan konseling untuk kamu saat ini.</p>
        </div>
    @endforelse
    </div>

    {{-- Pagination --}}
    @if($rekap->hasPages())
    <div class="mt-12 flex justify-center">
        {{ $rekap->links('vendor.pagination.tailwind-modern') }}
    </div>
    @endif

    {{-- Modal --}}
    <div
        x-show="openModal"
        x-transition.opacity
        class="fixed inset-0 bg-black/40 z-40"
        @click="openModal = false"
    ></div>

    <div 
        x-show="openModal"
        x-transition
        class="fixed z-50 inset-0 flex items-center justify-center p-4"
    >
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative" @click.stop>
            <button @click="openModal = false"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition text-xl font-bold">
                &times;
            </button>
            <h3 class="text-2xl font-bold text-blue-600 mb-4">Detail Hasil BK</h3>

            <div class="space-y-4 text-sm text-gray-700">
                <div>
                    <p class="font-semibold mb-1">Hasil Bimbingan Konseling:</p>
                    <p x-text="hasil" class="bg-gray-50 p-3 rounded-md border border-gray-200 whitespace-pre-line"></p>
                </div>
                <div>
                    <p class="font-semibold mb-1">Catatan Tambahan:</p>
                    <p x-text="catatan" class="bg-gray-50 p-3 rounded-md border border-gray-200 whitespace-pre-line"></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
