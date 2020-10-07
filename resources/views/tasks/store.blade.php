<div class="card shadow mb-4 hidden" id="cuadro2">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Registro de Tareas</h6>
  </div>
  <div class="card-body">
      <form class="user" autocomplete="off" method="post" id="store" enctype="multipart/form-data">
        @csrf

        <div class="row">

           <div class="col-md-6">
              
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                        <label for=""><b>Responsable</b></label>
                        <select name="responsable" id="responsable-store" class="form-control select2" required>
                            <option value="">Seleccione</option>
                        </select>
                    </div>
                </div>
              </div>


              <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                          <label for=""><b>Asunto</b></label>
                          <input type="text" name="issue" id="issue-store" class="form-control" required >
                      </div>
                  </div>
              </div>


              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                        <label for=""><b>Fecha</b></label>
                        <input type="date" name="fecha" id="fecha-store" class="form-control" required min="<?= date("Y-m-d")?>">
                    </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                        <label for=""><b>Hora</b></label>
                        <input type="time" name="time" id="time-store" class="form-control" required>
                    </div>
                </div>
              </div>


              <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for=""><b>Estatus</b></label>
                        <select name="status_task" id="status_task-store" class="form-control" required>
                            <option value="Abierta">Abierta</option>
                            
                        </select>
                    </div>
                </div>
              </div>
              
           </div>


           <div class="col-md-6">
              <div class="row">
                <div class="col-md-12">
                   <div class="form-group">
                        <label for=""><b>Comentarios</b></label>
                        <!-- <textarea name="observaciones" id="observaciones-store" class="form-control" cols="30" rows="5"></textarea> -->
                        <textarea id="summernote" class="form-control" name="observaciones"></textarea>
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
            <button id="send_usuario" class="btn btn-primary btn-user">
                Registrar
            </button>

          </center>
          <br>
          <br>
      </form>
      
    </div>

