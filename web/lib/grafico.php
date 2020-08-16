<?php
require_once("../lib/common.php");

if (isset($_REQUEST['accion'])) {
	switch ($_REQUEST['accion']) {
		case 1:
		  # select
		  $conn = conectarBD();
		  seleccionar($conn);
		  break;
	}
}

function seleccionar ($conn){
	$sql= "select count(id_lenguaje) as cantidad, id_lenguaje from solucion group by id_lenguaje order by id_lenguaje;";
	$stmt = $conn->prepare($sql);
		
	$data = ejecutarSQL($stmt);  
	echo json_encode($data);
}



?>
