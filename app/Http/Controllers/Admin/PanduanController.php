<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Panduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PanduanController extends Controller
{
    public function index()
    {
        $panduan = Panduan::latest()->get();
        return view('content.admin.panduan.panduan', compact('panduan'));
    }

    public function create()
    {
        return view('content.admin.panduan.create-panduan');
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi_panduan' => 'required',
            'gambar' => 'nullable|image|max:2048',
            'role' => 'required|in:guru,siswa',
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('panduan', 'public');
        } else {
            $validated['gambar'] = null;
        }

        Panduan::create($validated);

        return redirect()->route('admin.panduan.index')->with('success', 'Panduan berhasil ditambahkan.');
    }

    public function edit(Panduan $panduan)
    {
        return view('content.admin.panduan.edit-panduan', compact('panduan'));
    }

        public function update(Request $request, Panduan $panduan)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi_panduan' => 'required',
            'gambar' => 'nullable|image|max:2048',
            'role' => 'required|in:guru,siswa',
        ]);

        // Gambar baru di-upload
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($panduan->gambar && Storage::disk('public')->exists($panduan->gambar)) {
                Storage::disk('public')->delete($panduan->gambar);
            }
            // Simpan gambar baru
            $validated['gambar'] = $request->file('gambar')->store('panduan', 'public');
        }

        $panduan->update($validated);

        return redirect()->route('admin.panduan.index')->with('success', 'Panduan berhasil diperbarui.');
    }


    public function destroy(Panduan $panduan)
    {
        if ($panduan->gambar) {
            Storage::disk('public')->delete($panduan->gambar);
        }

        $panduan->delete();

        return back()->with('success', 'Panduan berhasil dihapus.');
    }
}
