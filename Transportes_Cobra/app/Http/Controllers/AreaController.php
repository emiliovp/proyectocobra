<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;
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
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
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
            /*$msjDescription = 'Se ha puesto como '.$mov.' el área con id '.$request->post("id");
            
            $data = array(
                'ip_address' => $this->ip_address_client, 
                'description' => $msjDescription,
                'tipo' => 'bloqueo',
                'id_user' => $idEmployee
            );
            
            $bitacora = new CalLogBookMovements;
            $bitacora->guardarBitacora($data);*/
            
            return Response::json(true);
        }
        return Response::json(false);
    }
}
