<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;
use App\perfil;
use App\modulo;
use App\Area;
use App\RelPerfilModulo;
use Session;
class PerfilController extends Controller
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
        return view('perfiles.lista');
    }
    public function anyData()
    {
        $a = new perfil;
        $data = $a->getperfil();

        return Datatables::of($data)->make(true);
    }
    public function sotredperfil(){
        $a = new Area;
        $area = $a->getarea();
        $b = new modulo;
        $modp = $b->getmodulop();

        return view('perfiles.nuevo')->with(['area' => $area,'modulo' => $modp ]);
    }
    public function modhijo(Request $request){
        $a = new modulo;
        $modh = $a->getmoduloh($request->padre);
        print_r(json_encode($modh));
    }
    public function stored(Request $request){
        $request->validate([
            'nombrep' => 'required',
            'area' => 'required',
            'modulop' => 'required',
        ]);
        $datap = array();
        $nombre = ($request->post('nombrep') !='') ? $request->post('nombrep') : NULL;
        $datap['nombre'] = substr($nombre,0,128);
        $descripcion = ($request->post('nombrep') !='') ? $request->post('nombrep') : NULL;
        $datap['descripcion'] = substr($nombre,0,256);
        $datap['area_id'] = $request->post('area');
        $a = new perfil;
        if($idp = $a->setPerfil($datap)){
            
        }else{
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }
        $modPad = $request->post('hiddenModuloPad');
        $idmodulos = explode('_',$modPad);
        $modHijo = ($request->post('hiddenModulo') != '') ? $request->post('hiddenModulo') : NULL;
        if ($modHijo != NULL ) {
            $idmoduloshijo = explode('_',$modHijo);
            $idmodulos = array_merge($idmodulos,$idmoduloshijo);
        }
        $rel = new RelPerfilModulo;
        if($resRel = $rel->setRelModPer($idp, $idmodulos)){
            Session::flash('success', 'La operación se ha realizado con exito');
            return redirect('perfiles/lista');
        }else{
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }
    }
    public function bajaPerfil(Request $request){
        $a = new perfil;
        $term = $request->post('id');
        if($a->baja_perfil($term) === true) {
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
