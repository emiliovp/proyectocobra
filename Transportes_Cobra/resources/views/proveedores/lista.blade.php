@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div id="success">
        <div class="col-sm-12">
            @if(Session::has('success'))
            <div class="alert alert-success" role="alert"> 
                <strong> Realizado: </strong>  {{ Session::get('success') }}
            </div>
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Lista de proveedores.
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ url('/home') }}">Regresar</a>
                            <button class="btn btn-success" id="altaProv">Alta de proveedor</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-bordered" id="perfil-table">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Nombre</th>
                                        <!--<th>Área</th>-->
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
            columns: [0,1],
            format: {
                body: function (data, row, column, node) {
                    // if it is select
                    if (column == 2) {
                        return $(data).find("option:selected").text()
                    } else return data
                }
            },
        }
    };
    var table = $('#perfil-table').DataTable({
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
        ajax: '{!! route("listproveedor") !!}',
        columns: [
            {data: 'id', name: 'id'}, 
            {data: 'nombre',  name: 'nombre'},
            //{data: 'area',  name: 'area'},
            {
                render: function (data,type,row){
                    var html = '';
                    html = '<div class="row">'+
                            '<div class="col-md-6">'+
                                '<a href="{{url("/proveedores/editar")}}/'+row.id+'" class="btn btn-primary btn-block" id="editarlink" name="editarprov" data-id="'+row.id+'">Editar</a>'+
                                '</div>'+
                            '<div class="col-md-6">'+
                                '<button class="btn btn-danger btn-block btn-baja" id="bajaprov" name="bajaprov" data-idproveedor="'+row.id+'">Baja</button>'+
                            '</div>'+
                           '</div>';

                    return html;
                }
            },
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
$('#altaProv').click(function()
{
    var url = '{!!route('nuevoproveedor')!!}';
    $( location).attr("href",url);
});
$(document).on("click", "#bajaprov", function(){
    swal({
        title: '¿Esta seguro de eliminar el proveedor?',
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
        var id = $(this).attr("data-idproveedor");
            var ajax = $.ajax({
                type: 'POST',
                data: {id: id},
                url: '{{ route("bajaprov") }}',
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