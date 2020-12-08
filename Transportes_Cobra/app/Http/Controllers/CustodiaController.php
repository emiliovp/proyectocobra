<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Crypt;
use App\RelSolicitudCustodiaProveedor;
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
        foreach ($servicios as $key => $value) {
            $idservicios[$key] = $value['cat_opciones_id'];
            
        }
        $proveedores = $c->getServiciosByIdOp($idservicios);
        $sectorista = $d->getUserByPerfil('SECTORISTA');
        return view('custodias.custodia')->with(['data' => $data[0], 'servicios' => $servicios, 'proveedores'=> $proveedores, 'sectorista' =>$sectorista]);
    }
    public function storedCustodia(Request $request){
        $a = new RelServicioSolicitud;
        $b = new RelSolicitudCustodiaProveedor;
        $sol = new Solicitud;
        $sol_id = $request->post('sol_id');
        $servicios = $a->getServiciosCustodiaById($sol_id);
        //dd($request->post());
        foreach ($servicios as $key => $value) {
            $servicio = $value['control_servicio'];
            $proveedor = $request->post('hiddenProveedor_'.$servicio);
            $placa = $request->post('hiddenPlaca_'.$servicio);
            $modelo = $request->post('hiddenModelo_'.$servicio);
            $nombre = $request->post('hiddenNombre_'.$servicio);
            $proveedores = explode('_', $proveedor);
            $placas = explode('_', $placa);
            $modelos = explode('_', $modelo);
            $nombres = explode('_', $nombre);
            $descripciones = $request->post('observacion');
            $fecha = $request->post('fecha');
            $dataProvSol = array();
            foreach ($proveedores as $key => $prov) {
                $result = $b->setRelProvCustodiaSol($servicio,$prov,$placas[$key],$modelos[$key],$nombres[$key], $descripciones);
                if ($result == false) {
                    Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
                    return redirect()->back()->withInput();
                }
            }
        }
        Session::flash('success', 'La operación se ha realizado con exito');
        return redirect('custodia/lista');
    }
}
