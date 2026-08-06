<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSiswa
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isSiswa()) {
            if ($request->user() && $request->user()->isAdmin()) {
                return redirect()->route('admin.exams.index')->with('error', 'Halaman ini khusus untuk Siswa.');
            }

            return redirect()->route('dashboard')->with('error', 'Akses khusus Siswa.');
        }

        return $next($request);
    }
}
