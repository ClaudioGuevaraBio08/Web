<?php
require_once("../lib/common.php");

function getActividadRep(){
	$db = conectarBD();
	$lenguaje = $_SESSION['lenguaje'];
	$query = 'select actividad.id_actividad, actividad.nombre from actividad where id_actividad not in (select id_actividad from solucion where id_lenguaje =' . $lenguaje . ');';
	$result = $db->query($query);
	$result->execute();
	$listas = '<option value="0">Actividad</option>';
	while($row = $result->fetch(PDO::FETCH_ASSOC)){
		$listas .= "<option value='$row[id_actividad]'>$row[nombre] (N° $row[id_actividad])</option>";
	}
	return $listas;
}
echo getActividadRep();
?>
