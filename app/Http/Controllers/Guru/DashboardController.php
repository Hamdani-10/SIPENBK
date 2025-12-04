<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): View{

        $guru = Auth::user()->guruBk;

        return view('content.guru.dashboard', [
            'namaGuru' => $guru->nama_guru_bk ?? Auth::user()->name
        ]);
    }
}
