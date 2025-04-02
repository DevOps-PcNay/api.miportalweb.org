<?php
  //  Para poder llamar a las clases que se requieren llamar desde el "Controllers"
  require_once "Controllers/Get.controller.php";

  // obteniendo la tabla
  //echo "<pre>";print_r($routesArray[1]); echo "</pre>";
  //return

  // Para el caso de que se envie a la URL parametros para  mostrar en las peticiones GET solo algunas columnas, se tiene que separar y asignarlo en arreglos.
  //$table = $routesArray[1];
  // $routesArray[1][0] = Para obtener solamentente el nombre de la tabla.
  $table = explode("?",$routesArray[1])[0];

  //echo "<pre>";print_r($table); echo "</pre>";
  //return;
  
  //$select = "*";
  //$select = $_GET["select"]??"*";
  // Verificando si viene la variable Super Global $_GET["orderBy"]
  $orderBy = $_GET["orderBy"] ?? null;    // Si no viene el valor $_GET["orderBy"] le asigna un "null"
  $orderMode = $_GET["orderMode"] ?? null;    // Si no viene el valor $_GET["orderMode"] le asigna un "null"

  // Para limitar los registros a mostrar 
  $startAt = $_GET["startAt"] ?? null;    // Si no viene el valor $_GET["startAt"] le asigna un null
  $endAt = $_GET["endAt"] ?? null;    // Si no viene el valor $_GET["endAt"] le asigna un null

  // Se verificara si viene una variable super global de tipo GET con el parametro "select"
  // Si no se manda esta variable "select", tomara el valor de "*"
  if (isset($_GET["select"])){
    if ($_GET["select"]== "*"){    
      $select = "*";
     }
     else {
      $select = $_GET["select"];
    }  
    //echo"<pre>";print_r($select);echo"</pre>";
    //return false;
  }
  else {
    $select = "*";
  }


 
  //echo"<pre>";print_r($select);echo"</pre>";
    //return false;
 
  
  // Otra forma de llamar la funcion:
  //$response = Getcontroller::getData($table);
  // Esta se tiene que enviar al "Controllers", a traves de :
  $response = new GetController();


  // ===============================================
  // Peticiones Get CON filtro
  // ===============================================
  // Verificando si viene una variable super global "linkTo"
  
  if (isset($_GET["linkTo"]) && isset($_GET["equalTo"])){
    $response->getDataFilter($table,$select,$_GET["linkTo"],$_GET["equalTo"],$orderBy,$orderMode,$startAt,$endAt);
  } // if (isset($_GET["linkTo"]) && isset($_GET["equalTo"])){
  else
  {
    // ===============================================
    // Peticiones Get SIN filtro
    // ===============================================
    //$select = "*";
    $response->getData($table,$select,$orderBy,$orderMode,$startAt,$endAt);
  }


  
  //echo "<pre>";print_r($response); echo "</pre>";
  //return;
  
?>