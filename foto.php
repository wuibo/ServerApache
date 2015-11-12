<?php
$uploaddir = '/var/www/html/uploads/';

$name = $_POST['value1'];
$pass = $_POST['value2'];

$uploadfile = $uploaddir.$name;
//comprobar que el nombre y contraseña son correctos
//hacer conexion a base de datos
$servername = "localhost";
$username = "atrapados";
$password = "RULS6";
$dbname = "atrapados";
$conn = new mysqli($servername,$username,$password,$dbname);
//comprobar conexion
if($conn->connect_error){
	die("connection failes: ".$conn->connect_error);
}
//realizar la query
$sql = "SELECT * FROM usuarios WHERE Nombre = '".$name."' AND Password = '".$pass."'";
$result = $conn->query($sql);

//comprobar resultadosp
if($result->num_rows > 0){
	//usuario y contraseña correctos
	if(move_uploaded_file($_FILES['picture']['tmp_name'],$uploadfile)){
		#fichero movido correctamente
		//indicar en la BD que hay foto
		$sql = "UPDATE usuarios SET Foto = '".$name."'
			WHERE Nombre = '".$name."' AND Password = '".$pass."'";
		if($conn->query($sql) === TRUE){
			//actualización correcta
			$arr = array('Result' => '0');
			echo json_encode($arr);
		}else{
			//error en la actualizaicón
			$arr = array('Result' => '-3');
			echo json_encode($arr);
			
		}		
		
	}else{
		#problema al movel el fichero
		$arr = array('Result'=>'-2');
		echo json_encode($arr);
	}	
}else{
	//usuario o contraseña erroneaos
	$arr = array('Result'=>'-1');
	echo json_encode($arr);
	
}
?>
