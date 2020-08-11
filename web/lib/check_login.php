<?php
session_start();
require('../lib/common.php');
$_SESSION['loggedin'] = false;
$_SESSION['id_usu'] = null;
$_SESSION['nemp'] = 0;
$success = false;
$msg = "Error de usuario o clave";
$location = "../lib/logout.php";
$_SESSION['loggedinMIAPP'] = false;
$_SESSION['last_activity'] = 0;
$conn = conectarBD();
if ($conn) {
	if (isset($_POST['username'], $_POST['pwd'])) {
		if ($_POST['username']!="" and $_POST['pwd']!="") {
			$id_usu = $_POST['username'];
			$password = $_POST['pwd'];
			$encrypted_password = encriptar($password);
			$sql = "select * from usuario where correo = :id_usu and contrasena = :password";
			$stmt = $conn->prepare($sql);
			$stmt->bindValue(':id_usu', $id_usu);
			$stmt->bindValue(':password', $encrypted_password);
			if ($stmt->execute()) {
				$array_session = $stmt->fetchAll(\PDO::FETCH_ASSOC);
				if (count($array_session) == 1) {
					$_SESSION['username'] = $array_session[0]["nombre"] . " " . $array_session[0]["apellido"];
					$_SESSION['correo'] = $array_session[0]["correo"];
					$_SESSION['nivel'] = $array_session[0]["id_nivel"];
					$_SESSION['loggedinMIAPP'] = true;
					$_SESSION['last_activity'] = time();
					$success = true;
					$msg = "ok";
					$location = "../view/index.php";
					// Insert the access to the db.
					//registerActivity($id_per, 'true');
				} else {
					//registerActivity($id_per, 'false');
					$msg = "Usuario o Clave erróneos.";
					// registerActivity($id_per, 1, null, 'false', null, 'index');
				}
			} else {
				$msg = "Error al ejecutar la consulta.";
			}
		} else {
			$msg = "Todos los datos son requeridos.";
		}
	} else {
		$msg = "Todos los datos son requeridos.";
	}
    } else {
	    $msg = "Todos los datos son requeridos.";
    }
} else {
	$msg = "No puede conectar a la Base de Datos.";
}
$jsonOutput = array('success' => $success, 'msg' => $msg, 'location'=> $location);
echo json_encode($jsonOutput);
?>
