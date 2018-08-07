<?php
require_once 'entidad.php';
require_once 'IApiUsuario.php';
require_once 'MWparaAutentificar.php';
require_once "AutentificadorJWT.php";
//require_once './fpdf/fpdf.php';
//require_once './fpdf/pdf.php';

class usuariosApi extends entidad implements IApiUsuario
{

/**
 * @api {post} /usuarios/ingresos/{id} Devuelve todos los ingresos, si se pasa un ID devuelve sólo los de ese ID
 * @apiName ingresos
 * @apiGroup usuarios/ingresos
 *
 * @apiHeader {String} token User access unique JWT token.
 * 
 * @apiHeaderExample {json} Header-Example:
 *     {
 *       "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MTIxNzc4MTEsImV4cCI6MTUxMjI2NDIxMSwiYXVkIjoiZjhiNjY2ZjNhYjhlZjQzMGJhNWFiYjhkNjBiMTU4ZDJkYzQwNjExOCIsImRhdGEiOnsiSUQiOjEsImVtYWlsIjoiYWRtaW4iLCJwZXJmaWwiOiJhZG1pbiIsIm5vbWJyZSI6IkFkbWluaXN0cmFkb3IiLCJhcGVsbGlkbyI6IkFETUlOIiwic2V4byI6MCwidHVybm8iOiJUIiwiZm90byI6bnVsbCwiaGFiaWxpdGFkbyI6MSwiZmVjaGFfY3JlYWRvIjoiMjAxNy0xMS0yNSAxNDoyOToyNCJ9LCJhcHAiOiJUUCBieSBIb25la2VyIn0.seE4LHGMrw2MroQo1OT8wD3BiFrNXN4wNbc2fr0IDIE"
 *     }
 * 
 * @apiParam {Int} id Identificador del usuario del cual mostrar la información.
 * @apiParam {String} fecha_desde Fecha desde la cual filtrar los resultados, el formato es 'YYYY-mm-dd hh:ii:ss', se puede excluir la hora.
 * @apiParam {String} fecha_hasta Fecha hasta la cual filtrar los resultados, no la incluye, el formato es 'YYYY-mm-dd hh:ii:ss', se puede excluir la hora.
 *
 * @apiSuccess {String} file Archivo CSV generado con la consulta.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     [
 *       {
 *         "ID": 23,
 *         "ingreso_usuarioID": 4,
 *         "fecha_ingreso": "2017-11-24 01:46:38",
 *         "nombre": "Vlad",
 *         "apellido": "Tepes"
 *       },
 *       {
 *         "ID": 111,
 *         "ingreso_usuarioID": 4,
 *         "fecha_ingreso": "2017-11-27 21:55:14",
 *         "nombre": "Vlad",
 *         "apellido": "Tepes"
 *       }
 *     ]
 *
 * @apiError NoHayIngresosRegistrados La consulta requerida no trajo resultados.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "error": "No hay ingresos registrados."
 *     }
 * 
 */
    public function ingresos($request, $response, $args)
    {
        $Arr = $request->getParsedBody();

        if(isset($args['id']))
        {
            $id = $args['id'];
            $entidad = entidad::TraerIngresos($id);
        }
        elseif(isset($Arr['fecha_desde']) && isset($Arr['fecha_hasta']))
        {
            $desde = $Arr['fecha_desde'];
            $hasta = $Arr['fecha_hasta'];
            $entidad = entidad::TraerIngresos(null, $desde, $hasta);
        }
        else
        {
            $entidad = entidad::TraerIngresos();
        }

        if(!$entidad)
        {
            $objDelaRespuesta = new stdclass();
            $objDelaRespuesta->error = "No hay ingresos registrados.";
            $NuevaRespuesta = $response->withJson($objDelaRespuesta, 409);
        }
        else
        {
            $NuevaRespuesta = $response->withJson($entidad, 200);
        }

        return $NuevaRespuesta;
    }

