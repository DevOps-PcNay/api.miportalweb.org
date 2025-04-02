<?php
  require_once "Connection.php";

  class GetModel{
    // Obtiene todos los datos 

    // ===============================================
    // Peticiones Get SIN filtro
    // ===============================================    

    static public function getData($table,$select,$orderBy,$orderMode,$startAt,$endAt)
    {
      $sql = "SELECT $select FROM $table";

      // Cuando se ordene y NO se limite los registros a mostrar
      if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null)){
        $sql = "SELECT $select FROM $table ORDER BY $orderBy $orderMode";
      }

      // Cuando se ordene y se limite los registros a mostrar
      if (($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null)){
        $sql = "SELECT $select FROM $table ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt";
      }

      // Cuando NO se ordene y se limite los registros a mostrar
      if (($orderBy == null) && ($orderMode == null) && ($startAt != null) && ($endAt != null)){
        $sql = "SELECT $select FROM $table LIMIT $startAt,$endAt";
      }

      // Preparando la conexion
      $stmt = Connection::connect()->prepare($sql);
      $stmt->execute();
      // PDO::FETCH_CLASS = Para que muestre las columnas de la tabla.
      return $stmt->fetchAll(PDO::FETCH_CLASS);     
    }

    // ===============================================
    // Peticiones Get CON filtro
    // ===============================================    

    static public function getDataFilter($table,$select,$linkTo,$equalTo,$orderBy,$orderMode,$startAt,$endAt)
    {

      // En el caso de que se envien varias condiciones en la URL
      //&linkTo="title_course,id_instructor_course";
      //&equalTo="Gussi,ghost_2";

      // Se presenta un problema cuando en "$equalTo = "Textos, Texto2, Texto3,Texto 4" venga con comas 

      $linkToArray = explode(",",$linkTo);
      //echo "<pre>"; print_r($linkToArray); echo "</pre>";

      $equalToArray = explode("_",$equalTo);
      //echo "<pre>"; print_r($equalToArray); echo "</pre>";
      $linkToText = "";


      // Se hara las modificaciones para el caso de que se coloque mas de dos AND
      if (count($linkToArray)>1){
        foreach ($linkToArray as $key => $value){
          if ($key > 0){
            $linkToText .= "AND ".$value." = :".$value." ";
          }
        } // foreach ($linkToArray as $key => $value){
      } // foreach ($linkToArray as $key => $value)
      //return;

      // Sin Ordenar y limitar Datos.
      $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText";

      // Cuando se ordene y sin limite los registros a mostrar
      if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null)){
        $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode";
      }

      // Cuando se ordene y se limite los registros a mostrar
      if (($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null)){
        $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt";
      }

      // Cuando no se ordena y se limite los registros a mostrar
      if (($orderBy == null) && ($orderMode == null) && ($startAt != null) && ($endAt != null)){
        $sql = "SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText LIMIT $startAt,$endAt";
      }

      // Mostrando el contenido de la sentencia "SQL"
      //echo "<pre>";print_r($sql);echo"</pre>";
      //return;

      
      // Preparando la conexion
      $stmt = Connection::connect()->prepare($sql);

      // Para enlazar parametros.
      //$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);

      // Ahora agregandolo a la sentencia "bindParam"
      //$stmt -> bindParam(":$linkTo",$equalTo, PDO::PARAM_STR);
      foreach ($linkToArray as $key => $value){
        $stmt -> bindParam(":".$value,$equalToArray[$key], PDO::PARAM_STR); // Genera de forma diamica los "bindParam"
      }

      $stmt -> execute();
      // PDO::FETCH_CLASS = Para que muestre las columnas de la tabla.
      return $stmt->fetchAll(PDO::FETCH_CLASS);     
    }

  } // GetModel
?>