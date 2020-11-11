<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'nombre', 
        'estatus',
        'created_at'
    ];
    protected $table = "catalogo";
    protected $hidden = [];
    protected $primaryKey = 'id';

    public function getcatalogosactivos(){
        $sql = Catalogo::where('estatus','=', 1)
        ->get()
        ->toArray();
        return $sql;
    }
    public function getcatalogosactivosexcep($id){
        $sql = Catalogo::where('estatus','=', 1)
        ->where('id','<>',$id)
        ->get()
        ->toArray();
        return $sql;
    }
    public function getnombre($nombre){
        $sql = Catalogo::Where('nombre', '=', $nombre)->where('estatus','=', 1)->first();

        if($sql == null) {
            return false;
        } else {
            return true;
        }
    }
    public function setcatalogo($nombre) {
        $catalogo = new Catalogo;
        $catalogo->nombre = mb_strtoupper($nombre);
        if($catalogo->save()) {
            return true;
        }
        return false;
    }
    public function getnombrerepetido($nombre, $id){
        $sql = Catalogo::Where('nombre', '=', $nombre)->where('estatus','=', 1)->where('id','<>',$id)->first();
        if($sql == null) {
            return false;
        } else {
            return true;
        }
    }
    public function editarcatalogo($idAEditar, $nombre){
        $catalogo = Catalogo::find($idAEditar);
        $catalogo->nombre = $nombre;
        $catalogo->updated_at = date("Y-m-d H:m:i");;
        if($catalogo->save()) {
            return true;
        }
        return false;
    }
    public function baja_catalogo($val){
        date_default_timezone_set('America/Mexico_City');
        $data = Catalogo::find($val);
        $data->estatus = 3;
        $data->updated_at = date('yy-m-d H:m:s');
        if($data->save()) {
            return true;
        }

        return false;
    }
    public function getcatalogosByOpt($idopt){
        return Catalogo::select(['catalogo.id', 
            'catalogo.nombre', 
            'catalogo.estatus'])
            ->leftjoin('cat_opciones', 'cat_opciones.catalogo_id', '=', 'catalogo.id')
            ->where('cat_opciones.id','=', $idopt)
            ->get()
            ->toArray();
    }
}
