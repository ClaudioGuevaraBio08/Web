$(document).ready(function(){
	$.ajax({
		type: 'POST',
		url: '../lib/combobox_lenguaje.php'
	})
	.done(function(lenguaje_rep){
		$('#lista_lenguaje').html(lenguaje_rep)
	})
	.fail(function(){
		alert('error')
	})
})
