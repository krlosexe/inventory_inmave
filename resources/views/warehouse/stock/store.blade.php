<div class="card shadow mb-4 hidden" id="cuadro2">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Registro de Stock Productos</h6>
  </div>
  <div class="card-body">
      <form class="user" autocomplete="off" method="post" id="store" enctype="multipart/form-data">
        @csrf
        <div class="row">

          <div class="col-md-12">

            
              <div class="row">
                <div class="col-md-4">
                    <label for=""><b>Productos</b></label>
                      <div class="form-group valid-required">
                        <select name="products" class="form-control select2" id="products" required></select>
                      </div>
                </div>


                <div class="col-md-2">
                  <br>
                  <button type="button" class="btn btn-primary btn-user" id="add_product">
                      Agregar
                  </button>
                </div>



                <div class="col-md-4">
                      <label for=""><b>Bodega</b></label>
                        <div class="form-group valid-required">
                          <select name="warehouse" class="form-control select2" id="warehouse" required>
                            <option value="">Seleccione</option>
                            <option value="Medellin">Medellin</option>
                            <option value="Bogota">Bogota</option>
                            <!-- <option value="Cali">Cali</option> -->
                          </select>
                        </div>
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
                            <th>Nombre</th>
			                      <th>Presentacion</th>
                            <th>Cantidad</th>
                            <th></th>
			                    </tr>
			                  </thead>
			                  <tbody>
			                    
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

