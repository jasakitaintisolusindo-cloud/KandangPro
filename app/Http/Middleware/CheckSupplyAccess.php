<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSupplyAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->canAccess('supplies')) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman Master Inventaris.');
        }

        return $next($request);
    }
}
