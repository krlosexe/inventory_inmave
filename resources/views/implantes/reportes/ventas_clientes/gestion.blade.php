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
				<h1 class="h3 mb-2 text-gray-800">Ventas por Clientes </h1>
				<div id="alertas"></div>
				<input type="hidden" class="id_user">
				<input type="hidden" class="token">
				<!-- DataTales Example -->
				<div class="card shadow mb-4" id="cuadro1">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-primary">Reporte de Ventas por Clientes</h6>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered" id="table" width="100%" cellspacing="0">
								<thead>
									<tr>
										<!-- <th>Acciones</th> -->
										<th>#</th>
										<th>Referencia</th>
										<th>Serial</th>
										<th>Tipo</th>
										<th>Cliente</th>
										<th>Identificación</th>
										<th>Bodega</th>
										<th>Valor de Factura</th>
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
				@include('implantes.reportes.ventas_clientes.store')
				@include('implantes.reportes.ventas_clientes.view')
				@include('implantes.reportes.ventas_clientes.edit')
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
<script src="https://cdn.socket.io/socket.io-2.3.0.js"></script>
<script>
	$(document).ready(function() {
		store();
		list();
		update();
		$("#collapse_Reportes-Implantes").addClass("show");
		$("#nav_ventas-clientes, #modulo_Implantes").addClass("active");
		verifyPersmisos(id_user, tokens, "ventas_implantes");
		var url = $(location).attr('href').split("/").splice(-1);
		if (url[0] == "ventas_implantes") {
			$("#add_remision_invoice_implant").css("display", "none");
		}
	});
	function update() {
		enviarFormularioPut("#form-update", 'api/output/implantes/update', '#cuadro4', false, "#avatar-edit");
	}
	function store() {
		enviarFormulario("#store", 'api/output/implantes/create', '#cuadro2');
	}
	let contador = 0
	function serial(data){
		$.ajax({
				url: '' + document.getElementById('ruta').value + '/api/get/implante/' + data,
				type: 'GET',
				dataType: 'JSON',
				async: false,
				error: function(data) {
					alert(data.responseJSON.mensaje)
				},
				success: function(data) {

					$('#warehouse').val(data.head.warehouse)
					var html = "";
					var validaProduct = false
					$("#table_products_out tbody tr").each(function() {
						if (data.serial == $(this).find(".serial").val()) {
							validaProduct = true;
						}
					});
					contador++
					if (!validaProduct) {
						html += "<tr>"
						html += "<td>" + data.referencia + " <input type='hidden' class='id_product' name='referencia[]' value='" + data.referencia + "' ><input type='hidden' class='id_product' name='id_product[]' value='" +  data.id + "' > </td>"
						html += "<td>" + data.serial + " <input type='hidden'  class='serial' name='serial[]' value='" + data.serial + "' > </td>"
						html += "<td>" + 1 + " <input type='hidden' class='id_product'  value='1' > </td>"
						html += "<td><input type='text' class='price form-control items_calc price_product' name='price[]' value='" + number_format(data.products.precio, 2) + "' onchange='calcProduc(this)'  required></td>"
						html += "<td><input type='number' class='form-control items_calc qty_product' name='qty[]' value='1' min = '1'  max='2' readonly></td>"
						html += "<td><span onclick='deleteProduct(this, " + '""' + ")' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
						html += "</tr>"
					} else {
						warning('¡Recuerde que los campos son obligatorios!');
					}
					$("#items").val(contador);
					$("#table_products_out tbody").append(html)
					$('#serial').val("");
					setTimeout(() => {
						$('#serial').focus();
						calcProduc();
					}, 1000);
				}
			});
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
				"url": '' + url + '/api/get/clientes/implantes/list/' + name_rol,
				"dataSrc": ""
			},
			"columns": [
				{
					"data": "id"
				},
				{
					"data": "referencia"
				},{
					"data": "serial"
				},
				{
					"data": "reissue",
					render: function(data, type, row) {
						if (data == 1) {
							return "Reemisión";
						} else {
							return "Factura";
						}
					}
				},
				{
					"data": "name"
				},
				{
					"data": "nit_c"
				},
				{
					"data": "warehouse"
				},
				{
					"data": "total_invoice",
					render: function(data, type, row) {
						var botones = "";
						return number_format(data, 2);
					}
				},
				{
					"data": "fec_regins"
				},
				{
					"data": "email_regis"
				}
			],
			"language": idioma_espanol,
			"dom": 'Bfrtip',
			"responsive": true,
			"buttons": [
				'copy', 'csv', 'excel', 'pdf', 'print'
			]
		});
		ver("#table tbody", table)
		edit("#table tbody", table)
		activar("#table tbody", table)
		desactivar("#table tbody", table)
		eliminar("#table tbody", table)
	}
	function nuevo() {
		$("#alertas").css("display", "none");
		$("#store")[0].reset();
		getClients("#clients")
		$("#indicador_edit").val(0)
		cuadros("#cuadro1", "#cuadro2");
		$('#table_products_out tbody').empty();
		$('#table_products_edit_out tbody').empty();
		$('#subtotal_text').empty(0)
		$('#vat_total_text').empty(0)
		$('#discount_total_text').empty(0)
		$('#rte_fuente_text').empty(0)
		$('#total_invoice_text').empty(0)

		$("#table_products_out tbody").html("")
		$("#serial").focus();
		$("#serial").change(function() {
			$("#serial").val($("#serial").val().substr(2))
			serial($("#serial").val());
		});
	}
	/* ------------------------------------------------------------------------------- */
	/*
		Funcion que muestra el cuadro3 para la consulta del banco.
	*/
	function ver(tbody, table) {
		$(tbody).on("click", "span.consultar", function() {
			$("#alertas").css("display", "none");
			var data = table.row($(this).parents("tr")).data();
			$("#warehouse_view").val(data.warehouse).trigger("change").attr("disabled", "disabled")
			getClients("#clients_view", data.id_client)
			if (data.discount_total > 0) {
				$("#apply_discount_view").prop("checked", true)
			} else {
				$("#apply_discount_view").prop("checked", false)
			}
			$("#subtotal_text_view").text(`$ ${number_format(data.subtotal, 2)}`)
			$("#subtotal_view").val(data.subtotal)
			$("#subtotal_with_discount_view").val(data.subtotal_with_discount)
			$("#vat_total_text_view").text(`$ ${number_format(data.vat_total, 2)}`)
			$("#vat_total_view").val(data.vat_total)
			$("#discount_total_view").val(data.discount_total)
			$("#discount_total_text_view").text(number_format(data.discount_total, 2))
			$("#total_invoice_text_view").text(`$ ${number_format(data.total_invoice, 2)}`)
			$("#total_invoice_view").val(data.total_invoice)
			$("#observations_view").val(data.observations)
			ShowProducts("#table_products_view", data.products)
			cuadros('#cuadro1', '#cuadro3');
		});
	}
	/* ------------------------------------------------------------------------------- */
	/*
		Funcion que muestra el cuadro3 para la consulta del banco.
	*/
	function edit(tbody, table) {
		$(tbody).on("click", "span.editar", function() {
			$("#alertas").css("display", "none");
			var data = table.row($(this).parents("tr")).data();
			$("#table_products_out tbody").html("")
			$("#indicador_edit").val(1)

			$("#items_id").val(data.items.length)
			$("#name_edit").val(data.name)
			$("#nit_edit").val(data.nit)
			$("#phone_edit").val(data.phone)
			$("#email_edit").val(data.email)
			$("#city_edit").val(data.city)
			$("#address_edit").val(data.address)

			$("#table_products_out").focus();
			$("#serial_edit").focus();
			$("#serial_edit").change(function() {
				$("#serial_edit").val($("#serial_edit").val().substr(2))
				AddProductosEdit($("#serial_edit").val());
			});
			getClients("#clients_edit", data.id_client)
			$("#warehouse_edit").val(data.warehouse).trigger("change")
			ShowProducts("#table_products_edit_out", data)
			if (data.discount_type === 5) {
				$("#apply_discount3_edit").prop("checked", true)
			}
			if (data.discount_type === 15) {
				$("#apply_discount2_edit").prop("checked", true)
			}
			if (data.discount_type === 10) {
				$("#apply_discount_edit").prop("checked", true)
			}

			$("#subtotal_text_edit").text(`$ ${number_format(data.discount_total + data.subtotal, 2)}`)
			$("#subtotal_edit").val(data.discount_total + data.subtotal)
			$("#subtotal_with_discount_edit").val(data.subtotal_with_discount)
			$("#vat_total_text_edit").text(`$ ${number_format(0, 2)}`)
			$("#vat_total_edit").val(data.vat_total)
			$("#discount_total_edit").val(data.discount_total)
			$("#discount_total_text_edit").text(number_format(data.discount_total, 2))
			$("#total_invoice_text_edit").text(`$ ${number_format(data.total_invoice, 2)}`)
			$("#total_invoice_edit").val(data.total_invoice)
			$("#observations_edit").val(data.observations)
			$("#id_edit").val(data.id)
			cuadros('#cuadro1', '#cuadro4');
		});
	}
	$("#print").click(function(e) {
		window.open(`api/invoice/implante/print/${$("#id_edit").val()}`, "_blank");
	});
	/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a desactivar
	*/
	function desactivar(tbody, table) {
		$(tbody).on("click", "span.desactivar", function() {
			var data = table.row($(this).parents("tr")).data();
			statusConfirmacion('api/products/entry/output/status/' + data.id + "/" + 2, "¿Esta seguro de desactivar el registro?", 'desactivar');
		});
	}
	/* ------------------------------------------------------------------------------- */
	/* ------------------------------------------------------------------------------- */
	/*
		Funcion que capta y envia los datos a desactivar
	*/
	function activar(tbody, table) {
		$(tbody).on("click", "span.activar", function() {
			var data = table.row($(this).parents("tr")).data();
			statusConfirmacion('api/products/entry/output/status/' + data.id + "/" + 1, "¿Esta seguro de desactivar el registro?", 'activar');
		});
	}
	/* ------------------------------------------------------------------------------- */
	function eliminar(tbody, table) {
		$(tbody).on("click", "span.eliminar", function() {
			var data = table.row($(this).parents("tr")).data();
			statusConfirmacion('api/products/entry/output/status/' + data.id + "/" + 0, "¿Esta seguro de eliminar el registro?", 'Eliminar');
		});
	}
	function getProducts(select, select_default = false) {
		$.ajax({
			url: '' + document.getElementById('ruta').value + '/api/products',
			type: 'GET',
			data: {
				"id_user": id_user,
				"token": tokens,
			},
			dataType: 'JSON',
			async: false,
			error: function() {},
			success: function(data) {
				$(select + " option").remove();
				$(select).append($('<option>', {
					value: "",
					text: "- Seleccione"
				}));
				$.each(data, function(i, item) {
					$(select).append($('<option>', {
						value: item.id,
						text: item.description,
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
	function AddProductosEdit(data) {
		socket_referencia_edit(data);
	}
	function socket_referencia_edit(data) {
		$.ajax({
				url: '' + document.getElementById('ruta').value + '/api/get/implante/' + data,
				type: 'GET',
				dataType: 'JSON',
				async: false,
				error: function(data) {
					alert(data.responseJSON.mensaje)
				},
				success: function(data) {
					var html = "";
					var validaProduct = false
					$("#table_products_edit_out tbody tr").each(function() {
						if (data.serial == $(this).find(".serial").val()) {
							validaProduct = true;
						}
					});
					if (!validaProduct) {
						html += "<tr>"
						html += "<td>" + data.referencia + " <input type='hidden' class='id_product' name='referencia[]' value='" + data.referencia + "' > </td>"
						html += "<td>" + data.serial + " <input type='hidden'  class='serial' name='serial[]' value='" + data.serial + "' > </td>"
						html += "<td><input type='text' class='form-control items_calc qty_product' name='salida[]' value='1'readonly></td>"
						html += "<td><input type='number' class='form-control items_calc qty_product' name='qty[]' value='1' min = '1'  max='1' readonly></td>"
						html += "<td><input type='text' class='form-control items_calc price_product' name='price[]' value='" + number_format(0, 2) + "' onchange='calcProduc(this," + '"_edit"' + ")' required></td>"
						html += "<td><span onclick='deleteProduct(this, " + '""' + ")' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
						html += "</tr>"
					} else {
						warning('¡La opción seleccionada ya se encuentra agregada!');
					}
					$("#table_products_edit_out tbody").append(html)
					$('#serial_edit').val("");
					setTimeout(() => {
						$('#serial_edit').focus();
					}, 1000);
				}
			});
	}
	function ShowProducts(table, data) {
		$(table + " tbody").html("")
		$.map(data.items, function(item, key) {
			let html = ""
			html += "<tr>"
			html += "<td>" + item.referencia + " <input type='hidden' class='id_product' name='referencia[]' value='" + item.referencia + "' ><input type='hidden' class='id_product' name='id_product[]' value='" +  data.id + "' ><input type='hidden' class='id_product' name='price[]' value='" + item.price + "' ></td>"
			html += "<td>" + item.serial + " <input type='hidden' class='id_product' name='serial[]' value='" + item.serial + "' > </td>"
			html += "<td><input type='number' class='form-control qty_product items_calc' name='qty[]' value='" + item.qty + "' max='" + item.qty + "' readonly><input type='hidden' class='form-control qty_product_hidden items_calc' value='" + item.qty + "' disabled></td>"
			html += "<td><input type='number' class='form-control  items_calc existence' name='existence'  value='" + item.qty + "' disabled><input type='hidden' disabled class='form-control items_calc existence_hidden' value='" + item.qty + "' disabled></td>"
			html += "<td><input style='text-align: right;width: 142px;' type='text' class='price_product form-control monto_formato_decimales total_product' value='" + number_format(item.price, 2) + "'onchange='calcProduc(this, " + '"_edit"' + ")'  name='total[]' required></td>"
			html += "<td><span onclick='deleteProduct(this, " + '"_edit"' + ")' class='eliminar btn btn-sm btn-danger waves-effect' data-toggle='tooltip' title='Eliminar'><i class='fas fa-trash-alt' style='margin-bottom:5px'></i></span></td>"
			html += "</tr>"
			$(table + " tbody").append(html)
			$("#price_edit_" + item.id).val(item.price)

		});
	}
	function deleteProduct(element, edit = '') {
		var tr = $(element).parent("td").parent("tr").remove()
		calcSubTotal(".price_product", edit)
		calcTotalVat(".vat_product", edit)
		calTotal(".total_product", edit)
	}
	function getClients(select, select_default = false) {
		$.ajax({
			url: '' + document.getElementById('ruta').value + '/api/implantes-clientes',
			type: 'GET',
			data: {
				"id_user": id_user,
				"token": tokens,
			},
			dataType: 'JSON',
			async: false,
			error: function() {},
			success: function(data) {
				$(select + " option").remove();
				$(select).append($('<option>', {
					value: "",
					text: "- Seleccione"
				}));
				$.each(data, function(i, item) {
					if (data.status == 1) {}
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
	function calcProduc(element, edit = '') {
		var price = inNum($(element).parent("td").parent("tr").children("td").find(".price_product").val())
		var qty = inNum($(element).parent("td").parent("tr").children("td").find(".qty_product").val())
		var vat = $(element).parent("td").parent("tr").children("td").find(".vat_product")
		var existence = $(element).parent("td").parent("tr").children("td").find(".existence")
		var existence_hidden = $(element).parent("td").parent("tr").children("td").find(".existence_hidden")
		if (edit != '') {
			var qty_hidden = inNum($(element).parent("td").parent("tr").children("td").find(".price_product").val())
			existence.val(1)
		} else {
			existence.val(1)
		}
		let total
		if (vat.is(':checked')) {
			total = price
			$(element).parent("td").parent("tr").children("td").find(".vat_hidden").val(1)
		} else {
			total = price
			$(element).parent("td").parent("tr").children("td").find(".vat_hidden").val(0)
		}
		$(element).parent("td").parent("tr").children("td").find(".price_product").val(number_format(total, 2))
		calcSubTotal(".price_product", edit)
		calTotal(".price_product", edit)
	}
	function calcSubTotal(fields, edit = '') {
		let subtotal = 0
		$.map($(fields), function(item, key) {
			const total = inNum($(item).val())
			subtotal =  parseFloat(subtotal) + parseFloat(total)
		});
		var discount_field = $(`#apply_discount${edit}`)
		let discount_ammount
		if (discount_field.is(':checked')) {
			discount_ammount = subtotal * 0.10
		} else {
			discount_ammount = 0
		}
		var discount_field2 = $(`#apply_discount2${edit}`)
		let discount_ammount2
		if (discount_field2.is(':checked')) {
			discount_ammount2 = subtotal * 0.15
		} else {
			discount_ammount2 = 0
		}
		var discount_field3 = $(`#apply_discount3${edit}`)
		let discount_ammount3
		if (discount_field3.is(':checked')) {
			discount_ammount3 = subtotal / 100 * 5
		} else {
			discount_ammount3 = 0
		}
		$(`#discount_total${edit}`).val((parseFloat(discount_ammount) + parseFloat(discount_ammount2) + parseFloat(discount_ammount3) ))
		$(`#discount_total_text${edit}`).text(`$ ${number_format((parseFloat(discount_ammount)  + parseFloat(discount_ammount2) + parseFloat(discount_ammount3)), 2)}`)
		$(`#subtotal_text${edit}`).text(`$ ${number_format(subtotal, 2)}`)
		$(`#subtotal${edit}`).val(subtotal)
		let sub_total_with_discount = subtotal - (parseFloat(discount_ammount) + parseFloat(discount_ammount2) + parseFloat(discount_ammount3))
		$(`#subtotal_with_discount${edit}`).val(sub_total_with_discount)
	}
	function calcTotalVat(fields, edit = '') {
		let totalVat = 0
		$.map($(fields), function(item, key) {
			if ($(item).is(':checked')) {
				const price = inNum($(item).parent("td").parent("tr").children("td").find(".price_product").val())
				const qty = $(item).parent("td").parent("tr").children("td").find(".qty_product").val()
				const vat = price
				totalVat = totalVat + vat
			}
		});
		const totalVat2 = (($(`#subtotal_with_discount${edit}`).val()))
		$(`#vat_total_text${edit}`).text(`$ ${number_format(totalVat2, 2)}`)
		$(`#vat_total${edit}`).val(totalVat2)
	}
	function calTotal(fields, edit = '') {
		let total_invoice = 0
		$.map($(fields), function(item, key) {
			if ($(item).val() != "") {
				total_invoice = parseFloat(total_invoice) + parseFloat(inNum($(item).val()))
			}
		});
		const discount = inNum($(`#discount_total${edit}`).val())
		const percentage_rte_fuete = inNum($(`#rte_fuente${edit}`).val())
		const rte_fuete = ($(`#subtotal_with_discount${edit}`).val() / 100) * percentage_rte_fuete
		total_invoice = (($(`#subtotal_with_discount${edit}`).val()))
		total_invoice = total_invoice - rte_fuete
		$(`#rte_fuente_text${edit}`).text(`$ ${number_format(rte_fuete, 2)}`)
		$(`#rte_fuente_total${edit}`).val(rte_fuete)
		$(`#total_invoice_text${edit}`).text(`$ ${number_format(total_invoice, 2)}`)
		$(`#total_invoice${edit}`).val(total_invoice)
	}
	$("#apply_discount").change(function(e) {
		if ($("#apply_discount").is(':checked')) {
			$("#type_discount").val(10)
		}else{
			$("#type_discount").val(0)
		}
		calcSubTotal(".price_product")
		calTotal(".price_product")
	});
	$("#apply_discount2").change(function(e) {
		if ($("#apply_discount2").is(':checked')) {
			$("#type_discount").val(15)
		}else{
			$("#type_discount").val(0)
		}
		calcSubTotal(".price_product")
		calTotal(".price_product")
	});
	$("#apply_discount3").change(function(e) {
		if ($("#apply_discount3").is(':checked')) {
			$("#type_discount").val(15)
		}else{
			$("#type_discount").val(0)
		}
		calcSubTotal(".price_product")
		calTotal(".price_product")
	});
	$("#apply_discount_edit").change(function(e) {
		if ($("#apply_discount_edit").is(':checked')) {
			$("#type_discount_edit").val(10)
		}else{
			$("#type_discount_edit").val(0)
		}
		calcSubTotal(".price_product", '_edit')
		calTotal(".total_product", '_edit')
	});
	$("#apply_discount2_edit").change(function(e) {
		if ($("#apply_discount2_edit").is(':checked')) {
			$("#type_discount_edit").val(15)
		}else{
			$("#type_discount_edit").val(0)
		}
		calcSubTotal(".price_product", '_edit')
		calTotal(".total_product", '_edit')
	});
	$("#apply_discount3_edit").change(function(e) {
		if ($("#apply_discount3_edit").is(':checked')) {
			$("#type_discount_edit").val(5)
		}else{
			$("#type_discount_edit").val(0)
		}
		calcSubTotal(".price_product", '_edit')
		calTotal(".total_product", '_edit')
	});
	$(".discount").keyup(function(e) {
		calcSubTotal(".price_product")
		calTotal(".price_product")
	});
	$(".discount_edit").keyup(function(e) {
		calcSubTotal(".price_product", '_edit')
		calTotal(".total_product", '_edit')
	});
</script>
@endsection
