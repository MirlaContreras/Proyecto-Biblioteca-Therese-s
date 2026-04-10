<?php

//Controlador historial controlador

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

//2. verificacion de seguridad

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true)
 {
	header("Location: vistaLogin.php");
	exit();
}

require_once __DIR__ . '/../Modelo/manejadorLibros.php';
require_once __DIR__ . '/../Modelo/conexion.php';

//4. logica principal
$manejador= new manejadorLibros();

$idUsuarioLogin= $_SESSION['idUsuarios'];

$busqueda= null;

//obtenemos el id del usuario
try {
	
	if (isset($_GET['busqueda']) && !empty($_GET['busqueda'])) {
	$busqueda=$_GET['busqueda'];
	

	}
	

	//5. llamar al modelo
	$historial= $manejador->obtenerHistorial($idUsuarioLogin, $busqueda);
	$listaLibros= $manejador->obtenerTituloLibros();

} catch (Exception $e) {

	//manejo de errores 

	error_log("error en obtenerHistorial". $e->getMessage());
	$historial=[];// mandamos un array vacio para que no se dañe la vista;
	$errorControlador="No se pudo cargar el hostorial";
	
}
	


