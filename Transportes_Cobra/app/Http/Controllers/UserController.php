<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\CalUserLogin;
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
}
