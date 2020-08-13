<?php
require_once("../lib/common.php");

function getDificultadRep(){
	$db = conectarBD();
	$query = 'select * from dificultad';
	$result = $db->query($query);
	$result->execute();
	$listas = '<option value="0">Dificultad</option>';
	while($row = $result->fetch(PDO::FETCH_ASSOC)){
		$listas .= "<option value='$row[id_dificultad]'>$row[nombre]</option>";
	}
	return $listas;
}
echo getDificultadRep();
?>
