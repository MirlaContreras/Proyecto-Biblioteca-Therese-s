<?php
session_start();
//Control de acceso: Si no esta logeado lo dirige al login
require_once'../Modelo/manejadorLibros.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
	header("Location:vistaLogin.php");
	exit();
}
//El nombre del usuario logueado lo obtenemos de la sesion 
$nombreUsuario = $_SESSION['nameUsuarios'];
$idUsuario= $_SESSION['idUsuarios'];
$manejador=new manejadorLibros();

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../Estilos/estilos.css">
	<title></title>
</head>

<body class="resumenLibros">

	<header class="cabecera">
			<a href="vistaPaginaPrincipal.php"><p>Tu Biblioteca Online</p></a>
			<a id="log-out" href="../Controlador/logOutControlador.php">Cerrar sesión</a>
	</header>
	<nav class="barraNavegador">
		<ul class="navegador">
			<li id="verde"><a href="addLibros.php">Añadir Libro</a></li>
			<li id="marron"><a href="historial.php">Ver mi historial</a></li>
			<li id="beige"><a href="perfil.php">Mi perfil</a></li>
		</ul>	
	</nav>

		
			<main>
				<div id="resumenDash">
				<p style="font-weight:600; ">Resumen</p>
				<p>Total de libros leídos:
						<?php 
					echo $res= $manejador->totalLibros($idUsuario);
					?>		
				</p>
				</div>

				<div class="carrusel">
					
					<article class="contCat" id="leidos">
						<p>Leídos: <?php
						echo $res= $manejador->contarLibros($idUsuario,'leído');
						$resumen= $manejador->resumenLibro($idUsuario, 'leído');
						?> </p>
						<?php
						if (empty($resumen)) :
						?>

							<p>Aun no tienes libros agregados, añade algunos</p>

						<?php
						else:				

						foreach ($resumen as $libro):
						?>
						<div class="tarjetaDash">
						<img src="../<?php echo htmlspecialchars($libro['ruta_imagen'] ?? '/Uploads/Portadas/portadaDefault.png'); ?>"
						alt="portada de <?php echo htmlspecialchars($libro['titulo']); ?>">
						<div class="detalleLibroDash"> 		
							<p id="tituloLibroDash"><?php echo htmlspecialchars($libro['titulo']) ?></p>
							<p>Autor: <?php echo htmlspecialchars($libro['autor']) ?></p>
							<p>Genero: <?php echo htmlspecialchars($libro['genero']) ?></p>	
							</div>
						</div>
							<?php
						endforeach; //fin del bucle
						endif; //fin del if empty
						
					?>				
					</article>

					

					<article class="contCat" id="leyendo">
						<p>Leyendo: <?php
						echo $res= $manejador->contarLibros($idUsuario,'leyendo');
						$resumen= $manejador->resumenLibro($idUsuario, 'leyendo');
						?> </p>
						<?php
						if (empty($resumen)) :
						?>

							<p>Aun no tienes libros agregados, añade algunos</p>

						<?php
						else:				

						foreach ($resumen as $libro):
						?>
						<div class="tarjetaDash">
						<img src="../<?php echo htmlspecialchars($libro['ruta_imagen'] ?? '/Uploads/Portadas/portadaDefault.png'); ?>"
						alt="portada de <?php echo htmlspecialchars($libro['titulo']); ?>">
						<div class="detalleLibroDash"> 		
							<p id="tituloLibroDash"><?php echo htmlspecialchars($libro['titulo']) ?></p>
							<p>Autor: <?php echo htmlspecialchars($libro['autor']) ?></p>
							<p>Genero: <?php echo htmlspecialchars($libro['genero']) ?></p>	
							</div>
						</div>
							<?php
						endforeach; //fin del bucle
						endif; //fin del if empty
						
					?>		
					</article>

					

					<article class="contCat"  id="por-leer">
						<p>Libros Por Leer: <?php
						echo $res= $manejador->contarLibros($idUsuario,'no leído');
						$resumen= $manejador->resumenLibro($idUsuario, 'no leído');
						?> </p>
						<?php
						if (empty($resumen)) :
						?>

							<p>Aun no tienes libros agregados, añade algunos</p>

						<?php
						else:				

						foreach ($resumen as $libro):
						?>
						<div class="tarjetaDash">
						<img src="../<?php echo htmlspecialchars($libro['ruta_imagen'] ?? '/Uploads/Portadas/portadaDefault.png'); ?>"
						alt="portada de <?php echo htmlspecialchars($libro['titulo']); ?>">
						<div class="detalleLibroDash"> 		
							<p id="tituloLibroDash"><?php echo htmlspecialchars($libro['titulo']) ?></p>
							<p>Autor: <?php echo htmlspecialchars($libro['autor']) ?></p>
							<p>Genero: <?php echo htmlspecialchars($libro['genero']) ?></p>	
							</div>
						</div>
							<?php
						endforeach; //fin del bucle
						endif; //fin del if empty
						
					?>		
					</article>
				</div>
			</main>
		
			<footer class="main-footer">
			    <div class="footer-container">
			       

			        <div class="footer-credits">
			            <p>Proyecto Final - Diplomado de Programación Web, UNEWEB.</p>
			        </div>
			        <div class="footer-copyright">
			            <p>&copy; <?php echo date("Y"); ?> Therese´s Biblioteca. Todos los derechos reservados.</p>
			        </div>
			    </div>
			</footer>
</body>
</html>