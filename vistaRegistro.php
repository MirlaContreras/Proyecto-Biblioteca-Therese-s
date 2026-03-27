<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="estilos.css">
	<title>Ingresar a la cuenta</title>
</head>
<body class="vistaRegistro">

	<header class="cabecera">
	<a href="vistaPaginaPrincipal.php"><p>Tu Biblioteca Online</p></a>
			
	</header>
	

	<div class="formRegistro">

		<h2 style="color: #6e4e37ff;">Ingresa tus datos</h2>

	<form action="../Controlador/registroControlador.php" method="POST" class="datosPerfil">
		<label for='nombre'>Nombre</label>
		<input type="text" id="nombre" name="nombre" required>

		<label for="apellido">Apellido</label>
		<input type="text" name="apellido" id="apellido" required>

		<label for="usuario">Usuario</label>
		<input type="text" id="usuario" name="usuario" required>

		<label for="contraseña">Contraseña</label>
		<input type="password" name="password" id="password" required>

		<label for="passwordRepeat">Repita la contraseña</label>
		<input type="password" name="passwordRepeat" id="passwordRepeat" required>

		<button class="boton-guardar" type="submit" name="registrar">Registrarse</button>
	</form>

<p>Ya tienes cuenta? ingresa <a href="vistaLogin.php">aqui</a></p>

<?php
	if (isset($_GET['error'])){
		$error = $_GET['error'];
		echo '<div style="color: red; margin-top: 15px;">';
		if ($error ==='passwordsNoCoinciden') {
			echo'error las constraseñas no coinciden';
		}elseif ($error === 'falloRegistro') {
			echo'El nombre de usuario ya se encuentra registrado o hubo un error en la base de datos';
		}

		echo '</div>';
	}
?>

</div>
</body>
</html>