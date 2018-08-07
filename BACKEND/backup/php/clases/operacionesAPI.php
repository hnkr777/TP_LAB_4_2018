<?php
require_once 'operacion.php';
require_once 'IApiOperaciones.php';
//require_once 'fpdf/pdf.php';


class operacionesAPI extends operacion implements IApiOperaciones
{

        
/**
 * @api {get} /usuarios/ingresos Devuelve la cantidad de operaciones por usuario
 * @apiName cantidadOperaciones
 * @apiGroup usuarios/ingresos
 *
 * @apiHeader {String} token User access unique JWT token.
 * 
 * @apiHeaderExample {json} Header-Example:
 *     {
 *       "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MTIxNzc4MTEsImV4cCI6MTUxMjI2NDIxMSwiYXVkIjoiZjhiNjY2ZjNhYjhlZjQzMGJhNWFiYjhkNjBiMTU4ZDJkYzQwNjExOCIsImRhdGEiOnsiSUQiOjEsImVtYWlsIjoiYWRtaW4iLCJwZXJmaWwiOiJhZG1pbiIsIm5vbWJyZSI6IkFkbWluaXN0cmFkb3IiLCJhcGVsbGlkbyI6IkFETUlOIiwic2V4byI6MCwidHVybm8iOiJUIiwiZm90byI6bnVsbCwiaGFiaWxpdGFkbyI6MSwiZmVjaGFfY3JlYWRvIjoiMjAxNy0xMS0yNSAxNDoyOToyNCJ9LCJhcHAiOiJUUCBieSBIb25la2VyIn0.seE4LHGMrw2MroQo1OT8wD3BiFrNXN4wNbc2fr0IDIE"
 *     }
 * 
 * @apiSuccess {String} respuesta JSON con un mensaje de operación exitosa.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     [
 *       {
 *         "ID": 1,
 *         "nombre": "Administrador",
 *         "apellido": "ADMIN",
 *         "sexo": 0,
 *         "turno": "T",
 *         "perfil": "admin",
 *         "email": "admin",
 *         "foto": "./images/admin.gif",
 *         "fecha_creado": "2017-11-20 14:42:27",
 *         "habilitado": 1,
 *         "cantidad": 10
 *       },
 *       {
 *         "ID": 9,
 *         "nombre": "Usuario comun",
 *         "apellido": "comun",
 *         "sexo": 0,
 *         "turno": "T",
 *         "perfil": "usuario",
 *         "email": "usuario",
 *         "foto": null,
 *         "fecha_creado": "2017-11-20 14:42:27",
 *         "habilitado": 1,
 *         "cantidad": 2
 *       }
 *     ]
 *
 * @apiError ErrorGenerico Error genérico al intentar realizar la consulta.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 500 Internal server error
 *     {
 *       "error": "consulta fallida."
 *     }
 * 
 */
    public function cantidadOperaciones($request, $response, $args)
    {
        $operaciones = operacion::TraerOperacionesPorUsuario();
        if(!$operaciones)
            return $response->withJson(array("error", "consulta fallida."), 500);

        $newresponse = $response->withJson($operaciones, 200);
        return $newresponse;
    }

/**
 * @api {post} /operaciones/getpdf Devuelve informe de todas las operaciones en PDF, ya sea histórico o por rango entre fechas
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
    public static function generarPDF($request, $response, $args)
    {
        $entidad;
        $buff = " -  histórico";
        $Arr = $request->getParsedBody();
        
        if(isset($Arr['fecha_desde']) && isset($Arr['fecha_hasta']) && $Arr['fecha_desde'] != '' && $Arr['fecha_hasta'] != '')
        {
            $desde = $Arr['fecha_desde'];
            $hasta = $Arr['fecha_hasta'];
            $entidad = entidad::TraerIngresos(null, $desde, $hasta);
            $buff = " -  entre fechas ".$desde." hasta ".$hasta;
        }
        else
        {
            $entidad = operacion::TraerTodasLasOperaciones();
        }

        if(!$entidad)
        {
            $entidad = array(array('Sin datos', 'Sin datos', 'Sin datos', 'Sin datos', 'Sin datos'));
        }

        $pdf = new PDF('L','mm','A3');
        $header = array('ID', 'ID ingreso Usuario', 'ID salida Usuario', 'Fecha ingreso', 'Fecha salida', 'Importe', 'Color', 'Patente', 'Marca', 'Cochera', 'discapacitados');
        
        $pdf->AddPage();
        $pdf->Image('utn.png',10,8,33);
        $pdf->SetY(25);
        $pdf->SetFont('Arial','B',20);
        
        $pdf->Cell(0, 0, utf8_decode('Operaciones'), 0, 0, 'C');
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

        $file = "tmp/Operaciones.pdf";
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
        
        if(isset($Arr['fecha_desde']) && isset($Arr['fecha_hasta']) && $Arr['fecha_desde'] != '' && $Arr['fecha_hasta'] != '')
        {
            $desde = $Arr['fecha_desde'];
            $hasta = $Arr['fecha_hasta'];
            $entidad = entidad::TraerIngresos(null, $desde, $hasta);
            $buff = " -  entre fechas ".$desde." hasta ".$hasta;
        }
        else
        {
            $entidad = operacion::TraerTodasLasOperaciones();
        }

        if(!$entidad)
        {
            $entidad = array(array('Sin datos', 'Sin datos', 'Sin datos', 'Sin datos', 'Sin datos'));
        }

        $header = array('ID', 'ID ingreso Usuario', 'ID salida Usuario', 'Fecha ingreso', 'Fecha salida', 'Importe', 'Color', 'Patente', 'Marca', 'Cochera', 'discapacitados');
        $datos = "Operaciones;\n\n";
        
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

        $file = "tmp/operaciones.csv";
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

    public static function ImportePorFecha($ingreso, $salida)
    {
        $fecha_ingreso = strtotime($ingreso);
        $fecha_salida = strtotime($salida);
        
        $tarifa24h = 170;
        $tarifa12h = 90;
        $tarifa1h = 10;
        $interval = $fecha_salida/1000 - $fecha_ingreso/1000;
    
        $horas = $interval > 3599 ? intval($interval/3600) : 0; // en horas
        $minutos = intval(($interval % 3600) / 60); // en minutos
        $segundos = $interval - ($horas*3600) - ($minutos*60); // en segundos
        
        $total = intval($horas / 24) * $tarifa24h;
        $total += intval(($horas % 24) / 12) * $tarifa12h;
        $total += (($horas % 24) % 12) * $tarifa1h;
        $total += $segundos > 0 ? $tarifa1h : 0;

        return $total;
        //return (intval($interval / 86400) * $tarifa24h) + (intval(($interval % 86400) / 43200) * $tarifa12h) + (intval((($interval % 86400) % 43200) / 3600) * $tarifa1h) + (intval(((($interval % 86400) % 43200) % 3600) / 60) > 0 ? $tarifa1h : 0);
    }

/**
 * @api {get} /operaciones Trae información de todas las operaciones, actuales e históricas
 * @apiName TraerTodas
 * @apiGroup operaciones
 *
 * @apiHeader {String} token User access unique JWT token.
 * 
 * @apiHeaderExample {json} Header-Example:
 *     {
 *       "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MTIxNzc4MTEsImV4cCI6MTUxMjI2NDIxMSwiYXVkIjoiZjhiNjY2ZjNhYjhlZjQzMGJhNWFiYjhkNjBiMTU4ZDJkYzQwNjExOCIsImRhdGEiOnsiSUQiOjEsImVtYWlsIjoiYWRtaW4iLCJwZXJmaWwiOiJhZG1pbiIsIm5vbWJyZSI6IkFkbWluaXN0cmFkb3IiLCJhcGVsbGlkbyI6IkFETUlOIiwic2V4byI6MCwidHVybm8iOiJUIiwiZm90byI6bnVsbCwiaGFiaWxpdGFkbyI6MSwiZmVjaGFfY3JlYWRvIjoiMjAxNy0xMS0yNSAxNDoyOToyNCJ9LCJhcHAiOiJUUCBieSBIb25la2VyIn0.seE4LHGMrw2MroQo1OT8wD3BiFrNXN4wNbc2fr0IDIE"
 *     }
 * 
 * @apiSuccess {String} respuesta JSON con información sobre las operaciones.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     [
 *       {
 *         "ID": 6,
 *         "ingreso_usuarioID": 1,
 *         "salida_usuarioID": 1,
 *         "fecha_ingreso": "2017-11-20 15:54:42",
 *         "fecha_salida": "2017-11-20 16:01:20",
 *         "importe": "10.00",
 *         "color": "00ff99",
 *         "patente": "qwe123",
 *         "marca": "Mercedes-Benz",
 *         "cochera": 5,
 *         "discapacitados": 0
 *       },
 *       {
 *         "ID": 13,
 *         "ingreso_usuarioID": 1,
 *         "salida_usuarioID": null,
 *         "fecha_ingreso": "2017-12-03 20:46:42",
 *         "fecha_salida": null,
 *         "importe": "0.00",
 *         "color": "#573fcd",
 *         "patente": "AQV390",
 *         "marca": "Volkswagen",
 *         "cochera": 15,
 *         "discapacitados": 1
 *       },
 *       {
 *         "ID": 14,
 *         "ingreso_usuarioID": 1,
 *         "salida_usuarioID": null,
 *         "fecha_ingreso": "2017-12-03 20:54:07",
 *         "fecha_salida": null,
 *         "importe": "0.00",
 *         "color": "#808040",
 *         "patente": "dfg456",
 *         "marca": "Volvo",
 *         "cochera": 5,
 *         "discapacitados": 0
 *       }
 *     ]
 *
 * @apiError ErrorGenerico Error genérico al intentar realizar la consulta.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 500 Internal server error
 *     {
 *       "error": "consulta fallida."
 *     }
 * 
 */
    public function TraerTodas($request, $response, $args)
    {
        $operaciones = operacion::TraerTodasLasOperaciones();
        $newresponse = $response->withJson($operaciones, 200);
        return $newresponse;
    }

/**
 * @api {get} /operaciones/cocheras/{id} Devuelve si la cochera esta ocupada o no
 * @apiName estaOcupada
 * @apiGroup operaciones/cocheras
 *
 * @apiHeader {String} token User access unique JWT token.
 * 
 * @apiHeaderExample {json} Header-Example:
 *     {
 *       "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MTIxNzc4MTEsImV4cCI6MTUxMjI2NDIxMSwiYXVkIjoiZjhiNjY2ZjNhYjhlZjQzMGJhNWFiYjhkNjBiMTU4ZDJkYzQwNjExOCIsImRhdGEiOnsiSUQiOjEsImVtYWlsIjoiYWRtaW4iLCJwZXJmaWwiOiJhZG1pbiIsIm5vbWJyZSI6IkFkbWluaXN0cmFkb3IiLCJhcGVsbGlkbyI6IkFETUlOIiwic2V4byI6MCwidHVybm8iOiJUIiwiZm90byI6bnVsbCwiaGFiaWxpdGFkbyI6MSwiZmVjaGFfY3JlYWRvIjoiMjAxNy0xMS0yNSAxNDoyOToyNCJ9LCJhcHAiOiJUUCBieSBIb25la2VyIn0.seE4LHGMrw2MroQo1OT8wD3BiFrNXN4wNbc2fr0IDIE"
 *     }
 * 
 * @apiParam {Int} id Identificador de la cochera a consultar.
 *
 * @apiSuccess {String} consulta La cochera {id} está ocupada.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "La cochera {id} está ocupada."
 *     }
 * 
 * @apiSuccess {String} consulta La cochera {id} está libre.
 * 
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "La cochera {id} está libre."
 *     }
 *
 * @apiError ParametrosInvalidos Faltan parámetros de entrada o son incorrectos.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "error": "Datos de cochera invalidos."
 *     }
 * 
 */
    public function estaOcupada($request, $response, $args) // si la cochera esta ocupada, true or false
    {
        if(isset($args['id']) && self::validarCochera($args['id']))
        {
            $ocupada = operacion::EstaLaCocheraOcupada($args['id']);
            if($ocupada)
                $newresponse = $response->withJson("La cochera ".$args['id']." está ocupada", 200);
            else
                $newresponse = $response->withJson("La cochera ".$args['id']." está libre", 200);
        }
        else
        {
            $newresponse = $response->withJson(array("error" => "Datos de cochera invalidos."), 409);
        }
        return $newresponse;
    }

/**
 * @api {get} /operaciones/cocheras Devuelve todas las cocheras ocupadas
 * @apiName TraerEstacionados
 * @apiGroup operaciones/cocheras
 *
 * @apiHeader {String} token User access unique JWT token.
 * 
 * @apiHeaderExample {json} Header-Example:
 *     {
 *       "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MTIxNzc4MTEsImV4cCI6MTUxMjI2NDIxMSwiYXVkIjoiZjhiNjY2ZjNhYjhlZjQzMGJhNWFiYjhkNjBiMTU4ZDJkYzQwNjExOCIsImRhdGEiOnsiSUQiOjEsImVtYWlsIjoiYWRtaW4iLCJwZXJmaWwiOiJhZG1pbiIsIm5vbWJyZSI6IkFkbWluaXN0cmFkb3IiLCJhcGVsbGlkbyI6IkFETUlOIiwic2V4byI6MCwidHVybm8iOiJUIiwiZm90byI6bnVsbCwiaGFiaWxpdGFkbyI6MSwiZmVjaGFfY3JlYWRvIjoiMjAxNy0xMS0yNSAxNDoyOToyNCJ9LCJhcHAiOiJUUCBieSBIb25la2VyIn0.seE4LHGMrw2MroQo1OT8wD3BiFrNXN4wNbc2fr0IDIE"
 *     }
 * 
 * @apiSuccess {JSON} array Cocheras.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     [
 *       {
 *         "ID": 13,
 *         "ingreso_usuarioID": 1,
 *         "salida_usuarioID": null,
 *         "fecha_ingreso": "2017-12-03 20:46:42",
 *         "fecha_salida": null,
 *         "importe": "0.00",
 *         "color": "#573fcd",
 *         "patente": "AQV390",
 *         "marca": "Volkswagen",
 *         "cochera": 15,
 *         "discapacitados": 0
 *       },
 *       {
 *         "ID": 14,
 *         "ingreso_usuarioID": 1,
 *         "salida_usuarioID": null,
 *         "fecha_ingreso": "2017-12-03 20:54:07",
 *         "fecha_salida": null,
 *         "importe": "0.00",
 *         "color": "#808040",
 *         "patente": "dfg456",
 *         "marca": "Volvo",
 *         "cochera": 5,
 *         "discapacitados": 0
 *       },
 *       {
 *         "ID": 17,
 *         "ingreso_usuarioID": 1,
 *         "salida_usuarioID": null,
 *         "fecha_ingreso": "2017-12-04 09:13:12",
 *         "fecha_salida": null,
 *         "importe": "0.00",
 *         "color": "#008000",
 *         "patente": "PVP492",
 *         "marca": "Chevrolet",
 *         "cochera": 8,
 *         "discapacitados": 0
 *       }
 *     ]
 * 
 */
    public function TraerEstacionados($request, $response, $args)
    {
        $operaciones = operacion::TraerTodasLasCocherasOcupadas();
        $newresponse = $response->withJson($operaciones, 200);
        return $newresponse;
    }

/**
 * @api {post} /operaciones Dar de alta una nueva operacion
 * @apiName altaOperacion
 * @apiGroup operaciones
 *
 * @apiHeader {String} token User access unique JWT token.
 * 
 * @apiHeaderExample {json} Header-Example:
 *     {
 *       "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MTIxNzc4MTEsImV4cCI6MTUxMjI2NDIxMSwiYXVkIjoiZjhiNjY2ZjNhYjhlZjQzMGJhNWFiYjhkNjBiMTU4ZDJkYzQwNjExOCIsImRhdGEiOnsiSUQiOjEsImVtYWlsIjoiYWRtaW4iLCJwZXJmaWwiOiJhZG1pbiIsIm5vbWJyZSI6IkFkbWluaXN0cmFkb3IiLCJhcGVsbGlkbyI6IkFETUlOIiwic2V4byI6MCwidHVybm8iOiJUIiwiZm90byI6bnVsbCwiaGFiaWxpdGFkbyI6MSwiZmVjaGFfY3JlYWRvIjoiMjAxNy0xMS0yNSAxNDoyOToyNCJ9LCJhcHAiOiJUUCBieSBIb25la2VyIn0.seE4LHGMrw2MroQo1OT8wD3BiFrNXN4wNbc2fr0IDIE"
 *     }
 * 
 * @apiParam {String} color Color del vehículo, debe estar en el formato '#a3f0ff' (hexadecimal 24 bits con numeral al inicio).
 * @apiParam {String} patente Patente del vehículo a ingresar.
 * @apiParam {String} marca Marca del vehículo.
 * @apiParam {Int} cochera Número de cochera, entre 1 y 24 inclusive.
 * @apiParam {int} discapacitados Si el cliente es discapacitado, puede ser 0 (falso) ó 1 (verdadero).
 *
 * @apiSuccess {JSON} respuesta La operación se guardó correctamente.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "respuesta": "Se guardo la operacion."
 *     }
 *
 * @apiError ParametrosInvalidos Faltan parámetros de entrada o son incorrectos.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "error": "Faltan parametros obligatorios de la operacion o son invalidos."
 *     }
 * 
 * @apiError VehiculoYaEstacionado El vehículo (patente) ya se encuentra estacionado.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "error": "El vehiculo ya se encuentra estacionado."
 *     }
 * 
 * @apiError CocheraOcupada La cochera ya está ocupada.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "error": "La cochera ya se encuentra ocupada."
 *     }
 */
    public function altaPartida($request, $response, $args)
    {
        $objDelaRespuesta= new stdclass();
        $ArrParam = $request->getParsedBody();

        if(
            isset($ArrParam['datos']) && $ArrParam['datos'] != "" &&
            isset($ArrParam['juego']) && $ArrParam['juego'] != ""
            //isset($ArrParam['cochera']) && $ArrParam['cochera'] != "" && self::validarCochera($ArrParam['cochera'])
        ) {
            //$oper = operacion::traerOperacionAlta($ArrParam['patente']);
            //$ocupada = operacion::EstaLaCocheraOcupada($ArrParam['cochera']);
            
            
            if(isset(apache_request_headers()["token"]))
            {
                $operacion = new operacion();
                $token = apache_request_headers()["token"];
                //date_default_timezone_set('America/Argentina/Buenos_Aires');
                //$date = date('Y-m-d_H-i-s', time());
                
                $operacion->juego = $ArrParam['juego'];
                $operacion->datos = $ArrParam['datos'];
                
                //$operacion->discapacitados = (isset($ArrParam['discapacitados']) && $ArrParam['discapacitados'] == 1 ) ? 1 : 0;
                
                $usuario = json_decode(MWparaAutentificar::VerificarToken($token));
                $operacion->id = $usuario->id;
            }
            else
            {
                return $response->withJson(array("error" => "Acceso denegado, token invalido."), 409);
            }
        }
        else
        {
            return $response->withJson(array("error" => "Faltan parametros obligatorios de la operacion o son invalidos."), 409);
        }
        
        $operacion->AgregarNueva();
        
        $objDelaRespuesta->respuesta = "Se guardo la operacion.";
        return $response->withJson($objDelaRespuesta, 200);
    }

/**
 * @api {delete} /operaciones Elimina una operacion por ID de operación
 * @apiName eliminarOperacion
 * @apiGroup operaciones
 *
 * @apiHeader {String} token User access unique JWT token.
 * 
 * @apiHeaderExample {json} Header-Example:
 *     {
 *       "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MTIxNzc4MTEsImV4cCI6MTUxMjI2NDIxMSwiYXVkIjoiZjhiNjY2ZjNhYjhlZjQzMGJhNWFiYjhkNjBiMTU4ZDJkYzQwNjExOCIsImRhdGEiOnsiSUQiOjEsImVtYWlsIjoiYWRtaW4iLCJwZXJmaWwiOiJhZG1pbiIsIm5vbWJyZSI6IkFkbWluaXN0cmFkb3IiLCJhcGVsbGlkbyI6IkFETUlOIiwic2V4byI6MCwidHVybm8iOiJUIiwiZm90byI6bnVsbCwiaGFiaWxpdGFkbyI6MSwiZmVjaGFfY3JlYWRvIjoiMjAxNy0xMS0yNSAxNDoyOToyNCJ9LCJhcHAiOiJUUCBieSBIb25la2VyIn0.seE4LHGMrw2MroQo1OT8wD3BiFrNXN4wNbc2fr0IDIE"
 *     }
 * 
 * @apiParam {Int} id Identificador de la operación a eliminar.
 *
 * @apiSuccess {JSON} resultado La operación se eliminó correctamente.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "resultado": "La operacion se ha eliminado exitosamente."
 *     }
 *
 * @apiError ParametrosInvalidos Faltan parámetros de entrada o son incorrectos.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "resultado": "No se pasó el ID de la operacion a eliminar."
 *     }
 * 
 * @apiError ErrorGenerico Error genérico al intentar eliminar la operacion..
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "resultado": "Error: no se pudo eliminar la operacion."
 *     }
 * 
 */
    public function eliminarOperacion($request, $response, $args)
    {
        $ArrayDeParametros = $request->getParsedBody();      // pasar por 'x-www-form-urlencoded'
        $objDelaRespuesta = new stdclass();
        
        if(isset($ArrayDeParametros['id']))
        {
            $id = $ArrayDeParametros['id'];

            $operacion = new operacion();
            $operacion->ID = $id;
            $cantidadDeBorrados = $operacion->borrarOperacion();
            
            $objDelaRespuesta->cantidad = $cantidadDeBorrados;
            if($cantidadDeBorrados > 0)
            {
                $objDelaRespuesta->resultado = "La operacion se ha eliminado exitosamente.";
                return $response->withJson($objDelaRespuesta, 200);
            }
            else
            {
                $objDelaRespuesta->resultado = "Error: no se pudo eliminar la operacion.";
                return $response->withJson($objDelaRespuesta, 409);
            }
        }
        else
        {
            $objDelaRespuesta->resultado = "No se pasó el ID de la operacion a eliminar.";
            return $response->withJson($objDelaRespuesta, 409);
        }
    }
    
