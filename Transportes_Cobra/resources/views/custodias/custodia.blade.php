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
                            <input type="text" class="form-control" value="{{ $data['cliente'] }}" disabled/>                     
                        </div> 
                        <div class="col-md-4">
                            <label for="lsalida">Lugar de salida:</label>
                            <input class="form-control campo-requerido" value ="{{ $data['lugarSalida'] }}" disabled/>
                        </div>    
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="salida">fecha de salida:</label>
                            <input type="text" class="form-control lg-4 campo-requerido" value ="{{ $data['fecha_inicio'] }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label for="destino">Destino:</label>
                            <input type="text" class="form-control lg-4 campo-requerido" value ="{{ $data['destino'] }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label for="salida">fecha de llegada:</label>
                            <input type="text" class="form-control lg-4 campo-requerido" value ="" disabled>
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
                    <input type="hidden" name="servicioSolicitud" id="servicioSolicitud" value="{{ $valservicio['control_servicio'] }}"/>
                        <div class="col-md-4">
                            <label for="proveedor_{{ $valservicio['control_servicio'] }}">Proveedor de custodia</label>
                            <select class="form-control" id="proveedor_{{ $valservicio['control_servicio'] }}" name ="proveedor_{{ $valservicio['control_servicio'] }}">
                                <option value='' >Seleccione una opción...</option>
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
                            <label for="placa_{{ $valservicio['control_servicio'] }}">Placa</label>
                            <input {{ $activo }} id="placa_{{ $valservicio['control_servicio'] }}" name="placa_{{ $valservicio['control_servicio'] }}" type="text" class="form-control" />
                        </div>
                        <div class="col-md-4">
                            <label for="modelo_{{ $valservicio['control_servicio'] }}">Modelo</label>
                            <input {{ $activo }} id="modelo_{{ $valservicio['control_servicio'] }}" name="modelo_{{ $valservicio['control_servicio'] }}" class ="form-control"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <label for="nombre">Nombre del custodio</label>
                            <input class="form-control" onkeyup="mayus(this);" id="nombreCus_{{ $valservicio['control_servicio'] }}" name="nombreCus_{{ $valservicio['control_servicio'] }}"/>
                        </div>
                        
                        <div class="col-md-1">  
                        <label>&nbsp;</label>
                            <input type="hidden" name="hiddenProveedor_{{ $valservicio['control_servicio'] }}" id="hiddenProveedor_{{ $valservicio['control_servicio'] }}"/>
                            <input {{ $requerido }} type="hidden" name="hiddenPlaca_{{ $valservicio['control_servicio'] }}" id="hiddenPlaca_{{ $valservicio['control_servicio'] }}"/>
                            <input {{ $requerido }} type="hidden" name="hiddenModelo_{{ $valservicio['control_servicio'] }}" id="hiddenModelo_{{ $valservicio['control_servicio'] }}"/>
                            <input class="campo-requerido" type="hidden" name="hiddenNombre_{{ $valservicio['control_servicio'] }}" id="hiddenNombre_{{ $valservicio['control_servicio'] }}"/>
                            <button type="button" data-tipo="{{ $valservicio['tipo_servicio'] }}" id="addproveedor_{{ $valservicio['control_servicio'] }}" class="form-control btn btn-primary btn-add" ><i class="fas fa-plus"></i></button>
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
                            <textarea id="observacion" name="observacion" class="form-control"></textarea >
                        </div>
                        <div class="col-md-4">
                            <label for="fecha">Fecha</label>
                            <input id="fecha" name ="fecha" class="form-control" />
                        </div>
                        <!--<div class="col-md-4">
                            <label>Estatus</label>
                            <select class="form-control">
                                <option>Seleccione una opcion...</option>
                            </select>
                        </div>-->
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
    var idBtnControl = $(this).attr('id');
    var claveProv = idBtnControl.split('_');
    var tcustodia = $(this).attr("data-tipo");
    var tipo = 'proveedor';
    var hiddenIdTipo = '#hiddenProveedor_'+claveProv[1];
    var selectSelectedIdTipo = '#proveedor_'+claveProv[1]+' option:selected';
    var SelectedId = '#proveedor_'+claveProv[1];
    var divUlIdTipo = '#content_list_proveedor_'+claveProv[1];
    var ulIdTipo = '#list_proveedor_'+claveProv[1];
    var titleList = 'Lista de custodios agregados';
    /**Elementos extras */
    var placa = '#placa_'+claveProv[1];
    var modelo = '#modelo_'+claveProv[1];
    var nombre = '#nombreCus_'+claveProv[1];
    /**Elementos extras */
    /**Elementos ocultos extras*/
    var hiddenPlaca = '#hiddenPlaca_'+claveProv[1];
    var hiddenModelo = '#hiddenModelo_'+claveProv[1];
    var hiddenNombre = '#hiddenNombre_'+claveProv[1];
    /**Elementos ocultos extras*/
    /**valores */
    var valSelect = $(selectSelectedIdTipo).val();
    var SelectedId = '#proveedor_'+claveProv[1];
    var valorid = $(SelectedId).val();
    var valor = $(hiddenIdTipo).val();
    var valorPlaca = $(placa).val();
    var valorModelo = $(modelo).val();
    var valornombre = $(nombre).val();

    var valorhiddenplaca = $(hiddenPlaca).val();
    var valorhiddenmodelo = $(hiddenModelo).val();
    var valorhiddennombre = $(hiddenNombre).val();
    valSelect = valSelect.split('_');
    if (tcustodia == "A BORDO") {
        if(valorid != '') {
            var html = '<li id="elementoLista_'+valorid+'_'+claveProv[1]+'">'+$(selectSelectedIdTipo).text()+' / '+valornombre+'<input type="checkbox" value="'+valorid+'" class="checkremove_'+tipo+'_'+claveProv[1]+'"/></li>';
            if(valor != ''){    
                $resultComp = compararRepetidos(valor, valSelect[0], valorhiddennombre,valornombre);
                if($resultComp === true) {
                    $(hiddenIdTipo).val(valor+'_'+valSelect[0]);
                    $(ulIdTipo).append(html);
                    $(hiddenPlaca).val(valorhiddenplaca+'_'+0);
                    $(hiddenModelo).val(valorhiddenmodelo+'_'+0);
                    $(hiddenNombre).val(valorhiddennombre+'_'+valornombre);
                } else {
                    swal(
                        'Validación',
                        'El '+tipo+' ya se ha agregado, vuelva a intentarlo con otro.',
                        'warning'
                    )
                }
            }else{
            $(divUlIdTipo).html('<strong>'+titleList+'</strong> <input type="button" class="quitarlista btn btn-danger btn-sm" data-lista="'+ulIdTipo+'" data-afectado="'+hiddenIdTipo+'" data-tipo="checkremove_'+tipo+'_'+claveProv[1]+'" value="Quitar de la lista" /> <label for="seleccionartodo_'+claveProv[1]+'"><input type="checkbox" class="seleccionartodo" id="seleccionartodo_'+claveProv[1]+'" data-tipo="'+tipo+'" data-clave="'+claveProv[1]+'"/>Seleccionar todo</label>');
            $(hiddenIdTipo).val(valSelect[0]);
            $(ulIdTipo).append(html);
            $(hiddenPlaca).val('0');
            $(hiddenModelo).val('0');
            $(hiddenNombre).val(valornombre);
            }
        }else{
            swal(
                    'Validación',
                    'Los campos son obligatorios.',
                    'warning'
                )
        }
    }else if(tcustodia == "EN UNIDAD"){
        if(valorid != '') {
            var html = '<li id="elementoLista_'+valorid+'_'+claveProv[1]+'">'+$(selectSelectedIdTipo).text()+' / '+valorPlaca+' / '+valorModelo+' / '+valornombre+'<input type="checkbox" value="'+valorid+'" class="checkremove_'+tipo+'_'+claveProv[1]+'"/></li>';
            if(valor != ''){    
                $resultComp = compararRepetidos(valor, valSelect[0], valorhiddennombre,valornombre);
                if($resultComp === true) {
                    $(hiddenIdTipo).val(valor+'_'+valSelect[0]);
                    $(ulIdTipo).append(html);
                    $(hiddenPlaca).val(valorhiddenplaca+'_'+valorPlaca);
                    $(hiddenModelo).val(valorhiddenmodelo+'_'+valorModelo);
                    $(hiddenNombre).val(valorhiddennombre+'_'+valornombre);
                } else {
                    swal(
                        'Validación',
                        'El '+tipo+' ya se ha agregado, vuelva a intentarlo con otro.',
                        'warning'
                    )
                }
            }else{
            $(divUlIdTipo).html('<strong>'+titleList+'</strong> <input type="button" class="quitarlista btn btn-danger btn-sm" data-lista="'+ulIdTipo+'" data-afectado="'+hiddenIdTipo+'" data-tipo="checkremove_'+tipo+'_'+claveProv[1]+'" value="Quitar de la lista" /> <label for="seleccionartodo_'+claveProv[1]+'"><input type="checkbox" class="seleccionartodo" id="seleccionartodo_'+claveProv[1]+'" data-tipo="'+tipo+'" data-clave="'+claveProv[1]+'"/>Seleccionar todo</label>');
            $(hiddenIdTipo).val(valSelect[0]);
            $(ulIdTipo).append(html);
            $(hiddenPlaca).val(valorPlaca);
            $(hiddenModelo).val(valorModelo);
            $(hiddenNombre).val(valornombre);
            }
        }else{
            swal(
                    'Validación',
                    'Los campos son obligatorios.',
                    'warning'
                )
        }
    }
});
function compararRepetidos(actuales, valorABuscar, nombreactuales, nombreabuscar) {
    var valoresActuales = actuales.split('_');
    var valorABuscarAct = valorABuscar.split('_');
    var valoresNombAct = nombreactuales.split('_');
    var valorNomABusc = nombreabuscar.split('_');
    if(Array.isArray(valoresActuales) == true) {
        if(valoresActuales.includes(valorABuscarAct[0]) == 1 && valoresNombAct.includes(valorNomABusc[0]) == 1) {
            return false;
        }
    } else {
        if(valorABuscarAct == valoresActuales || valorNomABusc == valoresNombAct) {
            return false;
        }
    }
    return true;
}
function mayus(e) {
    e.value = e.value.toUpperCase();
}

