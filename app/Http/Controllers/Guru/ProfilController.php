<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guruBk;
        return view('content.guru.profil.index', compact('guru'));
    }

        public function update(Request $request)
    {
        $guru = Auth::user()->guruBk;

        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($guru->foto && Storage::exists($guru->foto)) {
                Storage::delete($guru->foto);
            }

            $guru->foto = $request->file('foto')->store('foto_users', 'public');
            $guru->save();
        }

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }
}
