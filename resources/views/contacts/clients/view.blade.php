<div class="card shadow mb-4 hidden" id="cuadro3">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Consulta de Cliente</h6>
  </div>
  <div class="card-body">
      <form class="user" autocomplete="off" method="post"  enctype="multipart/form-data">
      
        @csrf

        <div class="row">
        
           <div class="col-md-3">
             <label for=""><b>Nombre</b></label>
              <div class="form-group valid-required">
                <input type="text" name="name" class="form-control form-control-user" id="name_view" placeholder="Nombre" required>
              </div>
          </div>

          <div class="col-md-3">
             <label for=""><b>NIT</b></label>
              <div class="form-group valid-required">
                <input type="text" name="nit" class="form-control form-control-user" id="nit_view" placeholder="Nit" required>
              </div>
          </div>

          <div class="col-md-3">
             <label for=""><b>Telefono</b></label>
              <div class="form-group valid-required">
                <input type="text" name="phone" class="form-control form-control-user" id="phone_view" placeholder="Pj: 3152077862">
              </div>
          </div>


          <div class="col-md-3">
             <label for=""><b>Email</b></label>
              <div class="form-group valid-required">
                <input type="email" name="email" class="form-control form-control-user" id="email_view" placeholder="Pj: cardenascarlos18@gmail.com">
              </div>
          </div>

        </div>


        <div class="row">

         <div class="col-md-3">
           <label for=""><b>Ciudad</b></label>
              <div class="form-group valid-required">
                <input type="text" name="city" class="form-control form-control-user" id="city_view" placeholder="Pj: Medellin">
              </div>
          </div>
              
        </div>




        <div class="row">
          <div class="col-md-12">
              <label for=""><b>Direccion</b></label>
              <div class="form-group valid-required">
                <textarea name="address" class="form-control" id="address_view" placeholder="Pj: cra 107 - 30 #108 99" cols="30" rows="10"></textarea>
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

