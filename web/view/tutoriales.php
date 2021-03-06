<?php
session_start();
require_once("../lib/common.php");
validarSesion();
?>

<!DOCTYPE html>
<head>
<?php 
head_lenguajes(); 
?>
</head>

<body>
<?php 
sidebar();
opcion_soluciones();
navbar_lenguaje();
tutoriales();
?>

<div id="modal_tutoriales_popup" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo-modal-tutorial">Tutorial</span></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!--form-->
			<div id="div_form_login">
				<!--login form-->
				<form id="tutorial_form"  name="tutorial_form" class="form-horizontal" role="form" action="" method="POST">
					<div class="modal-body">
						<div id="login_msg">
							<div id="info_login_msg" class="glyphicon glyphicon-chevron-right"></div>
							<div class="input-group" id="user_input">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<input id="nombre_tutorial" type="text" class="form-control" name="nombre_tutorial"  placeholder="nombre" required>
							</div>
							<p></p>
							<div class="input-group" id="user_input">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<textarea id="instrucciones" type="text" class="form-control" name="instrucciones" rows="3"  placeholder="instrucciones" required></textarea>
							</div>
							<p></p>
							<div class="input-group" id="user_input">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<textarea id="link-video" type="text" class="form-control" name="link-video" rows="3"  placeholder="link-video" required></textarea>
							</div>
						</div>
					</div>	
				</form>
				<div class="modal-footer">
					<div class="text-center">
						<button id="agregar_tutorial" name="agregar_tutorial" type="submit" class="btn btn-success btn-sm btn-block">Agregar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="modal_instrucciones_popup" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo-modal-instrucciones"></span></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!--form-->
			<div id="div_form_login">
				<!--login form-->
				<form id="instrucciones_form"  name="instrucciones_form" class="form-horizontal" role="form" action="" method="POST">
					<div class="modal-body">
						<div id="login_msg">
							<div id="info_login_msg" class="glyphicon glyphicon-chevron-right"></div>
							<p></p>
							<p id="instrucciones_texto" name="instrucciones_texto"></p>
						</div>
					</div>	
				</form>
			</div>
		</div>
	</div>
</div>
<div id="modal_ayuda_popup" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo-modal-instrucciones"></span>Ayuda</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!--form-->
			<div id="div_form_login">
				<!--login form-->
				<form id="instrucciones_form"  name="instrucciones_form" class="form-horizontal" role="form" action="" method="POST">
					<div class="modal-body">
						<?php
							if($_SESSION["tipo_usuario"] == 1){
								echo '<img src="../img/ayuda_tutorial.gif" alt="Ayuda"> ';
							}
							if($_SESSION["tipo_usuario"] == 2){
								echo '<img src="../img/ayuda_cliente_tutoriales.gif" alt="Ayuda"> ';
							}
						?>
						
					</div>	
				</form>
			</div>
		</div>
	</div>
</div>

<?php footer_paginas(); ?>
<script src="../controller/tabla_tutoriales.js"></script>

</body>
</html>
