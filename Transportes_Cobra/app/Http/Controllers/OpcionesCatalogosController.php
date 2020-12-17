<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\LogMovimiento;
use App\CalUserLogin;
use App\CatOpciones;
use App\Catalogo;
use Session;
class OpcionesCatalogosController extends Controller
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
    public function index(Request $request)
    {
        $mov = new LogMovimiento;
        $usr = new CalUserLogin;
        $id = Auth::user()->usuario_id;
        $data = $usr->getuserid($id);
        $data = array(
            'ip_address' => $this->ip_cliente, 
            'descripcion' => 'El usuario '.$data[0]['username'].' visualizó lista de opciones del catalogo con el id: '.$request->id.'.',
            'tipo' => 4,
            'id_user' => $id
        );
        $bitacora = new LogMovimiento;
        $bitacora->setMovimiento($data);
        return view('catalogos.listaopciones')->with(['id' => $request->id]);
    }
    public function dataIndexOptCat(Request $request) {
        $optCat = new CatOpciones;     
        $data = $optCat->getcatalogosactivos($request->id);
        return Datatables::of($data)->make(true);
    }
    public function altaOpcion(Request $request){
        $cat = new Catalogo;
        $catalogos= $cat->getcatalogosactivosexcep($request->id);
        //dd($catalogos);
        return view('catalogos.altaoptcat')->with(['catalogos_id' => $request->id, 'catalogos'=>$catalogos]);
    }
    public function OptionByCatId(Request $request) {
        $op = new CatOpciones; 
        $option = $op->getcatalogosactivos($request->cat);
        print_r(json_encode($option));
    }
    public function storeoptcat(Request $request) {
        $request->validate([
            "cat_op_descripcion" => "required|string|min:3|max:100|regex:/^[A-Za-z0-9[:space:]\s\S]+$/",
        ]);
        
        $optcat = new CatOpciones;

        if($request->post("cat_opciones_id")) {
            $getJerarDependencia = $optcat->getOptCatalogoById($request->post("cat_opciones_id"));
            $jerarquia = ($getJerarDependencia['jerarquia']+1);
            $cat_opciones_id = $request->post("cat_opciones_id");
        }  else {
            $jerarquia = 1;
            $cat_opciones_id = null;
        }
        
        $lastid = $optcat->guardarOpt($request->post('cat_op_descripcion'), $request->post('catalogos_id'), $cat_opciones_id, $jerarquia);
        if($lastid == false){
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }else {
            Session::flash('success', 'La operación se ha realizado con exito');
            return redirect()->route('listaopciones', ['id' => $request->post('catalogos_id')]);
        }
        /*$idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }*/
       
        /*$data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Se ha realizado la alta del catálogo '.$request->post("name"),
            'tipo' => 'alta',
            'id_user' => $idEmployee
        );
        
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);*/
    }
    public function editarOpcion(Request $request){
        $opt = new CatOpciones;
        $cat = new Catalogo;
        $opcionAEditar = $opt->getOptCatalogoById($request->opt);
        $opciones = $opt->getOptCatalogoByIdOpcion($request->opt);
        $catalogo = $cat->getcatalogosactivos();
        if(isset($opcionAEditar['cat_opciones_id'])){
            $padcat = $cat->getcatalogosByOpt($opcionAEditar['cat_opciones_id']);
            $catpadre = $padcat[0]['id'];
            $optpad = $opcionAEditar['cat_opciones_id'];
        }else{
            $catpadre = 0;
            $optpad = 0;
        }
        return view('catalogos.ediatropcion')->with(['opcionAEditar' => $opcionAEditar, 'catalogos_id' => $request->id, 'optcat' => $opciones,'catalogos'=>$catalogo,'padre'=>$catpadre,'oppad'=>$optpad]);
    }
    public function updateoptcat(Request $request){
        $request->validate([
            "cat_op_descripcion" => "required|string|min:3|max:100|regex:/^[A-Za-z0-9[:space:]\s\S]+$/",
        ]);
        $optcat = new CatOpciones;
        $aut = new Catalogo;
        if($request->post("cat_opciones_id")) {
            $getJerarDependencia = $optcat->getOptCatalogoById($request->post("cat_opciones_id"));
            $jerarquia = ($getJerarDependencia['jerarquia']+1);
            $cat_opciones_id = $request->post("cat_opciones_id");
        }  else {
            $jerarquia = 1;
            $cat_opciones_id = null;
        }
        $resulopt=$optcat->setedicionopt($request->post("idopt"), $request->post('cat_op_descripcion'), $request->post('catalogos_id'), $cat_opciones_id, $jerarquia);
        if($resulopt == false){
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }else {
            Session::flash('success', 'La operación se ha realizado con exito');
            return redirect()->route('listaopciones', ['id' => $request->post('catalogos_id')]);
        }
        /*
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        if($idEmployee == 0) {
            $idEmployee = null;
        }       
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Se ha realizado la alta del catálogo '.$request->post("name"),
            'tipo' => 'alta',
            'id_user' => $idEmployee
        ); 
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);
        */
    }
    public function deleteoptcat(Request $request) {
        $optcatalogo = new CatOpciones;
        /*$idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        if($idEmployee == 0) {
            $idEmployee = null;
        }*/
        if($optcatalogo->eliminacionLogica($request->post('id')) === true) {
            /*$data = array(
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado la eliminación de una opción de catálogo '.$request->post("id"),
                'tipo' => 'alta',
                'id_user' => $idEmployee
            );
            $bitacora = new LogBookMovements;
            $bitacora->guardarBitacora($data);*/ 
            return Response::json(true);
        }
        return Response::json(false);
    }
}
