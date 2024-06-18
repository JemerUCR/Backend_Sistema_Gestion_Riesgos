<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use Notifiable , HasApiTokens;

    protected $table = 't_usuario'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'cedula'; // Clave primaria de la tabla
    public $incrementing = false; // Si la clave primaria no es auto-incremental
    public $timestamps = false; //Desactiva las columnas de tiempo buscadas por el modelo Eloquent de Laravel

    protected $fillable = [
        'cedula', 'correo', 'password_hashed', 'cod_rol', 'cod_departamento'  //datos perimitidos con ingreso masivo
    ];

    protected $hidden = [
        'password_hashed', 'remember_token', //son los campos que no seran mostrados en la respuesta
    ];

    //belongsToMany indica una relación muchos a muchos.
    //El primer parámetro es el modelo relacionado (Rol).
    //El segundo parámetro es la tabla intermedia (t_detalle_usuario).
    //El tercer parámetro es la clave foránea en la tabla intermedia que referencia al modelo actual (cedula).
    //El cuarto parámetro es la clave foránea en la tabla intermedia que referencia al modelo relacionado (codrol).
    public function roles() {
        return $this->belongsToMany(Rol::class,'t_detalle_usuario','cedula', 'cod_rol');
    }
    
}

