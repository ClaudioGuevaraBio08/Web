window.onload = function () {
	
	$.post("../lib/grafico.php",
	function (data){
		var lenguaje = [];
		var cantidad = [];
		
		var data_json = JSON.parse(data);
		
		for (var i = 0; i<data_json.data.length; i++){
			lenguaje.push(data_json.data[i].id_lenguaje)
			cantidad.push(data_json.data[i].cantidad)
		}
		console.log(lenguaje);
		console.log(cantidad);
		
		
		
		
		var a = 0;
		
		var chart = new CanvasJS.Chart("grafico", {
			animationEnabled: true,
		
			title:{
				text:"Fortune 500 Companies by Country"
			},
			axisX:{
				interval: 1
			},
			axisY2:{
				interlacedColor: "rgba(1,77,101,.2)",
				gridColor: "rgba(1,77,101,.1)",
				title: "Number of Companies"
			},
			var chartdata = {
				type: "bar",
				labels: lenguaje,
				datasets: [{
					label: 'lenguaje',
					data: cantidad
				}]
			};
			
			data: [{ chartdata }]
		});
		chart.render();
	});
}

		
