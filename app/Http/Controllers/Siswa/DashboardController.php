<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): View{
        $siswa = Auth::user()->siswa;

        return view('content.siswa.dashboard', [
            'namaSiswa'=> $siswa->nama_siswa ?? Auth::user()->name
        ]);
    }
}
