<div class="card shadow mb-4 hidden" id="cuadro2">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Registro de Recepcion Tecnica</h6>
  </div>
  <div class="card-body">
      <form class="user" autocomplete="off" method="post" id="store" enctype="multipart/form-data">
        @csrf
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
                <input type="text" class="form-control form-control-user" id="nit_provider" disabled>
              </div>
          </div>
          <div class="col-md-3">
            <label for=""><b>Direccion</b></label>
              <div class="form-group valid-required">
                <input type="text" class="form-control form-control-user" id="address_provider" disabled>
              </div>
          </div>
          <div class="col-md-3">
            <label for=""><b>Telefono</b></label>
              <div class="form-group valid-required">
                <input type="text" class="form-control form-control-user" id="phone_provider" disabled>
              </div>
          </div>
          <div class="col-md-3">
            <label for=""><b>Email</b></label>
              <div class="form-group valid-required">
                <input type="text" class="form-control form-control-user" id="email_provider" disabled>
              </div>
          </div>
        </div>
        <hr>
        <div class="row my-2">
            <!-- <div class="col-md-6">
                <label for=""><b>Productos</b></label>
                  <div class="form-group valid-required">
                    <select name="products" class="form-control select2" id="products" required></select>
                  </div>
            </div> -->
            <div class="col-md-2">
              <br>
              <button type="button" class="btn btn-primary btn-user" id="add_product">
                  Agregar
              </button>
            </div>
            <!-- <div class="col-md-3">
              <br>
              <button type="button" class="btn btn-primary btn-user" data-toggle="modal" data-target=".bd-example-modal-lg" id="new_product">
                  Nuevo Producto
              </button>
            </div> -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
			                <table class="table table-bordered" id="table_products" width="100%" cellspacing="0">
			                  <thead>
			                    <tr>
                            <th>Serial</th>
                            <th>Lote</th>
                            <th>Registro INVIMA</th>
                            <th>Vence</th>
                            <th>Valor</th>
                            <th>Gramaje</th>
                            <th>Perfil</th>
                            <th>Total Factura</th>
                            <th></th>
			                    </tr>
			                  </thead>
			                  <tbody>
                        <tfoot>
                          <!-- <tr>
                            <th colspan="8" style="text-align: right;">Total factura <input type="hidden" name="total_invoice" id="total_invoice"></th>
                            <th style="text-align: right;" id="total_invoice_text">$0</th>
                          </tr> -->
                        </tfoot>
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

