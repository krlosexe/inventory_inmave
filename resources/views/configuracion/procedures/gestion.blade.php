@extends('layouts.app')
	

	@section('CustomCss')

		<style>
			.kv-avatar .krajee-default.file-preview-frame,.kv-avatar .krajee-default.file-preview-frame:hover {
			    margin: 0;
			    padding: 0;
			    border: none;
			    box-shadow: none;
			    text-align: center;
			}
			.kv-avatar {
			    display: inline-block;
			}
			.kv-avatar .file-input {
			    display: table-cell;
			    width: 213px;
			}
			.kv-reqd {
			    color: red;
			    font-family: monospace;
			    font-weight: normal;
			}
		</style>


	@endsection


	@section('content')
	     <!-- Page Wrapper -->
		  <div id="wrapper">

		    @include('layouts.sidebar')

		    <!-- Content Wrapper -->
		    <div id="content-wrapper" class="d-flex flex-column">

		      <!-- Main Content -->
		      <div id="content">

				@include('layouts.topBar') 
		       

		        <!-- Begin Page Content -->
			        <div class="container-fluid">

			          <!-- Page Heading -->
			          <h1 class="h3 mb-2 text-gray-800">Procedimientos</h1>

			          <div id="alertas"></div>
			          <input type="hidden" class="id_user">
			          <input type="hidden" class="token">

			          <!-- DataTales Example -->
			          <div class="card shadow mb-4" id="cuadro1">
			            <div class="card-header py-3">
			              <h6 class="m-0 font-weight-bold text-primary">Gestion de Procedimientos</h6>

			              <button onclick="nuevo()" class="btn btn-primary btn-icon-split" style="float: right;">
		                    <span class="icon text-white-50">
		                      <i class="fas fa-plus"></i>
		                    </span>
		                    <span class="text">Nuevo registro</span>
		                  </button>
			            </div>
			            <div class="card-body">
			              <div class="table-responsive">
			                <table class="table table-bordered" id="table" width="100%" cellspacing="0">
			                  <thead>
			                    <tr>
								  <th>Acciones</th>
								  <th>Nombre</th>
			                      <th>Fecha de registro</th>
								  <th>Registrado por</th>
			                    </tr>
			                  </thead>
			                  <tbody>
			                    
			                  </tbody>
			                </table>
			              </div>
			            </div>
			          </div>


			          @include('configuracion.procedures.store')
					  @include('configuracion.procedures.view')
					  @include('configuracion.procedures.edit')


			        </div>
			        <!-- /.container-fluid -->

		      </div>
		      <!-- End of Main Content -->

		      <!-- Footer -->
		      <footer class="sticky-footer bg-white">
		        <div class="container my-auto">
		          <div class="copyright text-center my-auto">
		            <span>Copyright &copy; Your Website 2019</span>
		          </div>
		        </div>
		      </footer>
		      <!-- End of Footer -->

		    </div>
		    <!-- End of Content Wrapper -->

		  </div>
		  <input type="hidden" id="ruta" value="<?= url('/') ?>">
	@endsection





	@section('CustomJs')

		<script>
			$(document).ready(function(){
				store();
				list();
				update();

				$("#collapse_Configuracion").addClass("show");
				$("#nav_procedures, #modulo_Configuracion").addClass("active");

				verifyPersmisos(id_user, tokens, "procedures");
			});



			function update(){
				enviarFormularioPut("#form-update", 'api/procedures', '#cuadro4', false, "#avatar-edit");
			}


			function store(){
				enviarFormulario("#store", 'api/procedures', '#cuadro2');
			}



			function list(cuadro) {
				var data = {
					"id_user": id_user,
					"token"  : tokens,
				};
				$('#table tbody').off('click');
				var url=document.getElementById('ruta').value; 
				cuadros(cuadro, "#cuadro1");

				var table=$("#table").DataTable({
					"destroy":true,
					
					"stateSave": true,
					"serverSide":false,
					"ajax":{
						"method":"GET",
						 "url":''+url+'/api/procedures',
						 "data": {
							"id_user": id_user,
							"token"  : tokens,
						},
						"dataSrc":""
					},
					"columns":[
						{"data": null,
							render : function(data, type, row) {
								var botones = "";
								if(consultar == 1)
									botones += "<span class='consultar btn btn-sm btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
								if(actualizar == 1)
									botones += "<span class='editar btn btn-sm btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fas fa-edit' style='margin-bottom:5px'></i></span> ";
								if(data.status == 1 && actualizar == 1)
									botones += "<span class='desactivar btn btn-sm btn-warning waves-effect' data-toggle='tooltip' title='Desactivar'><i class='fa fa-unlock' style='margin-bottom:5px'></i></span> ";
								else if(data.status == 2 && actualizar == 1)
									botones += "<span class='activar btn btn-sm btn-warning waves-effect' data-toggle='tooltip' title='Activar'><i class='fa fa-lock' style='margin-bottom:5px'></i></span> ";
								if(borrar == 1)
									botones += "<span class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span>";
								return botones;
							}
						},
						{"data":"nombre"},
						{"data": "fec_regins"},
						{"data": "email_regis"}
						
					],
					"language": idioma_espanol,
					"dom": 'Bfrtip',
					"responsive": true,
					"buttons":[
						'copy', 'csv', 'excel', 'pdf', 'print'
					]
				});


				ver("#table tbody", table)
				edit("#table tbody", table)
				activar("#table tbody", table)
				desactivar("#table tbody", table)
				eliminar("#table tbody", table)


			}





			function getCategory(select, select_default = false){
			
				$.ajax({
					url: 'http://pdtclientsolutions.com/crm-public/api/category',
					type:'GET',
					data: {
						"id_user": id_user,
						"token"  : tokens,
					},
					dataType:'JSON',
					async: false,
					error: function() {
						
					},
					success: function(data){
						$(select+" option").remove();
						$(select).append($('<option>',
						{
							value: "",
							text : "Seleccione"
						}));
					
						$.each(data, function(i, item){
							
							
							$(select).append($('<option>',
							{
								value: item.id,
								text : item.name,
								selected : select_default == item.id ? true : false
								
							}));

							
						});

					}
				
				});
			}


			function ChangeCategory(select, select_sub, select_default = false){
				$(select).change(function (e) { 
					
					$.ajax({
						url: 'http://pdtclientsolutions.com/crm-public/api/category/sub/'+$(select).val(),
						type:'GET',
						data: {
							"id_user": id_user,
							"token"  : tokens,
						},
						dataType:'JSON',
						async: false,
						error: function() {
							
						},
						success: function(data){
							$(select_sub+" option").remove();
							$(select_sub).append($('<option>',
							{
								value: "",
								text : "Seleccione"
							}));
							$.each(data, function(i, item){
								$(select_sub).append($('<option>',
								{
									value: item.id,
									text : item.name,
									selected : select_default == item.id ? true : false
									
								}));

								
							});

						}
					
					});
					
				});
			}


		function getProducts(select, select_default = false){
			
			$.ajax({
				url: ''+document.getElementById('ruta').value+'/api/products',
				type:'GET',
				data: {
					"id_user": id_user,
					"token"  : tokens,
				},
				dataType:'JSON',
				async: false,
				error: function() {
					
				},
				success: function(data){
					$(select+" option").remove();
					$(select).append($('<option>',
					{
						value: "",
						text : "Seleccione"
					}));
				
					$.each(data, function(i, item){
						
						
						$(select).append($('<option>',
						{
							value: item.id,
							text : item.description,
							selected : select_default == item.id ? true : false
							
						}));

						
					});


					$(select).select2({
						width : "100%",
						sorter: function(data) {
							/* Sort data using lowercase comparison */
							return data.sort(function (a, b) {
								a = a.text.toLowerCase();
								b = b.text.toLowerCase();
								if (a > b) {
									return 1;
								} else if (a < b) {
									return -1;
								}
								return 0;
							});
						}
					});

				}
			
			});
		}
		



			function AddProductos(btn, select_product, table){
				$(btn).unbind().click(function (e) { 
					
					$.ajax({
						url: ''+document.getElementById('ruta').value+'/api/products/'+$(select_product).val(),
						type:'GET',
						data: {
							"id_user": id_user,
							"token"  : tokens,
						},
						dataType:'JSON',
						async: false,
						error: function() {
							
						},
						success: function(data){
							var html 

							var validaProduct = false
							$(table + " tbody tr").each(function() {
								if (data.id == $(this).find(".id_product").val()) {
									validaProduct = true;
								}
							});

							if(!validaProduct){
								html += "<tr>"
									html +="<td>"+data.description+" <input type='hidden' class='id_product' name='id_product[]' value='"+data.id+"' > </td>"
									html +="<td><input type='number' class='form-control qty_product items_calc' name='qty[]' value='1' onkeyup='calcProduc(this)' required></td>"
									html +="<td><span onclick='deleteProduct(this)' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
								html += "</tr>"
							}else{
								warning('¡La opción seleccionada ya se encuentra agregada!');
							}
							


							$(table+" tbody").append(html)

					
							$(".monto_formato_decimales").change(function() {   
								if($(this).val() != ""){  
									$(this).val(number_format($(this).val(), 2));   
								}       
							});

						}
					
					});
					
				});
			}






			function nuevo() {
				$("#alertas").css("display", "none");
				$("#store")[0].reset();

				getCategory("#category", 124124124)
				ChangeCategory("#category", "#id_procedure")
				getProducts("#products")

				AddProductos("#add_product", "#products", "#table_products")



				cuadros("#cuadro1", "#cuadro2");
			}

			/* ------------------------------------------------------------------------------- */
			/* 
				Funcion que muestra el cuadro3 para la consulta del banco.
			*/
			function ver(tbody, table){
				$(tbody).on("click", "span.consultar", function(){
					$("#alertas").css("display", "none");
					var data = table.row( $(this).parents("tr") ).data();

					$("#nombre-view").val(data.nombre).attr("disabled", "disabled")
					
					cuadros('#cuadro1', '#cuadro3');
				});
			}



			/* ------------------------------------------------------------------------------- */
			/* 
				Funcion que muestra el cuadro3 para la consulta del banco.
			*/
			function edit(tbody, table){
				$(tbody).on("click", "span.editar", function(){
					$("#alertas").css("display", "none");
					var data = table.row( $(this).parents("tr") ).data();

					$("#nombre-edit").val(data.nombre)
					cuadros('#cuadro1', '#cuadro4');
					$("#id_edit").val(data.id)
					cuadros('#cuadro1', '#cuadro4');
				});
			}



					
		/* ------------------------------------------------------------------------------- */
			/*
				Funcion que capta y envia los datos a desactivar
			*/
			function desactivar(tbody, table){
				$(tbody).on("click", "span.desactivar", function(){
					var data=table.row($(this).parents("tr")).data();
					statusConfirmacion('api/procedures/status/'+data.id+"/"+2,"¿Esta seguro de desactivar el registro?", 'desactivar');
				});
			}
		/* ------------------------------------------------------------------------------- */

		/* ------------------------------------------------------------------------------- */
			/*
				Funcion que capta y envia los datos a desactivar
			*/
			function activar(tbody, table){
				$(tbody).on("click", "span.activar", function(){
					var data=table.row($(this).parents("tr")).data();
					statusConfirmacion('api/procedures/status/'+data.id+"/"+1,"¿Esta seguro de desactivar el registro?", 'activar');
				});
			}
		/* ------------------------------------------------------------------------------- */



			function eliminar(tbody, table){
				$(tbody).on("click", "span.eliminar", function(){
					var data=table.row($(this).parents("tr")).data();
					statusConfirmacion('api/procedures/status/'+data.id+"/"+0,"¿Esta seguro de eliminar el registro?", 'Eliminar');
				});
			}

		</script>
		



	@endsection


