<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;

//APACHE_REQUEST_HEADERS();

require './composer/vendor/autoload.php';
require_once './php/clases/AccesoDatos.php';
require_once './php/clases/usuariosApi.php';
require_once './php/clases/operacionesAPI.php';
require_once './php/clases/AutentificadorJWT.php';
require_once './php/clases/MWparaCORS.php';
require_once './php/clases/MWparaAutentificar.php';
require_once './php/clases/usuario.php';
require_once './php/clases/operacion.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

// ruta por defecto, no hay autenticacion
$app->get('[/]', function(){
    include('./index.html');
});

// ruta de login, en caso de existir el usuario, devuelve token (JSON) {'token': 'abcdef1234567890'}
$app->post('/login', \usuariosApi::class . ':Login')->add(\MWparaCORS::class . ':HabilitarCORSTodos');
$app->post('/apirestjugadores/altausuario[/]', \usuariosApi::class . ':altaUsuario'); // Dar de alta un nuevo usuario

//  -- /jugadoresarchivo/apirestjugadores/jugadores
$app->group('/jugadoresarchivo', function () {
    $this->get('/apirestjugadores/jugadores[/]', \usuariosApi::class . ':traerTodos');  // traer todos los usuarios
})->add(\MWparaAutentificar::class . ':VerificarUsuario')->add(\MWparaCORS::class . ':HabilitarCORSTodos');

$app->group('/partidas', function () {
    $this->get('/traertodas[/]', \operacionesAPI::class . ':traerTodas');  // Trae todas las partidas jugadas
    $this->post('/guardarjugada[/]', \operacionesAPI::class . ':altaPartida'); // Da de alta una nueva partida
})->add(\MWparaAutentificar::class . ':VerificarUsuario')->add(\MWparaCORS::class . ':HabilitarCORSTodos');


$app->run();

?>