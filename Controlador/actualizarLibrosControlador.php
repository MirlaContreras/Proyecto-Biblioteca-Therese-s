<?php

session_start();

require_once '../Modelo/conexion.php';
require_once '../Modelo/manejadorLibros.php';

$response = [

'success' => false,
'message' => 'Peticion incorrecta.'

];


	if (!isset($_SESSION['idUsuarios'])) {
		$response['message'] = 'Error no has iniciado sesion';
	}elseif (isset($_POST['historialId']) && isset($_POST['estado'])) {
		
		try{
			$historialId = $_POST['historialId'];
			$nuevoEstado = $_POST['estado'];
			$idUsuario = $_SESSION['idUsuarios'];

			$fechaManual = isset($_POST['fecha_lectura']) ? $_POST['fecha_lectura'] : null;

			if ($nuevoEstado != 'leído' && !empty($fechaManual)) {
				echo json_encode(['success'=> false, 'message' => 'Error, no puedes poner la fecha si no tienes el libro "leído']);
				exit;
			}

			$manejador = new manejadorLibros();
			$exito=false;

			if ($fechaManual) {
				$exito=$manejador->actualizarFecha($historialId, $fechaManual, $idUsuario);
			}else{
				$exito= $manejador->actualizarEstado($historialId, $nuevoEstado, $idUsuario);
			}
			
			if ($exito) {
				$response['success']=true;
				$response['message']='Actualización Exitosa';
			}else{
				$response['message']='no se pudo actualizar los datos';
			}	

		}catch(Exception $e)
		{
			$response['message'] = "Error inesperado en el controlador" . $e->getMessage();
		}

		header('Content-Type: application/json');
		echo json_encode($response);

	}elseif(isset($_POST['eliminarLibro']) && isset($_POST['historialId'])){
			$historialId=$_POST['historialId'];

			$manejador = new manejadorLibros();
			$eliminar=$manejador->eliminarLibro($historialId);

			if ($eliminar) {
				header("Location:../Vista/historial.php?mensaje=Exito");
				exit();

			}else{
				header("Location:../Vista/historial.php?mensaje=Error");
				exit();
			}
	}





