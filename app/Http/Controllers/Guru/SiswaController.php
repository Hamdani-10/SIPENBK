<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use Exception;

class SiswaController extends Controller
{
    // Menampilkan halaman siswa
    public function index( Request $request)
    {
        $query = Siswa::with('user');

        // Filter: Pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $search = $request->search;
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('no_whatsapp_siswa', 'like', "%{$search}%")
                  ->orWhere('nama_ortu', 'like', "%{$search}%")
                  ->orWhere('no_whatsapp_ortu', 'like', "%{$search}%");
            });
        }

        // Filter: Kelas
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        // Sorting
        $query->orderBy('nama_siswa', $request->sort ?? 'asc');

        // Get data kelas untuk filter dinamis
        $kelasList = Siswa::select('kelas')->distinct()->pluck('kelas');

        // Pagination
        $siswa = $query->paginate(10);
        
        return view('content.guru.siswa.siswa', compact('siswa', 'kelasList'));
    }

    // Mengambil data siswa untuk ditampilkan di form edit
    public function update(Request $request, $id_siswa)
    {
        $siswa = Siswa::findOrFail($id_siswa);

        $validated = $request->validate([
            'nisn' => 'required|string|unique:siswa,nisn,' . $siswa->id,
            'nama_siswa' => 'required|string|max:25',
            'kelas' => 'required|string|max:20',
            'no_whatsapp_siswa' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'nama_ortu' => 'required|string|max:25',
            'no_whatsapp_ortu' => ['required', 'regex:/^[0-9]{10,15}$/'],
        ]);

        try {
            $siswa->update($validated);

            return redirect()->route('guru.siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()])->withInput();
        }
    }

    // Mengambil id siswa untuk hapus
    public function destroy($id_siswa)
    {
        try {
            $siswa = Siswa::findOrFail($id_siswa);
            $siswa->delete();

            return redirect()->route('guru.siswa.index')->with('success', 'Data siswa berhasil dihapus!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }
    }
}
