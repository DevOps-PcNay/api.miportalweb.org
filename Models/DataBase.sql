/*
-- Ejecutarlo desde una terminal de Mysql 
-- Cuando sea la PRIMERA VEZ que se esta generando la base de datos, se debe entrar como root en la computadora y accesar a "mysql"
		mysql -u root -p


-- Se debe accesar al directorio donde se encuentra el "script.sql" y ejecutar el comando "mysql" desde una terminal
-- $ mysql -u nom-usr -p NombreBaseDatos < script.sql
-- Otra Forma :
	Es el usuario y contraseña que se definio cuando se creo el usuario (asignando permisos para crear, borrar tablas.)

--    mysql -u usuario -p NombreBaseDatos

      mysql -u root -p 
--    source script.sql ó \. script.sql

			Borrar tabla: DROP TABLE <nombre Tabla>
			Borrar Base Datos : DROP DATABASE <nombre Base Datos>
			Borrar el contenido de la tabla : 
					truncate table nombre-tabla;
*/


/* 
Mostrar todos los usuarios 
  select user from mysql.user;

Para borrar un usuario: se tiene que ejecutar los dos comandos.
Para borrar un usuario para todos los hosts:
	drop user ventas-pos;

Para borrar un usuario en especifico
	delete from mysql.user where user = ‘ventas-pos’

Para borrar mas de un usuario en el host
	drop user ‘ventas-pos’@’localhost’;
	
	flush privileges;

BORRANDO EL CONTENIDO DE UNA TABLA EN MariaDB
	truncate table nombre-tabla;
Para mostrar los campos de una tabla:
	describe t_Responsivas;


*/

/* Tabla de Datos */
/* Se ocupa los 9 espacios, no se desperdicia espacio.*/
  /* CHAR(X) = cuando se define de algun tamaño pero no se utiliza, se despedicia espacio, por ejemplo
  CHAR(30), pero el valor de "title" es de 20, se desperdicio 60 espacios.
  VARCHAR(80) se adapta al tamaño del titulo.
  En la base de datos se puede guardar, videos, documentos en formato binario, pero creceria mucho.
  Se sube el video, documento, solo se graba la URL en el campo de la base de datos.
	
	estado INTEGER UNSIGNED DEFAULT 0,

	Tipos De Datos que maneja MySQL, MariaDb
	https://www.anerbarrena.com/tipos-dato-mysql-5024/

  */
/* DROP DATABASE IF EXISTS bd_responsivas; */

/*
CREATE DATABASE IF NOT EXISTS bd_BaseDatos3;
 /* SET time_zone = 'America/Tijuana';  */

USE bd_BaseDatos3;


/* Solo se ejecuta la primera vez.  */
CREATE USER 'usuario_basedatos3'@'localhost' IDENTIFIED BY 'basedatos3-Mar-05-2025';
GRANT ALL on bd_BaseDatos3.* to 'usuario_basedatos3'  IDENTIFIED BY 'basedatos3-Mar-05-2025';

/*

CREATE TABLE t_Cintas
(
  id_cintas SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  num_serial VARCHAR(15) NOT NULL,
  fecha_inic DATE NULL,
	fecha_final DATE NULL,
  ubicacion VARCHAR(20) NOT NULL,
	comentarios TEXT DEFAULT NULL  
);
*/