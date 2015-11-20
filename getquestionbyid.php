<?php
$servername = "localhost";
$username = "atrapados";
$password = "RULS6";
$dbname = "atrapados";
//conectar con la base de datos
$conn = new mysqli($servername,$username,$password,$dbname);
$conn->set_charset('utf8mb4');
//comprobar conexion
if($conn->connect_error){
	die("connection failes: ".$conn->connect_error);
}
//obtener dato
$qid = $_POST['id'];
//obtener la pregunta
$sql = "SELECT Pregunta,R1,R2,R3,R4,Correcta FROM preguntas
	WHERE id = ".$qid;
$result = $conn->query($sql);
if($result->num_rows == 1){
	$row = $result->fetch_assoc();
	echo json_encode($row);
}else{
	//error obteniendo pregunta
	$arr = array('Result'=>'-1');
	echo json_encode($arr);
}
$conn->close();
?>
