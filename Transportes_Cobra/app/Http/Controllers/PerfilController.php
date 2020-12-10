<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\LogMovimiento;
use App\CalUserLogin;
use App\RelPerfilModulo;
use App\perfil;
use App\modulo;
use App\Area;
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
            'descripcion' => 'El usuario '.$data[0]['username'].' visualizó lista de perfiles.',
            'tipo' => 4,
            'id_user' => $id
        );
        $bitacora = new LogMovimiento;
        $bitacora->setMovimiento($data);
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
        if(!$idp = $a->setPerfil($datap)){
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

            $mov = new LogMovimiento;
            $usr = new CalUserLogin;
            $idusrlog = Auth::user()->usuario_id;
            $datausrlog = $usr->getuserid($idusrlog);
            $datalog = array(
            'ip_address' => $this->ip_cliente, 
            'descripcion' => 'El usuario '.$datausrlog[0]['username'].' dio de alta el perfil: '.$nombre.'con el ID:'.$idp.'.',
            'tipo' => 1,
            'id_user' => $idusrlog
            );
            $bitacora = new LogMovimiento;
            $bitacora->setMovimiento($datalog);

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
            $mov = new LogMovimiento;
            $usr = new CalUserLogin;
            $idusrlog = Auth::user()->usuario_id;
            $datausrlog = $usr->getuserid($idusrlog);
            $datalog = array(
            'ip_address' => $this->ip_cliente, 
            'descripcion' => 'El usuario '.$datausrlog[0]['username'].' ha dado de baja un perfil con el ID: '.$term.'.',
            'tipo' => 2,
            'id_user' => $idusrlog
            );
            $bitacora = new LogMovimiento;
            $bitacora->setMovimiento($datalog);
            
            return Response::json(true);
        }
        return Response::json(false);
    }
    public function updperfil(Request $request){
        $a = new Area;
        $area = $a->getarea();
        $b = new modulo;
        $modp = $b->getmodulop();
        $c = new perfil;
        $info = $c->getPerfilById($request->id);
        $modPadEdit = $b->getModuloByPerfil($request->id);
        $modHijoEdit = $b->getModuloByPerfil($request->id, 1);
        $modPadEditlist = $b->getModuloByPerfil($request->id, null, 1);
        $modHijEditlist = $b->getModuloByPerfil($request->id, 1, 2);
        $modHijEditlist = json_encode($modHijEditlist);
        $modHijEditlist = json_decode($modHijEditlist, true);
        return view('perfiles.editar')->with(['info' => $info[0],'area' => $area,'modulo' => $modp,'modPadEdit' => $modPadEdit[0], 'modHijoEdit' => $modHijoEdit[0], 'mpdPadLista' => $modPadEditlist, 'modHijoList' => $modHijEditlist]);
    }
    public function modificacionperfil(Request $request){
        $perfil = new perfil;
        $relMod = new RelPerfilModulo;
        $id = $request->post('perfilId');
        $nombre = ($request->post('nombrep') !='') ? $request->post('nombrep') : NULL;
        $perfNombre = substr($nombre,0,128);
        $descripcion = ($request->post('nombrep') !='') ? $request->post('nombrep') : NULL;
        $perfDescripcion = substr($nombre,0,256);
        $area_id = $request->post('area');
        $modPad = $request->post('hiddenModuloPad');
        $idmodulos = explode("_",$modPad);
        $modHijo = $request->post('hiddenModulo');
        $arrModHijo = explode("_",$modHijo);
        $updp = $perfil->updatePerfil($id,$perfNombre,$perfDescripcion,$area_id);
        if ($updp == false) {
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }
        $updModPad = $relMod->updateModulos($id);
        if ($updModPad == false) {
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }
        if ($arrModHijo != NULL ) {
            $idmodulos = array_merge($idmodulos,$arrModHijo);
        }
        $rel = new RelPerfilModulo;
        if($resRel = $rel->setRelModPer($id, $idmodulos)){
            $mov = new LogMovimiento;
                $usr = new CalUserLogin;
                $idusrlog = Auth::user()->usuario_id;
                $datausrlog = $usr->getuserid($idusrlog);
                $datalog = array(
                'ip_address' => $this->ip_cliente, 
                'descripcion' => 'El usuario '.$datausrlog[0]['username'].' ha editado el perfil: '.$perfNombre.' con el ID: '.$id.'.',
                'tipo' => 3,
                'id_user' => $idusrlog
                );
                $bitacora = new LogMovimiento;
                $bitacora->setMovimiento($datalog);
            Session::flash('success', 'La operación se ha realizado con exito');
            return redirect('perfiles/lista');
        }else {
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }
    }
}
