<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekapHasil;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapHasilController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $rekap = RekapHasil::with(['jadwal.siswa', 'guruBk.user'])
            ->when($search, function ($query, $search) {
                $query->whereHas('jadwal.siswa', function ($q) use ($search) {
                    $q->where('nama_siswa', 'like', "%$search%");
                })->orWhereHas('guruBk.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            })
            ->latest()
            ->paginate(6);

        return view('content.admin.rekap.rekap', compact('rekap'));
    }

    public function create()
    {
        $jadwalList = Jadwal::with('siswa')
            ->whereDoesntHave('rekapHasil')
            ->get();

        return view('content.admin.rekap.create-rekap', compact('jadwalList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => [
                'required',
                'exists:jadwal,id',
                Rule::unique('rekap_hasil_kehadiran', 'id_jadwal'),
            ],
            'hasil_bk' => 'required|string',
            'kehadiran_siswa' => 'required|boolean',
            'kehadiran_ortu' => 'required|boolean',
            'catatan_tambahan' => 'nullable|string'
        ], [
            'id_jadwal.required' => 'Jadwal wajib dipilih.',
            'id_jadwal.exists' => 'Jadwal yang dipilih tidak ditemukan.',
            'id_jadwal.unique' => 'Rekap untuk jadwal ini sudah pernah dibuat.',
            'hasil_bk.required' => 'Hasil bimbingan wajib diisi.',
            'hasil_bk.string' => 'Hasil bimbingan harus berupa teks.',
            'kehadiran_siswa.required' => 'Status kehadiran siswa wajib dipilih.',
            'kehadiran_siswa.boolean' => 'Format kehadiran siswa tidak valid.',
            'kehadiran_ortu.required' => 'Status kehadiran orang tua wajib dipilih.',
            'kehadiran_ortu.boolean' => 'Format kehadiran orang tua tidak valid.',
            'catatan_tambahan.string' => 'Catatan tambahan harus berupa teks.',
        ]);

        $jadwal = Jadwal::with('guruBk')->findOrFail($request->id_jadwal);

        if (!$jadwal->guruBk) {
            return back()->withErrors(['id_jadwal' => 'Guru BK tidak ditemukan pada jadwal yang dipilih.'])->withInput();
        }

        RekapHasil::create([
            'id_jadwal' => $jadwal->id,
            'id_guru_bk' => $jadwal->guruBk->id,
            'hasil_bk' => $request->hasil_bk,
            'kehadiran_siswa' => $request->kehadiran_siswa,
            'kehadiran_ortu' => $request->kehadiran_ortu,
            'catatan_tambahan' => $request->catatan_tambahan,
        ]);

        return redirect()->route('admin.rekap.index')->with('success', 'Data laporan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $rekap = RekapHasil::with(['jadwal.siswa', 'jadwal.rekapHasil'])->findOrFail($id);

        $jadwalList = Jadwal::with('siswa')
            ->whereDoesntHave('rekapHasil')
            ->orWhere('id', $rekap->id_jadwal)
            ->get();

        return view('content.admin.rekap.edit-rekap', compact('rekap', 'jadwalList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id',
            'hasil_bk' => 'required|string',
            'kehadiran_siswa' => 'required|boolean',
            'kehadiran_ortu' => 'required|boolean',
            'catatan_tambahan' => 'nullable|string'
        ], [
            'id_jadwal.required' => 'Jadwal wajib dipilih.',
            'id_jadwal.exists' => 'Jadwal yang dipilih tidak ditemukan.',
            'hasil_bk.required' => 'Hasil bimbingan wajib diisi.',
            'hasil_bk.string' => 'Hasil bimbingan harus berupa teks.',
            'kehadiran_siswa.required' => 'Status kehadiran siswa wajib dipilih.',
            'kehadiran_siswa.boolean' => 'Format kehadiran siswa tidak valid.',
            'kehadiran_ortu.required' => 'Status kehadiran orang tua wajib dipilih.',
            'kehadiran_ortu.boolean' => 'Format kehadiran orang tua tidak valid.',
            'catatan_tambahan.string' => 'Catatan tambahan harus berupa teks.',
        ]);

        $jadwal = Jadwal::with('guruBk')->findOrFail($request->id_jadwal);

        if (!$jadwal->guruBk) {
            return back()->withErrors(['id_jadwal' => 'Guru BK tidak ditemukan pada jadwal yang dipilih.'])->withInput();
        }

        $rekap = RekapHasil::findOrFail($id);
        $rekap->update([
            'id_jadwal' => $jadwal->id,
            'id_guru_bk' => $jadwal->guruBk->id,
            'hasil_bk' => $request->hasil_bk,
            'kehadiran_siswa' => $request->kehadiran_siswa,
            'kehadiran_ortu' => $request->kehadiran_ortu,
            'catatan_tambahan' => $request->catatan_tambahan,
        ]);

        return redirect()->route('admin.rekap.index')->with('success', 'Data laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $rekap = RekapHasil::findOrFail($id);
        $rekap->delete();

        return redirect()->route('admin.rekap.index')->with('success', 'Data laporan berhasil dihapus.');
    }

    public function cetakPDF($id){
        $rekap = RekapHasil::with(['jadwal.siswa', 'guruBk'])->findOrFail($id); 
        $pdf = Pdf::loadView('content.admin.rekap.rekap-pdf', compact('rekap'))
        ->setPaper('A4', 'potrait');

        return $pdf->download('laporan-bk.pdf' . $rekap->jadwal->siswa->nama_siswa . '.pdf');
    }
}
