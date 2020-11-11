@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card-deck">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Datos del usuario</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Almacenadora logistica internacional cobra</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label><strong>Nombre:</strong> {{$datos[0]['nombre_usuario']}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label><strong>Usuario:</strong> {{$datos[0]['username']}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label><strong>Perfil:</strong> {{$datos[0]['nombre_perfil']}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label><strong>Area:</strong> {{$datos[0]['nombre_area']}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-danger" style="color:#FFFFFF;"href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">{{ __('Logout') }}</button>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Menú</div>
                            <ul class="list-group ">
                                @foreach($datos AS $val)
                                    <a href="{{ url($val['ruta']) }}" class="list-group-item list-group-item-action">{{$val['nombre_modulo']}}</a>
                                @endforeach
                                <!--<a href="#" class="list-group-item list-group-item-action">Morbi leo risus</a>
                                <a href="#" class="list-group-item list-group-item-action">Porta ac consectetur ac</a>
                                <a href="#" class="list-group-item list-group-item-action">Vestibulum at eros</a>-->

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
