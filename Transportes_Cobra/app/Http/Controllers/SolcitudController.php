<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;

use App\CatOpciones;
use App\Solicitud;
use App\Clientes;
use App\Bodega;

class SolcitudController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('solicitud.lista');  //->with(['alat' => 0]);
    }
    public function anyData()
    {
        $a = new Area;
        $data = $a->getarea();

        return Datatables::of($data)->make(true);
    }
    public function nuevaSolicitud(){
        $cat= new CatOpciones;
        $tmovimiento = $cat->getOptCatalogoByName('TIPO DE MOVIMIENTO');
        $tservico = $cat->getOptCatalogoByName('SERVICIOs');
        return view('solicitud.nuevaSolicitud')->with(['movimiento' => $tmovimiento, 'servicio' =>$tservico]);
    }
    public function autocomplete(Request $request){
        $term = $request->get('term');
        $term = (isset($term)) ? $term : 0 ;
        if($term === '0') {
            return $dataclientes[] = array(
                'respuesta' => 'No se encontro el registro'
            );
        }else{
            $clientes = new Clientes;
            $dataclientes = $clientes->getclientesautocomplete($term);
            if (empty($dataclientes)) {
                return $dataclientes[] = array('respuesta'=>'No se encontro el registro');
            }
            if (count($dataclientes) > 0) {
                return $dataclientes;
            } else if($data==null) {
                return $dataclientes[] = array('respuesta'=>'No se encontro el registro');
            }
        }
    }
    public function getbodegas(Request $request){
        $param = $request->get('idcliente');
        $cliente = (isset($param)) ? $param : NULL ;
        $conb = new Bodega;
        $bodegas = $conb->getbodegabycliente($cliente);
        if ($bodegas == null) {
            $bodegas = $conb->getbodegaslibres();
        }
        return $bodegas;
    }
    public function getopt(Request $request){
        $a = new CatOpciones;
        $modh = $a->getOptByIdOpcion($request->option);
        print_r(json_encode($modh));
    }
}
