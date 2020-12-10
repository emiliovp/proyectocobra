<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\LogMovimiento;
use App\CalUserLogin;
use App\Area;

class AreaController extends Controller
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
          'descripcion' => 'El usuario '.$data[0]['username'].' visualizó lista de áreas.',
          'tipo' => 4,
          'id_user' => $id
          );
          $bitacora = new LogMovimiento;
          $bitacora->setMovimiento($data);
        return view('areas.lista');  //->with(['alat' => 0]);
    }
    public function anyData()
    {
        $a = new Area;
        $data = $a->getarea();

        return Datatables::of($data)->make(true);
    }
    public function sotredarea(){
        $a = new Area;
        $area = $a->getarea();

        return view('area.nuevo')->with(['area' => $area,'modulo' => $modp ]);
    }
    public function stored(Request $request){
        $area = $request->post('nombre');
        $descripcion = $request->post('descripcion');
        $des = (isset($descripcion)) ? $descripcion : NULL;
        $a = new Area;
        $repetido = $a->getnombre($area);
        if (!$repetido == true) {
            if($a->guardararea($request->post('nombre'), $descripcion) === true) {
                $mov = new LogMovimiento;
                $usr = new CalUserLogin;
                $idusrlog = Auth::user()->usuario_id;
                $datausrlog = $usr->getuserid($idusrlog);
                $datalog = array(
                'ip_address' => $this->ip_cliente, 
                'descripcion' => 'El usuario '.$datausrlog[0]['username'].' dio de alta el área: '.$area.'.',
                'tipo' => 1,
                'id_user' => $idusrlog
                );
                $bitacora = new LogMovimiento;
                $bitacora->setMovimiento($datalog);
                return Response::json(true);
            }
            return Response::json(false);
        }else{
            return Response::json('2');
        }
    }
    public function updated(Request $request){
        $id = $request->post('id');
        $area = $request->post('nombre');
        $descripcion = $request->post('descripcion');
        $des = (isset($descripcion)) ? $descripcion : NULL;
        $a = new Area;
        $repetido = $a->getnombrerepetido($area, $id);
        if (!$repetido == true) {
            if($a->editarea($id,$area, $des) === true) {
                $mov = new LogMovimiento;
                $usr = new CalUserLogin;
                $idusrlog = Auth::user()->usuario_id;
                $datausrlog = $usr->getuserid($idusrlog);
                $datalog = array(
                'ip_address' => $this->ip_cliente, 
                'descripcion' => 'El usuario '.$datausrlog[0]['username'].' ha editado el área: '.$area.' con el ID: '.$id.'.',
                'tipo' => 3,
                'id_user' => $idusrlog
                );
                $bitacora = new LogMovimiento;
                $bitacora->setMovimiento($datalog);
                return Response::json(true);
            }
            return Response::json(false);
        }else{
            return Response::json('2');
        }
    }
    public function baja_area(Request $request){
        $a = new Area;
        $term = $request->post('id');

        if($a->baja_area($term) === true) {
            $mov = new LogMovimiento;
            $usr = new CalUserLogin;
            $idusrlog = Auth::user()->usuario_id;
            $datausrlog = $usr->getuserid($idusrlog);
            $datalog = array(
            'ip_address' => $this->ip_cliente, 
            'descripcion' => 'El usuario '.$datausrlog[0]['username'].' ha dado de baja un área con el ID: '.$term.'.',
            'tipo' => 2,
            'id_user' => $idusrlog
            );
            $bitacora = new LogMovimiento;
            $bitacora->setMovimiento($datalog);
            
            return Response::json(true);
        }
        return Response::json(false);
    }
}
