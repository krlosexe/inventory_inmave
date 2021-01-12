<div class="card shadow mb-4 hidden" id="cuadro4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Editar Recepción Tecnica</h6>
    </div>
    <div class="card-body">
        <form class="user" autocomplete="off" method="post" id="form-update" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="put">
            <div class="row">
                <div class="col-md-6">
                    <label for=""><b>Proveedor</b></label>
                    <div class="form-group valid-required">
                        <select name="id_provider" class="form-control select2" id="provider_edit" required></select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for=""><b>NIT</b></label>
                    <div class="form-group valid-required">
                        <input type="text" class="form-control form-control-user" id="nit_provider_edit" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for=""><b>Direccion</b></label>
                    <div class="form-group valid-required">
                        <input type="text" class="form-control form-control-user" id="address_provider_edit" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for=""><b>Telefono</b></label>
                    <div class="form-group valid-required">
                        <input type="text" class="form-control form-control-user" id="phone_provider_edit" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for=""><b>Email</b></label>
                    <div class="form-group valid-required">
                        <input type="text" class="form-control form-control-user" id="email_provider_edit" disabled>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row my-2">
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table_products_edit" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Referencia</th>
                                    <th>Serial</th>
                                    <th>Lote</th>
                                    <th>Registro INVIMA</th>
                                    <th>Vence</th>
                                    <th>Valor</th>
                                    <th>Gramaje</th>
                                    <th>perfil</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <!-- <th colspan="10" style="text-align: right;">Subtotal <input type="hidden" name="subtotal" id="subtotal_edit"> </th>
                            <th style="text-align: right;" id="subtotal_text_edit">$0</th>
                          </tr>
                          <tr>
                            <th style="text-align: left;">Descuento </th>
                            <th style="text-align: right;" id="descuento_text_edit"><input style="text-align: right;" type="text" class="form-control monto_formato_decimales discount_edit" name="discount" id="discount_edit" value="0"></th>
                            
                            <th colspan="8" style="text-align: right;">IVA <input type="hidden" name="vat_total" id="vat_total_edit"></th>
                            <th style="text-align: right;" id="vat_total_text_edit">$0</th>
                          </tr>

                          <tr>
                            <th style="text-align: left;">RTE Fuente</th>
                            <th style="text-align: right;" id="rte_fuente_text_edit"><input style="text-align: right;" type="text" class="form-control monto_formato_decimales discount_edit" name="rte_fuente" id="rte_fuente_edit" value="0"></th> -->

                                    <!-- <th colspan="8" style="text-align: right;">Total factura <input type="hidden" name="total_invoice" id="total_invoice_edit"></th>
                            <th style="text-align: right;" id="total_invoice_text_edit">$0</th> -->
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- <div class="row">
         <div class="col-md-6">
            <label for=""><b>Observaciones</b></label>
              <div class="form-group valid-required">
                <textarea class="form-control" name="observations" id="observations_edit" cols="30" rows="10"></textarea>
              </div>
          </div>
        </div> -->
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
        <button id="send_usuario" class="btn btn-primary btn-user">
            Guardar
        </button>
    </center>
    <br>
    <br>
    </form>

</div>