<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\RelProveedorServicio;
use App\LogMovimiento;
use App\CalUserLogin;
use App\Clientes;
use Session;

class ClientesController extends Controller
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
            'descripcion' => 'El usuario '.$data[0]['username'].' visualizó lista de clientes.',
            'tipo' => 4,
            'id_user' => $id
        );
        $bitacora = new LogMovimiento;
        $bitacora->setMovimiento($data);
        return view('clientes.lista');
    }
    public function anyData()
    {
        $a = new clientes;
        $data = $a->getclientesactivos();

        return Datatables::of($data)->make(true);
    }
    public function altaCliente(){
        return view('clientes.nuevo');
    }
    public function stored(Request $request){
        $cliente = new Clientes;
        $request->validate([
            'nombre' => 'required',
            'responsable' => 'required',
            'ubicacion' => 'required',
        ]);
        $nombre = ($request->post('nombre') !='') ? substr($request->post('nombre'),0,255) : NULL;
        $rfc = ($request->post('rfc') !='') ? substr($request->post('rfc'),0,18) : NULL;
        $responsable = ($request->post('responsable') !='') ? substr($request->post('responsable'),0,255) : NULL;
        $telefono = ($request->post('telefono') !='') ? substr($request->post('telefono'),0,45) : NULL;
        $extension = ($request->post('extension') !='') ? substr($request->post('extension'),0,10) : NULL;
        $ubicacion = ($request->post('ubicacion') !='') ? substr($request->post('ubicacion'),0,255) : NULL;
        $data = array();
        $data['nombre'] = $nombre;
        $data['rfc'] = $rfc;
        $data['telefono'] = $telefono;
        $data['extension'] = $extension;
        $data['direccion'] = $ubicacion;
        $data['responsable'] = $responsable;
        //dd($request->post(),$data);
        /*$repetido = $cliente->getclienterepetido($nombre);
        if ($repetido == true) {
            Session::flash('excepcionerror', 'El cliente ya ha sido dado de alta');
            return redirect()->back()->withInput();
        }*/
        $idcliente = $cliente->setCliente($data);
        if ($idcliente == false) {
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }
        $usr = new CalUserLogin;
        $idusrlog = Auth::user()->usuario_id;
        $datausrlog = $usr->getuserid($idusrlog);
        $datalog = array(
        'ip_address' => $this->ip_cliente, 
        'descripcion' => 'El usuario '.$datausrlog[0]['username'].' dio de alta al cliente: '.$nombre.' con el ID: '.$idcliente.'.',
        'tipo' => 1,
        'id_user' => $idusrlog
        );
        $bitacora = new LogMovimiento;
        $bitacora->setMovimiento($datalog);
        Session::flash('success', 'La operación se ha realizado con exito');
        return redirect('clientes/lista');
    }
    public function bajaCliente(Request $request){
        $cliente = new clientes;
        $term = $request->post('id');
        if($cliente->bajaCliente($term) === true) {
            $a = new CalUserLogin;
            $idusrlog = Auth::user()->usuario_id;
            $datausrlog = $a->getuserid($idusrlog);
            $datalog = array(
            'ip_address' => $this->ip_cliente, 
            'descripcion' => 'El usuario '.$datausrlog[0]['username'].' ha dado de baja un cliente con el ID: '.$term.'.',
            'tipo' => 2,
            'id_user' => $idusrlog
            );
            $bitacora = new LogMovimiento;
            $bitacora->setMovimiento($datalog);
            return Response::json(true);
        }
        return Response::json(false);
    }
    public function updCliente(Request $request){
        $cliente = new clientes;
        $data = $cliente->getClientesActivosById($request->id);
        return view('clientes.editar')->with(['info' => $data[0]]);
    }
    public function modificacionCliente(Request $request){
        $cliente = new clientes;
        $request->validate([
            'nombre' => 'required',
            'responsable' => 'required',
            'ubicacion' => 'required',
        ]);
        $idCliente = $request->post('idCliente');
        $nombre = ($request->post('nombre') !='') ? substr($request->post('nombre'),0,255) : NULL;
        $rfc = ($request->post('rfc') !='') ? substr($request->post('rfc'),0,18) : NULL;
        $responsable = ($request->post('responsable') !='') ? substr($request->post('responsable'),0,255) : NULL;
        $telefono = ($request->post('telefono') !='') ? substr($request->post('telefono'),0,45) : NULL;
        $extension = ($request->post('extension') !='') ? substr($request->post('extension'),0,10) : NULL;
        $direccion = ($request->post('ubicacion') !='') ? substr($request->post('ubicacion'),0,255) : NULL;
        $result = $cliente->updateCliente($idCliente,$nombre,$rfc,$telefono,$extension,$direccion,$responsable);
        if ($result === false ) {
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }
        $mov = new LogMovimiento;
        $usr = new CalUserLogin;
        $idusrlog = Auth::user()->usuario_id;
        $datausrlog = $usr->getuserid($idusrlog);
        $datalog = array(
        'ip_address' => $this->ip_cliente, 
        'descripcion' => 'El usuario '.$datausrlog[0]['username'].' ha editado al cliente: '.$nombre.' con el ID: '.$idCliente.'.',
        'tipo' => 3,
        'id_user' => $idusrlog
        );
        $bitacora = new LogMovimiento;
        $bitacora->setMovimiento($datalog);
        Session::flash('success', 'La operación se ha realizado con exito');
        return redirect('clientes/lista');
    }
}
