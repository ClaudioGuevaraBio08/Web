$(function() {  
	obtenerEjerciciosAlumno();
	obtenerEjerciciosAdministrador();
});
function obtenerEjerciciosAlumno(){    
var acciones ="<button type='button' class='btn btn-info btn-xs' " +
              "onclick='ayuda();' title='Ayuda'>" + 
              "<i class='fas fa-question'></i>";
	var table = $('#tabla-ejercicios-alumno').dataTable({
		"columnDefs": [
      {"title": "N° Actividad", "targets": 0, "orderable": false, "className": "dt-body-center", "visible": true},
      {"title": "Titulo", "targets": 1, "orderable": true, "className": "dt-body-center"},
      {"title": "Dificultad", "targets": 2, "orderable": true, "className": "dt-body-center"},
      {"title": acciones, "targets": 3, "orderable": false, "className": "dt-nowrap dt-right"},
    ],
    dom: 'Bfrtip',
        buttons: [
            'csvHtml5',
            'pdfHtml5'
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
            "<button type='button' class='btn btn-info btn-xs' onclick='mostrar(" + data[i]["id_actividad"] + ");' title='Instrucciones'>"+
            "<i class='fas fa-eye'></i></button>" +
            "<button type='button' class='btn btn-primary btn-xs' onclick='mostrar_solucion(" + data[i]["id_actividad"] + ");' title='Solucion'>"+
            "<i class='fas fa-reply'></i>"
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

function obtenerEjerciciosAdministrador(){
	var accion_agregar = "<button type='button' class='btn btn-success btn-xs' " +
                       "onclick='agregar();' title='Agregar'>" + 
                       "<i class='fas fa-plus'></i></button>" +
                       "<button type='button' class='btn btn-info btn-xs' " +
                       "onclick='ayuda();' title='Ayuda'>" + 
                       "<i class='fas fa-question'></i>";
    
	var table = $('#tabla-ejercicios-administrador').dataTable({
		"columnDefs": [
      {"title": "N° Actividad", "targets": 0, "orderable": false, "className": "dt-body-center", "visible": true},
      {"title": "Titulo", "targets": 1, "orderable": true, "className": "dt-body-center"},
      {"title": "Dificultad", "targets": 2, "orderable": true, "className": "dt-body-center"},
      {"title": accion_agregar, "targets": 3, "orderable": false, "className": "dt-nowrap dt-right"},
    ],
    dom: 'Bfrtip',
        buttons: [
            'csvHtml5',
            'pdfHtml5'
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
            "<i class='fas fa-trash'></i></button>" +
            "<button type='button' class='btn btn-info btn-xs' onclick='mostrar(" + data[i]["id_actividad"] + ");' title='Instrucciones'>"+
            "<i class='fas fa-eye'></i></button>" +
            "<button type='button' class='btn btn-primary btn-xs' onclick='mostrar_solucion(" + data[i]["id_actividad"] + ");' title='Solucion'>"+
            "<i class='fas fa-reply'></i>"
          ]);
        }
      } else {
        swal({
          title: "Error",
          type: "error",
          showConfirmButton: false,
         });  
          setTimeout(() => {
            window.location = response.location;
          }, 1500);
      }      
    }, error: function(jqXHR, textStatus, errorThrown ) {
      swal({
        title: "Error",
        type: "error",
        showConfirmButton: false,
       });  
        setTimeout(() => {
          window.location = response.location;
        }, 1500);
    }
  })
}

