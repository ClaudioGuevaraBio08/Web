<?php
require_once("../lib/common.php");

$conn = conectarBD();


$sql= "select count(id_lenguaje) as cantidad, id_lenguaje from solucion group by id_lenguaje order by id_lenguaje;";
$stmt = $conn->prepare($sql);
    
$data = ejecutarSQL($stmt);  
echo json_encode($data);


?>
