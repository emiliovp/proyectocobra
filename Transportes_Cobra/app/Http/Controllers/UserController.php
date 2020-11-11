<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
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
