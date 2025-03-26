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

        if (JWTAuth::attempt($credentials)) {
            
            $token = JWTAuth::fromUser(JWTAuth::user());

            $cookie = cookie('jwt_token', $token, 60);

            return redirect()->intended('/')->withCookie($cookie);
        }
        
        // Si las credenciales son incorrectas, volver al formulario de login con error
        return back()->withErrors([
            'email'=> 'El correo es incorrecto',
            'password' => 'La contraseña es incorrecta'
        ]);
    }

    /* public function me()
    {   
        return response()->json(JWTAuth::user());
    } */

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
    public function webLogout(Request $request)
    {
        try {
            // Obtener el token de la solicitud
            $token = JWTAuth::getToken();
    
            // Invalidar el token
            JWTAuth::invalidate($token);
    
            // Si la solicitud es de tipo JSON, responde con el mensaje de éxito
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Cierre de sesión exitoso']);
            }
    
            // Si la solicitud no es JSON (probablemente desde una solicitud web), redirige al home
            return redirect('/')->with('message', 'Cierre de sesión exitoso');
            
        } catch (JWTException $e) {
            // Si el token no es válido o ya ha expirado, devolver un error adecuado
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Token inválido o ya expirado'], 401);
            }
    
            // Si la solicitud no es JSON (probablemente desde una solicitud web), redirige al home con el error
            return redirect('/')->with('error', 'Token inválido o ya expirado');
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


