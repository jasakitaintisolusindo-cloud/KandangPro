<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCoopAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->canAccess('coops')) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman Master Kandang.');
        }

        return $next($request);
    }
}
