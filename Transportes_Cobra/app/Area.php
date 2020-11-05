<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
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
    protected $table = "area";
    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function getarea(){
        $sql= Area::select([
            'id', 
            'nombre', 
            'descripcion',
            'estatus',
            'created_at',
            'updated_at'
            ])
        ->where('estatus','=', 1)
        ->get()
        ->toArray();
        return $sql;
    }
    public function getnombre($nombre){
        $sql = Area::Where('nombre', '=', $nombre)->where('estatus','=', 1)->first();

        if($sql == null) {
            return false;
        } else {
            return true;
        }
    }
    public function getnombrerepetido($nombre, $id){
        $sql = Area::Where('nombre', '=', $nombre)->where('estatus','=', 1)->where('id','<>',$id)->first();

        if($sql == null) {
            return false;
        } else {
            return true;
        }
    }
    public function guardararea($nombre,$descripcion) {
        $area = new Area;
        $area->nombre = mb_strtoupper($nombre);
        $area->descripcion = $descripcion;
        if($area->save()) {
            return true;
        }
        return false;
    }
    public function editarea($idAEditar, $nombre,$descripcion){
        $area = Area::find($idAEditar);
        $area->nombre = $nombre;
        $area->descripcion = $descripcion;
        $area->updated_at = date("Y-m-d H:m:i");;
        if($area->save()) {
            return true;
        }
        return false;
    }
    public function baja_area($val){
        date_default_timezone_set('America/Mexico_City');
        $data = Area::find($val);
        $data->estatus = 3;
        $data->updated_at = date('yy-m-d H:m:s');
        if($data->save()) {
            return true;
        }

        return false;
    }
}
