<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\GuruBk;
use App\Models\User;
use Exception;

class GuruBkController extends Controller
{
    /**
     * Tampilkan semua data Guru BK.
     */
    public function index()
    {
        $guru_bk = GuruBk::with('user')->get();
        return view('content.admin.guru.guru_bk', compact('guru_bk'));
    }

    /**
     * Simpan data Guru BK baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|unique:guru_bk,user_id|exists:users,id',
            'nama_guru_bk' => 'required|string|max:25',
            'jabatan' => 'required|string|max:20',
            'no_whatsapp_guru_bk' => ['required', 'regex:/^[0-9]{10,15}$/'],
        ]);

        try {
            GuruBk::create($request->only([
                'user_id',
                'nama_guru_bk',
                'jabatan',
                'no_whatsapp_guru_bk'
            ]));

            return redirect()->route('admin.guru_bk.index')->with('success', 'Data Guru BK berhasil dilengkapi.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Tampilkan form edit Guru BK.
     */
    public function edit($id)
{
    $guruBk = GuruBk::with('user')->findOrFail($id);

    if (!$guruBk->user) {
        return redirect()->route('admin.guru_bk.index')
                         ->withErrors(['error' => 'Akun pengguna untuk guru BK ini tidak ditemukan.']);
    }

    return view('content.admin.guru.edit-gurubk', compact('guruBk'));
}


    /**
     * Update data Guru BK.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_guru_bk' => 'required|string|max:25',
            'jabatan' => 'required|string|max:20',
            'no_whatsapp_guru_bk' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $guruBk = GuruBk::findOrFail($id);

            // Cek jika ada upload foto baru
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($guruBk->foto && Storage::disk('public')->exists($guruBk->foto)) {
                    Storage::disk('public')->delete($guruBk->foto);
                }

                // Simpan foto baru
                $fotoPath = $request->file('foto')->store('foto_users', 'public');
                $guruBk->foto = $fotoPath;
            }

            // Update data lainnya
            $guruBk->nama_guru_bk = $request->nama_guru_bk;
            $guruBk->jabatan = $request->jabatan;
            $guruBk->no_whatsapp_guru_bk = $request->no_whatsapp_guru_bk;

            $guruBk->save();

            return redirect()->route('admin.guru_bk.index')->with('success', 'Data Guru BK berhasil diperbarui.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Hapus data Guru BK.
     */
    public function destroy($id)
    {
        try {
            $guruBk = GuruBk::findOrFail($id);
            $guruBk->delete();

            return redirect()->route('admin.guru_bk.index')->with('success', 'Data Guru BK berhasil dihapus.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }
    }
}
