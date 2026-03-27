/* Creación de la base de datos */
CREATE DATABASE proyectoBiblioteca;
USE proyectoBiblioteca;

/* TABLA 1: USUARIOS */

CREATE TABLE usuarios (
	idUsuarios INT AUTO_INCREMENT PRIMARY KEY, 
	nameUsuarios VARCHAR(60) NOT NULL, 
	apellidoUsuarios VARCHAR(50) NOT NULL,
	usuario VARCHAR(50) NOT NULL UNIQUE, 
	password VARCHAR(255) NOT NULL /*importante para el hash*/ 
);

/* TABLA 2: LIBROS*/
CREATE TABLE libros(
	idLibros INT AUTO_INCREMENT PRIMARY KEY, 
	titulo VARCHAR(200) not NULL,
	autor VARCHAR(100),
	genero VARCHAR(100),
	imagen MEDIUMBLOB, /*usamos MEDIUMBLOB o BLOB para almacenar imagenes*/
	idUsuarios_registro INT,
	FOREIGN KEY (idUsuarios_registro) REFERENCES usuarios(idUsuarios)
	ON DELETE SET NULL ON UPDATE CASCADE 
	);

/* TABLA 3: COMENTARIOS */
CREATE TABLE comentarios(
	idComentarios INT AUTO_INCREMENT PRIMARY KEY,
	idLibros INT NOT NULL, 
	idUsuarios INT NOT NULL,
	comentario TEXT NOT NULL, 
	fecha DATETIME NOT NULL, 
	FOREIGN KEY(idUsuarios) REFERENCES usuarios(idUsuarios)
	ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (idLibros) REFERENCES libros(idLibros)
	ON DELETE CASCADE ON UPDATE CASCADE
);

/* TABLA 4: HISTORIAL LIBROS (para registrar el estado de la lectura de ada usuario)*/
CREATE TABLE historialLibros(
	idHistorial INT AUTO_INCREMENT PRIMARY KEY,
	idLibros INT NOT NULL,
	idUsuarios INT NOT NULL,
	estado ENUM('leído' , 'no leído', 'leyendo') NOT NULL, 
	fechaLectura Date,
	FOREIGN KEY (idUsuarios) REFERENCES usuarios(idUsuarios)
	ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (idLibros) REFERENCES libros(idLibros)
	ON DELETE CASCADE ON UPDATE CASCADE
);

