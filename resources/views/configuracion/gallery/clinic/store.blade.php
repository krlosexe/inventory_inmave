<div class="card shadow mb-4 hidden" id="cuadro2">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Registro de Galeria</h6>
  </div>
  <div class="card-body">
      <form class="user" autocomplete="off" method="post" id="store" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-md-3">
              <label for=""><b>Clinica</b></label>
                <div class="form-group valid-required">
                  <select name="id_clinic" class="form-control" id="clinic" required></select>
                </div>
          </div>

        </div>

        <div class="row">
          <div class="col-sm-12 text-center"> 
              <label for=""><b>Adjuntar Foto</b></label>
              <div>
                  <div class="file-loading">
                      <input id="file-input-edit" name="file" type="file" required>
                  </div>
              </div>
              <div class="kv-avatar-hintss">
                  <small>Seleccione una foto</small>
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

