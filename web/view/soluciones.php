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
soluciones();
?>

<div id="modal_soluciones_popup" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo-modal-solucion"></span></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!--form-->
			<div id="div_form_login">
				<!--login form-->
				<form id="soluciones_form"  name="soluciones_form" class="form-horizontal" role="form" action="" method="POST">
					<div class="modal-body">
						<div id="login_msg">
							<div id="info_login_msg" class="glyphicon glyphicon-chevron-right"></div>
							<div class="input-group" id="user_input">
								<select id="lista_actividad" name="lista_actividad" class="form-control"></select>
							</div>
							<p></p>
							<div class="input-group" id="user_input">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<textarea id="solucion" type="text" class="form-control" name="solucion" rows="3"  placeholder="Solucion del ejercicio" required></textarea>
							</div>
						</div>
					</div>	
				</form>
				<div class="modal-footer">
					<div class="text-center">
						<button id="agregar_solucion" type="submit" class="btn btn-success btn-sm btn-block">Agregar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="modal_solucionesupdate_popup" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo-modal-solucionupdate"></span></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!--form-->
			<div id="div_form_login">
				<!--login form-->
				<form id="solucionesupdate_form"  name="solucionesupdate_form" class="form-horizontal" role="form" action="" method="POST">
					<div class="modal-body">
						<div id="login_msg">
							<div id="info_login_msg" class="glyphicon glyphicon-chevron-right"></div>
							<div class="input-group" id="user_input">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<textarea id="solucionupdate" type="text" class="form-control" name="solucionupdate" rows="3"  placeholder="Solucion del ejercicio" required></textarea>
							</div>
						</div>
					</div>	
				</form>
				<div class="modal-footer">
					<div class="text-center">
						<button id="agregar_solucionupdate" type="submit" class="btn btn-success btn-sm btn-block">Agregar</button>
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
						<img src="../img/ayuda_solucion.gif" alt="Ayuda"> 
					</div>	
				</form>
			</div>
		</div>
	</div>
</div>
    
<?php footer_paginas(); ?>
<script src="../controller/tabla_soluciones.js"></script>
<script type="text/javascript" src="../controller/combobox_actividad.js"></script>

</body>
</html>
