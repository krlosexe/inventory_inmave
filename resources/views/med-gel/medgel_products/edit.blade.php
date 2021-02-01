<div class="card shadow mb-4 hidden" id="cuadro4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Editar Producto</h6>
  </div>
  <div class="card-body">
      <form class="user" autocomplete="off" method="post" id="form-update" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" value="put">
        <div class="row">
          <div class="col-md-12">
              <div class="row">
                <div class="col-md-3">
                  <label for=""><b>Referencia</b></label>
                    <div class="form-group valid-required">
                      <input type="text" name="referencia" class="form-control form-control-user" id="referencia_edit" required>
                    </div>
                </div>
              </div>
                <div class="row">
                    <div class="col-md-3">
                      <label for=""><b>Descripcion</b></label>
                        <div class="form-group valid-required">
                          <input type="text" name="description" class="form-control form-control-user" id="description_edit" placeholder="Pj: Mesolift Cocktail" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                      <label for=""><b>Registro Invima</b></label>
                        <div class="form-group valid-required">
                          <input type="text" name="register_invima" class="form-control form-control-user" id="register_invima_edit">
                        </div>
                    </div>
                    <div class="col-md-3">
                            <label for=""><b>Fecha Expiración</b></label>
                            <div class="form-group valid-required">
                                <input type="date" name="date_expire" class="form-control form-control-user" id="date_expire_edit">
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <!-- <input type="hidden" name="inicial" id="inicial"> -->
        <input type="hidden" name="id_user" class="id_user">
        <!-- <input type="hidden" name="token" class="token"> -->
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

