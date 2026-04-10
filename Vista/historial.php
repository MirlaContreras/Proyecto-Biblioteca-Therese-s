<?php

require_once'../Controlador/historialControlador.php';

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Mi Historial</title>
		<link rel="stylesheet" href="../Estilos/estilos.css">
		<link rel="stylesheet" href="../Estilos/estilosHistorial.css">
	</head>
	<body style="background-color: #a9a251ff">

		<header class="cabecera">
			<a href="vistaPaginaPrincipal.php"><p>Tu Biblioteca Online</p></a>
			<a id="log-out" href="../Controlador/logOutControlador.php">Cerrar sesión</a>
		</header>
		<div class="containerHistorial">
			<div class="barraBusqueda">
				<h2>Mi historial de lectura</h2>

				<form method="GET" action="historial.php" >
					<label for='busqueda'>Buscar un libro</label>
					<input type="text" id="busqueda" name="busqueda" autocomplete="off"  placeholder="Ingresa el nombre del libro" 
					value="<?php echo isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ""; ?>" required>
					
					<datalist id="titulo-sugerencia">
						<?php 
							if (!empty($listaLibros)) {
								foreach($listaLibros as $libro){
									echo '<option value="'. htmlspecialchars($libro['titulo']). '">';
								}
							}
						?>
					</datalist>
					<button type="submit" id="btn-sin-estilo"><img src="../Imágenes/buscar.png" alt="Editar foto" width="30" class="btn-editar"></button>
				
				<?php 
				if(isset($_GET['busqueda'])): ?>
				<a href="historial.php"><button type="button" id="btn-sin-estilo" >Ver todos</button></a>

				<?php endif; ?>

				</form>
			</div>
			<?php
			if (isset($errorControlador)) :
			
			?>

			<p style="color: red;"<?php echo $errorControlador; ?> >
			</p>


			<?php 
			elseif (empty($historial)) :
			?>

				<p>Aun no tienes libros agregados, añade algunos</p>

			<?php
			else:				

			foreach ($historial as $libro):
			?>

			<div class="libro-item clickable"
				data-historial-id= "<?php echo $libro['idHistorial']; ?>"
				data-titulo= "<?php echo htmlspecialchars($libro['titulo']); ?>"
				data-autor= "<?php echo htmlspecialchars($libro['autor']); ?>"
				data-genero= "<?php echo htmlspecialchars($libro['genero']); ?>"
				data-estado= "<?php echo htmlspecialchars($libro['estado']); ?>"	
				data-imagen-src="../<?php echo htmlspecialchars($libro['ruta_imagen'] ?? 'Imágenes/default.png');?>">

				<img src="../<?php echo htmlspecialchars($libro['ruta_imagen'] ?? 'Imágenes/default.png'); ?>" 
				alt="portada de <?php echo htmlspecialchars($libro['titulo']); ?>">

				<div class="libro-info">
						<h3><?php ($libro['titulo']); ?></h3>
						<p><strong>Autor:</strong> <?php echo htmlspecialchars($libro['autor']) ?> </p>
						<p><strong>Genero:</strong> <?php echo htmlspecialchars($libro['genero']) ?> </p>
						<p class="labelFecha"><strong>fecha de lectura:</strong> <?php echo ($libro['fechaLectura']) ?> </p>

					<?php 

					//logica para asignar la clase css segun el estado 
					$claseEstado='';
					if ($libro['estado'] == 'leyendo') {
						$claseEstado='leyendo';
					}elseif($libro['estado'] == 'leído'){
						$claseEstado='leido';
					}elseif($libro['estado'] == 'no leído'){
						$claseEstado='no-leido';
					}
					
					?>

					<p><strong>Estado</strong>
					<span class="estado <?php echo $claseEstado;  ?>"><?php echo htmlspecialchars(ucfirst($libro['estado']))?></span>
					</p>

					<button type="button"
							class="btnAbrirFecha boton-guardar-datos"
							id="btn-sin-estilo"
							style="margin-top:10px; cursor:pointer;"
							data-id="<?php echo $libro['idHistorial'];?>" data-estado="<?php echo $libro['estado'];?>">
					Añadir o actualizar fecha de lectura
					</button>
				</div> <?php //libro-info?>

				<div class="area-comentario">
					<button type="button" class="btnAbrirComentario" 
					data-id="<?php echo $libro["idLibros"];?>"
					data-comentario="<?php echo htmlspecialchars($libro["comentario"] ?? "");?>">

					añadir / editar reseña
						
					</button>
					<div class="caja-comentario" style="background: #FDFDFD; border: 1PX solid #EEE; padding:8PX ; font-size: 0.85EM; color: #555; font-style: italic; min-height: 40PX;">

						<?php
							echo !empty($libro['comentario'])
							? htmlspecialchars($libro['comentario'])
							:"Sin reseña...";
						?>
					</div><?php //cierre COMENTARIO SOLO?>

				</div><?php //cierre contenedor comentario?>

				<div class="borrarLibro">
					<img class="eliminarLibro" src="../Imágenes/eliminar.png" alt="eliminar" data-id="<?php echo $libro['idHistorial'];?>" >
				</div>

			</div> <?php //cierre contenedor clikable?>
			<?php 
			endforeach; //fin del bucle
			endif; //fin del if empty
			?>
		</div><?php //cierre contenedor padre?>
		<div id="detalleModal" class="modal">
			
			<div class="modalContenido">
				<span class="cerrarDetalle">&times;</span>

				<div class="modalCuerpo">
					<img id="modalImagen" src="" alt="Portada del libro" style="max-width: 150px ;">

				<h2 id="modalTitulo"></h2>
				<p><strong>Autor:</strong> <span id="modalAutor"></span></p>
				
				
				<p><strong>Genero:</strong> <span id="modalGenero"></span></p>

				<form id="actualizarEstado">
					<p><strong>Estado:</strong>
						<select id="modalEstado" name="estado" style="width: 100%; min-width: 200px; padding: 8px; font-size: 16px;">
						<option value="leído">Leído</option>
						<option value="leyendo">Leyendo</option>
						<option value="no leído">No Leído</option>
						</select>
					</p>

					<input type="hidden" id="modalHistorialId" name="historialId">

					<button type="submit" class="boton-guardar-datos">Actualizar estado</button>
				</form>

				</div>
			</div>
		</div>

		<div id="modalFecha" class="modal">
		    <div class="modalContenido">
		        <span class="cerrarFecha" style="float:right; cursor: pointer; font-size: 28px; font-weight: bold;">&times;</span>
		        <h3>Seleccionar fecha de lectura</h3>
		        
		        <form action="../Controlador/actualizarLibrosControlador.php">
		            <input type="hidden" id="idHistorialFecha" name="historialId">
		            <input type="hidden" name="estado" value="leído">
		            
		            <label for="inputFecha">¿Cuándo lo terminaste?</label>
		            <input type="date" id="inputFecha" name="fecha_lectura" required style="width: 100%; padding: 8px; margin: 10px 0;">

		            <button type="submit" style="width: 100%; padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Guardar Fecha</button>
		        </form>
		    </div>
		</div>

		<div id="modalComentario" class="modal" style="display:none; position: fixed; z-index: 9999; left: 0; top: 0;width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);">
			<div class="modalContenido">
				<span class="cerrarComentario" style="float: right; cursor: pointer; font-weight: bold;">&times;</span>

				<h3 id="tituloLibroComentario">Reseña del Libro</h3>

				<form id="formComentario">
					<input type="hidden" id="idLibroInput" name="historialId">
					<label style="font-weight: bold;">Deja tu opinion:</label>
					<textarea id="textoComentarioInput" name="comentario" rows="5"
					style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-top: 5px; resize: vertical;" placeholder="deja aqui tu comentario"></textarea>
					<button type="submit" style="width: 100%; padding: 10px; background-color: #fefaf9ff; color: white; border: none; border-radius: 5px; cursor: pointer; margin-top: 15px; font-weight: bold; font-size: 16px;">Guardar Reseña
					</button>
				</form>
			</div> 
		</div>

		<div id="modalEliminarLibro" class="modal" style="align-items: flex-start;">
		    <div class="modalContenido">
		        <span class="cerrarEliminar" style="float:right; cursor: pointer; font-size: 28px; font-weight: bold;">&times;</span>
		        <h3>Está seguro que desea eliminar el libro?</h3>
		        <form action="../Controlador/actualizarLibrosControlador.php" method="POST">
		            <input type="hidden" id="modalHistorialIdE" name="historialId" value="">

		            <button type="submit" name="eliminarLibro" style="width: 200px; height: 50px; margin-top: 15px; margin-right: 15px; background-color: #44442aff;color: white;font-size: 1.5rem;border: none;">Sí</button>
		            <button type="button" class="cerrarEliminar" style="width: 200px; height: 50px; margin-top: 15px; margin-right: 15px; background-color: #a9a251ff;color: white;font-size: 1.5rem;border: none;">No</button>
		        </form>
		    </div>
		</div>
			<script>
			const inputBusqueda = document.getElementById('busqueda');

			inputBusqueda.addEventListener('input', function(){

				if (this.value.length>0) {
					this.setAttribute('list', 'titulo-sugerencia');
				}else{
					this.setAttribute('list', '');
				}
			});
			</script>
		<script src="../JS/historial.js"></script>
	</body>
</html>