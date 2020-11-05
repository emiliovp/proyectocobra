<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class perfil extends Model
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
        'area_id',
        'estatus',
        'created_at',
        'updated_at'
    ];
    protected $table = "perfil";
    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function getperfil(){
        $sql= perfil::select([
            'perfil.id AS perfil_id',
            'perfil.nombre AS nombre_perfil',  
            'area.nombre AS area',
            DB::raw('concat(perfil.nombre," / ", area.nombre) AS per_area'),
        ])
        ->join('area', 'area.id', '=', 'perfil.area_id')
        ->where('perfil.estatus','=', 1)
        ->get()
        ->toArray();
        return $sql;
    }
    public function setPerfil($datp){
        try{
            $data = perfil::create($datp);
            return $data->id;
        } catch (\Throwable $th){
            return false;
        }
    }
    public function baja_perfil($val){
        date_default_timezone_set('America/Mexico_City');
        $data = perfil::find($val);
        $data->estatus = 3;
        $data->updated_at = date('yy-m-d H:m:s');
        if($data->save()) {
            return true;
        }

        return false;
    }
}
