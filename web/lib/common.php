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
				<h2 class="titulo">Actividades según lenguaje de programación</h2>
				<div class="row">
				   <div class="col-md-12">	 
					   <canvas id="miGrafico1"></canvas> 
				   </div>
				</div>

			</div>
		</section>
		<section>
			<div class="contenedor2">
				<h2 class="titulo">Actividades según dificultad</h2>
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
						<img src="../img/imagen2.jpg" alt="">
					</div>
					<div class="imagen-galeria">
						<img src="../img/imagen3.jpg" alt="">
					</div>
					<div class="imagen-galeria">
						<img src="../img/imagen1.png" alt="">
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
										<li><a href="../lib/logout.php"><span class="glyphicon glyphicon-log-in"></span>Cerrar Sesión</a></li>
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
	$C = <<<EOF
					<div class="row">
						<div class="col-xl-8 col-lg-7">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">¿Qué es?</h6>
								</div>
								<div class="card-body">
									<p>
									C es un lenguaje de programación de propósito general originalmente desarrollado por Dennis Ritchie entre 1969 y 1972 en los Laboratorios Bell, como evolución del anterior lenguaje B, a su vez basado en BCPL.
									</p>
								</div>
							</div>
						</div>
						<div class="col-xl-4 col-lg-5">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Usos</h6>
								</div>
								<div class="card-body">
									<p>C es utilizado, entre otras cosas, para el desarrollo de sistemas operativos. Sistemas operativos como Unix y Linux son escritos en C.
									</br>C además es el precursos de muchos lenguajes de alto nivel como Python, Perl, PHP y Ruby.</p>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 mb-4">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Ventajas</h6>
								</div>
								<div class="card-body">
									<ul>
										<li>Minimalista, sencillo.
										<li>Acceso a memoria directamente, sin "capas de protección" aparte del Sistema Operativo.
										<li>Rendimiento increíble debido a que su código se compila directamente a instrucciones de procesador y a la gran cantidad de optimizaciones que hace el compilador.
									</ul>
								</div>
							</div>
						</div>
						<div class="col-lg-6 mb-4">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Desventajas</h6>
								</div>
								<div class="card-body">
								<ul>
									<li>Por el tipo de estructura: No es un lenguaje visual, no puede ser deducido de forma intuitiva, como por ejemplo el Visual Basic.</li>
									<li>Encapsulación: Para el uso de funciones anidadas necesita de extensiones. No tiene instrucciones de entrada y salida, ni para el manejo de cadenas de caracteres. </li>
									<li>Por la forma de procesamiento: Carece de instucciones que faciliten a los desarrolladores la programación multihilo.</li>
									<li>No cuenta con instrucciones para programación dirigida a objetos.</li>
								</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
EOF;
$CPP = <<<EOF
					<div class="row">
						<div class="col-xl-8 col-lg-7">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">¿Qué es?</h6>
								</div>
								<div class="card-body">
									<p>
										Es un lenguaje de programación de propósito general creado por Bjarne Stroustrup como una extensión del lenguaje de programación C, o "C con clases".</br>
										El lenguaje se ha expandido significativamente con el tiempo, y C ++ moderno ahora tiene características funcionales, genéricas y orientadas a objetos, además de facilidades para la manipulación de memoria de bajo nivel.</br>
										Casi siempre se implementa como un lenguaje compilado, y muchos proveedores proporcionan compiladores C++, incluidos Free Software Foundation, LLVM, Microsoft, Intel, Oracle e IBM, por lo que está disponible en muchas plataformas.
										</p>
								</div>
							</div>
						</div>
						<div class="col-xl-4 col-lg-5">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Usos</h6>
								</div>
								<div class="card-body">
									<p>C++ es usado por muchas aplicaciones que tienen como prioridad la optimización de recursos y el buen control sobre la memoria, por supuesto, sin dejar de lado la escalabilidad, modularidad y la distribución por varias plataformas.</br>
									C++ es tan poderoso que se utiliza en muchos proyectos importantes: TensorFlow para Machine Learning, V8 como el motor de JavaScript para Google Chrome y Node.js, Electron para crear aplicaciones de escritorio con HTML, CSS y JavaScript, entre otras.</p>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 mb-4">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Ventajas</h6>
								</div>
								<div class="card-body">
									<ul>
										<li>Lenguaje de programación orientado a objetos.</li>
										<li>Es muy potente en lo que se refiere a creación de sistemas complejos un lenguaje muy robusto.</li>
										<li>Permite elaborar aplicaciones cencillas como un"hello world".</li>
										<li>Existen muchas algoritmos cuyo pseudocodigo se encuentran desarrollados en C++</li>
										<li>Es ideal para programar sistemas operativos</li>
										<li>Hay muchos codigos</li>
										</ul>
								</div>
							</div>
						</div>
						<div class="col-lg-6 mb-4">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Desventajas</h6>
								</div>
								<div class="card-body">
									<ul>
										<li>Uso de DLLs (librerías dinámicas) muy complejo. Java y .Net han evolucionado estos conceptos manipulando las DLLs mediante los frameworks que proveen. En cambio, en C++ el desarrollador debe encargarse de cargar y liberar de memoria estas librerías, y correr los riesgos por el manejo de esta memoria.</li>
										<li>Elaborar un sistema en C++ es como construir un rascacielos: tiene buen soporte y es robusto, pero si existen errores en los pisos inferiores toda la parte superior se viene abajo terriblemente.</li>
										<li>Manejo de punteros y memoria respecto a ello. Claro, esta también es una gran ventaja porque permite un mejor control de la memoria y una buena administración de recursos de computadora, pero la inexperiencia de los desarrolladores o la pérdida de costumbre con este tipo de variables (sobre todo cuando son dobles o triples punteros, inclusive de mayor orden) los lleva al desastre.</li>
										<li>No es recomendable para desarrollo de páginas Web.</li>
										<li>Uno de los motivos que más quebraderos de cabeza ha traído a los programadores en lenguaje C es la correcta liberación de la memoria. Si se te olvida liberar algo de memoria, cuando el programa termina esa memoria se queda ocupada (aunque el programa ya no siga ejecutándose), son los famosos “Memory Leaks”.</li>
										<li>Es difícil (o por lo menos no es tan sencillo como en otros lenguajes) programar bases de datos</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
