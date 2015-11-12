<?php
$servername = "localhost";
$username = "atrapados";
$password = "RULS6";
$dbname = "atrapados";

//crear conexion
$conn = new mysqli($servername,$username,$password,$dbname);
//comprobar conexion
if($conn->connect_error){
	die("connection failes: ". $conn->connect_error);
}

//obtener datos
$Nombre = $_GET['Nombre'];
$Pass = $_GET['Pass'];

//preparar query
$sql = "SELECT Nombre FROM usuarios WHERE Nombre = '".$Nombre."'";
$result = $conn->query($sql);

//comprobar resultados
if ($result->num_rows > 0){
	$arr = array('Result' =>'1');
	echo json_encode($arr); 	
}else{
	//insertar los datos
	$sql = "INSERT INTO usuarios (Nombre,Password,PuntOnline,MaxPunt)
		VALUES ('".$Nombre."','".$Pass."',0,0)";
	if($conn->query($sql) === TRUE){
		$arr = array('Result'=>'0');
		echo json_encode($arr);
	}else{
		$arr = array('Result'=>'-1');
		echo json_encode($arr);
	}
}

//cerrar conexion
$conn->close();
?>
