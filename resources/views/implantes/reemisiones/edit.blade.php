<div class="card shadow mb-4 hidden" id="cuadro4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Editar Remisión de Productos</h6>
    </div>
    <div class="card-body">
        <form class="user" autocomplete="off" method="post" id="form-update" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="put">
            <input type="hidden" name="discount_type" id="type_discount_edit">
            <input type="hidden" name="reissue" id="reissue_edit">
            <div class="row">
                <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <label for=""><b>Serial</b></label>
                        <div class="form-group valid-required">
                            <input type="text" maxlength="15" id="serial_edit" class="form-control form-control-user">
                        </div>
                    </div>
                </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for=""><b>Cliente</b></label>
                            <div class="form-group valid-required">
                                <select name="id_client" class="form-control" id="clients_edit" required>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <br>
                            <button type="button" class="btn btn-primary btn-user" id="add_remision_invoice_implant">
                                Facturar
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for=""><b>Bodega</b></label>
                            <div class="form-group valid-required">
                            <input type="text" maxlength="15" name="warehouse" id="warehouse_edit" class="form-control form-control-user" readonly>
                            </div>
                        </div>
                        <div class="col-2">
                            <label for=""><b>Items</b></label>
                            <div class="form-group valid-required">
                            <input type="text" maxlength="15" name="items" id="items_id" class="form-control form-control-user" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for=""><b>Nombre Paciente</b></label>
                            <div class="form-group valid-required">
                                <input type="text" name="name" class="form-control form-control-user" id="name_edit" placeholder="Nombre" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for=""><b>Identificación</b></label>
                            <div class="form-group valid-required">
                                <input type="text" name="nit" class="form-control form-control-user" id="nit_edit" placeholder="Identificación">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table_products_edit_rem" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Referencia</th>
                                    <th>Serial</th>
                                    <th>Salida (Cantidad)</th>
                                    <th>Existencia Actual</th>
                                    <th>Estatus</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="text-align: center;" id="descuento_text">
                                        <label for="apply_discount3">¿ Quieres Aplicar Descuento (5%)?</label>
                                        <input type="checkbox" id="apply_discount3_edit" class="form-control">
                                        <br><br>

                                        <label for="apply_discount_edit">¿ Quieres Aplicar Descuento (10%)?</label>
                                        <input type="checkbox" id="apply_discount_edit" class="form-control">
                                        <br><br>
                                        <label for="apply_discount2">¿ Quieres Aplicar Descuento (15%)?</label>
                                        <input type="checkbox" id="apply_discount2_edit" class="form-control">
                                    </th>
                                    <th colspan="6" style="text-align: right;">Subtotal
                                        <input type="hidden" name="subtotal" id="subtotal_edit">
                                        <input type="hidden" name="subtotal_with_discount" id="subtotal_with_discount_edit">
                                    </th>
                                    <th style="text-align: right;" id="subtotal_text_edit">$0</th>
                                </tr>
                                <tr>
                                    <th colspan="7" style="text-align: right;">IVA <input type="hidden" name="vat_total" id="vat_total_edit"></th>
                                    <th style="text-align: right;" id="vat_total_text_edit">$0</th>
                                </tr>
                                <tr>
                                    <th colspan="7" style="text-align: right;">Descuento <input type="hidden" name="discount_total" id="discount_total_edit"></th>
                                    <th style="text-align: right;" id="discount_total_text_edit">$0</th>
                                </tr>
                                <tr>
                                    <th style="text-align: left;">RTE Fuente</th>
                                    <th style="text-align: right;"><input type="text" class="form-control discount_edit" name="rte_fuente" id="rte_fuente_edit" value="0"></th>
                                    <th colspan="5" style="text-align: right;">RTE Fuente <input type="hidden" name="rte_fuente_total" id="rte_fuente_total_edit"></th>
                                    <th style="text-align: right;" id="rte_fuente_text_edit">$0</th>
                                </tr>
                                <tr>
                                    <th colspan="7" style="text-align: right;">Total factura <input type="hidden" name="total_invoice" id="total_invoice_edit"></th>
                                    <th style="text-align: right;" id="total_invoice_text_edit">$0</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <input type="hidden" name="inicial" id="inicial">
            <input type="hidden" name="id_user" class="id_user">
            <input type="hidden" name="token" class="token">
            <input type="hidden" name="id_user_edit" id="id_edit">
            <br>
            <br>
    </div>
    <center>
        <button type="button" class="btn btn-danger btn-user" onclick="prev('#cuadro4')">
            Cancelar
        </button>
        <button type="submit" type="button" class="btn btn-info btn-user">
            Guardar
        </button>
        <button id="print" type="button" class="btn btn-primary btn-user">
            Imprimir
        </button>
    </center>
    <br>
    <br>
    </form>
</div>