<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Revisa si el usuario está logueado Y si su rol es 'admin'
        if (auth()->check() && auth()->user()->rol == 'admin') {
            // 2. Si es admin, déjalo pasar a la siguiente ruta
            return $next($request);
        }

        // 3. Si no es admin (es cliente o invitado), envíalo al catálogo
        return redirect(route('home')); 
    }
}