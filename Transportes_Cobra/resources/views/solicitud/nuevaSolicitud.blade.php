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
<form method="POST" action="{{route('storedusuario')}}" id="form-solicitud" accept-charset="UTF-8" enctype="multipart/form-data">
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
                    <h5 class="card-title">Solicitud de programación</h5>
                </div>
                <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Datos generales del movimiento</h6>
                <hr/>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="tmovimiento">Tipo de movimiento:</label>
                            <select class="form-control" name="tmovimiento" id="tmovimiento">
                                <option value="">Seleccione una opción valida</opcition>
                                    @foreach($movimiento as $val)
                                        @php
                                            $selected = "";
                                        @endphp
                                        @if ($errors)
                                            @if($val['id'] == old('tmovimiento'))
                                                @php
                                                    $selected = "selected";
                                                @endphp
                                            @endif
                                        @endif
                                    <option {{ $selected }} value="{{ $val['id'] }}">{{ $val['nombre'] }}</option>
                                    @endforeach
                            </select>
                            <!--<input type="text" class="form-control lg-4 campo-requerido {{ $errors->has('tmovimiento') ? ' is-invalid' : '' }}" id="tmovimiento" name="tmovimiento" value="{{ old('tmovimiento') }}">-->
                            @if ($errors->has('tmovimiento'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('tmovimiento')}}</strong>
                            </span>   
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label for="cliente">cliente:</label>
                            <input type="hidden" name ="clienteid" id="clienteid" value="" />
                            <input type="text" disable class="form-control lg-4 campo-requerido {{ $errors->has('cliente') ? ' is-invalid' : '' }} autocomplete" data-type="cliente" value="{{ old('cliente') }}" id="cliente" name="cliente">
                            @if ($errors->has('cliente'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('cliente')}}</strong>
                            </span>   
                            @endif                     
                        </div> 
                        <div class="col-md-4">
                            <label for="bodega">Bodega:</label>
                            <!--<input type="text" class="form-control lg-4" id="bodega" name="bodega" value="{{ old('bodega') }}">-->
                            <select class="form-control campo-requerido" id="bodega" name="bodega" data-padre="padre">
                                <option value="">Seleccione una opción valida</option>
                            </select>
                        </div>    
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="pautorizada">Persona autorizada:</label>
                            <input type="text" class="form-control lg-4 campo-requerido" id="pautorizada" name="pautorizada" value="{{ old('pautorizada') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="fecprogramada">Fecha y hora programada:</label>
                            <input type="text" class="form-control lg-4 campo-requerido datetimepicker" id="fecprogramada" name="fecprogramada" value="{{ old('fecprogramada') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="tmercancia">Tipo de mercancia:</label>
                            <input type="text" class="form-control lg-4 campo-requerido" id="tmercancia" name="tmercancia" value="{{ old('tmercancia') }}">
                        </div>   
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="lsalida">Lugar de salida:</label>
                            <input class="form-control" name="salida" id="salida" />
                            @if ($errors->has('lsalida'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('lsalida')}}</strong>
                            </span>   
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label for="destino">Destino:</label>
                            <input type="text" class="form-control lg-4 campo-requerido" id="destino" name="destino" value="{{ old('destino') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header ">
                    <h5 class="card-title">Servicios</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="tservicio">Tipo de servicio:</label>
                            <select class="form-control" name="tservicio" id="tservicio">
                            <option value="">Seleccione una opción valida</opcition>
                                    @foreach($servicio as $val)
                                        @php
                                            $selected = "";
                                        @endphp
                                        @if ($errors)
                                            @if($val['id'] == old('tmovimiento'))
                                                @php
                                                    $selected = "selected";
                                                @endphp
                                            @endif
                                        @endif
                                    <option {{ $selected }} value="{{ $val['id'] }}">{{ $val['nombre'] }}</option>
                                    @endforeach
                            </select>
                            @if ($errors->has('tservicio'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('tservicio')}}</strong>
                            </span>   
                            @endif                     
                        </div>
                        <div class="col-md-4">
                            <label for="tmovimiento">Detalle de servicio:</label>
                            <select class="form-control" name="detservicio" id="detservicio"></select>
                            <!-- <input type="text" class="form-control lg-4 campo-requerido {{ $errors->has('tmovimiento') ? ' is-invalid' : '' }}" id="tmovimiento" name="tmovimiento" value="{{ old('tmovimiento') }}">
                            -->
                            @if ($errors->has('tmovimiento'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('tmovimiento')}}</strong>
                            </span>   
                            @endif
                        </div> 
                        <div class="col-md-4">
                            <label for="not_ad">Notas adicionales:</label>
                            <input type="text" class="form-control lg-4" id="not_ad" name="not_ad" value="{{ old('not_ad') }}">
                        </div>
                        <div class="col-md-1">
                            <label>&nbsp;</label>
                            <input type="hidden" name="hiddentservicio" id="hiddentservicio"/>
                            <input type="hidden" name="hiddenNotasAd" id="hiddenNotasAd"/>
                            <button id="addservivcio" name="addservivcio" type="button" class="form-control btn btn-primary btn-add" disabled><i class="fas fa-plus"></i></button>
                        </div>    
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div id="content_list_servicio" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_servicio">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header ">
                    <h5 class="card-title">Datos de control del inventario</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="descripcion">Descripción del producto:</label>
                            <input class="form-control lg-4" nombre="descripcion" id="descripcion"/>
                        </div>
                        <div class="col-md-4">
                            <label for="contenedor">Contenedor:</label>
                            <input class="form-control" name="contenedor" id="contenedor"/>
                        </div>
                        <div class="col-md-4">
                            <label for="cantidad">Cantidad:</label>
                            <input class="form-control" id="cantidad" name="cantidad"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label from="tproducto">Tipo de producto</label>
                            <input class="form-control" id="tproducto" name="tproducto"/>
                        </div>
                        <div class="col-md-4">
                            <label from="notasadinv">Notas adicionales</label>
                            <input class="form-control" id="notasadinv" name="notasadinv"/>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <input type="hidden" name="hiddenModuloPad" id="hiddenModuloPad"/>
                            <input type="hidden" name="hiddenModulo" id="hiddenModulo"/>
                            <button id="addperfil" type="button" class="form-control btn btn-primary btn-add" disabled>Agregar</button>
                        </div>
                    </div>
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
            $('#form-solicitud').submit();
        }
    });
});
$('#form-solicitud').validate({
    ignore: "",
    submitHandler: function(form) {
        mostrarLoading();
        setTimeout(form.submit(), 500);
    }
});
$.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '< Ant',
 nextText: 'Sig >',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 changeMonth: true,
 changeYear: true,
 weekHeader: 'Sm',
 dateFormat: 'yy-mm-dd H:i',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);
