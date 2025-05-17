<?php
  require_once "Models/Post.model.php";
  
  class PostController 
  {
    // Peticiones POST para crear datos.
    static public function postData($table,$data)
    {
      // Se solicita una respuesta del modelo.
      $response = PostModel::postData($table,$data);
      echo '<pre>'; print_r($response); echo '</pre>';
      return;

    } // static public function postData($table,$data)
  }
?>
