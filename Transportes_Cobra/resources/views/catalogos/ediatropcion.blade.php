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
</style>
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
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Editar opción de catálogo') }}</div>
                <div class="card-body">
                    <form method="POST" id="updopcion-form" action="{{route('updateoptcat')}}">
                        @csrf
                        <input type="hidden" name="catalogos_id" id="catalogos_id" value="{{$catalogos_id}}"/>
                        <input type="hidden" name="idopt" id="idopt" value="{{$opcionAEditar['id']}}"/>
                        <input type="hidden" name="idpad" id="idpad" value="{{$padre}}"/>
                        <input type="hidden" name="optpad" id="optpad" value="{{$oppad}}"/>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="cat_op_descripcion" class="txt-bold">Nombre de la opción<span style="color: red;">*</span></label>
                                <input type="text" placeholder="Descripción de la opción..." class="form-control{{ $errors->has('cat_op_descripcion') ? ' is-invalid' : '' }} campo-requerido" value="{{ !empty(old('cat_op_descripcion')) ? old('cat_op_descripcion') : $opcionAEditar['nombre'] }}" name="cat_op_descripcion" required id="cat_op_descripcion"/>
                                        
                                @if ($errors->has('cat_op_descripcion'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('cat_op_descripcion') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="cat_id" class="txt-bold">Dependencia con catálogo</label>
                                    <select class="form-control" name="cat_id" id="cat_id">
                                        <option value="">Seleccione...</option>
                                    @foreach($catalogos AS $row)
                                        @php
                                            $selected = "";
                                        @endphp
                                        @if($row['id'] == $padre)
                                            @php
                                                $selected = "selected";
                                            @endphp
                                        @endif
                                        <option {{$selected}} value="{{$row['id']}}">{{$row["nombre"]}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="cat_opciones_id" class="txt-bold">Dependencia con otra opción</label>
                                <select class="form-control" name="cat_opciones_id" id="cat_opciones_id">
                                    <option value="">Seleccione...</option>
                                
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <a class="btn btn-warning col-md-12" style="color:#FFFFFF;" href="{{url('catalogos/listaopciones')}}/{{$catalogos_id}}">Regresar</a>
                            </div>    
                            <div class="col-md-6">
                                <input type="submit" class="btn btn-primary col-md-12" id="guardar" value="Guardar"/>
                            </div>    
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
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
$('#guardar').click(function() {
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
            $('#updopcion-form').submit();
        }
    });
});
$('#updopcion-form').validate({
    ignore: "",
    submitHandler: function(form) {
        mostrarLoading();
        setTimeout(form.submit(), 500);
    }
});
$(window).on("load",cargaropt());
function cargaropt(){
    var cat = $('#idpad').val();
    var opt = $('#optpad').val();
    $('#cat_opciones_id').empty();
    $('#cat_opciones_id').append("<option value=''>Selecciona una opcion valida</option>");
    $.ajax({
    url: "{{ route('opByCat') }}",
    dataType: "JSON",
    data:{cat: cat},
        success: function(response) {
            $.each(response,function(index,value){
                var select = '';
                if (opt == value.id) {
                    select = 'selected'
                }
                $('#cat_opciones_id').append("<option "+select+" value='"+value.id+"'>"+value.nombre+"</option>");
            
            })
        }
    });
}
$(document).on('change','#cat_id', function(){
    var cat = $(this).val();
    var opt = $('#idopt').val();
    $('#cat_opciones_id').empty();
    $('#cat_opciones_id').append("<option value=''>Selecciona una opcion valida</option>");
    $.ajax({
    url: "{{ route('opByCat') }}",
    dataType: "JSON",
    data:{cat: cat},
        success: function(response) {
            $.each(response,function(index,value){
                $('#cat_opciones_id').append("<option value='"+value.id+"'>"+value.nombre+"</option>");
            
            })
        }
    });
});
</script>
@endpush