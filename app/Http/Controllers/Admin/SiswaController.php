<?php

namespace App\Http\Controllers\Admin;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function index(Request $request)
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

        return view('content.admin.siswa.siswa', compact('siswa', 'kelasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|unique:siswa,user_id|exists:users,id',
            'nisn' => 'required|string|unique:siswa,nisn',
            'nama_siswa' => 'required|string|max:25',
            'kelas' => 'required|string|max:20',
            'no_whatsapp_siswa' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'nama_ortu' => 'required|string|max:25',
            'no_whatsapp_ortu' => ['required', 'regex:/^[0-9]{10,15}$/'],
        ], [
            'user_id.required' => 'Akun siswa wajib dipilih.',
            'user_id.unique' => 'Akun siswa ini sudah digunakan.',
            'user_id.exists' => 'Akun siswa tidak ditemukan dalam sistem.',

            'nisn.required' => 'NISN wajib diisi.',
            'nisn.unique' => 'NISN sudah digunakan.',
            'nisn.string' => 'NISN harus berupa teks.',

            'nama_siswa.required' => 'Nama siswa wajib diisi.',
            'nama_siswa.string' => 'Nama siswa harus berupa teks.',
            'nama_siswa.max' => 'Nama siswa maksimal 25 karakter.',

            'kelas.required' => 'Kelas wajib diisi.',
            'kelas.string' => 'Kelas harus berupa teks.',
            'kelas.max' => 'Kelas maksimal 20 karakter.',

            'no_whatsapp_siswa.required' => 'Nomor WhatsApp siswa wajib diisi.',
            'no_whatsapp_siswa.regex' => 'Format nomor WhatsApp siswa tidak valid (hanya angka, 10–15 digit).',

            'nama_ortu.required' => 'Nama orang tua wajib diisi.',
            'nama_ortu.string' => 'Nama orang tua harus berupa teks.',
            'nama_ortu.max' => 'Nama orang tua maksimal 25 karakter.',

            'no_whatsapp_ortu.required' => 'Nomor WhatsApp orang tua wajib diisi.',
            'no_whatsapp_ortu.regex' => 'Format nomor WhatsApp orang tua tidak valid (hanya angka, 10–15 digit).',
    ]);

        try {
            Siswa::create($validated);

            return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dilengkapi.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])->withInput();
        }
    }

    public function show($id_siswa)
    {
        $siswa = Siswa::with('user')->findOrFail($id_siswa);

        if (!$siswa->user) {
            return redirect()->route('admin.siswa.index')->withErrors([
                'error' => 'Akun pengguna untuk siswa ini tidak ditemukan.'
            ]);
        }

        return view('content.admin.siswa.detail-siswa', compact('siswa'));
    }

    public function edit($id_siswa)
    {
        $siswa = Siswa::with('user')->findOrFail($id_siswa);

        if (!$siswa->user) {
            return redirect()->route('admin.siswa.index')->withErrors([
                'error' => 'Akun pengguna untuk siswa ini tidak ditemukan.'
            ]);
        }

        return view('content.admin.siswa.edit-siswa', compact('siswa'));
    }

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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.unique' => 'NISN sudah digunakan.',
            'nisn.string' => 'NISN harus berupa teks.',

            'nama_siswa.required' => 'Nama siswa wajib diisi.',
            'nama_siswa.string' => 'Nama siswa harus berupa teks.',
            'nama_siswa.max' => 'Nama siswa maksimal 25 karakter.',

            'kelas.required' => 'Kelas wajib diisi.',
            'kelas.string' => 'Kelas harus berupa teks.',
            'kelas.max' => 'Kelas maksimal 20 karakter.',

            'no_whatsapp_siswa.required' => 'Nomor WhatsApp siswa wajib diisi.',
            'no_whatsapp_siswa.regex' => 'Format nomor WhatsApp siswa tidak valid (hanya angka, 10–15 digit).',

            'nama_ortu.required' => 'Nama orang tua wajib diisi.',
            'nama_ortu.string' => 'Nama orang tua harus berupa teks.',
            'nama_ortu.max' => 'Nama orang tua maksimal 25 karakter.',

            'no_whatsapp_ortu.required' => 'Nomor WhatsApp orang tua wajib diisi.',
            'no_whatsapp_ortu.regex' => 'Format nomor WhatsApp orang tua tidak valid (hanya angka, 10–15 digit).',
        ]);

        try {
            if ($request->hasFile('foto')){
                if ($siswa->foto && Storage::disk('public')->exists($siswa->foto_users)){
                    Storage::disk('public')->delete($siswa->foto_users);
                }
            }
            $siswa->update($validated);

            return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id_siswa)
    {
        try {
            $siswa = Siswa::findOrFail($id_siswa);
            $siswa->delete();

            return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }
    }
}
