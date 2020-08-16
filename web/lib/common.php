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
	<link href="../css/jquery.dataTables.min.css" rel="stylesheet">
	<link href="../css/buttons.dataTables.min.css" rel="stylesheet">
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
					<li id = "usuario"><span class="glyphicon glyphicon-user"></span> {$_SESSION['username']} 
					<li><a href="../lib/logout.php"><span class="glyphicon glyphicon-log-in"></span> Cerrar Sesión</a></li>
				</ul>
			</div>
		</div>
EOF;
	$strOut = <<<EOF
	<div class="navbar navbar-light" style="background-color: hsla(195, 46%, 5%, 0.1);">
        	<div id="encabezado" class="hoc clear">
                	<div class="fl_right">
                        	<ul class="nospace">
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
			<div class="contenedor1">
				<h2 class="titulo">¿Qué quieres aprender?</h2>
				<div class="galeria-port">
					<div class="imagen-galeria">
						<img src="../img/imagen2.jpg" alt="">
						<div class="hover-galeria">
							<a href="../view/lenguaje.php?lenguaje=1&nombre_lenguaje=C&ruta_imagen=../img/c.png"> <img src="../img/icono.png" alt=""></a>
						</div>
					</div>
					<div class="imagen-galeria">
						<img src="../img/imagen3.jpg" alt="">
						<div class="hover-galeria">
							<a href="../view/lenguaje.php?lenguaje=2&nombre_lenguaje=C&ruta_imagen=../img/cpp.png"> <img src="../img/icono.png" alt=""></a>
						</div>
					</div>
					<div class="imagen-galeria">
						<img src="../img/imagen1.png" alt="">
						<div class="hover-galeria">
							<a href="../view/lenguaje.php?lenguaje=3&nombre_lenguaje=Python&ruta_imagen=../img/python.png"> <img src="../img/icono.png" alt=""></a>
						</div>
					</div>
					<div class="imagen-galeria">
						<img src="../img/imagen4.jpg" alt="">
						<div class="hover-galeria">
							<a href="../view/lenguaje.php?lenguaje=4&nombre_lenguaje=Java&ruta_imagen=../img/java.png"> <img src="../img/icono.png" alt=""></a>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section>
			<div class="contenedor2">
				<h2 class="titulo">Cantidad de actividades por lenguaje</h2>
				<div class="row">
				   <div class="col-md-12">	 
					   <canvas id="miGrafico1"></canvas> 
				   </div>
				</div>

			</div>
		</section>
		<section>
			<div class="contenedor2">
				<h2 class="titulo">Cantidad de actividades según la dificultad</h2>
				<div class="row">
				   <div class="col-md-12">	 
					   <canvas id="miGrafico2"></canvas> 
							  
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
						<h3><span>1</span>¿Cuál es nuestro propósito?</h3>
						<p>Buscamos que los estudiantes comiencen a ejercitar sus habilidades de programación desde temprana edad, 
						con ejercicios escritos y revisados por nuestros administradores
						</p>
						<h3><span>2</span>¿Cuánto puedes aprender con nosotros?</h3>
						<p>El límite sólo se encuentra en cuánto quieras aprender. Los ejercicios, tutoriales y videos siempre estarán disponibles para ti</p>
						<h3><span>3</span>¿Qué necesitas para mejorar?</h3>
						<p>Sólo necesitas constancia y ganas de aprender.</p>
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
					</div>
					<div class="imagen-galeria">
						<img src="../img/imagen2.jpg" alt="">
					</div>
					<div class="imagen-galeria">
						<img src="../img/imagen3.jpg" alt="">
					</div>
					<div class="imagen-galeria">
						<img src="../img/imagen4.jpg" alt="">
					</div>
				</div>
			</div>
		</section>
  </main>
EOF;
echo $str;
}

function sidebar (){
	$str = <<<EOF
	<div id="wrapper">
    	<!-- Sidebar -->
		<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion"  id="accordionSidebar">
		
      		<!-- Sidebar - Brand -->
      		<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        		<div class="sidebar-brand-icon">
					
					<img src="{$_SESSION['ruta_imagen']}" alt="" style="width:58px;height:58px">
        		</div>	
     		</a>

      		<!-- Divider -->
      		<hr class="sidebar-divider my-1">

      		<!-- Nav Item - Dashboard -->
      		<li class="nav-item">
        		<a class="nav-link" href="../view/lenguaje.php?lenguaje={$_SESSION['lenguaje']}&nombre_lenguaje={$_SESSION['nombre_lenguaje']}&ruta_imagen={$_SESSION['ruta_imagen']}">
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
				<a class="nav-link collapsed" href="../view/tutoriales.php" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
					<i class="fas fa-book-open"></i>
					<span>Tutoriales</span>
				</a>
			</li>

			<!-- Nav Item - Utilities Collapse Menu -->
			<li class="nav-item">
				<a class="nav-link collapsed" href="../view/ejercicios.php" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
					<i class="fab fa-accessible-icon"></i>
					<span>Ejercicios</span>
				</a>
			</li>
			
			
			
EOF;

echo $str;
}

