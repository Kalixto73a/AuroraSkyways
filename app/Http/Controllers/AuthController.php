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

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'role' => 'nullable|string|in:admin,user',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        if (!$request->has('role')) {
            $request->merge(['role' => 'user']);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->password = bcrypt($request->password);
        $user->save();

        $token = JWTAuth::fromUser($user);

        if (!$request->wantsJson()) {
            return redirect()->route('home');
        }

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = JWTAuth::attempt($credentials)) {  

            $user = JWTAuth::user();

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Las credenciales no coinciden con nuestros registros.'
            ], 401);
        }

        return back()->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.']);
    }

    public function webLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate(); 
    
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Correo o Contraseña incorrectos']);

    }

    /* public function me()
    {   
        return response()->json(JWTAuth::user());
    } */

    public function logout(Request $request)
    {
        try {

            $token = JWTAuth::getToken();
            
            if (!$token) {
                return response()->json(['error' => 'Token no proporcionado'], 401);
            }

            $user = JWTAuth::authenticate($token);

            JWTAuth::invalidate($token);

            return response()->json(['message' => 'Cierre de sesión exitoso']);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        }
    
    }
    
    public function webLogout(Request $request)
    {
        try {

            Auth::guard('web')->logout();
    
            try {
                JWTAuth::invalidate(JWTAuth::getToken());
            } catch (JWTException $e) {
                throw new JWTException('Token inválido o ya expirado');
            }
    
            $request->session()->invalidate();
            $request->session()->regenerateToken();
    
            return redirect('/')->with('message', 'Has cerrado sesión exitosamente.');
        } catch (JWTException $e) {
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


