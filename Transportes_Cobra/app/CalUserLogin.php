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
    public function getuser_validate($usr){
        $sql= CalUserLogin::select(['usuario.id as id_usr',
            DB::raw('concat(usuario.nombre," ",usuario.aPaterno," ",usuario.aMaterno) as nombre_completo'),
            'usuario.username',
            'usuario.password',
            'perfil.id AS id_perfil',
            'perfil.nombre AS nperfil',
            'area.id AS id_area',
            'area.nombre AS narea'
        ])
        ->distinct()
        ->join('perfil', 'perfil.id', '=', 'usuario.perfil_id')
        ->join('area', 'area.id', '=', 'perfil.area_id')
        ->where('usuario.estatus', '=', 1)
        ->where('usuario.username', '=', $usr)
        ->get()
        ->toArray();
        return $sql;
    }
    public function getuserid($usr){
        $sql= CalUserLogin::select(['usuario.id as id_usr',
            'usuario.nombre',
            'usuario.aPaterno',
            'usuario.aMaterno',
            DB::raw('concat(usuario.nombre," ",usuario.aPaterno," ",usuario.aMaterno) as nombre_completo'),
            'usuario.username',
            'usuario.correo',
            'usuario.password',
            'usuario.telefono',
            'usuario.ext',
            'perfil.id AS id_perfil',
            'perfil.nombre AS nperfil',
            'area.id AS id_area',
            'area.nombre AS narea'
        ])
        ->distinct()
        ->join('perfil', 'perfil.id', '=', 'usuario.perfil_id')
        ->join('area', 'area.id', '=', 'perfil.area_id')
        ->where('usuario.estatus', '=', 1)
        ->where('usuario.id', '=', $usr)
        ->get()
        ->toArray();
        return $sql;
    }
    public function getuseridnameupdatevalidate($idusr, $cuenta){
        $sql= CalUserLogin::select(['usuario.id as id_usr',
            'usuario.nombre',
            'usuario.aPaterno',
            'usuario.aMaterno',
            DB::raw('concat(usuario.nombre," ",usuario.aPaterno," ",usuario.aMaterno) as nombre_completo'),
            'usuario.username',
            'usuario.correo',
            'usuario.password',
            'usuario.telefono',
            'usuario.ext',
            'perfil.id AS id_perfil',
            'perfil.nombre AS nperfil',
            'area.id AS id_area',
            'area.nombre AS narea'
        ])
        ->distinct()
        ->join('perfil', 'perfil.id', '=', 'usuario.perfil_id')
        ->join('area', 'area.id', '=', 'perfil.area_id')
        ->where('usuario.estatus', '=', 1)
        ->where('usuario.id', '<>', $idusr)
        ->whereRaw("usuario.username LIKE '".$cuenta."%'")
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
    public function getInfUsr($idusr, $id_mod = null){
        if ($id_mod == null) {
            $sql = CalUserLogin::select([
                DB::raw('CONCAT(usuario.nombre," ", usuario.aPaterno," ", usuario.aMaterno) as nombre_usuario'),
                'usuario.username','usuario.correo', 'usuario.telefono', 'usuario.ext', 'perfil.nombre AS nombre_perfil',
                'area.nombre AS nombre_area', 'modulo.nombre AS nombre_modulo', 'modulo.descripcion AS des_mod',
                'modulo.icono','modulo.ruta', 'modulo.padre'
            ])
            ->join('perfil','perfil.id', '=', 'usuario.perfil_id')
            ->join('area', 'area.id', '=', 'perfil.area_id')
            ->join('rel_perfil_modulo', 'rel_perfil_modulo.perfil_id', '=', 'perfil.id')
            ->join('modulo', 'modulo.id', '=', 'rel_perfil_modulo.modulo_id')
            ->where('usuario.estatus','=',1)
            ->where('perfil.estatus','=',1)
            ->where('usuario.id','=',$idusr)
            ->where('modulo.padre','=',NULL)
            ->where('modulo.ruta','<>',NULL)
            ->get()
            ->toArray();
        }else{
            $sql = CalUserLogin::select([
                DB::raw('CONCAT(usuario.nombre," ", usuario.aPaterno," ", usuario.aMaterno) as nombre_usuario'),
                'usuario.username','usuario.correo', 'usuario.telefono', 'usuario.ext', 'perfil.nombre AS nombre_perfil',
                'area.nombre AS nombre_area', 'modulo.nombre AS nombre_modulo', 'modulo.descripcion AS des_mod',
                'modulo.icono','modulo.ruta', 'modulo.padre'
            ])
            ->join('perfil','perfil.id', '=', 'usuario.perfil_id')
            ->join('area', 'area.id', '=', 'perfil.area_id')
            ->join('rel_perfil_modulo', 'rel_perfil_modulo.perfil_id', '=', 'perfil.id')
            ->join('modulo', 'modulo.id', '=', 'rel_perfil_modulo.modulo_id')
            ->where('usuario.estatus','=',1)
            ->where('perfil.estatus','=',1)
            ->where('usuario.id','=',$idusr)
            ->where('modulo.padre','=',$id_mod)
            ->where('modulo.ruta','<>',NULL)
            ->get()
            ->toArray();
        }
        return $sql;
    }
    public function updusuario($idusr, $data){
        try {
            date_default_timezone_set('America/Mexico_City');
            $updusuario = CalUserLogin::find($idusr);
            $updusuario->nombre = $data['nombre']; 
            $updusuario->aPaterno = $data['aPaterno']; 
            $updusuario->aMaterno = $data['aMaterno']; 
            $updusuario->username = $data['username']; 
            if ($data['password'] != null) {
                $updusuario->password = $data['password'];
            } 
            $updusuario->correo = $data['correo']; 
            $updusuario->telefono = $data['telefono']; 
            $updusuario->ext = $data['ext']; 
            $updusuario->perfil_id = $data['perfil_id'];
            $updusuario->updated_at = date('yy-m-d H:m:s');
            $updusuario->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function getUserByPerfil($perfil){
        return CalUserLogin::select([ 
            DB::raw('CONCAT(usuario.nombre," ", usuario.aPaterno," ", usuario.aMaterno) as nombre_usuario'), 
            'usuario.id AS idsectorista',
            'usuario.username', 
            'usuario.correo', 
            'usuario.telefono', 
            'usuario.ext', 
            'perfil.nombre AS perfil',
            'perfil.id AS idperfil',
            'area.nombre AS area'
            ])
            ->join('perfil', 'perfil.id', '=', 'usuario.perfil_id')
            ->join('area', 'area.id', '=', 'perfil.area_id')
            ->whereRaw("perfil.nombre LIKE '".$perfil."%'")
            ->get()
            ->toArray();
    }
}
