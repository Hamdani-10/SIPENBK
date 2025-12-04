<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'id_guru_bk', 'id_siswa', 'nama_siswa', 'tanggal', 'waktu', 'kategori', 
        'dibuat_oleh', 'tujuan', 'status', 'no_whatsapp_guru_bk', 
        'no_whatsapp_siswa', 'no_whatsapp_ortu', 'notifikasi', 'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'notifikasi' => 'boolean',
    ];

    protected $attributes = [
        'status' => self::STATUS_TERSEDIA,
        'notifikasi' => false,
    ];

    public const STATUS_TERSEDIA = 'tersedia';
    public const STATUS_BENTROK = 'bentrok';
    public const STATUS_SELESAI = 'selesai';

    public const DIBUAT_OLEH_ADMIN = 'admin';
    public const DIBUAT_OLEH_GURU = 'guru';
    public const DIBUAT_OLEH_SISWA = 'siswa';

    public const TUJUAN_SISWA = 'siswa';
    public const TUJUAN_ORANGTUA = 'orangtua';

    public function guruBk()
    {
        return $this->belongsTo(GuruBk::class, 'id_guru_bk', 'id')->withDefault();
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id')->withDefault();
    }

    // App\Models\Jadwal.php

    public function rekapHasil()
    {
        return $this->hasOne(RekapHasil::class, 'id_jadwal', 'id');
    }


    // Formatting tanggal dan waktu
    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal ? Carbon::parse($this->tanggal)->translatedFormat('l, d F Y') : '-';
    }

        public function getFormattedWaktuAttribute()
    {
        return $this->waktu ? Carbon::createFromFormat('H:i:s', $this->waktu)->format('H:i') : '-';
    }
    

    public function getLinkWhatsappGuruAttribute()
    {
        return $this->no_whatsapp_guru_bk ? 'https://wa.me/' . $this->no_whatsapp_guru_bk : null;
    }

    public function getLinkWhatsappSiswaAttribute()
    {
        return $this->no_whatsapp_siswa ? 'https://wa.me/' . $this->no_whatsapp_siswa : null;
    }

    public function getLinkWhatsappOrtuAttribute()
    {
        return $this->no_whatsapp_ortu ? 'https://wa.me/' . $this->no_whatsapp_ortu : null;
    }

    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', now());
    }

    public function scopeBelumTerkirim($query)
    {
        return $query->where('notifikasi', false);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['dibuat_oleh'])) {
            $query->where('dibuat_oleh', $filters['dibuat_oleh']);
        }

        if (!empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }

        if (!empty($filters['tujuan'])) {
            $query->where('tujuan', $filters['tujuan']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['tanggal'])) {
            $query->whereDate('tanggal', $filters['tanggal']);
        }
    }

    // Mutator untuk format nomor WhatsApp
    public function setNoWhatsappGuruBkAttribute($value)
    {
        $this->attributes['no_whatsapp_guru_bk'] = $this->sanitizeWhatsapp($value);
    }

    public function setNoWhatsappSiswaAttribute($value)
    {
        $this->attributes['no_whatsapp_siswa'] = $this->sanitizeWhatsapp($value);
    }

    public function setNoWhatsappOrtuAttribute($value)
    {
        $this->attributes['no_whatsapp_ortu'] = $this->sanitizeWhatsapp($value);
    }

    // Fungsi helper lokal
    protected function sanitizeWhatsapp($value)
    {
        // Hapus semua karakter kecuali angka
        $nomor = preg_replace('/\D/', '', $value);

        // Ganti awalan 0 dengan 62 (Indonesia)
        if (substr($nomor, 0, 1) === '0') {
            $nomor = '62' . substr($nomor, 1);
        }

        return $nomor;
    }
}
