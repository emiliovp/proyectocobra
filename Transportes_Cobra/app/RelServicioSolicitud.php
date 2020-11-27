<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class RelServicioSolicitud extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'solicitud_id', 
        'proveedor ',
        'cat_opciones_id',
        'notasAdicionales',
        'complemento_1',
        'complemento_2',
        'created_at',
        'updated_at'
    ];
    protected $table = "servicios_solicitud";
    protected $hidden = [];
    protected $primaryKey = 'id';

    public function setserviciosol($idsol,$notas,$servicio){
        try {
            $sersol = new RelServicioSolicitud;
            $sersol->solicitud_id = $idsol;
            $sersol->cat_opciones_id = $servicio;
            $sersol->notasAdicionales = $notas;
            $sersol->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function getServiciosById($id){
        return RelServicioSolicitud::select('servicios_solicitud.id AS control_servicio',
        'servicios_solicitud.solicitud_id AS folio',
        'servicios_solicitud.cat_opciones_id',
        'b.id as idservicio',
        'b.nombre as servicio',
        DB::raw('CONCAT(b.nombre," ",cat_opciones.nombre) AS servicios_solicitud')
        )
        ->join('cat_opciones', 'cat_opciones.id', '=', 'servicios_solicitud.cat_opciones_id')
        ->join('cat_opciones as b', 'b.id', '=', 'cat_opciones.cat_opciones_id')
        ->where('servicios_solicitud.solicitud_id', '=', $id)
        ->get()
        ->toArray();
    }
    
}
