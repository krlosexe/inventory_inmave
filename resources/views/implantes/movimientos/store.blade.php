<div class="card shadow mb-4 hidden" id="cuadro2">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Registro de Traspaso de Implantes</h6>
    </div>
    <div class="card-body">
        <form class="user" autocomplete="off" method="post" id="store" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">

                    <div class="row">

                        <div class="col-md-4">
                            <label for=""><b>Bodega Origen</b></label>
                            <div class="form-group valid-required">
                                <select name="warehouse" class="form-control" id="warehouse" required>
                                    <option value="">Seleccione</option>
                                    <option value="Medellin">Medellin</option>
                                    <option value="Barranquilla">Barranquilla</option>
                                    <option value="Cali">Cali</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for=""><b>Bodega Origen</b></label>
                            <div class="form-group valid-required">
                                <select name="destiny" class="form-control" id="destiny" required>
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for=""><b>Serial</b></label>
                            <div class="form-group valid-required">
                                <select name="products" class="form-control select2" id="products" required></select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <br>
                            <button type="button" class="btn btn-primary btn-user" id="add_product">
                                Agregar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table_products" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Referencia</th>
                                    <th>Serial</th>
                                    <th>Salida (Cantidad)</th>
                                    <th>Existencia Actual</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <input type="hidden" name="id_user" class="id_user">
            <input type="hidden" name="token" class="token">
            <br>
            <br>
    </div>
    <center>
        <button type="button" class="btn btn-danger btn-user" onclick="prev('#cuadro2')">
            Cancelar
        </button>
        <button class="btn btn-primary btn-user">
            Registrar
        </button>
    </center>
    <br>
    <br>
    </form>
</div>