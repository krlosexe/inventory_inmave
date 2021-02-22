@extends('layouts.app')


@section('CustomCss')

<style>
	.kv-avatar .krajee-default.file-preview-frame,
	.kv-avatar .krajee-default.file-preview-frame:hover {
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
				<h1 class="h3 mb-2 text-gray-800">Traspaso de Productos</h1>
				<div id="alertas"></div>
				<input type="hidden" class="id_user">
				<input type="hidden" class="token">
				<!-- DataTales Example -->
				<div class="card shadow mb-4" id="cuadro1">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-primary">Gestion de Traspaso de Productos</h6>
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
										<!-- <th>Codigo Producto</th>
										<th>Descripcion</th> -->
										<th>Movimiento</th>
										<!-- <th>Precio Compra (Euro)</th> -->
										<!-- <th>Cantidad</th>
										<th>Lote</th> -->
										<th>Origen</th>
										<th>Destino</th>
										<th>Responsable</th>
										<th>Fecha</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				@include('warehouse.movimientos.store')
				@include('warehouse.movimientos.view')
				@include('warehouse.movimientos.edit')
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
<input type="hidden" id="indicador_edit">
@endsection
@section('CustomJs')
<script>
	$(document).ready(function() {
		store();
		list();
		update();
		$("#collapse_Almacen").addClass("show");
		$("#nav_movimientos, #modulo_Almacen").addClass("active");
		verifyPersmisos(id_user, tokens, "output");
	});
	function update() {
		enviarFormularioPut("#form-update", 'api/products/entry/output', '#cuadro4', false, "#avatar-edit");
	}
	function store() {
		enviarFormulario("#store", 'api/products/movimiento/output', '#cuadro2');
	}
	function list(cuadro) {
		var data = {
			"id_user": id_user,
			"token": tokens,
		};
		$('#table tbody').off('click');
		var url = document.getElementById('ruta').value;
		cuadros(cuadro, "#cuadro1");
		var table = $("#table").DataTable({
			"destroy": true,
			"stateSave": true,
			"serverSide": false,
			"ajax": {
				"method": "GET",
				"url": '' + url + '/api/products/movimiento/list',
				"data": {
					"id_user": id_user,
					"token": tokens,
				},
				"dataSrc": ""
			},
			"columns": [{
					"data": null,
					render: function(data, type, row) {
						var botones = "";
						if (consultar == 1)
							botones += "<span class='consultar btn btn-sm btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
						return botones;
					}
				},
				{
					"data": "type"
				},
				// {
				// 	"data": "price"
				// },

				{
					"data": "warehouse"
				},
				{
					"data": "destiny"
				},
				{
					"data": "email",
					render: function(data, type, row) {
						return row.usuario.email;

					}
				},
				{
					"data": "created_at"
				},

			],
			"language": idioma_espanol,
			"dom": 'Bfrtip',
			"responsive": true,
			"buttons": [
				'copy', 'csv', 'excel', 'pdf', 'print'
			]
		});
		ver("#table tbody", table)
		// edit("#table tbody", table)
		// activar("#table tbody", table)
		// desactivar("#table tbody", table)
		// eliminar("#table tbody", table)
		$(".buttons-excel").remove()
		var a = '<button id="xls" class="dt-button buttons-excel buttons-html5">Excel</button>';
		$(".dt-buttons").append(a)
		var b = '<button id="view_xls" target="_blank" style="opacity: 0" href="api/output/export/excel" class="dt-button buttons-excel buttons-html5">xls</button>';
		$('.dt-buttons').append(b);
		$("#xls").click(function(e) {
			url = $("#view_xls").attr("href");
			window.open(url, '_blank');
		});
	}
	function nuevo() {
		$("#table_products" + " tbody").html("")
		$("#alertas").css("display", "none");
		$("#store")[0].reset();
		AddProductos("#add_product", "#products", "#table_products")
		ProductsGetExistence("#warehouse", "#products", "#table_products")
		getClients("#clients")
		$("#indicador_edit").val(0)
		cuadros("#cuadro1", "#cuadro2");
			$('#table_products tbody').empty();
				// $('#destiny').empty(0);
				// $('#warehouse').empty(0);
				$('#products').empty(0);
				
	}
	function getClients(select, select_default = false) {
		$.ajax({
			url: '' + document.getElementById('ruta').value + '/api/clients',
			type: 'GET',
			data: {
				"id_user": id_user,
				"token": tokens,
			},
			dataType: 'JSON',
			async: false,
			error: function() {
			},
			success: function(data) {
				$(select + " option").remove();
				$(select).append($('<option>', {
					value: "",
					text: "- Seleccione"
				}));
				$.each(data, function(i, item) {
					if (data.status == 1) {
					}
					$(select).append($('<option>', {
						value: item.id,
						text: item.name,
						selected: select_default == item.id ? true : false

					}));
				});
				$(select).select2({
					width: "100%",
					sorter: function(data) {
						/* Sort data using lowercase comparison */
						return data.sort(function(a, b) {
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
	function AddProductos(btn, select_product, table) {
		$(btn).unbind().click(function(e) {
			const array_product = $(select_product).val().split("|")
			const id_product = array_product[0]
			const total = array_product[1]
			const price = array_product[2]
			const lote = array_product[3]
			const price_e = array_product[4]
			const presentation = array_product[5]
			const date_expiration = array_product[6]
			const register_invima = array_product[7]
			const description = $(`${select_product} option:selected`).text()
			var html
			var validaProduct = false
			$(table + " tbody tr").each(function() {
				if (id_product == $(this).find(".id_product").val()) {
					validaProduct = false;
				}
			});
			if (!validaProduct) {
				html += "<tr>"
				html += "<td>" + description + " <input type='hidden' class='id_product' name='id_product[]' value='" + id_product + "' ><input type='hidden' class='id_product' name='ext_total[]' value='" + total + "' ><input type='hidden' class='id_product' name='lote[]' value='" + lote + "' ><input type='hidden' class='id_product' name='price_euro[]' value='" + price_e + "' ><input type='hidden' class='id_product' name='date_expiration[]' value='" + date_expiration + "' ><input type='hidden' class='id_product' name='register_invima[]' value='" + register_invima + "' >  </td>"
				html += "<td>" + presentation + " </td>"
				html += "<td><input type='number' class='form-control items_calc qty_product' name='qty[]' value='0' min = '1' onchange='calcProduc(this)' max='" + total + "' required></td>"
				html += "<td><input type='number' disabled class='form-control items_calc existence' value='" + total + "' min = '1' required><input type='hidden' disabled class='form-control items_calc existence_hidden' value='" + total + "'></td>"
				html += "<td><span onclick='deleteProduct(this, " + '""' + ")' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
				html += "</tr>"
			} else {
				warning('¡La opción seleccionada ya se encuentra agregada!');
			}
			$(table + " tbody").append(html)
		});
	}
	function ProductsGetExistence(warehouse, product, table) {
		$(warehouse).unbind().change(function(e) {
			$(table + " tbody").html("")
			$.ajax({
				url: `${document.getElementById('ruta').value}/api/products/get/existence/warehouse/${$(this).val()}`,
				type: 'GET',
				data: {
					"id_user": id_user,
					"token": tokens,
				},
				dataType: 'JSON',
				async: false,
				error: function() {
				},
				success: function(data) {
					var html
					if ($("#warehouse").val() == 'Medellin') {

						html += '<option value="Bogota">Bogota</option>'
						html += '<option value="Cali">Cali</option>'

						// $(product + " option").remove();
						// 	$(product).append($('<option>', {
						// 		value: "",
						// 		text: "-Seleccione"
						// 	}));
					}
					if ($("#warehouse").val() == 'Bogota') {

						html += '<option value="Medellin">Medellin</option>'
						html += '<option value="Cali">Cali</option>'

						// $(product + " option").remove();
						// 	$(product).append($('<option>', {
						// 		value: "",
						// 		text: "-Seleccione"
						// 	}));
					}
					if ($("#warehouse").val() == 'Cali') {
						html += '<option value="Bogota">Bogota</option>'
						html += '<option value="Medellin">Medellin</option>'

					}
					$("#destiny").html(html)
					$(product + " option").remove();
					$(product).append($('<option>', {
						value: "",
						text: "-Seleccione"
					}));
					$.each(data, function(i, item) {
						$(product).append($('<option>', {
							value: `${item.id_product}|${item.total}|${item.price_cop}|${item.lote}|${item.price_euro}|${item.presentation}|${item.date_expiration}|${item.register_invima}`,
							text: item.description,
						}));
					});
					$(product).select2({
						width: "100%",
						sorter: function(data) {
							/* Sort data using lowercase comparison */
							return data.sort(function(a, b) {
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
		});
	}
	function deleteProduct(element, edit = '') {
		var tr = $(element).parent("td").parent("tr").remove()
		calcSubTotal(".price_product", edit)
		calcTotalVat(".vat_product", edit)
		calTotal(".total_product", edit)
	}
	function ver(tbody, table) {
		$(tbody).on("click", "span.consultar", function() {
			$("#alertas").css("display", "none");
			var data = table.row($(this).parents("tr")).data();
			var url = document.getElementById('ruta').value;
			var table2 = $("#table_view").DataTable({
				"destroy": true,
				"stateSave": true,
				"serverSide": false,
				"ajax": {
					"method": "GET",
					"url": '' + url + '/api/products/movimiento/detail/' + data.id,
					"data": {
						"id_user": id_user,
						"token": tokens,
					},
					"dataSrc": ""
				},
				"columns": [

					{
						"data": "code",
						render: function(data, type, row) {
							return row.product.code;

						}
					},
					{
						"data": "description",
						render: function(data, type, row) {
							return row.product.description;

						}
					},
					{
						"data": "qty"
					},

				],
				"language": idioma_espanol,
				"dom": 'Bfrtip',
				"responsive": true,
				"buttons": [
					'copy', 'csv', 'excel', 'pdf', 'print'
				]
			});
			// ShowProdcuts("#table_products_view", data.products)
			cuadros('#cuadro1', '#cuadro3');
		});
	}
</script>
@endsection