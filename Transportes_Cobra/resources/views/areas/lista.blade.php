@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Lista de áreas.
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ url('/home/1') }}">Regresar</a>
                            <button class="btn btn-success mov-area" id="altaPerf">Alta de área</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-bordered" id="area-table">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Área</th>
                                        <th>Editar</th>
                                        <th>Eliminar</th>
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
    var table = $('#area-table').DataTable({
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
        ajax: '{!! route("listaareas") !!}',
        columns: [
            {data: 'id', name: 'id'}, 
            {data: 'nombre',  name: 'nombre'},
            {
                render: function (data,type,row){
                    var html = '';
                    html = '<div class="row">'+
                            '<div class="col-md-12">'+
                                '<button class="btn btn-primary btn-block mov-area" id="editarea" name="editarea" data-movimiento="editar" data-desarea="'+row.descripcion+'" data-nomarea="'+row.nombre+'" data-idarea="'+row.id+'">Editar</button>'+
                            '</div>'+
                           '</div>';

                    return html;
                }
            },
            {
                render: function (data,type,row){
                    var html = '';
                    html = '<div class="row">'+
                            '<div class="col-md-12">'+
                                '<button class="btn btn-danger btn-block" id="baja" name="baja" data-idarea="'+row.id+'">Baja</button>'+
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
    $(document).on("click", ".mov-area", function(){
        var mov = $(this).attr("data-movimiento");
        if (mov=="editar") {
            var id = $(this).attr("data-idarea");
            var nom = $(this).attr("data-nomarea");
            var desc = $(this).attr("data-desarea");
            if (desc == 'null') {
                desc = '';
            }
            var titulo="Editar área";
            var cuerpo = '<div class="container" style="margin-top: 10px;">'+
                    '<form method="post" action="">'+
                    '<div class="form-group row">'+
                        '<div class="col-md-12">'+
                            '<label for="nombreArea" class="col-lg-12 col-form-label text-left txt-bold">Nombre del área</label>'+
                            '<input type="text" class="form-control" name="nombreArea" value="'+nom+'" id="nombreArea"/>'+
                            '<span id="errmsj_area" class="error-msj" role="alert">'+
                                '<strong>Favor de ingresar un nombre de área</strong>'+
                            '</span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="form-group row">'+
                        '<div class="col-md-12">'+
                            '<label for="descipcionArea" class="col-lg-12 col-form-label text-left txt-bold">Descripción del área</label>'+
                            '<input type="text" class="form-control" value="'+desc+'" name="descipcionArea" id="descipcionArea"/>'+
                        '</div>'+
                    '</div>'+
                    '<div class="form-group row">'+
                        '<div class="col-md-6">'+
                            '<a href="#" onclick="swal.closeModal(); return false;" class="btn btn-danger btn-block">Cancelar</a>&nbsp;&nbsp;'+
                            '</div>'+
                        '<div class="col-md-6">'+
                            '<input class="btn btn-primary btn-block" data-movimiento="editar" data-idarea="'+id+'" id="guardar" type="button" value="Guardar">'+
                        '</div>'+
                    '</div>'+
                    '</form>'+
                '</div>';
        }else{
            var titulo="Alta de área";
            var cuerpo = '<div class="container" style="margin-top: 10px;">'+
                    '<form method="post" action="">'+
                    '<div class="form-group row">'+
                        '<div class="col-md-12">'+
                            '<label for="nombreArea" class="col-lg-12 col-form-label text-left txt-bold">Nombre del área</label>'+
                            '<input type="text" class="form-control" name="nombreArea" id="nombreArea"/>'+
                            '<span id="errmsj_area" class="error-msj" role="alert">'+
                                '<strong>Favor de ingresar un nombre de área</strong>'+
                            '</span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="form-group row">'+
                        '<div class="col-md-12">'+
                            '<label for="descipcionArea" class="col-lg-12 col-form-label text-left txt-bold">Descripción del área</label>'+
                            '<input type="text" class="form-control" name="descipcionArea" id="descipcionArea"/>'+
                        '</div>'+
                    '</div>'+
                    '<div class="form-group row">'+
                        '<div class="col-md-6">'+
                            '<a href="#" onclick="swal.closeModal(); return false;" class="btn btn-danger btn-block">Cancelar</a>&nbsp;&nbsp;'+
                            '</div>'+
                        '<div class="col-md-6">'+
                            '<input class="btn btn-primary btn-block" data-movimiento="alta" id="guardar" type="button" value="Guardar">'+
                        '</div>'+
                    '</div>'+
                    '</form>'+
                '</div>';
        }
        Swal({
            title: titulo,
            // type: 'info',
            html: cuerpo,
            showCloseButton: true,
            showCancelButton: false,
            showConfirmButton: false,
            focusConfirm: false,
            allowOutsideClick: false,
        });
    });
$(document).on("click", "#guardar", function(){

    var nombrearea = $("#nombreArea").val();
    var descripcionarea = $("#descipcionArea").val();

    var mov = $(this).attr("data-movimiento");
    if (nombrearea == '') {
        mostrarError("errmsj_area");
    }else{
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if (mov == "editar") {
            var tittle = 'Edición';
            var id = $(this).attr("data-idarea");
            var ajax = $.ajax({
                type: 'POST',
                data: {nombre: nombrearea, id:id, descripcion: descripcionarea},
                url: '{{ route("editararea") }}',
                async: false,
                beforeSend: function(){
                    mostrarLoading();
                },
                complete: function(){
                    ocultarLoading();
                }
            });
        }else{
            var tittle = 'Alta';
            var ajax = $.ajax({
                type: 'POST',
                data: {nombre: nombrearea, descipcion: descripcionarea},
                url: '{{ route("storedareas") }}',
                async: false,
                beforeSend: function(){

                },
                complete: function(){

                }
            });
        }
        ajax.done(function(response){

            if(response == true) {
                table.ajax.reload();
                swal(
                    tittle,
                    'La operación se ha realizado con éxito',
                    'success'
                )
            }else if(response == 2){
                swal(
                    'Error',
                    'Esta área ya fue dada de alta, favor de ingresar otra',
                    'error'
                )
            } 
            else if(response == false) {
                swal(
                    'Error',
                    'La operación no pudo ser realizada',
                    'error'
                )
            }
        }); 
    }   
});
$(document).on("click", "#baja", function(){
    swal({
        title: '¿Esta seguro de eliminar el área?',
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
        var id = $(this).attr("data-idarea");
            var ajax = $.ajax({
                type: 'POST',
                data: {id: id},
                url: '{{ route("bajaararea") }}',
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
                        'Baja de área',
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