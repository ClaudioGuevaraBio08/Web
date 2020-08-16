$.ajax({
	url: '../lib/grafico.php',
	data: {accion: 1},
	dataType: 'json',
	async: true,
	success: function(data) {
		if(data.success){
			var lenguaje = [];
			var cantidad = [];
			
			var data_json = JSON.parse(data);
			console.log(data_json);
			
			for (var i = 0; i<data_json.data.length; i++){
				lenguaje.push(data_json.data[i].id_lenguaje);
				cantidad.push(data_json.data[i].cantidad);
			}
			
			console.log(lenguaje);
			console.log(cantidad);
			
			var ctx = document.getElementById('myChart').getContext('2d');
			var cantidad_por_lenguaje = new Chart(ctx,
			{
				"type":"bar",
				"data":{
					"labels":lenguaje,
					"datasets":[{data: cantidad}]
				}
			});
		}
	}
	
});

