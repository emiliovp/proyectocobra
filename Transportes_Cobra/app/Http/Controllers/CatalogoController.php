<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;
use App\Catalogo;
use Session;
class CatalogoController extends Controller
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
        return view('catalogos.lista');
    }
    public function anyData()
    {
        $a = new Catalogo;
        $data = $a->getcatalogosactivos();

        return Datatables::of($data)->make(true);
    }
    public function stored(Request $request){
        $area = $request->post('nombre');
        $a = new Catalogo;
        $repetido = $a->getnombre($area);
        if (!$repetido == true) {
            if($a->setcatalogo($request->post('nombre')) === true) {
                return Response::json(true);
            }
            return Response::json(false);
        }else{
            return Response::json('2');
        }
    }
    public function updated(Request $request){
        $id = $request->post('id');
        $cat = $request->post('nombre');
        $a = new Catalogo;
        $repetido = $a->getnombrerepetido($cat, $id);
        if (!$repetido == true) {
            if($a->editarcatalogo($id,$cat) === true) {
                return Response::json(true);
            }
            return Response::json(false);
        }else{
            return Response::json('2');
        }
    }
    public function baja_catalog(Request $request){
        $a = new Catalogo;
        $term = $request->post('id');

        if($a->baja_catalogo($term) === true) {
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
