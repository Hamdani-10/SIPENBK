<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RekapHasil;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class RekapHasilController extends Controller
{
    protected $guruBk;

    public function __construct()
    {
        $this->middleware('auth:guru');

        // Middleware untuk set guruBk dari guard
        $this->middleware(function ($request, $next) {
            $this->guruBk = Auth::guard('guru')->user()->guruBk;

            if (!$this->guruBk) {
                abort(403, 'Data Guru BK tidak ditemukan. Hubungi admin.');
            }

            return $next($request);
        });
    }

        public function index(Request $request)
    {
        $query = RekapHasil::with(['jadwal.siswa', 'guruBk.user'])
            ->where('id_guru_bk', $this->guruBk->id);

        // Search by nama siswa
        if ($request->filled('search')) {
            $query->whereHas('jadwal.siswa', function ($q) use ($request) {
                $q->where('nama_siswa', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by ID Jadwal
        if ($request->filled('jadwal')) {
            $query->where('id_jadwal', $request->jadwal);
        }

        $rekap = $query->latest()->get();

        $jadwalList = Jadwal::with('siswa')
            ->where('id_guru_bk', $this->guruBk->id)
            ->get();

        return view('content.guru.rekap.rekap', [
            'rekap' => $rekap,
            'jadwalList' => $jadwalList,
            'guru' => $this->guruBk,
        ]);
    }

    public function store(Request $request)
    {
        $jadwal = Jadwal::findOrFail($request->id_jadwal);
        $kehadiranOrtuPerlu = $jadwal->kategori === 'Pemanggilan Orang Tua'; // sesuaikan dengan form

        $request->validate([
            'id_jadwal' => [
                'required',
                'exists:jadwal,id',
                function ($attribute, $value, $fail) {
                    if (RekapHasil::where('id_jadwal', $value)->exists()) {
                        $fail('Rekap untuk jadwal ini sudah ada.');
                    }
                },
            ],
            'id_guru_bk' => 'required|exists:guru_bk,id',
            'hasil_bk' => 'nullable|string',
            'kehadiran_siswa' => 'required|in:0,1',
            'kehadiran_ortu' => $kehadiranOrtuPerlu ? 'required|in:0,1' : 'nullable|in:0,1',
            'catatan_tambahan' => 'nullable|string',
        ]);

        $kehadiran_ortu_value = $kehadiranOrtuPerlu
            ? (int) $request->kehadiran_ortu
            : 0;

        RekapHasil::create([
            'id_jadwal' => $request->id_jadwal,
            'id_guru_bk' => $request->id_guru_bk,
            'hasil_bk' => $request->hasil_bk,
            'kehadiran_siswa' => (int) $request->kehadiran_siswa,
            'kehadiran_ortu' => $kehadiran_ortu_value,
            'catatan_tambahan' => $request->catatan_tambahan,
        ]);

        return redirect()->route('guru.rekap.index')->with('success', 'Data Laporan berhasil ditambahkan.');
    }



        public function update(Request $request, $id)
    {
        $rekap = RekapHasil::findOrFail($id);

        // Cek apakah rekap ini milik guru BK yang sedang login
        if ($rekap->id_guru_bk !== $this->guruBk->id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $jadwal = Jadwal::findOrFail($request->id_jadwal);

        $kehadiranOrtuPerlu = $jadwal->kategori === 'Pemanggilan Orang Tua';

        $rules = [
            'id_jadwal' => 'required|exists:jadwal,id',
            'id_guru_bk' => 'required|exists:guru_bk,id',
            'hasil_bk' => 'nullable|string',
            'kehadiran_siswa' => 'required|in:0,1',
            'catatan_tambahan' => 'nullable|string',
        ];

        if ($kehadiranOrtuPerlu) {
            $rules['kehadiran_ortu'] = 'required|in:0,1';
        }

        $request->validate($rules);

        $rekap->fill($request->only([
            'id_jadwal',
            'id_guru_bk',
            'hasil_bk',
            'kehadiran_siswa',
            'catatan_tambahan',
        ]));

        $rekap->kehadiran_ortu = $kehadiranOrtuPerlu ? $request->kehadiran_ortu : 0;

        $rekap->save();

        return redirect()->route('guru.rekap.index')->with('success', 'Data Laporan berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $rekap = RekapHasil::findOrFail($id);
        $rekap->delete();

        return redirect()->route('guru.rekap.index')->with('success', 'Data Laporan berhasil dihapus.');
    }

    // 🔥 Tambahan untuk modal edit via AJAX
    public function edit($id)
    {
        $rekap = RekapHasil::with('jadwal.siswa')->findOrFail($id);

        // Optional: cek kepemilikan guru
        if ($rekap->id_guru_bk !== $this->guruBk->id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return response()->json($rekap);
    }

    public function cetakPDF($id)
    {
        $rekap = RekapHasil::with(['jadwal.siswa', 'guruBk.user'])
            ->findOrFail($id);

        // Cek apakah rekap ini milik guru BK yang sedang login
        if ($rekap->id_guru_bk !== $this->guruBk->id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $pdf = Pdf::loadView('content.guru.rekap.rekap-pdf', [
            'rekap' => $rekap,
            'guru' => $this->guruBk,
        ])->setPaper('A4', 'portrait');

        return $pdf->download('laporan-bk' . $rekap->jadwal->siswa->nama_siswa . '.pdf');
    }
}
