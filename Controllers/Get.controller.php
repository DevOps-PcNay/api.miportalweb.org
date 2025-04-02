<?php
  // Para llamar a la clase de Modelos con sus respectivos metodos
  require_once "Models/Get.model.php";

  class GetController{

    // ===============================================
    // Peticiones Get SIN filtro
    // ===============================================    

    // Obteniendo informacion del modelo
    // "static" porque retornara un valor  
    static public function getData($table,$select,$orderBy,$orderMode,$startAt,$endAt)
    {
      // Se instancia la clase GetModel, para usa el metodo "getData" 
      $response = GetModel::getData($table,$select,$orderBy,$orderMode,$startAt,$endAt); // Se ejecutara el metodo "getData", por esta razon usa ::
      $return = new GetController();
      $return->fncResponse($response);     
    }

    // ===============================================
    // Peticiones Get CON filtro
    // ===============================================    
    
    

    static public function getDataFilter($table,$select,$linkTo,$equalTo,$orderBy,$orderMode,$startAt,$endAt)
    {

      // Se instancia la clase GetModel, para usa el metodo "getData" 
      $response = GetModel::getDataFilter($table,$select,$linkTo,$equalTo,$orderBy,$orderMode,$startAt,$endAt); // Se ejecutara el metodo "getData", por esta razon usa ::
      //echo "<pre>"; print_r($response); echo"</pre>";
      //return;

      $return = new GetController();
      $return->fncResponse($response);     
    }


    // Respuestas del Controlador
    public function fncResponse($response){
      if (!empty($response))
      {
        $json = array(
        'status' => '200',
        'total' => count($response),
        'result' => $response
        );
        echo json_encode($json,http_response_code($json["status"]));   

      } // if (!empty($response))
      else
      {
        $json = array(
          'status' => '404',          
          'result' => 'Not Found'
          );
          echo json_encode($json,http_response_code($json["status"]));     
      }

    } // public function fncResponse(){

  }
?>
