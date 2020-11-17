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
}
