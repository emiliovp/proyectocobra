<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class RelProveedorServicio extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',  
        'proveedor_id ',
        'cat_opciones_id',
    ];
    protected $table = "rel_proveedor_cat_opciones";
    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function setRelProvServicio($idprov, $idservicio){
        try {
            $servprov = new RelProveedorServicio;
            $servprov->proveedor_id = $idprov;
            $servprov->cat_opciones_id = $idservicio;
            $servprov->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function updRelProvServicio($id){
        try {
            RelProveedorServicio::where('proveedor_id', '=', $id)->delete();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
