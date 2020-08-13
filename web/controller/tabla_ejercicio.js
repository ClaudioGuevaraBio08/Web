// Shorthand for $( document ).ready()
$(function() {  
	obtenerEjercicios();
});


function obtenerEjercicios(){
	var accion_agregar = "<button type='button' class='btn btn-success btn-xs' " +
                       "onclick='agregar();' title='Agregar'>" + 
                       "<i class='fas fa-plus'></i></button>";
    
	var table = $('#tabla-ejercicios').dataTable({
		"columnDefs": [
      {"title": "ID", "targets": 0, "orderable": false, "className": "dt-body-center", "visible": true},
      {"title": "Titulo", "targets": 1, "orderable": true, "className": "dt-body-center"},
      {"title": "Dificultad", "targets": 2, "orderable": true, "className": "dt-body-center"},
      {"title": accion_agregar, "targets": 3, "orderable": false, "className": "dt-nowrap dt-right"},
    ],
    "searching": true,
    "search": {
      "regex": true,
      "smart": true
    },
    "scrollX": false,
    "order": [[1, "asc"]],
    "bDestroy": true,
    "deferRender": true,
    "language": {
    },
    "pageLength": 10,
    "bPaginate": true,
    "bLengthChange": true,
    "bFilter": true,
    "bInfo": true,
    "bAutoWidth": false
	});
	
	table.fnClearTable();
	
	$.ajax({
    url: '../lib/tabla_ejercicios.php',
    data: {accion: 1},
    type: 'POST',
    dataType: 'json',
    async: true,
    success: function(response) {
      if (response.success) {
        var data = response.data;
        
        for(var i = 0; i < data.length; i++) {
          table.fnAddData([
            data[i]["id_actividad"],
            data[i]["nombre"],
            data[i]["dificultad"],
            "<button type='button' class='btn btn-warning btn-xs' onclick='editar(" + data[i]["id_actividad"] + ");' title='Editar'>"+
            "<i class='fas fa-edit'></i></button>&nbsp;" +
            "<button type='button' class='btn btn-danger btn-xs' onclick='eliminar(" + data[i]["id_actividad"] + ");' title='Eliminar'>"+
            "<i class='fas fa-trash'></i></button>"
          ]);
        }
      } else {
        swal('Error', response.msg[2], 'error');
      }      
    }, error: function(jqXHR, textStatus, errorThrown ) {
      swal('Error', textStatus + " " + errorThrown, 'error');
    }
  })
}

function enunciado(titulo) {
}

/* levanta el modal para ingresar datos */
function agregar() {
  $("#titulo-modal-ejercicio").html("Ejercicio");
  document.getElementById("ejercicio_form").reset();
  $("#agregar_ejercicio").attr("onClick", "agregarBD()");
  $("#modal_ejercicios_popup").modal("show");
}

/* agrega un registro a la base de datos */
function agregarBD() {
  val = validarFormularioEspecialista();
  if (val == false) return false;
  
  /* convierte el formulario en un string de parámetros */
  var form = $("#ejercicio_form").serialize();
  
  
  $.ajax({
    dataType: 'json',
    async: true,
    url: '../lib/tabla_ejercicios.php?accion=2',
    data: form,
    success: function (response) {    
      if (response.success) {          
        $("#modal_ejercicios_popup").modal("hide");
        obtenerEjercicios();
          
      } else {
        swal('Error', response.msg[2], 'error');
      }
    }, error: function (e) {
      swal('Error', e.responseText, 'error');
    }
  }); 
}

/* elimina un registro de la base de datos */
function eliminar(id_actividad) {
  swal({
    title: '¿Está seguro (a)?',
    text: "Esta operación no se puede revertir!",
    type: 'warning',
    showCancelButton: true,
  }).then(function () {
    $.ajax({
      dataType: 'json',
      async: true,
      url: '../lib/tabla_ejercicios.php',
      data: {accion: 5, id_actividad: id_actividad},
      success: function (response) {    
        if (response.success) {          
          obtenerEjercicios();
        } else {
          swal('Error', response.msg[2], 'error');
        }
      }, error: function (e) {
        swal('Error', e.responseText, 'error');
      }
    });    
  });
}

/* valida que los datos obligatorios tengan algún valor */
function validarFormularioEspecialista () {
  if ($('#nombre').val().trim().length<1) {
    swal('Atención', "El Nombre es requerido", 'info');
    return false;
  }
  
  return true;
}
