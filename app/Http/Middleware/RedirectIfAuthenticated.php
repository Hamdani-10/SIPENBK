<?php
/**
 * Copyright (c) 2025 Hamdani Kevin
 * This project is part of the SIPENBK Counseling Scheduling System thesis.
 * All rights reserved.
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use ILLuminate\Auth\Middleware\RedirectIfAuthenticated as RedirectIfAuthenticatedMiddleware;
use Illuminate\Http\JsonResponse;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guard): Response | JsonResponse
    {
        if (Auth::guard('admin')->check()){
            return redirect()->route('admin.dashboard.index');
        }else if (Auth::guard('guru')->check()){
            return redirect()->route('guru.dashboard.index');
        }else if (Auth::guard('siswa')->check()){
            return redirect()->route('siswa.dashboard.index');
        }   
        return $next($request);
    }
}
