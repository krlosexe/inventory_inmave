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
                    <label for=""><b>Cod Toskani</b></label>
                      <div class="form-group valid-required">
                        <input type="text" name="code" class="form-control form-control-user" id="code" placeholder="Pj: TKN-00-0060" required>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-3">
                    <label for=""><b>Descripcion</b></label>
                      <div class="form-group valid-required">
                        <input type="text" name="description" class="form-control form-control-user" id="description" placeholder="Pj: Mesolift Cocktail" required>
                      </div>
                  </div>


                  <div class="col-md-3">
                    <label for=""><b>Registro Invima</b></label>
                      <div class="form-group valid-required">
                        <input type="text" name="register_invima" class="form-control form-control-user" id="register_invima">
                      </div>
                  </div>




                  <div class="col-md-3">
                    <label for=""><b>Precio Euro (€)</b></label>
                      <div class="form-group valid-required">
                        <input type="text" name="price_euro" class="form-control form-control-user" id="price_euro" placeholder="Pj: 5" required>
                      </div>
                  </div>

                  <div class="col-md-3">
                    <label for=""><b>Presentacion</b></label>
                      <div class="form-group valid-required">
                        <input type="text" name="presentation" class="form-control form-control-user" id="presentation" placeholder="" required>
                      </div>
                  </div>
              </div>

              <div class="row">

                  <div class="col-md-3">
                      <label for=""><b>Precio distribuidor x Vial</b></label>
                      <div class="form-group valid-required">
                        <input type="text" name="price_distributor_x_vial" class="form-control form-control-user" id="price_distributor_x_vial" placeholder="" required>
                      </div>
                  </div>


                  <div class="col-md-3">
                    <label for=""><b>Precio distribuidor x Caja</b></label>
                      <div class="form-group valid-required">
                        <input type="text" name="price_distributor_x_caja" class="form-control form-control-user" id="price_distributor_x_caja" placeholder="" required>
                      </div>
                  </div>
                  
                  
                  <div class="col-md-3">
                     <label for=""><b>Precio Cliente Final x Vial</b></label>
                      <div class="form-group valid-required">
                        <input type="text" name="price_cliente_x_vial" class="form-control form-control-user" id="price_cliente_x_vial" placeholder="" required>
                      </div>
                  </div>

                  <div class="col-md-3">
                     <label for=""><b>Precio Cliente Final x Caja</b></label>
                      <div class="form-group valid-required">
                        <input type="text" name="price_cliente_x_caja" class="form-control form-control-user" id="price_cliente_x_caja" placeholder="" required>
                      </div>
                  </div>  
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

