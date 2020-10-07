<div class="card shadow mb-4 hidden" id="cuadro3">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Consulta de Cirugias</h6>
  </div>
  <div class="card-body">
      <form class="user" autocomplete="off" method="post" id="form-view" enctype="multipart/form-data">
      
        @csrf

        <div class="row">

           <div class="col-md-6">
              
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                        <label for=""><b>Responsable</b></label>
                        <select name="responsable" id="responsable-view" class="form-control select2" required>
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                </div>
              </div>



              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                        <label for=""><b>Paciente</b></label>
                        <input type="text" name="issue" id="name_client-view" class="form-control" required >
                    </div>
                </div>
              </div>





              <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                          <label for=""><b>Asunto</b></label>
                          <input type="text" name="issue" id="issue-view" class="form-control" required >
                      </div>
                  </div>
              </div>


              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                        <label for=""><b>Fecha</b></label>
                        <input type="date" name="fecha" id="fecha-view" class="form-control" required min="<?= date("Y-m-d")?>">
                    </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                        <label for=""><b>Hora</b></label>
                        <input type="time" name="time" id="time-view" class="form-control" required>
                    </div>
                </div>
              </div>


              <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for=""><b>Estatus</b></label>
                        <select name="status_task" id="status_task-view" class="form-control" required>
                            <option value="Abierta">Abierta</option>
                        </select>
                    </div>
                </div>
              </div>
              
           </div>


           <div class="col-md-6">
              <div class="row" id="comments">
                  <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2">
                          Foto
                        </div>
                        <div class="col-md-10">
                          Text
                        </div>
                    </div>
                  </div>
                </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                        <label for=""><b>Seguidores</b></label>
                        <select name="followers[]" id="followers-view" class="form-control select2" multiple required>
                            <option value="">Seleccione</option>
                        </select>
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

