<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\RelProveedorServicio;
use App\LogMovimiento;
use App\CalUserLogin;
use App\CatOpciones;
use App\Proveedor;
use App\perfil; // quitar
use App\modulo; // quitar
use App\area;
use Session;

class ProveedoresController extends Controller
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
        $usr = new CalUserLogin;
        $id = Auth::user()->usuario_id;
        $data = $usr->getuserid($id);
        $data = array(
            'ip_address' => $this->ip_cliente, 
            'descripcion' => 'El usuario '.$data[0]['username'].' visualizó lista de proveedores.',
            'tipo' => 4,
            'id_user' => $id
        );
        $bitacora = new LogMovimiento;
        $bitacora->setMovimiento($data);
        return view('proveedores.lista');
    }
    public function anyData()
    {
        $a = new Proveedor;
        $data = $a->getproveedor();

        return Datatables::of($data)->make(true);
    }
    public function altaproveedor(){
        $a = new CatOpciones;
        $servicios = $a->getOptCatalogoByName('SERVICIOS');

        return view('proveedores.nuevo')->with(['servicios' => $servicios ]);
    }
    public function optserv(Request $request){
        $a = new CatOpciones;
        $opt = $a->getOptByIdOpcion($request->servicio);
        print_r(json_encode($opt));
    }
    public function stored(Request $request){
        $relprovserv= new RelProveedorServicio;
        $prov = new Proveedor;
        $request->validate([
            'nombreprov' => 'required',
            'hiddenTipoServicios' => 'required',
        ]);
        $nombre = ($request->post('nombreprov') !='') ? $request->post('nombreprov') : NULL;
        $descripcion = ($request->post('descipcion') !='') ? $request->post('descipcion') : NULL;
        $servicios = ($request->post('hiddenTipoServicios') !='') ? $request->post('hiddenTipoServicios') : NULL;
        $data = array();
        $data['nombre'] = substr($nombre,0,128);
        $data['descripcion'] = substr($descripcion,0,255);
        $repetido = $prov->getproveedorrepetido($nombre);
        if ($repetido == true) {
            Session::flash('excepcionerror', 'El proveedor ya ha sido dado de alta');
            return redirect()->back()->withInput();
        }
        $idprov = $prov->setProveedor($data);
        if ($idprov == false) {
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }
        $servicios = explode('_', $servicios);
        foreach ($servicios as $key => $value) {
            if ($relprovserv->setRelProvServicio($idprov, $value) == false) {
                Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
                return redirect()->back()->withInput();
            } 
        }
            $usr = new CalUserLogin;
            $idusrlog = Auth::user()->usuario_id;
            $datausrlog = $usr->getuserid($idusrlog);
            $datalog = array(
            'ip_address' => $this->ip_cliente, 
            'descripcion' => 'El usuario '.$datausrlog[0]['username'].' dio de alta al proveedor: '.$nombre.' con el ID: '.$idprov.'.',
            'tipo' => 1,
            'id_user' => $idusrlog
            );
            $bitacora = new LogMovimiento;
            $bitacora->setMovimiento($datalog);
        Session::flash('success', 'La operación se ha realizado con exito');
        return redirect('proveedores/lista');
    }
    public function bajaProv(Request $request){
        $b = new Proveedor;
        $term = $request->post('id');
        if($b->baja_prov($term) === true) {
            $a = new CalUserLogin;
            $idusrlog = Auth::user()->usuario_id;
            $datausrlog = $a->getuserid($idusrlog);
            $datalog = array(
            'ip_address' => $this->ip_cliente, 
            'descripcion' => 'El usuario '.$datausrlog[0]['username'].' ha dado de baja un proveedor con el ID: '.$term.'.',
            'tipo' => 2,
            'id_user' => $idusrlog
            );
            $bitacora = new LogMovimiento;
            $bitacora->setMovimiento($datalog);
            return Response::json(true);
        }
        return Response::json(false);
    }
    public function updproveedor(Request $request){
        $a = new CatOpciones;
        $servicios = $a->getOptCatalogoByName('SERVICIOS');
        $c = new Proveedor;
        $info = $c->getproveedorby($request->id);
        $servicios = $a->getProveedorServicio($request->id);
        $servicios = json_encode($servicios);
        $servicios = json_decode($servicios, true);
        $id_control = $a->getIdsProveedorServicio($request->id);
        $id_control = json_encode($id_control);
        $id_control = json_decode($id_control, true);
        $dataserv = $a->getOptCatalogoByName('SERVICIOS');
        return view('proveedores.editar')->with(['datos' => $info[0],'servicios' => $servicios, 'idcont' => $id_control[0], 'dataserv' => $dataserv]);
    }
    public function modificacionproveedor(Request $request){
        $prov = new Proveedor;
        $relprovserv= new RelProveedorServicio;
        $id = $request->post('proveedor_id');
        $nombre = ($request->post('nombreprov') !='') ? $request->post('nombreprov') : NULL;
        $provNombre = substr($nombre,0,128);
        $descripcion = ($request->post('descripcion') !='') ? $request->post('descripcion') : NULL;
        $provDescripcion = substr($nombre,0,255);
        $servicios = $request->post('hiddenTipoServicios');
        $updprov = $prov->updateProveedor($id,$provNombre,$provDescripcion);
        if ($updprov == false) {
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }
        $updprovserv = $relprovserv->updRelProvServicio($id);
        if ($updprovserv === false) {
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }
        $servicios = explode('_', $servicios);
        foreach ($servicios as $key => $value) {
            if ($relprovserv->setRelProvServicio($id, $value) == false) {
                Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
                return redirect()->back()->withInput();
            }
        }
        $mov = new LogMovimiento;
        $usr = new CalUserLogin;
        $idusrlog = Auth::user()->usuario_id;
        $datausrlog = $usr->getuserid($idusrlog);
        $datalog = array(
        'ip_address' => $this->ip_cliente, 
        'descripcion' => 'El usuario '.$datausrlog[0]['username'].' ha editado al proveedor: '.$provNombre.' con el ID: '.$id.'.',
        'tipo' => 3,
        'id_user' => $idusrlog
        );
        $bitacora = new LogMovimiento;
        $bitacora->setMovimiento($datalog);
        Session::flash('success', 'La operación se ha realizado con exito');
        return redirect('proveedores/lista');
    }
}
