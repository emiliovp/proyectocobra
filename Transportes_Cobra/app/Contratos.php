<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Contratos extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'folioContrato', 
        'fechaInicio',
        'fechaTermino',
        'descripcionContrato',
        'estatus',
        'precio',
        'created_at',
        'updated_at'
    ];
    protected $table = "contrato";
    protected $hidden = [];
    protected $primaryKey = 'id';

    public function getcontratosactivos(){
        $sql = Contratos::select('contrato.id as idContrato', 
        'contrato.fechaInicio', 'contrato.fechaTermino',
            'contrato.descripcionContrato','contrato.estatus as estatuscont', 
            DB::raw("case 
                when contrato.estatus = 1 then 'ACTIVO'
                WHEN contrato.estatus = 2 then 'CANCELADO'
                WHEN contrato.estatus = 3 then 'VENCIDO'
            END AS estatusContrato"), 'contrato.created_at AS fechaAltaContrato', 
            'cliente.nombre as nombreCliente')
        ->join('rel_cliente_contrato', 'rel_cliente_contrato.contrato_id', '=', 'contrato.id')
        ->join('cliente','cliente.id','=','rel_cliente_contrato.cliente_id')
        ->where('cliente.estatus', '=', 1)
        ->get()
        ->toArray();
        return $sql;
    }
    public function getContratosActivosById($id){
        $sql = Contratos::select('contrato.id as idContrato', 
        'contrato.fechaInicio', 'contrato.fechaTermino','contrato.precio as preciocontrato', 
            'contrato.descripcionContrato','contrato.estatus as estatuscont',
            DB::raw("concat (bodega.clave, ' / ', case 
                when bodega.tipoBodega = 1 then 'Espacios compartidos'
                when bodega.tipoBodega = 2 then 'Espacio de un contenedor fijo'
                when bodega.tipoBodega = 3 then 'Mini bodegas'
                when bodega.tipoBodega = 4 then 'Espacios mayoreas a 180 mts cuadrados fijos'
                when bodega.tipoBodega = 5 then 'Cross dock' 
            end) as tipo_bodega"),
            DB::raw("case 
                when contrato.estatus = 1 then 'ACTIVO'
                WHEN contrato.estatus = 2 then 'CANCELADO'
                WHEN contrato.estatus = 3 then 'VENCIDO'
            END AS estatusContrato"), 'contrato.created_at AS fechaAltaContrato', 
            'cliente.nombre as nombreCliente')
        ->join('rel_cliente_contrato', 'rel_cliente_contrato.contrato_id', '=', 'contrato.id')
        ->join('cliente','cliente.id','=','rel_cliente_contrato.cliente_id')
        ->join('rel_cliente_bodega','rel_cliente_bodega.contrato_id', '=', 'contrato.id')
        ->join('bodega','bodega.id','=','rel_cliente_bodega.bodega_id')
        ->where('cliente.estatus', '=', 1)
        ->where('contrato.id', '=', $id)
        ->get()
        ->toArray();
        return $sql;
    }
    public function setContrato($data){
        try{
            $result = Contratos::create($data);
            return $result->id;
        } catch (\Throwable $th){
            return false;
        }
    }
    public function updateCliente($id,$nombre,$rfc,$telefono,$extension,$direccion,$responsable){
        date_default_timezone_set('America/Mexico_City');
        $data = Contratos::find($id);
        $data->nombre = $nombre;
        $data->rfc = $rfc;
        $data->telefono = $telefono;
        $data->extension = $extension;
        $data->direccion = $direccion;
        $data->responsable = $responsable;
        $data->updated_at = date('yy-m-d H:m:s');
        if($data->save()) {
            return true;
        }

        return false;
    }
    public function bajaContrato($val){
        date_default_timezone_set('America/Mexico_City');
        $data = Contratos::find($val);
        $data->estatus = 2;
        $data->updated_at = date('yy-m-d H:m:s');
        if($data->save()) {
            return true;
        }
        return false;
    }
}
