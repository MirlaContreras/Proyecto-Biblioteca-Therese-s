<?php

// --- PEGA ESTO AL INICIO PARA VER EL ERROR OCULTO ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ----------------------------------------------------


session_start();

require_once "../Modelo/conexion.php";
require_once "../Modelo/manejadorLibros.php";

$response= [
	"success"=> false,
	"mesagge"=>"error desconocido"
];

if (!isset($_SESSION['idUsuarios'])){

	$response['message']='Error: debes iniciar sesion';
	header('Content-Type: application/json');
	echo json_encode($response);
	exit;
}

if (isset($_POST['historialId']) && isset($_POST['comentario'])) {
	
	$idUsuario= $_SESSION['idUsuarios'];

	$idLibro= $_POST['historialId'];

	$texto= trim($_POST['comentario']);

	if(empty($texto)){
		$response['message']='El comentario no puede estar vacio';
		header('Content-Type: application/json');
		echo json_encode($response);
		exit;
	}

	try{

		$manejador= new manejadorLibros();

		$resultado= $manejador->guardarComentario($idLibro, $idUsuario, $texto);

		if ($resultado) {
			$response['success']=true;
			$response['message']='Reseña guardada correctamente';
		}else{
			$response['message']="Hubo un error al intentar guardar en la base de datos";
		}
	}catch( Exception $e){
		$response['message']='Error del servidor: '. $e->getMessage();
	}

}else{
	$response['message']='faltan datos obligatorios (ID del libro o Comentario)';
}

header('Content-Type: application/json');
echo json_encode($response);
?>