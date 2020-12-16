<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class RelClienteContrato extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'cliente_id', 
        'contrato_id ',
        'estatus'
    ];
    protected $table = "rel_cliente_contrato";
    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function setclientecontrato($cliente, $contrato_id){
        //try {
            $clientebodega = new RelClienteContrato;
            $clientebodega->cliente_id = $cliente;
            $clientebodega->contrato_id = $contrato_id;
            $clientebodega->save();
            $data = $clientebodega->id;
            return $data;
        /*} catch (\Throwable $th) {
            return false;
        }*/
    }
}
