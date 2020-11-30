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
                    <label for=""><b>Referencia</b></label>
                      <div class="form-group valid-required">
                        <input type="text" name="referencia" class="form-control form-control-user" id="referencia"  required>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-3">
                    <label for=""><b>Descripcion</b></label>
                      <div class="form-group valid-required">
                        <input type="text" name="description" class="form-control form-control-user" id="description" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                    <label for=""><b>Registro Invima</b></label>
                      <div class="form-group valid-required">
                        <input type="text" name="register_invima" class="form-control form-control-user" id="register_invima">
                      </div>
                  </div>
                  <div class="col-md-3">
                    <label for=""><b>Gramaje</b></label>
                      <div class="form-group valid-required">
                        <input type="text" name="gramaje" class="form-control form-control-user" id="gramaje" required>
                      </div>
                  </div>

                  <div class="col-md-3">
                    <label for=""><b>Perfil</b></label>
                      <div class="form-group valid-required">
                      <select name="perfil" class="form-control" id="perfil" required>
                          <option value="">Seleccione</option>
                          <option value="Bajo">Bajo</option>
                          <option value="Medio">Medio</option>
                          <option value="Alto">Alto</option>
                        </select>
                      </div>
                  </div>
              </div>
          </div>
        </div>

        <input type="hidden" name="id_user" class="id_user">
        <!-- <input type="hidden" name="token" class="token"> -->
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

