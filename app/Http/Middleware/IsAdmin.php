<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();
        if($user && $user->role === 'admin'){
            return $next($request);
        }
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Acceso denegado. Se requiere permiso de administrador.'], 403);
        }

        return redirect('/login')->with('error', 'Acceso denegado. No tienes permisos de administrador.');
        }
}
