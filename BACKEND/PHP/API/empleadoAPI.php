<?php

include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Entidades/empleado.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Interfaces/IApiUsable.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/EntidadesPDO/empleadoPDO.php";

include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Utils/JSONToCSV.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Utils/FPDF/fpdf.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Utils/FPDF/pdf.php";

class empleadoAPI extends empleado implements IApiUsable{

    public function TraerTodos($request, $response, $args)
    {
        $empleados = empleadoPDO::traerEmpleados();
        $response = $response->withJson($empleados, 200, JSON_UNESCAPED_UNICODE);  
        
        return $response;
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $empleado = empleadoPDO::traerEmpleadoPorId($id);
        $response = $response->withJson($empleado, 200);  
        return $response;
    }

    public function CargarUno($request, $response, $args)
    { 
        $ArrayDeParametros = $request->getParsedBody();
        $empleado = new empleado();
        $empleado->email = $ArrayDeParametros['email'];
        $empleado->password = $ArrayDeParametros['password'];
        $empleado->perfil = $ArrayDeParametros['perfil'];
        if(empleadoPDO::altaEmpleado($empleado) != 1){
            $newBody = [
                "Estado" => "Error",
                "Mensaje" => "No se pudo dar de alta el empleado"
            ];

            $response->getBody()->write(json_encode($newBody));
        }
        else{
            $newBody = [
                "Estado" => "Ok",
                "Mensaje" => "Empleado dado de alta con exito"
            ];

            $response->getBody()->write(json_encode($newBody));
        }

        return $response;
    }

    public function BorrarUno($request, $response, $args) { }

    public function ModificarUno($request, $response, $args)
    { 
        $ArrayDeParametros = $request->getParsedBody();
        $empleado = new empleado();
        $empleado->id = $ArrayDeParametros['id'];
        $empleado->email = $ArrayDeParametros['email'];
        $empleado->password = $ArrayDeParametros['password'];
        $empleado->perfil = $ArrayDeParametros['perfil'];
        $empleado->suspendido = $ArrayDeParametros['suspendido'];     

        if(empleadoPDO::modificarEmpleado($empleado) != 1){
            $newBody = [
                "Estado" => "Error",
                "Mensaje" => "No se pudo modificar el empleado"
            ];

            $response->getBody()->write(json_encode($newBody));
        }
        else{
            $newBody = [
                "Estado" => "Ok",
                "Mensaje" => "Empleado modificado con exito"
            ];

            $response->getBody()->write(json_encode($newBody));
        }

        return $response;
    }

    public function RemiserosDisponibles($request, $response, $args)
    {
        $empleados = empleadoPDO::RemiserosDisponibles();
        $response = $response->withJson($empleados, 200, JSON_UNESCAPED_UNICODE);  
        
        return $response;
    }

}




?>