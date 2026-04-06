<?php

 if (session_status() === PHP_SESSION_NONE){
 	session_start();
 } //INICIA SESION DE PHP SI NO EXISTE

 if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !==true) {
 	header("Location: vistaLogin.php");
 	exit();
 } //VERIFICA QUE ESTE LOGEADO
// inclusion de los archivos necesarios 

require_once '../Modelo/manejadorLibros.php';
require_once '../Modelo/conexion.php';


$manejadorLibros= new manejadorLibros();
$listaLibros= $manejadorLibros->obtenerTituloLibros();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addLibro'])) {
	//OBTENCION DE DATOS

	$titulo = htmlspecialchars($_POST['titulo']);
    $autor = htmlspecialchars($_POST['autor']);
    $genero = htmlspecialchars($_POST['genero']);
    $estado = $_POST['estado']; // 'estado' no necesita sanitización si viene de un select controlado
    
    // Obtiene el ID del usuario de la sesión (asegúrate de que exista en la sesión)
    $idUsuarios = $_SESSION['idUsuarios']; 

    // Inicializa la variable para la imagen a null
    $rutaParaDB = null;

	//Verificar si se subio un archivo

	if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
		//CONVERTIMOS A BINARIOS BLOB
		$fileTmpPath= $_FILES['imagen']['tmp_name'];
		$fileName= $_FILES['imagen']['name'];
	}

	//Logica de almacenamiento

	$directorioDestinoServidor=__DIR__.'/../Uploads/Portadas/';

	$extension=pathinfo($fileName, PATHINFO_EXTENSION);
	$nombreArchivoUnico="portada_".$idUsuarios."_".time().".".$extension;
	$rutaDestinoServidor= $directorioDestinoServidor. $nombreArchivoUnico;

	if (move_uploaded_file($fileTmpPath, $rutaDestinoServidor)) {
		$rutaParaDB='Uploads/Portadas/'.$nombreArchivoUnico;
	}else{
		header("Location:../Vista/addLibros.php?mensaje=errorArchivo");
	}

	//VALIDACION 

	if(empty($titulo) || empty($idUsuarios)) {
		header("Location:../Vista/addLibros.php?mensaje=errorTOI");
		exit();
	}

	

	$registroExitoso = $manejadorLibros->registrarLibroHistorial(
		$titulo,
		$autor,
		$genero,
		$rutaParaDB,
		$idUsuarios,
		$estado
		
	);

	if ($registroExitoso == 'exito') {
		header("Location:../Vista/addLibros.php?mensaje=exito");
		exit();
	}elseif($registroExitoso == 'libro_registrado'){
		header("Location:../Vista/addLibros.php?mensaje=libro_registrado");
		exit();
	}elseif($registroExitoso == 'error'){
		header("Location:../Vista/addLibros.php?mensaje=error");
		exit();
	}
}
