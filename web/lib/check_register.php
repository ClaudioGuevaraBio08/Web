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
	if (isset($_POST['usernombre'], $_POST['userapellido'], $_POST['correo'], $_POST['pwd'], $_POST['pwd_confirm'])) {
		if ($_POST['usernombre']!="" and $_POST['userapellido']!= "" and $_POST['correo']!="" and $_POST['pwd']!="" and $_POST['pwd_confirm'] != "") {
			if (strlen($_POST['pwd']) >= 8){
				if ($_POST['pwd'] == $_POST['pwd_confirm']){
					$nombre = $_POST['usernombre'];
					$apellido = $_POST['userapellido'];
					$correo = $_POST['correo'];
					$password = $_POST['pwd'];
					$confirm_password = $_POST['pwd_confirm'];
					$encrypted_password = encriptar($password);
					$sql = "insert into usuario values (:correo, 2, :password, :nombre, :apellido)";
					$stmt = $conn->prepare($sql);
					$stmt->bindValue(':nombre', $nombre);
					$stmt->bindValue(':apellido', $apellido);
					$stmt->bindValue(':correo', $correo);
					$stmt->bindValue(':password', $encrypted_password);
					if($stmt->execute()){
						$array_session = $stmt->fetchAll(\PDO::FETCH_ASSOC);
						if (count($array_session) == 1) {
							$_SESSION['loggedinMIAPP'] = false;
							$location  = "../index/index.php";
							$success = true;
							$msg = "ok";
						}
						else{
							$msg = "Usuario o Clave erróneos.";
						}
					}
					else {
						$msg = "Usuario ya registrado ";
					}
				} else {
					$msg = "Contraseñas distintas";
				}
			} else{
				$msg = "Contraseña debe tener minimo 8 caracteres";
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
