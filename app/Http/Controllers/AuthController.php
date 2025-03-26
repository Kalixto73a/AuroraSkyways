<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;

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
        if ($token = JWTAuth::attempt($credentials)) {  
            // Obtener el usuario autenticado
            $user = JWTAuth::user();

            // Responder con el token y el usuario en JSON
            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200);
        }

        // Si la solicitud es JSON, devolver un error 401 en JSON
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Las credenciales no coinciden con nuestros registros.'
            ], 401);
        }

        // Si la solicitud no es JSON, redirigir con error
        return back()->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.']);
    }

    public function webLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate(); // 🔹 Regenera la sesión después del login
    
            return redirect()->intended('/'); // 🔹 Debería redirigir aquí
        }
    
        return back()->withErrors([
            'email' => 'El correo es incorrecto.',
            'password' => 'La contraseña es incorrecta.'
        ]);
    }

    /* public function me()
    {   
        return response()->json(JWTAuth::user());
    } */

    public function logout(Request $request)
    {
        try {
            // Intentar obtener el token
            $token = JWTAuth::getToken();
            
            // Si no se proporciona token, lanzar una excepción
            if (!$token) {
                return response()->json(['error' => 'Token no proporcionado'], 401);
            }

            // Intentar autenticar al usuario con el token
            $user = JWTAuth::authenticate($token);

            // Invalidar el token
            JWTAuth::invalidate($token);

            return response()->json(['message' => 'Cierre de sesión exitoso']);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        }
    
    }
    
    public function webLogout(Request $request)
    {
        try {
            // Cerrar sesión de la aplicación web
            Auth::guard('web')->logout();
    
            // Invalidar el token JWT (solo si JWT se usa en la sesión web)
            try {
                JWTAuth::invalidate(JWTAuth::getToken());
            } catch (JWTException $e) {
                throw new JWTException('Token inválido o ya expirado');
            }
    
            // Regenerar la sesión para evitar vulnerabilidades
            $request->session()->invalidate();
            $request->session()->regenerateToken();
    
            return redirect('/')->with('message', 'Has cerrado sesión exitosamente.');
        } catch (JWTException $e) {
            // Si ocurre un error con el token JWT
            return redirect('/')->with('error', 'Token inválido o ya expirado');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Error al cerrar sesión');
        }
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


