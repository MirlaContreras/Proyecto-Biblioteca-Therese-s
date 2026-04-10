<?php

require_once '../Controlador/perfilControlador.php';


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../Estilos/estilos.css">
	<title></title>
	<style>
		.modal-container {
	    position: fixed; /* Se queda fijo al hacer scroll */
	    z-index: 1000; /* Se pone encima de todo */
	    left: 0;
	    top: 0;
	    width: 100%;
	    height: 100%;
	    overflow: auto;
	    background-color: rgba(0,0,0,0.5); /* Color negro semitransparente */
	    
	    /* Para centrar la cajita */
	    display: flex;
	    justify-content: center; /* Centrado horizontal */
	    align-items: center; /* Centrado vertical */
	}

	/* La cajita blanca del centro */
	.modal-content {
	    background-color: #fefefe;
	    padding: 20px;
	    border: 1px solid #888;
	    width: 80%;
	    max-width: 400px; /* Que no sea gigante */
	    border-radius: 10px;
	    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
	    position: relative;
	}

	/* La X para cerrar */
	.close-btn {
	    position: absolute;
	    top: 10px;
	    right: 15px;
	    color: #aaa;
	    font-size: 28px;
	    font-weight: bold;
	    cursor: pointer;
	}

	.close-btn:hover {
	    color: black;
	}
	</style>
</head>
<body>
	<header class="cabecera">
			<a href="vistaPaginaPrincipal.php"><p>Tu Biblioteca Online</p></a>
			<a id="log-out" href="../Controlador/logOutControlador.php">Cerrar sesión</a>
	</header>
<div class="contenedorPerfil">
	
	<div class="ContenedorDatosSimples">
		
			<div class="fotoPerfil">
				<?php $rutaFoto =  isset($datosUsuario['fotoPerfil']) && !empty($datosUsuario['fotoPerfil'])
				? "../Uploads/fotosPerfil/" . $datosUsuario['fotoPerfil']
				:"../Imágenes/user-default.png";
				?>

				<img src="<?php echo $rutaFoto; ?>" alt="mi foto" class="foto-redonda" width="150">

				<form action="" method="POST" enctype="multipart/form-data">
					
					<input type="file"  name="fotoPerfil" id="inputFotoOculta" accept="image/*"> 

					<button type="button" id="btn-sin-estilo" onclick="document.getElementById('inputFotoOculta').click()">
						<img src="../Imágenes/iconoEditar.png" alt="Editar foto" width="30" class="btn-editar">
					</button>

				</form>
			</div>
			
			<form action="../Controlador/perfilControlador.php" method="POST" class="datosPerfil">

				<div id="username">
					<label for="userName">username:</label>
					<input type="text" id="userName" name="userName" value="<?php echo $datosUsuario['usuario']; ?>">
					
				</div>
				<div id="nombre">
					<label for="nameUsuario">Nombre: </label>
					 <input type="text" value="<?php echo $datosUsuario['nameUsuarios']; ?>" id="nameUsuario" name="nameUsuario">
					
				</div>
				<div id="apellido">

					<label for="apellidoUsuario">Apellido: </label>
						<input type="text" value="<?php echo $datosUsuario['apellidoUsuarios']; ?>" id="apellidoUsuario" name="apellidoUsuario"></p>
					
				</div>

				<button type="submit" name="actualizarDatos" class="boton-guardar-datos">Guardar Datos</button>
		
			 </form>
		
	</div>
	
	<div class="cambiarPassword">
		<button type="button" onclick="abrirModal()" class="boton-actualizar-pass" >cambiar contraseña</button>
	</div>

</div>

<div id="modalPassword" class="modal-container" style="display:none;">
	<div class="modal-content">

		<form action="../Controlador/perfilControlador.php" method="POST" class="datosPerfil">
			<span class="close-btn" onclick="cerrarModal()">&times;</span>
			<h2>CAMBIAR CONTRASEÑA</h2>

			<label for="passActual">Contraseña Actual</label>
			<input type="password" name="passActual" id="passActual" placeholder required ="Escriba su contraseña actual">
			

			<label for="newPass">Nueva Contraseña</label>
			<input type="password" name="newPass" id="newPass" placeholder required ="Escriba su nueva contraseña">
			

			<label for="repPass">Repite la nueva contraseña</label>
			<input type="password" name="repPass" id="repPass" placeholder required ="Escriba nuevamente la nueva contraseña">
			<div class="centrar">
			<button type="submit" name="actualizarClave" class="boton-guardar-pass">Guardar Contraseña</button>
			<button type="button" onclick="cerrarModal()" class="boton-cerrar-pass">Cancelar</button>
			</div>
		</form>
	</div>
</div>

<script>
        
        document.getElementById('inputFotoOculta').addEventListener('change', function() {
            if (this.files[0]) {
                this.form.submit();
            }
        });

        function abrirModal(){
        	document.getElementById('modalPassword').style.display="flex";
        }

        function cerrarModal(){ 
        	document.getElementById("modalPassword").style.display="none"
        }

        window.onclick=function(event){
        	var modal= document.getElementById("modalPassword");
        	if (event.target == modal) {
        		modal.style.display="none";
        	}

        }
    </script>

</body>
</html>