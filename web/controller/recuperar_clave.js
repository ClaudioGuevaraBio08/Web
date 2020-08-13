
$(function() {  
  $("#recuperarclave_form").submit(function () {
    var user_correo_recuperarclave = $('#correo_recuperarclave').val().trim();
    
    $.ajax({
      type: "POST",
      dataType: 'json',
      url: "../lib/recuperar_clave.php",
      data: {correo_recuperarclave: user_correo_recuperarclave},
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
    
    $('#correo_recuperarclave').val('');
    return false;
  });
});

