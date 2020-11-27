<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class RelServiciosSolicitudProveedor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'servicios_solicitud_id', 
        'proveedor_id ',
        'descripcion',
        'adicional',
        'estatus',
        'created_at',
        'updated_at'
    ];
    protected $table = "rel_servicios_solicitud_has_proveedor";
    protected $hidden = [];
    protected $primaryKey = 'id';

    public function setRelProvServicioSol($idrelsolser,$proveedor,$descripcion){
        //try {
            $relsersolprov = new RelServiciosSolicitudProveedor;
            $relsersolprov->servicios_solicitud_id = $idrelsolser;
            $relsersolprov->proveedor_id = $proveedor;
            $relsersolprov->descripcion = $descripcion;
            $relsersolprov->save();
            return true;
        /*} catch (\Throwable $th) {
            return false;
        }*/
    }
}
