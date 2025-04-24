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


    // ===================================================================
    // Peticiones GET sin filtro entre tabla Relacionadas
    // =================================================================

    static public function getRelData($rel,$type,$select,$orderBy,$orderMode,$startAt,$endAt)
    {

      // Obteniendo los nombres de las tabla (que se envian en "rel")

      $relArray = explode(",",$rel);
      //Indice 0 = contiene la tabla principal
      //echo "<pre>";print_r($relArray);echo"</pre>";
      
      $typeArray = explode(",",$type);
      //echo "<pre>";print_r($typeArray);echo"</pre>";
      
      //return

      // Generando las relaciones de forma dinamica.

      $innerJoinText = "";

      // Se hara las modificaciones para el caso de que se coloque mas de dos AND
      if (count($relArray)>1)
      {
        foreach ($relArray as $key => $value)
        {
          if ($key > 0) // No se toma encuenta el indice 0, porque es a tabla principal
          {
            $innerJoinText .= "INNER JOIN ".$value." ON ".$relArray[0].".id_".$typeArray[$key]."_".$typeArray[0]." =".$value.".id_".$typeArray[$key]." ";
          }
        } // foreach ($relArray as $key => $value){
      
        //return;


        // Se crea la relacion entre dos tablas.
        //"SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $relArray[0].id_$typeArray[1]_$typeArray[0] = $relArray[1].id_$typeArray[1]";


        // Sin Ordernar y limitar Datos.
        $sql = "SELECT $select FROM $relArray[0] $innerJoinText";

        // Cuando se ordene y NO se limite los registros a mostrar
        if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null))
        {
          $sql = "SELECT $select FROM $relArray[0] $innerJoinText ORDER BY $orderBy $orderMode";
        }

        // Cuando se ordene y se limite los registros a mostrar
        if (($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null))
        {
          $sql = "SELECT $select FROM $relArray[0] $innerJoinText ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt";
        }

        // Cuando NO se ordene y se limite los registros a mostrar
        if (($orderBy == null) && ($orderMode == null) && ($startAt != null) && ($endAt != null))
        {
          $sql = "SELECT $select FROM $relArray[0] $innerJoinText LIMIT $startAt,$endAt";
        }

        // Preparando la conexion
        $stmt = Connection::connect()->prepare($sql);
        $stmt->execute();
        // PDO::FETCH_CLASS = Para que muestre las columnas de la tabla.
        return $stmt->fetchAll(PDO::FETCH_CLASS);     

      } // (count($relArray)>1)
      else 
      {
        return null;
      }

    }  // static public function getRelData($rel,$type,$select,$orderBy,$orderMode,$startAt,$endAt)

    // ===================================================================
    // Peticiones GET CON filtro entre tabla Relacionadas
    // =================================================================

    static public function getRelDataFilter($rel,$type,$select,$linkTo,$equalTo,$orderBy,$orderMode,$startAt,$endAt)
    {
      // Se organizan los filtros.

      $linkToArray = explode(",",$linkTo);
      //echo "<pre>"; print_r($linkToArray); echo "</pre>";

      $equalToArray = explode("_",$equalTo);
      //echo "<pre>"; print_r($equalToArray); echo "</pre>";
      //return
      $linkToText = "";


      // Se hara las modificaciones para el caso de que se coloque mas de dos AND
      if (count($linkToArray)>1){
        foreach ($linkToArray as $key => $value){
          if ($key > 0){
            $linkToText .= "AND ".$value." = :".$value." ";
          }
        } // foreach ($linkToArray as $key => $value){
      } // foreach ($linkToArray as $key => $value)

      // Se organizan las Relacione.

      // Obteniendo los nombres de las tabla (que se envian en "rel")
      $relArray = explode(",",$rel);
      //Indice 0 = contiene la tabla principal
      //echo "<pre>";print_r($relArray);echo"</pre>";
      
      $typeArray = explode(",",$type);
      //echo "<pre>";print_r($typeArray);echo"</pre>";
      
      //return

      // Generando las relaciones de forma dinamica.

      $innerJoinText = "";

      // Se hara las modificaciones para el caso de que se coloque mas de dos AND
      if (count($relArray)>1)
      {
        foreach ($relArray as $key => $value)
        {
          if ($key > 0) // No se toma encuenta el indice 0, porque es a tabla principal
          {
            $innerJoinText .= "INNER JOIN ".$value." ON ".$relArray[0].".id_".$typeArray[$key]."_".$typeArray[0]." =".$value.".id_".$typeArray[$key]." ";
          }
        } // foreach ($relArray as $key => $value){
      
        //return;


        // Se crea la relacion entre dos tablas.
        //"SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $relArray[0].id_$typeArray[1]_$typeArray[0] = $relArray[1].id_$typeArray[1]";


        // Sin Ordernar y limitar Datos.
        $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] = :$linkToArray[0] $linkToText";

        // Cuando se ordene y NO se limite los registros a mostrar
        if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null))
        {
          $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode";
        }

        // Cuando se ordene y se limite los registros a mostrar
        if (($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null))
        {
          $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt";
        }

        // Cuando NO se ordene y se limite los registros a mostrar
        if (($orderBy == null) && ($orderMode == null) && ($startAt != null) && ($endAt != null))
        {
          $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] = :$linkToArray[0] $linkToText LIMIT $startAt,$endAt";
        }

        // Preparando la conexion
        $stmt = Connection::connect()->prepare($sql);
        
        // Ahora agregandolo a la sentencia "bindParam"
        //$stmt -> bindParam(":$linkTo",$equalTo, PDO::PARAM_STR);
        foreach ($linkToArray as $key => $value){
          $stmt -> bindParam(":".$value,$equalToArray[$key], PDO::PARAM_STR); // Genera de forma diamica los "bindParam"
        }

        $stmt->execute();
        // PDO::FETCH_CLASS = Para que muestre las columnas de la tabla.
        return $stmt->fetchAll(PDO::FETCH_CLASS);     

      } // (count($relArray)>1)
      else 
      {
        return null;
      }

    }  // static public function getRelData($rel,$type,$select,$orderBy,$orderMode,$startAt,$endAt)

    // ============================================
    // Peticion GET para el buscador SIN relaciones
    // ============================================

    static public function getDataSearch($table,$select,$linkTo,$search,$orderBy,$orderMode,$startAt,$endAt)
    {
      // En el caso de que se envien varias condiciones en la URL
      //&linkTo="title_course,id_instructor_course";
      //&equalTo="Gussi,ghost_2";

      // Se presenta un problema cuando en "$equalTo = "Textos, Texto2, Texto3,Texto 4" venga con comas 

      $linkToArray = explode(",",$linkTo);
      //echo "<pre>"; print_r($linkToArray); echo "</pre>";

      $searchArray = explode("_",$search);
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
      
  
      $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText";

      // Cuando se ordene y NO se limite los registros a mostrar
      if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null)){
        $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode";
      }

      // Cuando se ordene y se limite los registros a mostrar
      if (($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null)){
        $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt";
      }

      // Cuando NO se ordene y se limite los registros a mostrar
      if (($orderBy == null) && ($orderMode == null) && ($startAt != null) && ($endAt != null)){
        $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText LIMIT $startAt,$endAt";
      }

      // Preparando la conexion
      $stmt = Connection::connect()->prepare($sql);

      // Ahora agregandolo a la sentencia "bindParam"
      //$stmt -> bindParam(":$linkTo",$equalTo, PDO::PARAM_STR);
      foreach ($linkToArray as $key => $value){
        // Debe ser apartir del Indice 1, esta contiene el nombre de la columna 
        if ($key > 0) {
          $stmt -> bindParam(":".$value,$searchArray[$key], PDO::PARAM_STR); // Genera de forma diamica los "bindParam"
        }

      }      

      $stmt->execute();
      // PDO::FETCH_CLASS = Para que muestre las columnas de la tabla.
      return $stmt->fetchAll(PDO::FETCH_CLASS);     

      
    } //static public function getDataSearch($table,$select,$linkTo,$search,$orderBy,$orderMode,$startAt,&endAt)

    // ===================================================================
    // Peticiones GET CON filtro entre tabla Relacionadas
    // =================================================================

    static public function getRelDataSearch($rel,$type,$select,$linkTo,$search,$orderBy,$orderMode,$startAt,$endAt)
    {

         // Se presenta un problema cuando en "$equalTo = "Textos, Texto2, Texto3,Texto 4" venga con comas 

         $linkToArray = explode(",",$linkTo);
         //echo "<pre>"; print_r($linkToArray); echo "</pre>";
   
         $searchArray = explode("_",$search);
         //echo "<pre>"; print_r($equalToArray); echo "</pre>";
         $linkToText = "";
   
   
         // Se hara las modificaciones para el caso de que se coloque mas de dos AND
         if (count($linkToArray)>1){
           foreach ($linkToArray as $key => $value){
             if ($key > 0){
               $linkToText .= "AND ".$value." = :".$value." ";
             }
           } // foreach ($linkToArray as $key => $value){
         } // foreach ($linkToArray as $key => $va

      // Se organizan las Relacione.

      // Obteniendo los nombres de las tabla (que se envian en "rel")
      $relArray = explode(",",$rel);
      //Indice 0 = contiene la tabla principal
      //echo "<pre>";print_r($relArray);echo"</pre>";
      
      $typeArray = explode(",",$type);
      //echo "<pre>";print_r($typeArray);echo"</pre>";
      
      //return

      // Generando las relaciones de forma dinamica.

      $innerJoinText = "";

      // Se hara las modificaciones para el caso de que se coloque mas de dos AND
      if (count($relArray)>1)
      {
        foreach ($relArray as $key => $value)
        {
          if ($key > 0) // No se toma encuenta el indice 0, porque es a tabla principal
          {
            $innerJoinText .= "INNER JOIN ".$value." ON ".$relArray[0].".id_".$typeArray[$key]."_".$typeArray[0]." =".$value.".id_".$typeArray[$key]." ";
          }
        } // foreach ($relArray as $key => $value){
      
        //return;


        // Se crea la relacion entre dos tablas.
        //"SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $relArray[0].id_$typeArray[1]_$typeArray[0] = $relArray[1].id_$typeArray[1]";


        // Sin Ordernar y limitar Datos.
        $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText";

        // Cuando se ordene y NO se limite los registros a mostrar
        if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null))
        {
          $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode";
        }

        // Cuando se ordene y se limite los registros a mostrar
        if (($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null))
        {
          $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt";
        }

        // Cuando NO se ordene y se limite los registros a mostrar
        if (($orderBy == null) && ($orderMode == null) && ($startAt != null) && ($endAt != null))
        {
          $sql = "SELECT $select FROM $relArray[0] $innerJoinText WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText LIMIT $startAt,$endAt";
        }

        // Preparando la conexion
        $stmt = Connection::connect()->prepare($sql);
        
      // Ahora agregandolo a la sentencia "bindParam"
      //$stmt -> bindParam(":$linkTo",$equalTo, PDO::PARAM_STR);
      foreach ($linkToArray as $key => $value){
        // Debe ser apartir del Indice 1, esta contiene el nombre de la columna 
        if ($key > 0) {
          $stmt -> bindParam(":".$value,$searchArray[$key], PDO::PARAM_STR); // Genera de forma diamica los "bindParam"
        }
      }      

        $stmt->execute();
        // PDO::FETCH_CLASS = Para que muestre las columnas de la tabla.
        return $stmt->fetchAll(PDO::FETCH_CLASS);     

      } // (count($relArray)>1)
      else 
      {
        return null;
      }

    }  // static public function getRelData($rel,$type,$select,$orderBy,$orderMode,$startAt,$endAt)


    // ===================================================================
    // Peticiones GET para Rangos
    // =================================================================

    static public function getDataRange($table,$select,$linkTo,$between1,$between2,$orderBy,$orderMode,$startAt,$endAt,$filterTo,$inTo)
    {
      $filter = "";
      if (($filterTo != null) && ($inTo != null))
      {
        $filter = 'AND '.$filterTo.' IN ('.$inTo.')';
      }

      $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter";

      // Cuando se ordene y NO se limite los registros a mostrar
      if (($orderBy != null) && ($orderMode != null) && ($startAt == null) && ($endAt == null)){
        $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter ORDER BY $orderBy $orderMode";
      }

      // Cuando se ordene y se limite los registros a mostrar
      if (($orderBy != null) && ($orderMode != null) && ($startAt != null) && ($endAt != null)){
        $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt";
      }

      // Cuando NO se ordene y se limite los registros a mostrar
      if (($orderBy == null) && ($orderMode == null) && ($startAt != null) && ($endAt != null)){
        $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' $filter LIMIT $startAt,$endAt";
      }

      // Preparando la conexion
      $stmt = Connection::connect()->prepare($sql);
      $stmt->execute();
      // PDO::FETCH_CLASS = Para que muestre las columnas de la tabla.
      return $stmt->fetchAll(PDO::FETCH_CLASS);     
    
    }



  } // GetModel
?>