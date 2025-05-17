<?php
  require_once "Models/Connection.php";
  require_once "Controllers/Post.controller.php";

  if (isset($_POST))
  {
    //echo '<pre>';print_r($_POST);echo'</pre>';    
    $columns = array();
    // Obteniendo los indices que son los campos de la $_POST
    foreach (array_keys($_POST) as $key => $value)
    {
      // Los agrega aun arreglo
      array_push($columns,$value);
    }
    
    //echo '<pre>'; print_r($table); echo '</pre>';
    //echo '<pre>'; print_r($columns); echo '</pre>';
    // echo '<pre>'; print_r(Connection::getColumnsData($table,$columns)); echo '</pre>';

    // Valida si existe la "Tabla" y "campos de la tabla"
    if (empty(Connection::getColumnsData($table,$columns)))
    {
      $json = array(
        'status' => '400',
        'result' => 'Error: Fields in the form do not match the database'
      );

      echo json_encode($json, http_response_code($json["status"]));
      return;
    }

    // Solicitamos respuesta al Controlador para Crear Datos De Cualquier Tabla
    $response = new PostController();
    $response->postData($table,$_POST);

  } // if (isset($_POST))
?>
