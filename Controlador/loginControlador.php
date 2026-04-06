<?php
session_start();
require_once '../Modelo/manejadorUsuarios.php';
require_once '../Modelo/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['iniciarSesion'])) {
	$usuarioIngresado = htmlspecialchars($_POST['usuario']);
	
	$passwordIngresada= $_POST['password'];
	
	$manejador= new manejadorUsuarios();

	$usuarioData= $manejador->buscarUsuario($usuarioIngresado);

	if ($usuarioData)
	{
		
		
		if (password_verify($passwordIngresada, $usuarioData['password'])) 
		{
			$_SESSION['idUsuarios'] = $usuarioData['idUsuarios'];
			$_SESSION['nameUsuarios'] = $usuarioData['nameUsuarios'];
			$_SESSION['usuario']= $usuarioData['usuario'];
			$_SESSION['loggedin'] = true; 
			header("Location:../Vista/vistaPaginaPrincipal.php");
			exit();
		}
		
	}

	header("Location:../Vista/vistaLogin.php?error=credencialesInvalidas");
			exit();
}else
	{
		header('Location:../Vista/vistaLogin.php');
		exit();
	}