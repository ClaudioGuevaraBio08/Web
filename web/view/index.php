<?php
session_start();
require_once("../lib/common.php");
validarSesion();
?>

<!DOCTYPE html>
<head>
<?php 
head(); 
?>
</head>

<body>
<?php 
navbar();
main_pagina_in();
?>
    
<?php footer_paginas(); ?>
<script src="../controller/index.js"></script>
</body>
</html>
