<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'user') {
            return $next($request);
        }
    
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Acceso denegado. Se requiere permiso de usuario.'], 403);
        }
    
        return redirect('/login')->with('error', 'Acceso denegado. No tienes permisos de usuario.');
    }
}
