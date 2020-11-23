<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;

use App\datosControlInventario;
use App\RelServicioSolicitud;
use App\RelClienteBodega;
use App\CatOpciones;
use App\Solicitud;
use App\Clientes;
use App\Bodega;

class SolcitudController extends Controller
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
        return view('solicitud.lista');  //->with(['alat' => 0]);
    }
    public function anyData()
    {
        $a = new Solicitud;
        $data = $a->getSolicitudInfo();

        return Datatables::of($data)->make(true);
    }
    public function nuevaSolicitud(){
        $cat= new CatOpciones;
        $tmovimiento = $cat->getOptCatalogoByName('TIPO DE MOVIMIENTO');
        $tservico = $cat->getOptCatalogoByName('SERVICIOs');
        return view('solicitud.nuevaSolicitud')->with(['movimiento' => $tmovimiento, 'servicio' =>$tservico]);
    }
    public function autocomplete(Request $request){
        $term = $request->get('term');
        $term = (isset($term)) ? $term : 0 ;
        if($term === '0') {
            return $dataclientes[] = array(
                'respuesta' => 'No se encontro el registro'
            );
        }else{
            $clientes = new Clientes;
            $dataclientes = $clientes->getclientesautocomplete($term);
            if (empty($dataclientes)) {
                return $dataclientes[] = array('respuesta'=>'No se encontro el registro');
            }
            if (count($dataclientes) > 0) {
                return $dataclientes;
            } else if($data==null) {
                return $dataclientes[] = array('respuesta'=>'No se encontro el registro');
            }
        }
    }
    public function getbodegas(Request $request){
        $param = $request->get('idcliente');
        $cliente = (isset($param)) ? $param : NULL ;
        $conb = new Bodega;
        $bodegas = $conb->getbodegabycliente($cliente);
        if ($bodegas == null) {
            $bodegas = $conb->getbodegaslibres();
        }
        return $bodegas;
    }
    public function getopt(Request $request){
        $a = new CatOpciones;
        $modh = $a->getOptByIdOpcion($request->option);
        print_r(json_encode($modh));
    }
    public function stored (Request $request){
        $a = new RelClienteBodega;
        $b = new Solicitud;
        $c = new RelServicioSolicitud;
        $d = new datosControlInventario;
        $bodegarel = $a->getBodegaCliente($request->clienteid, $request->bodega);
        if (count($bodegarel)>0) {
            $idbodega = $bodegarel[0]['id'];
        }else{
            $idbodega = $a->setbodegacliente($request->clienteid, $request->bodega);
            if ($idbodega == false) {
                Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
                return redirect()->back()->withInput();
            }
        }

        $datosSolicitud = array();
        $datosSolicitud['fechaHoraProgramada'] = $request->fecprogramada;
        $datosSolicitud['tipoMercancia'] = $request->tmercancia;
        $datosSolicitud['lugarSalida'] = $request->salida;
        $datosSolicitud['destino'] = $request->destino;
        $datosSolicitud['tipo_movimiento'] = $request->tmovimiento;
        $datosSolicitud['rel_cliente_bodega_id'] = $idbodega;
        $idsol = $b->setsolicitud($datosSolicitud);
        if ($idsol == false) {
            Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
            return redirect()->back()->withInput();
        }
        $servicios = $request->hiddentservicio;
        $notaserv = $request->hiddenNotasAd;
        $servicios = explode("_", $servicios);
        $notasad = explode("_", $notaserv);
        foreach ($servicios as $key => $value) {
            if ($notasad[$key] == 0) {
                $notas = null;
            }else{
                $notas = $notasad[$key];
            }
            $result = $c->setserviciosol($idsol,$notas,$value);
            if ($result == false) {
                Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
                return redirect()->back()->withInput();
            }
        }        
        $controlinv = $request->hiddenidcontrol;
        $controldesc = $request->hiddenDescripcion;
        $contenedor = $request->hiddenContenedor;
        $cantidad = $request->hiddenCantidad;
        $tproducto = $request->hiddentproducto;
        $notadinv = $request->hiddennotadinv;

        $controlinv = explode("_", $controlinv);
        $contdesc = explode("_", $controldesc);
        $cont = explode("_", $contenedor);
        $cantidad = explode("_", $cantidad);
        $tprod = explode("_", $tproducto);
        $notadinv = explode("_", $notadinv);
        $datoscontrol = array();
        foreach ($controlinv as $key => $value) {
            if ($notadinv[$key] == 0) {
                $notacontinv = NULL;
            }else {
                $notacontinv = $notadinv[$key];
            }
            $datoscontrol['solicitud_id'] = $idsol;
            $datoscontrol['descripcion'] = $contdesc[$key];
            $datoscontrol['contenedor'] = $cont[$key];
            $datoscontrol['cantidad'] = $cantidad[$key];
            $datoscontrol['tipoProducto'] = $tprod[$key];
            $datoscontrol['notasAd'] = $notacontinv;

            $resultcontinv = $d->setcontrolinv($datoscontrol);
            if ($resultcontinv == false) {
                Session::flash('excepcionerror', 'Error al realizar la operación, favor de volver a intentarlo');
                return redirect()->back()->withInput();
            }
        }
        Session::flash('success', 'La operación se ha realizado con exito');
        return redirect('solicitud/lista');
        //dd($request);
    }
}
