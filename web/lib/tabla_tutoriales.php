<?php
session_start();
require_once("../lib/common.php");

if (isset($_REQUEST['accion'])) {
  switch ($_REQUEST['accion']) {     
    case 1:
      # select
      $conn = conectarBD();
      seleccionar ($conn);
      break;
    case 2:
      # insert
      $conn = conectarBD();
      insertar ($conn);
      break;       
    case 3:
      # select where = ?
      $conn = conectarBD();
      seleccionarUno ($conn);
      break;      
    case 4:
      # update where = ?
      $conn = conectarBD();
      actualizar ($conn);
      break;    
    case 5:
      # delete where = ?
      $conn = conectarBD();
      eliminar ($conn);
      break;
    case 6:
      # delete where = ?
      $conn = conectarBD();
      select_instrucciones ($conn);
      break;
  }  
}

function insertar ($conn) {
  $nombre_tutorial = $_REQUEST['nombre_tutorial'];
  $instrucciones = $_REQUEST['instrucciones'];
  $link_video = $_REQUEST['link-video'];
  $lenguaje = $_SESSION['lenguaje'];

  $correo = $_SESSION['correo'];
  $fecha = date_create();
  $str = "../archivos/tutoriales/". $correo. "_". $nombre_tutorial . "_". date_timestamp_get($fecha). ".txt";
  $ar = fopen($str, "a");
  fwrite($ar, $instrucciones);
  fclose($ar);
  $sql= "insert into tutorial (id_lenguaje, nombre_tutorial, instrucciones, link_video) values (:lenguaje, :nombre_tutorial, :instrucciones, :link_video);";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':nombre_tutorial', $nombre_tutorial); 
  $stmt->bindValue(':instrucciones', $str);
  $stmt->bindValue(':link_video', $link_video);
  $stmt->bindValue(':lenguaje', $lenguaje);
  
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"][0]));
}


function seleccionar ($conn) {
  $lenguaje = $_SESSION['lenguaje'];
  $sql= "select tutorial.id_tutorial, tutorial.nombre_tutorial, lenguaje.nombre as lenguaje, tutorial.instrucciones, tutorial.link_video from tutorial join lenguaje on lenguaje.id_lenguaje = tutorial.id_lenguaje where tutorial.id_lenguaje = :lenguaje order by tutorial.id_tutorial;";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':lenguaje', $lenguaje);
    
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"]));
}

function actualizar ($conn) {
  $id_tutorial = $_REQUEST['id_tutorial'];
  $nombre_tutorial = $_REQUEST['nombre_tutorial'];
  $instrucciones = $_REQUEST['instrucciones'];
  $link_video = $_REQUEST['link-video'];
  
  $ruta_anterior = "select instrucciones from tutorial where id_tutorial = :id_tutorial;";
  $stmt = $conn->prepare($ruta_anterior);
  $stmt->bindValue(':id_tutorial', $id_tutorial); 
  $res = ejecutarSQL($stmt); 
  
  unlink($res["data"][0]["instrucciones"]);
  
  $correo = $_SESSION['correo'];
  $fecha = date_create();
  $str = "../archivos/tutoriales/". $correo. "_". $nombre_tutorial . "_". date_timestamp_get($fecha). ".txt";
  $ar = fopen($str, "a");
  write($ar, $instrucciones);
  fclose($ar);
  
  $sql= "update tutorial set nombre_tutorial = :nombre_tutorial, instrucciones = :instrucciones, link_video = :link_video where id_tutorial = :id_tutorial;";
  
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':id_tutorial', $id_tutorial); 
  $stmt->bindValue(':nombre_tutorial', $nombre_tutorial);  
  $stmt->bindValue(':instrucciones', $instrucciones);
  $stmt->bindValue(':link_video', $link_video);
    
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"][0]));
}

function seleccionarUno ($conn) {
  $id_tutorial = $_REQUEST['id_tutorial'];
  $sql= "select id_tutorial, id_lenguaje, nombre_tutorial, instrucciones, link_video from tutorial where id_tutorial = :id_tutorial order by nombre_tutorial asc;";
  
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':id_tutorial', $id_tutorial);  
    
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"][0]));
}


function eliminar ($conn) {
  $id_tutorial = $_REQUEST['id_tutorial'];
  $sql= "delete from tutorial where id_tutorial = :id_tutorial;";
  
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':id_tutorial', $id_tutorial);  
    
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"]));
}

function select_instrucciones ($conn) {
  $id_tutorial = $_REQUEST['id_tutorial'];
  $sql= "select nombre_tutorial, instrucciones from tutorial where id_tutorial = :id_tutorial;";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':id_tutorial', $id_tutorial);  
    
  $res = ejecutarSQL($stmt);  
  $texto = fopen($res["data"][0]["instrucciones"],"r");
  while(!feof($texto)){
    $linea = $linea . fgets($texto) . "<br>";
  }
  fclose($texto);
  
  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"][0], "texto"=>$linea));
}

?>
