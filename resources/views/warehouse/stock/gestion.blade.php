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
			          <h1 class="h3 mb-2 text-gray-800">Stock de Productos</h1>
			          <div id="alertas"></div>
			          <input type="hidden" class="id_user">
			          <input type="hidden" class="token">
			          <!-- DataTales Example -->
			          <div class="card shadow mb-4" id="cuadro1">
			            <div class="card-header py-3">
			              <h6 class="m-0 font-weight-bold text-primary">Gestion de Stock de Productos</h6>
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
								  <th>#</th>
								  <th>Bodega</th>
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
			          @include('warehouse.stock.store')
					  @include('warehouse.stock.view')
					  @include('warehouse.stock.edit')
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

				$("#collapse_Almacen").addClass("show");
				$("#nav_stock, #modulo_Almacen").addClass("active");

				verifyPersmisos(id_user, tokens, "stock");
			});
			function update(){
				enviarFormularioPut("#form-update", 'api/products/entry/stock', '#cuadro4', false, "#avatar-edit");
			}
			function store(){
				enviarFormulario("#store", 'api/products/entry/stock', '#cuadro2');
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
						 "url":''+url+'/api/products/entry/stock',
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
								return botones;
							}
						},
						{"data":"id"},
						{"data":"warehouse"},
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
									html +="<td>"+data.presentation+"</td>"
									html +="<td><input type='number' class='form-control qty_product items_calc' name='qty[]' value='1' onkeyup='calcProduc(this)' min = '1' required></td>"
									html +="<td><span onclick='deleteProduct(this, "+'""'+")' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
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
				GetCategories("#category")
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

					$("#warehouse_view").val(data.warehouse).trigger("change").attr("disabled", "disabled")

					ShowProdcuts("#table_products_view", data.products)
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

					$("#warehouse_edit").val(data.warehouse).trigger("change")

					getProducts("#products_edit")

					ShowProdcuts("#table_products_edit", data.products)
					AddProductos("#add_product_edit", "#products_edit", "#table_products_edit")
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
					statusConfirmacion('api/products/entry/stock/status/'+data.id+"/"+2,"¿Esta seguro de desactivar el registro?", 'desactivar');
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
					statusConfirmacion('api/products/entry/stock/status/'+data.id+"/"+1,"¿Esta seguro de desactivar el registro?", 'activar');
				});
			}
		/* ------------------------------------------------------------------------------- */



			function eliminar(tbody, table){
				$(tbody).on("click", "span.eliminar", function(){
					var data=table.row($(this).parents("tr")).data();
					statusConfirmacion('api/products/entry/stock/status/'+data.id+"/"+0,"¿Esta seguro de eliminar el registro?", 'Eliminar');
				});
			}




			function ShowProdcuts(table, data){
				let html = ""
				$.map(data, function (item, key) {
					
					html += "<tr>"
						html +="<td>"+item.description+" <input type='hidden' class='id_product' name='id_product[]' value='"+item.id_product+"' > </td>"
						html +="<td>"+item.presentation+"</td>"
						html +="<td><input type='text' class='form-control qty_product items_calc' name='qty[]' value='"+item.qty+"' onkeyup='calcProduc(this, "+'"_edit"'+")' required></td>"
						html +="<td><span onclick='deleteProduct(this, "+'"_edit"'+")' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
					html += "</tr>"

				});

				$(table+" tbody").html(html)

			}


			function deleteProduct(element, edit = ''){
				var tr = $(element).parent("td").parent("tr").remove()
			}




		</script>
		



	@endsection


