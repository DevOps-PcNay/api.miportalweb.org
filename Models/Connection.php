<?php
  class Connection {
    // Informacion de la base de datos.
    // Static = Cuando se requiere almacenar para despues utilizarsse

      
    static public function infoDatabase(){
      $infoDB = array(
        "database" => "bd_BaseDatos1",
        "user" => "usuario_basedatos1",
        "pass" => "basedatos1-Mar-05-2025",
      );
      return $infoDB;

    }

    // Conexion a la Base de Datos.
    // se utiliza el PDO, los parametros se obtienen del arreglo que se definio los datos de la base de datos.
    static public function connect(){
      try {
        $link = new PDO ("mysql:host=localhost;dbname=".Connection::infoDatabase()["database"],Connection::infoDatabase()["user"], 
        Connection::infoDatabase()["pass"]
        );

        //$link->exec("set name utf8");
        $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		

        $mitz="America/Tijuana";
        $tz = (new DateTime('now', new DateTimeZone($mitz)))->format('P');
        $link->exec("SET time_zone='$tz';");					


      }catch(PDOException $e){
        die ("Error: ".$e->getMessage());
      }

      return $link;
      
    } // static public function connect(){    
  
  } // class Conecction {

