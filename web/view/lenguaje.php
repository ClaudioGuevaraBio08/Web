<?php
session_start();
require_once("../lib/common.php");
$lenguaje = $_REQUEST['lenguaje'];
$nombre_lenguaje = $_REQUEST['nombre_lenguaje'];
$ruta_imagen = $_REQUEST['ruta_imagen'];
$_SESSION['ruta_imagen'] = $ruta_imagen;
$_SESSION['lenguaje'] = $lenguaje;
$_SESSION['nombre_lenguaje'] = $nombre_lenguaje;
if ($lenguaje == 2){
	$nombre_lenguaje = 'C++';
	$_SESSION['nombre_lenguaje'] = $nombre_lenguaje;
}
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
pagina_lenguaje();
?>
    
<?php footer_paginas(); ?>
<script src="../controller/index.js"></script>

<script src="../vendor/chart.js/Chart.min.js"></script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="../controller/grafico.js"></script>

</body>
</html>
