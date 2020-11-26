<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Crypt;
use App\datosControlInventario;
use App\RelServicioSolicitud;
use App\RelClienteBodega;
use App\CatOpciones;
use App\Solicitud;
use App\Clientes;
use App\Bodega;

class ServicioController extends Controller
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
        return view('servicios.lista');  //->with(['alat' => 0]);
    }
    public function anyData()
    {
        $a = new Solicitud;
        $data = $a->getSolicitudInfo();

        return Datatables::of($data)->make(true);
    }
    public function atendersol(Request $request){
        $a = new Solicitud;
        $b = new RelServicioSolicitud;
        $c = new CatOpciones;
        $data = $a->getSolicitudInfoById($request->id);
        $servicios = $b->getServiciosById($request->id);
        $idservicios = array();
        foreach ($servicios as $key => $value) {
            $idservicios[$key] = $value['cat_opciones_id'];
            
        }
        $proveedores = $c->getServiciosByIdOp($idservicios);
        //dd($servicios,$proveedores);
        return view('servicios.servicios')->with(['data' => $data[0], 'servicios' => $servicios, 'proveedores'=> $proveedores]);
    }
}
