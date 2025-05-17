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

    // =================================
    // Validar Existencia de una Tabla 
    // =================================
    static public function getColumnsData($table,$columns)
    {
      // Obteniendo la base de datos
      $database = Connection::infoDatabase()["database"];
      
      // Obtener las columnas de la Tablas
      $validate = Connection::connect()->query("SELECT COLUMN_NAME AS item FROM information_schema.columns WHERE table_schema = '$database' AND table_name = '$table'")->fetchAll(PDO::FETCH_OBJ);

      if(empty($validate))
      {
        return null;
      }
      else
      {
        // Ajustes de seleccion de columna globales.
        if ($columns[0] == "*")
        {
          // Eliminando el primer indice del arreglo
          array_shift($columns);

        }
        $sum = 0;
        foreach($validate as $key => $value)
        {
          //in_array($value->item,$columns)
          // echo '<pre>';print_r(in_array($value->item,$columns));echo'</pre>';       }
          $sum += in_array($value->item,$columns);
        }

        //echo '<pre>';print_r($sum);echo'</pre>';
        return $sum == count($columns) ? $validate : null;

      }

    } // static public function getColumnsData($table,$columns)

  } // class Connection {

