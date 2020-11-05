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
        'telefono',
        'ext',
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
        $sql= CalUserLogin::select(['usuario.id as id_usr',
            DB::raw('concat(usuario.nombre," ",usuario.aPaterno," ",usuario.aMaterno) as nombre_completo'),
            'usuario.username',
            'perfil.nombre AS nperfil',
            'area.nombre AS narea'
        ])
        ->distinct()
        ->join('perfil', 'perfil.id', '=', 'usuario.perfil_id')
        ->join('area', 'area.id', '=', 'perfil.area_id')
        ->where('usuario.estatus', '=', 1)
        ->get()
        ->toArray();
        return $sql;
    }
    public function setUsuario($datau){
        try{
            $data = CalUserLogin::create($datau);
            return $data->id;
        } catch (\Throwable $th){
            return false;
        }
    }
    public function getnombrerepetido($nombre){
        $sql = CalUserLogin::Where('username', '=', $nombre)->where('estatus','=', 1)->first();
        if($sql == null) {
            return false;
        } else {
            return true;
        }
    }
    public function baja_usr($val){
        date_default_timezone_set('America/Mexico_City');
        $data = CalUserLogin::find($val);
        $data->estatus = 3;
        $data->updated_at = date('yy-m-d H:m:s');
        if($data->save()) {
            return true;
        }

        return false;
    }
}
