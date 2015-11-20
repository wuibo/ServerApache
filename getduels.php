<?php
$servername  = "localhost";
$username = "atrapados";
$password = "RULS6";
$dbname = "atrapados";
//conectar con la base de datos
$conn = new mysqli($servername,$username,$password,$dbname);
$conn->set_charset('utf8mb4');
if($conn->connect_error){
	die("connection failes: ".$conn->connect_error);
}
//obtner datos
$name = $_POST["name"];
$pss = $_POST["pss"];
//comprobar nombres y contrseña y obtener id
$sql = "SELECT id FROM usuarios
	WHERE Nombre = '".$name."' AND Password = '".$pss."'";
$result = $conn->query($sql);
if($result->num_rows == 1){
	$arr = array();
	//id obtenido correctametne
	$uid = $result->fetch_assoc()["id"];
	//obtener duelos en solitario
	$sql = "SELECT u.Nombre, u.Foto, d.point1, d.time, d.id
		FROM duelos d
		JOIN usuarios u ON u.id = d.user1
		WHERE d.n_users=1 AND d.user1=".$uid."
		ORDER BY d.time DESC";
	$result = $conn->query($sql);
	$date = strtotime(date("Y-m-d H:i:s"));
	while($row = $result->fetch_array(MYSQL_NUM)){
		//comprobar que no han pasado 24h
		$dtime = strtotime($row[3]);
		$diff = $date - $dtime;
		if($diff > 86400){
			//tiempo para duelo excedido, borrar
			$sql = "DELETE FROM duelos
				WHERE id = ".$row[4];
			$delete = $conn->query($sql);
		}else{
			//mandar el duelo
			$arr[] = $row;
		}
		
	}
	//obtener duelos compeltados
	$sql = "SELECT u1.Nombre, u1.Foto, d.point1, d.point2, u2.Nombre, u2.Foto, d.time, d.id
		FROM duelos d
		JOIN usuarios u1 ON u1.id=d.user1
		JOIN usuarios u2 ON u2.id=d.user2
		WHERE d.user1=".$uid." OR d.user2=".$uid."
		ORDER BY d.time DESC";
	$result = $conn->query($sql);
	while($row = $result->fetch_array(MYSQL_NUM)){
		//mandar duelos
		$arr[] = $row;
	}
	echo json_encode($arr);
	
}else{
	//error en la consulta nombre y contraseña
	$arr = array('Result'=>'-1');
	echo json_encode($arr);
}
$conn->close();
?>
