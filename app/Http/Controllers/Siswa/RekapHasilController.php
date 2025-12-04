<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\RekapHasil;

class RekapHasilController extends Controller
{
    protected $siswa;

    public function __construct()
    {
        $this->middleware('auth:siswa');

        $this->middleware(function ($request, $next) {
            $this->siswa = Auth::guard('siswa')->user();
            if (!$this->siswa) {
                abort(403, 'Data siswa tidak ditemukan.');
            }
            return $next($request);
        });
    }

        public function index()
    {
        $user = Auth::user(); // karena guard-nya default dari users
        if ($user->role !== 'siswa') {
            abort(403, 'Hanya siswa yang bisa mengakses rekap.');
        }

        $siswa = $user->siswa; // Ambil relasi dari user ke siswa

        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan untuk user ini.');
        }

        $rekap = RekapHasil::whereHas('jadwal', function ($query) use ($siswa) {
            $query->where('id_siswa', $siswa->id); // Cocokkan dengan siswa yang sedang login
        })->with(['jadwal.guruBk'])->latest()->paginate(3);

        return view('content.siswa.rekap.rekap', [
            'rekap' => $rekap,
            'siswa' => $siswa,
        ]);
    }
}

