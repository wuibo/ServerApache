<?php
$servername = "localhost";
$username = "atrapados";
$password = "RULS6";
$dbname = "atrapados";

//crear conexion
$conn = new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
	die("connection failes: ".$conn->connect_error);
}
$conn->set_charset('utf8mb4');
//obtener datos
$name = $_POST['name'];

//preparar la query
$sql = "SELECT Nombre,Foto,PuntOnLine,MaxPunt
	FROM usuarios
	WHERE Nombre='".$name."'";
$result = $conn->query($sql);
if($result->num_rows == 1){
	//correcto
	$row = $result->fetch_assoc();
	echo json_encode($row);
}else{
	//error
	$arr = array('Result'=>'-1');
	echo json_encode($arr);
}
$conn->close();
?>
