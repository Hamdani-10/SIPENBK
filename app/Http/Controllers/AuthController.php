<?php
/**
 * Copyright (c) 2025 Hamdani Kevin
 * This project is part of the SIPENBK Counseling Scheduling System .
 * All rights reserved.
 */

namespace App\Http\Controllers;

use App\Http\Requests\UserAuthVerifyRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use ILLuminate\View\View;
use App\Models\User;

class AuthController extends Controller
{
    public function index(): View
    {
        return view('auth.login');
    }

    public function verify(UserAuthVerifyRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Cari user berdasarkan email
        $user = User::where('email', $data['email'])->first();

        // Jika email tidak ditemukan
        if (!$user) {
            return redirect()->route('login')->with('error', 'Email anda salah.');
        }

        // Cek berdasarkan role dan login
        if (Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password'], 'role' => 'admin'])) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard.index'));
        } elseif (Auth::guard('guru')->attempt(['email' => $data['email'], 'password' => $data['password'], 'role' => 'guru'])) {
            $request->session()->regenerate();
            return redirect()->intended(route('guru.dashboard.index'));
        } elseif (Auth::guard('siswa')->attempt(['email' => $data['email'], 'password' => $data['password'], 'role' => 'siswa'])) {
            $request->session()->regenerate();
            return redirect()->intended(route('siswa.dashboard.index'));
        }

        // Kalau email ada tapi password salah
        return redirect()->route('login')->with('error', 'Password anda salah.');
    }


    public function logout(Request $request): RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } elseif (Auth::guard('guru')->check()) {
            Auth::guard('guru')->logout();
        } elseif (Auth::guard('siswa')->check()) {
            Auth::guard('siswa')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
