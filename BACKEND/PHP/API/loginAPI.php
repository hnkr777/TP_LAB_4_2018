<?php

include_once $_SERVER['DOCUMENT_ROOT']."/PHP/Interfaces/IApiUsable.php";

class loginAPI implements IApiUsable{

    public function TraerTodos($request, $response, $args){ }
    
    public function TraerUno($request, $response, $args){ } 

    /**
    * @api {post} /login CargarUno
    * @apiVersion 0.1.0
    * @apiName CargarUno
    * @apiGroup loginAPI
    * @apiDescription Logea al usuario, creando un session token, y crea un nuevo registro de login en el sistema
    *
    * @apiParam {String} email  Email del usuario
    * @apiParam {String} password Password del usuario
    *
    * @apiExample Como usarlo:
    *   ->post('[/]', \loginAPI::class . ':CargarUno')
    * @apiSuccess {Object} SessionToken SessionToken del usuario
    * @apiError userNotFound Usuario no encontrado, datos incorrectos o faltantes
    * @apiErrorExample No se encontro al usuario:
    *     {
    *       "Estado" => "Error",
    *       "Mensaje" => "Datos incorrectos"
    *     }
    */
    public function CargarUno($request, $response, $args)
    { 
        $ArrayDeParametros = $request->getParsedBody();
        $email = isset($ArrayDeParametros['email']) ? $ArrayDeParametros['email'] : null;
        $password = isset($ArrayDeParametros['password']) ? $ArrayDeParametros['password'] : null;
        
        if( !isset($email) || !isset($password) ){
            $retorno = [
                "Estado" => "Error",
                "Mensaje" => "Los parametros de email y password son obligatorios"
            ];

            //$retorno = array('error'=> "Usuario deshabilitado." );
            return $response->withJson( $retorno, 409 );
            //$response->getBody()->write(json_encode($newBody));
        } else {

            if
                ( 
                    $email == "" || 
                    $email == null ||
                    $password == "" || 
                    $password == null 
                )
            {
                $retorno = [
                    "Estado" => "Error",
                    "Mensaje" => "Los parametros de email y password no pueden estar vacios"
                ];

                return $response->withJson( $retorno, 409 );
                //$response->getBody()->write(json_encode($newBody));
            }

            else

            {
                
                
                if ( empleadoPDO::empleadoValidation($email,$password) != 0 ) 
                {

                    if( empleadoPDO::traerEstadoSuspendidoEmpleadoPorEmail($email) == 0 )
                    {
                        
                        $idcliente = empleadoPDO::traerEmpleadoPorEmailYPassword($email,$password);

                        $cliente = empleadoPDO::traerEmpleadoPorId($idcliente);
                        $profile;

                        if($cliente[0]->perfil == 1){
                            $profile = "cliente";
                        } else if($cliente[0]->perfil == 2){
                            $profile = "encargado";
                        } else if($cliente[0]->perfil == 3){
                            $profile = "remisero";
                        }

                        $newcliente = [
                            "email" => $cliente[0]->email,
                            "perfil" => $profile
                        ];

                        $newBody = [
                            "SessionToken" => validationAPI::CrearToken($newcliente),
                            "perfil" => $profile
                        ];

                        $response->getBody()->write(json_encode($newBody));
                        
                    } 
                    else 
                    {
                        
                        $retorno = [
                            "Estado" => "Error",
                            "Mensaje" => "Acceso denegado: usuario suspendido"
                        ];

                        //$response->getBody()->write(json_encode($newBody));    
                        return $response->withJson( $retorno, 403 );
                    }

                } else {

                    $retorno = [
                        "Estado" => "Error",
                        "Mensaje" => "Acceso denegado"
                    ];

                    //$response->getBody()->write(json_encode($newBody));
                    return $response->withJson( $retorno, 401 );
                }

            }

        }
        
        return $response;
       
    }

    public function BorrarUno($request, $response, $args){ }

    public function ModificarUno($request, $response, $args){ }

}




?>