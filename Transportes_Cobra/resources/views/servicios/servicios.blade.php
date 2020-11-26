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
<form method="POST" action="{{route('storedsolicitud')}}" id="form-solicitud" accept-charset="UTF-8" enctype="multipart/form-data">
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
                        <div class="row">
                            <div class="col-md-5">
                                <label for="responsable_{{ $valservicio['idservicio'] }}" >Responsable de {{ $valservicio['servicios_solicitud'] }}</label>
                                <select class ="form-control">
                                <option val="">Seleccione una opción</option>
                                @foreach($proveedores AS $keys => $value)
                                    @if($valservicio['idservicio'] == $value['idservicio'])
                                    <option val="{{ $value['idproveedor'] }}">{{ $value['proveedor'] }}</option>                                                              
                                    @endif
                                @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                            @foreach($proveedores AS $keys => $val)
                                @if($val['nom_servicio'] == 'MANIOBRA' && $valservicio['idservicio'] == $val['idservicio'])
                                @php $nombre = 'Numero de recursos'; @endphp
                                @elseif($val['nom_servicio'] == 'TRANSPORTE' && $valservicio['idservicio'] == $val['idservicio'])
                                @php $nombre = 'Placas de unidad'; @endphp                       
                                @elseif($val['nom_servicio'] == 'CUSTODIA' && $valservicio['idservicio'] == $val['idservicio'])
                                @php $nombre = 'Nombre del custodio'; @endphp
                                @endif
                            @endforeach
                            <label for="descripcionprov_{{ $value['idproveedor'] }}" >{{ $nombre }}</label>
                            <input class="form-control" id="descripcionprov_{{ $value['idproveedor'] }}" name ="descripcionprov_{{ $value['idproveedor'] }}"/>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <input type="hidden" name="hiddenModuloPad" id="hiddenModuloPad"/>
                                <input type="hidden" name="hiddenModulo" id="hiddenModulo"/>
                                <button id="addperfil" type="button" class="form-control btn btn-primary btn-add" >Agregar</button>
                            </div> 
                        </div>
                    </div>
                </div>
                @endforeach
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
    if($(this).attr('id') == "addservivcio") {
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
    }else if( $(this).attr('id')  == "addInvDat"){
        var tipo = 'inventario';
        var hiddenIdTipo = '#hiddenidcontrol';
        var ulIdTipo = '#list_control_inv';

        var descp = $('#descripcion').val();
        var contenedor = $('#contenedor').val();
        var cantidad = $('#cantidad').val();
        var tipo_producto = $('#tproducto').val();
        var notas_adicionales_inv = $('#notasadinv').val();
        
        var valor_desc = $('#hiddenDescripcion').val();
        var valor_cont = $('#hiddenContenedor').val();
        var valor_cant = $('#hiddenCantidad').val();
        var valor_tprod = $('#hiddentproducto').val();       
        var valor_not = $('#hiddennotadinv').val();
        if (descp == '' || contenedor == '' || cantidad == '' || tipo_producto == '') {
            swal(
                'Validación',
                'Debe capturar los datos obligatorios.',
            )
        }else{
            var controlid = $('#hiddenidcontrol').val();
            control = controlid.split('_');
            if (control == '') {
                var control_id = 1;
            }else{
               var control_id =  Math.max.apply(null, control) + 1;
            }
            var html = '<li id="elementoLista_'+control_id+'">'+descp+' - '+contenedor+' - '+cantidad+' - '+tipo_producto+' - '+notas_adicionales_inv+' <input type="checkbox" value="'+control_id+'" class="'+tipo+'"/></li>';
            if (control == '') {
                $('#content_list_control_inv').html('<strong>Lista de control de inventario</strong> <input type="button" class="quitarlista btn btn-danger btn-sm" data-lista="'+ulIdTipo+'" data-afectado="'+hiddenIdTipo+'" data-tipo="'+tipo+'" value="Quitar de la lista" /> <label for="seleccionartodo"><input type="checkbox" class="seleccionartodo" id="seleccionartodo" data-tipo="'+tipo+'" data-clave=""/>Seleccionar todo</label>');
                $('#hiddenidcontrol').val(control_id);
                $('#list_control_inv').append(html);
                if (notas_adicionales_inv == ''){
                    notas_adicionales_inv = 0;
                }
                $('#hiddenDescripcion').val(descp);
                $('#hiddenContenedor').val(contenedor);
                $('#hiddenCantidad').val(cantidad);
                $('#hiddentproducto').val(tipo_producto);
                $('#hiddennotadinv').val(notas_adicionales_inv);
                $('#descripcion').val('');
                $('#contenedor').val('');
                $('#cantidad').val('');
                $('#tproducto').val('');
                $('#notasadinv').val('');
            }else{
                $('#hiddenidcontrol').val(controlid+'_'+control_id);
                $('#list_control_inv').append(html);
                if (notas_adicionales_inv == ''){
                    notas_adicionales_inv = 0;
                }
                $('#hiddenDescripcion').val(valor_desc+'_'+descp);
                $('#hiddenContenedor').val(valor_cont+'_'+contenedor);
                $('#hiddenCantidad').val(valor_cant+'_'+cantidad);
                $('#hiddentproducto').val(valor_tprod+'_'+tipo_producto);
                $('#hiddennotadinv').val(valor_not+'_'+notas_adicionales_inv);
                $('#descripcion').val('');
                $('#contenedor').val('');
                $('#cantidad').val('');
                $('#tproducto').val('');
                $('#notasadinv').val('');
            }
        }

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
$(document).on('click', '.quitarlista', function(){
    var classCheckstipo = $(this).data('tipo');
    console.log(classCheckstipo);
    if (classCheckstipo == 'inventario'){
        var idDelAfectado = $(this).data('afectado');
        var valorChecks = $('.'+classCheckstipo+':checked');
        var valorAfectado = $(idDelAfectado).val();
        var valorAfectadoArray = valorAfectado.split('_');
        var resultAfectado = '';
        if(valorChecks.length > 0) {
            valorChecks.each(function() {
                resultAfectado = quitarElementosInventarios(valorAfectadoArray, $(this).val());
                $(idDelAfectado).val(resultAfectado);
            });          
        } else {
            swal(
                'Remover de la lista',
                'Debe seleccionar un elemento de la lista para remover.',
                'warning'
            )
        }
    }else{
        var idDelAfectado = $(this).data('afectado');
        var valorChecks = $('.'+classCheckstipo+':checked');
        var valorAfectado = $(idDelAfectado).val();
        var valorAfectadoArray = valorAfectado.split('_');
        var resultAfectado = '';
        if(valorChecks.length > 0) {
            valorChecks.each(function() {
                resultAfectado = quitarElementos(valorAfectadoArray, $(this).val());
                $(idDelAfectado).val(resultAfectado);
            });          
        } else {
            swal(
                'Remover de la lista',
                'Debe seleccionar un elemento de la lista para remover.',
                'warning'
            )
        }
    }
});
function quitarElementos (arr, item) {
    var notas = $('#hiddenNotasAd').val();
    notas = notas.split('_');
    var i = arr.indexOf( item );
    notas.splice(i, 1);
    quitarNotas(notas);
    var result = '';
    $('#elementoLista_'+item).remove();
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
function quitarNotas(arr){
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
    $('#hiddenNotasAd').val(result);
}
function quitarElementosInventarios (arr, item) {
    var i = arr.indexOf( item );

    var descripcion = $('#hiddenDescripcion').val();
    var contenedor = $('#hiddenContenedor').val();
    var cantidad = $('#hiddenCantidad').val();
    var tproducto = $('#hiddentproducto').val();
    var notas = $('#hiddennotadinv').val();
    descripcion = descripcion.split('_');
    descripcion.splice(i, 1);
    quitarComplementos(descripcion, '#hiddenDescripcion');

    contenedor = contenedor.split('_');
    contenedor.splice(i, 1);
    quitarComplementos(contenedor, '#hiddenContenedor');

    cantidad = cantidad.split('_');
    cantidad.splice(i, 1);
    quitarComplementos(cantidad, '#hiddenCantidad');

    tproducto = tproducto.split('_');
    tproducto.splice(i, 1);
    quitarComplementos(tproducto, '#hiddentproducto');

    notas = notas.split('_');
    notas.splice(i, 1);
    quitarComplementos(notas, '#hiddennotadinv');

    var result = '';
    $('#elementoLista_'+item).remove();
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
function quitarComplementos(arr, idinp){
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
    $(idinp).val(result);
}
</script>
@endpush