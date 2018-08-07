<?php

//include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Entidades/encuesta.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Interfaces/IApiUsable.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/EntidadesPDO/encuestaPDO.php";

//class encuestaAPI extends encuesta implements IApiUsable{
class encuestaAPI implements IApiUsable{

    public function TraerTodos($request, $response, $args)
    {
        $encuestas = encuestaPDO::traerencuestas();
        $response = $response->withJson($encuestas, 200, JSON_UNESCAPED_UNICODE);  
        
        return $response;
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $encuesta = encuestaPDO::traerencuestaPorId($id);
        $response = $response->withJson($encuesta, 200);  
        return $response;
    }

    public function CargarUno($request, $response, $args)
    {
        $a = encuestaPDO::altaencuesta( $request->getParsedBody() );
    
        if($a == 0){
            $newBody = [
                "Estado" => "Error",
                "Mensaje" => "No se pudo dar de alta el encuesta"
            ];

            $response->getBody()->write(json_encode($newBody));
        } 
        else
        {
            $newBody = [
                "Estado" => "Ok",
                "Mensaje" => "encuesta dado de alta con exito"
            ];

            $response->getBody()->write(json_encode($newBody));
        }

        return $response;
    }

    public function BorrarUno($request, $response, $args) { }

    public function ModificarUno($request, $response, $args) { }

    public function SubirImagenes($request, $response, $args){
        
        // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
        // Check MIME Type by yourself.
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
            $finfo->file($_FILES['upfile']['tmp_name']),
            array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
            ),
            true
        )) {
            throw new RuntimeException('Invalid file format.');
        }

        if (!move_uploaded_file(
            $_FILES['upfile']['tmp_name'],
            sprintf('./PHP/ImagenesEncuestas/%s',
                $_FILES['upfile']['name']
            )
        )) {
            throw new RuntimeException('Failed to move uploaded file.');
        }

        if(true){

            $newBody = [
                "Response" => "Archivo subido con exito"
            ];

        } else {

            $newBody = [
                "Estado" => "Error",
                "Mensaje" => "Datos incorrectos"
            ];

        }

        $response->getBody()->write(json_encode($newBody));
        
        return $response;

    }

}

?>