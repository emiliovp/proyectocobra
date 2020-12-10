<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class LogMovimiento extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'ip_address', 
        'descripcion',
        'tipo',
        'usuario_id',
        'created_at',
    ];
    protected $table = "log_movimientos";
    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function setMovimiento($data){
        $bitacora = new LogMovimiento;
        $bitacora->ip_address = $data["ip_address"];
        $bitacora->descripcion = $data["descripcion"];
        $bitacora->tipo = $data["tipo"];
        $bitacora->usuario_id = $data["id_user"];

        $bitacora->save();
    }
}
