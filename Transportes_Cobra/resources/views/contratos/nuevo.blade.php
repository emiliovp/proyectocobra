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
<form method="POST" action="{{route('storedcontrato')}}" id="form-contrato" accept-charset="UTF-8" enctype="multipart/form-data">
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
                    Alta de cliente.
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="cliente">cliente cliente</label>
                            <input type="hidden" name="idcliente" id="idcliente"/>
                            <input class="form-control campo-requerido autocomplete" data-type="cliente" id="cliente" name="cliente"/>
                        </div>
                        <div class="col-md-4">
                            <label for="bodega">Bodega</label>
                            <select class="form-control campo-requerido" name="bodega" id="bodega">
                                <option value="">Seleccione una opción valida</opcition>
                                    @foreach($bodega as $val)
                                        @php
                                            $selected = "";
                                        @endphp
                                        @if ($errors)
                                            @if($val['id'] == old('bodega'))
                                                @php
                                                    $selected = "selected";
                                                @endphp
                                            @endif
                                        @endif
                                    <option {{ $selected }} value="{{ $val['id'] }}">{{ $val['nombreBodega'] }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="precio">Precio</label>
                            <input class="form-control campo-requerido" id="precio" name="precio"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="finicio">Fecha de inicio</label>
                            <input class="form-control campo-requerido" name="finicio" id="finicio"></input>
                        </div>
                        <div class="col-md-4">
                            <label for="ftermino">Fecha de termino</label>
                            <input class="form-control campo-requerido" name="ftermino" id="ftermino"></input>
                        </div>
                        <div class="col-md-4">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control campo-requerido" id="observaciones" name="observaciones"></textarea>
                        </div>   
                    </div>
                    <div class="form-group row">
                        
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('contratos') }}" id="regresar" class="btn btn-warning">Regresar</a>
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
            $('#form-contrato').submit();
        }
    });
});
$('#form-contrato').validate({
    ignore: "",
    submitHandler: function(form) {
        mostrarLoading();
        setTimeout(form.submit(), 500);
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
                $('#idcliente').val(data.id);
            }
        }
    });
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
    dateFormat: 'yy-mm-dd',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
};
$.datepicker.setDefaults($.datepicker.regional['es']);
$(function () {
    $('#finicio').datepicker();
    $('#ftermino').datepicker();
});
</script>
@endpush