    public function registrarIngreso($request, $response, $args)
    {
        $objDelaRespuesta = new stdclass();
        if(isset($args['id']))
        {
            $id = $args['id'];
            $count = entidad::GuardarIngreso($id);
            if($count == 1)
            {
                $objDelaRespuesta->respuesta = "Ingreso registrado exitosamente.";
                $NuevaRespuesta = $response->withJson($objDelaRespuesta, 200);
            }
            else
            {
                $objDelaRespuesta->error = "No se pudo registrar el ingreso.";
                $NuevaRespuesta = $response->withJson($objDelaRespuesta, 500);
            }
        }
        else
        {
            $objDelaRespuesta->error = "Consulta no valida: falta el ID de usuario.";
            $NuevaRespuesta = $response->withJson($objDelaRespuesta, 500);
        }
        
        return $NuevaRespuesta;
    }

/**
 * @api {post} /login Ingreso al sistema con email y clave
 * @apiName Login
 * @apiGroup login
 *
 * @apiParam {String} email Email of the user.
 * @apiParam {String} password Password of the user.
 *
 * @apiSuccess {String} token JWT with login data of the user.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MTIxNzc4MTEsImV4cCI6MTUxMjI2NDIxMSwiYXVkIjoiZjhiNjY2ZjNhYjhlZjQzMGJhNWFiYjhkNjBiMTU4ZDJkYzQwNjExOCIsImRhdGEiOnsiSUQiOjEsImVtYWlsIjoiYWRtaW4iLCJwZXJmaWwiOiJhZG1pbiIsIm5vbWJyZSI6IkFkbWluaXN0cmFkb3IiLCJhcGVsbGlkbyI6IkFETUlOIiwic2V4byI6MCwidHVybm8iOiJUIiwiZm90byI6bnVsbCwiaGFiaWxpdGFkbyI6MSwiZmVjaGFfY3JlYWRvIjoiMjAxNy0xMS0yNSAxNDoyOToyNCJ9LCJhcHAiOiJUUCBieSBIb25la2VyIn0.seE4LHGMrw2MroQo1OT8wD3BiFrNXN4wNbc2fr0IDIE"
 *     }
 *
 * @apiError UsuarioNoValido The email and password of the User was incorrect.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "error": "No es usuario valido."
 *     }
 * 
 * @apiError ParametrosIncorrectos The login params was not found.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "error": "Faltan los datos del usuario y su clave."
 *     }
 */
    public function Login($request, $response, $next) {
        sleep(1); // para evitar overflooding de peticiones...
        $token = "";
        $ArrayDeParametros = $request->getParsedBody();
    
        if(isset( $ArrayDeParametros['email']) && isset( $ArrayDeParametros['clave']) )
        {
            $email = $ArrayDeParametros['email'];
            $clave = $ArrayDeParametros['clave'];
            
            if(usuario::esValido($email, $clave))
            {
                
                $usuario = entidad::BuscarUsuario($email, $clave);
                /*if($usuario->habilitado == 0)
                {
                    $retorno = array('error'=> "Usuario deshabilitado." );
                    return $response->withJson( $retorno, 409 );
                }*/
                
                //entidad::GuardarIngreso($usuario->ID);

                $datos = array(
                    'id' => $usuario->id,
                    'email' => $usuario->email, 
                    'nombre' => $usuario->nombre,
                    'sexo' => $usuario->sexo,
                    'cuit' => $usuario->cuit,
                    'gano' => $usuario->gano
                );

                $token = AutentificadorJWT::CrearToken($datos);
                $retorno = array('token' => $token );
                //var_dump($retorno); die();
                $newResponse = $response->withJson( $retorno, 200 );
            }
            else
            {
                $retorno = array('error'=> "Email y/o clave incorrectos." );
                $newResponse = $response->withJson( $retorno, 409 ); 
            }
        }
        elseif ((isset($request->getHeader('token')[0]))) // para renovar el token timeout
        {
            $usr = AutentificadorJWT::ObtenerPayLoad($request->getHeader('token')[0]);
            //var_dump($usr); die ();
            
            $datos = (array) $usr->data;
            $token = AutentificadorJWT::CrearToken($datos);

            $retorno = array('token' => $token );
            //var_dump($retorno); die();
            $newResponse = $response->withJson( $retorno, 200 );
        }
        else
        {
            $retorno = array('error'=> "Faltan los datos del usuario y su clave." );
            $newResponse = $response->withJson( $retorno ,409); 
        }
    
        return $newResponse;
    }

