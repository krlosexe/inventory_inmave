<div class="card shadow mb-4 hidden" id="cuadro2">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Registro de Recepcion Tecnica</h6>
    </div>
    <div class="card-body">
        <form class="user" autocomplete="off" method="post" id="store" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <label for=""><b>Referencia</b></label>
                    <div class="form-group valid-required">
                        <input type="text" name="referencia" class="form-control form-control-user" id="referencia" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for=""><b>Fecha de Ingreso</b></label>
                    <div class="form-group valid-required">
                        <input type="date" name="fecha_ingreso" class="form-control form-control-user" id="fecha_ingreso" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for=""><b>Bodega Origen</b></label>
                    <div class="form-group valid-required">
                        <input type="text" name="bodega_origen" class="form-control form-control-user" id="bodega_origen" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for=""><b>Nro. Traslado</b></label>
                    <div class="form-group valid-required">
                        <input type="text" name="nro_traslado" class="form-control form-control-user" id="nro_traslado" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <label for=""><b>Bodega</b></label>
                    <div class="form-group valid-required">
                        <select name="warehouse" class="form-control" id="warehouse" required>
                            <option value="">Seleccione</option>
                            <option value="Bogota">Bogota</option>
                            <option value="Cali">Cali</option>
                            <option value="Medellin">Medellin</option>
                            <option value="Barranquilla">Barranquilla</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for=""><b>Proveedor</b></label>
                    <div class="form-group valid-required">
                        <select name="id_provider" class="form-control select2" id="provider" required></select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for=""><b>NIT</b></label>
                    <div class="form-group valid-required">
                        <input type="text" class="form-control form-control-user" id="nit_provider" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for=""><b>Direccion</b></label>
                    <div class="form-group valid-required">
                        <input type="text" class="form-control form-control-user" id="address_provider" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for=""><b>Telefono</b></label>
                    <div class="form-group valid-required">
                        <input type="text" class="form-control form-control-user" id="phone_provider" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for=""><b>Email</b></label>
                    <div class="form-group valid-required">
                        <input type="text" class="form-control form-control-user" id="email_provider" readonly>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row my-2">
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table_products_imp" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Referencia</th>
                                    <th>Serial</th>
                                    <th>Lote</th>
                                    <th>Registro INVIMA</th>
                                    <th>F. Vencimiento</th>
                                    <th>Valor</th>
                                    <th>Descripción</th>
                                    <th>Gramaje</th>
                                    <th>Perfil</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            </tfoot>
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
        <button type="submit" class="btn btn-primary btn-user">
            Registrar
        </button>
    </center>
    <br>
    <br>
    </form>
</div>