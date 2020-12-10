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

class ProveedoresController extends Controller
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
            'descripcion' => 'El usuario '.$data[0]['username'].' visualizó lista de proveedores.',
            'tipo' => 4,
            'id_user' => $id
        );
        $bitacora = new LogMovimiento;
        $bitacora->setMovimiento($data);
        return view('proveedores.lista');
    }
    public function anyData()
    {
        $a = new perfil;
        $data = $a->getperfil();

        return Datatables::of($data)->make(true);
    }
}
