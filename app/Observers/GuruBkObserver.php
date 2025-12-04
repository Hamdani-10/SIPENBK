<?php

namespace App\Observers;

use App\Models\GuruBk;

class GuruBkObserver
{
    /**
     * Handle the GuruBk "created" event.
     */
    public function created(GuruBk $guruBk): void
    {
        //
    }

    /**
     * Handle the GuruBk "updated" event.
     */
    public function updated(GuruBk $guruBk): void
    {
        //
    }

    /**
     * Handle the GuruBk "deleted" event.
     */
    public function deleted(GuruBk $guruBk): void
    {
        if ($guruBk->user) {
            $guruBk->user->delete();
        }
    }

    /**
     * Handle the GuruBk "restored" event.
     */
    public function restored(GuruBk $guruBk): void
    {
        //
    }

    /**
     * Handle the GuruBk "force deleted" event.
     */
    public function forceDeleted(GuruBk $guruBk): void
    {
        //
    }
}
