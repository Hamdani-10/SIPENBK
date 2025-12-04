<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Siswa extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'siswa';
    protected $with = ['user'];
    protected $appends = ['display_whatsapp_siswa', 'display_whatsapp_ortu'];

    // Primary key
    protected $primaryKey = 'id';
    public $incrementing = true;  
    protected $keyType = 'int';   

    // Field yang bisa diisi massal
    protected $fillable = [
        'user_id',
        'nisn',
        'nama_siswa',
        'kelas',
        'no_whatsapp_siswa',
        'nama_ortu',
        'no_whatsapp_ortu',
        'foto',
    ];

    // Relasi: siswa milik satu user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Accessor: Format nomor WhatsApp siswa menjadi +62
     */
    protected function formattedWhatsappSiswa(): Attribute
    {
        return Attribute::make(
            get: function () {
                $nomor = preg_replace('/\D/', '', $this->no_whatsapp_siswa); // hanya angka

                if (!$nomor) {
                    return '-';
                }

                if (str_starts_with($nomor, '0')) {
                    $nomor = '62' . substr($nomor, 1);
                } elseif (!str_starts_with($nomor, '62')) {
                    $nomor = '62' . $nomor;
                }

                return $nomor;
            }
        );
    }

    /**
     * Accessor: Format nomor WhatsApp orang tua menjadi +62
     */
    protected function formattedWhatsappOrtu(): Attribute
    {
        return Attribute::make(
            get: function () {
                $nomor = preg_replace('/\D/', '', $this->no_whatsapp_ortu); // hanya angka

                if (!$nomor) {
                    return '-';
                }

                if (str_starts_with($nomor, '0')) {
                    $nomor = '62' . substr($nomor, 1);
                } elseif (!str_starts_with($nomor, '62')) {
                    $nomor = '62' . $nomor;
                }

                return $nomor;
            }
        );
    }

    /**
     * Accessor: Nama user dari relasi user
     */
    protected function namaUser(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user?->name ?? '-'
        );
    }

        /**
     * Accessor: Tampilkan no WA siswa dengan format lokal 08xxxx
     */
    protected function displayWhatsappSiswa(): Attribute
    {
        return Attribute::make(
            get: function () {
                $nomor = preg_replace('/\D/', '', $this->no_whatsapp_siswa); // hanya angka

                if (str_starts_with($nomor, '62')) {
                    return '0' . substr($nomor, 2);
                }

                return $nomor ?: '-';
            }
        );
    }

    /**
     * Accessor: Tampilkan no WA ortu dengan format lokal 08xxxx
     */
    protected function displayWhatsappOrtu(): Attribute
    {
        return Attribute::make(
            get: function () {
                $nomor = preg_replace('/\D/', '', $this->no_whatsapp_ortu); // hanya angka

                if (str_starts_with($nomor, '62')) {
                    return '0' . substr($nomor, 2);
                }

                return $nomor ?: '-';
            }
        );
    }

}

