<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use App\LogMovimiento;
use App\CalUserLogin;
use App\sessions;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // public $ip_address_client;
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->ip_address_client = getIpAddress();
        $this->middleware('guest')->except('logout');
    }
    public function login(Request $request){
        $a = new CalUserLogin;
        $usr = $request->post('email');
        $datos = $a->getuser_validate($usr);
        $pass = $request->post('password');
        if (count($datos) < 1) {
            Session::flash('msjError', 'El usuario no existe, favor de validar la información');
            return redirect()->back()->withInput();
        }
        $pass_base = Crypt::decryptString($datos[0]['password']);
        $usr_bas = $datos[0]['username'];
        if (count($datos) > 1) {
            Session::flash('msjError', 'Existe un problema con su usuario, favor de comunicarse con el administrador');
            return redirect()->back()->withInput();
        }
        if ($pass != $pass_base ) {
            Session::flash('msjError', 'Su contraseña no coincide con la registrada, favor de introducir los datos correctos');
            return redirect()->back()->withInput();
        }
        if ($usr != $usr_bas ) {
            Session::flash('msjError', 'Su usuario no coincide con la registrada, favor de introducir los datos correctos');
            return redirect()->back()->withInput();
        }
        return $this->sendLoginResponse($request);
    }
    protected function sendLoginResponse(Request $request)
    {
        $a = new CalUserLogin;
        $usr = $request->post('email');
        $datos = $a->getuser_validate($usr);
        $request->session()->regenerate();
        if (Auth::User() == null) {
            $previous_session = null;
        }else{
            $previous_session = Auth::User()->session_id;
        }

        if ($previous_session) {
            Session::getHandler()->destroy($previous_session);
        }        
        
        $session = new CalUserLogin; 
        $session->nombre = $datos[0]['nombre_completo'];
        $session->usuario = $datos[0]['username'];
        $session->perfil = $datos[0]['id_perfil'];
        $session->area = $datos[0]['id_area'];
       
        $tabsession = new sessions;
        $tabsession->description = 'Usuario logueado';
        $tabsession->tipo = 1;
        $tabsession->usuario_id = $datos[0]['id_usr'];
        $tabsession->save();
        //dd($session);
        $this->guard()->login($tabsession, true);
        
        return redirect($this->redirectTo);
    }
}
