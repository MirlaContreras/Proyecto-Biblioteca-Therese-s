document.addEventListener("DOMContentLoaded", function(){

	//LOGICA DEL MODAL DE DETALLES 

	var modal= document
	.getElementById("detalleModal");
	var spanCerrar = document.getElementsByClassName('cerrarDetalle')[0];
	var libros = document.querySelectorAll(".clickable");
	var libroSelecionado = null;

	
	libros.forEach(function(libro){
		libro.onclick = function(e){

			if (e.target.classList.contains('btnAbrirFecha')) {
				return;
			}
			
            console.log("¡Clic detectado en un libro!");
			var historialId= this.dataset.historialId;
			var titulo = this.dataset.titulo;
			var autor = this.dataset
			.autor;
			var genero = this.dataset.genero;
			var estado = this.dataset.estado.trim();
			var imagenSrc= this.dataset.imagenSrc;
			libroSelecionado = this;

		
			document.getElementById("modalTitulo").textContent = titulo;
			document.getElementById("modalAutor").textContent = autor;
			document.getElementById("modalGenero").textContent = genero;
			document.getElementById("modalImagen").src = imagenSrc;
			document.getElementById("modalHistorialId").value = historialId;

			var selectEstado = document.getElementById("modalEstado");

			selectEstado.value= estado;

			modal.style.display = "block";
		}

	})

	spanCerrar.onclick = function() {
		modal.style.display = "none";

	}

	window.onclick= function(event){
		if (event.target == modal) {
			modal.style.display= "none";
		}
		if (event.target== modalComentario) {
			modalComentario.style.display="none";
		}
	}

	//Logica de ajax

	var formActualizar = document.getElementById("actualizarEstado");

	formActualizar.addEventListener("submit", function(event){

		event.preventDefault();

		var formData= new FormData(formActualizar);

		fetch('../Controlador/actualizarLibrosControlador.php', {
			method: 'POST',
			body: formData
		})

		.then(response=> response.json())
		.then(data=> {
			if (data.success) {
				console.log("Servidor dice:", data.message);
				if (libroSelecionado) {
					var nuevoEstado = document.getElementById("modalEstado").value;
					var spanEstado = libroSelecionado.querySelector(".estado");
					spanEstado.textContent= nuevoEstado.charAt(0).toUpperCase()+nuevoEstado.slice(1);
					spanEstado.classList.remove("estado-leido", "estado-leyendo", "estado-no-leido");
					if (nuevoEstado === "leído") {
						spanEstado.classList.add("estado-leido");
					}else if (nuevoEstado === "leyendo") {
						spanEstado.classList.add("estado-leyendo");

					}else if (nuevoEstado === "no leído") {
						spanEstado.classList.add("estado-no-leído");
					}

					libroSelecionado.dataset.estado = nuevoEstado;
					var btnFechaInterno= libroSelecionado.querySelector('.btnAbrirFecha');
					if (btnFechaInterno) {
						btnFechaInterno.dataset.estado= nuevoEstado;
					}

					var pFecha= libroSelecionado.querySelector(".labelFecha");
					if (pFecha) {
						pFecha.textContent="";
					}
				}

				modal.style.display = "none";
			}else{
				console.error("Error del servidor:", data.message);
				alert("Hubo un error al actualizar:" + data.message);
			}
		})
		.catch(error =>{
			console.error('error de red', error);
			alert("error de conexion.No se pudo actualizar.");




		});
	});
});


