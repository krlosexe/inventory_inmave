<div class="card shadow mb-4 hidden" id="cuadro4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Editar Salida de Productos</h6>
    </div>
    <div class="card-body">
        <form class="user" autocomplete="off" method="post" id="form-update" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="put">
            <input type="hidden" name="discount_type" id="type_discount_edit">


            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li  class="nav-item">
                  <a id="tab1-0" class="nav-link active" id="home-tab" data-toggle="tab" href="#home-edit" role="tab" aria-controls="home" aria-selected="true">Factura</a>
                </li>
                <li  class="nav-item">
                  <a id="tab2-1" class="nav-link" id="profile-tab" data-toggle="tab" href="#profile-edit" role="tab" aria-controls="profile" aria-selected="false">Pagos</a>
                </li>
              </ul>

              <br><br>


              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active tab_content1-0" id="home-edit" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for=""><b>Cliente</b></label>
                                    <div class="form-group valid-required">
                                        <select name="id_client" class="form-control" id="clients_edit" disabled>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for=""><b>Bodega</b></label>
                                    <div class="form-group valid-required">
                                        <select name="warehouse" class="form-control" id="warehouse_edit" disabled>
                                            <option value="">Seleccione</option>
                                            <option value="Medellin">Medellin</option>
                                            <option value="Bogota">Bogota</option>
                                            <option value="Cali">Cali</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="table_products_edit" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Presentacion</th>
                                            <th>Precio (COP)</th>
                                            <th>Salida (Cantidad)</th>
                                            <th>Existencia Actual</th>
                                            <th>%IVA</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="text-align: center;" id="descuento_text">
                                                <label for="apply_discount_edit">¿ Quieres Aplicar Descuento (10%)?</label>
                                                <input type="checkbox" id="apply_discount_edit" class="form-control">
                                                <br><br>
                                                <label for="apply_discount_edit2">¿ Quieres Aplicar Descuento (15%)?</label>
                                                <!-- <input style="text-align: right;" type="text" class="form-control monto_formato_decimales discount" name="discount" id="discount" value="0">-->
                                                <input type="checkbox" id="apply_discount_edit2" class="form-control">
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
                                            <th style="text-align: right;"><input type="text" disabled class="form-control discount_edit" name="rte_fuente" id="rte_fuente_edit" value="0"></th>
                                            <th colspan="5" style="text-align: right;">RTE Fuente <input type="hidden" name="rte_fuente_total" id="rte_fuente_total_edit"></th>
                                            <th style="text-align: right;" id="rte_fuente_text_edit">$0</th>
                                        </tr>
                                        <tr>
                                            <th colspan="7" style="text-align: right;">Total factura <input type="hidden" name="total_invoice" id="total_invoice_edit"></th>
                                            <th style="text-align: right;" id="total_invoice_text_edit">$0</th>
                                        </tr>

                                        <tr>
                                            <th colspan="7" style="text-align: right;">Saldo </th>
                                            <th style="text-align: right;" id="balance">$0</th>
                                        </tr>


                                    </tfoot>
                                </table>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for=""><b>Observaciones</b></label>
                                        <div class="form-group valid-required">
                                            <textarea class="form-control" name="observations" id="observations_edit" cols="30" rows="10"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="tab-pane fade tab_content2-1" id="profile-edit" role="tabpanel" aria-labelledby="profile-tab">

                    <button type="button" class="btn btn-primary btn-user" id="add_pay">
                        Agregar un Pago
                    </button>
                    <br><br>


                    <div id="content_pay"></div>

                    <br><br>
                    <button type="submit" type="button" class="btn btn-info btn-user">
                       Guardar Pago
                    </button>
                    <br><br>




                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Metodo</th>
                                    <th>Monto</th>
                                    <th>Comprobante</th>
                                </tr>
                            </thead>
                            <tbody id="table-pagos">
                            </tbody>
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
        <button id="print" type="button" class="btn btn-primary btn-user">
            Imprimir
        </button>
        <!--
            <button id="send_usuario" class="btn btn-primary btn-user">
                Guardar
            </button>-->
    </center>
    <br>
    <br>
    </form>
</div>
