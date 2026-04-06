<?php

//Inicia sesion para poder acceder a las variables de sesion
session_start();

//Destruye todas las variables de la sesion
$_SESSION = array();

//Si se usa una sesion basada en cookies, tabien es necesario destruirlas

if (ini_get("session.use_cookies")){
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time()- 42000,
		$params["path"], $params["domain"], 
		$params["secure"], $params["httponly"]
	);
}

//Finalmente, destruye la sesion

session_destroy();

//Redirige al usuario a la pagina de login
header("Location: ../Vista/vistaLogin.php");
exit();
?>