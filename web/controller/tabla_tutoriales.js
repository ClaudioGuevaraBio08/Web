$(function() {  
	obtenerTutorialesAlumno();	
	obtenerTutorialesAdministrador();
});
function obtenerTutorialesAlumno(){
  var acciones = "<button type='button' class='btn btn-info btn-xs' " +
                  "onclick='ayuda();' title='Ayuda'>" + 
                  "<i class='fas fa-question'></i>";

	var table = $('#tabla-tutoriales-alumno').dataTable({
		"columnDefs": [
      {"title": "N° Tutorial", "targets": 0, "orderable": true, "className": "dt-body-center", "visible": true},
      {"title": "Titulo", "targets": 1, "orderable": true, "className": "dt-body-center"},
      {"title": "Link", "targets": 2, "orderable": true, "className": "dt-body-center"},
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
    url: '../lib/tabla_tutoriales.php',
    data: {accion: 1},
    type: 'POST',
    dataType: 'json',
    async: true,
    success: function(response) {
      if (response.success) {
        var data = response.data;
        
        for(var i = 0; i < data.length; i++) {
          table.fnAddData([
            data[i]["id_tutorial"],
            data[i]["nombre_tutorial"],
            '<a href = ' + data[i]['link_video'] + '">' + data[i]['link_video'] + '<\a>',
            "<button type='button' class='btn btn-info btn-xs' onclick='mostrar(" + data[i]["id_tutorial"] + ");' title='Instrucciones'>"+
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

function obtenerTutorialesAdministrador(){
	var acciones = "<button type='button' class='btn btn-success btn-xs' " +
                       "onclick='agregar();' title='Agregar'>" + 
                       "<i class='fas fa-plus'></i></button>" +
                       "<button type='button' class='btn btn-info btn-xs' " +
                       "onclick='ayuda();' title='Ayuda'>" + 
                       "<i class='fas fa-question'></i>";

    
	var table = $('#tabla-tutoriales-administrador').dataTable({
		"columnDefs": [
      {"title": "N° Tutorial", "targets": 0, "orderable": true, "className": "dt-body-center", "visible": true},
      {"title": "Titulo", "targets": 1, "orderable": true, "className": "dt-body-center"},
      {"title": "Link", "targets": 2, "orderable": true, "className": "dt-body-center"},
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
    url: '../lib/tabla_tutoriales.php',
    data: {accion: 1},
    type: 'POST',
    dataType: 'json',
    async: true,
    success: function(response) {
      if (response.success) {
        var data = response.data;
        
        for(var i = 0; i < data.length; i++) {
          table.fnAddData([
            data[i]["id_tutorial"],
            data[i]["nombre_tutorial"],
            '<a href = ' + data[i]['link_video'] + '">' + data[i]['link_video'] + '<\a>',
            "<button type='button' class='btn btn-warning btn-xs' onclick='editar(" + data[i]["id_tutorial"] + ");' title='Editar'>"+
            "<i class='fas fa-edit'></i></button>&nbsp;" +
            "<button type='button' class='btn btn-danger btn-xs' onclick='eliminar(" + data[i]["id_tutorial"] + ");' title='Eliminar'>"+
            "<i class='fas fa-trash'></i></button>" +
            "<button type='button' class='btn btn-info btn-xs' onclick='mostrar(" + data[i]["id_tutorial"] + ");' title='Instrucciones'>"+
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

function mostrar(id_tutorial){
	$.post("../lib/tabla_tutoriales.php?accion=6", {id_tutorial: id_tutorial}, function(response) {    
    if (response.success) {


	  document.getElementById("titulo-modal-instrucciones").innerHTML = response.data['nombre_tutorial'];
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
function ayuda(){
  $("#modal_ayuda_popup").modal("show");
}
/* levanta el modal para ingresar datos */
function agregar() {
  $("#titulo-modal-tutorial").html("Tutorial");
  document.getElementById("tutorial_form").reset();
  $("#agregar_tutorial").attr("onClick", "agregarBD()");
  $("#modal_tutoriales_popup").modal("show");
}

/* agrega un registro a la base de datos */
function agregarBD() {
  val = validarFormularioEspecialista();
  if (val == false) return false;
  
  /* convierte el formulario en un string de parámetros */
  var form = $("#tutorial_form").serialize();
  
  
  $.ajax({
    dataType: 'json',
    async: true,
    url: '../lib/tabla_tutoriales.php?accion=2',
    data: form,
    success: function (response) {    
      if (response.success) {          
        $("#modal_tutoriales_popup").modal("hide");
        obtenerTutorialesAlumno();
        obtenerTutorialesAdministrador();
          
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
function editar(id_tutorial) {
  document.getElementById("tutorial_form").reset();

  $.post("../lib/tabla_tutoriales.php?accion=3", {id_tutorial: id_tutorial}, function(response) {    
    if (response.success) {
      $.each(response.data, function(index, value) {
        if ($("input[name="+index+"]").length && value) {
          $("input[name="+index+"]").val(value);
        } else if ($("select[name="+index+"]").length && value){
          $("select[name="+index+"]").val(value);
        }
      });
    
      $("#titulo-modal-tutorial").html("Editar Tutorial");
      $("#agregar_tutorial").attr("onClick", "editarBD(" + id_tutorial + ")");
      $("#modal_tutoriales_popup").modal("show");
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
function editarBD(id_tutorial) {
  val = validarFormularioEspecialista(); 
  if (val == false) return false;
  var form = $("#tutorial_form").serialize();
  $.post("../lib/tabla_tutoriales.php?accion=4&id_tutorial=" + id_tutorial, form, function(response) {
    if (response.success) {
      $("#modal_tutoriales_popup").modal("hide");
      obtenerTutorialesAlumno();
      obtenerTutorialesAdministrador();
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
function eliminar(id_tutorial) {
  swal({
    title: '¿Seguro?',
    text: "Esta operación no se puede revertir",
    type: 'warning',
    showCancelButton: false,
    showConfirmButton: true,
  }).then(function () {
    $.ajax({
      dataType: 'json',
      async: true,
      url: '../lib/tabla_tutoriales.php',
      data: {accion: 5, id_tutorial: id_tutorial},
      success: function (response) {    
        if (response.success) {          
          obtenerTutorialesAdministrador();
          obtenerTutorialesAlumno();
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
  if ($('#nombre_tutorial').val().trim().length<1){
    swal('Atención', "El Nombre es requerido", 'info');
    return false;
  }
  else if ($('#instrucciones').val().trim().length < 1){
    swal('Atención', 'Las instrucciones son requeridas', 'info');
    return false;
  }
  else {
    return true;
  }
}