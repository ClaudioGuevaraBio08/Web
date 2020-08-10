<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

setlocale (LC_TIME, "es_ES.utf8");

$file = "../../conf.ini";
$ini = parse_ini_file($file, false);

date_default_timezone_set($ini['timezone']);

function validarSesion() {
  if (isset($_SESSION["loggedinMIAPP"])) {
    if ($_SESSION["loggedinMIAPP"] != true) {
      logout();
    }
  } else {
    logout();
  }
}

function logout() {
  header('Location: ../lib/logout.php');
}

function encriptar($string)
{
  $encrypt_method = $GLOBALS['ini']['encrypt_method'];
  $secret_key = $GLOBALS['ini']['secret_key'];
  $secret_iv = $GLOBALS['ini']['secret_iv'];
  $encrypted = openssl_encrypt($string, $encrypt_method, $secret_key, 0, $secret_iv);
  $encrypted = base64_encode($encrypted);
  return $encrypted;
}

function desencriptar($string)
{
  $encrypt_method = $GLOBALS['ini']['encrypt_method'];
  $secret_key = $GLOBALS['ini']['secret_key'];
  $secret_iv = $GLOBALS['ini']['secret_iv'];
  $decrypted = openssl_decrypt(base64_decode($string), $encrypt_method, $secret_key, 0, $secret_iv);
  return $decrypted;
}

/*
 * devuelve ID conexión a la DB
 */
function conectarBD () {
  $i = $GLOBALS['ini'];
  $host = $i["host"];
  $db = $i["db"];
  $user = $i["user"];
  $password = $i["password"];
  $port = $i["port"];

  // conectar a la base de datos
  try {
    $conn = new PDO('pgsql:host='.$host.';port='.$port.';dbname='.$db.';user='.$user.';password='.$password);
    return $conn;

  } catch (PDOException $e) {
    //echo $e->getMessage();
    return false;
  }
}

function ejecutarSQL ($stmt) {
  $res = array();
  $res["success"] = false;
  $res["msg"] = "Error SQL";
  $res["data"] = null;

  try {
    if ($stmt->execute()) {
      $res["data"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $res["msg"] = "éxito";
      $res["success"] = true;
    } else {

      /* https://www.postgresql.org/docs/12/errcodes-appendix.html */
      $arrayError['23503'] = "El registro no se puede eliminar porque tiene información asociada.";
      $res["msg"] = "Error SQL, Contacte al soporte";
    }

  } catch (PDOException $e) {
    $res["msg"] = $e->getMessage();
  }

  return $res;
}

function head () {
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
  echo $str;
}

function navbar () {
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
          <li><a href="index.php"><i class="fa fa-lg fa-home"></i></a></li
          <li><a href="" data-toggle="modal" data-target="#modal_login_popup"><span class="glyphicon glyphicon-log-in"></span> Iniciar Sesion</a>
          <li><a href="" data-toggle="modal" data-target="#modal_register_popup"><span class="glyphicon glyphicon-log-in"></span> Registrase</a>
        </ul>
      </div>
    </div>
  EOF;

  if ($_SESSION["loggedinMIAPP"] == true) {
    echo $strIn;
  } else {
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

function main_pagina_in () {
  $str = <<<EOF
  <main>
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

function main_pagina_out () {
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

function footer() {
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
    <h2 class="titulo-final">&copy; Patrocinador | No tengo :(</h2>
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
?>