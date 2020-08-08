/* sweetalert2 default values */
swal.setDefaults({
  confirmButtonColor: '#5cb85c', 
  cancelButtonColor: '#d9534f',
  confirmButtonText: 'Aceptar',
  cancelButtonText: 'Cancelar'
});

function showMessageAlert(msg) {
  swal('Atención', msg, 'warning');
}

function logout () {
  window.location.href = "../lib/logout.php";
}