function opcion_soluciones(){
	$str_administrador = <<<EOF
			<!-- Nav Item - Utilities Collapse Menu -->
			<li class="nav-item">
				<a class="nav-link collapsed" href="../view/soluciones.php" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
					<i class="fab fa-accessible-icon"></i>
					<span>Soluciones</span>
				</a>
			</li>
			
		</ul>
EOF;
	$str_alumno = <<<EOF
			
		</ul>

EOF;
if ($_SESSION["tipo_usuario"] == 1){
	echo $str_administrador;
}
if ($_SESSION["tipo_usuario"] == 2){
	echo $str_alumno;
}
}


function navbar_lenguaje(){
	$str = <<<EOF
		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
				<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
					<ul class="navbar-nav ml-auto">
						<div class="topbar-divider d-none d-sm-block"></div>
						<li class="nav-item dropdown no-arrow">
							<div id="encabezado" class="hoc clear">
								<div class="fl_right">
									<ul class="nospace">
										<li><a href="../view/index.php"><i class="fa fa-lg fa-home"></i></a></li>
										<li id = "usuario"><span class="glyphicon glyphicon-user"></span> {$_SESSION['username']} </a>
										<li><a href="../lib/logout.php"><span class="glyphicon glyphicon-log-in"></span> Cerrar</a></li>
									</ul>
								</div>
							</div>
						</li>
					</ul>
				</nav>
				
				<div class="container-fluid">
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
						<h1 class="h3 mb-0 text-gray-800">Lenguaje de Programación {$_SESSION['nombre_lenguaje']}</h1>
					</div>
EOF;
echo $str;
}

function pagina_lenguaje (){
	$str = <<<EOF

					<div class="row">
						<div class="col-xl-8 col-lg-7">
							<div class="card shadow mb-4">
								<div class="card-body">
									<canvas id="myChart" width=25 height="20"></canvas>			  
								</div>
							</div>
						</div>
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
				</div>
			</div>
		</div>
	</div>
EOF;
echo $str;
}

function ejercicios(){
	$str_alumno = <<<EOF
					<div class="row">
						<div class="container-fluid">
							<div class="card shadow mb-4">
            					<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Repositorio de Ejerciciciosxdd</h6>
            					</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="tabla-ejercicios-alumno" class="table table-bordered" id="dataTable" width="100%" cellspacing="0"></table>
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
	$str_administrador = <<<EOF
					<div class="row">
						<div class="container-fluid">
							<div class="card shadow mb-4">
            					<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Repositorio de Ejerciciciosxddxd</h6>
            					</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="tabla-ejercicios-administrador" class="table table-bordered" id="dataTable" width="100%" cellspacing="0"></table>
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
if ($_SESSION["tipo_usuario"] == 1){
	echo $str_administrador;
}
if ($_SESSION["tipo_usuario"] == 2){
	echo $str_alumno;
}
}

function tutoriales(){
	$str_alumno = <<<EOF
					<div class="row">
						<div class="container-fluid">
							<div class="card shadow mb-4">
            					<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Repositorio de Tutoriales</h6>
            					</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="tabla-tutoriales-alumno" class="table table-bordered" id="dataTable" width="100%" cellspacing="0"></table>
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
	$str_administrador = <<<EOF
					<div class="row">
						<div class="container-fluid">
							<div class="card shadow mb-4">
            					<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Repositorio de Tutoriales</h6>
            					</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="tabla-tutoriales-administrador" class="table table-bordered" id="dataTable" width="100%" cellspacing="0"></table>
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

if ($_SESSION["tipo_usuario"] == 1){
	echo $str_administrador;
}
if ($_SESSION["tipo_usuario"] == 2){
	echo $str_alumno;
}
}

function soluciones(){
	$str = <<<EOF
					<div class="row">
						<div class="container-fluid">
							<div class="card shadow mb-4">
            					<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Repositorio de respuestas</h6>
            					</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="tabla-soluciones" class="table table-bordered" id="dataTable" width="100%" cellspacing="0"></table>
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
if ($_SESSION["tipo_usuario"] == 1){
	echo $str;
}
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
	<script src="../pdf/jquery-3.3.1.slim.min.js"></script>
	<script src="../extras/sweetalert2/sweetalert2.min.js"></script>
	<script src="../vendor/jquery/jquery.min.js"></script>
	<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
	<script src="../js/sb-admin-2.min.js"></script>
	<script src="../vendor/datatables/jquery.dataTables.min.js"></script>
	<script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
	<script src="../js/demo/datatables-demo.js"></script>
	

	
	<script src="../pdf/dataTables.buttons.min.js"></script>
	<script src="../pdf/jszip.min.js"></script>
	<script src="../pdf/pdfmake.min.js"></script>
	<script src="../pdf/vfs_fonts.js"></script>
	<script src="../pdf/buttons.html5.min.js"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js" integrity="sha256-JG6hsuMjFnQ2spWq0UiaDRJBaarzhFbUxiUTxQDA9Lk=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js" integrity="sha256-XF29CBwU1MWLaGEnsELogU6Y6rcc5nCkhhx89nFMIDQ=" crossorigin="anonymous"></script>
	<script src="../vendor/chart.js/Chart.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js" integrity="sha256-CfcERD4Ov4+lKbWbYqXD6aFM9M51gN4GUEtDhkWABMo=" crossorigin="anonymous"></script>

	<script src="../controller/grafico.js"></script>
	

EOF;
echo $str;
}



?>
