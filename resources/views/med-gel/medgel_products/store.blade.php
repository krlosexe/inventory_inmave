<div class="card shadow mb-4 hidden" id="cuadro2">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Registro de Productos</h6>
    </div>
    <div class="card-body">
        <form class="user" autocomplete="off" method="post" id="store" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <label for=""><b>Referencia</b></label>
                            <div class="form-group valid-required">
                                <input type="text" name="referencia" class="form-control form-control-user" id="referencia" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for=""><b>Lote</b></label>
                            <div class="form-group valid-required">
                                <input type="text" name="lote" class="form-control form-control-user" id="lote" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for=""><b>Descripcion</b></label>
                            <div class="form-group valid-required">
                                <input type="text" name="description" class="form-control form-control-user" id="description" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for=""><b>Registro Invima</b></label>
                            <div class="form-group valid-required">
                                <input type="text" name="register_invima" class="form-control form-control-user" id="register_invima">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for=""><b>Fecha Expiración</b></label>
                            <div class="form-group valid-required">
                                <input type="date" name="date_expire" class="form-control form-control-user" id="date_expire">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for=""><b>Cantidad</b></label>
                            <div class="form-group valid-required">
                                <input type="number" name="qty" min="1" value="1" class="form-control form-control-user" id="cantidad">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="id_user" class="id_user">
            <!-- <input type="hidden" name="token" class="token"> -->
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