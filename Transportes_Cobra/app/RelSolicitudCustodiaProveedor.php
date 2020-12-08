<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class RelSolicitudCustodiaProveedor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',  
        'placa ',
        'modelo',
        'nombre_custodio',
        'proveedor_id',
        'servicio_solicitud_id',
        'observaciones',
        'estatus',
        'created_at',
        'updated_at'
    ];
    protected $table = "rel_solicitud_custodia";
    protected $hidden = [];
    protected $primaryKey = 'id';

    public function setRelProvCustodiaSol($idrelsolcus,$proveedor,$placa,$modelo,$nombre, $observacion){

        //try {
            $relsersolprov = new RelSolicitudCustodiaProveedor;
            $relsersolprov->placa = ($placa == 0 ) ? NULL : $placa ;
            $relsersolprov->modelo = ($modelo == 0) ? NULL : $modelo ;
            $relsersolprov->nombre_custodio = $nombre;
            $relsersolprov->servicios_solicitud_id = $idrelsolcus;
            $relsersolprov->proveedor_id = $proveedor;
            $relsersolprov->observaciones = $observacion;
            $relsersolprov->save();
            return true;
        /*} catch (\Throwable $th) {
            return false;
        }*/
    }
}
