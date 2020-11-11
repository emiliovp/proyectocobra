<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\CalUserLogin;
use App\perfil;
use Session;

class UserController extends Controller
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
        return view('usuarios.lista');  //->with(['alat' => 0]);
    }
    public function anyData()
    {
        $a = new CalUserLogin;
        $data = $a->getuser();

        return Datatables::of($data)->make(true);
    }
    public function nuevo(){
        $a = new perfil;
        $perfiles = $a->getperfil();
        return view('usuarios.nuevo')->with(['perfil' => $perfiles]);
    }
    public function stored(Request $request){
        $a = new CalUserLogin;
        $request->validate([
            'nombrep' => 'required',
            'apaterno' => 'required',
            'usuario' => 'required',
            'password1' => 'required',
            'perfil' => 'required',
            'correo' => 'required',
        ]);
        $nombre = ($request->post('nombrep') !='') ? $request->post('nombrep') : NULL;
        $apaterno = ($request->post('nombrep') !='') ? $request->post('nombrep') : NULL;
        $amaterno = ($request->post('amaterno') !='') ? $request->post('amaterno') : NULL; 
        $usr = ($request->post('usuario') !='') ? $request->post('usuario') : NULL;
        $pass = ($request->post('password1') !='') ? $request->post('password1') : NULL;
        $request->post('password2');
        $perfil = ($request->post('perfil') !='') ? $request->post('perfil') : NULL;
        $correo = ($request->post('correo') !='') ? $request->post('correo') : NULL;
        $tel = ($request->post('telefono') !='') ? $request->post('telefono') : NULL;
        $ext = ($request->post('ext') !='') ? $request->post('ext') : NULL;
        $repetido = $a->getnombrerepetido($usr);
        if ($repetido == true) {
            Session::flash('excepcionerror', 'El usuario ya ha sido dado de alta favor de ingresar un nuevo usuario');
            return redirect()->back()->withInput();
        }
        $datausr = array();
        $datausr['nombre'] = substr($nombre,0,128);
        $datausr['aPaterno'] = substr($apaterno,0,128);
        $datausr['aMaterno'] = substr($amaterno,0,128);
        $datausr['username'] = substr($usr,0,128);
        $datausr['password'] =  Hash::make($pass);
        $datausr['correo'] = substr($correo,0,128);
        $datausr['telefono'] = substr($tel,0,15);
        $datausr['ext'] = substr($ext,0,15);
        $datausr['perfil_id'] = substr($perfil,0,15);
        if($idp = $a->setUsuario($datausr)){
            Session::flash('success', 'La operación se ha realizado con exito');
            return redirect('usuarios/lista');
        }else{
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }
    }
    public function updusr(Request $request){
        $term = $request->id;
        $a = new CalUserLogin;
        $b = new perfil;
        $data = $a->getuserid($term);
        $perfiles = $b->getperfil();
        return view('usuarios.editar')->with(['datos' => $data[0],'perfil' => $perfiles]);
    }
    public function updateusuario(Request $request){
        $request->validate([
            'nombrep' => 'required',
            'apaterno' => 'required',
            'usuario' => 'required',
            'perfil' => 'required',
            'correo' => 'required',
        ]);
        $a = new CalUserLogin;
        $nombre = ($request->post('nombrep') !='') ? substr($request->post('nombrep'),0,128) : NULL;
        $apaterno = ($request->post('nombrep') !='') ? substr($request->post('nombrep'),0,128) : NULL;
        $amaterno = ($request->post('amaterno') !='') ? substr($request->post('amaterno'),0,128) : NULL; 
        $usr = ($request->post('usuario') !='') ? substr($request->post('usuario'),0,128) : NULL;
        $pass = ($request->post('password1') !='') ? $request->post('password1') : NULL;
        $request->post('password2');
        $perfil = ($request->post('perfil') !='') ? $request->post('perfil') : NULL;
        $correo = ($request->post('correo') !='') ? substr($request->post('correo'),0,128) : NULL;
        $tel = ($request->post('telefono') !='') ? substr($request->post('telefono'),0,15) : NULL;
        $ext = ($request->post('ext') !='') ? substr($request->post('ext'),0,15) : NULL;
        $repetido = $a->getuseridnameupdatevalidate($request->post('usuario_id'),$usr);
        if ($repetido == true) {
            Session::flash('excepcionerror', 'El usuario ya ha sido dado de alta favor de ingresar un nuevo usuario');
            return redirect()->back()->withInput();
        }
        $idusr = $request->post('usuario_id');
        $datausr = array();
        $datausr['nombre'] = $nombre;
        $datausr['aPaterno'] = $apaterno;
        $datausr['aMaterno'] = $amaterno;
        $datausr['username'] = $usr;
        $datausr['password'] = ($pass != null) ? Crypt::encryptString($pass) : NULL ;
        $datausr['correo'] = $correo;
        $datausr['telefono'] = $tel;
        $datausr['ext'] = $ext;
        $datausr['perfil_id'] = $perfil;

        if($idp = $a->updusuario($idusr,$datausr)){
            Session::flash('success', 'La operación se ha realizado con exito');
            return redirect('usuarios/lista');
        }else{
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }
    }
    public function baja_usr(Request $request){
        $a = new CalUserLogin;
        $term = $request->post('id');
        if($a->baja_usr($term) === true) {
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
