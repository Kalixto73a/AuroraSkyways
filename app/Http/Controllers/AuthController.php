<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\JWT;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'role' => 'nullable|string|in:admin,user',
            'password' => 'required|confirmed|min:8',
        ]);

        // Si la validación falla
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        // Si no se especifica el 'role', asignar 'user' por defecto
        if (!$request->has('role')) {
            $request->merge(['role' => 'user']);
        }

        // Creación del nuevo usuario
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->password = bcrypt($request->password); // Usar bcrypt para encriptar la contraseña
        $user->save();

        // Generar el token de acceso para el nuevo usuario (usando JWT)
        $token = JWTAuth::fromUser($user);

        // Si la solicitud es de tipo web (navegador), redirigir a la página principal
        if (!$request->wantsJson()) {
            return redirect()->route('home'); // Redirigir al home
        }

        // Si la solicitud es de tipo API (Postman u otra herramienta), devolver el token y el usuario
        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        // Validar las credenciales de email y contraseña
        $credentials = $request->only('email', 'password');

        // Intentar autenticar al usuario usando JWT
        if (JWTAuth::attempt($credentials)) {
            // Si la solicitud es de tipo API (por ejemplo, Postman o una SPA)
            if ($request->wantsJson()) {
                // Obtener el usuario autenticado
                $user = JWTAuth::user();

                // Generar el token para el usuario autenticado
                $token = $this->respondWithToken(JWTAuth::fromUser($user));

                // Responder con el token y el usuario en formato JSON
                return response()->json([
                    'user' => $user,
                    'token' => $token
                ], 200);
            }
            return redirect()->route('home'); // Redirigir al home (o la página principal)
        }
        // Si la autenticación falla, devolver un mensaje de error
        return back()->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.']);
    }

    public function webLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (JWTAuth::attempt($credentials)) {
            // Si el login es exitoso, redirige al usuario a la página principal
            return redirect()->intended('/');
        }

        // Si las credenciales son incorrectas, volver al formulario de login con error
        return back()->withErrors(['email' => 'Las credenciales son incorrectas']);
    }

    public function me()
    {   
        return response()->json(JWTAuth::user());
    }

    public function logout()
    {
        try {
            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token); // Invalidar el token

            return response()->json(['message' => 'Cierre de sesión exitoso']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token inválido o ya expirado'], 401);
        }
    }
    public function weblogout()
    {
        try {
            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token); // Invalidar el token

            return response()->json(['message' => 'Cierre de sesión exitoso']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token inválido o ya expirado'], 401);
        }
        return redirect('/');  // Redirige a la página principal después de cerrar sesión
    }

    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh(JWTAuth::getToken()));   
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}


