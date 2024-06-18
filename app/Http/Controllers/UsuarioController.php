<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\Usuario;
use Illuminate\Support\Facades\Hash;


class UsuarioController extends Controller
{
    public function index()
    {
        // Obtener todos los usuarios junto con sus roles
        $usuarios = Usuario::with('roles')->get();

        return response()->json($usuarios, 200);
    }

    public function show($cedula)
    {
        // Buscar el usuario por su cédula junto con los roles
        $usuario = Usuario::with('roles')->find($cedula);

        if (is_null($usuario)) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json($usuario, 200);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cedula' => 'required|string|max:15|unique:t_usuario,cedula',
            'correo' => 'required|string|max:30',
            'password_hashed' => 'required|string|max:255',
            'cod_rol' => 'required|integer|exists:t_rol,cod_rol', //Verificar que el rol exista antes de agregarlo
            'cod_departamento' => 'required|integer',
        ]);

        $validatedData['password_hashed'] = Hash::make($validatedData['password_hashed']);

        $usuario = Usuario::create([
            'cedula' => $validatedData['cedula'],
            'correo' => $validatedData['correo'],
            'password_hashed' => $validatedData['password_hashed'],
            'cod_departamento' => $validatedData['cod_departamento'],
        ]);

        //Asiganar el rol especificado
        $usuario->roles()->attach($validatedData['cod_rol']);

        return response()->json($usuario, 201);
    }

    public function update(Request $request, $cedula)
    {
        // Buscar el usuario por su cédula
        $usuario = Usuario::find($cedula);

        if (is_null($usuario)) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Validar los datos entrantes
        $validatedData = $request->validate([
            'nombre_usuario' => 'sometimes|required|string|max:30',
            'password_hashed' => 'sometimes|required|string|max:255',
            'cod_rol' => 'sometimes|required|integer|exists:t_rol,cod_rol', // Verificar que el rol exista
            'cod_departamento' => 'sometimes|required|integer',
        ]);

        // Separar los datos del usuario de los datos de rol
        $usuarioData = array_filter($validatedData, function ($key) {
            return $key !== 'cod_rol';
        }, ARRAY_FILTER_USE_KEY);

        // Encriptar la contraseña si se proporciona
        if (isset($usuarioData['password_hashed'])) {
            $usuarioData['password_hashed'] = Hash::make($usuarioData['password_hashed']);
        }

        // Actualizar los datos del usuario
        $usuario->update($usuarioData);

        // Actualizar o asignar el rol del usuario si se proporciona
        if (isset($validatedData['cod_rol'])) {
            // Verificar si el rol ya está asignado
            if (!$usuario->roles->contains($validatedData['cod_rol'])) {
                // Asignar el nuevo rol
                $usuario->roles()->attach($validatedData['cod_rol']);
            }
        }

        return response()->json($usuario, 200);
    }

    public function destroy($cedula)
    {
        // Buscar el usuario por su cédula
        $usuario = Usuario::find($cedula);

        if (is_null($usuario)) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Desvincular todos los roles del usuario
        $usuario->roles()->detach();

        // Eliminar el usuario
        $usuario->delete();

        return response()->json(['message' => 'Usuario eliminado exitosamente'], 200);
    }
}
