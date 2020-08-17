// Shorthand for $( document ).ready()
$(function() {  
	obtenerSoluciones();
});

function obtenerSoluciones(){
	var accion_agregar = "<button type='button' class='btn btn-success btn-xs' " +
                       "onclick='agregar();' title='Agregar'>" + 
                       "<i class='fas fa-plus'></i></button>";
    
	var table = $('#tabla-soluciones').dataTable({
		"columnDefs": [
      {"title": "N° Actividad", "targets": 0, "orderable": true, "className": "dt-body-center", "visible": true},
      {"title": "Titulo", "targets": 1, "orderable": true, "className": "dt-body-center"},
      {"title": accion_agregar, "targets": 2, "orderable": false, "className": "dt-nowrap dt-right"},
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
    url: '../lib/tabla_soluciones.php',
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
            data[i]["nombre_actividad"],
            "<button type='button' class='btn btn-warning btn-xs' onclick='editar(" + data[i]["id_actividad"] + ");' title='Editar'>"+
            "<i class='fas fa-edit'></i></button>&nbsp;" +
            "<button type='button' class='btn btn-danger btn-xs' onclick='eliminar(" + data[i]["id_actividad"] + ");' title='Eliminar'>"+
            "<i class='fas fa-trash'></i></button>" +
            "<button type='button' class='btn btn-info btn-xs' onclick='mostrar(" + data[i]["id_actividad"] + ");' title='Instrucciones'>"+
            "<i class='fas fa-eye'></i></button>"
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

function mostrar(id_actividad){
	$.post("../lib/tabla_soluciones.php?accion=6", {id_actividad: id_actividad}, function(response) {    
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
  $("#titulo-modal-solucion").html("Solucionario");
  document.getElementById("soluciones_form").reset();
  $("#agregar_solucion").attr("onClick", "agregarBD()");
  $("#modal_soluciones_popup").modal("show");
}

/* agrega un registro a la base de datos */
function agregarBD() {
  val = validarFormularioEspecialista();
  if (val == false) return false;
  
  /* convierte el formulario en un string de parámetros */
  var form = $("#soluciones_form").serialize();
  
  
  $.ajax({
    dataType: 'json',
    async: true,
    url: '../lib/tabla_soluciones.php?accion=2',
    data: form,
    success: function (response) {    
      if (response.success) {          
        $("#modal_soluciones_popup").modal("hide");
        obtenerSoluciones();
          
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
  document.getElementById("solucionesupdate_form").reset();

  $.post("../lib/tabla_soluciones.php?accion=3", {id_actividad: id_actividad}, function(response) {    
    if (response.success) {
      $.each(response.data, function(index, value) {
        if ($("input[name="+index+"]").length && value) {
          $("input[name="+index+"]").val(value);
        } else if ($("select[name="+index+"]").length && value){
          $("select[name="+index+"]").val(value);
        }
      });
		
      $("#titulo-modal-solucionupdate").html("Editar Solucionario");
      $("#agregar_solucionupdate").attr("onClick", "editarBD(" + id_actividad + ")");
      $("#modal_solucionesupdate_popup").modal("show");
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
  val = validarFormularioEspecialistaupdate();
  
  if (val == false) return false;
  
  var form = $("#solucionesupdate_form").serialize();
  $.post("../lib/tabla_soluciones.php?accion=4&id_actividad=" + id_actividad, form, function(response) {
  
    if (response.success) {
      $("#modal_solucionesupdate_popup").modal("hide");
      obtenerSoluciones();
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
      url: '../lib/tabla_soluciones.php',
      data: {accion: 5, id_actividad: id_actividad},
      success: function (response) {    
        if (response.success) {          
          obtenerSoluciones();
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
  if ($('#solucion').val().trim().length<1) {
    swal('Atención', "La solución es requerida", 'info');
    return false;
  }
  return true;
}

function validarFormularioEspecialistaupdate () {
  if ($('#solucionupdate').val().trim().length<1) {
    swal('Atención', "La solución es requerida", 'info');
    return false;
  }
  
  return true;
}
