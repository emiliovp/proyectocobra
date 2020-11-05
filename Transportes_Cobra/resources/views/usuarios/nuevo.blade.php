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
<form method="POST" action="{{route('storedusuario')}}" id="form-usuario" accept-charset="UTF-8" enctype="multipart/form-data">
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
                    Alta de usuario.
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="nombrep">Nombre:</label>
                            <input type="text" class="form-control lg-4 campo-requerido {{ $errors->has('nombrep') ? ' is-invalid' : '' }}" value="{{ old('nombrep') }}" id="nombrep" name="nombrep">
                            @if ($errors->has('nombrep'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('nombrep')}}</strong>
                            </span>   
                            @endif                     
                        </div>
                        <div class="col-md-4">
                            <label for="apaterno">Apellido paterno:</label>
                            <input type="text" class="form-control lg-4 campo-requerido {{ $errors->has('apaterno') ? ' is-invalid' : '' }}" id="apaterno" name="apaterno" value="{{ old('apaterno') }}">
                            @if ($errors->has('apaterno'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('apaterno')}}</strong>
                            </span>   
                            @endif
                        </div> 
                        <div class="col-md-4">
                            <label for="amaterno">Apellido materno:</label>
                            <input type="text" class="form-control lg-4" id="amaterno" name="amaterno" value="{{ old('amaterno') }}">
                        </div>    
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="usuario">Usuario:</label>
                            <input type="text" class="form-control lg-4 campo-requerido" id="usuario" name="usuario" value="{{ old('usuario') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="password1">Contraseña:</label>
                            <input type="password" class="form-control lg-4 campo-requerido" id="password1" name="password1" value="{{ old('password1') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="password2">Confirmar contraseña:</label>
                            <input type="password" class="form-control lg-4 campo-requerido" id="password2" name="password2" value="{{ old('password2') }}">
                        </div>   
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="perfil">Perfil / área:</label>
                            <select class="form-control campo-requerido" id="perfil" name="perfil" value="{{ old('perfil') }}">
                                <option value="">Seleccione...</option>
                                @foreach($perfil as $val)
                                    @php
                                        $selected = "";
                                    @endphp
                                    @if ($errors)
                                        @if($val['perfil_id'] == old('area'))
                                            @php
                                                $selected = "selected";
                                            @endphp
                                        @endif
                                    @endif
                                <option {{ $selected }} value="{{ $val['perfil_id'] }}">{{ $val['per_area'] }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('perfil'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('perfil')}}</strong>
                            </span>   
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label for="correo">Correo electronico:</label>
                            <input type="text" class="form-control lg-4 campo-requerido" id="correo" name="correo" value="{{ old('correo') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="telefono">Telefono:</label>
                            <input type="text" class="form-control lg-4" id="telefono" name="telefono" value="{{ old('telefono') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="ext">Extención:</label>
                            <input type="text" class="form-control lg-4" id="ext" name="ext" value="{{ old('ext') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('ListaUsuarios') }}" id="regresar" class="btn btn-warning">Regresar</a>
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
            $('#form-usuario').submit();
        }
    });
});
$('#form-usuario').validate({
    ignore: "",
    submitHandler: function(form) {
        mostrarLoading();
        setTimeout(form.submit(), 500);
    }
});
</script>
@endpush