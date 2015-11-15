<?php
$servername = "localhost";
$username = "atrapados";
$password = "RULS6";
$dbname = "atrapados";
//conectar con la base de datos
$conn = new mysqli($servername,$username,$password,$dbname);
$conn->set_charset('utf8mb4');
//comprobar conexión
if($conn->connect_error){
	die("connection failes: ".$conn->connect_error);
}

//obtener datos
$tema = $_GET['Tema'];
$lvl = $_GET['lvl'];
//obtener las líneas
$sql = "SELECT * FROM preguntas WHERE Tema = '".$tema."' AND Nivel=".$lvl;
$result = $conn->query($sql);
if($result->num_rows>0){
	//generar el número aleatorio 
	$rand = rand(0,($result->num_rows-1));
	//pasar a la fila elegida
	$result->data_seek($rand);
	$row = $result->fetch_assoc();
	$arr = array();
	$arr["Pregunta"]=$row["Pregunta"];
	$arr["R1"]=$row["R1"];
	$arr["R2"]=$row["R2"];
	$arr["R3"]=$row["R3"];
	$arr["R4"]=$row["R4"];
	$arr["Correcta"]=$row["Correcta"];
	echo json_encode($arr);

}else{
	//error al consultar al base de datos
	$arr = array('Result' => '-1');
	echo json_encode($arr);
}

$conn->close();
?>
