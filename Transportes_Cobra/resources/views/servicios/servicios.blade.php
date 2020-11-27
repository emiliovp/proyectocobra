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
<form method="POST" action="{{route('storedProveedor')}}" id="form-proveedores" accept-charset="UTF-8" enctype="multipart/form-data">
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
                    <h5 class="card-title">Captura de servicios</h5>
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
                            <label for="destino">Destino:</label>
                            <input type="text" class="form-control lg-4 campo-requerido" id="destino" name="destino" value ="{{ $data['destino'] }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label for="salida">fecha de salida:</label>
                            <input type="text" class="form-control lg-4 campo-requerido" id="destino" name="destino" value ="{{ $data['fecha_inicio'] }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label for="salida">fecha de salida:</label>
                            <input type="text" class="form-control lg-4 campo-requerido" id="destino" name="destino" value ="{{ $data['fecha_termino'] }}" disabled>
                        </div> 
                    </div>
                    <div class="form-group row">
                        
                    </div>
                </div>
            </div>
            <div class="accordion" id="accordionSeccionesApps">
                @foreach($servicios AS $keys => $valservicio)
                <div class="card" >
                    <div class="card-header ">
                        <h5 class="card-title">{{ $valservicio['servicios_solicitud'] }}</h5>
                    </div>

                    <div class="card-body">
                    <input type="hidden" name="servicioSolicitud" id="servicioSolicitud" value="{{ $valservicio['control_servicio'] }}"/>
                        <div class="row">
                            <div class="col-md-5">
                                <label for="responsable_{{ $valservicio['control_servicio'] }}" >Responsable de {{ $valservicio['servicios_solicitud'] }}</label>
                                <select id="responsable_{{ $valservicio['control_servicio'] }}" name="responsable_{{ $valservicio['control_servicio'] }}"  class ="form-control">
                                <option value="">Seleccione una opción</option>
                                @foreach($proveedores AS $keys => $value)
                                    @if($valservicio['idservicio'] == $value['idservicio'])
                                    <option value="{{ $value['idproveedor'] }}">{{ $value['proveedor'] }}</option>                                                              
                                    @endif
                                @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                            @foreach($proveedores AS $keys => $val)
                                @if($val['nom_servicio'] == 'MANIOBRA' && $valservicio['idservicio'] == $val['idservicio'])
                                @php 
                                    $nombre = 'Numero de recursos';
                                    $idprov = $val['idproveedor'];
                                @endphp
                                @elseif($val['nom_servicio'] == 'TRANSPORTE' && $valservicio['idservicio'] == $val['idservicio'])
                                @php $nombre = 'Placas de unidad';  $idprov = $val['idproveedor']; @endphp                       
                                @elseif($val['nom_servicio'] == 'CUSTODIA' && $valservicio['idservicio'] == $val['idservicio'])
                                @php $nombre = 'Nombre del custodio'; @endphp
                                @endif
                            @endforeach
                            <label for="descripcionprov_{{ $valservicio['control_servicio'] }}" >{{ $nombre }}</label>
                            <input class="form-control" id="descripcionprov_{{ $valservicio['control_servicio'] }}" name ="descripcionprov_{{ $valservicio['control_servicio'] }}"/>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <input type="hidden" class="campo-requerido" data-servicio="{{ $valservicio['servicio'] }}" name="hiddenProveedor_{{ $valservicio['control_servicio'] }}" id="hiddenProveedor_{{ $valservicio['control_servicio'] }}"/>
                                <input type="hidden" class="campo-requerido" name="hiddenDescripcion_{{ $valservicio['control_servicio'] }}" id="hiddenDescripcion_{{ $valservicio['control_servicio'] }}"/>
                                <button id="addproveedor_{{ $valservicio['control_servicio'] }}" type="button" class="form-control btn btn-primary btn-add" >Agregar</button>
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
                </div>
                @endforeach
            </div>
            <div class="card" >
                <div class="card-header">
                    <h5 class="card-tittle">Sectorista</h5>
                </div>
                <div class="card-body" >
                    <div class="row">
                        <div class="col-md-5">
                            <label for="sectorista">Seleccione el sectorista</label>
                            <select class="form-control campo-requerido" name="sectorista" id="sectorista">
                                <option value="">Seleccione una opcion...</option>
                                @foreach($sectorista AS $keys => $value)
                                    <option value="{{ $value['idsectorista'] }}">{{ $value['nombre_usuario'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row" style="margin-top:10px;">
                <div class="col-md-6">
                    <a href="{{ route('catalogos') }}" class="btn btn-warning btn-block" style="color:#FFFFFF;">{{ __('Regresar') }}</a>
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
            $('#form-proveedores').submit();
        }
    });
});
$('#form-proveedores').validate({
    ignore: "",
    submitHandler: function(form) {
        mostrarLoading();
        setTimeout(form.submit(), 500);
    }
});
$('.btn-add').click(function(){
    var idBtnControl = $(this).attr('id');
    var claveProv = idBtnControl.split('_');
    var tipo = 'proveedor';
    var hiddenIdTipo = '#hiddenProveedor_'+claveProv[1];
    var hiddenDescripcion = '#hiddenDescripcion_'+claveProv[1];
    var selectSelectedIdTipo = '#responsable_'+claveProv[1]+' option:selected';
    var SelectedId = '#responsable_'+claveProv[1];
    var divUlIdTipo = '#content_list_proveedor_'+claveProv[1];
    var ulIdTipo = '#list_proveedor_'+claveProv[1];
    var titleList = 'Lista de proveedores agregados';
    var clave_serv = $(hiddenIdTipo).data('servicio');
    var valor = $(hiddenIdTipo).val();
    var valSelect = $(selectSelectedIdTipo).val();
    var valorid = $(SelectedId).val();
    var val_desc = $('#descripcionprov_'+claveProv[1]).val();
    var val_hidden_desc = $(hiddenDescripcion).val();
    if (valorid !='' && val_desc !='') {
        var html = '<li id="elementoLista_'+valorid+'_'+claveProv[1]+'">'+$(selectSelectedIdTipo).text()+' / '+val_desc+' <input type="checkbox" value="'+valorid+'" class="checkremove_'+tipo+'_'+claveProv[1]+'"/></li>';
        valSelect = valSelect.split('_');
        if(valor != '' ) {
            if (clave_serv == 'CUSTODIA') {
                var valor2 = $(hiddenDescripcion).val();
                var valSelect2 = $('#descripcionprov_'+claveProv[1]).val();
                valSelect2 = valSelect2.split('_');
                $resultComp = compararRepetidos(valor2, valSelect2[0]);
            }else{
                $resultComp = compararRepetidos(valor, valSelect[0]);
            }
            if($resultComp === true) {
                $(hiddenIdTipo).val(valor+'_'+valSelect[0]);
                $(ulIdTipo).append(html);
                $(hiddenDescripcion).val(val_hidden_desc+'_'+val_desc);
            } else {
                swal(
                    'Validación',
                    'El '+tipo+' ya se ha agregado, vuelva a intentarlo con otro.',
                    'warning'
                )
            }
        } else {
            $(divUlIdTipo).html('<strong>'+titleList+'</strong> <input type="button" class="quitarlista btn btn-danger btn-sm" data-lista="'+ulIdTipo+'" data-afectado="'+hiddenIdTipo+'" data-tipo="checkremove_'+tipo+'_'+claveProv[1]+'" value="Quitar de la lista" /> <label for="seleccionartodo_'+claveProv[1]+'"><input type="checkbox" class="seleccionartodo" id="seleccionartodo_'+claveProv[1]+'" data-tipo="'+tipo+'" data-clave="'+claveProv[1]+'"/>Seleccionar todo</label>');
            $(hiddenIdTipo).val(valSelect[0]);
            $(ulIdTipo).append(html);
            $(hiddenDescripcion).val(val_desc);
        }
    }else{
        swal(
                'Validación',
                'Los campos son obligatorios.',
                'warning'
            )
    }
});
function compararRepetidos(actuales, valorABuscar) {
    var valoresActuales = actuales.split('_');
    var valorABuscarAct = valorABuscar.split('_');
    console.log(valoresActuales);
    console.log(valorABuscarAct);
    if(Array.isArray(valoresActuales) == true) {
        if(valoresActuales.includes(valorABuscarAct[0]) == 1) {
            return false;
        }
    } else {
        if(valorABuscarAct == valoresActuales) {
            return false;
        }
    }
    return true;
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
    var desc = $('#hiddenDescripcion_'+identificador).val();
    var desc_ref ='#hiddenDescripcion_'+identificador;
    desc = desc.split('_');
    var i = arr.indexOf( item );
    desc.splice(i, 1);
    quitarDesc(desc,desc_ref);
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
function quitarDesc(arr,desc_ref){
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