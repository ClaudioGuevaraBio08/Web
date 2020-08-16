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
  $lenguaje = $_SESSION['lenguaje'];
  $actividad = $_REQUEST['lista_actividad'];
  $solucion = $_REQUEST['solucion'];
  
  $ruta_anterior = "select actividad.nombre as nombre from solucion join actividad on actividad.id_actividad = solucion.id_actividad where actividad.id_actividad = :actividad and solucion.id_lenguaje = :lenguaje;";
  $stmt = $conn->prepare($ruta_anterior);
  $stmt->bindValue(':actividad', $actividad); 
  $stmt->bindValue(':lenguaje', $lenguaje);
  $res = ejecutarSQL($stmt);
  
  $correo = $_SESSION['correo'];
  $fecha = date_create();
  $str = "../archivos/soluciones/". $correo. "_". $res["data"]["0"]["nombre"] . "_". date_timestamp_get($fecha). ".txt";
  $ar = fopen($str, "a");
  fwrite($ar, $solucion);
  fclose($ar);
  $sql= "insert into solucion (id_lenguaje, id_actividad, solucion) values (:lenguaje, :actividad, :solucion);";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':lenguaje', $lenguaje); 
  $stmt->bindValue(':actividad', $actividad);
  $stmt->bindValue(':solucion', $str);
  
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"][0]));
}


function seleccionar ($conn) {
  $lenguaje = $_SESSION['lenguaje'];
  $sql= "select actividad.nombre as nombre_actividad, solucion.id_actividad from solucion join actividad on actividad.id_actividad = solucion.id_actividad where solucion.id_lenguaje = :lenguaje order by solucion.id_lenguaje;";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':lenguaje', $lenguaje);
    
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"]));
}

function actualizar ($conn) {
  $actividad = $_REQUEST['id_actividad'];
  $lenguaje = $_SESSION['lenguaje'];
  $solucion = $_REQUEST['solucionupdate'];
  
  $ruta_anterior = "select solucion.solucion as solucion, actividad.nombre as nombre from solucion join actividad on actividad.id_actividad = solucion.id_actividad where actividad.id_actividad = :actividad and solucion.id_lenguaje = :lenguaje;";
  $stmt = $conn->prepare($ruta_anterior);
  $stmt->bindValue(':actividad', $actividad); 
  $stmt->bindValue(':lenguaje', $lenguaje);
  $res = ejecutarSQL($stmt); 
  
  unlink($res["data"][0]["solucion"]);
  
  $correo = $_SESSION['correo'];
  $fecha = date_create();
  $str = "../archivos/soluciones/". $correo. "_". $res["data"][0]["nombre"]. "_". date_timestamp_get($fecha). ".txt";
  $ar = fopen($str, "a");
  fwrite($ar, $solucion);
  fclose($ar);
  
  $sql= "update solucion set solucion = :solucion where id_actividad = :actividad and id_lenguaje = :lenguaje;";
  
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':actividad', $actividad); 
  $stmt->bindValue(':lenguaje', $lenguaje);  
  $stmt->bindValue(':solucion', $str);
    
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

function select_instrucciones ($conn) {
  $id_actividad = $_REQUEST['id_actividad'];
  $lenguaje = $_SESSION['lenguaje'];
  $sql= "select actividad.nombre, solucion.solucion from solucion join actividad on actividad.id_actividad = solucion.id_actividad where solucion.id_actividad = :id_actividad and solucion.id_lenguaje = :lenguaje;";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':id_actividad', $id_actividad);  
  $stmt->bindValue(':lenguaje', $lenguaje);  
    
  $res = ejecutarSQL($stmt);  
  $texto = fopen($res["data"][0]["solucion"],"r");
  while(!feof($texto)){
    $linea = $linea . fgets($texto) . "<br>";
  }
  fclose($texto);
	
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"][0], "texto"=>$linea));
}

?>
