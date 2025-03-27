<?php
  // Con esta clase permitira conectar con la ruta principal en "Routes.php"
  class RoutesController{
  
    // Ruta principal
    // En este metodo se conecta con la carpeta "Routes" ue contiene la ruta principal    
    public function index(){
      include "Routes/Routes.php";

    }
  }
?>
