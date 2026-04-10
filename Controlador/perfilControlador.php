<?php

 if (session_status() === PHP_SESSION_NONE){
 	session_start();
 } //INICIA SESION DE PHP SI NO EXISTE

 if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !==true) {
 	header("Location: vistaLogin.php");
 	exit();
 } //VERIFICA QUE ESTE LOGEADO

 require_once __DIR__ . '/../Modelo/manejadorUsuarios.php';
 require_once __DIR__ . '/../Modelo/conexion.php';
 // incluye los archivos que necesita, usa el __DIR__ para que siempre encuentre las carpetas

 try{



 	$UsuarioLogin= $_SESSION['usuario'];
 	$manejador= new manejadorUsuarios();
 	$idUsuario = $_SESSION['idUsuarios'];
 	$datosUsuario= $manejador->buscarIdUsuario($idUsuario);

 //Para subir la foto de perfil

 	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fotoPerfil'])) {
 		
 		$nombreArchivo = $_FILES['fotoPerfil']['name'];
 		$temporal = $_FILES['fotoPerfil']['tmp_name'];

 		$carpetaDestino = '../Uploads/fotosPerfil/';
 		$rutaCompleta= $carpetaDestino. $nombreArchivo;

 		if (move_uploaded_file($temporal, $rutaCompleta)) {
 			$manejador->actualizarFotoPerfil($UsuarioLogin, $nombreArchivo);

 			header("Location: ../Vista/perfil.php");
 			exit();
 		}
 	}else{
 		$datosUsuario= $manejador->buscarIdUsuario($idUsuario);
 	}

//para actualizar los datos del usuario

 	
 		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizarDatos'])) {

 			$antiguosDatos= $manejador->buscarIdUsuario($idUsuario);

 			$userName= !empty($_POST['userName'])
 			? $_POST['userName']
 			: $antiguosDatos['usuario'];

			$nameUsuario = !empty($_POST['nameUsuario'])
			? $_POST['nameUsuario']
			: $antiguosDatos['nameUsuarios'];

			$apellidoUsuario = !empty($_POST['apellidoUsuario'])
			? $_POST['apellidoUsuario']
			: $antiguosDatos['apellidoUsuarios'];


			$manejador = new manejadorUsuarios();
			$actualizar= $manejador->actualizarDatosUsuarios($userName, $nameUsuario, $apellidoUsuario, $idUsuario);

			if ($actualizar) {
				header("Location:../Vista/perfil.php?mensaje=exito");
				exit();
			}else{
				header("Location:../Vista/perfil.php?mensaje=error_bd");
				exit();
			}
		}

//CAMBIAR CONTRASEÑA

		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizarClave'])) {
			
			$passActual= $_POST['passActual'];
			$passNew= $_POST['newPass'];
			$passConfirm= $_POST['repPass'];


			if ($passNew !== $passConfirm) {
				 
				 header("Location: ../Vista/perfil.php?mensaje=error_pass_no_coinciden");
			}

			$datosUsuario= $manejador->buscarIdUsuario($idUsuario);
			$hashGuardado= $datosUsuario['password'];
			

			if (password_verify($passActual, $hashGuardado)) {
				
				$nuevoHash= password_hash($passNew, PASSWORD_DEFAULT);

				$resultado= $manejador->actualizarPassword($idUsuario, $nuevoHash);

				if ($resultado) {
					header("Location:../Vista/perfil.php?mensaje=exito_pass");
					exit();
				}else{
					header("Location:../Vista/perfil.php?mensaje=error_bd_pass");
					exit();
				}
			}else{
				header("Location:../Vista/perfil.php?mensaje=error_pass_incorrecta");
				exit();
			}

		}


//para mostrar los datos
 	

 }catch (Exception $e){
 	error_log("error en buscarUsuario". $e->getMessage());
 	$datosUsuario=[];//array vacio para que no se dañe la vista
 	$errorControlador="No se pudo cargar los datos del usuario";
 }

