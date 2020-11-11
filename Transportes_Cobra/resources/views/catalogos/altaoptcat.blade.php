@extends('layouts.app')

@section('content')
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
                <div class="card-header">{{ __('Alta de opciones de catálogos') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{route('storeoptcat')}}">
                        @csrf
                        <input type="hidden" name="catalogos_id" id="catalogos_id" value="{{$catalogos_id}}"/>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="cat_op_descripcion" class="txt-bold">Nombre de la opción<span style="color: red;">*</span></label>
                                <input type="text" placeholder="Descripción de la opción..." class="form-control{{ $errors->has('cat_op_descripcion') ? ' is-invalid' : '' }}" value="{{ old('cat_op_descripcion') }}" name="cat_op_descripcion" required id="cat_op_descripcion"/>
                                        
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
                                        <option value="{{$row['id']}}">{{$row["nombre"]}}</option>
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
                                <input type="submit" class="btn btn-primary col-md-12" value="Guardar"/>
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
<script>
    $(document).ready(function(){
        $(document).on('change','#cat_id', function(){
            var cat = $(this).val();
            $('#cat_opciones_id').empty();
            $('#cat_opciones_id').append("<option value=''>Selecciona una opcion valida</option>");
            $.ajax({
                url: "{{ route('opByCat') }}",
                dataType: "JSON",
                data:{cat: cat},
                async: true,
                beforeSend: function(){
                    mostrarLoading();
                },
                complete: function(){
                    ocultarLoading();
                },
                success: function(response) {
                    $.each(response,function(index,value){
                        $('#cat_opciones_id').append("<option value='"+value.id+"'>"+value.nombre+"</option>");
                    
                    })
                }
            });
        });
    });
</script>
@endpush