<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EhGestor
{
    public function handle(Request $request, Closure $next)
    {
        $usuario = $request->user();

        if (! $usuario || ! $usuario->ehGestor()) {
            abort(403, 'Acesso restrito a gestores.');
        }

        return $next($request);
    }
}
