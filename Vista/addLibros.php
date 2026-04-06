<?php

require_once '../Controlador/librosControlador.php';
		$texto='';
	if (isset($_GET['mensaje'])) {
		$mensaje=$_GET['mensaje'];

		if ($mensaje === 'exito') {	
			$texto ='Libro añadido exitosamente';
		}elseif($mensaje === 'libro_registrado')
		{
			$texto="El libro ya se encuentra registrado en su biblioteca";
		}elseif($mensaje ==='errorArchivo'){		
			$texto='error al subir la imagen, verifique el formato';

		}elseif($mensaje ==='error'){	
			$texto='error';
		}
	}
	

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../Estilos/estilos.css">
	<title></title>
</head>

<body class="contAdd">
	<header class="cabecera">
			<a href="vistaPaginaPrincipal.php"><p>Tu Biblioteca Online</p></a>
			<a id="log-out" href="../Controlador/logOutControlador.php">Cerrar sesión</a>
	</header>

<div class="formAgregar">
	<div class="detalleAgregar">
	<p>Añadir un nuevo libro</p>	

	<form action="../Controlador/librosControlador.php" method="POST" enctype="multipart/form-data">
		
		<label for='titulo'>Ingresa el titulo del libro</label>
		<input name='titulo' id="titulo" autocomplete="off" required>
		<datalist id="titulo-sugerencia">
			<?php 
				if (!empty($listaLibros)) {
					foreach($listaLibros as $libro){
						echo '<option value="'. htmlspecialchars($libro['titulo']). '">';
					}
				}
			?>
		</datalist>

		<label for="autor">Ingrese el nombre del autor</label>
		<input type="text" name="autor" id="autor" required>

		<label for="genero">Ingrese el genero literario</label>
		<input type="text" name="genero" id="genero">
		<div class="selectEstado">
		<label for="estado">Estado de lectura</label>
		<select id="estado" name="estado" required>
		<option value="leyendo">Leyendo</option>
		<option value="leído">Leido</option>
		<option value="no leído">Por leer</option>		
		</select>
		</div>
		<div class="selectEstado">
		<label for="portada" class="boton-archivo">Añada una portada del libro si desea</label>
		<input class='inputEscondido' type="file" id='portada' name="imagen" accept="image/*">
		</div>
	</div>
	<div class="boton-mensaje">
		<button class="boton-guardar" type="submit" name="addLibro">Guardar</button>
		<?php if ($texto != ''): ?>
        <p id="mensaje-registro" > <?php echo $texto; ?>
        </p>
    <?php endif; ?>
    </div>
	</form>

	
	<script>
		const inputTitulo = document.getElementById('titulo');

		inputTitulo.addEventListener('input', function(){

			if (this.value.length>0) {
				this.setAttribute('list', 'titulo-sugerencia');
			}else{
				this.setAttribute('list', '');
			}
		});
	</script>
</div>
</body>
</html>