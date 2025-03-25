<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('loginView');
    }
    /**
     * Intenta autenticar al usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validar las credenciales del formulario
        $credentials = $request->only('email', 'password');

        // Intentar autenticar al usuario
        if (JWTAuth::attempt($credentials)) {
            // Si las credenciales son correctas, redirige al usuario a la página principal
            return redirect()->intended('/'); // Redirige a la página deseada o a la página principal
        }

        // Si las credenciales son incorrectas, redirige de vuelta con un mensaje de error
        return back()->withErrors([
            'email' => 'Las credenciales son incorrectas',
        ]);
    }

    /**
     * Cierra sesión del usuario autenticado.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
}