    /**
 * @api {put} /operaciones Operacion finalizada, egreso de vehiculo
 * @apiName bajaOperacion
 * @apiGroup operaciones
 *
 * @apiHeader {String} token User access unique JWT token.
 * 
 * @apiHeaderExample {json} Header-Example:
 *     {
 *       "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MTIxNzc4MTEsImV4cCI6MTUxMjI2NDIxMSwiYXVkIjoiZjhiNjY2ZjNhYjhlZjQzMGJhNWFiYjhkNjBiMTU4ZDJkYzQwNjExOCIsImRhdGEiOnsiSUQiOjEsImVtYWlsIjoiYWRtaW4iLCJwZXJmaWwiOiJhZG1pbiIsIm5vbWJyZSI6IkFkbWluaXN0cmFkb3IiLCJhcGVsbGlkbyI6IkFETUlOIiwic2V4byI6MCwidHVybm8iOiJUIiwiZm90byI6bnVsbCwiaGFiaWxpdGFkbyI6MSwiZmVjaGFfY3JlYWRvIjoiMjAxNy0xMS0yNSAxNDoyOToyNCJ9LCJhcHAiOiJUUCBieSBIb25la2VyIn0.seE4LHGMrw2MroQo1OT8wD3BiFrNXN4wNbc2fr0IDIE"
 *     }
 * 
 * @apiParam {String} patente Identificador (patente) de la operación a dar de baja (egreso).
 *
 * @apiSuccess {JSON} resultado La operación se eliminó correctamente.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "respuesta": "Operacion de salida exitosa."
 *     }
 *
 * @apiError ParametrosInvalidos Faltan parámetros de entrada o son incorrectos.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "error": "Parametros o valores de operacion no validos."
 *     }
 * 
 * @apiError VehiculoNoEstacionado El vehículo no se encuentra estacionado.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "error": "El vehiculo no se encuentra estacionado."
 *     }
 * 
 * @apiError ErrorGenerico Error genérico.
 * 
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "error": "No se pudo dar de baja la operacion."
 *     }
 */
    public function bajaOperacion($request, $response, $args)
    {
        $ArrayDeParametros = $request->getParsedBody();
        $objDelaRespuesta = new stdclass();
        //var_dump($ArrayDeParametros); die();
        
        if(isset($ArrayDeParametros['patente']))
        {
            $operacion = new operacion();
            $patente = $ArrayDeParametros['patente'];
            $operacion = operacion::traerOperacionAlta($patente);

            if($operacion)
            {
                $token = apache_request_headers()["token"];
                $usuario = json_decode(MWparaAutentificar::VerificarToken($token));
                $operacion->salida_usuarioID = $usuario->ID;
                date_default_timezone_set('America/Argentina/Buenos_Aires');
                $operacion->fecha_salida = date("Y-m-d H:i:s"); // revisar zona horaria, debe ser "America/Argentina/Buenos_Aires"
                $operacion->importe = operacionesAPI::ImportePorFecha($operacion->fecha_ingreso, $operacion->fecha_salida);

                file_put_contents('hora.txt', 'importe: '.$operacion->importe.'\r\n'.'Fecha ingreso: '.$operacion->fecha_ingreso.'Fecha salida: '.$operacion->fecha_salida);
                
                /*echo "Fecha ingreso: ". $operacion->fecha_ingreso ."\r\n";
                echo "Fecha salida: ". $operacion->fecha_salida ."\r\n";
                //var_dump($operacion); die();
                echo "Importe: $ " . $operacion->importe; die();*/

                $resultado = $operacion->terminarOperacion();
                
                if($resultado === false)
                {
                    $objDelaRespuesta->error = "No se pudo dar de baja la operacion.";
                    return $response->withJson($objDelaRespuesta, 409);
                }
                else
                {
                    $objDelaRespuesta->datos = $resultado;
                    $objDelaRespuesta->respuesta = "Operacion de salida exitosa.";
                }
            }
            else
            {
                $objDelaRespuesta->error = "El vehiculo no se encuentra estacionado.";
                return $response->withJson($objDelaRespuesta, 409);
            }
        }
        else
        {
            $objDelaRespuesta->error = "Parametros o valores de operacion no validos.";
            return $response->withJson($objDelaRespuesta, 409);
        }

        $objDelaRespuesta->tarea = "Baja operacion";
        return $response->withJson($objDelaRespuesta, 200);
    }

