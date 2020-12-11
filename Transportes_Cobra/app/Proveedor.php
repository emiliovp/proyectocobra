<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'nombre', 
        'descripcion',
        'estatus',
        'created_at',
        'updated_at'
    ];
    protected $table = "proveedor";
    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function getproveedor(){
        $sql= Proveedor::where('estatus','=', 1)
        ->get()
        ->toArray();
        return $sql;
    }
    public function getproveedorby($id){
        $sql= Proveedor::where('estatus','=', 1)->where('id',$id)
        ->get()
        ->toArray();
        return $sql;
    }
    public function setProveedor($datp){
        try{
            $data = Proveedor::create($datp);
            return $data->id;
        } catch (\Throwable $th){
            return false;
        }
    }
    public function baja_perfil($val){
        date_default_timezone_set('America/Mexico_City');
        $data = Proveedor::find($val);
        $data->estatus = 3;
        $data->updated_at = date('yy-m-d H:m:s');
        if($data->save()) {
            return true;
        }

        return false;
    }
    public function getproveedorrepetido($nombre){
        $sql = Proveedor::Where('nombre', '=', $nombre)->where('estatus','=', 1)->first();
        if($sql == null) {
            return false;
        } else {
            return true;
        }
    }
    public function baja_prov($val){
        date_default_timezone_set('America/Mexico_City');
        $data = Proveedor::find($val);
        $data->estatus = 3;
        $data->updated_at = date('yy-m-d H:m:s');
        if($data->save()) {
            return true;
        }
        return false;
    }
    public function updateProveedor($id,$provNombre,$provDescripcion){
        date_default_timezone_set('America/Mexico_City');
        $data = Proveedor::find($id);
        $data->nombre = $provNombre;
        $data->descripcion = $provDescripcion;
        $data->updated_at = date('yy-m-d H:m:s');
        if($data->save()) {
            return true;
        }

        return false;
    }
}
