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
			          <h1 class="h3 mb-2 text-gray-800">Recepciones Tecnicas</h1>

			          <div id="alertas"></div>
			          <input type="hidden" class="id_user">
			          <input type="hidden" class="token">

			          <!-- DataTales Example -->
			          <div class="card shadow mb-4" id="cuadro1">
			            <div class="card-header py-3">
			              <h6 class="m-0 font-weight-bold text-primary">Gestion de Recepciones Tecnicas</h6>

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
								  <th>Nº</th>
								  <th>Proveedor</th>
								  <th>Total</th>
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


			          @include('implantes.store')
					  @include('implantes.view')
					  @include('implantes.edit')


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




			  <div class="modal fade bd-example-modal-lg" id="modal_product" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Agregar un Producto</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              
                  <div class="modal-body">

                    
                      
                    <div class="row">
                      <div class="col-md-12">
                        <label for=""><b>Categorias</b></label>
                          <div class="form-group valid-required">
                            <select name="category" id="category" class="form-control select2">
                              <option value="">Selecciones</option>
                            </select>
                          </div>
                      </div>
                    </div>



                    <div class="row">
                        <div class="col-md-12">
                          <label for=""><b>Descripcion</b></label>
                            <div class="form-group valid-required">
                              <input type="text" name="description" class="form-control form-control-user" id="description" placeholder="Pj: DEXTROSA 5%">
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-12">
                          <label for=""><b>Laboratorio</b></label>
                            <div class="form-group valid-required">
                              <input type="text" name="laboratory" class="form-control form-control-user" id="laboratory" placeholder="Pj: ALFASAFE">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12">
                        <label for=""><b>Presentacion Comercial</b></label>
                          <div class="form-group valid-required">
                            <input type="text" name="commercial_presentation" class="form-control form-control-user" id="commercial_presentation" placeholder="Pj: FRASCO">
                          </div>
                      </div>
                    </div>
                    
                  </div>
              
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  <button type="button" id="save_product" class="btn btn-primary">Guardar</button>
                </div>
              
            </div>
          </div>
        </div>

		    </div>
		    <!-- End of Content Wrapper -->

		  </div>
		  <input type="hidden" id="indicador_edit">
		  <input type="hidden" id="ruta" value="<?= url('/') ?>">
	@endsection

	@section('CustomJs')

		<script>
			$(document).ready(function(){
				store();
				list();
				update();

				$("#collapse_Almacen").addClass("show");
				$("#nav_technical_reception, #modulo_Almacen").addClass("active");

				verifyPersmisos(id_user, tokens, "technical_reception");
			});



			function update(){
				enviarFormularioPut("#form-update", 'api/implantes/technical/reception/edit', '#cuadro4', false, "#avatar-edit");
			}


			function store(){
				enviarFormulario("#store", 'api/implantes/technical/reception', '#cuadro2');
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
						 "url":''+url+'/api/technical/reception/implante',
						//  "data": {
						// 	"id_user": id_user,
						// 	"token"  : tokens,
						// },
						"dataSrc":""
					},
					"columns":[
						{"data": null,
							render : function(data, type, row) {
								var botones = "";
								if(actualizar == 1)
									botones += "<span class='editar btn btn-sm btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fas fa-edit' style='margin-bottom:5px'></i></span> ";
								if(borrar == 1)
									botones += "<span class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span>";
								return botones;
							}
						},
						{"data":"id"},
						{
							"data":"name_provider",
							render: function(data,type,row) {
								return row.proveedor.name
							}
						},
						{"data":"total_invoice"},
						{"data": "created_at"},
						{
							"data": "email_regis",
							render:function(data,type,row){
								return row.user.email
							}
						}
						
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

			function getProviders(select, select_default = false){
			
				$.ajax({
					url: ''+document.getElementById('ruta').value+'/api/providers',
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


			function ChangeProviders(select, edit = ''){
				$(select).change(function (e) { 
					
					$.ajax({
						url: ''+document.getElementById('ruta').value+'/api/providers/'+$(select).val(),
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
							$(`#nit_provider${edit}`).val(data.name)
							$(`#address_provider${edit}`).val(data.address)
							$(`#phone_provider${edit}`).val(data.phone)
							$(`#email_provider${edit}`).val(data.email)
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
							$('#table_products tbody').empty();
							// var validaProduct = false
							// $(table + " tbody tr").each(function() {
							// 	if (data.id == $(this).find(".id_product").val()) {
							// 		validaProduct = true;
							// 	}
							// });

							// if(!validaProduct){
								html += "<tr>"
									html +="<td><input type='text' class='form-control' name='serial[]' required></td>"
									html +="<td><input type='text' class='form-control' name='lotes[]' required></td>"
									html +="<td><input type='text' class='form-control' name='register_invima[]' required></td>"
									html +="<td><input type='date' class='form-control' name='date_expiration[]' required></td>"
									html +="<td><input style='text-align: right;width: 142px;' type='text'  class='form-control monto_formato_decimales price_product items_calc' onkeyup='calcProduc(this)' name='price[]' required></td>"
									html +="<td><input type='text' class='form-control' name='gramaje[]' required></td>"
									html +="<td><input type='text' class='form-control' name='total' required></td>"
									
									html +="<td><span onclick='deleteProduct(this)' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
								html += "</tr>"
							// }else{
							// 	warning('¡La opción seleccionada ya se encuentra agregada!');
							// }
							
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
			
			function io(){
				try {	
				var ws = new WebSocket("ws://localhost:3001/chat");
				ws.on('serial', function(code){
					$('#serial').text(code);
				});
				} catch (e) {
					console.warn(e)
				}
			}

			function AddProductosEdit(btn, select_product, table){
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
							$('#table_products_edit tbody').empty();
							// var validaProduct = false
							// $(table + " tbody tr").each(function() {
							// 	if (data.id == $(this).find(".id_product").val()) {
							// 		validaProduct = true;
							// 	}
							// });

							// if(!validaProduct){
								html += "<tr>"
								html +="<td><input type='text' class='form-control' name='serial[]' required></td>"
									html +="<td><input type='text' class='form-control' name='lotes[]' required></td>"
									html +="<td><input type='text' class='form-control' name='register_invima[]' required></td>"
									html +="<td><input type='date' class='form-control' name='date_expiration[]' required></td>"
									html +="<td><input style='text-align: right;width: 142px;' type='text'  class='form-control monto_formato_decimales price_product items_calc' onkeyup='calcProduc(this)' name='price[]' required></td>"
									html +="<td><input type='text' class='form-control' name='gramaje[]' required></td>"
									html +="<td><input type='text' class='form-control' name='total' required></td>"
									html +="<td><span onclick='deleteProduct(this, "+'"_edit"'+")' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
								html += "</tr>"
							// }else{
							// 	warning('¡La opción seleccionada ya se encuentra agregada!');
							// }
							
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
				$("#indicador_edit").val(0)
				getProviders("#provider")
				ChangeProviders("#provider")
				AddProductos("#add_product", "#products", "#table_products")

				$('#table_products tbody').empty();

				getProducts("#products")

				GetCategories("#category")


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
					
					console.log('data',data);

					$("#indicador_edit").val(1)
					getProviders("#provider_edit", data.id_provider)
					ChangeProviders("#provider_edit", "_edit")
					$("#provider_edit").trigger("change");

					getProducts("#products_edit")


					ShowProdcuts("#table_products_edit", data)

					$("#subtotal_text_edit").text(`$ ${number_format(data.subtotal, 2)}`)
					$("#subtotal_edit").val(data.subtotal)

					$("#vat_total_text_edit").text(`$ ${number_format(data.vat_total, 2)}`)
					$("#vat_total_edit").val(data.vat_total)

					$("#total_invoice_text_edit").text(`$ ${number_format(data.total_invoice, 2)}`)
					$("#total_invoice_edit").val(data.total_invoice)

					$("#discount_edit").val(number_format(data.discount, 2))
					$("#rte_fuente_edit").val(number_format(data.rte_fuente, 2))

					$("#observations_edit").val(data.observations)

					$('#table_products tbody').empty();	

					AddProductosEdit("#add_product_edit", "#products_edit", "#table_products_edit")

					GetCategories("#category")
					
					cuadros('#cuadro1', '#cuadro4');
					$("#id_edit").val(data.id)
					cuadros('#cuadro1', '#cuadro4');
				});
			}


			function ShowProdcuts(table, data){
				let html = ""
				$.map(data.detalle, function (item, key) {
					html += "<tr>"
					html +="<td><input type='text' class='form-control' name='serial[]'  value='"+item.serial+"'  required></td>"
					html +="<td><input type='text' class='form-control' name='lotes[]'  value='"+item.lote+"'  required></td>"
					html +="<td><input type='text' class='form-control' name='register_invima[]'  value='"+item.register_invima+"' required></td>"
					html +="<td><input type='date' class='form-control' name='date_expiration[]'  value='"+item.date_expiration+"' required></td>"
					html +="<td><input style='text-align: right;width: 142px;' type='text'  class='form-control monto_formato_decimales price_product items_calc' onkeyup='calcProduc(this)' name='price[]' value='"+number_format(item.price, 2)+"' required></td>"
					html +="<td><input type='text' class='form-control' name='gramaje[]'  value='"+item.gramaje+"' required></td>"
					html +="<td><input type='text' class='form-control' name='total'  value='"+data.total_invoice+"' required></td>"
		            //html +="<td><input style='text-align: right;width: 142px;' type='text' class='form-control monto_formato_decimales total_product' value='"+number_format(item.total, 2)+"'  name='total[]' readonly required></td>"
					html +="<td><span onclick='deleteProduct(this, "+'"_edit"'+")' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
					html += "</tr>"

				});

				$(table+" tbody").html(html)


			}
					
		/* ------------------------------------------------------------------------------- */
			/*
				Funcion que capta y envia los datos a desactivar
			*/
			function desactivar(tbody, table){
				$(tbody).on("click", "span.desactivar", function(){
					var data=table.row($(this).parents("tr")).data();
					statusConfirmacion('api/technical/reception/status/'+data.id+"/"+2,"¿Esta seguro de desactivar el registro?", 'desactivar');
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
					statusConfirmacion('api/technical/reception/status/'+data.id+"/"+1,"¿Esta seguro de desactivar el registro?", 'activar');
				});
			}
		/* ------------------------------------------------------------------------------- */



			function eliminar(tbody, table){
				$(tbody).on("click", "span.eliminar", function(){
					// console.log('hola');
					var data=table.row($(this).parents("tr")).data();
					statusConfirmacion('api/technical/reception/implante/delete/'+data.id+"","¿Esta seguro de eliminar el registro?", 'Eliminar');
				});
			}



			function calcProduc(element, edit = ''){

				var price = inNum($(element).parent("td").parent("tr").children("td").find(".price_product").val())
				var qty   = inNum($(element).parent("td").parent("tr").children("td").find(".qty_product").val())
				var vat   = $(element).parent("td").parent("tr").children("td").find(".vat_product")

				

				let total
				if(vat.is(':checked')){

					total = ((price * qty) * 1.19)
					$(element).parent("td").parent("tr").children("td").find(".vat_hidden").val(1)

				}else{

					total = (price * qty)
					$(element).parent("td").parent("tr").children("td").find(".vat_hidden").val(0)

				}

				$(element).parent("td").parent("tr").children("td").find(".total_product").val(number_format(total, 2))

				calcSubTotal(".price_product", edit)
				calcTotalVat(".vat_product", edit)
				calTotal(".total_product", edit)
			

			}


			function deleteProduct(element){
				var tr = $(element).parent("td").parent("tr").children("td").find(".price_product").val()
			}

			function calcSubTotal(fields, edit = ''){
				let subtotal = 0
				$.map($(fields), function (item, key) {

					const qty       = $(item).parent("td").parent("tr").children("td").find(".qty_product").val()
					

					const total = inNum($(item).val()) * qty
					subtotal    = parseFloat(subtotal) + parseFloat(total)
					
				});

				$(`#subtotal_text${edit}`).text(`$ ${number_format(subtotal, 2)}`)
				$(`#subtotal${edit}`).val(subtotal)
			}



			function calcTotalVat(fields,  edit = ''){
				let totalVat  = 0
				$.map($(fields), function (item, key) {

					if($(item).is(':checked')){
						const price = inNum($(item).parent("td").parent("tr").children("td").find(".price_product").val())
						const qty   = $(item).parent("td").parent("tr").children("td").find(".qty_product").val()

						const vat = ((price * qty) * 0.19)

						totalVat = totalVat + vat
					}

				});

				$(`#vat_total_text${edit}`).text(`$ ${number_format(totalVat, 2)}`)
				$(`#vat_total${edit}`).val(totalVat)
			}


			function calTotal(fields, edit = ''){

				let total_invoice = 0

				
				$.map($(fields), function (item, key) {

					if($(item).val() != ""){
						total_invoice = parseFloat(total_invoice) + parseFloat(inNum($(item).val()))
					}

				});

				

				const discount  = inNum($(`#discount${edit}`).val())
				const rte_fuete = inNum($(`#rte_fuente${edit}`).val())

				total_invoice   = ((total_invoice - discount) - rte_fuete)
				$(`#total_invoice_text${edit}`).text(`$ ${number_format(total_invoice, 2)}`)
				$(`#total_invoice${edit}`).val(total_invoice)

			}
			


			$(".discount").keyup(function (e) { 
				calTotal(".total_product")
			});


			$(".discount_edit").keyup(function (e) { 
				calTotal(".total_product", '_edit')
			});



			function deleteProduct(element, edit = ''){
				var tr = $(element).parent("td").parent("tr").remove()

				calcSubTotal(".price_product", edit)
				calcTotalVat(".vat_product", edit)
				calTotal(".total_product", edit)
			}


			$("#save_product").click(function (e) { 

				const data = {
					"category" : $("#category").val(),
					"description" : $("#description").val(),
					"commercial_presentation" : $("#commercial_presentation").val(),
					"laboratory" : $("#laboratory").val(),
					"id_user": id_user,
					"token"  : tokens
				}
				
				var indicador_edit = $("#indicador_edit").val()

				$.ajax({
					url: ''+document.getElementById('ruta').value+'/api/products',
					type: "POST",
					data: data,
					dataType:'JSON',
					async: false,
					error: function() {
						
					},
					success: function(data){
						$("#modal_product").modal('hide')


						let html = ""
						html += "<tr>"
							html +="<td>"+data.data.description+" <input type='hidden' class='id_product' name='id_product[]' value='"+data.data.id+"' > </td>"
							html +="<td>"+data.data.commercial_presentation+"</td>"
							html +="<td><input type='text' class='form-control' name='laboratory[]' value='"+data.data.laboratory+"' required></td>"
							html +="<td><input type='text' class='form-control' name='lotes[]' required></td>"
							html +="<td><input type='text' class='form-control' name='register_invima[]' required></td>"
							html +="<td><input type='date' class='form-control' name='date_expiration[]' required></td>"

							if(indicador_edit == 0){
								html +="<td><input style='text-align: right;width: 142px;' type='text' class='form-control monto_formato_decimales price_product items_calc' onkeyup='calcProduc(this)' name='price[]' required></td>"
							}else{
								html +="<td><input style='text-align: right;width: 142px;' type='text' class='form-control monto_formato_decimales price_product items_calc' onkeyup='calcProduc(this, "+'"_edit"'+")' name='price[]' required></td>"
							}


							if(indicador_edit == 0){
								html +="<td><input type='text' class='form-control qty_product items_calc' name='qty[]' value='1' onkeyup='calcProduc(this)' required></td>"
							}else{
								html +="<td><input type='text' class='form-control qty_product items_calc' name='qty[]' value='1' onkeyup='calcProduc(this, "+'"_edit"'+")' required></td>"
							}

							if(indicador_edit == 0){
								html +="<td><input type='checkbox' class='form-control vat_product items_calc'  onchange='calcProduc(this)'><input type='hidden' class='vat_hidden' name='vat[]' value='0'></td>"
							}else{
								html +="<td><input type='checkbox' class='form-control vat_product items_calc'  onchange='calcProduc(this, "+'"_edit"'+")'><input type='hidden' class='vat_hidden' name='vat[]' value='0'></td>"
							}
							
							
							
							html +="<td><input style='text-align: right;width: 142px;' type='text' class='form-control monto_formato_decimales total_product' name='total[]' readonly required></td>"



							if(indicador_edit == 0){
								html +="<td><span onclick='deleteProduct(this)' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
							}else{
								html +="<td><span onclick='deleteProduct(this, "+'"_edit"'+")' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
							}


							
						html += "</tr>"

						$("#table_products tbody").append(html)

						$("#table_products_edit tbody").append(html)





					}
				
				});
				
			});


		</script>
		



	@endsection


