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
  }  
}

function insertar ($conn) {
  $lenguaje = $_SESSION['lenguaje'];
  $actividad = $_REQUEST['lista_actividad'];
  $solucion = $_REQUEST['solucion'];
  
  $correo = $_SESSION['correo'];
  $fecha = date_create();
  $str = "../archivos/soluciones/". $correo. "_". $actividad . "_". date_timestamp_get($fecha). ".txt";
  $ar = fopen($str, "a");
  fwrite($ar, $solucion);
  fclose($ar);
  $sql= "insert into solucion (id_lenguaje, id_actividad, solucion) values (:lenguaje, :actividad, :solucion);";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':lenguaje', $lenguaje); 
  $stmt->bindValue(':actividad', $actividad);
  $stmt->bindValue(':solucion', $solucion);
  
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"][0]));
}


function seleccionar ($conn) {
  $lenguaje = $_SESSION['lenguaje'];
  $sql= "select lenguaje.nombre as nombre_lenguaje, solucion.id_actividad, solucion.solucion from solucion join lenguaje on lenguaje.id_lenguaje = solucion.id_lenguaje where solucion.id_lenguaje = :lenguaje order by solucion.id_lenguaje;";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':lenguaje', $lenguaje);
    
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"]));
}

function actualizar ($conn) {
  $actividad = $_REQUEST['id_actividad'];
  $lenguaje = $_SESSION['lenguaje'];
  $solucion = $_REQUEST['solucionupdate'];
  
  #$id_actividad = $_REQUEST['id_actividad'];
  #$nombre = $_REQUEST['nombre'];
  #$dificultad = $_REQUEST['lista_dificultad'];
  #$enunciado = $_REQUEST['enunciado'];
  #$correo = $_SESSION['correo'];
  #$fecha = date_create();
  #$str = "../ejercicios/". $correo. "_". $nombre. "_". date_timestamp_get($fecha). ".txt";
  #$ar = fopen($str, "a");
  #fwrite($ar, $enunciado);
  #fclose($ar);
  
  $sql= "update solucion set solucion = :solucion where id_actividad = :actividad and id_lenguaje = :lenguaje;";
  
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':actividad', $actividad); 
  $stmt->bindValue(':lenguaje', $lenguaje);  
  $stmt->bindValue(':solucion', $solucion);
    
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"][0]));
}

function seleccionarUno ($conn) {
  $actividad = $_REQUEST['id_actividad'];
  $lenguaje = $_SESSION['lenguaje'];
  
  $sql= "select id_lenguaje, id_actividad, solucion from solucion where id_actividad = :actividad and id_lenguaje = :lenguaje order by solucion asc;";
  
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':actividad', $actividad);
  $stmt->bindValue(':lenguaje', $lenguaje);   
    
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"][0]));
}

function eliminar ($conn) {
  $id_actividad = $_REQUEST['id_actividad'];
  $lenguaje = $_SESSION['lenguaje'];
  $sql= "delete from solucion where id_actividad = :id_actividad and id_lenguaje = :lenguaje;";
  
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':id_actividad', $id_actividad);  
  $stmt->bindValue(':lenguaje', $lenguaje);  
    
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"]));
}

?>
