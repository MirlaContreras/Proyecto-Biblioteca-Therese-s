<?php
//Incluimos el archivo que contiene la conexion
require_once 'conexion.php';

class manejadorUsuarios {

	private $conexion;
	public function __construct(){
		//Obtenemos la conexion global
		global $conexion;
		$this->conexion = $conexion;
	}

	/* REGISTRO */

	public function registrarUsuarios($nombre, $apellido, $usuario, $passwordHash){

		//Usamos prepared statments por seguridad (evita inyeccion SQL)

		$sql = "INSERT INTO usuarios(nameUsuarios, apellidoUsuarios, usuario, password) VALUES (?,?,?,?)";
		$stmt= $this->conexion->prepare($sql);

		if ($stmt === FALSE) {
			return false;
		}

		//Enlaza los parametros ('ssss' indica strings)
		$stmt->bind_param("ssss", $nombre, $apellido, $usuario, $passwordHash);

		$resultado = $stmt->execute();

		$stmt->close();
		return $resultado;
	}

	/*LOGIN*/

	public function buscarUsuario($usuario){
		$sql = "SELECT idUsuarios, nameUsuarios, apellidoUsuarios, usuario, password ,fotoPerfil from usuarios where usuario=?";

		$stmt= $this->conexion->prepare($sql);

		if ($stmt === FALSE) {
			return null;
		}

		$stmt->bind_param("s", $usuario);
		$stmt->execute();

		$resultado = $stmt->get_result();

		if($resultado->num_rows===1){
			$usuarioData = $resultado->fetch_assoc();
			$stmt->close();
			return $usuarioData;
		}

		$stmt->close();
		return null; //usuario no encontrado
	}

		public function buscarIdUsuario($idUsuario){
		$sql = "SELECT idUsuarios, nameUsuarios, apellidoUsuarios, usuario, password ,fotoPerfil from usuarios where idUsuarios=?";

		$stmt= $this->conexion->prepare($sql);

		if ($stmt === FALSE) {
			return null;
		}

		$stmt->bind_param("s", $idUsuario);
		$stmt->execute();

		$resultado = $stmt->get_result();

		if($resultado->num_rows===1){
			$usuarioData = $resultado->fetch_assoc();
			$stmt->close();
			return $usuarioData;
		}

		$stmt->close();
		return null; //usuario no encontrado
	}

	public function actualizarFotoPerfil($usuario, $fotoPerfil){

		$sql= "UPDATE usuarios SET fotoPerfil =? where usuario=?";

		$stmt = $this->conexion->prepare($sql);

		if ($stmt === FALSE) {
			echo "Error en prepare: ".$thie->conexion->error;
			return false;
		}

		$stmt->bind_param("ss", $fotoPerfil, $usuario);
		$resultado= $stmt->execute();

		$stmt->close();

		return $resultado;
		

	}


	public function actualizarDatosUsuarios( $userName, $nombreUsuario, $apellidoUsuario, $idUsuarios){

        try{
            $sql = "UPDATE usuarios
                    SET usuario = ?,
                    nameUsuarios=?,
                    apellidoUsuarios=?
                    WHERE idUsuarios = ? ";

            $stmt = $this->conexion->prepare($sql);

            if (!$stmt) {
                // CORRECCIÓN 1: Agregamos el '$' que faltaba antes de 'this'
                error_log("Error al preparar la consulta: " . $this->conexion->error);
                return false;
            }

            $stmt->bind_param("sssi", $userName, $nombreUsuario, $apellidoUsuario, $idUsuarios);

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

    public function actualizarPassword($idUsuario, $nuevoHash){


    	$sql="UPDATE usuarios SET password=? WHERE idUsuarios=?";

    	$stmt= $this->conexion->prepare($sql);

    	if ($stmt === FALSE) {
    		return false;
    	}

    	$stmt->bind_param("si", $nuevoHash, $idUsuario);

    	$resultado= $stmt->execute();
    	$stmt->close();

    	return $resultado;
    }
}

