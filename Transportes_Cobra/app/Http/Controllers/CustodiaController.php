<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Crypt;
use App\RelServiciosSolicitudProveedor;
use App\datosControlInventario;
use App\RelServicioSolicitud;
use App\CalUserLogin;
use App\CatOpciones;
use App\Solicitud;

class CustodiaController extends Controller
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
        return view('custodias.lista');
    }
    public function anyData()
    {
        $a = new Solicitud;
        $data = $a->getSolicitudInfo();

        return Datatables::of($data)->make(true);
    }
    public function atendercustodia(Request $request){
        $a = new Solicitud;
        $b = new RelServicioSolicitud;
        $c = new CatOpciones;
        $d = new CalUserLogin;
        $data = $a->getSolicitudInfoById($request->id);
        $servicios = $b->getServiciosCustodiaById($request->id);
        $idservicios = array();
        //dd($servicios);
        foreach ($servicios as $key => $value) {
            $idservicios[$key] = $value['cat_opciones_id'];
            
        }
        $proveedores = $c->getServiciosByIdOp($idservicios);
        $sectorista = $d->getUserByPerfil('SECTORISTA');
        return view('custodias.custodia')->with(['data' => $data[0], 'servicios' => $servicios, 'proveedores'=> $proveedores, 'sectorista' =>$sectorista]);
    }
    public function storedCustodia(Request $request){

    }
}
