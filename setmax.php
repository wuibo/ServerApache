<?php
$server="localhost";
$username="atrapados";
$password="RULS6";
$dbname="atrapados";

//crear conexion
$conn = new mysqli($server,$username,$password,$dbname);
//comprobar conexión
if($conn->connect_error){
	die("connection failes: ". $conn->connect_error);
}
//obtener datos
$Nombre = $_GET['Nombre'];
$Pass = $_GET['Pass'];
$Max = $_GET['Max'];

//comprobar que elusuario existe
$sql = "SELECT MaxPunt FROM usuarios
	WHERE Nombre = '".$Nombre."' AND Password = '".$Pass."'";
$result = $conn->query($sql);
if($result->num_rows > 0){
	//comprobar que realmente es máxima
	while($row = $result->fetch_assoc()){
		if($row["MaxPunt"]>intval($Max)){
			//no es máximo
			$arr = array('Result' => '-2');
			echo json_encode($arr);
		}else{
			//realizar el cambio
			$sql = "UPDATE usuarios SET MaxPunt = ".$Max."
				WHERE Nombre = '".$Nombre."' AND Password = '".$Pass."'";
			if($conn->query($sql) === TRUE){
				//responder correcto
				$arr = array('Result' => '1');
				echo json_encode($arr);
			}else{
				//responder con error
				$arr = array('Result' => '-3');
				echo json_encode($arr);
			}
		}
	}
}else{
	//usuarios o contraseña erroneo
	$arr = array('Result' => '-1');
	echo json_encode($arr);
}
//cerrar conexion
$conn->close();
?>
