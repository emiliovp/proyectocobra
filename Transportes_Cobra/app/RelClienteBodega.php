<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class RelClienteBodega extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'cliente_id', 
        'bodega_id ',
        'estatus'
    ];
    protected $table = "rel_cliente_bodega";
    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function getBodegaCliente($cliente, $bodega_id){
        $sql = RelClienteBodega::where('cliente_id','=', $cliente)
        ->where('bodega_id','=', $bodega_id)
        ->get()
        ->toArray();
        return $sql;
    }
    public function setbodegacliente($cliente, $bodega_id){
        try {
            $clientebodega = new RelClienteBodega;
            $clientebodega->cliente_id = $cliente;
            $clientebodega->bodega_id = $bodega_id;
            $clientebodega->save();
            $data = $clientebodega->id;
            return $data;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
