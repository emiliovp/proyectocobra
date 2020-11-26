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
    public function getSolicitudInfo(){
        return Solicitud::select(['solicitud.id AS folio','solicitud.fechaHoraProgramada', 
        'solicitud.tipoMercancia', 
        'solicitud.lugarSalida', 
        'solicitud.destino',
        'cat_opciones.nombre AS tmovimiento', 
        DB::raw("CASE
        WHEN solicitud.estatus = 1 THEN 'Programada'
        WHEN solicitud.estatus = 2 THEN 'En ejecución'
        WHEN solicitud.estatus = 2 THEN 'Cerrada'
        END AS estatus"),'solicitud.estatus AS estado', 
        'solicitud.created_at AS fecha_inicio', 
        'solicitud.updated_at AS fecha_termino', 'cliente.nombre AS cliente', 'bodega.clave'
        ])
        ->join('rel_cliente_bodega', 'rel_cliente_bodega.id', '=', 'solicitud.rel_cliente_bodega_id')
        ->join('cliente', 'cliente.id', '=', 'rel_cliente_bodega.cliente_id')
        ->join('bodega', 'bodega.id', '=', 'rel_cliente_bodega.bodega_id')
        ->join('cat_opciones', 'cat_opciones.id', '=', 'solicitud.tipo_movimiento')
        // ->where('solicitud.estatus', '<>', 3)
        ->get()
        ->toArray();
    }
    public function getSolicitudInfoById($id){
        return Solicitud::select(['solicitud.id AS folio','solicitud.fechaHoraProgramada', 
        'solicitud.tipoMercancia', 
        'solicitud.lugarSalida', 
        'solicitud.destino',
        'cat_opciones.nombre AS tmovimiento', 
        DB::raw("CASE
        WHEN solicitud.estatus = 1 THEN 'Programada'
        WHEN solicitud.estatus = 2 THEN 'En ejecución'
        WHEN solicitud.estatus = 2 THEN 'Cerrada'
        END AS estatus"),'solicitud.estatus AS estado', 
        'solicitud.created_at AS fecha_inicio', 
        'solicitud.updated_at AS fecha_termino', 'cliente.nombre AS cliente', 'bodega.clave'
        ])
        ->join('rel_cliente_bodega', 'rel_cliente_bodega.id', '=', 'solicitud.rel_cliente_bodega_id')
        ->join('cliente', 'cliente.id', '=', 'rel_cliente_bodega.cliente_id')
        ->join('bodega', 'bodega.id', '=', 'rel_cliente_bodega.bodega_id')
        ->join('cat_opciones', 'cat_opciones.id', '=', 'solicitud.tipo_movimiento')
        ->where('solicitud.id', '=', $id)
        ->get()
        ->toArray();
    }
}
