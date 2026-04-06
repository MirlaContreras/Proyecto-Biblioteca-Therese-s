<?php

require_once '../Modelo/conexion.php';
require_once '../Modelo/manejadorUsuarios.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar'])) {
	
	$nombre= htmlspecialchars($_POST['nombre']);
	$apellido= htmlspecialchars($_POST['apellido']);
	$usuario= htmlspecialchars($_POST['usuario']);
	$password= $_POST['password'];
	$passwordRepeat= $_POST['passwordRepeat'];

	// validación de contraseñas
	if ($password !== $passwordRepeat) {
		//header("Location:../Vista/vistaRegistro.php?error=passwordsNoCoinciden");
		//exit();
	}

	// Encriptar la contraseña 

	$passwordHash = password_hash($password, PASSWORD_DEFAULT);

	$manejador= new manejadorUsuarios();
	$registroExitoso = $manejador->registrarUsuarios($nombre, $apellido, $usuario, $passwordHash);

	if ($registroExitoso) {
		header('Location:../Vista/vistaLogin.php?mensaje=registro_exitoso');
		exit();
	}else{
		header('Location:../Vista/vistaRegistro.php?mensaje=falloRegistro');
		exit();
	}

	
}else{

	header('Location:../Vista/vistaRegistro.php');
	exit();
}

?>