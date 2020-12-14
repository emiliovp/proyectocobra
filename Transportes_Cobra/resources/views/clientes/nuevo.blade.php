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
<form method="POST" action="{{route('storedcliente')}}" id="form-cliente" accept-charset="UTF-8" enctype="multipart/form-data">
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
                            <label for="nombre">Nombre cliente</label>
                            <input class="form-control campo-requerido" id="nombre" name="nombre"/>
                        </div>
                        <div class="col-md-4">
                            <label for="rfc">RFC</label>
                            <input class="form-control" name="rfc" id="rfc"/>
                        </div>
                        <div class="col-md-4">
                            <label for="responsable">Responsable</label>
                            <input class="form-control campo-requerido" id="responsable" name="responsable"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="telefono">Telefono</label>
                            <input class="form-control" name="telefono" id="telefono"></input>
                        </div>
                        <div class="col-md-4">
                            <label for="extension">Extension</label>
                            <input class="form-control" name="extension" id="extension"></input>
                        </div>
                        <div class="col-md-4">
                            <label for="ubicacion">Ubicación</label>
                            <input class="form-control campo-requerido" name="ubicacion" id="ubicacion"/>
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
            $('#form-cliente').submit();
        }
    });
});
$('#form-cliente').validate({
    ignore: "",
    submitHandler: function(form) {
        mostrarLoading();
        setTimeout(form.submit(), 500);
    }
});
</script>
@endpush