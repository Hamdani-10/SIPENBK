<?php

namespace App\Observers;

use App\Models\User;
use App\Models\GuruBk;
use App\Models\Siswa;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    // public function created(User $user): void
    // {
    //     try {
    //         if ($user->role === 'guru') {
    //             GuruBk::create([
    //                 'user_id' => $user->id,
    //                 'nama_guru_bk' => $user->name,
    //                 'jabatan' => 'Guru BK',
    //                 'no_whatsapp_guru_bk' => $user->no_whatsapp_guru_bk ?? '-', // Default or generated WhatsApp number
    //             ]);
    //         }
            
    //         if ($user->role === 'siswa') {
    //             Siswa::create([
    //                 'user_id' => $user->id,
    //                 'nisn' => '-' . uniqid(),
    //                 'nama_siswa' => $user->name,
    //                 'kelas' => '-',
    //                 'no_whatsapp_siswa' => '-', // Default or generated WhatsApp number
    //                 'nama_ortu' => '-', // Default or generated parent name
    //                 'no_whatsapp_ortu' => '-', // Default or generated WhatsApp number
    //             ]);
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('Error creating GuruBk for user ID ' . $user->id . ': ' . $e->getMessage());
    //     }
    // }


    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        try {
            if ($user->role === 'guru') {
                GuruBk::where('user_id', $user->id)->delete();
            }
            if ($user->role === 'siswa') {
                Siswa::where('user_id', $user->id)->delete();
            }
        } catch (\Exception $e) {
            Log::error('Error deleting GuruBk for user ID ' . $user->id . ': ' . $e->getMessage());
        }
    }
}
