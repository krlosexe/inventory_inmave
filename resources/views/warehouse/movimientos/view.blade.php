<div class="card shadow mb-4 hidden" id="cuadro3">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Consulta de Salida de Productos</h6>
  </div>
  <div class="card-body">
      <form class="user" autocomplete="off" method="post"  enctype="multipart/form-data">
      
        @csrf



        <div class="row">
          <div class="col-md-4">
            
              <label for=""><b>Cliente</b></label>
              <div class="form-group valid-required">
                  <select name="client" class="form-control" id="clients_view" disabled>
                  </select>
              </div>
            
          </div>
        </div>



        <div class="row">
            <div class="col-md-4">
                  <label for=""><b>Bodega</b></label>
                    <div class="form-group valid-required">
                      <select name="warehouse" class="form-control select2" id="warehouse_view" required>
                        <option value="">Seleccione</option>
                        <option value="Medellin">Medellin</option>
                        <option value="Bogota">Bogota</option>
                      </select>
                    </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                <table class="table table-bordered" id="table_products_view" width="100%" cellspacing="0">
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
                              <label for="apply_discount">¿ Quieres Aplicar Descuento (10%)?</label>
                              <input type="checkbox" id="apply_discount_view" class="form-control">
                            </th>

                            <th colspan="6" style="text-align: right;">Subtotal 
                              <input type="hidden" name="subtotal" id="subtotal_view"> 
                              <input type="hidden" name="subtotal_with_discount" id="subtotal_with_discount_view"> 
                            </th>
                            <th style="text-align: right;" id="subtotal_text_view">$0</th>
                          </tr>
                          <tr>
                            <th colspan="7" style="text-align: right;">IVA <input type="hidden" name="vat_total" id="vat_total_view"></th>
                            <th style="text-align: right;" id="vat_total_text_view">$0</th>
                          </tr>


                          <tr>
                            <th colspan="7" style="text-align: right;">Descuento <input type="hidden" name="discount_total" id="discount_total_view"></th>
                            <th style="text-align: right;" id="discount_total_text_view">$0</th>
                          </tr>



                          <tr>
                            <th colspan="7" style="text-align: right;">Total factura <input type="hidden" name="total_invoice" id="total_invoice_view"></th>
                            <th style="text-align: right;" id="total_invoice_text_view">$0</th>
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
            <button type="button"  class="btn btn-danger btn-user" onclick="prev('#cuadro3')">
                Cancelar
            </button>
          </center>
          <br>
          <br>
      </form>
      
    </div>

