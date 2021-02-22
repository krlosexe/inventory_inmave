<div class="card shadow mb-4 hidden" id="cuadro2">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Registro de Salida de Productos</h6>
    </div>
    <div class="card-body">
        <form class="user" autocomplete="off" method="post" id="store" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <input type="hidden" name="discount_type" id="type_discount">
                        <div class="col-md-4">
                            <label for=""><b>Serial</b></label>
                            <div class="form-group valid-required">
                                <input type="text" maxlength="15" name="number" id="serial" class="form-control form-control-user">
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-6">
                            <label for=""><b>Cliente</b></label>
                            <div class="form-group valid-required">
                                <select name="id_client" class="form-control" id="clients" required>
                                </select>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-3">
                            <label for=""><b>Nombre</b></label>
                            <div class="form-group valid-required">
                                <input type="text" name="name" class="form-control form-control-user" id="name" placeholder="Nombre" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for=""><b>Identificación</b></label>
                            <div class="form-group valid-required">
                                <input type="text" name="nit" class="form-control form-control-user" id="nit" placeholder="Identificación">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for=""><b>Telefono</b></label>
                            <div class="form-group valid-required">
                                <input type="text" name="phone" class="form-control form-control-user" id="phone" placeholder="Pj: 3152077862">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for=""><b>Email</b></label>
                            <div class="form-group valid-required">
                                <input type="email" name="email" class="form-control form-control-user" id="email" placeholder="Pj: cardenascarlos18@gmail.com">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for=""><b>Ciudad</b></label>
                            <div class="form-group valid-required">
                                <input type="text" name="city" class="form-control form-control-user" id="city" placeholder="Pj: Medellin">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for=""><b>Direccion</b></label>
                            <div class="form-group valid-required">
                                <textarea name="address" class="form-control" id="address" placeholder="Pj: cra 107 - 30 #108 99" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for=""><b>Bodega</b></label>
                            <div class="form-group valid-required">
                                <input type="text" maxlength="15" name="warehouse" id="warehouse" class="form-control form-control-user" readonly>
                            </div>
                        </div>
                        <div class="col-2">
                            <label for=""><b>Items</b></label>
                            <div class="form-group valid-required">
                                <input type="text" maxlength="15" name="items" id="items" class="form-control form-control-user" readonly>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table_products_out" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Referencia</th>
                                    <th>Serial</th>
                                    <th>Existencia Actual</th>
                                    <th>Precio</th>
                                    <th>Salida (Cantidad)</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="text-align: center;" id="descuento_text">
                                        <label for="apply_discount3">¿ Quieres Aplicar Descuento (5%)?</label>
                                        <input type="checkbox" id="apply_discount3" class="form-control">
                                        <br><br>
                                        <label for="apply_discount">¿ Quieres Aplicar Descuento (10%)?</label>
                                        <input type="checkbox" id="apply_discount" class="form-control">
                                        <br><br>
                                        <label for="apply_discount2">¿ Quieres Aplicar Descuento (15%)?</label>
                                        <!-- <input style="text-align: right;" type="text" class="form-control monto_formato_decimales discount" name="discount" id="discount" value="0">-->
                                        <input type="checkbox" id="apply_discount2" class="form-control">
                                    </th>
                                    <th colspan="6" style="text-align: right;">Subtotal
                                        <input type="hidden" name="subtotal" id="subtotal">
                                        <input type="hidden" name="subtotal_with_discount" id="subtotal_with_discount">
                                    </th>
                                    <th style="text-align: right;" id="subtotal_text">$0</th>
                                </tr>
                                <tr>
                                    <th colspan="7" style="text-align: right;">IVA <input type="hidden" name="vat_total" id="vat_total"></th>
                                    <th style="text-align: right;" id="vat_total_text">$0</th>
                                </tr>
                                <tr>
                                    <th colspan="7" style="text-align: right;">Descuento <input type="hidden" name="discount_total" id="discount_total"></th>
                                    <th style="text-align: right;" id="discount_total_text">$0</th>
                                </tr>
                                <tr>
                                    <th style="text-align: left;">RTE Fuente</th>
                                    <th style="text-align: right;"><input type="text" class="form-control discount" name="rte_fuente" id="rte_fuente" value="0"></th>
                                    <th colspan="5" style="text-align: right;">RTE Fuente <input type="hidden" name="rte_fuente_total" id="rte_fuente_total"></th>
                                    <th style="text-align: right;" id="rte_fuente_text">$0</th>
                                </tr>
                                <tr>
                                    <th colspan="7" style="text-align: right;">Total factura <input type="hidden" name="total_invoice" id="total_invoice"></th>
                                    <th style="text-align: right;" id="total_invoice_text">$0</th>
                                </tr>
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
        <button class="btn btn-primary btn-user">
            Registrar
        </button>
    </center>
    <br>
    <br>
    </form>
</div>