    public function getIdAlta($patente) // devuelve la operacion si la patente esta registrada y en alta, sino false
    {
        $objDelaRespuesta = new stdclass();
        
        $operacion = new operacion();
        $operacion->patente = $patente;
        
        //var_dump($operacion); die();

        $resultado = $operacion->traerOperacionAlta($patente);
        
        if($resultado == false)
            $objDelaRespuesta->error = "No se pudo dar de baja la operacion.";
        else
            $objDelaRespuesta->respuesta = "Se ha dado de baja la operacion exitosamente.";
        
        
        $objDelaRespuesta->tarea = "Baja operacion";
        return $response->withJson($objDelaRespuesta, 200);
    }

    public static function validarCochera($cochera) // si el numero de cochera es valido
    {
        $number = filter_var($cochera, FILTER_VALIDATE_INT);
        if(($number !== FALSE) && $number > 0 && $number < 25 )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
/**
 * @api {post} /operaciones/estadisticas Devuelve todas las estadisticas de uso de las cocheras, entre las fechas dadas (opcional).
 * @apiName estadisticas
 * @apiGroup operaciones/estadisticas
 *
 * @apiHeader {String} token User access unique JWT token.
 * 
 * @apiHeaderExample {json} Header-Example:
 *     {
 *       "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MTIxNzc4MTEsImV4cCI6MTUxMjI2NDIxMSwiYXVkIjoiZjhiNjY2ZjNhYjhlZjQzMGJhNWFiYjhkNjBiMTU4ZDJkYzQwNjExOCIsImRhdGEiOnsiSUQiOjEsImVtYWlsIjoiYWRtaW4iLCJwZXJmaWwiOiJhZG1pbiIsIm5vbWJyZSI6IkFkbWluaXN0cmFkb3IiLCJhcGVsbGlkbyI6IkFETUlOIiwic2V4byI6MCwidHVybm8iOiJUIiwiZm90byI6bnVsbCwiaGFiaWxpdGFkbyI6MSwiZmVjaGFfY3JlYWRvIjoiMjAxNy0xMS0yNSAxNDoyOToyNCJ9LCJhcHAiOiJUUCBieSBIb25la2VyIn0.seE4LHGMrw2MroQo1OT8wD3BiFrNXN4wNbc2fr0IDIE"
 *     }
 * 
 * @apiParam {String} desde Fecha desde la cual filtrar los resultados, el formato es 'YYYY-mm-dd hh:ii:ss', se puede excluir la hora.
 * @apiParam {String} hasta Fecha hasta la cual filtrar los resultados, no la incluye, el formato es 'YYYY-mm-dd hh:ii:ss', se puede excluir la hora.
 *
 * @apiSuccess {String} JSON generado con la consulta.
 *     [
 *       {
 *         "cantidad": 1,
 *         "cochera": 1,
 *         "discapacitados": false
 *       },
 *       {
 *         "cantidad": 0,
 *         "cochera": 2,
 *         "discapacitados": true
 *       },
 *       {
 *         "cantidad": 1,
 *         "cochera": 3,
 *         "discapacitados": false
 *       },
 *       {
 *         "cantidadDiscapacitados": 2
 *       }
 *     ]
 *
 * @apiError ErrorGenerico Error genérico.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 409 Conflict
 *     {
 *       "error": "Error genérico."
 *     }
 * 
 */
    public static function estadisticas($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $operaciones = operacion::getStatistics( (isset($params['desde']) ? $params['desde'] : null), (isset($params['hasta']) ? $params['hasta'] : null) );
        $newresponse = $response->withJson($operaciones, 200);
        return $newresponse;
    }

}


?>