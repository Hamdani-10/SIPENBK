<?php

use Illuminate\Support\Facades\Route;


// Redirect ke halaman login
Route::get('/', function () {
    return view('auth.login');
});

// Authentikasi Routes
Route::middleware('guest')->group(function(){
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'index'])->name('login');
    Route::post('/login',[App\Http\Controllers\AuthController::class, 'verify'])->name('auth.verify');
});

// Password Reset Routes
Route::get('/lupa-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/lupa-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Logout Route
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout')->middleware(['auth:admin,guru,siswa']);

// Route untuk halaman admin
Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin' ], function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard.index');

    // User Management - Umum (List, Edit, Delete)
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create/admin', [App\Http\Controllers\Admin\UserController::class, 'createAdmin'])->name('admin.users.create.admin');
    Route::get('/users/create/guru', [App\Http\Controllers\Admin\UserController::class, 'createGuru'])->name('admin.users.create.guru');
    Route::get('/users/create/siswa', [App\Http\Controllers\Admin\UserController::class, 'createSiswa'])->name('admin.users.create.siswa');
    Route::post('/users/store', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/edit/{id}', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/update/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/delete/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');

    // Rotue untuk menu halaman Guru BK
    Route::get('/guru-bk', [App\Http\Controllers\Admin\GuruBkController::class, 'index'])->name('admin.guru_bk.index');
    Route::post('/guru-bk', [App\Http\Controllers\Admin\GuruBkController::class, 'store'])->name('admin.guru_bk.store');
    Route::get('/guru-bk/edit/{id}', [App\Http\Controllers\Admin\GuruBkController::class, 'edit'])->name('admin.edit-gurubk');
    Route::put('/guru-bk/update/{id}', [App\Http\Controllers\Admin\GuruBkController::class, 'update'])->name('admin.guru_bk.update');
    Route::delete('/guru-bk/delete/{id}', [App\Http\Controllers\Admin\GuruBkController::class, 'destroy'])->name('admin.guru_bk.destroy');

    // Route untuk menu halaman siswa
    Route::get('/siswa', [App\Http\Controllers\Admin\SiswaController::class, 'index'])->name('admin.siswa.index');
    Route::post('/siswa', [App\Http\Controllers\Admin\SiswaController::class, 'store'])->name('admin.siswa.store');
    Route::get('/siswa/edit/{id_siswa}', [App\Http\Controllers\Admin\SiswaController::class, 'edit'])->name('admin.edit-siswa');
    Route::put('/siswa/update/{id_siswa}', [App\Http\Controllers\Admin\SiswaController::class, 'update'])->name('admin.siswa.update');
    Route::delete('/siswa/delete/{id_siswa}', [App\Http\Controllers\Admin\SiswaController::class, 'destroy'])->name('admin.siswa.destroy');
    Route::get('/siswa/{id_siswa}', [App\Http\Controllers\Admin\SiswaController::class, 'show'])->name('admin.siswa.show');

    // Route Halaman menu Jadwal
    Route::get('/jadwal', [App\Http\Controllers\Admin\JadwalController::class, 'index'])->name('admin.jadwal.index');
    Route::get('/jadwal/create', [App\Http\Controllers\Admin\JadwalController::class, 'create'])->name('admin.create-jadwal');
    Route::post('/jadwal', [App\Http\Controllers\Admin\JadwalController::class, 'store'])->name('admin.jadwal.store');
    Route::get('/jadwal/edit/{id}', [App\Http\Controllers\Admin\JadwalController::class, 'edit'])->name('admin.edit-jadwal');
    Route::put('/jadwal/update/{id}', [App\Http\Controllers\Admin\JadwalController::class, 'update'])->name('admin.jadwal.update');
    Route::delete('/jadwal/delete/{id}', [App\Http\Controllers\Admin\JadwalController::class, 'destroy'])->name('admin.jadwal.destroy');
    Route::get('jadwal/{id}', [App\Http\Controllers\Admin\JadwalController::class, 'show'])->name('admin.jadwal.show');

    // Route untuk menu rekap hasil
    Route::get('/rekap', [App\Http\Controllers\Admin\RekapHasilController::class, 'index'])->name('admin.rekap.index');
    Route::get('/rekap/create', [App\Http\Controllers\Admin\RekapHasilController::class, 'create'])->name('admin.create-rekap');
    Route::get('/rekap/cetak/{id}', [App\Http\Controllers\Admin\RekapHasilController::class, 'cetakPDF'])->name('admin.rekap.cetak-pdf');
    Route::post('/rekap', [App\Http\Controllers\Admin\RekapHasilController::class, 'store'])->name('admin.rekap.store');
    Route::get('/rekap/edit/{id}', [App\Http\Controllers\Admin\RekapHasilController::class, 'edit'])->name('admin.edit-rekap');
    Route::put('/rekap/update/{id}', [App\Http\Controllers\Admin\RekapHasilController::class, 'update'])->name('admin.rekap.update');
    Route::delete('/rekap/delete/{id}', [App\Http\Controllers\Admin\RekapHasilController::class, 'destroy'])->name('admin.rekap.destroy');

    // Route untuk menu panduan
    Route::get('/panduan', [App\Http\Controllers\Admin\PanduanController::class, 'index'])->name('admin.panduan.index');
    Route::get('/panduan/create', [App\Http\Controllers\Admin\PanduanController::class, 'create'])->name('admin.create-panduan');
    Route::post('/panduan', [App\Http\Controllers\Admin\PanduanController::class, 'store'])->name('admin.panduan.store');
    Route::get('/panduan/edit/{panduan}', [App\Http\Controllers\Admin\PanduanController::class, 'edit'])->name('admin.edit-panduan');
    Route::put('/panduan/update/{panduan}', [App\Http\Controllers\Admin\PanduanController::class, 'update'])->name('admin.panduan.update');
    Route::delete('/panduan/delete/{panduan}', [App\Http\Controllers\Admin\PanduanController::class, 'destroy'])->name('admin.panduan.destroy');

});

