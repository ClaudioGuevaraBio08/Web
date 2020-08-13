$(document).ready(function(){
	$.ajax({
		type: 'POST',
		url: '../lib/combobox_dificultad.php'
	})
	.done(function(dificultad_rep){
		$('#lista_dificultad').html(dificultad_rep)
	})
	.fail(function(){
		alert('error')
	})
})
