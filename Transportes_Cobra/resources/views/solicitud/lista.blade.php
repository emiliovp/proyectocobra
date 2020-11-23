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
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Lista de solcitudes de programación.
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('home') }}">Regresar</a>
                            <button class="btn btn-success" id="altaSolicitud">Alta de solicitud</button>
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
                                        <th>Fecha y hora de inicio</th>
                                        <th>fecha y hora de termino</th>
                                        <th>Estatus</th>
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
            columns: [0,1,2,3,4,5],
            format: {
                body: function (data, row, column, node) {
                    // if it is select
                    if (column == 6) {
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
        ajax: '{!! route("listSolicitud") !!}',
        columns: [
            {data: 'folio', name: 'folio'}, 
            {data: 'cliente', name: 'cliente'}, 
            {data: 'fechaHoraProgramada',  name: 'fechaHoraProgramada'},
            {data: 'fecha_inicio',  name: 'fecha_inicio'},
            {data: 'fecha_termino',  name: 'fecha_termino'},
            {data: 'estatus', name: 'estatus'},
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
$('#altaSolicitud').click(function()
{
    var url = '{!!route('nuevasolicitud')!!}';
    $( location).attr("href",url);
});
$(document).on("click", "#bajausr", function(){
    swal({
        title: '¿Esta seguro de eliminar el usuario?',
        text: 'Esta operación no se podra revertir',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar'
    }).then((result) => {  
        if (result.value) {
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });
        var id = $(this).attr("data-idusr");
            var ajax = $.ajax({
                type: 'POST',
                data: {id: id},
                url: '{{ route("bajausr") }}',
                async: false,
                beforeSend: function(){
                    mostrarLoading();
                },
                complete: function(){
                    ocultarLoading();
                }
            });
            ajax.done(function(response){
                if(response == 1) {
                    table.ajax.reload();
                    swal(
                        'Exito',
                        'La operación se ha realizado con éxito',
                        'success'
                    )
                } else if(response == false) {
                    swal(
                        'Error',
                        'La operación no pudo ser realizada',
                        'error'
                    )
                }
            });
        }
    }); 
});
</script>
@endpush