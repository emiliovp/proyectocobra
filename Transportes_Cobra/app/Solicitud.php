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
        'nombre', 
        'rfc ',
        'telefono',
        'extension',
        'dirección',
        'responsable',
        'estatus',
        'created_at',
        'updated_at'
    ];
    protected $table = "clientes";
    protected $hidden = [];
    protected $primaryKey = 'id';

    public function getSolicitud(){
        $sql = Solicitud::where('estatus','=', 1)
        ->get()
        ->toArray();
        return $sql;
    }
}
