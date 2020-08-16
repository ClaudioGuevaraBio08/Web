<!DOCTYPE html>
<html>
	<head>
		<title>Gráfico</title>
	</head>
	
	<body>
		<canvas id="myChart" width="400" height="400"></canvas>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.js"></script>
		<script type="text/javascript">
			var ctx = document.getElementById('myChart').getContext('2d');
			var myChart = new Chart(ctx, {
				type: 'line',
				data: {
					labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
					datasets: [{
						label: '# of Votes',
						backgroundColor: 'rgba(255, 99, 132)',
						borderColor: 'rgba(255, 99, 132),'
						data: [12, 19, 3, 5, 2, 3],
					}]
				},
				options: {}
			});
		</script>
	</body>

</html>
