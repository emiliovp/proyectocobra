<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use App\CalUserLogin;
use App\perfil;
class HomeController extends Controller
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
    public function index($id_mod = null)
    {
        $a = new CalUserLogin;
        $idusr = Auth::user()->usuario_id;
        if ($id_mod != null) {
            $datos = $a->getInfUsr($idusr, $id_mod);
        }else{
            $datos = $a->getInfUsr($idusr);
        }
        $datos = ($datos == null) ? null : $datos ;
        //dd($datos);
        return view('home')->with(['datos' => $datos]);
    }
    public function modhijo($id_mod = null)
    {
        $a = new CalUserLogin;
        $idusr = Auth::user()->usuario_id;
        if ($id_mod != null) {
            $datos = $a->getInfUsr($idusr, $id_mod);
        }else{
            $datos = $a->getInfUsr($idusr);
        }
        return view('home')->with(['datos' => $datos]);
    }
}
