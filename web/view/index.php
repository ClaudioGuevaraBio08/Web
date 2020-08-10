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
  header_pagina();
  main_pagina_in();
  ?>
    
  <?php footer(); ?>
  <script src="../controller/index.js"></script>
</body>
</html>