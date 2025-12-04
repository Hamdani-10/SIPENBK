<?php

namespace App\Providers;
use App\Models\User;
use App\Observers\UserObserver;
use App\Models\GuruBk;
use App\Models\Siswa;
use App\Observers\GuruBkObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\SiswaObserver;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        setlocale(LC_TIME, 'id_ID.utf8');
        Carbon::setLocale('id');
        User::observe(UserObserver::class);
        GuruBk::observe(GuruBkObserver::class);
        Siswa::observe(SiswaObserver::class);
    }
}
