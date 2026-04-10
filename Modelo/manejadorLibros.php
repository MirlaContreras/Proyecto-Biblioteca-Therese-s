<?php

require_once 'conexion.php';
 
class manejadorLibros{

	private $conexion;

	public function __construct()
	{
		global $conexion;
		if (!$conexion instanceof mysqli) {
			die("error fatal: La conexion a la base de datos no es valido en manejadorLibros");
		}
		$this->conexion = $conexion;
	}

	/** 
	 *Registra un nuevo libro y su estado en el historial del usuario.
	 * @param string $titulo, $autor, $genero, $estado
	 * @param int $idUsuario ID del usuario logueado.
	 * @param mixed $image_blob Datos binarios de la imagen o  NULL
	 * @return bool True si el registro fue exitoso
	 */

	private function normalizarTexto($texto){
		$texto = mb_strtolower($texto, 'UTF-8');

		$texto = str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
        ['a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u'],
        $texto);

        $texto = preg_replace('/[^a-z0-9]/', '', $texto);

        return ($texto);

	}


	public function registrarLibroHistorial($titulo, $autor, $genero,$ruta_imagen,  $idUsuarios_registro, $estado){

		$tituloNormalizado = $this->normalizarTexto($titulo);

		$sqlBuscar= "SELECT idLibros FROM libros WHERE busqueda = ?";
		$stmt= $this->conexion->prepare($sqlBuscar);
		$stmt->bind_param("s", $tituloNormalizado);
		$stmt->execute();
		$resultado = $stmt->get_result();

		$idLibroFinal = 0; 

		if ($fila = $resultado->fetch_assoc()) {
			$idLibroFinal= $fila['idLibros'];
			$stmt->close();
		} else{

			$stmt->close();
			$sqlLibro= "INSERT INTO libros(titulo, busqueda, autor, genero, ruta_imagen, idUsuarios_registro) VALUES (?,?,?,?,?,?)";
			$stmtLibro= $this->conexion->prepare($sqlLibro);
			$stmtLibro->bind_param("sssssi", $titulo, $tituloNormalizado, $autor, $genero, $ruta_imagen, $idUsuarios_registro);

			if ($stmtLibro->execute()) {
			$idLibroFinal = $this->conexion->insert_id;
			$stmtLibro->close();
			}else{
				return false;
			}
		}

		//de aqui para abajo 
		
		$sqlCheckHistorial = "SELECT idHistorial FROM historiallibros WHERE idLibros = ? AND idUsuarios=?";
		$stmtCheck= $this->conexion->prepare($sqlCheckHistorial);
		$stmtCheck->bind_param("ii", $idLibroFinal, $idUsuarios_registro);
		$stmtCheck->execute();
		$stmtCheck->store_result();

		if ($stmtCheck->num_rows > 0) {
			$stmtCheck->close();
			return 'libro_registrado';
		}

		$stmtCheck->close();

		
		//2. REGISTRAR EL EVENTO DEL HISTORIAL

		$sqlHistorial = "INSERT INTO historiallibros(idLibros, idUsuarios, estado) VALUES (?,?,?)";
		$stmtHistorial= $this->conexion->prepare($sqlHistorial);


		if ($stmtHistorial === FALSE) {
			error_log("Error al ejecutar insercion en el historial". $this->conexion->error);
			return "error";
		}

		$stmtHistorial->bind_param("iis", $idLibroFinal, $idUsuarios_registro, $estado);

		if (!$stmtHistorial->execute()) {
			error_log("error al ejecutar insercion en historial" . $stmtHistorial->error);
			$stmtHistorial->close();
			return "error";
		}

		$stmtHistorial->close();
		//Si llegamos hasta aqui ambas inserciones fueron exitosas
		return "exito";

	}

	public function obtenerTituloLibros(){
		$sql="SELECT titulo FROM libros ORDER BY titulo ASC";
		$resultado= $this->conexion->query($sql);

		$libros= [];
		while ($fila = $resultado->fetch_assoc()){
			$libros[]=$fila;
		}

		return $libros;
	}

	public function obtenerHistorial($idUsuarios, $busqueda=null)
	{
		$historial=[];//Array para almacenar los resultados
		$sql ="SELECT 
				h.idHistorial,
				l.titulo,
				l.idLibros,
				l.autor,
				l.genero,
				l.ruta_imagen,
				h.estado,
				h.fechaLectura,
				c.comentario
			FROM
				historiallibros h
			JOIN
				libros l ON h.idLibros = l.idLibros
			LEFT JOIN comentarios c ON h.idLibros = c.idLibros AND h.idUsuarios = c.idUsuarios
			WHERE
				h.idUsuarios = ?";

			if ($busqueda != null) {
				$sql.= " AND l.titulo LIKE ? ";
			}
			$sql.= " ORDER BY h.idHistorial DESC";
			$stmt = $this->conexion->prepare($sql);
			if (!$stmt) {
   			 die("Error en la consulta SQL: " . $this->conexion->error);
			} 

		if ($busqueda != null) {
			$termino = "%" . $busqueda . "%";
			$stmt->bind_param("is", $idUsuarios, $termino);
		}else{
			$stmt->bind_param("i", $idUsuarios);
		}
		if ($stmt === FALSE) {
			error_log("Error al prerparar la consulta de historial:". $this->conexion->error);
			return $historial;
		}
		
		if (!$stmt->execute()) {
			error_log("error al ejecutar la consulta:". $stmt->error);
			return $historial;
		}
		//4. Obtenemos el resultado
		$resultado=$stmt->get_result();
		$historial = $resultado->fetch_all(MYSQLI_ASSOC);
		$stmt->close();
		//6. Devolvemos la lista

		return $historial;
	}


    public function actualizarEstado($historialId, $nuevoEstado, $idUsuario){

        try{
            $sql = "UPDATE historiallibros
                    SET estado = ?, fechaLectura=NULL
                    WHERE idHistorial = ? AND idUsuarios = ?";

            $stmt = $this->conexion->prepare($sql);

            if (!$stmt) {
                // CORRECCIÓN 1: Agregamos el '$' que faltaba antes de 'this'
                error_log("Error al preparar la consulta: " . $this->conexion->error);
                return false;
            }

            $stmt->bind_param("sii", $nuevoEstado, $historialId, $idUsuario);

            // CORRECCIÓN 2 (LÓGICA):
            // Guardamos el resultado de la ejecución (true o false)
            $resultado = $stmt->execute();

            $stmt->close();

            // Devolvemos true si la orden se ejecutó, sin importar si cambió filas o no.
            return $resultado;

        } catch (Exception $e){
            error_log("Error en actualizarEstado: " . $e->getMessage());
            return false;
        }
    }

	//APARTIR DE AQUI DESCUBRI PDO (Objetos de Datos de PHP).

	public function actualizarFecha($idHistorial, $fechaManual, $idUsuario){

		$sql="UPDATE historiallibros
			 SET fechaLectura = ?, estado = 'leído'
			 WHERE idHistorial = ? AND idUsuarios=?";

		$stmt = $this->conexion->prepare($sql);

		if ($stmt===false) {
			die("error en la consulta". $this->conexion->error);
		}

		$stmt->bind_param("sii", $fechaManual, $idHistorial, $idUsuario);

		if ($stmt->execute()) {
			return true;
		}else {
			echo "error al ejecutar". $stmt->error;
			return 'error3';
		}
	}




	public function guardarComentario($idLibro, $idUsuario, $comentario){
		$sqlcheck="SELECT idComentarios FROM comentarios Where idLibros=? AND idUsuarios=?";
		$stmtCheck=$this->conexion->prepare($sqlcheck);
		$stmtCheck->bind_param("ii", $idLibro, $idUsuario);
		$stmtCheck->execute();
		$stmtCheck->store_result();

		if ($stmtCheck->num_rows>0 ) {
			$sql="UPDATE comentarios set comentario=?, fecha=NOW() WHERE idUsuarios = ? AND idLibros=? ";
			$stmt = $this->conexion->prepare($sql);
			$stmt->bind_param("sii", $comentario, $idUsuario, $idLibro);
		}else{
			$sql="INSERT INTO comentarios (idLibros, idUsuarios, comentario, fecha) VALUES (?,?,?, NOW())";
			$stmt=$this->conexion->prepare($sql);
			$stmt->bind_param("iis", $idLibro, $idUsuario, $comentario);
		}

		return $stmt->execute();

	}

	public function contarLibros($idUsuario, $estado){

		$sql= 'SELECT COUNT(*) AS TOTAL from historiallibros where idUsuarios= ? AND estado = ?';
		$stmt= $this->conexion->prepare($sql);
		$stmt->bind_param("is", $idUsuario, $estado);

		$stmt->execute();

		$resultado= $stmt->get_result();
		$fila= $resultado->fetch_assoc();
		$stmt->close();

		return $fila['TOTAL'];


	}


	public function totalLibros($idUsuario){

		$sql= "SELECT COUNT(*) AS TOTAL from historiallibros WHERE idUsuarios= ?";
		$stmt= $this->conexion->prepare($sql);
		$stmt->bind_param("i", $idUsuario);
		$stmt->execute();
		$resultado=$stmt->get_result();
		$contador= $resultado->fetch_assoc();
		$stmt->close();

		return $contador['TOTAL'];

	}

	public function resumenLibro($idUsuario, $estado){
		$sql="SELECT h.idHistorial, l.titulo, l.autor, l.genero, ruta_imagen FROM historiallibros h JOIN
		libros l ON h.idLibros = l.idLibros
		WHERE h.idUsuarios= ? AND h.estado=?";

		$stmt= $this->conexion->prepare($sql);

		if ($stmt== false) {
			die("error en la consulta". $this->conexion->error);
		}
		$stmt->bind_param("is", $idUsuario, $estado);
		if (!$stmt->execute()) {
			echo "error al ejecutar".$stmt->error;
			return false;
		}
		$resultado=$stmt->get_result();
		$historial = $resultado->fetch_all(MYSQLI_ASSOC);
		$stmt->close();

		return $historial;
	}

	public function eliminarLibro($idHistorial){

		$sql="DELETE FROM historiallibros WHERE idHistorial = ?";
		$stmt= $this->conexion->prepare($sql);

		if ($stmt == false) {
			die("error en la consulta". $this->conexion->error);
				return false;
		}

		$stmt->bind_param("i", $idHistorial);
		if (!$stmt->execute()) {
			echo "error al ejecutar la consulta".$stmt->error;
			return false;
		}
		$stmt->close();
		return true;
	}
	
}
	

	