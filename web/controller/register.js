// Shorthand for $( document ).ready()
$(function() {
  
  $("#register_form").submit(function () {
    var user_nombre = $('#nombre').val().trim();
    var user_apellido = $('#apellido').val().trim();
    var user_correo = $('#correo').val().trim();
    var user_clave = $('#clave').val().trim();
    var user_confirm_clave = $('#confirm_password').val().trim();
    
    $.ajax({
      type: "POST",
      dataType: 'json',
      url: "../lib/check_register.php",
      data: {usernombre: user_nombre, userapellido: user_apellido, correo: user_correo, pwd: user_clave, pwd_confirm: user_confirm_clave},
      async: "false",
      success: function (response) {
      
        if (response.success) {
          window.location=response.location;
        } else {
          showMessageAlert(response.msg);
        }
      }, error: function (e) {
        logout();
      }
    });
    
    $('#nombre').val('');
    $('#apellido').val('');
    $('#correo').val('');
    $('#clave').val('');
    $('#confirm_password').val('');
    return false;
  });
});
