<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\RelClienteContrato;
use App\RelClienteBodega;
use App\LogMovimiento;
use App\CalUserLogin;
use App\Contratos;
use App\Bodega;
use Session;

class ContratosController extends Controller
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
            'descripcion' => 'El usuario '.$data[0]['username'].' visualizó lista de contratos.',
            'tipo' => 4,
            'id_user' => $id
        );
        $bitacora = new LogMovimiento;
        $bitacora->setMovimiento($data);
        return view('contratos.lista');
    }
    public function anyData()
    {
        $a = new Contratos;
        $data = $a->getcontratosactivos();

        return Datatables::of($data)->make(true);
    }
    public function altaCliente(){
        $bodega = new Bodega;
        $databod = $bodega->getbodegaslibresdesc();
        return view('contratos.nuevo')->with(['bodega' => $databod]);
    }
    public function stored(Request $request){
        $bodega = new Bodega;
        $contrato = new Contratos;
        $relclientecontrato = new RelClienteContrato;
        $a = new RelClienteBodega;
        $request->validate([
            'cliente' => 'required',
            'bodega' => 'required',
            'precio' => 'required',
            'finicio' => 'required',
            'ftermino' => 'required'
        ]);
        $idcliente = ($request->post('idcliente') !='') ? $request->post('idcliente') : NULL;
        $idbodega = ($request->post('bodega') !='') ? $request->post('bodega') : NULL;
        $precio = ($request->post('precio') !='') ? $request->post('precio') : NULL;
        $finicio = ($request->post('finicio') !='') ? $request->post('finicio') : NULL;
        $ftermino = ($request->post('ftermino') !='') ? $request->post('ftermino'): NULL;
        $observaciones = ($request->post('observaciones') !='') ? $request->post('observaciones') : NULL;
        $datacontrato = array();
        $datacontrato['fechaInicio'] = $finicio;
        $datacontrato['fechaTermino'] = $ftermino;
        $datacontrato['descripcionContrato'] = $observaciones;
        $datacontrato['precio'] = $precio;
        $idcontrato= $contrato->setContrato($datacontrato);
        if ($idcontrato === false ) {
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }
        $relcontrato = array();
        $relclientebodega = array();
        $resultrel1 = $relclientecontrato->setclientecontrato($idcliente,$idcontrato);
        if ($resultrel1 === false ) {
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }
        $resultrel2 = $a->setbodegacliente($idcliente,$idbodega,$idcontrato);
        if ($resultrel2 === false ) {
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }
        $usr = new CalUserLogin;
        $idusrlog = Auth::user()->usuario_id;
        $datausrlog = $usr->getuserid($idusrlog);
        $datalog = array(
        'ip_address' => $this->ip_cliente, 
        'descripcion' => 'El usuario '.$datausrlog[0]['username'].' dio de alta un contrato con el ID: '.$idcontrato.'.',
        'tipo' => 1,
        'id_user' => $idusrlog
        );
        $bitacora = new LogMovimiento;
        $bitacora->setMovimiento($datalog);
        Session::flash('success', 'La operación se ha realizado con exito');
        return redirect('contratos/lista');
    }
    public function bajaContrato(Request $request){
        $contrato = new Contratos;
        $term = $request->post('id');
        if($contrato->bajaContrato($term) === true) {
            $a = new CalUserLogin;
            $idusrlog = Auth::user()->usuario_id;
            $datausrlog = $a->getuserid($idusrlog);
            $datalog = array(
            'ip_address' => $this->ip_cliente, 
            'descripcion' => 'El usuario '.$datausrlog[0]['username'].' ha dado de baja un contrato con el ID: '.$term.'.',
            'tipo' => 2,
            'id_user' => $idusrlog
            );
            $bitacora = new LogMovimiento;
            $bitacora->setMovimiento($datalog);
            return Response::json(true);
        }
        return Response::json(false);
    }
    public function verCliente(Request $request){
        $cliente = new contratos;
        $data = $cliente->getContratosActivosById($request->id);
        return view('contratos.editar')->with(['info' => $data[0]]);
    }
}
