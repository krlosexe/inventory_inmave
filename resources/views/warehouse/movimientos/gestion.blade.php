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
										<th>Precio Compra (Euro)</th>
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
		$("#nav_output, #modulo_Almacen").addClass("active");

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
			"columns": [
				{
					"data": null,
					render : function(data, type, row) {
						var botones = "";
						if(consultar == 1)
							botones += "<span class='consultar btn btn-sm btn-info waves-effect' data-toggle='tooltip' title='Consultar'><i class='fa fa-eye' style='margin-bottom:5px'></i></span> ";
			
						return botones;
					}
				},

				// {
				// 	"data": "code"
				// },
				// {
				// 	"data": "description"
				//},
				{
					"data": "type"
				},
				{
					"data": "price"
				},
				// {
				// 	"data": "qty"
				// },
				// {
				// 	"data": "lote"
				// },
				{
					"data": "origin"
				},
				{
					"data": "destiny"
				},
				{
					"data": "email"
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

			console.log(url)
			window.open(url, '_blank');
		});


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
				"url": '' + url + '/api/products/movimiento/detail/'+data.id_product,
				"data": {
					"id_user": id_user,
					"token": tokens,
				},
				"dataSrc": ""
			},
			"columns": [

				{
					"data": "code",
					render : function(data, type, row) {
								return row.product.code;
						
							}
				},
				{
					"data": "description",
					render : function(data, type, row) {
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



	// function ShowProdcuts(table, data) {

	// 	console.log(data)

	// 	$(table + " tbody").html("")
	// 	$.map(data, function(item, key) {

	// 		let html = ""
	// 		html += "<tr>"
	// 		html += "<td>" + item.description + " <input type='hidden' class='id_product' name='id_product[]' value='" + item.id_product + "' >  </td>"
	// 		html += "<td>" + item.presentation + "</td>"


	// 		html += "<td>"
	// 		html += "<select class='form-control items_calc price_product' id='price_edit_" + item.id + "' name='price[]' onchange='calcProduc(this, " + '"_edit"' + ")' required>"
	// 		html += "<option value=''>Seleccione el precio</option>"
	// 		html += "<option value='" + item.price_distributor_x_caja + "'>Precio Distribuidor x Caja - " + number_format(item.price_distributor_x_caja, 2) + "</option>"
	// 		html += "<option value='" + item.price_distributor_x_vial + "'>Precio Distribuidor x Vial - " + number_format(item.price_distributor_x_vial, 2) + "</option>"
	// 		html += "<option value='" + item.price_cliente_x_caja + "'>Precio Cliente Final x Caja - " + number_format(item.price_cliente_x_caja, 2) + "</option>"
	// 		html += "<option value='" + item.price_cliente_x_vial + "'>Precio Cliente Final x Vial  - " + number_format(item.price_cliente_x_vial, 2) + "</option>"
	// 		html += "</select>"

	// 		html += "</td>"



	// 		//html +="<td><input style='text-align: right;width: 142px;' type='text' class='form-control monto_formato_decimales price_product items_calc' value='"+number_format(item.price, 2)+"'  onkeyup='calcProduc(this, "+'"_edit"'+")' name='price[]' required></td>"


	// 		html += "<td><input type='number' class='form-control qty_product items_calc' name='qty[]' value='" + item.qty + "' onchange='calcProduc(this, " + '"_edit"' + ")' required><input type='hidden' class='form-control qty_product_hidden items_calc' value='" + item.qty + "' disabled></td>"

	// 		html += "<td><input type='number' class='form-control  items_calc existence' name='existence'  value='" + item.existence + "' disabled><input type='hidden' disabled class='form-control items_calc existence_hidden' value='" + item.existence + "' disabled></td>"


	// 		if (item.vat == 1) {
	// 			html += "<td><input type='checkbox' class='form-control vat_product items_calc'checked onchange='calcProduc(this, " + '"_edit"' + ")'><input type='hidden' class='vat_hidden' name='vat[]' value='" + item.vat + "'></td>"
	// 		} else {
	// 			html += "<td><input type='checkbox' class='form-control vat_product items_calc' onchange='calcProduc(this, " + '"_edit"' + ")'><input type='hidden' class='vat_hidden' name='vat[]' value='" + item.vat + "'></td>"
	// 		}

	// 		html += "<td><input style='text-align: right;width: 142px;' type='text' class='form-control monto_formato_decimales total_product' value='" + number_format(item.total, 2) + "'  name='total[]' readonly required></td>"
	// 		html += "<td><span onclick='deleteProduct(this, " + '"_edit"' + ")' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
	// 		html += "</tr>"


	// 		$(table + " tbody").append(html)
	// 		$("#price_edit_" + item.id).val(item.price)

	// 	});


	// }

</script>




@endsection