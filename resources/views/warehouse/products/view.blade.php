<div class="card shadow mb-4 hidden" id="cuadro3">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Consulta de Productos</h6>
  </div>
  <div class="card-body">
      <form class="user" autocomplete="off" method="post"  enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                      <label for=""><b>Cod Toskani</b></label>
                        <div class="form-group valid-required">
                          <input type="text" name="code" class="form-control form-control-user" id="code_view" placeholder="Pj: TKN-00-0060" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                      <label for=""><b>Descripcion</b></label>
                        <div class="form-group valid-required">
                          <input type="text" name="description" class="form-control form-control-user" id="description_view" placeholder="Pj: Mesolift Cocktail" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                      <label for=""><b>Precio Euro (€)</b></label>
                        <div class="form-group valid-required">
                          <input type="text" name="price_euro" class="form-control form-control-user" id="price_euro_view" placeholder="Pj: 5" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                      <label for=""><b>Presentacion</b></label>
                        <div class="form-group valid-required">
                          <input type="text" name="presentation" class="form-control form-control-user" id="presentation_view" placeholder="" required>
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
            <button type="button"  class="btn btn-danger btn-user" onclick="prev('#cuadro3')">
                Cancelar
            </button>
          </center>
          <br>
          <br>
      </form>
    </div>

