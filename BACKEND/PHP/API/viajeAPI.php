<?php

include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Entidades/viaje.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Interfaces/IApiUsable.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/EntidadesPDO/viajePDO.php";

include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Utils/JSONToCSV.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Utils/FPDF/fpdf.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Utils/FPDF/pdf.php";

class viajeAPI extends viaje implements IApiUsable{

    public function TraerTodos($request, $response, $args)
    {
        $viajes = viajePDO::traerviajes();
        $response = $response->withJson($viajes, 200, JSON_UNESCAPED_UNICODE);  
        
        return $response;
    }

    public function TraerUno($request, $response, $args)
    { 
        $id = $args['id'];
        $viaje = viajePDO::traerviajePorId($id);
        $response = $response->withJson($viaje, 200);  
        return $response;
    }

    public function CargarUno($request, $response, $args)
    { 
        $a = viajePDO::altaviaje($request->getParsedBody());
    
        if($a == 0){
            $newBody = [
                "Estado" => "Error",
                "Mensaje" => "No se pudo dar de alta el viaje"
            ];

            $response->getBody()->write(json_encode($newBody));
        } 
        else
        {
            $newBody = [
                "Estado" => "Ok",
                "Mensaje" => "viaje dado de alta con exito"
            ];

            $response->getBody()->write(json_encode($newBody));
        }

        return $response;
    }

    public function BorrarUno($request, $response, $args) { }

    public function ModificarUno($request, $response, $args)
    { 
        $ArrayDeParametros = $request->getParsedBody();
        $viaje = new viaje();

        $viaje->id = $ArrayDeParametros['id'];
        $viaje->estado_viaje = $ArrayDeParametros['estado_viaje'];
        $viaje->id_chofer = $ArrayDeParametros['id_chofer'];
        $viaje->id_cliente = $ArrayDeParametros['id_cliente'];
        $viaje->fecha_hora_viaje = $ArrayDeParametros['fecha_hora_viaje'];
        $viaje->origen = $ArrayDeParametros['origen'];
        $viaje->destino = $ArrayDeParametros['destino'];
        $viaje->medio_de_pago = $ArrayDeParametros['medio_de_pago'];
        $viaje->comodidad_solicitada = $ArrayDeParametros['comodidad_solicitada'];
        $viaje->cantidad_de_ascientos_solicitados = $ArrayDeParametros['cantidad_de_ascientos_solicitados'];
        $viaje->costo = $ArrayDeParametros['costo'];

        if(viajePDO::modificarviaje($viaje) != 1){
            $newBody = [
                "Estado" => "Error",
                "Mensaje" => "No se pudo modificar el viaje"
            ];

            $response->getBody()->write(json_encode($newBody));
        }
        else{
            $newBody = [
                "Estado" => "Ok",
                "Mensaje" => "viaje modificado con exito"
            ];

            $response->getBody()->write(json_encode($newBody));
        }

        return $response;
    }

}

?>