<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\GuruBk;
use Illuminate\Support\Facades\Log;

class JadwalController extends Controller
{
    protected $siswa;

    public function __construct()
    {
        $this->middleware('auth:siswa');
        $this->middleware(function ($request, $next) {
            $this->siswa = Auth::guard('siswa')->user()->siswa;
            if (!$this->siswa) abort(403, 'Data siswa tidak ditemukan.');
            return $next($request);
        });
    }

    /**
     * Tampilkan semua jadwal untuk siswa (baik yang dibuat sendiri maupun oleh guru/admin).
     */
    public function index(Request $request)
    {
        $query = Jadwal::with(['guruBk', 'siswa'])
            ->where('id_siswa', $this->siswa->id);

        // Optional: Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('guruBk', function ($qb) use ($request) {
                    $qb->where('nama_guru_bk', 'like', '%' . $request->search . '%');
                })->orWhere('kategori', 'like', '%' . $request->search . '%');
            });
        }

        // Optional: Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Ambil semua data tanpa pagination dulu
        $jadwalAll = $query->get();

        // Update status dan urutkan: yang belum selesai dulu
        $jadwalSorted = $jadwalAll->map(function ($item) {
            $jadwalDateTime = Carbon::parse($item->tanggal)
                ->setTimeFromTimeString($item->waktu)
                ->addHour(); // misal 1 jam durasi

            if ($item->status !== 'selesai' && $jadwalDateTime->isPast()) {
                $item->status = 'selesai';
                $item->save();
            }

            return $item;
        })->sortBy(function ($item) {
            // Jadwal selesai taruh di belakang
            $tanggal = Carbon::parse($item->tanggal);
            return $item->status === 'selesai'
                ? $tanggal->addYears(100)
                : $tanggal;
        })->values();

        // Manual pagination
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage = 3;
        $pagedData = $jadwalSorted->slice(($currentPage - 1) * $perPage, $perPage);
        $jadwal = new \Illuminate\Pagination\LengthAwarePaginator($pagedData, $jadwalSorted->count(), $perPage);
        $jadwal->withPath(route('siswa.jadwal.index'))->appends($request->query());

        // Ambil list guru BK (untuk form tambah jadwal)
        $guru_bk = GuruBk::with('user')->get();

        return view('content.siswa.jadwal.jadwal', compact('jadwal', 'guru_bk'))
            ->with('siswa', $this->siswa);
    }

    /**
     * Simpan jadwal baru yang dibuat oleh siswa untuk dirinya sendiri.
     */
        public function store(Request $request)
    {
        $validated = $request->validate([
            'id_guru_bk' => 'required|exists:guru_bk,id',
            'tanggal' => 'required|date',
            'waktu' => 'required|in:09:00,10:00,11:00,12:00,13:00',
            'kategori' => 'required|string|max:50|not_in:Pemanggilan Orang Tua',
            'notifikasi' => 'nullable|boolean',
        ], [
            'kategori.not_in' => 'Kategori "Pemanggilan Orang Tua" hanya bisa dibuat oleh Guru BK.',
        ]);

        $siswa = $this->siswa;
        $guru = GuruBk::findOrFail($validated['id_guru_bk']);

        $isBentrok = Jadwal::where('tanggal', $validated['tanggal'])
            ->where('waktu', $validated['waktu'])
            ->where(function ($query) use ($siswa, $validated) {
                $query->where('id_siswa', $siswa->id)
                    ->orWhere('id_guru_bk', $validated['id_guru_bk']);
            })
            ->whereNotIn('status', ['selesai', 'batal'])  // Hanya hitung jadwal aktif
            ->exists();

        $jadwal = Jadwal::create([
            'id_siswa' => $siswa->id,
            'id_guru_bk' => $validated['id_guru_bk'],
            'nama_siswa' => $siswa->nama_siswa,
            'nama_ortu' => $siswa->nama_ortu,
            'no_whatsapp_siswa' => $siswa->no_whatsapp_siswa,
            'no_whatsapp_guru_bk' => $guru->no_whatsapp_guru_bk,
            'tanggal' => $validated['tanggal'],
            'waktu' => $validated['waktu'],
            'kategori' => $validated['kategori'],
            'status' => $isBentrok ? 'bentrok' : 'tersedia',
            'notifikasi' => $request->boolean('notifikasi'),
            'tujuan' => 'siswa',
            'dibuat_oleh' => 'siswa',
        ]);

        // Kirim notifikasi hanya jika tidak bentrok
        if ($jadwal->notifikasi && !$isBentrok) {
            $this->kirimNotifikasiFonnte($jadwal->fresh());
        }

        // 📝 Redirect dengan pesan berbeda sesuai status
        return redirect()->route('siswa.jadwal.index')->with(
            'success',
            $isBentrok
                ? 'Jadwal berhasil ditambahkan, namun terdapat bentrok dengan jadwal lain. Silakan cek kembali.'
                : 'Jadwal berhasil ditambahkan!'
        );
    }
    /**
     * Kirim notifikasi WhatsApp menggunakan Fonnte (untuk siswa saja).
     */
    private function kirimNotifikasiFonnte(Jadwal $jadwal)
    {
        $jadwal->load('guruBk');
        $token = config('services.fonnte.token');
        Log::debug('Token Fonnte:', [$token]);

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

        // Target hanya ke siswa dan guru BK
        $target = array_filter([
            $jadwal->no_whatsapp_siswa,
            $jadwal->no_whatsapp_guru_bk,
        ]);

        Log::debug('Target Notifikasi:', $target);

        foreach ($target as $nomor) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $nomor,
                    'message' => $pesan,
                ]);

                Log::debug('Fonnte response:', [$response->body()]);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim notifikasi Fonnte:', ['error' => $e->getMessage()]);
            }
        }
    }

}
