<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;
        return view('content.siswa.profil.index', compact('siswa'));
    }

        public function update(Request $request)
    {
        $siswa = Auth::user()->siswa;

        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($siswa->foto && Storage::exists($siswa->foto)) {
                Storage::delete($siswa->foto);
            }

            $siswa->foto = $request->file('foto')->store('foto_users', 'public');
            $siswa->save();
        }

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

}
