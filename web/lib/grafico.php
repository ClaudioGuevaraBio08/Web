<?php
require_once("../lib/common.php");

if (isset($_REQUEST['accion'])) {
  switch ($_REQUEST['accion']) {     
    case 1:
      # select
      $conn = conectarBD();
      caso1 ($conn);
      break;
    case 2:
      # select
      $conn = conectarBD();
      caso2 ($conn);
      break;
  }
}

function caso1 ($conn){
	$query = "SELECT lenguaje.nombre, count(solucion.id_lenguaje) as cantidad from solucion join lenguaje on lenguaje.id_lenguaje = solucion.id_lenguaje group by lenguaje.nombre;";
	$result = $conn->prepare($query);
	$result->execute();
	$listas = array();
	while($row = $result->fetch(PDO::FETCH_ASSOC)){
		$listas[] = $row;
	}

	echo json_encode($listas);
}


function caso2 ($conn){
	$query = "select count(actividad.id_dificultad) as cantidad, dificultad.nombre from actividad join dificultad on dificultad.id_dificultad = actividad.id_dificultad group by dificultad.id_dificultad;";
	$result = $conn->prepare($query);
	$result->execute();
	$listas = array();
	while($row = $result->fetch(PDO::FETCH_ASSOC)){
		$listas[] = $row;
	}

	echo json_encode($listas);
}


?>
