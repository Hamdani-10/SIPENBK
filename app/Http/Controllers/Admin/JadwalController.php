<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\GuruBk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JadwalController extends Controller
{
    // Menampilkan daftar seluruh jadwal
    public function index(Request $request)
    {
        // Ubah status otomatis jika waktu sudah lewat > 1 jam
        Jadwal::where('status', '!=', 'selesai')
            ->where(function ($query) {
                $query->where('tanggal', '<', now()->toDateString())
                    ->orWhere(function ($query) {
                        $query->where('tanggal', now()->toDateString())
                            ->where('waktu', '<', now()->subHour()->format('H:i'));
                    });
            })
            ->update(['status' => 'selesai']);

        $jadwals = Jadwal::with(['siswa', 'guruBk'])
            ->when($request->search, function ($query) use ($request) {
                $query->where('nama_siswa', 'like', '%' . $request->search . '%');
            })
            ->when($request->kategori, function ($query) use ($request) {
                $query->where('kategori', $request->kategori);
            })
            ->when($request->guru, function ($query) use ($request) {
                $query->whereHas('guruBk', function ($q) use ($request) {
                    $q->where('nama_guru_bk', 'like', '%' . $request->guru . '%');
                });
            })
            ->when($request->tanggal_mulai && $request->tanggal_selesai, function ($query) use ($request) {
                $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
            })
            // Tampilkan yang paling dekat dulu (hari ini & masa depan), baru yang lewat
            ->orderByRaw("
                CASE 
                    WHEN tanggal > CURDATE() THEN 1
                    WHEN tanggal = CURDATE() AND waktu >= CURTIME() THEN 1
                    ELSE 2
                END, tanggal ASC, waktu ASC
            ")
            ->paginate(10)
            ->withQueryString();

        return view('content.admin.jadwal.jadwal', compact('jadwals'));
    }


    // Menampilkan detail jadwal
    public function show($id)
    {
        $jadwal = Jadwal::with('guruBk')->findOrFail($id);

        return view('content.admin.jadwal.detail-jadwal', compact('jadwal'));
    }

    // Menampilkan form create
    public function create()
    {
        $siswa = Siswa::with('user')->get();
        $guruBk = GuruBk::with('user')->get();

        // Generate pilihan jam dari 09:00 sampai 13:00
        $waktu = [];
        for ($i = 9; $i <= 13; $i++) {
            $waktu[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
        }

        return view('content.admin.jadwal.create-jadwal', compact('siswa', 'guruBk', 'waktu'));
    }

    // Menyimpan data jadwal baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_guru_bk' => 'required|exists:guru_bk,id',
            'id_siswa' => 'required|exists:siswa,id',
            'tanggal' => 'required|date',
            'waktu' => 'required|in:09:00,10:00,11:00,12:00,13:00',
            'kategori' => 'required|string|max:50',
            'nama_ortu' => 'nullable|string|max:100',
            'notifikasi' => 'nullable|boolean',
        ]);

        $guruBk = GuruBk::findOrFail($validated['id_guru_bk']);
        $siswa = Siswa::findOrFail($validated['id_siswa']);

        // CEK BENTROK sesuai logic terbaru: hanya jika tanggal + waktu + guru sama
        $isBentrok = Jadwal::where('tanggal', $validated['tanggal'])
            ->where('waktu', $validated['waktu'])
            ->where('id_guru_bk', $validated['id_guru_bk'])
            ->exists();

        // Buat jadwal
        $jadwal = Jadwal::create([
            'id_guru_bk' => $guruBk->id,
            'id_siswa' => $siswa->id,
            'nama_siswa' => $siswa->nama_siswa,
            'no_whatsapp_siswa' => $siswa->no_whatsapp_siswa,
            'no_whatsapp_guru_bk' => $guruBk->no_whatsapp_guru_bk,
            'tanggal' => $validated['tanggal'],
            'waktu' => $validated['waktu'],
            'kategori' => $validated['kategori'],
            'status' => $isBentrok ? 'bentrok' : 'tersedia',
            'notifikasi' => $request->boolean('notifikasi'),
            'tujuan' => $validated['kategori'] === 'Pemanggilan Orang Tua' ? 'orangtua' : 'siswa',
            'nama_ortu' => $validated['kategori'] === 'Pemanggilan Orang Tua' ? ($validated['nama_ortu'] ?? $siswa->nama_ortu) : null,
            'no_whatsapp_ortu' => $validated['kategori'] === 'Pemanggilan Orang Tua' ? $siswa->no_whatsapp_ortu : null,
            'dibuat_oleh' => 'admin',
        ]);

        // Kirim notifikasi jika perlu dan TIDAK bentrok
        if ($jadwal->notifikasi && !$isBentrok) {
            $this->kirimNotifikasiFonnte($jadwal->fresh());
        }

        return redirect()->route('admin.jadwal.index')->with(
            'success',
            $isBentrok
                ? 'Jadwal berhasil ditambahkan, namun terdapat bentrok pada guru yang sama.'
                : 'Jadwal berhasil ditambahkan!'
        );
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $siswa = Siswa::with('user')->get();
        $guruBk = GuruBk::with('user')->get();

        // Generate pilihan jam dari 09:00 sampai 13:00
        $waktu = [];
        for ($i = 9; $i <= 13; $i++) {
            $waktu[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
        }

        return view('content.admin.jadwal.edit-jadwal', compact('jadwal', 'siswa', 'guruBk', 'waktu'));
    }

    // Update data jadwal
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_guru_bk' => 'required|exists:guru_bk,id',
            'id_siswa' => 'required|exists:siswa,id',
            'tanggal' => 'required|date',
            'waktu' => 'required|in:09:00,10:00,11:00,12:00,13:00',
            'kategori' => 'required|string|max:50',
            'nama_ortu' => 'nullable|string|max:100',
            'notifikasi' => 'nullable|boolean',
        ]);

        $jadwal = Jadwal::findOrFail($id);

        $guruBk = GuruBk::findOrFail($validated['id_guru_bk']);
        $siswa = Siswa::findOrFail($validated['id_siswa']);

        // Cek bentrok: selain diri sendiri
        $isBentrok = Jadwal::where('id', '!=', $jadwal->id)
            ->where('tanggal', $validated['tanggal'])
            ->where('waktu', $validated['waktu'])
            ->where('id_guru_bk', $validated['id_guru_bk'])
            ->exists();

        $jadwal->update([
            'id_guru_bk' => $guruBk->id,
            'id_siswa' => $siswa->id,
            'nama_siswa' => $siswa->nama_siswa,
            'no_whatsapp_siswa' => $siswa->no_whatsapp_siswa,
            'no_whatsapp_guru_bk' => $guruBk->no_whatsapp_guru_bk,
            'tanggal' => $validated['tanggal'],
            'waktu' => $validated['waktu'],
            'kategori' => $validated['kategori'],
            'nama_ortu' => $validated['nama_ortu'],
            'status' => $isBentrok ? 'bentrok' : 'tersedia',
            'notifikasi' => $request->has('notifikasi'),
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    // Menghapus jadwal
    public function destroy($id)
    {
        Jadwal::findOrFail($id)->delete();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }

    // Kirim notifikasi WhatsApp via Fonnte
    private function kirimNotifikasiFonnte(Jadwal $jadwal)
    {
        $jadwal->load('guruBk');
        $token = config('services.fonnte.token');

        $pesan = "*Assalamu'alaikum Wr. Wb.*\n\n"
            . "Kami dari *BK SMAN 1 Kwanyar* ingin menginformasikan bahwa Anda telah dijadwalkan untuk mengikuti kegiatan *Bimbingan Konseling* dengan rincian sebagai berikut:\n\n"
            . "📌 *Nama Siswa:* {$jadwal->nama_siswa}\n"
            . "📅 *Tanggal:* {$jadwal->formatted_tanggal}\n"
            . "⏰ *Waktu:* {$jadwal->formatted_waktu}\n"
            . "🗂️ *Kategori:* {$jadwal->kategori}\n\n"
            . "Mohon untuk hadir tepat waktu sesuai jadwal yang telah ditentukan.\n\n"
            . "Untuk informasi lebih lanjut, silakan menghubungi Guru BK yang bersangkutan:\n"
            . "👤 *{$jadwal->guruBk->nama_guru_bk}* (WA: {$jadwal->no_whatsapp_guru_bk})\n\n"
            . "*Atas perhatiannya kami ucapkan terima kasih.*\n\n"
            . "*Salam hormat,*\n"
            . "*BK SMAN 1 Kwanyar*";

        $target = array_filter([
            $jadwal->no_whatsapp_guru_bk,
            $jadwal->no_whatsapp_siswa,
            $jadwal->tujuan === 'orangtua' ? $jadwal->no_whatsapp_ortu : null,
        ]);

        foreach ($target as $nomor) {
            try {
                Http::withHeaders([
                    'Authorization' => $token,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $nomor,
                    'message' => $pesan,
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal kirim WA Fonnte:', ['error' => $e->getMessage()]);
            }
        }
    }

}
