<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Response;

use App\RelServiciosSolicitudProveedor;
use App\datosControlInventario;
use App\RelServicioSolicitud;
use App\LogMovimiento;
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
        $this->middleware('auth');
        $this->ip_cliente = ipAddress();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $mov = new LogMovimiento;
        $usr = new CalUserLogin;
        $id = Auth::user()->usuario_id;
        $data = $usr->getuserid($id);
        $data = array(
            'ip_address' => $this->ip_cliente, 
            'descripcion' => 'El usuario '.$data[0]['username'].' visualizó lista de operaciones de servicio.',
            'tipo' => 4,
            'id_user' => $id
        );
        $bitacora = new LogMovimiento;
        $bitacora->setMovimiento($data);
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
        $usr = new CalUserLogin;
        $idusrlog = Auth::user()->usuario_id;
        $datausrlog = $usr->getuserid($idusrlog);
        $datalog = array(
        'ip_address' => $this->ip_cliente, 
        'descripcion' => 'El usuario '.$datausrlog[0]['username'].' realizó la asignación de  servicios a la solicitud con el folio:'.$sol_id.'.',
        'tipo' => 1,
        'id_user' => $idusrlog
        );
        $bitacora = new LogMovimiento;
        $bitacora->setMovimiento($datalog);
        Session::flash('success', 'La operación se ha realizado con exito');
        return redirect('servicio/lista');

    }
}
