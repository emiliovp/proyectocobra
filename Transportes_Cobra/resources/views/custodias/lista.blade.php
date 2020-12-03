@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="col-sm-12">
        @if(Session::has('success'))
        <div class="alert alert-success" role="alert"> 
            <strong> Realizado: </strong>  {{ Session::get('success') }}
        </div>
        @endif
    </div>
    <div class="col-sm-12">
        @if(Session::has('excepcionerror'))
        <div class="alert alert-danger" role="alert"> 
            <strong> Error: </strong>  {{ Session::get('excepcionerror') }}
        </div>
        @endif
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Lista de custodias.
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('home') }}">Regresar</a>
                            <!-- <button class="btn btn-success" id="altaSolicitud">Alta de solicitud</button>-->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-bordered" id="solicitud-table">
                                <thead>
                                    <tr>
                                        <th>Folio</th>
                                        <th>Cliente</th>
                                        <th>Fecha de programación</th>
                                        <th>Fecha y hora de alta</th>
                                        <th>fecha y hora de termino</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
var buttonCommon = {
        exportOptions: {
            columns: [0,1,2],
            format: {
                body: function (data, row, column, node) {
                    if (column == 3) {
                        return $(data).find("option:selected").text()
                    } else return data
                }
            },
        }
    };
    var table = $('#solicitud-table').DataTable({
        language: {
            url: "{{ asset('json/Spanish.json') }}",
            buttons: {
                copyTitle: 'Tabla copiada',
                copySuccess: {
                    _: '%d líneas copiadas',
                    1: '1 línea copiada'
                }
            }
        },
        processing: true,
        serverSide: true,
        ajax: '{!! route("listCustodia") !!}',
        columns: [
            {data: 'folio', name: 'folio'}, 
            {data: 'cliente', name: 'cliente'}, 
            {data: 'fechaHoraProgramada',  name: 'fechaHoraProgramada'},
            {data: 'fecha_inicio',  name: 'fecha_inicio'},
            {data: 'fecha_termino',  name: 'fecha_termino'},
            {data: 'estatus', name: 'estatus'},
            {
                render: function (data,type,row){
                    var html = '';
                    if (row.estado == 1) {
                        @inject('crypt', 'Illuminate\Support\Facades\Crypt')
                        html = '<div class="row">'+
                            '<div class="col-md-12">'+
                            '<a href="{{url("/custodia/atender")}}/'+row.folio+'" class="btn btn-primary btn-block" id="atendersolicitud" name="atendersolicitud" data-id="'+row.folio+'">Atender</a>'+
                            '</div>'+
                           '</div>';   
                    }else{
                        html = '<div class="row"><div class="col-lg-12 text-center"><label>Atendido</label></div></div>';
                    }                    
                    return html;
                }
            }
        ],
        dom: 'Blfrtip',
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todo"]],
        buttons: [
            /*$.extend(true, {}, buttonCommon, {
                extend: "copyHtml5"
            }),*/ 
            $.extend(true, {}, buttonCommon, {
                extend: "csvHtml5"
            }), 
            /*$.extend(true, {}, buttonCommon, {
                extend: "excelHtml5"
            }),*/ 
            $.extend(true, {}, buttonCommon, {
                extend: "pdfHtml5"
            })
        ]
    });
</script>
@endpush