EOF;

$PYTHON = <<<EOF
					<div class="row">
						<div class="col-xl-8 col-lg-7">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">¿Qué es?</h6>
								</div>
								<div class="card-body">
									<p>
									Python es un lenguaje de programación interpretado cuya filosofía hace hincapié en la legibilidad de su código.</br>
									Se trata de un lenguaje de programación multiparadigma, ya que soporta orientación a objetos, programación imperativa y, en menor medida, programación funcional.</br>
									Es un lenguaje interpretado, dinámico y multiplataforma.	
									</p>
								</div>
							</div>
						</div>
						<div class="col-xl-4 col-lg-5">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Usos</h6>
								</div>
								<div class="card-body">
									<p>
									Puedes programar en varios estilos dentro de la llamada programación multiparadigma: estructurada, funcional, orientada a objetos o a aspectos. No obstante, sus principales beneficios van más allá de esas posibilidades del desarrollo web. Este software es muy versátil y útil para la automatización de procesos con el fin de ahorrarte complicaciones.
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 mb-4">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Ventajas</h6>
								</div>
								<div class="card-body">
									<ul>
										<li>Simplificado y rápido: Este lenguaje simplifica mucho la programación. Es un gran lenguaje para scripting, si usted requiere algo rápido, con unas cuantas líneas ya está resuelto.</li>
										<li>Elegante y flexible: El lenguaje le da muchas herramientas, si usted quiere listas de varios tipo de datos, no hace falta que declares cada tipo de datos. Es un lenguaje tan flexible que no requiere preocuparse tanto por los detalles.</li>
										<li>Programación sana y productiva: Es sencillo de aprender, direccionado a las reglas perfectas, le hace como dependiente de mejorar, cumplir las reglas, el uso de las lineas, de variables”.</br>Ademas es un lenguaje que fue hecho con productividad en mente, es decir, Python le hace ser mas productivo, le permite entregar en los tiempos que me requieren.</li>
										<li>Ordenado y limpio: El orden que mantiene Python, es de lo que más le gusta a sus usuarios, es muy legible, cualquier otro programador lo puede leer y trabajar sobre el programa escrito en Python. Los módulos están bien organizados, a diferencia de otros lenguajes.</li>
										<li>Portable: Es un lenguaje muy portable en comparación con otros lenguajes. La filosofía de baterías incluidas, son las librerías que más usted necesita al día a día de programación, ya están dentro del interprete, no tiene la necesidad de instalarlas adicionalmente con en otros lenguajes.</li>
										<li>Comunidad: Algo muy importante para el desarrollo de un lenguaje es la comunidad, la misma comunidad de Python cuida el lenguaje y casi todas las actualizaciones se hacen de manera democrática.</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-lg-6 mb-4">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Desventajas</h6>
								</div>
								<div class="card-body">
									<ul>
										<li>Curva de aprendizaje: La “curva de aprendizaje cuando ya estás en la parte web no es tan sencilla”.</li>
										<li>Hosting: La mayoría de los servidores no tienen soporte a Python, y si lo soportan, la configuración es un poco difícil.</li>
										<li>Librerías incluidas: Algunas librerías que trae por defecto no son del gusto de amplio de la comunidad, y optan a usar librerías de terceros.</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
EOF;

