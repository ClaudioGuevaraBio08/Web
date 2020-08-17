$(document).ready(function() {
    $.ajax({
        url: "../lib/grafico.php?accion=1",
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        method: "GET",
        success: function(data) {
            var nombre = [];
            var cantidad = [];
            var color = ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)'];
            var bordercolor = ['rgba(255,99,132,1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)'];
            for (var i in data) {
                nombre.push(data[i].nombre);
                cantidad.push(data[i].cantidad);
            }
            var chartdata = {
                labels: nombre,
                datasets: [{
                    label: nombre,
                    backgroundColor: color,
                    borderColor: color,
                    borderWidth: 2,
                    hoverBackgroundColor: color,
                    hoverBorderColor: bordercolor,
                    data: cantidad
                }]
            };
            var mostrar = $("#miGrafico1");
            var grafico = new Chart(mostrar, {
                type: 'doughnut',
                data: chartdata,
                options: {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        },
    });
});
$(document).ready(function() {
    $.ajax({
        url: "../lib/grafico.php?accion=2",
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        method: "GET",
        success: function(data) {
            var nombre = [];
            var cantidad = [];
            var color = ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)'];
            var bordercolor = ['rgba(255,99,132,1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)'];
            for (var i in data) {
                nombre.push(data[i].nombre);
                cantidad.push(data[i].cantidad);
            }
            var chartdata = {
                labels: nombre,
                datasets: [{
                    backgroundColor: color,
                    borderColor: color,
                    borderWidth: 2,
                    hoverBackgroundColor: color,
                    hoverBorderColor: bordercolor,
                    data: cantidad
                }]
            };
            var mostrar = $("#miGrafico2");
            var grafico = new Chart(mostrar, {
                type: 'bar',
                data: chartdata,
                options: {
					legend: {
						display: false
					},
                    responsive: true,
                    scales: {
                        yAxes: [{
							scaleLabel: {
								display: true,
								labelString: 'Cantidad'
							},
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        xAxes: [{
							scaleLabel: {
								display: true,
								labelString: 'Dificultad'
							}
						}],
                    }
                }
            });
        },
    });
});