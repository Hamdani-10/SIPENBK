<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Panduan;
use Illuminate\View\View;

class PanduanController extends Controller
{
    public function index(): View
    {
        // Mengambil data panduan untuk guru
        $panduan = Panduan::where('role', 'siswa')->latest()->get();

        return view('content.siswa.panduan.panduan', compact('panduan'));
    }
}
