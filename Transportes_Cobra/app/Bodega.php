<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'R', 
        'tipoBodega',
        'dimensiones',
        'estatus',
        'created_at',
        'updated_at'
    ];
    protected $table = "bodega";
    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function getbodegabycliente($id){
        return Bodega::join('rel_cliente_bodega', 'rel_cliente_bodega.bodega_id', '=', 'bodega.id')
        ->where('rel_cliente_bodega.cliente_id', '=', $id)->get()->toArray();
    }
    public function getbodegaslibres(){
        return bodega::where('estatus','=',1)
        ->whereRaw('bodega.id NOT IN (SELECT rel_cliente_bodega.bodega_id FROM rel_cliente_bodega WHERE estatus = 1)')
        ->get()->toArray();
    }
    public function getbodegaslibresdesc(){
        return bodega::select('id', 'clave','dimensiones',
            DB::raw("case 
            when tipoBodega = 1 then 'Espacios compartidos'
            when tipoBodega = 2 then 'Espacio de un contenedor fijo'
            when tipoBodega = 3 then 'Mini bodegas'
            when tipoBodega = 4 then 'Espacios mayoreas a 180 mts cuadrados fijos'
            when tipoBodega = 5 then 'Cross dock' 
            end as tipo_bodega"),
            DB::raw("concat(clave,' / ', case 
            when tipoBodega = 1 then 'Espacios compartidos'
            when tipoBodega = 2 then 'Espacio de un contenedor fijo'
            when tipoBodega = 3 then 'Mini bodegas'
            when tipoBodega = 4 then 'Espacios mayoreas a 180 mts cuadrados fijos'
            when tipoBodega = 5 then 'Cross dock' 
            end) nombreBodega")
        )
        ->where('estatus','=',1)
        ->whereRaw('bodega.id NOT IN (SELECT rel_cliente_bodega.bodega_id FROM rel_cliente_bodega WHERE estatus = 1)')
        ->get()->toArray();
    }
}
