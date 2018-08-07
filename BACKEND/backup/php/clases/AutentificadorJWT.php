<?php
require_once './composer/vendor/autoload.php';
use Firebase\JWT\JWT;

class AutentificadorJWT
{
    private static $secretPwd = 'pqowieur1%'; //Unser¿Leute°
    private static $encryptType = ['HS256'];
    private static $aud = null;
    public static $timeout = (60 * 60 * 24); // 24 horas de timeout...
    
    public static function CrearToken($datos)
    {
        $ahora = time();
        
        $payload = array(
        	'iat'=>$ahora,
            'exp' => $ahora + self::$timeout,
            'aud' => self::Aud(),
            'data' => $datos,
            'app'=> "TP by Honeker"
        );

        return JWT::encode($payload, self::$secretPwd);
    }
    
    public static function VerificarToken($token, $passing = true) // passing reinicia el contador de timeout
    {
        if(empty($token) || $token == "")
        {
            throw new Exception("El token esta vacio.");
        } 
        // las siguientes lineas lanzan una excepcion, de no ser correcto o de haberse terminado el tiempo       
        try
        {
            $decodificado = JWT::decode(
            $token,
            self::$secretPwd,
            self::$encryptType
            );
        } 
        catch (ExpiredException $e) 
        {
            throw new Exception("Token expired.");
        }
        
        // si no da error,  verifico los datos de AUD que uso para saber de que lugar viene  
        if($decodificado->aud !== self::Aud())
        {
            throw new Exception("No es el usuario valido");
        }
    }
    
   
    public static function ObtenerPayLoad($token)
    {
        return JWT::decode(
            $token,
            self::$secretPwd,
            self::$encryptType
        );
    }

    public static function ObtenerData($token)
    {
        return JWT::decode(
            $token,
            self::$secretPwd,
            self::$encryptType
        )->data;
    }
    private static function Aud()
    {
        $aud = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }
        
        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();
        
        return sha1($aud);
    }
}


/*

$app->post('/token', function (){
    echo "token test<br>";
    
    
    $key = "pqowieur1%";
    $token = array(
        "iss" => "http://example.org",
        "aud" => "http://example.com",
        "iat" => 1356999524,
        "nbf" => 1357000000,
        "exp" => time() + 60,
        "email" => "asd@test.net"
    );
    
    
    $jwt = JWT::encode($token, $key);
    echo $jwt;

});

$app->post('/deco', function($request, $response, $args){
    $jwt = $request->getHeader('token');
    $decoded = null;
    
    try
    {
        $decoded = JWT::decode($jwt[0], "pqowieur1%", array('HS256'));
    } 
    catch(Exception $e)
    {
        
        echo "Token expirado";
    }
    
    //var_dump($decoded);
    //die();

    print_r($decoded);
    
    $decoded_array = (array) $decoded;
});

*/