// modal fecha

	var modalFecha = document.getElementById("modalFecha");
    var spanCerrarFecha = document.getElementsByClassName('cerrarFecha')[0]; // Usamos querySelector o className
    var botonesFecha = document.querySelectorAll('.btnAbrirFecha');
    var inputFecha = document.getElementById('inputFecha');
    var inputIdHistorialFecha = document.getElementById('idHistorialFecha');
    var formFecha = document.querySelector('#modalFecha form');

    botonesFecha.forEach(function(boton){
    	boton.addEventListener('click', function(e){
    		e.preventDefault();
    		e.stopPropagation(); //evita que abra el otro modal

    		var idLibro= this.dataset.id;
    		var estadoLibro=this.dataset.estado;

    		if (estadoLibro.toLowerCase() !== 'leído') {
    			alert("solo puedes agregar la fecha si el libro ya está leído");
    			return;
    		}

    		var hoy = new Date().toISOString().split('T')[0];
    		if (inputFecha) inputFecha.setAttribute('max', hoy);

    		if (inputIdHistorialFecha) inputIdHistorialFecha.value = idLibro;
    		if (inputFecha) inputFecha.value=''; // limpiar

    		if (modalFecha) modalFecha.style.display='block';
    	});
    });

    if (spanCerrarFecha) {

    	spanCerrarFecha.onclick=function(){
    		modalFecha.style.display="none";
    	}
    }

    if (formFecha) {
    	formFecha.addEventListener('submit', function(e){
    		e.preventDefault();

    		var datosFormulario = new FormData(this);

    		fetch('../Controlador/actualizarLibrosControlador.php', {
    			method:'POST',
    			body: datosFormulario
    		})

    		.then(response=>response.json())
    		.then(data=>{
    			if (data.success) {
    				alert("fecha guardada correctamente");
    				modalFecha.style.display='none';
    				//recargar la pagina
    				location.reload();
    			}else{
    				alert("alerta: "+data.message);
    			}
    		})

    		.catch(error=>{
    			console.error('Error:', error);
    			alert("Error de conexion con el servidor.");
    		});
    	});
    }


    //MODAL COMENTARIO 

    var modalComentario = document.getElementById("modalComentario");
    var spanCerrarComentario = document.querySelector('.cerrarComentario');
    var botonesComentario= document.querySelectorAll('.btnAbrirComentario');
    var formComentario=document.getElementById('formComentario');

    var inputIdLibro=document.getElementById('idLibroInput');
    var tituloHeader= document.getElementById('tituloLibroComentario');
    var areaTexto= document.getElementById('textoComentarioInput');

   	botonesComentario.forEach(function(boton){
   		boton.addEventListener("click", function(e){
   			e.preventDefault();
   			e.stopPropagation();

   			console.log("Abriendo modal de comentario");
   			var idLibro= this.dataset.id;
   			var titulo=this.dataset.titulo;
   			var comentarioActual= this.dataset.comentario;

   			if (inputIdLibro) inputIdLibro.value= idLibro;
   			if (tituloHeader) tituloHeader.textContent="Reseña de: " + titulo;
   			if (areaTexto) areaTexto.value= comentarioActual ? comentarioActual: "";
   			if (modalComentario) modalComentario.style.display="block";   		
   		});
   	});

   	if (spanCerrarComentario) {
   		spanCerrarComentario.onclick=function(){
   			modalComentario.style.display="none";
   		}
   	}

   	if (formComentario) {
   		formComentario.addEventListener('submit', function(e){
   			e.preventDefault();
   			var datos = new FormData(this);
   			fetch('../Controlador/comentariosControlador.php', {
   				method: 'POST',
   				body: datos
   			})
   			.then(response=>response.json())
   			.then(data => {
   				if (data.success) {
   					alert("OK "+ data.message);
   					modalComentario.style.display='none';
   					location.reload();
   				}else{
   					alert("ERROR "+ data.message);
   				}
   			})
   			.catch(error=>{
   				console.error('Error: ', error);
   				alert("Error de conexion al guardar");
   			});

   		});
   	}

   	//Modal eliminar libro

   	  var eliminarLibro= document.querySelectorAll('.eliminarLibro');
   	  var spanCerrar =document.querySelectorAll('.cerrarEliminar');
   	  var modalPrincipal= document.getElementById('modalEliminarLibro');

   	  var inputOculto= document.getElementById("modalHistorialIdE");
	
	eliminarLibro.forEach(function(eliminar){
		eliminar.addEventListener("click", function(e){
			e.preventDefault();
   			e.stopPropagation();
   			var idCorrecto= this.getAttribute("data-id");
   			inputOculto.value=idCorrecto;
   			console.log(idCorrecto);
   			modalPrincipal.style.display = "flex";

		});

	});
	spanCerrar.forEach(function(boton){
		boton.onclick = function() {
		modalPrincipal.style.display  = "none";

			}
	})
	