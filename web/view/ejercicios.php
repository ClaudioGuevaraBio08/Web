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
ejercicios();
?>
<div id="modal_ejercicios_popup" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo-modal-ejercicio">Ejercicio</span></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!--form-->
			<div id="div_form_login">
				<!--login form-->
				<form id="ejercicio_form"  name="ejercicio_form" class="form-horizontal" role="form" action="" method="POST">
					<div class="modal-body">
						<div id="login_msg">
							<div id="info_login_msg" class="glyphicon glyphicon-chevron-right"></div>
							<div class="input-group" id="user_input">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<input id="nombre" type="text" class="form-control" name="nombre"  placeholder="nombre" required>
							</div>
							<p></p>
							<div class="input-group" id="user_input">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<textarea id="enunciado" type="text" class="form-control" name="enunciado" rows="3"  placeholder="enunciado" required></textarea>
							</div>
							<p></p>
							<div class="input-group" id="user_input">
								<select id="lista_dificultad" name="lista_dificultad" class="form-control"></select>
							</div>
						</div>
					</div>	
				</form>
				<div class="modal-footer">
					<div class="text-center">
						<button id="agregar_ejercicio" type="submit" class="btn btn-success btn-sm btn-block">Agregar</button>
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
<div id="modal_soluciones_popup" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo-modal-soluciones"></span></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!--form-->
			<div id="div_form_login">
				<!--login form-->
				<form id="soluciones_form"  name="soluciones_form" class="form-horizontal" role="form" action="" method="POST">
					<div class="modal-body">
						<div id="login_msg">
							<div id="info_login_msg" class="glyphicon glyphicon-chevron-right"></div>
							<p></p>
							<p id="soluciones_texto" name="soluciones_texto"></p>
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
								echo '<img src="../img/ayuda_ejercicio.gif" alt="Ayuda"> ';
							}
							if($_SESSION["tipo_usuario"] == 2){
								echo '<img src="../img/ayuda_cliente_ejercicios.gif" alt="Ayuda"> ';
							}
						?>	
					</div>	
				</form>
			</div>
		</div>
	</div>
</div>
<?php footer_paginas(); ?>
<script src="../controller/tabla_ejercicio.js"></script>
<script type="text/javascript" src="../controller/combobox_dificultad.js"></script>
</body>
</html>