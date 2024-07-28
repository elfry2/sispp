<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForbidIfLevelIsKepalaSekolah
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->level->name == "Kepala Sekolah")
            return redirect()
                ->back()
                ->with('message', (object) [
                    'type' => 'danger',
                    'content' => 'Akses tidak diizinkan.'
                ]);

        return $next($request);
    }
}
