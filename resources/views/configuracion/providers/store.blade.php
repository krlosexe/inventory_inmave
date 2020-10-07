<div class="card shadow mb-4 hidden" id="cuadro2">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Registro de Proveedores</h6>
  </div>
  <div class="card-body">
      <form class="user" autocomplete="off" method="post" id="store" enctype="multipart/form-data">
        @csrf
        <div class="row">
        
           <div class="col-md-3">
             <label for=""><b>Nombre</b></label>
              <div class="form-group valid-required">
                <input type="text" name="name" class="form-control form-control-user" id="name" placeholder="Nombre" required>
              </div>
          </div>

          <div class="col-md-3">
             <label for=""><b>NIT</b></label>
              <div class="form-group valid-required">
                <input type="text" name="nit" class="form-control form-control-user" id="nit" placeholder="Nit" required>
              </div>
          </div>

          <div class="col-md-3">
             <label for=""><b>Telefono</b></label>
              <div class="form-group valid-required">
                <input type="text" name="phone" class="form-control form-control-user" id="phone" placeholder="Pj: 3152077862">
              </div>
          </div>


          <div class="col-md-3">
             <label for=""><b>Email</b></label>
              <div class="form-group valid-required">
                <input type="email" name="email" class="form-control form-control-user" id="email" placeholder="Pj: cardenascarlos18@gmail.com">
              </div>
          </div>

        </div>


        <div class="row">
          <div class="col-md-12">
              <label for=""><b>Direccion</b></label>
              <div class="form-group valid-required">
                <textarea name="address" class="form-control" id="address" placeholder="Pj: cra 107 - 30 #108 99" cols="30" rows="10"></textarea>
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

