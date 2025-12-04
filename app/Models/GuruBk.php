<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class GuruBk extends Model
{
    use HasFactory;

    protected $table = 'guru_bk';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $appends = ['display_whatsapp_guru_bk'];

    protected $fillable = [
        'nama_guru_bk',
        'jabatan',
        'no_whatsapp_guru_bk',
        'foto',
        'user_id',
    ];

    /**
     * Relasi ke tabel users.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function GuruBk()
    {
        return $this->hasMany(GuruBk::class, 'id_guru_bk');
    }

    /**
     * Accessor: Format nomor WhatsApp menjadi +62 (contoh: 0812 -> +62812)
     */
    protected function formattedWhatsapp(): Attribute
{
    return Attribute::make(
        get: function () {
            $nomor = preg_replace('/\D/', '', $this->no_whatsapp_guru_bk); // hanya angka

            if (!$nomor) {
                return '-';
            }

            if (str_starts_with($nomor, '0')) {
                $nomor = '62' . substr($nomor, 1);
            } elseif (str_starts_with($nomor, '62')) {
                $nomor = '62' . $nomor;
            }

            return $nomor;
        }
    );
}


    /**
     * Accessor: Nama user dari relasi
     */
    protected function namaUser(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user?->name ?? '-'
        );
    }

    /**
     * Accessor: Jabatan dengan huruf kapital setiap kata
     */
    protected function formattedJabatan(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->jabatan
                ? ucwords(strtolower($this->jabatan))
                : '-'
        );
    }
    protected function displayWhatsappGuruBk(): Attribute
    {
        return Attribute::make(
            get: function () {
                $nomor = preg_replace('/\D/', '', $this->no_whatsapp_guru_bk); // hanya angka

                if (str_starts_with($nomor, '62')) {
                    return '0' . substr($nomor, 2);
                }

                return $nomor ?: '-';
            }
        );
    }
}
