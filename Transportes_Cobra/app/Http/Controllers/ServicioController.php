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
        $d = new CalUserLogin;
        $data = $a->getSolicitudInfoById($request->id);
        $servicios = $b->getServiciosById($request->id);
        $idservicios = array();
        foreach ($servicios as $key => $value) {
            $idservicios[$key] = $value['cat_opciones_id'];
            
        }
        $proveedores = $c->getServiciosByIdOp($idservicios);
        $sectorista = $d->getUserByPerfil('SECTORISTA');
        return view('servicios.servicios')->with(['data' => $data[0], 'servicios' => $servicios, 'proveedores'=> $proveedores, 'sectorista' =>$sectorista]);
    }
    public function storedProveedores(Request $request){
        $a = new RelServicioSolicitud;
        $b = new RelServiciosSolicitudProveedor;
        $sol = new Solicitud;
        $sol_id = $request->post('sol_id');
        $servicios = $a->getServiciosById($sol_id);
        foreach ($servicios as $key => $value) {
            $servicio = $value['control_servicio'];
            $proveedor = $request->post('hiddenProveedor_'.$servicio);
            $descripcion = $request->post('hiddenDescripcion_'.$servicio);
            $proveedores = explode('_', $proveedor);
            $descripciones = explode('_',$descripcion);
            $dataProvSol = array();
            foreach ($proveedores as $key => $prov) {
                /*$dataProvSol['servicios_solicitud_id'] = $servicio;
                $dataProvSol['proveedor_id'] = $prov;
                $dataProvSol['descripcion'] = $descripciones[$key];
                //$dataProvSol['adicional'] = $servicio;
                dd($request->post(),$dataProvSol);*/
                $result = $b->setRelProvServicioSol($servicio,$prov,$descripciones[$key]);
                if ($result == false) {
                    Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
                    return redirect()->back()->withInput();
                }
            }
        }
        $resultsol = $sol->updateSectStatus($sol_id,2,$request->post('sectorista'));
        if ($resultsol == false) {
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }
        Session::flash('success', 'La operación se ha realizado con exito');
        return redirect('servicio/lista');

    }
}
