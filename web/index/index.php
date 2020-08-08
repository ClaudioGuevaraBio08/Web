<?php
require_once("../lib/common.php");
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
main_pagina_out();
?>

<!--modals-->
  <div id="modal_login_popup" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Ingreso</h4>
        </div>
        <!--form-->
        <div id="div_form_login">
          <!--login form-->
          <form id="login_form" name="login_form" class="form-horizontal" role="form" action="" method="POST">
            <div class="modal-body">
              <div id="login_msg">
                <div id="info_login_msg" class="glyphicon glyphicon-chevron-right"></div>
                  <span id="text_login_msg">Credenciales</span>
              </div>
              <p></p>
              <div class="input-group" id="user_input">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input id="username" type="text" class="form-control" name="username"  placeholder="ej: 12345678K" required>
              </div>
              <p></p>
              <div class="input-group" id="user_pass">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input id="password" type="password" class="form-control" name="password" placeholder="clave" required>
              </div>
            </div>
            <div class="modal-footer">
              <div>
                <button id="submit_login" type="submit" class="btn btn-success btn-sm btn-block">Ingresar</button>
	      </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

<?php footer(); ?>
<script src="../controller/login.js"></script>


  
  
</body>
