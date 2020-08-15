$(document).ready(function(){
	$.ajax({
		type: 'POST',
		url: '../lib/combobox_actividad.php'
	})
	.done(function(actividad_rep){
		$('#lista_actividad').html(actividad_rep)
	})
	.fail(function(){
		alert('error')
	})
})
