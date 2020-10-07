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
			          <h1 class="h3 mb-2 text-gray-800">Galeria - Clinic</h1>

			          <div id="alertas"></div>
			          <input type="hidden" class="id_user">
			          <input type="hidden" class="token">

			          <!-- DataTales Example -->
			          <div class="card shadow mb-4" id="cuadro1">
			            <div class="card-header py-3">
			              <h6 class="m-0 font-weight-bold text-primary">Gestion de Galeria de Clinicas</h6>

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
								  <th>Clinica</th>
			                      <th>Image</th>
			                    </tr>
			                  </thead>
			                  <tbody>
			                    
			                  </tbody>
			                </table>
			              </div>
			            </div>
			          </div>


			          @include('configuracion.gallery.clinic.store')
					  @include('configuracion.gallery.clinic.edit')


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
				$("#nav_citys, #modulo_Configuracion").addClass("active");

				verifyPersmisos(id_user, tokens, "citys");
			});


			function update(){
				enviarFormularioPut("#form-update", 'api/gallery/clinic', '#cuadro4', false, "#avatar-edit");
			}


			function store(){
				enviarFormulario("#store", 'api/gallery/clinic', '#cuadro2');
			}



			function list(cuadro) {
				contarModulos("#posicion_modulo_vista_registrar");
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
						 "url":''+url+'/api/gallery/clinic',
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
								if(actualizar == 1)
									botones += "<span class='editar btn btn-sm btn-primary waves-effect' data-toggle='tooltip' title='Editar'><i class='fas fa-edit' style='margin-bottom:5px'></i></span> ";
							
								return botones;
							}
						},
						{"data":"name_clinic"},
						{"data":"image",
							render : function(data, type, row) {
							
								image = `<img src="${url}/img/gallery/clinic/${data}" width="100">`;
							
								return image;
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



			function nuevo() {
				$("#alertas").css("display", "none");
				$("#store")[0].reset();

				GetClinic("#clinic")


				$("#file-input-edit").fileinput('destroy');
				$("#file-input-edit").fileinput({
					theme: "fas",
					overwriteInitial: true,
					//maxFileSize: 1500,
					showClose: false,
					showCaption: false,
					browseLabel: '',
					removeLabel: '',
					browseIcon: '<i class="fa fa-folder-open"></i>',
					removeIcon: '<i class="fas fa-trash-alt"></i>',
					previewFileIcon: '<i class="fas fa-file"></i>',
					removeTitle: 'Cancel or reset changes',
					elErrorContainer: '#kv-avatar-errors-1',
					msgErrorClass: 'alert alert-block alert-danger',
					
					layoutTemplates: {main2: '{preview}  {remove} {browse}'},
					allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf", "docs"],
					
				});



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

					$("#sub_category_edit").val(data.id_sub_category)

					var url_imagen = 'img/gallery/clinic/'
					var url        = document.getElementById('ruta').value; 
					if((data.image != "" ) &&  (data.image != null)){
						var ext = data.image.split('.');
						if (ext[1] == "pdf") {
							img = '<embed class="kv-preview-data file-preview-pdf" src="'+url_imagen+data.image+'" type="application/pdf" style="width:213px;height:160px;" internalinstanceid="174">'
						}else{
							img = '<img src="'+url_imagen+data.image+'" class="file-preview-image kv-preview-data">'
						}
							
					}else{img = ""}



					$("#file-input-edit2").fileinput('destroy');
					$("#file-input-edit2").fileinput({
						theme: "fas",
						overwriteInitial: true,
						//maxFileSize: 1500,
						showClose: false,
						showCaption: false,
						browseLabel: '',
						removeLabel: '',
						browseIcon: '<i class="fa fa-folder-open"></i>',
						removeIcon: '<i class="fas fa-trash-alt"></i>',
						previewFileIcon: '<i class="fas fa-file"></i>',
						removeTitle: 'Cancel or reset changes',
						elErrorContainer: '#kv-avatar-errors-1',
						msgErrorClass: 'alert alert-block alert-danger',
						
						layoutTemplates: {main2: '{preview}  {remove} {browse}'},
						allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf", "docs", "mp4"],


						initialPreview: [ 
							img
						],

						initialPreviewConfig: [
							{caption: data.cotizacion , downloadUrl: url_imagen+data.cotizacion  ,url: url+"uploads/delete", key: data.cotizacion}
						],
						
					});
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
					statusConfirmacion('api/status-city/'+data.id_city+"/"+2,"¿Esta seguro de desactivar el registro?", 'desactivar');
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
					statusConfirmacion('api/status-city/'+data.id_city+"/"+1,"¿Esta seguro de desactivar el registro?", 'activar');
				});
			}
		/* ------------------------------------------------------------------------------- */



			function eliminar(tbody, table){
				$(tbody).on("click", "span.eliminar", function(){
					var data=table.row($(this).parents("tr")).data();
					statusConfirmacion('api/status-city/'+data.id_city+"/"+0,"¿Esta seguro de eliminar el registro?", 'Eliminar');
				});
			}




		/* ------------------------------------------------------------------------------- */
		  /*
		    Funcion que hace un count de los modulos registrados y el resultado se 
		    despliega en un select para la seleccion de la posicion del modulo.
		  */
		  function contarModulos(select){
		    $(select).find('option').remove().end().append('<option value="">Seleccione</option>');
		    $.ajax({
		          url: ''+document.getElementById('ruta').value+'/api/modulos',
		          type:'GET',
		          data: {
		          	"id_user": id_user,
					"token"  : tokens,
				  },
				  dataType:'JSON',
				  async: false,
		          error: function() {
		       //contarModulos();
		          },
		          success: function(respuesta){
		              var selectRegistrar = Object.keys(respuesta).length +1;
		              var selectActualizar = Object.keys(respuesta).length;
		              for(var i = 1; i <= selectRegistrar; i++){
		              	console.log(selectRegistrar);
		                agregarOptions(select, i, i);
		              }
		              
		          }
		      });
		  }



					


		</script>
		



	@endsection