function ayuda(){
  $("#modal_ayuda_popup").modal("show");
}
function mostrar_solucion(id_actividad){
	$.post("../lib/tabla_ejercicios.php?accion=7", {id_actividad: id_actividad}, function(response) {    
    console.log(response);
    if (response.success) {
      $.each(response.data, function(index, value) {
      });
	  document.getElementById("titulo-modal-soluciones").innerHTML = response.data['nombre'];
      document.getElementById("soluciones_texto").innerHTML = response.texto;
      $("#modal_soluciones_popup").modal("show");
    } else {
      swal({
        title: "Error",
        type: "error",
        showConfirmButton: false,
       });  
        setTimeout(() => {
          window.location = response.location;
        }, 1500);
    }
  }, 'json');
}
function mostrar(id_actividad){
	$.post("../lib/tabla_ejercicios.php?accion=6", {id_actividad: id_actividad}, function(response) {    
    if (response.success) {
      $.each(response.data, function(index, value) {
      });     
	  document.getElementById("titulo-modal-instrucciones").innerHTML = response.data['nombre'];
      document.getElementById("instrucciones_texto").innerHTML = response.texto;
      $("#modal_instrucciones_popup").modal("show");
    } else {
      swal({
        title: "Error",
        type: "error",
        showConfirmButton: false,
       });  
        setTimeout(() => {
          window.location = response.location;
        }, 1500);
    }
  }, 'json');
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
        obtenerEjerciciosAlumno();
        obtenerEjerciciosAdministrador();
      } else {
        swal({
          title: "Error",
          type: "error",
          showConfirmButton: false,
         });  
          setTimeout(() => {
            window.location = response.location;
          }, 1500);
      }
    }, error: function (e) {
      swal({
        title: "Error",
        type: "error",
        showConfirmButton: false,
       });  
        setTimeout(() => {
          window.location = response.location;
        }, 1500);
    }
  }); 
}
/* obtiene datos de una especialidad y los muestra en el modal */
function editar(id_actividad) {
  document.getElementById("ejercicio_form").reset();
  $.post("../lib/tabla_ejercicios.php?accion=3", {id_actividad: id_actividad}, function(response) {    
    if (response.success) {
      $.each(response.data, function(index, value) {
        if ($("input[name="+index+"]").length && value) {
          $("input[name="+index+"]").val(value);
        } else if ($("select[name="+index+"]").length && value){
          $("select[name="+index+"]").val(value);
        }
      });
      $("#titulo-modal-ejercicio").html("Editar");
      $("#agregar_ejercicio").attr("onClick", "editarBD(" + id_actividad + ")");
      $("#modal_ejercicios_popup").modal("show");
    } else {
      swal({
        title: "Error",
        type: "error",
        showConfirmButton: false,
       });  
        setTimeout(() => {
          window.location = response.location;
        }, 1500);
    }
  }, 'json');
}
/* actualiza los datos en la base de datos */
function editarBD(id_actividad) {
  val = validarFormularioEspecialista(); 
  if (val == false) return false;
  var form = $("#ejercicio_form").serialize();
  $.post("../lib/tabla_ejercicios.php?accion=4&id_actividad=" + id_actividad, form, function(response) {
    if (response.success) {
      $("#modal_ejercicios_popup").modal("hide");
      obtenerEjerciciosAlumno();
      obtenerEjerciciosAdministrador();
    } else {
      swal({
        title: "Error",
        type: "error",
        showConfirmButton: false,
       });  
        setTimeout(() => {
          window.location = response.location;
        }, 1500);
    }
  }, 'json');
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
          obtenerEjerciciosAlumno();
          obtenerEjerciciosAdministrador();
        } else {
          swal({
            title: "Error",
            type: "error",
            showConfirmButton: false,
           });  
            setTimeout(() => {
              window.location = response.location;
            }, 1500);
        }
      }, error: function (e) {
        swal({
          title: "Error",
          type: "error",
          showConfirmButton: false,
         });  
          setTimeout(() => {
            window.location = response.location;
          }, 1500);
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
  else if ($('#enunciado').val().trim().length < 1){
    swal('Atención', 'El enunciado es requerido', 'info');
    return false;
  }
  else if ($('#lista_dificultad').val() == 0) { 
    swal('Atención', 'La dificultad es requerida', 'info');
    return false;
  }
  else{
    return true;
  }
}