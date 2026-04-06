<?php 
mysqli_report(MYSQLI_REPORT_OFF);

// La conexion

$server = "mysql-theresebiblioteca.alwaysdata.net";
$user= "theresebiblioteca";
$pass= "261982651997MJCD";
$db = "theresebiblioteca_proyectobiblioteca";

// La conexion global

$conexion= new mysqli($server, $user, $pass, $db);

if ($conexion->connect_error) {
	//usamos connect_error para mostrar el mensaje de error de conexion
	die("conexión fallida". $conexion->connect_error);

// Establece el conjunto de caracteres para evitar problemas con tildes y ñ
}


$conexion->set_charset("utf8mb4");

?>
