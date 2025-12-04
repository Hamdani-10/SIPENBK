<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class JadwalNotification extends Notification
{
    protected $jadwal;

    public function __construct($jadwal)
    {
        $this->jadwal = $jadwal;
    }

    // Tentukan channel yang digunakan (misalnya, WhatsApp atau lainnya)
    public function via($notifiable)
    {
        return ['whatsapp']; // Gunakan WhatsApp atau channel lain yang sudah diatur
    }

    // Tentukan format pesan WhatsApp (contoh)
    public function toWhatsapp($notifiable)
    {
        return [
            'message' => "Jadwal baru untuk {$this->jadwal->nama_siswa}: {$this->jadwal->tanggal} pada pukul {$this->jadwal->waktu}",
            'phone' => $notifiable->no_whatsapp, // Asumsi ada field no_whatsapp di model pengguna
        ];
    }
}
