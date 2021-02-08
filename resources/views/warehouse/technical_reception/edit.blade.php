<div class="card shadow mb-4 hidden" id="cuadro4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Editar Compra</h6>
  </div>
  <div class="card-body">
      <form class="user" autocomplete="off" method="post" id="form-update" enctype="multipart/form-data">
      
        @csrf

        <input type="hidden" name="_method" value="put">
        
       

        <div class="row">
          <div class="col-md-4">
            <label for=""><b>Bodega</b></label>
              <div class="form-group valid-required">
                <select name="warehouse" class="form-control select2" id="warehouse_edit" required>
                  <option value="">Seleccione</option>
                  <option value="Medellin">Medellin</option>
                  <option value="Bogota">Bogota</option>
                  <option value="Cali">Cali</option>
                </select>
              </div>
          </div>



          <div class="col-md-4">
            <label for=""><b>Número de Factura</b></label>
              <div class="form-group valid-required">
                 <input type="text" class="form-control" name="number_invoice" id="number_invoice_edit" required>
              </div>
          </div>


          <div class="col-md-4">
            <label for=""><b>Fecha</b></label>
              <div class="form-group valid-required">
                 <input type="date" class="form-control" name="date_invoice" id="date_invoice_edit" required>
              </div>
          </div>

        </div>
        <hr>

        <div class="row">
        <div class="col-md-3">
              <label for=""><b>Transporte + Importación</b></label>
                <div class="form-group valid-required">
                <input type="text" class="form-control" name="otros" id="otros" required>
                </div>
          </div>
          <div class="col-md-6">
              <label for=""><b>Productos</b></label>
                <div class="form-group valid-required">
                  <select name="products" class="form-control select2" id="products_edit"></select>
                </div>
          </div>


          <div class="col-md-2">
            <br>
            <button type="button" class="btn btn-primary btn-user" id="add_product_edit">
                Agregar
            </button>
          </div>

<!--
          <div class="col-md-3">
            <br>
            <button type="button" class="btn btn-primary btn-user" data-toggle="modal" data-target=".bd-example-modal-lg" id="new_product_edt">
                Nuevo Producto
            </button>
          </div>-->



        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
			                <table class="table table-bordered" id="table_products_edit" width="100%" cellspacing="0">
			                  <thead>
			                    <tr>
                            <th>Nombre</th>
			                      <th>Presentacion</th>
                            <th>Lote</th>
                            <th>Registro INVIMA</th>
                            <th>Vence</th>
                            <th>Valor</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th></th>
			                    </tr>
			                  </thead>
			                  <tbody>
			                    
			                  </tbody>


                        <tfoot>
                          <tr>
                            <th colspan="7" style="text-align: right;">Subtotal <input type="hidden" name="subtotal" id="subtotal_edit"> </th>
                            <th colspan="2" style="text-align: right;" id="subtotal_text_edit">$0</th>
                          </tr>
                          <tr>
                            <th colspan="7" style="text-align: right;">Impuestos </th>
                            <th colspan="2" style="text-align: right;">
                              <input style="text-align: right;" type="text" class="form-control monto_formato_decimales taxes_edit" name="taxes" id="taxes_edit" value="0">
                            </th>
                          </tr>


                          <tr>
                            <th colspan="7" style="text-align: right;">Transporte </th>
                            <th colspan="2" style="text-align: right;" id="transpor_total_text_edit">
                              <input style="text-align: right;" type="text" class="form-control monto_formato_decimales taxes_edit" name="transport" id="transport_edit" value="0">
                            </th>
                          </tr>
                          

                          <tr>
                            <th colspan="7" style="text-align: right;">Total factura <input type="hidden" name="total_invoice" id="total_invoice_edit"></th>
                            <th colspan="2" style="text-align: right;" id="total_invoice_text_edit">$0</th>
                          </tr>


                        </tfoot>




			                </table>
			            </div>
             </div>
        </div>


        <div class="row">
         <div class="col-md-6">
            <label for=""><b>Observaciones</b></label>
              <div class="form-group valid-required">
                <textarea class="form-control" name="observations" id="observations_edit" cols="30" rows="10"></textarea>
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

            <button type="button"  class="btn btn-danger btn-user" onclick="prev('#cuadro4')">
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

