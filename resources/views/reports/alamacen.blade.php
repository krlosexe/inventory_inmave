@extends('layouts.app')


@section('CustomCss')

<style>
    .kv-avatar .krajee-default.file-preview-frame,
    .kv-avatar .krajee-default.file-preview-frame:hover {
        margin: 0;
        padding: 0;
        border: none;
        box-shadow: none;
        text-align: center;
    }

    .kv-avatar {
        display: inline-block;
    }

    .kv-avatar .file-input {
        display: table-cell;
        width: 213px;
    }

    .kv-reqd {
        color: red;
        font-family: monospace;
        font-weight: normal;
    }



    #slide {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 3000;
        display: none;

        /* background-color: rgba(0,0,0,.4); */
        transform: translateZ(0);
        -webkit-transform: translateZ(0);
        -moz-transform: translateZ(0);
        -ms-transform: translateZ(0);
        -o-transform: translateZ(0);
        overflow: hidden;
        -webkit-transition: 3s;
        -moz-transition: 3s;
        -ms-transition: 3s;
        -o-transition: 3s;
        transition: 3s;
    }

    #slide.show {
        display: block;
        pointer-events: auto;
        z-index: 3000;
        left: 0px;
        top: 0px;
        right: 0px;
        height: 912px;



        overflow-x: auto;
        overflow-y: auto;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1050;
        /* display: none; */
        width: 100%;
        height: 100%;
        overflow: hidden;
        outline: 0;
        overflow: scroll;




        background-color: rgba(0, 0, 0, 0.4);
        -webkit-transition: 3s;
        -moz-transition: 3s;
        -ms-transition: 3s;
        -o-transition: 3s;
        transition: 3s;

    }


    .side-panel-container {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 3001;
        display: block;
        width: calc(100% - 300px);
        background: #fff;

        transform: translateX(100%);
        -webkit-transition: 3s;
        -moz-transition: 3s;
        -ms-transition: 3s;
        -o-transition: 3s;
        transition: 3s;

    }

    .slide-show {
        z-index: 3001;
        width: 90%;
        -webkit-transform: translateX(0%);
        -moz-transform: translateX(0%);
        -ms-transform: translateX(0%);
        -o-transform: translateX(0%);
        transform: translateX(0%);

    }


    .side-panel-label {
        display: flex;
        position: absolute;
        left: 0;
        top: 21px;
        min-width: 30px;
        height: 38px;
        padding-right: 5px;
        background: rgba(47, 198, 246, .95);
        border-top-left-radius: 19px;
        border-bottom-left-radius: 19px;
        white-space: nowrap;
        overflow: hidden;
        transition: top .3s;
        box-shadow: inset -6px 0 8px -10px rgba(0, 0, 0, 0.95);
        z-index: 1;
        transform: translateX(-100%);
        cursor: pointer;
    }

    .side-panel-close-btn-inner:before {
        -webkit-transform: translateX(-50%) translateY(-50%) rotate(-45deg);
        -moz-transform: translateX(-50%) translateY(-50%) rotate(-45deg);
        -ms-transform: translateX(-50%) translateY(-50%) rotate(-45deg);
        -o-transform: translateX(-50%) translateY(-50%) rotate(-45deg);
        transform: translateX(-50%) translateY(-50%) rotate(-45deg);
    }


    .side-panel-close-btn-inner:after,
    .side-panel-close-btn-inner:before {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 14px;
        height: 2px;
        background-color: #fff;
        content: "";
    }


    .side-panel-close-btn-inner:after {
        -webkit-transform: translateX(-50%) translateY(-50%) rotate(45deg);
        -moz-transform: translateX(-50%) translateY(-50%) rotate(45deg);
        -ms-transform: translateX(-50%) translateY(-50%) rotate(45deg);
        -o-transform: translateX(-50%) translateY(-50%) rotate(45deg);
        transform: translateX(-50%) translateY(-50%) rotate(45deg);
    }


    .side-panel-close-btn-inner:after,
    .side-panel-close-btn-inner:before {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 14px;
        height: 2px;
        background-color: #fff;
        content: "";
    }
</style>


<link href="<?= url('/') ?>/vendor/summernote-master/dist/summernote.min.css" rel="stylesheet">
<script src="<?= url('/') ?>/vendor/summernote-master/dist/summernote.min.js"></script>



@endsection


@section('content')
<!-- Page Wrapper -->
<div id="wrapper">

    @include('layouts.sidebar')

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            @include('layouts.topBar')


            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <h1 class="h3 mb-2 text-gray-800">Almacen</h1>

                <div id="alertas"></div>
                <input type="hidden" class="id_user">
                <input type="hidden" class="token">

                <!-- DataTales Example -->
                <div class="card shadow mb-4" id="cuadro1">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Reporte por Almacen</h6>
                        <br>
                        <div class="col-md-4">
                            <label for=""><b>Bodega</b></label>
                            <div class="form-group valid-required">
                                <select onchange="ProductsGetExistence(this)" name="warehouse" class="form-control" id="warehouse" required>
                                    <option value="">Seleccione</option>
                                    <option value="Medellin">Medellin</option>
                                    <option value="Bogota">Bogota</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="table" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Almacen</th>
                                        <th>Descripción</th>
                                        <th>Codigo</th>
                                        <th>Total Compras(Euros)</th>
                                        <th>Precio Compra</th>
                                        <th>Ventas</th>
                                        <th>Venta Total</th>
                                        <th>Cantidad Remisión</th>
                                        <th>Total Remisión</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @include('tasks.store')
                @include('tasks.view')
                @include('tasks.edit')
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->
        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2019</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<input type="hidden" id="ruta" value="<?= url('/') ?>">
@endsection

@section('CustomJs')

<script>
    $(document).ready(function() {

        $("#collapse_Tareas").addClass("show");
        $("#nav_tasks, #modulo_Tareas").addClass("active");

        verifyPersmisos(id_user, tokens, "tasks");
    });

    function ProductsGetExistence(element) {
        console.log($(element).val());
        var url = document.getElementById('ruta').value;
        var table = $("#table").DataTable({
            "destroy": true,
            "stateSave": true,
            "serverSide": false,
            "ajax": {
                "method": "GET",
                "url": '' + url + '/api/almacen/existence/'+$(element).val(),

                "dataSrc": ""
            },
            "columns": [
                {
                    "data": "warehouse",

                },
                {
                    "data": "description",

                },
                {
                    "data": "code",
                },
                {
                    "data": "qty_total",
                },
                {
                    "data": "price",
                },
                {
                    "data":"qty_salida",
                },
                {
                    "data": "qty_total_vendido",
                },
                {
                    "data": "qty_remision",
                },
                {
                    "data": "remision_total",
                },
            ],
            "language": idioma_espanol,
            "dom": 'Bfrtip',
            "responsive": true,
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        table
            .search("").draw()

    }
</script>

@endsection