<?php
$servername = "localhost";
$username = "atrapados";
$password = "RULS6";
$dbname = "atrapados";
//conectar con la base de datos
$conn = new mysqli($servername,$username,$password,$dbname);
$conn->set_charset('utf8mb4');
if($conn->connect_error){
	die("connection failes: ".$conn->connect_error);
}
//obtener datos
$did = $_POST["id"];
//obtener los id de las preguntas
$sql = "SELECT q1,q2,q3,q4,q5,q6 FROM duelos
	WHERE id=".$did;
$result = $conn->query($sql);
if($result->num_rows == 1){
	//devolver lso id de preguntas
	$row = $result->fetch_assoc();
	echo json_encode($row);
}else{
	//error en el id
	$arr = array('Result'=>'-1');
	echo json_encode($arr);
}
?>
