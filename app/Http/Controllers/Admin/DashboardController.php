<?php
/**
 * Copyright (c) 2025 Hamdani Kevin
 * This project is part of the SIPENBK Counseling Scheduling System thesis.
 * All rights reserved.
 */


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use ILLuminate\View\View;
use App\Models\Siswa;
use App\Models\Jadwal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    
    public function index()
    {
        $jadwalHariIni = Jadwal::whereDate('tanggal', Carbon::today())->count();
        return view('content.admin.dashboard', [
            'totalSiswa' => Siswa::count(),
            'totalGuruBK' => User::where('role', 'guru')->count(),
            'totalUsers' => User::count(),
            'jadwalHariIni' => $jadwalHariIni,
        ]);
    }

}
