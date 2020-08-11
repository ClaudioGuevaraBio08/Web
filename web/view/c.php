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
pagina_c();
?>
    
<?php footer_paginas(); ?>
<script src="../controller/index.js"></script>
</body>
</html>