$(document).on('click', '.quitarlista', function(){
    var classCheckstipo = $(this).data('tipo');
    var idDelAfectado = $(this).data('afectado');
    var valorChecks = $('.'+classCheckstipo+':checked');
    var valorAfectado = $(idDelAfectado).val();
    var valorAfectadoArray = valorAfectado.split('_');
    var identificador = idDelAfectado.split('_');
    var resultAfectado = '';
    if(valorChecks.length > 0) {
        valorChecks.each(function() {
            resultAfectado = quitarElementos(valorAfectadoArray, $(this).val(), identificador[1]);
            $(idDelAfectado).val(resultAfectado);
        });
        
    } else {
        swal(
            'Remover de la lista',
            'Debe seleccionar un elemento de la lista para remover.',
            'warning'
        )
    }
});
function quitarElementos (arr, item, identificador) {
    var placa = $('#hiddenPlaca_'+identificador).val();
    var placa_ref ='#hiddenPlaca_'+identificador;
    var modelo = $('#hiddenModelo_'+identificador).val();
    var modelo_ref ='#hiddenModelo_'+identificador;
    var nombre = $('#hiddenNombre_'+identificador).val();
    var nombre_ref ='#hiddenNombre_'+identificador;
    placa = placa.split('_');
    modelo = modelo.split('_');
    nombre = nombre.split('_');
    var i = arr.indexOf( item );
    placa.splice(i, 1);
    modelo.splice(i, 1);
    nombre.splice(i, 1);
    quitarExt(placa,placa_ref);
    quitarExt(modelo,modelo_ref);
    quitarExt(nombre,nombre_ref);
    var result = '';
    $('#elementoLista_'+item+'_'+identificador).remove();
    arr.splice(i, 1);
    var count = 1; 
    arr.forEach(function(valor, index) {
        if(count == arr.length) {
            result += valor;
        } else {
            result += valor+'_';
            count = count+1;
        }
    });
    return result;
}
function quitarExt(arr,desc_ref){
    var result = '';
    var count = 1;
    arr.forEach(function(valor, index) {
        if(count == arr.length) {
            result += valor;
        } else {
            result += valor+'_';
            count = count+1;
        }
    });
    $(desc_ref).val(result);
}
</script>
@endpush