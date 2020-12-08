<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class RelPerfilModulo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'perfil_id', 
        'modulo_id'
    ];
    protected $table = "rel_perfil_modulo";
    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function setRelModPer($idp, $modulos){
        try {
            foreach ($modulos as $key => $value) {
                $relModPer = new RelPerfilModulo;
                $relModPer->perfil_id = $idp;
                $relModPer->modulo_id = $value;
                $relModPer->save();
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function updateModulos($id){
        try {
            // RelPerfilModulo::where('perfil_id', '=', $id)->whereNotIn('modulo_id',$modulos)->delete();
            RelPerfilModulo::where('perfil_id', '=', $id)->delete();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
