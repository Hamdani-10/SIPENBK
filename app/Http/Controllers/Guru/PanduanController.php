<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Panduan;
use Illuminate\View\View;

class PanduanController extends Controller
{
    public function index(): View
    {
        // Mengambil data panduan untuk guru
        $panduan = Panduan::where('role', 'guru')->latest()->get();

        return view('content.guru.panduan.panduan', compact('panduan'));
    }
}