$JAVA = <<<EOF
					<div class="row">
						<div class="col-xl-8 col-lg-7">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">¿Qué es?</h6>
								</div>
								<div class="card-body">
									<p>
										Java es un lenguaje de programación de propósito general basado en clases, orientado a objetos y diseñado para tener la menor cantidad posible de dependencias de implementación. </br>
										Está destinado a permitir que los desarrolladores de aplicaciones escriban una vez, se ejecuten en cualquier lugar (WORA), lo que significa que el código Java compilado puede ejecutarse en todas las plataformas que admiten Java sin necesidad de volver a compilar
									</p>
								</div>
							</div>
						</div>
						<div class="col-xl-4 col-lg-5">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Usos</h6>
								</div>
								<div class="card-body">
									<p>
										Java sirve para crear aplicaciones y procesos en una gran diversidad de dispositivos. Se base en programación orientada a objetivos, permite ejecutar un mismo programa en diversos sistemas operativos y ejecutar el código en sistemas remotos de manera segura.</br>
										Su ámbito de aplicación es tan amplio que Java se utiliza tanto en móviles como en electrodomésticos. Muchos programadores también utilizan este lenguaje para crear pequeñas aplicaciones que se insertan en el código HTML de una página para que pueda ser ejecutada desde un navegador.
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 mb-4">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Ventajas</h6>
								</div>
								<div class="card-body">
									<ul>
										<li>La principal característica de Java es que es independiente de la plataforma (multiplataforma). Esto significa que cuando estás programando en Java, no necesitas conocer a priori el tipo de ordenador o el sistema operativo para el que estás programando. Puedes ejecutar EL MISMO programa en un PC con Windows, otro con Linux, en un Servidor SUN con sistema operativo Solaris, o en un teléfono móvil de última generación.</li>
										<li>El lenguaje Java es orientado a objetos. El paradigma de programación orientada a objetos supuso un gran avance en el desarrollo de aplicaciones, ya que es capaz de acercar la forma de programar a la forma de pensar del ser humano.</li>
										<li>En java no existen problemas con la liberacion de memoria en el sistema: En Java decidieron romper con el sistema tradicional de liberación de memoria, haciendo que el programador ya no fuese el responsable de esa tarea. Así, lo único que necesita hacer el programador es solicitar la memoria al sistema.</li>
										<li>El lenguaje Java es relativamente fácil de aprender comparado con otros.</li>
										<li>Librerías Estándar: Una de las características que más potencia aporta al lenguaje Java es que viene acompañado de una serie de librerías estándar para realizar multitud de operaciones comunes a la hora de programar. Es el llamado Java API, que incluye tres bloques básicos.</li>
										<li>Hoy en día existen excelentes editores (IDEs) que aportan multitud de ayudas a la programación, haciendo que el desarrollo sea más fluido y cómodo.</li>
										<li>Una de las soluciones más elegantes propuestas por el lenguaje Java a uno de los problemas recurrentes en otros lenguajes de programación es la gestión de errores a través de excepciones. en C o C++ no existe un mecanismo específico para la gestión de los errores que puedan producirse en el código.</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-lg-6 mb-4">
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Desventajas</h6>
								</div>
								<div class="card-body">
									<ul>
										<li>Al tratarse de un lenguaje interpretado, el rendimiento en la ejecución de programas suele ser un poco menor.</li>
										<li>Al contrario que los programas compilados a código nativo, sólo podemos ejecutar un programa en Java si disponemos de una máquina virtual (JVM), sin este simulador no podremos ejecutar ningún programa escrito en Java.</li>
										<li>Aunque java es un lenguaje relativamente sencillo de manejar, no es recomendado implementarlo con personas que apenas se unen a este mundo, ya que Java se basa en una filosofía de programación (la orientación a objetos) que es una evolución de otras formas de entender la programación mas sencillas de enseñar e implementar.</li>
										<li>Su sintaxis comparada con C# o Python parece para algunos bastante engorrosa y al contrario que su semejante en .NET, C#, es un lenguaje que evoluciona muy lentamente.</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
EOF;

if ($_SESSION["lenguaje"] == 1){
	echo $C;
}
if ($_SESSION["lenguaje"] == 2){
	echo $CPP;
}
if ($_SESSION["lenguaje"] == 3){
	echo $PYTHON;
}
if ($_SESSION["lenguaje"] == 4){	
	echo $JAVA;
}
}

function ejercicios(){
	$str_alumno = <<<EOF
					<div class="row">
						<div class="container-fluid">
							<div class="card shadow mb-4">
            					<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary">Ejercicicios</h6>
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
									<h6 class="m-0 font-weight-bold text-primary">Ejercicicios</h6>
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
									<h6 class="m-0 font-weight-bold text-primary">Tutoriales</h6>
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
									<h6 class="m-0 font-weight-bold text-primary">Tutoriales</h6>
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
									<h6 class="m-0 font-weight-bold text-primary">Soluciones</h6>
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