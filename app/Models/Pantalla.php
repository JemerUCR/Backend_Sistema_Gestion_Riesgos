<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pantalla extends Model
{
    use HasFactory;

    protected $table = 't_pantalla';

    protected $primaryKey = 'cod_pantalla';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'cod_pantalla','nombre_pantalla'
    ]; //Datos ingresados de forma masiva

    protected $hidden = [];//Datos ocultos, no se mostraran

    //Relaciones Eloquent Laravel

    public function roles() {
        return $this->belongsToMany(Rol::class,'t_detalle_rol','cod_pantalla','cod_rol');
    }
}
