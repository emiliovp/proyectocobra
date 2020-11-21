<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'fechaHoraProgramada', 
        'tipoMercancia ',
        'lugarSalida',
        'destino',
        'tipo_movimiento',
        'rel_cliente_bodega_id',
        'estatus',
        'created_at',
        'updated_at'
    ];
    protected $table = "solicitud";
    protected $hidden = [];
    protected $primaryKey = 'id';

    public function getSolicitud(){
        $sql = Solicitud::where('estatus','=', 1)
        ->get()
        ->toArray();
        return $sql;
    }
    public function setsolicitud($data){
        try{
            $data = Solicitud::create($data);
            return $data->id;
        } catch (\Throwable $th){
            return false;
        }
    }
}
