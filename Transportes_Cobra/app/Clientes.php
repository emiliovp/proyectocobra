<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
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
    protected $table = "cliente";
    protected $hidden = [];
    protected $primaryKey = 'id';

    public function getclientesactivos(){
        $sql = Clientes::where('estatus','=', 1)
        ->get()
        ->toArray();
        return $sql;
    }
    public function getclientesautocomplete($name){
        return Clientes::whereRaw("nombre LIKE '".$name."%'")->get()->toArray();
    }
}
