<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CatOpciones extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'nombre',
        'jerarquia',
        'cat_opciones_id', 
        'estatus',
        'catalogo_id',
        'created_at',
        'updated_at'
    ];
    protected $table = "cat_opciones";
    protected $hidden = [];
    protected $primaryKey = 'id';

    public function getcatalogosactivos($id){
        return DB::table('cat_opciones as a')
        ->leftjoin('cat_opciones as b','b.id','=','a.cat_opciones_id')
        ->select('a.id', 
            'a.jerarquia',
            'a.created_at',
            'a.catalogo_id',
        DB::raw('if(a.cat_opciones_id is null, a.nombre, concat(b.nombre," - ", a.nombre)) as nombre'),
        DB::raw('if(a.cat_opciones_id is null, "--",concat(b.nombre," - ", a.nombre)) as dependencia'),
        DB::raw('CASE
            WHEN a.estatus = 1 THEN "Activo"
            WHEN a.estatus = 2 THEN "Bloqueado"
            ELSE "Baja"
            END AS estatus')
        )
        ->where('a.catalogo_id', '=', $id)
        ->where('a.estatus','=',1)
        ->get()
        ->toArray();
    }
    public function getOptCatalogoById($id) {
        return CatOpciones::where('id', '=', $id)
        ->first()
        ->toArray();
    }
    public function getOptCatalogoByName($catalogo) {
        return CatOpciones::select('cat_opciones.id','cat_opciones.nombre')
        ->join('catalogo','catalogo.id','=','cat_opciones.catalogo_id')
        ->whereRaw("catalogo.nombre LIKE '".$catalogo."%'")
        ->where('catalogo.estatus','=',1)
        ->where('cat_opciones.estatus','=',1)
        ->groupBy('cat_opciones.nombre')
        ->groupBy('cat_opciones.id')
        ->get()
        ->toArray();
    }
    public function guardarOpt($nomb, $idCat, $idOptCat = null, $jerarquia) {
        try {
            $opt = new CatOpciones;
            $opt->nombre = $nomb;
            $opt->catalogo_id = $idCat;
            $opt->jerarquia = $jerarquia;
            $opt->cat_opciones_id = $idOptCat;
            $opt->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function getOptCatalogoByIdOpcion($id) {
        return CatOpciones::where('catalogo_id', '=', $id)
        ->get()
        ->toArray();
    }
    public function getOptByIdOpcion($id) {
        return CatOpciones::where('cat_opciones_id', '=', $id)
        ->get()
        ->toArray();
    }
    public function setedicionopt($idAEditar, $nomb, $idCat, $idOptCat = null, $jerarquia) {
        try {
            $opt = CatOpciones::find($idAEditar);
            $opt->nombre = $nomb;
            $opt->catalogo_id = $idCat;
            $opt->jerarquia = $jerarquia;
            $opt->cat_opciones_id = $idOptCat;
            $opt->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function eliminacionLogica($id) {
        $optcat = CatOpciones::find($id);
        $optcat->estatus = 3;
        if($optcat->save()) {
            return true;
        }
        return false;
    }
}
