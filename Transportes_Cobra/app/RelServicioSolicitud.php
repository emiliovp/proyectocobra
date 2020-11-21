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
}
