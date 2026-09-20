<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsKitchen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isKitchen()) {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Anda harus login sebagai petugas dapur untuk mengakses KDS.');
    }
}
