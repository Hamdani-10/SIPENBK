<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;


class JadwalController extends Controller
{
    protected $guruBk;

    public function __construct()
    {
        $this->middleware('auth:guru');
        $this->middleware(function ($request, $next) {
            $this->guruBk = Auth::guard('guru')->user()->guruBk;
            if (!$this->guruBk) {
                abort(403, 'Data Guru BK tidak ditemukan. Hubungi admin.');
            }
            return $next($request);
        });
    }

    /**
     * Menampilkan daftar jadwal dan data siswa.
     */
       public function index(Request $request)
    {
        $query = Jadwal::with(['guruBk', 'siswa'])
            ->where('id_guru_bk', $this->guruBk->id);

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('siswa', function ($qs) use ($request) {
                    $qs->where('nama_siswa', 'like', '%' . $request->search . '%');
                })->orWhere('kategori', 'like', '%' . $request->search . '%');
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Ambil semua data (sementara tanpa paginate dulu)
        $jadwalAll = $query->get();

        // Update status + custom sort: yang belum selesai dulu
        $jadwalSorted = $jadwalAll->map(function ($item) {
            $jadwalDateTime = Carbon::parse($item->tanggal)
                ->setTimeFromTimeString($item->waktu)
                ->addHour();

            if ($item->status !== 'selesai' && $jadwalDateTime->isPast()) {
                $item->status = 'selesai';
                $item->save();
            }

            return $item;
        })->sortBy(function ($item) {
            // Kalau sudah selesai, taruh di belakang (plus 100 tahun)
            $tanggal = Carbon::parse($item->tanggal);
            return $item->status === 'selesai'
                ? $tanggal->addYears(100)
                : $tanggal;
        })->values();

        // Manual paginate hasil collection
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 7;
        $pagedData = $jadwalSorted->slice(($currentPage - 1) * $perPage, $perPage);
        $jadwal = new LengthAwarePaginator($pagedData, $jadwalSorted->count(), $perPage);
        $jadwal->withPath(route('guru.jadwal.index'))->appends($request->query());

        // Siswa dan ortu
        $siswa = Siswa::with('user')->get();
        $daftarOrtu = Siswa::select('id', 'nama_siswa', 'nama_ortu')
            ->whereNotNull('nama_ortu')
            ->get()
            ->map(function ($siswa) {
                return [
                    'id' => $siswa->id,
                    'nama_siswa' => $siswa->nama_siswa,
                    'nama_ortu' => $siswa->nama_ortu,
                ];
            });

        // List kategori unik
        $kategoriList = Jadwal::where('id_guru_bk', $this->guruBk->id)
            ->pluck('kategori')
            ->unique()
            ->sort()
            ->values();

        return view('content.guru.jadwal.jadwal', compact('jadwal', 'siswa', 'daftarOrtu', 'kategoriList'))
            ->with('guru', $this->guruBk);
    }

