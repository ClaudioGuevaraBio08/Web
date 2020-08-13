<?php
if (session_status() == PHP_SESSION_NONE){
	session_start();
}

setlocale (LC_TIME, "es_ES.utf8");

$file = "../../conf.ini";
$ini = parse_ini_file($file, false);

date_default_timezone_set($ini['timezone']);

function validarSesion() {
	if (isset($_SESSION["loggedinMIAPP"])){
		if ($_SESSION["loggedinMIAPP"] != true){
			logout();
		}
	}
	else{
		logout();
	}
}

function logout() {
	header('Location: ../lib/logout.php');
}

function encriptar($string){
	$encrypt_method = $GLOBALS['ini']['encrypt_method'];
	$secret_key = $GLOBALS['ini']['secret_key'];
	$secret_iv = $GLOBALS['ini']['secret_iv'];
	$encrypted = openssl_encrypt($string, $encrypt_method, $secret_key, 0, $secret_iv);
	$encrypted = base64_encode($encrypted);
	return $encrypted;
}

function desencriptar($string){
	$encrypt_method = $GLOBALS['ini']['encrypt_method'];
	$secret_key = $GLOBALS['ini']['secret_key'];
	$secret_iv = $GLOBALS['ini']['secret_iv'];
	$decrypted = openssl_decrypt(base64_decode($string), $encrypt_method, $secret_key, 0, $secret_iv);
	return $decrypted;
}

function conectarBD (){
	$i = $GLOBALS['ini'];
	$host = $i["host"];
	$db = $i["db"];
	$user = $i["user"];
	$password = $i["password"];
	$port = $i["port"];
	
	try{
		$conn = new PDO('pgsql:host='.$host.';port='.$port.';dbname='.$db.';user='.$user.';password='.$password);
		return $conn;
	}
	catch (PDOException $e){
		return false;
	}
}

