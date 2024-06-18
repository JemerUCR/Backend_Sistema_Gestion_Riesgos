<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 't_rol'; //Nombre de la tabla

    protected $primaryKey = 'cod_rol'; //LLave primaria de este modelo Eloquent(Bd Mysql)(t_rol)

    public $incrementing = false; //No es incrementable

    public $timestamps = false; //No se ingresan datos extra a la bd referente a la hora

    protected $fillable = [
        'cod_rol' , 'nombre_rol'
     ]; //campos ingresados de forma masiva

    protected $hidden = []; //Datos que no se mostraran en alguna salida Json o etc

    //Relaciones

    //Relacion con Usuarios en la tabla t_detalle_usuario con llave cod_rol y foranea de Usuario cedula
    public function usuarios() {
        return $this->belongsToMany(Usuario::class,'t_detalle_usuario','cod_rol','cedula');
    }

    public function pantallas() {
        return $this->belongsToMany(Pantalla::class,'t_detalle_rol', 'cod_rol', 'cod_pantalla');
    }
}
