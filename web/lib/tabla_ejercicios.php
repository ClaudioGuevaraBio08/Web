<?php
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
    case 7:
      # delete where = ?
      $conn = conectarBD();
      mostrar_solucion ($conn);
      break;
  }  
}

function insertar ($conn) {
  $nombre = $_REQUEST['nombre'];
  $enunciado = $_REQUEST['enunciado'];
  $dificultad = $_REQUEST['lista_dificultad'];
  $correo = $_SESSION['correo'];
  $fecha = date_create();
  $str = "../archivos/ejercicios/". $correo. "_". $nombre . "_". date_timestamp_get($fecha). ".txt";
  $ar = fopen($str, "a");
  fwrite($ar, $enunciado);
  fclose($ar);
  $sql= "insert into actividad (id_dificultad, correo, enunciado, nombre) values (:dificultad, :correo, :enunciado, :nombre);";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':nombre', $nombre); 
  $stmt->bindValue(':correo', $correo);
  $stmt->bindValue(':enunciado', $str);
  $stmt->bindValue(':dificultad', $dificultad);
  
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"][0]));
}

function seleccionar ($conn) {
  $sql= "select actividad.id_actividad, actividad.nombre, dificultad.nombre as dificultad, actividad.enunciado, actividad.correo from actividad join dificultad on actividad.id_dificultad = dificultad.id_dificultad order by id_actividad asc;";
  $stmt = $conn->prepare($sql);
    
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"]));
}

function actualizar ($conn) {
  $id_actividad = $_REQUEST['id_actividad'];
  $nombre = $_REQUEST['nombre'];
  $dificultad = $_REQUEST['lista_dificultad'];
  $enunciado = $_REQUEST['enunciado'];
  
  $ruta_anterior = "select enunciado from actividad where id_actividad = :id_actividad;";
  $stmt = $conn->prepare($ruta_anterior);
  $stmt->bindValue(':id_actividad', $id_actividad); 
  $res = ejecutarSQL($stmt); 
  
  unlink($res["data"][0]["enunciado"]);
  
  $correo = $_SESSION['correo'];
  $fecha = date_create();
  $str = "../archivos/ejercicios/". $correo. "_". $nombre. "_". date_timestamp_get($fecha). ".txt";
  $ar = fopen($str, "a");
  fwrite($ar, $enunciado);
  fclose($ar);
  $sql= "update actividad set nombre = :nombre, enunciado = :enunciado, id_dificultad = :dificultad where id_actividad = :id_actividad;";
  
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':id_actividad', $id_actividad); 
  $stmt->bindValue(':nombre', $nombre);  
  $stmt->bindValue(':enunciado', $str);
  $stmt->bindValue(':dificultad', $dificultad);
    
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"][0]));
}

function seleccionarUno ($conn) {
  $id_actividad = $_REQUEST['id_actividad'];
  $sql= "select id_actividad, nombre, enunciado from actividad where id_actividad = :id_actividad order by nombre asc;";
  
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':id_actividad', $id_actividad);  
    
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"][0]));
}


function eliminar ($conn) {
  $id_actividad = $_REQUEST['id_actividad'];
  $sql= "delete from actividad where id_actividad = :id_actividad;";
  
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':id_actividad', $id_actividad);  
    
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"]));
}


function select_instrucciones ($conn) {
  $id_actividad = $_REQUEST['id_actividad'];
  $sql= "select nombre, enunciado from actividad where id_actividad = :id_actividad;";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':id_actividad', $id_actividad);  
    
  $res = ejecutarSQL($stmt);  
  $texto = fopen($res["data"][0]["enunciado"],"r");
  while(!feof($texto)){
	$linea = $linea . fgets($texto) . "<br>";
  }
  fclose($texto);
	
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"][0], "texto"=>$linea));
}

function mostrar_solucion ($conn) {
  $id_actividad = $_REQUEST['id_actividad'];
  $lenguaje = $_SESSION['lenguaje'];
  $sql= "select actividad.nombre, solucion.solucion as solucion from actividad join solucion on solucion.id_actividad = actividad.id_actividad where actividad.id_actividad = :id_actividad and solucion.id_lenguaje = :lenguaje;";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':id_actividad', $id_actividad); 
  $stmt->bindValue(':lenguaje', $lenguaje); 
    
  $res = ejecutarSQL($stmt);  
  if (sizeof($res["data"][0]["solucion"]) == 0){
	$res["data"][0]["nombre"] = "No hay solucion disponible";
  }
  else{
	  $texto = fopen($res["data"][0]["solucion"],"r");
	  while(!feof($texto)){
		$linea = $linea . fgets($texto) . "<br>";
	  }
	  fclose($texto);
  }
	
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"][0], "texto"=>$linea));

}

?>
