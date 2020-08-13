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
  }  
}

function insertar ($conn) {
  $nombre = $_REQUEST['nombre'];
  $enunciado = $_REQUEST['enunciado'];
  $dificultad = $_REQUEST['lista_dificultad'];
  $correo = $_SESSION['correo'];
  $fecha = date_create();
  $str = "../ejercicios/". $correo. "_". $nombre. "_". date_timestamp_get($fecha). ".txt";
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

function eliminar ($conn) {
  $id_actividad = $_REQUEST['id_actividad'];
  $sql= "delete from actividad where id_actividad = :id_actividad;";
  
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':id_actividad', $id_actividad);  
    
  $res = ejecutarSQL($stmt);  
  echo json_encode(array("success"=>$res["success"], "msg"=>$res["msg"], "data"=>$res["data"]));
}

?>
