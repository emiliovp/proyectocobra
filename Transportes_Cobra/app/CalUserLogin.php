<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CalUserLogin extends Model
{
    public $tipo = null;
    protected $table = "usuario";

    protected $fillable = [
        'id',
        'nombre',
        'aPaterno',
        'aMaterno',
        'username',
        'password',
        'correo',
        'remember_token',
        'sesion_id',
        'perfil_id',
        'estatus',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public function getuser(){
        $sql= CalUserLogin::select([

            DB::raw('concat(usuario.nombre," ",usuario.aPaterno," ",usuario.aMaterno) as nombre_completo'),
            'usuario.username',
            'perfil.nombre AS nperfil',
            'area.nombre AS narea'
        ])
        ->join('perfil', 'perfil.id', '=', 'usuario.perfil_id')
        ->join('area', 'area.id', '=', 'perfil.area_id')
        ->get()
        ->toArray();
        return $sql;
    }
}
