@extends('layouts.main')

@section('title', 'Edit Laporan BK')
@section('page-title', 'Edit Laporan BK')
@section('page-icon', 'edit-3')

@section('content')
    {{-- Breadcrumb --}}
    <nav class="flex items-center text-sm text-gray-500 mb-6 space-x-2">
        <a href="{{ route('admin.rekap.index') }}" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">
            <i data-feather="clipboard" class="w-4 h-4 inline-block mr-1"></i> Laporan BK
        </a>
        <span>/</span>
        <span class="text-gray-700 font-semibold">Edit Laporan BK</span>
    </nav>

    {{-- Card --}}
    <div class="bg-white p-6 rounded-2xl">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Form Edit Laporan BK</h2>

        <form action="{{ route('admin.rekap.update', $rekap->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Jadwal, Siswa & Guru BK --}}
        <div>
            <label for="id_jadwal" class="block mb-2 text-sm font-medium text-gray-700">
                Jadwal, Siswa & Guru BK
            </label>
            <select name="id_jadwal" id="id_jadwal"
                class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none
                {{ $errors->has('id_jadwal') ? 'border-red-500' : 'border-gray-300' }}">
                <option value="">-- Pilih Jadwal, Siswa & Guru BK --</option>
                @foreach ($jadwalList as $jadwal)
                    @if($jadwal->siswa && $jadwal->guruBk)
                        <option value="{{ $jadwal->id }}" {{ old('id_jadwal', $rekap->id_jadwal) == $jadwal->id ? 'selected' : '' }}>
                            {{ $jadwal->kategori }} - {{ $jadwal->siswa->nama_siswa }} - (Guru: {{ $jadwal->guruBk->user->name ?? '-' }})
                        </option>
                    @endif
                @endforeach
            </select>
            @error('id_jadwal')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Hasil BK --}}
        <div>
            <label for="hasil_bk" class="block mb-2 text-sm font-medium text-gray-700">Hasil BK</label>
            <textarea name="hasil_bk" id="hasil_bk" rows="4"
                class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none
                {{ $errors->has('hasil_bk') ? 'border-red-500' : 'border-gray-300' }}"
                placeholder="Tuliskan hasil bimbingan konseling...">{{ old('hasil_bk', $rekap->hasil_bk) }}</textarea>
            @error('hasil_bk')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kehadiran --}}
        <div class="flex flex-col md:flex-row md:gap-6">
            <div class="flex items-center">
                <input type="hidden" name="kehadiran_siswa" value="0">
                <input type="checkbox" name="kehadiran_siswa" id="kehadiran_siswa" value="1"
                    class="mr-2 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    {{ old('kehadiran_siswa', $rekap->kehadiran_siswa) ? 'checked' : '' }}>
                <label for="kehadiran_siswa" class="text-sm text-gray-700">Kehadiran Siswa</label>
            </div>
            <div class="flex items-center mt-3 md:mt-0">
                <input type="hidden" name="kehadiran_ortu" value="0">
                <input type="checkbox" name="kehadiran_ortu" id="kehadiran_ortu" value="1"
                    class="mr-2 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    {{ old('kehadiran_ortu', $rekap->kehadiran_ortu) ? 'checked' : '' }}>
                <label for="kehadiran_ortu" class="text-sm text-gray-700">Kehadiran Orang Tua</label>
            </div>
        </div>

        {{-- Catatan Tambahan --}}
        <div>
            <label for="catatan_tambahan" class="block mb-2 text-sm font-medium text-gray-700">Catatan Tambahan</label>
            <textarea name="catatan_tambahan" id="catatan_tambahan" rows="3"
                class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none
                {{ $errors->has('catatan_tambahan') ? 'border-red-500' : 'border-gray-300' }}"
                placeholder="Isi catatan tambahan (opsional)...">{{ old('catatan_tambahan', $rekap->catatan_tambahan) }}</textarea>
            @error('catatan_tambahan')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex flex-col md:flex-row justify-between items-center pt-6 space-y-3 md:space-y-0 md:space-x-4">
            <button type="submit"
                class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold transition duration-200 w-full md:w-auto">
                <i data-feather="save" class="w-4 h-4"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.rekap.index') }}"
                class="flex items-center justify-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg text-sm font-semibold transition duration-200 w-full md:w-auto">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
        </div>
    </form>

    </div>
@endsection
