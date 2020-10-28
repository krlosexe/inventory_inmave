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



			#slide{
				position: absolute;
				top: 0;
				right: 0;
				bottom: 0;
				left: 0;
				z-index: 3000;
				display: none;
				
				/* background-color: rgba(0,0,0,.4); */
				transform: translateZ(0);
				-webkit-transform: translateZ(0);
				-moz-transform: translateZ(0);
				-ms-transform: translateZ(0);
				-o-transform: translateZ(0);
				overflow: hidden;
				 -webkit-transition: 3s;
					-moz-transition: 3s;
					-ms-transition: 3s;
					-o-transition: 3s;
					transition: 3s;
			}

			#slide.show{
				display: block;
    			pointer-events: auto;
				z-index: 3000;
				left: 0px;
				top: 0px;
				right: 0px;
				height: 912px;



				overflow-x: auto;
				overflow-y: auto;
				position: fixed;
				top: 0;
				left: 0;
				z-index: 1050;
				/* display: none; */
				width: 100%;
				height: 100%;
				overflow: hidden;
				outline: 0;
				overflow: scroll;




				background-color: rgba(0, 0, 0, 0.4);
				-webkit-transition: 3s;
				-moz-transition: 3s;
				-ms-transition: 3s;
				-o-transition: 3s;
				transition: 3s;
				
			}


			.side-panel-container {
				position: absolute;
				top: 0;
				right: 0;
				bottom: 0;
				z-index: 3001;
				display: block;
				width: calc(100% - 300px);
				background: #fff;
				
				transform: translateX(100%);
				 -webkit-transition: 3s;
					-moz-transition: 3s;
					-ms-transition: 3s;
					-o-transition: 3s;
					transition: 3s;

			}

			.slide-show{
				z-index: 3001;
				width: 90%;
				-webkit-transform: translateX(0%);
				-moz-transform: translateX(0%);
				-ms-transform: translateX(0%);
				-o-transform: translateX(0%);
				transform: translateX(0%);
			
			}


			.side-panel-label {
				display: flex;
				position: absolute;
				left: 0;
				top: 21px;
				min-width: 30px;
				height: 38px;
				padding-right: 5px;
				background: rgba(47,198,246,.95);
				border-top-left-radius: 19px;
				border-bottom-left-radius: 19px;
				white-space: nowrap;
				overflow: hidden;
				transition: top .3s;
				box-shadow: inset -6px 0 8px -10px rgba(0,0,0,0.95);
				z-index: 1;
				transform: translateX(-100%);
				cursor: pointer;
			}

			.side-panel-close-btn-inner:before {
				-webkit-transform: translateX(-50%) translateY(-50%) rotate(-45deg);
				-moz-transform: translateX(-50%) translateY(-50%) rotate(-45deg);
				-ms-transform: translateX(-50%) translateY(-50%) rotate(-45deg);
				-o-transform: translateX(-50%) translateY(-50%) rotate(-45deg);
				transform: translateX(-50%) translateY(-50%) rotate(-45deg);
			}


			.side-panel-close-btn-inner:after, .side-panel-close-btn-inner:before {
				position: absolute;
				top: 50%;
				left: 50%;
				width: 14px;
				height: 2px;
				background-color: #fff;
				content: "";
			}


			.side-panel-close-btn-inner:after {
				-webkit-transform: translateX(-50%) translateY(-50%) rotate(45deg);
				-moz-transform: translateX(-50%) translateY(-50%) rotate(45deg);
				-ms-transform: translateX(-50%) translateY(-50%) rotate(45deg);
				-o-transform: translateX(-50%) translateY(-50%) rotate(45deg);
				transform: translateX(-50%) translateY(-50%) rotate(45deg);
			}


			.side-panel-close-btn-inner:after, .side-panel-close-btn-inner:before {
				position: absolute;
				top: 50%;
				left: 50%;
				width: 14px;
				height: 2px;
				background-color: #fff;
				content: "";
			}



		</style>


	<link href="<?= url('/') ?>/vendor/summernote-master/dist/summernote.min.css" rel="stylesheet">
    <script src="<?= url('/') ?>/vendor/summernote-master/dist/summernote.min.js"></script>



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
			          <h1 class="h3 mb-2 text-gray-800">Productos</h1>

			          <div id="alertas"></div>
			          <input type="hidden" class="id_user">
			          <input type="hidden" class="token">

			          <!-- DataTales Example -->
			          <div class="card shadow mb-4" id="cuadro1">
			            <div class="card-header py-3">
			              <h6 class="m-0 font-weight-bold text-primary">Ranking de productos</h6>

			              <!-- <button onclick="nuevo()" class="btn btn-primary btn-icon-split" style="float: right;">
		                    <span class="icon text-white-50">
		                      <i class="fas fa-plus"></i>
		                    </span>
		                     <span class="text">Nuevo registro</span> 
		                  </button> -->
			            </div>
			            <div class="card-body">
							<div class="row">
							</div>
			              <div class="table-responsive">
			                <table class="table table-bordered" id="table" width="100%" cellspacing="0">
			                  <thead>
			                    <tr>
								  <!-- <th>Acciones</th> -->
								  <th>Descripción</th>
								  <th>Cantidad</th>
			                    </tr>
			                  </thead>
			                  <tbody></tbody>
			                </table>
			              </div>
			            </div>
			          </div>


			          @include('tasks.store')
					  @include('tasks.view')
					  @include('tasks.edit')


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

				$("#collapse_Tareas").addClass("show");
				$("#nav_tasks, #modulo_Tareas").addClass("active");

				verifyPersmisos(id_user, tokens, "tasks");
			});


			$("#date_init, #date_finish").change(function (e) { 
				list();
			});




			function update(){
				enviarFormularioPut("#form-update", 'api/tasks', '#cuadro4', false, "#avatar-edit");
			}

			function store(){
				enviarFormulario("#store", 'api/tasks', '#cuadro2');
			}

			$("#id_asesora_valoracion-filter, #overdue-filter").change(function (e) { 
				list();
			});


			function list(cuadro) {
				var data = {
					"id_user": id_user,
					"token"  : tokens,
				};

				var adviser = $("#id_asesora_valoracion-filter").val()
				var overdue = $("#overdue-filter").val()

				const date_init   = $("#date_init").val()
				const date_finish = $("#date_finish").val()

				$("#div-input-edit").css("display", "none")
				$('#table tbody').off('click');
				var url=document.getElementById('ruta').value; 
				cuadros(cuadro, "#cuadro1");

				var table=$("#table").DataTable({
					"destroy":true,
					"stateSave": true,
					"serverSide":false,
					"ajax":{
						"method":"GET",
						 "url":''+url+'/api/rakin-producto',
						
						"dataSrc":""
					},
					"columns":[		
						{"data":"description", 
							render : function(data, type, row) {
								return row.description;
						
							}
						},
						{"data": "quantities",
						},
					],
					"language": idioma_espanol,
					"dom": 'Bfrtip',
					"responsive": true,
					"buttons":[
						'copy', 'csv', 'excel', 'pdf', 'print'
					]
				});

				table
				.search("").draw()
				ver("#table tbody", table)
				edit("#table tbody", table)
				activar("#table tbody", table)
				desactivar("#table tbody", table)
				eliminar("#table tbody", table)


			}
			function nuevo() {
				$("#alertas").css("display", "none");
				$("#store")[0].reset();

				GetUsers("#responsable-store")
			//	GetUsers("#followers-store")


				$("#paciente-store option").remove();

				
				//getPacientes("#paciente-store")

				cuadros("#cuadro1", "#cuadro2");
			}
			function GetComments(comment_content, id_client){
				$(comment_content).html("Cargando...")
				var url=document.getElementById('ruta').value;	
				$.ajax({
					url:''+url+'/api/tasks/comments/'+id_client,
					type:'GET',
					dataType:'JSON',
					
					beforeSend: function(){

					},
					error: function (data) {
					},
					success: function(result){
						
						var url=document.getElementById('ruta').value; 
						var html = "";

						$.map(result, function (item, key) {
							html += '<div class="col-md-12" style="margin-bottom: 15px">'
								html += '<div class="row">'
									html += '<div class="col-md-2">'
										html += "<img class='rounded' src='"+url+"/img/usuarios/profile/"+item.img_profile+"' style='height: 4rem;width: 4rem; margin: 1%; border-radius: 50%!important;' title='"+item.name_follower+" "+item.last_name_follower+"'>"
										
									html += '</div>'
									html += '<div class="col-md-10" style="background: #eee;padding: 2%;border-radius: 17px;">'
										html += '<div>'+item.comments+'</div>'

										html += '<div><b>'+item.name_user+" "+item.last_name_user+'</b> <span style="float: right">'+item.create_at+'</span></div>'


									html += '</div>'
								html += '</div>'
							html += '</div>'
							
						});

						$(comment_content).html(html)
					}
				});
			}

			/* ------------------------------------------------------------------------------- */
			/* 
				Funcion que muestra el cuadro3 para la consulta del banco.
			*/
			function ver(tbody, table){
				$(tbody).on("click", "span.consultar", function(){
					$("#alertas").css("display", "none");
					var data = table.row( $(this).parents("tr") ).data();

					GetUsers("#responsable-view", data.responsable)
					GetUsers("#followers-view")
					

					//getPacientes("#paciente-view", data.id_client)

					$("#name_client-view").val(data.name_client).attr("disabled", "disabled")
					
					$("#responsable-view").val(data.responsable).attr("disabled", "disabled")
					$("#issue-view").val(data.issue).attr("disabled", "disabled")
					$("#paciente-view").val(data.id_client).attr("disabled", "disabled")
					$("#fecha-view").val(data.fecha).attr("disabled", "disabled")
					$("#time-view").val(data.time).attr("disabled", "disabled")
					$("#observaciones-view").val(data.observaciones).attr("disabled", "disabled")
					$("#status_task-view").val(data.status_task).attr("disabled", "disabled")

					var followers = []
					$.each(data.followers, function (key, item) { 
						followers.push(item.id_follower)
					});

					$("#followers-view").val(followers).attr("disabled", "disabled")
					$("#followers-view").trigger("change");

			//	GetComments("#comments", data.id_client)

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

					GetUsers("#responsable-edit", data.responsable)
		
					$("#name_client-edit").val(data.name_client).attr("disabled", "disabled")

					$("#responsable-edit").val(data.responsable)
					$("#paciente-edit").val(data.id_client)
					$("#issue-edit").val(data.issue)
					$("#fecha-edit").val(data.fecha)
					$("#time-edit").val(data.time)
					$("#observaciones-edit").val(data.observaciones)
					$("#status_task-edit").val(data.status_task)

					$("#id_edit").val(data.id_tasks)
					cuadros('#cuadro1', '#cuadro4');
				});
			}

			function SubmitComment(id, api, table, btn, summer){

				$(btn).unbind().click(function (e) { 

					var html = ""

					html += '<div class="col-md-12" style="margin-bottom: 15px">'
						html += '<div class="row">'
							html += '<div class="col-md-2">'
							html += '</div>'
							html += '<div class="col-md-10" style="background: #eee;padding: 2%;border-radius: 17px;">'
								html += '<div>'+$(summer).val()+'</div>'

								html += '<div><b></b> <span style="float: right">Ahora Mismo</span></div>'

							html += '</div>'
						html += '</div>'
					html += '</div>'

					$(table).append(html)
					var url=document.getElementById('ruta').value;

					$.ajax({
						url:''+url+"/"+api,
						type:'POST',
						data: {
							"id_user" : id_user,
							"token"   : tokens,
							"id"      : id,
							"comment" : $(summer).val(),
							
						},
						dataType:'JSON',
						beforeSend: function(){
							$(btn).text("espere...").attr("disabled", "disabled")
						},
						error: function (data) {
							$(btn).text("Comentar").removeAttr("disabled")
						},
						success: function(data){
							$(btn).text("Comentar").removeAttr("disabled")
							$(summer).summernote("reset");
						}
					});
		
				});

			}
		
		/* ------------------------------------------------------------------------------- */
			/*
				Funcion que capta y envia los datos a desactivar
			*/
			function desactivar(tbody, table){
				$(tbody).on("click", "span.desactivar", function(){
					var data=table.row($(this).parents("tr")).data();
					statusConfirmacion('api/tasks/status/'+data.id_tasks+"/"+2,"¿Esta seguro de desactivar el registro?", 'desactivar');
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
					statusConfirmacion('api/tasks/status/'+data.id_tasks+"/"+1,"¿Esta seguro de desactivar el registro?", 'activar');
				});
			}
		/* ------------------------------------------------------------------------------- */

			function eliminar(tbody, table){
				$(tbody).on("click", "span.eliminar", function(){
					var data=table.row($(this).parents("tr")).data();
					statusConfirmacion('api/tasks/status/'+data.id_tasks+"/"+0,"¿Esta seguro de eliminar el registro?", 'Eliminar');
				});
			}

		</script>
		
	@endsection


