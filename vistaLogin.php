<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="estilos.css">
	<title>Ingresar a la cuenta</title>
</head>
<body id="contPrincipal">

	

		<header class="cabecera" style="background-color:#a9a251ff;">
			<p>Tu Biblioteca Online</p>
		</header>
		
		<div id="contLogin">
		<form action="../Controlador/loginControlador.php" method="POST" id="formCont">
			
			<label name="usuario">Ingrese su nombre de usuarios</label>
			<input type="text" name="usuario" id="usuario" required>

			<label name="password">Ingrese su contraseña</label>
			<input type="password" name="password" id="password" required>
			<button type="submit" name="iniciarSesion" id="ingresar">Ingresar</button>
		</form>

		<p id="link-registro">Aun no tienes cuenta? <a href="vistaRegistro.php">Registrate aqui</a></p>

		<?php

		if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'registroExitoso') {
			echo'<div style="color:red; margin-top: 15px;"> Registro Exitoso, ya puede iniciar sesion</div>';
		}
		if (isset($_GET['error']) && $_GET['error'] === 'credencialesInvalidas') {
			echo '<div style="color: red; margin-top: 15px;">Error: Nombre de usuario o contraseña incorrectos.</div>';
		}
		 ?>

	 </div>
</body>
</html>