    /**
     * Menyimpan jadwal baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswa,id',
            'tanggal' => 'required|date',
            'waktu' => 'required|in:09:00,10:00,11:00,12:00,13:00',
            'kategori' => 'required|string|max:50',
            'nama_ortu' => 'nullable|string|max:100',
            'notifikasi' => 'nullable|boolean',
        ]);

        $siswa = Siswa::findOrFail($validated['id_siswa']);

        $isBentrok = Jadwal::where('tanggal', $validated['tanggal'])
            ->where('waktu', $validated['waktu'])
            ->where(function ($query) use ($validated) {
                $query->where('id_siswa', $validated['id_siswa'])
                    ->orWhere('id_guru_bk', Auth::guard('guru')->user()->guruBk->id);
            })
            ->exists();
            
        $jadwal = Jadwal::create([
            'id_guru_bk' => $this->guruBk->id,
            'id_siswa' => $siswa->id,
            'nama_siswa' => $siswa->nama_siswa,
            'no_whatsapp_siswa' => $siswa->no_whatsapp_siswa,
            'no_whatsapp_guru_bk' => $this->guruBk->no_whatsapp_guru_bk,
            'tanggal' => $validated['tanggal'],
            'waktu' => $validated['waktu'],
            'kategori' => $validated['kategori'],
            'status' => $isBentrok ? 'bentrok' : 'tersedia',
            'notifikasi' => $request->boolean('notifikasi'),
            'tujuan' => $validated['kategori'] === 'Pemanggilan Orang Tua' ? 'orangtua' : 'siswa',
            'nama_ortu' => $validated['kategori'] === 'Pemanggilan Orang Tua' ? ($validated['nama_ortu'] ?? $siswa->nama_ortu) : null,
            'no_whatsapp_ortu' => $validated['kategori'] === 'Pemanggilan Orang Tua' ? $siswa->no_whatsapp_ortu : null,
            'dibuat_oleh' => 'guru',
        ]);

        // Tambahan: Cek jika tidak bentrok baru kirim WA
        if ($jadwal->notifikasi && !$isBentrok) {
            $this->kirimNotifikasiFonnte($jadwal->fresh());
        }

        return redirect()->route('guru.jadwal.index')->with(
            'success',
            $isBentrok
                ? 'Jadwal berhasil ditambahkan, namun terdapat bentrok. Silakan periksa kembali.'
                : 'Jadwal berhasil ditambahkan!'
        );
    }
    /**
     * Memperbarui jadwal yang ada.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'waktu' => 'required|in:09:00,10:00,11:00,12:00,13:00',
            'kategori' => 'required|string|max:50',
            'nama_ortu' => 'nullable|string|max:100',
            'notifikasi' => 'nullable|boolean',
        ]);

        $jadwal = Jadwal::where('id_guru_bk', $this->guruBk->id)->findOrFail($id);
        $siswa = $jadwal->siswa;

        $isBentrok = Jadwal::where('tanggal', $validated['tanggal'])
            ->where('waktu', $validated['waktu'])
            ->where('id', '!=', $jadwal->id)
            ->where(function ($query) use ($jadwal) {
                $query->where('id_siswa', $jadwal->id_siswa)
                    ->orWhere('id_guru_bk', $jadwal->id_guru_bk);
            })
            ->exists();

        // Tentukan status berdasarkan bentrok dan waktu
        $status = $isBentrok ? 'bentrok' : (
            Carbon::parse("{$validated['tanggal']} {$validated['waktu']}")->addHour()->isPast()
                ? 'selesai' : 'tersedia'
        );

        $jadwal->update([
            'tanggal' => $validated['tanggal'],
            'waktu' => $validated['waktu'],
            'kategori' => $validated['kategori'],
            'status' => $status,
            'notifikasi' => $request->boolean('notifikasi'),
            'tujuan' => $validated['kategori'] === 'Pemanggilan Orang Tua' ? 'orangtua' : 'siswa',
            'nama_ortu' => $validated['kategori'] === 'Pemanggilan Orang Tua' ? ($validated['nama_ortu'] ?? $siswa->nama_ortu) : null,
            'no_whatsapp_ortu' => $validated['kategori'] === 'Pemanggilan Orang Tua' ? $siswa->no_whatsapp_ortu : null,
        ]);

        return redirect()->route('guru.jadwal.index')->with(
            'success',
            $isBentrok
                ? 'Jadwal berhasil diperbarui, namun terdapat bentrok dengan jadwal lain.'
                : 'Jadwal berhasil diperbarui!'
        );
    }
    /**
     * Menghapus jadwal.
     */
    public function destroy($id)
    {
        $jadwal = Jadwal::where('id_guru_bk', $this->guruBk->id)->findOrFail($id);
        $jadwal->delete();

        return redirect()->route('guru.jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }

    /**
     * Mengirim notifikasi melalui Fonnte.
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

        $target = array_filter([
            $jadwal->no_whatsapp_guru_bk,
            $jadwal->no_whatsapp_siswa,
            $jadwal->tujuan === 'orangtua' ? $jadwal->no_whatsapp_ortu : null,
        ]);

        Log::debug('Target Notifikasi:', $target);

        foreach ($target as $nomor) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => $token, // langsung token-nya, tanpa "Bearer"
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
