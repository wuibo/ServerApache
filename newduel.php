<?php

function createduel($uid,$conn){
	//generar numeros aleatorios para las preguntas
	//conocimiento del medio
	$sql = "SELECT id FROM preguntas
		WHERE Tema ='Conocimiento del medio'";
	$res = $conn->query($sql);
	$rand = rand(0,($res->num_rows-1));
	$res->data_seek($rand);
	$q1 = $res->fetch_assoc()["id"];
	//matematicas
	$sql = "SELECT id FROM preguntas
		WHERE Tema ='Matematicas'";
	$res = $conn->query($sql);
	$rand = rand(0,($res->num_rows-1));
	$res->data_seek($rand);
	$q2 = $res->fetch_assoc()["id"];
	//Lengua
	$sql = "SELECT id FROM preguntas
		WHERE Tema ='Lengua'";
	$res = $conn->query($sql);
	$rand = rand(0,($res->num_rows-1));
	$res->data_seek($rand);
	$q3 = $res->fetch_assoc()["id"];
	//Ingles
	$sql = "SELECT id FROM preguntas
		WHERE Tema ='Ingles'";
	$res = $conn->query($sql);
	$rand = rand(0,($res->num_rows-1));
	$res->data_seek($rand);
	$q4 = $res->fetch_assoc()["id"];
	//Musica
	$sql = "SELECT id FROM preguntas
		WHERE Tema ='Musica'";
	$res = $conn->query($sql);
	$rand = rand(0,($res->num_rows-1));
	$res->data_seek($rand);
	$q5 = $res->fetch_assoc()["id"];
	//Gimnasia
	$sql = "SELECT id FROM preguntas
		WHERE Tema ='Gimnasia'";
	$res = $conn->query($sql);
	$rand = rand(0,($res->num_rows-1));
	$res->data_seek($rand);
	$q6 = $res->fetch_assoc()["id"];
	//insertar el duelo
	$date = date("Y-m-d H:i:s");
	$sql = "INSERT INTO duelos
		(user1,n_users,q1,q2,q3,q4,q5,q6,time)
		VALUES (".$uid.",1,".$q1.",".$q2.",".$q3.",".$q4.",".$q5.",".$q6.",'".$date."')";
	if($conn->query($sql) === TRUE){
		//obtener el id del duelo creado
		$sql = "SELECT id FROM duelos
			WHERE time='".$date."' AND user1 = ".$uid;
		$res = $conn->query($sql);
		$did = $res->fetch_assoc()["id"];
		$arr = array('id' => $did);
		echo json_encode($arr);	
	}else{
		$arr = array('Result' => '-2');
		echo json_encode($arr);
	}
}

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
//obtener datos
$name = $_POST["name"];
$pss = $_POST["pss"];
//comprobar nombre y contraseña y obtener id
$sql = "SELECT id FROM usuarios
	WHERE Nombre = '".$name."' AND Password = '".$pss."'";
$result = $conn->query($sql);
if($result->num_rows == 1){
	$row = $result->fetch_assoc();
	$id = $row["id"];
	//mirar si hay duelos abiertos
	$sql = "SELECT u.Nombre, d.point1, d.id, d.time
		FROM duelos d
		JOIN usuarios u ON u.id=d.user1
		WHERE d.n_users=1 AND d.user1!=".$id;
	$result = $conn->query($sql);
	if($result->num_rows > 0 ){
		//asignar a un duelo existente
		//comprobar fech
		$date = strtotime(date("Y-m-d H:i:s"));
		$done = 0;
		while($row = $result->fetch_assoc()){
			$dtime = strtotime($row["time"]);
			$diff = $date - $dtime;
			if($diff >  86400){
				//tiempo para duelo excedido borrar duelo
				$sql = "DELETE FROM duelos
					WHERE id = ".$row["id"];
				$delete = $conn->query($sql);
			}else{
				//asignar duelo
				//actualizar tabla
				$sql = "UPDATE duelos
					SET n_users = 2, user2 = ".$id."
					WHERE id = ".$row["id"];
				if($conn->query($sql) === TRUE){
					//tabla actualizada correctamente
					echo json_encode($row);
					$done = 1;
				}else{
					//error al actualizar
					$arr = array('Result'=>'-3');
					echo json_encode($arr);
				}
				break;
			}
		}
		//comprobar si se ha asignado duelo
		if($done == 0){
			//no se ha asignado (crear)
			createduel($id,$conn);
		}			
	}else{
		//crear nuevo duelo
		createduel($id,$conn);
	}	
}else{
	//error en contraseña o nombre
	$arr = array('Result'=>'-1');
	echo json_encode($arr);
}
$conn->close();
?>