// Route untuk halaman guru
Route::group(['middleware'=>'auth:guru', 'prefix' => 'guru' ], function(){
    // Route untuk menu beranda
    Route::get('/home', [\App\Http\Controllers\Guru\DashboardController::class, 'index'])->name('guru.dashboard.index');

    // Route untuk menu profil
    Route::get('/profil', [App\Http\Controllers\Guru\ProfilController::class, 'index'])->name('guru.profil.index');
    Route::put('/profil/update/{id}', [App\Http\Controllers\Guru\ProfilController::class, 'update'])->name('guru.profil.update');

    // Route untuk menu siswa
    Route::get('/siswa', [App\Http\Controllers\Guru\SiswaController::class, 'index'])->name('guru.siswa.index');
    Route::post('/siswa', [App\Http\Controllers\Guru\SiswaController::class, 'store'])->name('guru.siswa.store');
    Route::put('/siswa/update/{id_siswa}', [App\Http\Controllers\Guru\SiswaController::class, 'update'])->name('guru.siswa.update');
    Route::delete('/delete/{id_siswa}', [App\Http\Controllers\Guru\SiswaController::class, 'destroy'])->name('guru.siswa.destroy');

    // Route untuk menu jadwal
    Route::get('/jadwal', [App\Http\Controllers\Guru\JadwalController::class, 'index'])->name('guru.jadwal.index');
    Route::post('/jadwal', [App\Http\Controllers\Guru\JadwalController::class, 'store'])->name('guru.jadwal.store');
    Route::put('/jadwal/update/{id}', [App\Http\Controllers\Guru\JadwalController::class, 'update'])->name('guru.jadwal.update');
    Route::delete('/jadwal/delete/{id}', [App\Http\Controllers\Guru\JadwalController::class, 'destroy'])->name('guru.jadwal.destroy');

    // Route untuk menu rekap hasil
    Route::get('/rekap', [App\Http\Controllers\Guru\RekapHasilController::class, 'index'])->name('guru.rekap.index');
    Route::get('/rekap/cetak/{id}', [App\Http\Controllers\Guru\RekapHasilController::class, 'cetakPDF'])->name('guru.rekap.cetak-pdf');
    Route::post('/rekap', [App\Http\Controllers\Guru\RekapHasilController::class, 'store'])->name('guru.rekap.store');
    Route::put('/rekap/{id}', [App\Http\Controllers\Guru\RekapHasilController::class, 'update'])->name('guru.rekap.update');
    Route::delete('/rekap/delete/{id}', [App\Http\Controllers\Guru\RekapHasilController::class, 'destroy'])->name('guru.rekap.destroy');

    // Route untuk menu panduan
    Route::get('/panduan', [App\Http\Controllers\Guru\PanduanController::class, 'index'])->name('guru.panduan.index');
});

// Route untuk halaman siswa
Route::group((['middleware'=>'auth:siswa', 'prefix' => 'siswa' ]), function(){
    Route::get('/home', [\App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('siswa.dashboard.index');

    // Route untuk menu profil
    Route::get('/profil', [App\Http\Controllers\Siswa\ProfilController::class, 'index'])->name('siswa.profil.index');
    Route::put('/profil/update/{id_siswa}', [App\Http\Controllers\Siswa\ProfilController::class, 'update'])->name('siswa.profil.update');

    // Route untuk menu jadwal
    Route::get('/jadwal', [App\Http\Controllers\Siswa\JadwalController::class, 'index'])->name('siswa.jadwal.index');
    Route::post('/jadwal', [App\Http\Controllers\Siswa\JadwalController::class, 'store'])->name('siswa.jadwal.store');

    // Route untuk menu rekap hasil
    Route::get('/rekap', [App\Http\Controllers\Siswa\RekapHasilController::class, 'index'])->name('siswa.rekap.index');

    // Route untuk menu panduan
    Route::get('/panduan', [App\Http\Controllers\Siswa\PanduanController::class, 'index'])->name('siswa.panduan.index');
});
