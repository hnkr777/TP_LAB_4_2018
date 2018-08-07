<?php

include_once $_SERVER['DOCUMENT_ROOT']."/PHP/EntidadesPDO/auxiliaresPDO.php";

class auxiliaresAPI {

    public function TraerAscientos($request, $response, $args)
    {
        $a = auxiliaresPDO::TraerAscientos();
        $response = $response->withJson($a, 200, JSON_UNESCAPED_UNICODE);  
        
        return $response;
    }

    public function TraerCalificaciones($request, $response, $args)
    {
        $a = auxiliaresPDO::TraerCalificaciones();
        $response = $response->withJson($a, 200, JSON_UNESCAPED_UNICODE);  
        
        return $response;
    }

    public function TraerEstadosViaje($request, $response, $args)
    {
        $a = auxiliaresPDO::TraerEstadosViaje();
        $response = $response->withJson($a, 200, JSON_UNESCAPED_UNICODE);  
        
        return $response;
    }

    public function TraerMediosDePago($request, $response, $args)
    {
        $a = auxiliaresPDO::TraerMediosDePago();
        $response = $response->withJson($a, 200, JSON_UNESCAPED_UNICODE);  
        
        return $response;
    }

    public function TraerNivelesComodidad($request, $response, $args)
    {
        $a = auxiliaresPDO::TraerNivelesComodidad();
        $response = $response->withJson($a, 200, JSON_UNESCAPED_UNICODE);  
        
        return $response;
    }

    public function TraerPerfiles($request, $response, $args)
    {
        $a = auxiliaresPDO::TraerPerfiles();
        $response = $response->withJson($a, 200, JSON_UNESCAPED_UNICODE);  
        
        return $response;
    }

}


?>