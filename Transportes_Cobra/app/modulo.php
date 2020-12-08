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
    public function getModuloByPerfil($perfil, $condicion = null, $tipoQry = null){
        if ($condicion == null) {
            $condicion = ' NULL';
        }else{
            $condicion = ' NOT NULL';
        }
        if ($tipoQry == null) {
            $query = modulo::query();
            $query = $query->select([DB::raw("GROUP_CONCAT(modulo.id separator '_')  as modulos")]);
        }elseif($tipoQry==1) {
            $query = modulo::query();
            $query = $query->select(["modulo.id as idModulo", "modulo.nombre as modulo"]);   
        }else {
            $query = DB::table('modulo as b');
            $query = $query->join('modulo','b.id','=','modulo.padre');
            $query = $query->select("modulo.id as idModulo", DB::raw("concat(b.nombre, ' / ', modulo.nombre) as modulo"));
        }
        $query->join('rel_perfil_modulo', 'rel_perfil_modulo.modulo_id', '=', 'modulo.id');
        $query->join('perfil','perfil.id', '=', 'rel_perfil_modulo.perfil_id');
        $query->where('perfil.id', '=', $perfil);
        $query->whereRaw('modulo.padre IS'. $condicion);
        return $query->get()->toArray();
    }
}
