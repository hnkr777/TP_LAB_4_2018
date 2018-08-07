<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

include_once $_SERVER['DOCUMENT_ROOT']."/composer/vendor/autoload.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/API/validationAPI.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/API/empleadoAPI.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/API/loginAPI.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/API/vehiculoAPI.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/API/MWparaCORS.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/API/viajeAPI.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/API/auxiliaresAPI.php";
include_once $_SERVER['DOCUMENT_ROOT']."/PHP/API/encuestaAPI.php";

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

/* ====== */

$app->get('[/]', function(){
    include('./index.html');
});

/* ====== */

$app->group('/login', function () {
    
	$this->post('[/]', \loginAPI::class . ':CargarUno'); //Nuevo login y trae un SessionToken
	//{email} y {password}

})->add(\MWparaCORS::class . ':HabilitarCORSTodos');

/* ====== */

$app->group('/empleados', function () {
 
	/**/ $this->get('/remiserosDisponibles[/]', \empleadoAPI::class . ':RemiserosDisponibles');

	/**/ $this->get('[/]', \empleadoAPI::class . ':TraerTodos');

	/**/ $this->get('/{id}', \empleadoAPI::class . ':traerUno');

	/**/ $this->post('[/]', \empleadoAPI::class . ':CargarUno');

	/**/ $this->post('/modificar[/]', \empleadoAPI::class . ':ModificarUno');


})->add(\MWparaCORS::class . ':HabilitarCORSTodos');

$app->group('/vehiculos', function () {
 
	/**/ $this->get('[/]', \vehiculoAPI::class . ':TraerTodos');

	/**/ $this->get('/{id}', \vehiculoAPI::class . ':traerUno');

	/**/ $this->post('[/]', \vehiculoAPI::class . ':CargarUno');

	/**/ $this->post('/modificar[/]', \vehiculoAPI::class . ':ModificarUno');

})->add(\MWparaCORS::class . ':HabilitarCORSTodos');

$app->group('/viajes', function () {
 
	/**/ $this->get('[/]', \viajeAPI::class . ':TraerTodos');

	/**/ $this->get('/{id}', \viajeAPI::class . ':traerUno');

	/**/ $this->post('[/]', \viajeAPI::class . ':CargarUno');

	/**/ $this->post('/modificar[/]', \viajeAPI::class . ':ModificarUno');

})->add(\MWparaCORS::class . ':HabilitarCORSTodos');

$app->group('/encuestas', function () {
 
	/**/ $this->get('[/]', \encuestaAPI::class . ':TraerTodos');

	/**/ $this->get('/{id}', \encuestaAPI::class . ':traerUno');

	/**/ $this->post('[/]', \encuestaAPI::class . ':CargarUno');

	/**/ $this->post('/archivos[/]', \encuestaAPI::class . ':SubirImagenes');

})->add(\MWparaCORS::class . ':HabilitarCORSTodos');

/* ====== */

$app->group('/aux/ascientos_posibles', function () {
 
	/**/ $this->get('[/]', \auxiliaresAPI::class . ':TraerAscientos');

})->add(\MWparaCORS::class . ':HabilitarCORSTodos')->add(\validationAPI::class . ':encargadoValidationMiddleware');

$app->group('/aux/calificaciones', function () {
 
	/**/ $this->get('[/]', \auxiliaresAPI::class . ':TraerCalificaciones');

})->add(\MWparaCORS::class . ':HabilitarCORSTodos')->add(\validationAPI::class . ':encargadoValidationMiddleware');

$app->group('/aux/estados_viaje', function () {
 
	/**/ $this->get('[/]', \auxiliaresAPI::class . ':TraerEstadosViaje');

})->add(\MWparaCORS::class . ':HabilitarCORSTodos')->add(\validationAPI::class . ':encargadoValidationMiddleware');

$app->group('/aux/medios_de_pago', function () {
 
	/**/ $this->get('[/]', \auxiliaresAPI::class . ':TraerMediosDePago');

})->add(\MWparaCORS::class . ':HabilitarCORSTodos')->add(\validationAPI::class . ':encargadoValidationMiddleware');

$app->group('/aux/niveles_comodidad', function () {
 
	/**/ $this->get('[/]', \auxiliaresAPI::class . ':TraerNivelesComodidad');

})->add(\MWparaCORS::class . ':HabilitarCORSTodos')->add(\validationAPI::class . ':encargadoValidationMiddleware');

$app->group('/aux/perfiles', function () {
 
	/**/ $this->get('[/]', \auxiliaresAPI::class . ':TraerPerfiles');

})->add(\MWparaCORS::class . ':HabilitarCORSTodos')->add(\validationAPI::class . ':encargadoValidationMiddleware');

/* ====== */

//USAR SIEMPRE $consulta->fetchAll(PDO::FETCH_ASSOC) EN VEZ DE CREAR ENTIDADES

$app->run();