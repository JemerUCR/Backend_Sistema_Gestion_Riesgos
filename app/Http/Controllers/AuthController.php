<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;    
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Definir las reglas de validación
        $rules = [
            'correo' => 'required|email',
            'password' => 'required',
        ];

        // Crear el validador
        $validator = Validator::make($request->all(), $rules);

        // Verificar si la validación falla
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Obtener credenciales de manera segura
        $credentials = $request->only('correo', 'password');

        // Buscar al usuario por correo electrónico
        $usuario = Usuario::where('correo', $credentials['correo'])->first();

        // Verificar si el usuario existe y la contraseña es correcta
        if (!$usuario || !Hash::check($credentials['password'], $usuario->password_hashed)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        // Generar el token de Sanctum para el usuario
        $token = $usuario->createToken('auth_token')->plainTextToken;

        // Devolver la respuesta con el token
        return response()->json(['token' => $token], 200);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }
}
