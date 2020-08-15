<?php
require_once("../lib/common.php");

function getLenguajeRep(){
	$db = conectarBD();
	$query = 'select * from lenguaje';
	$result = $db->query($query);
	$result->execute();
	$listas = '<option value="0">Lenguaje</option>';
	while($row = $result->fetch(PDO::FETCH_ASSOC)){
		$listas .= "<option value='$row[id_lenguaje]'>$row[nombre]</option>";
	}
	return $listas;
}
echo getLenguajeRep();
?>

