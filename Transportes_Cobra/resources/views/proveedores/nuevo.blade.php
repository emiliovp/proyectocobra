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
<form method="POST" action="{{route('storedprov')}}" id="form-prov" accept-charset="UTF-8" enctype="multipart/form-data">
@csrf
<div class="container-fluid">
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
                <div class="card-header">
                    Alta de proveedor.
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nombreprov">Proveedor:</label>
                            <input tipe="text" class="form-control lg-4 campo-requerido{{ $errors->has('nombreprov') ? ' is-invalid' : '' }}" value="{{ old('nombreprov') }}" id="nombreprov" name="nombreprov">
                            @if ($errors->has('nombreprov'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('nombreprov')}}</strong>
                            </span>   
                            @endif                     
                        </div>
                        <div class="col-md-6">
                            <label for="descipcion">Descripci&oacute;n:</label>
                            <input tipe="text" class="form-control lg-4" id="descipcion" name="descipcion" value="{{ old('descipcion') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="servicios">Servicios</label>
                            <select class="form-control" id="servicios" name="servicios" data-padre="servicios">
                                <option value="">Seleccione...</option>
                                @foreach($servicios as $val)
                                    <option value="{{ $val['id'] }}">{{ $val['nombre'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="tservicios">Tipo de servicios</label>
                            <select multiple class="form-control" name="tservicios" id="tservicios" data-hijo="tservicios">
                                <option value="">Seleccione...</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label>&nbsp;</label>
                            <input type="hidden" class="campo-requerido" name="hiddenTipoServicios" id="hiddenTipoServicios"/>
                            <button id="addservicios" type="button" class="form-control btn btn-primary btn-add" disabled><i class="fas fa-plus"></i></button>
                        </div>   
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div id="content_list_tiposervicio" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_tiposervicio">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('proveedores') }}" id="regresar" class="btn btn-warning">Regresar</a>
                            <button type="submit" class="btn btn-primary update" id="enviar" >Guardar</button>
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
            $('#form-prov').submit();
        }
    });
});
$('#form-prov').validate({
    ignore: "",
    submitHandler: function(form) {
        mostrarLoading();
        setTimeout(form.submit(), 500);
    }
});
$(document).on('change','#servicios', function(){
    var padre = $(this).val();
    $('#tservicios').empty();
    $('#tservicios').append("<option value=''>Selecciona una opcion valida</option>");
    $.ajax({
        url: "{{ route('optser') }}",
        dataType: "JSON",
        data:{servicio: padre},
        success: function(response) {
            $.each(response,function(index,value){
                $('#tservicios').append("<option value='"+value.id+"'>"+value.nombre+"</option>");
            })
            $('#addservicios').removeAttr("disabled");//habilita boton
        }
    });
});
$('.btn-add').click(function(){    
        var tipo = 'servicio';
        var hiddenIdTipo = '#hiddenTipoServicios';
        var selectSelectedIdTipo = '#tservicios option:selected';
        var divUlIdTipo = '#content_list_tiposervicio';
        var ulIdTipo = '#list_tiposervicio';
        var titleList = 'Lista de servicios agregados';
        var servicio = '#servicios option:selected';
        if($('#tservicios').val() != '') {
            var valor = $(hiddenIdTipo).val();
            var valSelect = $(selectSelectedIdTipo).val();
            var html = '<li id="elementoLista_'+valSelect+'">'+$(servicio).text()+' / '+$(selectSelectedIdTipo).text()+' <input type="checkbox" value="'+valSelect+'" class="checkremove_'+tipo+'"/></li>';
            valSelect = valSelect.split('_');
            if(valor != '') {
                if(compararRepetidos(valor, valSelect[0]) === true) {
                    $(hiddenIdTipo).val(valor+'_'+valSelect[0]);
                    $(ulIdTipo).append(html);
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
            }
        }else{
            swal(
                'Validación',
                'Debe seleccionar un '+tipo+' antes de intentar agregarlo.',
                'warning'
            )
        }
    return;
});
$(document).on('click', '.seleccionartodo', function(){
    var clave = $(this).data('clave');
    var tipo = $(this).data('tipo');
    if($(this).is(':checked')) {
        $('.checkremove_'+tipo).prop('checked', true);
    } else {
        $('.checkremove_'+tipo).prop('checked', false);
    }
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
});
function quitarElementos (arr, item) {
    var i = arr.indexOf( item );
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
</script>
@endpush