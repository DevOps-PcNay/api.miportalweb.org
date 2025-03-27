<?php
  // <h1>Subdominio api.miportalweb.org<h1>
  // phpinfo();

  // Mostrando los errores, cuando se utilize XAMPP
  //init_set("display_error",1);
  //init_set("log_errors");
  //ini_set("error_log","D:/xampp/htdocs/apirest-dinamica/php_error_log");

  
  // Para probar partes del codigo
  // Para verificar la clase "connection" para la base de datos.
  //require_once "Models/Connection.php";
  //echo "<pre>";print_r(Connection::connect()); echo "</pre>";
  //return

  
  require_once "Controllers/Routes.Controller.php";
  // Se realiza la instancia de la clase para poder accesar al metodo de la ruta principal (Routes.php)
  $index = new RoutesController();
  $index->index();
?>


