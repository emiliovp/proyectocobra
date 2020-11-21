<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class datosControlInventario extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'descripcion', 
        'contenedor ',
        'cantidad',
        'tipoProducto',
        'notasAd',
        'solicitud_id',
        'created_at',
        'updated_at'
    ];
    protected $table = "datosControlInv";
    protected $hidden = [];
    protected $primaryKey = 'id';

    public function setcontrolinv($data){
        try {
            $data = datosControlInventario::create($data);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
