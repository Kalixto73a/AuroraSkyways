<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Debes iniciar sesión.');
        }
    
        if (Auth::user()->role === 'user') {
            return $next($request);
        }
    
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Acceso denegado. Se requiere permiso de usuario.'], 403);
        }
    
        return redirect('/login')->with('error', 'Acceso denegado. No tienes permisos de usuario.');
    }
}
