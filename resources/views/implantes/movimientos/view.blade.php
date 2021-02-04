<div class="card shadow mb-4 hidden" id="cuadro3">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Detallde de Productos Traspaso</h6>
  </div>
  <div class="card-body">
    <form class="user" autocomplete="off" method="post" enctype="multipart/form-data">

      @csrf

      <div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered" id="table_view" width="100%" cellspacing="0">
								<thead>
									<tr>
										<!-- <th>Acciones</th> -->
										<th>Feferencia</th>
										<!-- <th>Movimiento</th>
										<th>Precio Compra (Euro)</th> -->
										<th>Serial</th>
										<th>Cantidad</th>
										<!-- <th>Lote</th>  -->
										<!-- <th>Origen</th>
										<th>Destino</th>
										<th>Responsable</th>
										<th>Fecha</th> -->
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
					</div>

      <!-- <input type="hidden" name="id_user" class="id_user">
      <input type="hidden" name="token" class="token"> -->
      <!-- <br>
      <br> -->
  </div>
  <center>
    <button type="button" class="btn btn-danger btn-user" onclick="prev('#cuadro3')">
      Cancelar
    </button>
  </center>
  <br>
  <br>
  </form>

</div>