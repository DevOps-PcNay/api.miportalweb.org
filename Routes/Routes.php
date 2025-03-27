<?php

  // Para mostrar que contiene la URL despues del nombre del dominio.
  //echo '<pre>'; print_r($_SERVER['REQUEST_URI']); echo '</pre>';
  //return;


  // Para convertir el texto que se obtiene despues del dominio.
  $routesArray = explode("/",$_SERVER['REQUEST_URI']);
  $routesArray = array_filter($routesArray);

  //echo '<pre>'; print_r($routesArray); echo '</pre>';
  // Para obtener el tipo de servicio Http que se le envia a la URL.
  //echo '<pre>'; print_r($_SERVER['REQUEST_METHOD']); echo '</pre>';
  //return;

  // ==================================================
  // Cuando no se hacen peticiones a la API
  // ==================================================

  // Cuando en la URL, Dominio no agregan informacion (tablas)

  if (empty($routesArray))
  {
    $json = array(
      'status' => 404,
      'result' => 'Not Found'
    );

    // Para convertirlo a formato JSon el arreglo (variables)
    // Para cambiar el valor del resultado del programa que se utiliza para probar los Endpoint (Postman, Rest Client), se modifica este valor
    // echo json_encode($json);
    echo json_encode($json,http_response_code($json["status"]));
    
    return;

  }  // if (empty($routesArray))

  // ==================================================
  // Cuando no se hacen peticiones a la API
  // ==================================================

  // Si se le pasa como parametro un nombre de tabla.
  if ((count($routesArray)== 1) && (isset($_SERVER['REQUEST_METHOD']))){
    //echo '<pre>'; print_r($_SERVER['REQUEST_METHOD']); echo '</pre>';
    if ($_SERVER['REQUEST_METHOD'] == "GET"){
      // Es por medio el cual se relaciona "Rutas" con 
      include "Services/Get.php";             
    }
    
    if ($_SERVER['REQUEST_METHOD'] == "POST"){
      $joson = array(
        'status' => '200',
        'result' => 'Solicitud POST'
      );
      echo json_encode($json,http_response_code($json["status"]));            
    }
    if ($_SERVER['REQUEST_METHOD'] == "PUT"){
      $joson = array(
        'status' => '200',
        'result' => 'Solicitud PUT'
      );
      echo json_encode($json,http_response_code($json["status"]));            
    }
    if ($_SERVER['REQUEST_METHOD'] == "DELETE"){
      $joson = array(
        'status' => '200',
        'result' => 'Solicitud POST'
      );
      echo json_encode($json,http_response_code($json["status"]));            
    }


  } // if ((count($routesArray)== 1) && (isset($_SERVER['REQUEST_METHOD']))){
  



?>

