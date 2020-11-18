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
                <div class="col-md-6">
                  
                    <label for=""><b>Cliente</b></label>
                    <div class="form-group valid-required">
                        <select name="id_client" class="form-control" id="clients" required>
                        </select>
                    </div>
                  
                </div>

                <div class="col-md-4">
                    <div class="form-group valid-required" style="text-align: center;">
                        <label for="reissue"> <b>Reemision ?</b> </label>
                        <input type="checkbox" name="reissue" id="reissue" class="form-control" value="1">
                    </div>
                  
                </div>
              </div>
            
              <div class="row">

                <div class="col-md-4">
                    <label for=""><b>Bodega</b></label>
                    <div class="form-group valid-required">
                        <select name="warehouse" class="form-control" id="warehouse" required>
                          <option value="">Seleccione</option>
                          <option value="Medellin">Medellin</option>
                          <option value="Bogota">Bogota</option>
                        </select>
                    </div>
                </div>


                <div class="col-md-4">
                    <label for=""><b>Implantes</b></label>
                      <div class="form-group valid-required">
                        <!-- <select name="products" class="form-control select2" id="products" required></select> -->
                        <input type="text" maxlength="15" name="number" id="serial" class="form-control form-control-user">
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
                            <th>serial</th>
                            <th>Gramaje</th>
                            <!-- <th>Presentacion</th> -->
                            <th>Precio (COP)</th>
                            <!-- <th>Salida (Cantidad)</th> -->
                            <th>Cantidad</th>
                            <!-- <th>%IVA</th>
                            <th>Total</th> -->
                            <th></th>
			                    </tr>
			                  </thead>
			                  <tbody>
			                    
			                  </tbody>


                        <tfoot>
                          <tr>
                            <th style="text-align: center;" id="descuento_text">
                              <label for="apply_discount">¿ Quieres Aplicar Descuento (10%)?</label>
                             <!-- <input style="text-align: right;" type="text" class="form-control monto_formato_decimales discount" name="discount" id="discount" value="0">--> 
                              <input type="checkbox" id="apply_discount" class="form-control">
                              <br><br>
                              <label for="apply_discount2">¿ Quieres Aplicar Descuento (15%)?</label>
                             <!-- <input style="text-align: right;" type="text" class="form-control monto_formato_decimales discount" name="discount" id="discount" value="0"> -->
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

                      <!-- <div class="row">
                        <div class="col-md-6">
                          <label for=""><b>Observaciones</b></label>
                            <div class="form-group valid-required">
                              <textarea class="form-control" name="observations" id="observations" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                      </div> -->
			            </div>
             </div>
        </div>





        <input type="hidden" name="id_user" class="id_user">
        <input type="hidden" name="token" class="token">
          <br>
          <br>
        </div>
          <center>

            <button type="button"  class="btn btn-danger btn-user" onclick="prev('#cuadro2')">
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

