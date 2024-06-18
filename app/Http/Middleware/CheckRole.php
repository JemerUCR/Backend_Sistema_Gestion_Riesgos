<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Rol; // Asegúrate de importar el modelo Rol si es necesario
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Obtener el usuario autenticado
        $usuario = Auth::user();

        // Verificar si el usuario tiene alguno de los roles permitidos
        foreach ($roles as $rol) {
            // Buscar el rol por su nombre en la tabla de roles
            $rolEncontrado = Rol::where('nombre_rol', $rol)->first();

            // Verificar si el usuario tiene asignado este rol
            if ($usuario->roles->contains($rolEncontrado)) {
                // Si tiene el rol permitido, continuamos con la solicitud
                return $next($request);
            }
        }

        // Si el usuario no tiene ninguno de los roles permitidos, devolvemos una respuesta de no autorizado
        return response()->json(['message' => 'No autorizado'], 403);
    }
}
