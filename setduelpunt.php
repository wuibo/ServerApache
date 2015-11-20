<?php
$servername = "localhost";
$username = "atrapados";
$password = "RULS6";
$dbname = "atrapados";
//connectar con la base de datos
$conn = new mysqli($servername,$username,$password,$dbname);
$conn->set_charset('utf8mb4');
if($conn->connect_error){
	die("connection failes: ".$conn->connect_error);
}
//obtener los datos
$name = $_POST["name"];
$pss = $_POST["pss"];
$did = $_POST["id"];
$punt = $_POST["punt"];
//comprobar usuario y contraseña y obtener id
$sql = "SELECT id FROM usuarios
	WHERE Nombre = '".$name."' AND Password = '".$pss."'";
$result = $conn->query($sql);
if($result->num_rows == 1){
	//usuario y contraseña correctos
	$row = $result->fetch_assoc();
	$uid = $row["id"];
	//comprobar que el usuario forma parte del duelo y que usuario es
	$sql = "SELECT user2, point2
		FROM duelos
		WHERE id = ".$did." AND user1=".$uid;
	$result = $conn->query($sql);
	if($result->num_rows == 1){
		//existe duelo y es el user1
		$row = $result->fetch_assoc();
		$p2 = $row["point2"];
		$u2 = $row["user2"];
		//actualizar duelo
		$sql = "UPDATE duelos
			SET point1 = ".$punt."
			WHERE id = ".$did;
		if($conn->query($sql) === TRUE){
			//comprobar si el otro usuario ya ha contestado
			if($p2 == null){
				$arr = array("Result"=>'0');
				echo json_encode($arr);
			}else{
				//comprobar ganador y actualizar puntuación online
				if($p2 < $punt){
					//sumar un punto al usuario 2
					$sql = "SELECT PuntOnline
						FROM usuarios
						WHERE id=".$u2;
					$result = $conn->query($sql);
					$ponline = $result->fetch_assoc()["PuntOnline"];
					$ponline ++;
					$sql = "UPDATE usuarios
						SET PuntOnline = ".$ponline."
						WHERE id = ".$u2;
					if($conn->query($sql) === TRUE){
						//actualiza correctamente
						$arr = array('Result'=>'0');
						echo json_encode($arr);
					}else{
						//error actulizando putuacion
						$arr = array('Result'=>'-3');
						echo json_encode($arr);
					}
				}elseif($punt < $p2){
					//sumar un punto al usuario 1 (cliente)
					$sql = "SELECT PuntOnline
						FROM usuarios
						WHERE id=".$uid;
					$result = $conn->query($sql);
					$ponline = $result->fetch_assoc()["PuntOnline"];
					$ponline ++;
					$sql = "UPDATE usuarios
						SET PuntOnline = ".$ponline."
						WHERE id = ".$uid;
					if($conn->query($sql) === TRUE){
						//actualiza correctamente
						$arr = array('Result'=>'0');
						echo json_encode($arr);
					}else{
						//error actulizando putuacion
						$arr = array('Result'=>'-3');
						echo json_encode($arr);
					}
				}else{
					//empate
					$arr = array('Result'=>'0');
					echo json_encode($arr);
				}
			}
		}else{
			//error actualizando duelo
			$arr = array('Result'=>'-2');
			echo json_encode($arr);
		}
	}else{
		//comprobar con usuario2
		$sql = "SELECT user1, point1
			FROM duelos
			WHERE id = ".$did." AND user2 = ".$uid;
		$result = $conn->query($sql);
		if($result->num_rows == 1){
			//existe el duelo y es user2
			$row = $result->fetch_assoc();
			$p1 = $row["point1"];
			$u1 = $row["user1"];
			//actualizar duelo
			$sql = "UPDATE duelos
				SET point2 = ".$punt."
				WHERE id = ".$did;
			if($conn->query($sql) === TRUE){
				//comprobar si el usuario ya ha contestado
				if($p1 == null){
					$arr = array('Result'=>'0');
					echo json_encode($arr);
				}else{
					//comprobar ganador y actualizar puntuacion online
					if($p1 < $punt){
						//sumar un punto al user 1
						$sql = "SELECT PuntOnline
							FROM usuarios
							WHERE id=".$u1;
						$result = $conn->query($sql);
						$ponline = $result->fetch_assoc()["PuntOnline"];
						$ponline ++;
						$sql = "UPDATE usuarios
							SET PuntOnline = ".$ponline."
							WHERE id = ".$u1;
						if($conn->query($sql) === TRUE){
							//actualizad correctamente
							$arr = array('Result'=>'0');
							echo json_encode($arr);	
						}else{
							//erro actualizando puntuacion
							$arr = array('Result'=>'-3');
							echo json_encode($arr);
						}
					}elseif($punt < $p1){
						//sumar un punto al user 2
						$sql = "SELECT PuntOnline
							FROM usuarios
							WHERE id=".$uid;
						$result = $conn->query($sql);
						$ponline = $result->fetch_assoc()["PuntOnline"];
						$ponline ++;
						$sql = "UPDATE usuarios
							SET PuntOnline = ".$ponline."
							WHERE id = ".$uid;
						if($conn->query($sql) === TRUE){
							//actualizad correctamente
							$arr = array('Result'=>'0');
							echo json_encode($arr);	
						}else{
							//erro actualizando puntuacion
							$arr = array('Result'=>'-3');
							echo json_encode($arr);
						}
					}else{
						//empate
						$arr = array('Result'=>'0');
						echo json_encode($arr);
					}
				}
			}else{
				//erro actualizando duelo
			}
		}else{
			//el duelo no existe o mal usuario
			$arr = array('Result'=>'-4');
			echo json_encode($arr);
		}
	}
}else{
	//usuarios o contraseña incorrectos
	$arr = array('Result'=>'-1');
	echo json_encode($arr);
}

$conn->close();
?>
