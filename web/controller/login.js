// Shorthand for $( document ).ready()
function cerrar_login(){
	$("#modal_login_popup").modal('hide');
}
$(function() {
  $("#recuperarclave").attr("onClick", "cerrar_login()");
  
  $("#login_form").submit(function () {
    var user_name = $('#username').val().trim();
    var user_password = $('#password').val().trim();
    $.ajax({
      type: "POST",
      dataType: 'json',
      url: "../lib/check_login.php",
      data: {username: user_name, pwd: user_password},
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
    
    $('#username').val('');
    $('#password').val('');
    return false;
  });
});
