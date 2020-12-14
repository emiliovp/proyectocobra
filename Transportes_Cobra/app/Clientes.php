<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'nombre', 
        'rfc',
        'telefono',
        'extension',
        'direccion',
        'responsable',
        'estatus',
        'created_at',
        'updated_at'
    ];
    protected $table = "cliente";
    protected $hidden = [];
    protected $primaryKey = 'id';

    public function getclientesactivos(){
        $sql = Clientes::select('id', 'nombre', 'rfc', 'telefono', 'extension', 'direccion', 'responsable', 
        DB::raw("case
                when estatus = 1 then 'activo'
                when estatus = 2 then 'bloqueado'
                when estatus = 3 then 'Baja logica'
        end AS estatus"),
        'created_at')->where('estatus','=', 1)
        ->get()
        ->toArray();
        return $sql;
    }
    public function getClientesActivosById($id){
        $sql = Clientes::select('id', 'nombre', 'rfc', 'telefono', 'extension', 'direccion', 'responsable', 
        DB::raw("case
                when estatus = 1 then 'activo'
                when estatus = 2 then 'bloqueado'
                when estatus = 3 then 'Baja logica'
        end AS estatus"),
        'created_at')->where('estatus','=', 1)
        ->where('id','=', $id)
        ->get()
        ->toArray();
        return $sql;
    }
    public function getclientesautocomplete($name){
        return Clientes::whereRaw("nombre LIKE '".$name."%'")->get()->toArray();
    }
    public function getclienterepetido($nombre){
        $sql = Clientes::Where('nombre', '=', $nombre)->where('estatus','=', 1)->first();
        if($sql == null) {
            return false;
        } else {
            return true;
        }
    }
    public function setCliente($data){
        try{
            $result = Clientes::create($data);
            return $result->id;
        } catch (\Throwable $th){
            return false;
        }
    }
    public function updateCliente($id,$nombre,$rfc,$telefono,$extension,$direccion,$responsable){
        date_default_timezone_set('America/Mexico_City');
        $data = Clientes::find($id);
        $data->nombre = $nombre;
        $data->rfc = $rfc;
        $data->telefono = $telefono;
        $data->extension = $extension;
        $data->direccion = $direccion;
        $data->responsable = $responsable;
        $data->updated_at = date('yy-m-d H:m:s');
        if($data->save()) {
            return true;
        }

        return false;
    }
    public function bajaCliente($val){
        date_default_timezone_set('America/Mexico_City');
        $data = Clientes::find($val);
        $data->estatus = 3;
        $data->updated_at = date('yy-m-d H:m:s');
        if($data->save()) {
            return true;
        }
        return false;
    }
}
