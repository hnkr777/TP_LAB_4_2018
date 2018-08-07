<?php

include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Entidades/vehiculo.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Interfaces/IApiUsable.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/EntidadesPDO/vehiculoPDO.php";

include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Utils/JSONToCSV.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Utils/FPDF/fpdf.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Utils/FPDF/pdf.php";

class vehiculoAPI extends vehiculo implements IApiUsable{

    public function TraerTodos($request, $response, $args)
    {
        $vehiculos = vehiculoPDO::traervehiculos();
        $response = $response->withJson($vehiculos, 200, JSON_UNESCAPED_UNICODE);  
        
        return $response;
    }

    public function TraerUno($request, $response, $args)
    { 
        $id = $args['id'];
        $vehiculo = vehiculoPDO::traerVehiculoPorId($id);
        $response = $response->withJson($vehiculo, 200);  
        return $response;
    }

    public function CargarUno($request, $response, $args)
    { 
        $ArrayDeParametros = $request->getParsedBody();
        $vehiculo = new vehiculo();
        $vehiculo->remisero = $ArrayDeParametros['remisero'];
        $vehiculo->nivel_comodidad = $ArrayDeParametros['nivel_comodidad'];
        $vehiculo->ascientos_disponibles = $ArrayDeParametros['ascientos_disponibles'];

        $a = vehiculoPDO::altaVehiculo($vehiculo);
    
        if($a == 0){
            $newBody = [
                "Estado" => "Error",
                "Mensaje" => "No se pudo dar de alta el vehiculo"
            ];

            $response->getBody()->write(json_encode($newBody));
        } 
        else
        {
            $newBody = [
                "Estado" => "Ok",
                "Mensaje" => "Vehiculo dado de alta con exito"
            ];

            $response->getBody()->write(json_encode($newBody));
        }

        return $response;
    }

    public function BorrarUno($request, $response, $args) { }

    public function ModificarUno($request, $response, $args)
    { 
        $ArrayDeParametros = $request->getParsedBody();
        $vehiculo = new vehiculo();
        $vehiculo->id = $ArrayDeParametros['id'];
        $vehiculo->remisero = $ArrayDeParametros['remisero'];
        $vehiculo->nivel_comodidad = $ArrayDeParametros['nivel_comodidad'];
        $vehiculo->ascientos_disponibles = $ArrayDeParametros['ascientos_disponibles'];
        $vehiculo->suspendido = $ArrayDeParametros['suspendido'];
        
        if(vehiculoPDO::modificarVehiculo($vehiculo) != 1){
            $newBody = [
                "Estado" => "Error",
                "Mensaje" => "No se pudo modificar el vehiculo"
            ];

            $response->getBody()->write(json_encode($newBody));
        }
        else{
            $newBody = [
                "Estado" => "Ok",
                "Mensaje" => "Vehiculo modificado con exito"
            ];

            $response->getBody()->write(json_encode($newBody));
        }

        return $response;
    }

}

?>