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
                    Vista de contrato.
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="cliente">cliente cliente</label>
                            <input type="hidden" name="idcliente" id="idcliente"/>
                            <input class="form-control" data-type="cliente" id="cliente" name="cliente" value="{{ $info['nombreCliente'] }}"/>
                        </div>
                        <div class="col-md-4">
                            <label for="bodega">Bodega</label>
                            <input class="form-control" name="bodega" id="bodega" value="{{ $info['tipo_bodega'] }}"/>
                        </div>
                        <div class="col-md-4">
                            <label for="precio">Precio</label>
                            <input class="form-control" id="precio" name="precio" value="{{ $info['preciocontrato'] }}"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="finicio">Fecha de inicio</label>
                            <input class="form-control" name="finicio" id="finicio" value="{{ $info['fechaInicio'] }}"></input>
                        </div>
                        <div class="col-md-4">
                            <label for="ftermino">Fecha de termino</label>
                            <input class="form-control" name="ftermino" id="ftermino" value="{{ $info['fechaTermino'] }}"></input>
                        </div>
                        <div class="col-md-4">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" value="{{ $info['descripcionContrato'] }}"></textarea>
                        </div>   
                    </div>
                    <div class="form-group row">
                        
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('contratos') }}" id="regresar" class="btn btn-warning">Regresar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection