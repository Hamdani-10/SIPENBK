<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapHasil extends Model
{
    use HasFactory;

    protected $table = 'rekap_hasil_kehadiran';
    protected $casts = [
        'kehadiran_ortu' => 'integer',
        'kehadiran_siswa' => 'integer',
    ];

    protected $fillable = [
        'id_jadwal',
        'id_guru_bk',
        'hasil_bk',
        'kehadiran_siswa',
        'kehadiran_ortu',
        'catatan_tambahan',
    ];

    /**
     * Relasi ke Jadwal
     */
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id');
    }

    /**
     * Relasi ke Guru BK
     */
    public function guruBk()
    {
        return $this->belongsTo(GuruBk::class, 'id_guru_bk', 'id');
    }

    /**
     * Relasi ke Siswa melalui Jadwal
     */
    public function siswa()
    {
        return $this->hasOneThrough(
            Siswa::class,
            Jadwal::class,
            'id',          // foreign key di jadwal
            'id',          // foreign key di siswa
            'id_jadwal',   // foreign key di rekap
            'id_siswa'     // local key di jadwal
        );
    }
}
