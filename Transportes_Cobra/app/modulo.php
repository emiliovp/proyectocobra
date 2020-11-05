<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class modulo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'nombre', 
        'descripcion',
        'icono',
        'ruta',
        'padre',
        'estatus',
        'created_at'
    ];
    protected $table = "modulo";
    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function getmodulop(){
        $sql = modulo::where('padre','=', NULL)
        ->where('estatus','=', 1)
        ->get()
        ->toArray();
        return $sql;
    }
    public function getmoduloh($padre){
        $sql = modulo::where('padre','=', $padre)
        ->where('estatus','=', 1)
        ->get()
        ->toArray();
        return $sql;
    }
}
