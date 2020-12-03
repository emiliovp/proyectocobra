@extends('layouts.app')

@section('content')
<style>
    .card-body {
        padding: 0.5rem 1.25rem;
    }
    label.error {
        font-size: 8pt;
        color: red;
    }
    .remover_campo {
        margin: auto;
        position: relative;
        border: 2px;
    }
    .divselectmultiple {
        max-height: 200px;
        overflow-y: auto;
    }
</style>
<form method="POST" action="{{route('storedcustodia')}}" id="form-custodia" accept-charset="UTF-8" enctype="multipart/form-data">
@csrf
<div class="container">
    <div id="success">
        <div class="col-sm-12">
            @if(Session::has('excepcionerror'))
            <div class="alert alert-danger" role="alert"> 
                <strong> Error: </strong>  {{ Session::get('excepcionerror') }}
            </div>
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header ">
                    <h5 class="card-title">Captura de custodia</h5>
                </div>
                <div class="card-body">
                <input type="hidden" name="sol_id" id="sol_id" value="{{ $data['folio'] }}"/>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="folio">Folio:</label>
                            <input type="text" name="folio" id="folio" class= "form-control" value ="{{ $data['folio'] }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label for="cliente">cliente:</label>
                            <input type="text" class="form-control" name ="clienteid" id="clienteid" value="{{ $data['cliente'] }}" disabled/>                     
                        </div> 
                        <div class="col-md-4">
                            <label for="lsalida">Lugar de salida:</label>
                            <input class="form-control campo-requerido" name="salida" id="salida" value ="{{ $data['lugarSalida'] }}" disabled/>
                        </div>    
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="salida">fecha de salida:</label>
                            <input type="text" class="form-control lg-4 campo-requerido" id="destino" name="destino" value ="{{ $data['fecha_inicio'] }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label for="destino">Destino:</label>
                            <input type="text" class="form-control lg-4 campo-requerido" id="destino" name="destino" value ="{{ $data['destino'] }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label for="salida">fecha de llegada:</label>
                            <input type="text" class="form-control lg-4 campo-requerido" id="destino" name="destino" value ="" disabled>
                        </div> 
                    </div>
                </div>
            </div>
            @foreach($servicios AS $keys => $valservicio)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Asignación de custodia {{ $valservicio['tipo_servicio'] }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="proveedor_{{ $valservicio['control_servicio'] }}">Proveedor de custodia</label>
                            <select class="form-control" id="proveedor_{{ $valservicio['control_servicio'] }}" name ="proveedor_{{ $valservicio['control_servicio'] }}">
                                <option>Seleccione una opción...</option>
                                @foreach($proveedores AS $keys => $value)
                                    @if($valservicio['idservicio'] == $value['idservicio'])
                                    <option value="{{ $value['idproveedor'] }}">{{ $value['proveedor'] }}</option>                                                              
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        @php
                            $requerido ='';
                            $activo ='disabled';
                            if($valservicio['tipo_servicio'] == 'EN UNIDAD'){
                                $requerido = "class = campo-requerido";
                                $activo ='';
                            }
                        @endphp
                        <div class="col-md-4">
                            <label for="placa">Placa</label>
                            <input {{ $activo }} type="text" class="form-control" />
                        </div>
                        <div class="col-md-4">
                            <label for="modelo">Modelo</label>
                            <input {{ $activo }} class ="form-control"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="nombre">Nombre</label>
                            <input class="form-control"/>
                        </div>
                        <div class="col-md-4">
                            <label for="apaterno">Apellido paterno</label>
                            <input class="form-control"/>
                        </div>
                        <div class="col-md-3">
                           <label for="amaterno">Apellido Materno</label>
                            <input class="form-control"/>
                        </div>
                        <div class="col-md-1">  
                        <label>&nbsp;</label>
                            <input type="hidden" name="hiddenProveedor_{{ $valservicio['control_servicio'] }}" id="hiddenProveedor_{{ $valservicio['control_servicio'] }}"/>
                            <input {{ $requerido }} type="hidden" name="hiddenPlaca_{{ $valservicio['control_servicio'] }}" id="hiddenPlaca_{{ $valservicio['control_servicio'] }}"/>
                            <input {{ $requerido }} type="hidden" name="hiddenModelo_{{ $valservicio['control_servicio'] }}" id="hiddenModelo_{{ $valservicio['control_servicio'] }}"/>
                            <input class="campo-requerido" type="hidden" name="hiddenNombre_{{ $valservicio['control_servicio'] }}" id="hiddenNombre_{{ $valservicio['control_servicio'] }}"/>
                            <input class="campo-requerido" type="hidden" name="hiddenApaterno_{{ $valservicio['control_servicio'] }}" id="hiddenApaterno_{{ $valservicio['control_servicio'] }}"/>
                            <input type="hidden" name="hiddenAmaterno_{{ $valservicio['control_servicio'] }}" id="hiddenAmaterno_{{ $valservicio['control_servicio'] }}"/>
                            <button id="" type="button" class="form-control btn btn-primary btn-add" ><i class="fas fa-plus"></i></button>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_proveedor_{{ $valservicio['control_servicio'] }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_proveedor_{{ $valservicio['control_servicio'] }}">
                                </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Observaciones</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="observacion">Observaciones</label>
                            <textarea  class="form-control"></textarea >
                        </div>
                        <div class="col-md-4">
                            <label for="fecha">Fecha</label>
                            <input class="form-control" />
                        </div>
                        <div class="col-md-4">
                            <label>Estatus</label>
                            <select class="form-control">
                                <option>Seleccione una opcion...</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row" style="margin-top:10px;">
                <div class="col-md-6">
                    <a href="{{ route('custodias') }}" class="btn btn-warning btn-block" style="color:#FFFFFF;">{{ __('Regresar') }}</a>
                </div>
                <div class="col-md-6">
                    <input type="button" value="Guardar FUS" id="enviar" class="form-control btn btn-primary" />
                </div>
            </div>    
        </div>
    </div>
</div>
</form>
@endsection
@push('scripts')
<script src="{{ asset('js/jquery_validate/jquery.validate.js') }}"></script>
<script>
$.validator.messages.required = 'El campo es requerido.';

jQuery.validator.addClassRules({
    'campo-requerido': {
        required: true
    }
});
$('#enviar').click(function() {
    swal.fire({
        title: 'Advertencia',
        text: "¿Esta seguro de realizar la operación?",
        type: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DC3545',
        confirmButtonColor: '#3085d6',
        allowOutsideClick: false,
        allowEscapeKey: false,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'De acuerdo'
    }).then((result) => {
        if (result.value) {
            $('#form-custodia').submit();
        }
    });
});
$('#form-custodia').validate({
    ignore: "",
    submitHandler: function(form) {
        mostrarLoading();
        setTimeout(form.submit(), 500);
    }
});
$('.btn-add').click(function(){
    
});
</script>
@endpush