function ejecutarSQL ($stmt){
	$res = array();
	$res["success"] = false;
	$res["msg"] = "Error SQL";
	$res["data"] = null;
	
	try{
		if ($stmt->execute()){
			$res["data"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$res["msg"] = "éxito";
			$res["success"] = true;
		}
		else{
			$arrayError['23503'] = "El registro no se puede eliminar porque tiene información asociada.";
			$res["msg"] = "Error SQL, Contacte al soporte";
		}
	}
	catch (PDOException $e){
		$res["msg"] = $e->getMessage();
	}
	
	return $res;
}

function head (){
	$str = <<<EOF
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.5">
	<meta http-equiv="X-UUA-Compatible" content="ie=edge">
	<title>{$GLOBALS['ini']['titulo']}</title>
	<link rel="stylesheet" href="../css/estilos.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
	crossorigin="anonymous">
	<link rel="stylesheet" href="../extras/bootstrap/bootstrap.css">
	<!--<link rel="stylesheet" href="../extras/bootstrap/bootstrap-theme.min.css">-->
	<link rel="stylesheet" href="../extras/sweetalert2/sweetalert2.min.css">
	<link rel="stylesheet" href="../extras/jquery/jquery-ui.min.css">
	<link rel="stylesheet" href="../css/common.css">
EOF;
	if ($_SESSION["loggedinMIAPP"] == true or $_SESSION["loggedinMIAPP"] == false ) {
    		echo $str;
  	}
}

function head_lenguajes (){
	$str = <<<EOF
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Mi app web</title>
	
	
	<link href="../css/all.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	<link href="../css/sb-admin-2.min.css" rel="stylesheet">
	<link rel="stylesheet" href="../css/estilos.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<link href="../css/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link rel="stylesheet" href="../extras/sweetalert2/sweetalert2.css">
	
	
	

EOF;
	echo $str;
}

function navbar (){
	$strIn = <<<EOF
	<div class="navbar navbar-light" style="background-color: hsla(195, 46%, 5%, 0.1);">
		<div id="encabezado" class="hoc clear">
			<div class="fl_right">
				<ul class="nospace">
					<li><a href="index.php"><i class="fa fa-lg fa-home"></i></a></li>
					<li><a class="dropdown-toggle" data-toggle="modal" href="#"><span class="glyphicon glyphicon-user"></span> {$_SESSION['username']} </a>
					<li><a href="../lib/logout.php"><span class="glyphicon glyphicon-log-in"></span> Cerrar</a></li>
				</ul>
			</div>
		</div>
EOF;
	$strOut = <<<EOF
	<div class="navbar navbar-light" style="background-color: hsla(195, 46%, 5%, 0.1);">
        	<div id="encabezado" class="hoc clear">
                	<div class="fl_right">
                        	<ul class="nospace">
                                	<li><a href="../index/index.php"><i class="fa fa-lg fa-home"></i></a></li>
                                	<li><a href="" data-toggle="modal" data-target="#modal_login_popup"><span class="glyphicon glyphicon-log-in"></span> Iniciar Sesion</a>
                                	<li><a href="" data-toggle="modal" data-target="#modal_register_popup"><span class="glyphicon glyphicon-log-in"></span> Registrase</a>
                        	</ul>
                	</div>
        	</div>
EOF;
if ($_SESSION["loggedinMIAPP"] == true){
	echo $strIn;
}
else{
	echo $strOut;
}
}

function header_pagina () {
	$str = <<<EOF
	<header>
		<section class="textos-header">
			<h1>¡Cursos de programación!</h1>
			<h2>Aprender a programar nunca fue tan fácil</h2>
		</section>
	</header>
EOF;
echo $str;
}

function main_pagina_in (){
	$str = <<<EOF
	<main>
		<section class="galeria">
			<div class="contenedor2">
				<h2 class="titulo">¿Qué quieres aprender?</h2>
				<div class="galeria-port">
					<div class="imagen-galeria">
						<img src="../img/imagen1.png" alt="">
						<div class="hover-galeria">
							<img src="../img/icono.png" alt="">
							<p>Saber más</p>
						</div>
					</div>
					<div class="imagen-galeria">
						<img src="../img/imagen2.jpg" alt="">
						<div class="hover-galeria">
							<a href="../view/c.php"> <img src="../img/icono.png" alt=""></a>
						</div>
					</div>
					<div class="imagen-galeria">
						<img src="../img/imagen3.jpg" alt="">
						<div class="hover-galeria">
							<img src="../img/icono.png" alt="">
							<p>Saber más</p>
						</div>
					</div>
					<div class="imagen-galeria">
						<img src="../img/imagen4.jpg" alt="">
						<div class="hover-galeria">
							<img src="../img/icono.png" alt="">
							<p>Saber más</p>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>
EOF;
echo $str;
}

function main_pagina_out(){
	$str = <<<EOF
	<main>
		<section class="informacion-personal">
			<div class="contenedor1">
				<h2 class="titulo">¿Quienes somos?</h2>
				<div class="contenedor-intormacion-personal">
					<img src="../img/portada2.jpg" alt="" class="imagen-about-us">
					<div class="contenido-textos">
						<h3><span>1</span>¿Cuál es el proposito de nuestra página?</h3>
						<p>Buscamos que los estudiantes desde temprano tengan una referencia sobre que es el programar
                                          y empiecen a desarrollar habilidades dajskdjaskdjkasjdksajdaskdjaskdjaskdsajdkas
						</p>
						<h3><span>2</span>Pregunta 2</h3>
						<p>asdasdsa</p>
						<h3><span>3</span>Pregunta 3</h3>
						<p>asdsadsar</p>
					</div>
				</div>
			</div>
		</section>
		<section class="about" id="servicio">
			<div class="contenedor2">
				<h3>¿Por qué debes trabajar con nosotros?.</h3>
				<p class="after">Conoce todos los beneficios</p>
				<div class="servicios">
					<div class="caja-servicios">
						<img src="../img/imagen5.png" alt="">
							<h4>Creatividad</h4>
							<p>Mejorarás tu capacidad para afrontar problemas.</p>
					</div>
					<div class="caja-servicios">
						<img src="../img/imagen6.png" alt="">
						<h4>Acceso libre</h4>
						<p>Tendrás acceso al material de estudio las 24 horas de día, sin costo alguno.</p>
					</div>
					<div class="caja-servicios">
						<img src="../img/imagen7.png" alt="">
						<h4>Conocimiento previo</h4>
						<p>Sólo necesitas tiempo y ganas aprender!</p>
					</div>
				</div>
			</div>
		</section>
		<section class="galeria">
			<div class="contenedor2">
				<h2 class="titulo">Lenguajes de Programación disponibles</h2>
				<div class="galeria-port">
					<div class="imagen-galeria">
						<img src="../img/imagen1.png" alt="">
						<div class="hover-galeria">
							<img src="../img/icono.png" alt="">
							<p>Saber más</p>
						</div>
					</div>
					<div class="imagen-galeria">
						<img src="../img/imagen2.jpg" alt="">
						<div class="hover-galeria">
							<img src="../img/icono.png" alt="">
							<p>Saber más</p>
						</div>
					</div>
					<div class="imagen-galeria">
						<img src="../img/imagen3.jpg" alt="">
						<div class="hover-galeria">
							<img src="../img/icono.png" alt="">
							<p>Saber más</p>
						</div>
					</div>
					<div class="imagen-galeria">
						<img src="../img/imagen4.jpg" alt="">
						<div class="hover-galeria">
							<img src="../img/icono.png" alt="">
							<p>Saber más</p>
						</div>
					</div>
				</div>
			</div>
		</section>
  </main>
EOF;
echo $str;
}

function pagina_c (){
	$str = <<<EOF
	<div id="wrapper">
    	<!-- Sidebar -->
		<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion"  id="accordionSidebar">
		
      		<!-- Sidebar - Brand -->
      		<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        		<div class="sidebar-brand-icon">
					<img src="../img/iconoc.png" alt="" style="width:100px;height:55px">
        		</div>	
     		</a>

      		<!-- Divider -->
      		<hr class="sidebar-divider my-1">

      		<!-- Nav Item - Dashboard -->
      		<li class="nav-item active">
        		<a class="nav-link" href="../view/index.php">
          		<i class="fas fa-undo-alt"></i>
          		<span>Inicio</span></a>
      		</li>

			<!-- Divider -->
      		<hr class="sidebar-divider">

      		<!-- Heading -->
      		<div class="sidebar-heading">
        		Opciones
      		</div>

			<!-- Nav Item - Pages Collapse Menu -->
      		<li class="nav-item">
        		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
					<i class="fas fa-book-open"></i>
					<span>Tutoriales</span>
				</a>
				<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
					<div class="bg-white py-2 collapse-inner rounded">
							<a class="collapse-item" href="buttons.html">Buttons</a>
							<a class="collapse-item" href="cards.html">Cards</a>
					</div>
				</div>
			</li>

			<!-- Nav Item - Utilities Collapse Menu -->
			<li class="nav-item">
				<a class="nav-link collapsed" href="../view/c_ejercicios.php" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
					<i class="fab fa-accessible-icon"></i>
					<span>Ejercicios</span>
				</a>
			</li>
		</ul>

		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
				<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
					<ul class="navbar-nav ml-auto">
						<div class="topbar-divider d-none d-sm-block"></div>
						<li class="nav-item dropdown no-arrow">
							<div id="encabezado" class="hoc clear">
								<div class="fl_right">
									<ul class="nospace">
										<li><a href="#"><i class="fa fa-lg fa-home"></i></a></li>
										<li><a class="dropdown-toggle" data-toggle="modal" href="#"><span class="glyphicon glyphicon-user"></span> {$_SESSION['username']} </a>
										<li><a href="../lib/logout.php"><span class="glyphicon glyphicon-log-in"></span> Cerrar</a></li>
									</ul>
								</div>
							</div>
						</li>
					</ul>
				</nav>

				<div class="container-fluid">
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
						<h1 class="h3 mb-0 text-gray-800">Lenguaje de Programación C</h1>
					</div>
					<div class="row">
						<div class="col-xl-8 col-lg-7">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
								</div>
								<div class="card-body">
									<div class="text-center">
										<img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="bucle-while-pciture.png" alt="">
									</div>
									<p>Add some quality, svg illustrations to your project courtesy of <a target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a constantly updated collection of beautiful svg images that you can use completely free and without attribution!</p>
									<a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on unDraw &rarr;</a>
									<div class="text-center">
										<iframe width="560" height="315" src="https://www.youtube.com/embed/iVt04mP5nc8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
									</div>
									<p>Add some quality, svg illustrations to your project courtesy of <a target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a constantly updated collection of beautiful svg images that you can use completely free and without attribution!</p>
									<a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on unDraw &rarr;</a>
								</div>
							</div>
						</div>
						<div class="col-xl-4 col-lg-5">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
								</div>
								<div class="card-body">
									<div class="text-center">
										<img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="img/undraw_posting_photo.svg" alt="">
									</div>
									<p>Add some quality, svg illustrations to your project courtesy of <a target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a constantly updated collection of beautiful svg images that you can use completely free and without attribution!</p>
									<a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on unDraw &rarr;</a>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 mb-4">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
								</div>
								<div class="card-body">
									<div class="text-center">
										<img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="img/undraw_posting_photo.svg" alt="">
									</div>
									<p>Add some quality, svg illustrations to your project courtesy of <a target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a constantly updated collection of beautiful svg images that you can use completely free and without attribution!</p>
									<a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on unDraw &rarr;</a>
								</div>
							</div>
						</div>
						<div class="col-lg-6 mb-4">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
								</div>
								<div class="card-body">
									<div class="text-center">
										<img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="img/undraw_posting_photo.svg" alt="">
									</div>
									<p>Add some quality, svg illustrations to your project courtesy of <a target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a constantly updated collection of beautiful svg images that you can use completely free and without attribution!</p>
									<a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on unDraw &rarr;</a>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="container-fluid">
							<h1 class="h3 mb-2 text-gray-800">Tables</h1>
          					<p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p>
							<div class="card shadow mb-4">
            					<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
            					</div>
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
											<thead>
                    							<tr>
													<th>Nombre del Ejercicio</th>
                      								<th>Link</th>
                      								<th>Dificultad</th>
													<th>Elementos a utilizar</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<th>Multiplicaciones</th>
													<th>https://www.youtube.com/watch?v=iVt04mP5nc8</th>
													<th>Facil</th>
													<th>while, for</th>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
EOF;
echo $str;
}

function c_ejercicios(){
	$str = <<<EOF
	<div id="wrapper">
    	<!-- Sidebar -->
		<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion"  id="accordionSidebar">
		
      		<!-- Sidebar - Brand -->
      		<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        		<div class="sidebar-brand-icon">
					<img src="../img/iconoc.png" alt="" style="width:100px;height:55px">
        		</div>	
     		</a>

      		<!-- Divider -->
      		<hr class="sidebar-divider my-1">

      		<!-- Nav Item - Dashboard -->
      		<li class="nav-item active">
        		<a class="nav-link" href="../view/index.php">
          		<i class="fas fa-undo-alt"></i>
          		<span>Inicio</span></a>
      		</li>

			<!-- Divider -->
      		<hr class="sidebar-divider">

      		<!-- Heading -->
      		<div class="sidebar-heading">
        		Opciones
      		</div>

			<!-- Nav Item - Pages Collapse Menu -->
      		<li class="nav-item">
        		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
					<i class="fas fa-book-open"></i>
					<span>Tutoriales</span>
				</a>
				<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
					<div class="bg-white py-2 collapse-inner rounded">
							<a class="collapse-item" href="buttons.html">Buttons</a>
							<a class="collapse-item" href="cards.html">Cards</a>
					</div>
				</div>
			</li>

			<!-- Nav Item - Utilities Collapse Menu -->
			<li class="nav-item">
				<a class="nav-link collapsed" href="../view/c_ejercicios.php" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
					<i class="fab fa-accessible-icon"></i>
					<span>Ejercicios</span>
				</a>
			</li>
		</ul>

		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
				<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
					<ul class="navbar-nav ml-auto">
						<div class="topbar-divider d-none d-sm-block"></div>
						<li class="nav-item dropdown no-arrow">
							<div id="encabezado" class="hoc clear">
								<div class="fl_right">
									<ul class="nospace">
										<li><a href="#"><i class="fa fa-lg fa-home"></i></a></li>
										<li><a class="dropdown-toggle" data-toggle="modal" href="#"><span class="glyphicon glyphicon-user"></span> {$_SESSION['username']} </a>
										<li><a href="../lib/logout.php"><span class="glyphicon glyphicon-log-in"></span> Cerrar</a></li>
									</ul>
								</div>
							</div>
						</li>
					</ul>
				</nav>

				<div class="container-fluid">
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
						<h1 class="h3 mb-0 text-gray-800">Lenguaje de Programación C</h1>
					</div>
					<div class="row">
						<div class="container-fluid">
							<h1 class="h3 mb-2 text-gray-800">Tables</h1>
          					<p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p>
							<div class="card shadow mb-4">
            					<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
            					</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="tabla-ejercicios" class="table table-bordered" id="dataTable" width="100%" cellspacing="0"></table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
EOF;
echo $str;
}

function footer(){
	$str = <<<EOF
	<footer>
		<div class="contenedor-footer">
			<div class="content-foo">
				<img src="../img/insta.png" alt="">
				<p>wwww.nose.com</p>
			</div>
			<div class="content-foo">
				<img src="../img/face.png" alt="">
				<p>www.nose.com</p>
			</div>
			<div class="content-foo">
				<img src="../img/gmail.png" alt="">
				<p>chichadioss23@gmail.com</p>
			</div>
		</div>
	</footer>
	<script src="../extras/modernizr/modernizr-2.8.3.min.js"></script>
	<script src="../extras/jquery/jquery-3.2.1.min.js"></script>
	<script src="../extras/bootstrap/bootstrap.min.js"></script>
	<script src="../extras/sweetalert2/sweetalert2.min.js"></script>
	<script src="../extras/jquery/jquery-ui.min.js"></script>
	<script src="../controller/common.js"></script>
EOF;
echo $str;
}

function footer_paginas(){
	$str = <<<EOF
	<script src="../extras/sweetalert2/sweetalert2.min.js"></script>
	<script src="../vendor/jquery/jquery.min.js"></script>
	<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
	<script src="../js/sb-admin-2.min.js"></script>
	<script src="../vendor/datatables/jquery.dataTables.min.js"></script>
	<script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
	<script src="../js/demo/datatables-demo.js"></script>
	<script src="../controller/tabla_ejercicio.js"></script>
EOF;
echo $str;
}


?>