    public function esAdmin($request, $response, $next) {
        $token = apache_request_headers()["token"];
        $usuario = json_decode(MWparaAutentificar::VerificarToken($token));
        
        if($usuario->perfil == 'admin')
        {
            $resp = $next($request, $response);
        }
        else
        {
            $arr = array('error' => 'No es administrador.');
            $resp = $response->withJson($arr, 409);
        }
        
        return $resp;
    }

/**
 * @api {get} /usuarios/ Petición de información de todos los usuarios
 * @apiName TraerTodos
 * @apiGroup usuarios
 *
 * @apiHeader {String} token User unique JWT token.
 *
 * @apiSuccess {Array} Array Array in JSON format with the list of users.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     [
 *       {
 *         "ID": 1,
 *         "nombre": "Administrador",
 *         "apellido": "ADMIN",
 *         "sexo": 0,
 *         "perfil": "admin",
 *         "turno": "T",
 *         "email": "admin",
 *         "password": "1234",
 *         "habilitado": 1,
 *         "fecha_creado": "2017-11-20 14:42:27",
 *         "foto": null
 *       },
 *       {
 *         "ID": 2,
 *         "nombre": "Juan",
 *         "apellido": "Miguel",
 *         "sexo": 1,
 *         "perfil": "usuario",
 *         "turno": "M",
 *         "email": "juanmiguel@gg.com",
 *         "password": "1234",
 *         "habilitado": 1,
 *         "fecha_creado": "2017-11-20 14:42:27",
 *         "foto": null
 *       }
 *     ]
 *
 * @apiError TokenExpired The token of the user expired.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "tipo": "acceso",
 *       "descripcion": "error Expired token"
 *     }
 */
    public function TraerTodos($request, $response, $args) {
        $todosLosUsuarios = entidad::TraerTodoLosUsuarios();

        /*foreach ($todosLosUsuarios as $key => $value) { // traer todos los que no sean administradores...
            if($value->perfil == 'admin')
                unset($todosLosUsuarios[$key]);
        }*/

        $newresponse = $response->withJson($todosLosUsuarios, 200);
        return $newresponse;
    }
    
/**
 * @api {post} /usuarios Alta de nuevos usuarios
 * @apiName altaUsuario
 * @apiGroup usuarios
 *
 * @apiHeader {String} token User access unique JWT token.
 * 
 * @apiHeaderExample {json} Header-Example:
 *     {
 *       "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MTIxNzc4MTEsImV4cCI6MTUxMjI2NDIxMSwiYXVkIjoiZjhiNjY2ZjNhYjhlZjQzMGJhNWFiYjhkNjBiMTU4ZDJkYzQwNjExOCIsImRhdGEiOnsiSUQiOjEsImVtYWlsIjoiYWRtaW4iLCJwZXJmaWwiOiJhZG1pbiIsIm5vbWJyZSI6IkFkbWluaXN0cmFkb3IiLCJhcGVsbGlkbyI6IkFETUlOIiwic2V4byI6MCwidHVybm8iOiJUIiwiZm90byI6bnVsbCwiaGFiaWxpdGFkbyI6MSwiZmVjaGFfY3JlYWRvIjoiMjAxNy0xMS0yNSAxNDoyOToyNCJ9LCJhcHAiOiJUUCBieSBIb25la2VyIn0.seE4LHGMrw2MroQo1OT8wD3BiFrNXN4wNbc2fr0IDIE"
 *     }
 * 
 * @apiParam {String} nombre Firstname of the new user.
 * @apiParam {String} apellido Lastname of the new user.
 * @apiParam {String} sexo Sexo del nuevo usuario, puede ser 0 (mujer) ó 1 (hombre).
 * @apiParam {String} email Email of the new user.
 * @apiParam {String} password Password of the new user.
 * @apiParam {String} turno Turno del nuevo usuario, puede ser 'M', 'T' o 'N'.
 * @apiParam {String} perfil Perfil del nuevo usuario, puede ser null, el perfil por defecto es 'usuario'.
 * @apiParam {String} habilitado Si el usuario va a crearse habilitado o no (con baja lógica), puede ser 0 ó 1.
 * @apiParam {String} foto Imagen del nuevo usuario, puede ser nula.
 *
 * @apiSuccess {String} respuesta El usuario se creo correctamente.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "respuesta": "Se guardo el usuario correctamente."
 *     }
 *
 * @apiError ParametrosInvalidos Faltan parámetros de entrada o son incorrectos.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "error": "Faltan parametros obligatorios del usuario."
 *     }
 * 
 * @apiError ErrorOther Errores de email unique constraint, de base de datos u otros.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "error": "SQL error: xxxxxx"
 *     }
 */
    public function altaUsuario($request, $response, $args) {
        sleep(3); // para evitar overflooding de peticiones...
        $objDelaRespuesta= new stdclass();
        $ArrayDeParametros = $request->getParsedBody();
        //var_dump($ArrayDeParametros);
        if(
            isset($ArrayDeParametros['nombre']) && $ArrayDeParametros['nombre'] != '' &&
            isset($ArrayDeParametros['email']) && $ArrayDeParametros['email'] != '' &&
            isset($ArrayDeParametros['clave']) && $ArrayDeParametros['clave'] != '' &&
            isset($ArrayDeParametros['cuit']) && $ArrayDeParametros['cuit'] != '' &&
            isset($ArrayDeParametros['sexo']) && $ArrayDeParametros['sexo'] != ''
        ) {
            $entidad = new entidad();
            
            //isset($ArrayDeParametros['gano']) && $ArrayDeParametros['gano'] != ''
            /*$archivos = $request->getUploadedFiles();
            $destino="./images/";
            $foto = null;
            //var_dump($archivos);
            //var_dump($archivos['foto']);
            if(isset($archivos['foto']))
            {
                $nombreAnterior = $archivos['foto']->getClientFilename();
                $extension = explode(".", $nombreAnterior);
                //var_dump($nombreAnterior);
                $extension = array_reverse($extension);
                $foto = $destino.$ArrayDeParametros['email'].".".$extension[0];
                $archivos['foto']->moveTo($foto);
            }*/

            $entidad->nombre = $ArrayDeParametros['nombre'];
            $entidad->email = $ArrayDeParametros['email'];
            $entidad->clave = $ArrayDeParametros['clave'];
            $entidad->cuit = $ArrayDeParametros['cuit'];
            $entidad->sexo = $ArrayDeParametros['sexo'];
            //$entidad->gano = $ArrayDeParametros['gano'];
            $entidad->gano = 'No';

            //$entidad->perfil = isset($ArrayDeParametros['perfil']) ? $ArrayDeParametros['perfil'] : "usuario"; // por defecto en db es "usuario"
            //$entidad-> = isset($ArrayDeParametros['discapacitados']) ? $ArrayDeParametros['discapacitados'] : 0; // por defecto en db es false o 0
            //$entidad->foto = isset($foto) ? $foto : null;      // por defecto en db es ""
            //$entidad->habilitado = isset($ArrayDeParametros['habilitado']) ? $ArrayDeParametros['habilitado'] : "0"; // por defecto en db es "1"
            //$entidad->fecha_creado = null; //isset($ArrayDeParametros['fecha_creado']) ? $ArrayDeParametros['fecha_creado'] : null; // por defecto en db es now()
        }
        else
        {
            return $response->withJson(array("error" => "Faltan parametros obligatorios del usuario."), 409);
        }
        
        $entidad->InsertarParametros();
        
        $objDelaRespuesta->respuesta = "Se guardo el usuario correctamente.";   
        return $response->withJson($objDelaRespuesta, 200);
    }
    
/**
 * @api {delete} /usuarios Eliminar un usuario
 * @apiName borrarUsuario
 * @apiGroup usuarios
 *
 * @apiHeader {String} token User access unique JWT token.
 * 
 * @apiHeaderExample {json} Header-Example:
 *     {
 *       "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MTIxNzc4MTEsImV4cCI6MTUxMjI2NDIxMSwiYXVkIjoiZjhiNjY2ZjNhYjhlZjQzMGJhNWFiYjhkNjBiMTU4ZDJkYzQwNjExOCIsImRhdGEiOnsiSUQiOjEsImVtYWlsIjoiYWRtaW4iLCJwZXJmaWwiOiJhZG1pbiIsIm5vbWJyZSI6IkFkbWluaXN0cmFkb3IiLCJhcGVsbGlkbyI6IkFETUlOIiwic2V4byI6MCwidHVybm8iOiJUIiwiZm90byI6bnVsbCwiaGFiaWxpdGFkbyI6MSwiZmVjaGFfY3JlYWRvIjoiMjAxNy0xMS0yNSAxNDoyOToyNCJ9LCJhcHAiOiJUUCBieSBIb25la2VyIn0.seE4LHGMrw2MroQo1OT8wD3BiFrNXN4wNbc2fr0IDIE"
 *     }
 * 
 * @apiParam {Int} id Identificador del usuario a eliminar.
 *
 * @apiSuccess {String} respuesta JSON con un mensaje de operación exitosa.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "resultado": "Se ha eliminado el usuario exitosamente."
 *     }
 *
 * @apiError ErrorGenerico Error genérico al intentar eliminar el usuario dado.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "resultado": "Error: no se pudo eliminar el usuario."
 *     }
 * 
 * @apiError ParametrosIncorrectos El id es incorrecto o no existe.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Conflict
 *     {
 *       "resultado": "No se pasó el ID del usuario a eliminar."
 *     }
 */
    public function borrarUsuario($request, $response, $args) { 
        $ArrayDeParametros = $request->getParsedBody();      // pasar por 'x-www-form-urlencoded'
        $objDelaRespuesta = new stdclass();
        
        if(isset($ArrayDeParametros['id']))
        {
            $id = $ArrayDeParametros['id'];

            $entidad = new entidad();
            $entidad->id = $id;
            $cantidadDeBorrados = $entidad->borrarEntidad();
            
            $objDelaRespuesta->cantidad = $cantidadDeBorrados;
            if($cantidadDeBorrados > 0)
            {
                $objDelaRespuesta->resultado = "Se ha eliminado el usuario exitosamente.";
                $newResponse = $response->withJson($objDelaRespuesta, 200);
                return $newResponse;
            }
            else
            {
                $objDelaRespuesta->resultado = "Error: no se pudo eliminar el usuario.";
                $newResponse = $response->withJson($objDelaRespuesta, 409);
                return $newResponse;
            }
        }
        else
        {
            $objDelaRespuesta->resultado = "No se pasó el ID del usuario a eliminar.";
            $newResponse = $response->withJson($objDelaRespuesta, 404);
            return $newResponse;
        }
    }
    
/**
 * @api {put} /usuarios Habilitar o deshabilitar un usuario
 * @apiName suspenderUsuario
 * @apiGroup usuarios
 *
 * @apiHeader {String} token User access unique JWT token.
 * 
 * @apiHeaderExample {json} Header-Example:
 *     {
 *       "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MTIxNzc4MTEsImV4cCI6MTUxMjI2NDIxMSwiYXVkIjoiZjhiNjY2ZjNhYjhlZjQzMGJhNWFiYjhkNjBiMTU4ZDJkYzQwNjExOCIsImRhdGEiOnsiSUQiOjEsImVtYWlsIjoiYWRtaW4iLCJwZXJmaWwiOiJhZG1pbiIsIm5vbWJyZSI6IkFkbWluaXN0cmFkb3IiLCJhcGVsbGlkbyI6IkFETUlOIiwic2V4byI6MCwidHVybm8iOiJUIiwiZm90byI6bnVsbCwiaGFiaWxpdGFkbyI6MSwiZmVjaGFfY3JlYWRvIjoiMjAxNy0xMS0yNSAxNDoyOToyNCJ9LCJhcHAiOiJUUCBieSBIb25la2VyIn0.seE4LHGMrw2MroQo1OT8wD3BiFrNXN4wNbc2fr0IDIE"
 *     }
 * 
 * @apiParam {Int} id Identificador del usuario a habilitar o deshabilitar.
 * @apiParam {Int} habilitado Valor de habilitación del usuario, 0 para deshabilitar y 1 para habilitar, debe alternarse.
 *
 * @apiSuccess {String} respuesta JSON con un mensaje de operación exitosa.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "respuesta": "Se ha cambiado el estado del usuario exitosamente.",
 *       "tarea": "Habilitar"
 *     }
 *
 * @apiError ErrorGenerico Error genérico al intentar eliminar el usuario dado.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 200 Conflict
 *     {
 *       "error": "No se pudo cambiar el estado del usuario o ya estaba en el mismo estado.",
 *       "tarea": "Habilitar"
 *     }
 * 
 * @apiError ParametrosIncorrectos El id es incorrecto o no existe.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "error": "Parametros o valores de usuario no validos.",
 *       "tarea": "Habilitar"
 *     }
 */
    public function suspenderUsuario($request, $response, $args) {
        $params = $request->getParsedBody();
        $objDelaRespuesta = new stdclass();
        //var_dump($params); die();
        
        if(isset($params['id']) && isset($params['habilitado']) && ($params['habilitado'] == 0 || $params['habilitado'] == 1))
        {
            $entidad = new entidad();
            $entidad->ID = $params['id'];
            $entidad->habilitado = $params['habilitado'];
            //var_dump($entidad); die();

            $resultado = $entidad->HabilitarUsuario();

            if(!$resultado)
            {
                $objDelaRespuesta->error = "No se pudo cambiar el estado del usuario o ya estaba en el mismo estado.";
                $objDelaRespuesta->tarea = "Habilitar";
                return $response->withJson($objDelaRespuesta, 200);
            }
            else
            {
                $objDelaRespuesta->respuesta = "Se ha cambiado el estado del usuario exitosamente.";
                $objDelaRespuesta->tarea = "Habilitar";
                return $response->withJson($objDelaRespuesta, 200);
            }
        }
        else
        {
            $objDelaRespuesta->error = "Parametros o valores de usuario no validos.";
            $objDelaRespuesta->tarea = "Habilitar";
            return $response->withJson($objDelaRespuesta, 409);
        }
    }

    
/**
 * @api {post} /usuarios/ingresos/getexcel Devuelve informe de todos los ingresos en CSV, ya sea histórico o por rango entre fechas
 * @apiName generarExcel
 * @apiGroup usuarios/ingresos
 *
 * @apiHeader {String} token User access unique JWT token.
 * 
 * @apiHeaderExample {json} Header-Example:
 *     {
 *       "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MTIxNzc4MTEsImV4cCI6MTUxMjI2NDIxMSwiYXVkIjoiZjhiNjY2ZjNhYjhlZjQzMGJhNWFiYjhkNjBiMTU4ZDJkYzQwNjExOCIsImRhdGEiOnsiSUQiOjEsImVtYWlsIjoiYWRtaW4iLCJwZXJmaWwiOiJhZG1pbiIsIm5vbWJyZSI6IkFkbWluaXN0cmFkb3IiLCJhcGVsbGlkbyI6IkFETUlOIiwic2V4byI6MCwidHVybm8iOiJUIiwiZm90byI6bnVsbCwiaGFiaWxpdGFkbyI6MSwiZmVjaGFfY3JlYWRvIjoiMjAxNy0xMS0yNSAxNDoyOToyNCJ9LCJhcHAiOiJUUCBieSBIb25la2VyIn0.seE4LHGMrw2MroQo1OT8wD3BiFrNXN4wNbc2fr0IDIE"
 *     }
 * 
 * @apiParam {Int} id Identificador del usuario del cual mostrar la información.
 * @apiParam {String} fecha_desde Fecha desde la cual filtrar los resultados, el formato es 'YYYY-mm-dd hh:ii:ss', se puede excluir la hora.
 * @apiParam {String} fecha_hasta Fecha hasta la cual filtrar los resultados, no la incluye, el formato es 'YYYY-mm-dd hh:ii:ss', se puede excluir la hora.
 *
 * @apiSuccess {File} file Archivo CSV generado con la consulta.
 *
 * @apiError ErrorGenerico Error genérico al generar el excel (csv).
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "error": "Error genérico al generar el csv."
 *     }
 * 
 */
    public function generarExcel($request, $response, $args)
    {
        $entidad;
        $buff = " -  historico";
        $Arr = $request->getParsedBody();
        
        if(isset($args['id']) && $args['id'] != '')
        {
            $id = $args['id'];
            $entidad = entidad::TraerIngresos($id);
            $buff = " -  ingresos del usuario (ID): ".$id;
        }
        elseif(isset($Arr['fecha_desde']) && isset($Arr['fecha_hasta']) && $Arr['fecha_desde'] != '' && $Arr['fecha_hasta'] != '')
        {
            $desde = $Arr['fecha_desde'];
            $hasta = $Arr['fecha_hasta'];
            $entidad = entidad::TraerIngresos(null, $desde, $hasta);
            $buff = " -  entre fechas ".$desde." hasta ".$hasta;
        }
        else
        {
            $entidad = entidad::TraerIngresos();
        }

        if(!$entidad)
        {
            $entidad = array(array('Sin datos', 'Sin datos', 'Sin datos', 'Sin datos', 'Sin datos'));
        }

        $header = array('ID', 'ID ingreso Usuario', 'Fecha', 'Nombre','Apellido');
        $datos = "Ingresos;\n\n";
        
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $datos .= "Fecha:  ;".date('Y-m-d H:i:s').$buff.";\n\n\n";

        //echo $datos; die();

        foreach ($header as $key => $value) {
            $datos .= $value.";";
        }
        $datos .= "\n";

        foreach ($entidad as $keya => $elem) {
            foreach ($elem as $key => $value) {
                $datos .= $value.";";
            }
            $datos .= "\n";
        }
        $datos .= "\n;";
        
        //echo $datos; die();

        $file = "tmp/Ingresos.csv";
        file_put_contents($file, $datos);

        $res = $response->withHeader('Content-Description', 'File Transfer')
        ->withHeader('Content-Type', 'application/pdf')
        ->withHeader('Content-Disposition', 'attachment;filename="'.basename($file).'"')
        ->withHeader('Expires', '0')
        ->withHeader('Cache-Control', 'must-revalidate')
        ->withHeader('Pragma', 'public')
        ->withHeader('Content-Length', filesize($file));
    
        readfile($file);
        return $res;
    }

/**
 * @api {post} /usuarios/ingresos/getpdf Devuelve informe de todos los ingresos en PDF, ya sea histórico o por rango entre fechas
 * @apiName generarPDF
 * @apiGroup usuarios/ingresos
 *
 * @apiHeader {String} token User access unique JWT token.
 * 
 * @apiHeaderExample {json} Header-Example:
 *     {
 *       "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MTIxNzc4MTEsImV4cCI6MTUxMjI2NDIxMSwiYXVkIjoiZjhiNjY2ZjNhYjhlZjQzMGJhNWFiYjhkNjBiMTU4ZDJkYzQwNjExOCIsImRhdGEiOnsiSUQiOjEsImVtYWlsIjoiYWRtaW4iLCJwZXJmaWwiOiJhZG1pbiIsIm5vbWJyZSI6IkFkbWluaXN0cmFkb3IiLCJhcGVsbGlkbyI6IkFETUlOIiwic2V4byI6MCwidHVybm8iOiJUIiwiZm90byI6bnVsbCwiaGFiaWxpdGFkbyI6MSwiZmVjaGFfY3JlYWRvIjoiMjAxNy0xMS0yNSAxNDoyOToyNCJ9LCJhcHAiOiJUUCBieSBIb25la2VyIn0.seE4LHGMrw2MroQo1OT8wD3BiFrNXN4wNbc2fr0IDIE"
 *     }
 * 
 * @apiParam {Int} id Identificador del usuario del cual mostrar la información.
 * @apiParam {String} fecha_desde Fecha desde la cual filtrar los resultados, el formato es 'YYYY-mm-dd hh:ii:ss', se puede excluir la hora.
 * @apiParam {String} fecha_hasta Fecha hasta la cual filtrar los resultados, no la incluye, el formato es 'YYYY-mm-dd hh:ii:ss', se puede excluir la hora.
 *
 * @apiSuccess {File} file Archivo pdf generado con la consulta.
 *
 * @apiError ErrorGenerico Error genérico al generar el pdf.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "error": "Error genérico al generar el pdf."
 *     }
 * 
 */
    public function generarPDF($request, $response, $args)
    {
        $entidad;
        $buff = " -  histórico";
        $Arr = $request->getParsedBody();
        
        if(isset($args['id']) && $args['id'] != '')
        {
            $id = $args['id'];
            $entidad = entidad::TraerIngresos($id);
            $buff = " -  ingresos del usuario (ID): ".$id;
        }
        elseif(isset($Arr['fecha_desde']) && isset($Arr['fecha_hasta']) && $Arr['fecha_desde'] != '' && $Arr['fecha_hasta'] != '')
        {
            $desde = $Arr['fecha_desde'];
            $hasta = $Arr['fecha_hasta'];
            $entidad = entidad::TraerIngresos(null, $desde, $hasta);
            $buff = " -  entre fechas ".$desde." hasta ".$hasta;
        }
        else
        {
            $entidad = entidad::TraerIngresos();
        }

        if(!$entidad)
        {
            $entidad = array(array('Sin datos', 'Sin datos', 'Sin datos', 'Sin datos', 'Sin datos'));
        }

        $pdf = new PDF('P','mm','A3');
        $header = array('ID', 'ID ingreso Usuario', 'Fecha', 'Nombre','Apellido');
        
        $pdf->AddPage();
        $pdf->Image('utn.png',10,8,33);
        $pdf->SetY(25);
        $pdf->SetFont('Arial','B',20);
        
        $pdf->Cell(0, 0, utf8_decode('Ingresos'), 0, 0, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial','',8);
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $pdf->Cell(0, 0, utf8_decode('Fecha:  '.date('Y-m-d H:i:s').$buff ), 0, 0, 'C');
        $pdf->Ln(4);
        
        $pdf->SetFont('Arial','',10);
        $pdf->Ln(10);
        //$pdf->Cell(0, 80);
        $pdf->BasicTable($header, $entidad);

        $pdf->SetY(10);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(0, 0, utf8_decode('Página '.$pdf->PageNo()), 0, 0);

        $file = "tmp/Ingresos.pdf";
        $pdf->Output("F", $file);

        $res = $response->withHeader('Content-Description', 'File Transfer')
        ->withHeader('Content-Type', 'application/pdf')
        ->withHeader('Content-Disposition', 'attachment;filename="'.basename($file).'"')
        ->withHeader('Expires', '0')
        ->withHeader('Cache-Control', 'must-revalidate')
        ->withHeader('Pragma', 'public')
        ->withHeader('Content-Length', filesize($file));
    
        readfile($file);
        return $res;
    }
}