$(function () {
    $('#fecprogramada').datepicker();
});
$(document).on('change','#tservicio', function(){
    var opt = $(this).val();
    $('#detservicio').empty();
    $('#detservicio').append("<option value=''>Selecciona una opcion valida</option>");
    console.log(opt);
    if (opt !=="") {
        $.ajax({
            url: "{{ route('getopt') }}",
            dataType: "JSON",
            data:{option: opt
            },beforeSend: function(){
                mostrarLoading();
            },success: function(response) {
                ocultarLoading();
                $.each(response,function(index,value){
                    $('#detservicio').append("<option value='"+value.id+"'>"+value.nombre+"</option>");
                })
                $('#addservivcio').removeAttr("disabled");//habilita boton
            }
        });
    }
});
$(document).on('focus','.autocomplete',function(){
    type = $(this).data('type');
    $('#clienteid').val('');
    if(type =='cliente')autoType='nombre';
    $(this).autocomplete({
        minLength:0,
        source: function(request, response){
            $.ajax({
                url: "{{ route('autocomplete') }}",
                dataType: "json",
                data:{ 
                    term: request.term,
                    type: type,
                },success: function(data){
                    var array = $.map(data, function(item){
                        var response = "";
                        if (item[autoType] !== undefined) {
                            response={
                                label: item[autoType],
                                value: item[autoType],
                                data: item
                            }
                        }else{
                            response = {
                                    label: item,
                                    value: item,
                                    data: "fail"
                                }
                        }
                        return response;
                    });
                    response(array);
                }
            });
        },select: function(event, ui){
            var data = ui.item.data;
            if (data.respuesta != "No se encontro el registro")
            {
                $('#clienteid').val(data.id);
                $('#pautorizada').val(data.responsable);
                getbodega(data.id);
            }
        }
    });
});
function getbodega(id){
    $('#bodega').empty();
    $('#bodega').append("<option value=''>Selecciona una opcion valida</option>");
    $.ajax({
        url: "{{ route('getbodega') }}",
        dataType: "json",
        data: {
            idcliente: id
        },beforeSend: function(){
            mostrarLoading();
        },success: function(data){
            ocultarLoading();
            $.each(data, function(index, value){
                $('#bodega').append("<option value='"+value.id+"'>"+value.clave+"</option>");
            });
        }
    });
}
$('.btn-add').click(function(){    

        var tipo = 'servicio';
        var hiddenIdTipo = '#hiddentservicio';
        var selectSelectedIdTipo = '#tservicio option:selected';
        var divUlIdTipo = '#content_list_servicio';
        var ulIdTipo = '#list_servicio';
        var titleList = 'Lista de servicios agregados';
        if($('#detservicio').val() != '') {
            var valor = $(hiddenIdTipo).val();
            var valor_not = $('#hiddenNotasAd').val();
            var valSelect = $('#detservicio').val();
            var tservicio = $('#tservicio option:selected').text();
            var detservicio = $('#detservicio option:selected').text();
            var notAd = $('#not_ad').val();
            var html = '<li id="elementoLista_'+valSelect+'">'+tservicio+' '+detservicio+', '+notAd+' <input type="checkbox" value="'+valSelect+'" class="checkremove_'+tipo+'"/></li>';
            valSelect = valSelect.split('_');
            if(valor != '') {
                if(compararRepetidos(valor, valSelect[0]) === true) {
                    $(hiddenIdTipo).val(valor+'_'+valSelect[0]);
                    $(ulIdTipo).append(html);
                    if (notAd == ''){
                        notAd = 0;
                    }
                    $('#hiddenNotasAd').val(valor_not+'_'+notAd);
                    $('#not_ad').val('');
                } else {
                    swal(
                        'Validación',
                        'El '+tipo+' ya se ha agregado, vuelva a intentarlo con otro.',
                        'warning'
                    )
                }
            }else {
                $(divUlIdTipo).html('<strong>'+titleList+'</strong> <input type="button" class="quitarlista btn btn-danger btn-sm" data-lista="'+ulIdTipo+'" data-afectado="'+hiddenIdTipo+'" data-tipo="checkremove_'+tipo+'" value="Quitar de la lista" /> <label for="seleccionartodo"><input type="checkbox" class="seleccionartodo" id="seleccionartodo" data-tipo="'+tipo+'" data-clave=""/>Seleccionar todo</label>');
                $(hiddenIdTipo).val(valSelect[0]);
                $(ulIdTipo).append(html);
                if (notAd == ''){
                    notAd = 0;
                }
                $('#hiddenNotasAd').val(notAd);
                $('#not_ad').val('');
            }
        } else {
            swal(
                'Validación',
                'Debe seleccionar un '+tipo+' antes de intentar agregarlo.',
                'warning'
            )
        }
    return;
});
function compararRepetidos(actuales, valorABuscar) {
    var valoresActuales = actuales.split('_');
    var valorABuscarAct = valorABuscar.split('_');

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
</script>
@endpush