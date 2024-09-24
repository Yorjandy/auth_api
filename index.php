<?php
require 'vendor/autoload.php';
use Mlh\Mlh\controller\AuthManager;

$json = file_get_contents('php://input');
$args = json_decode($json,true);

if(json_last_error() != JSON_ERROR_NONE){
    $args = $_REQUEST;
}

if(isset($args['method']) && method_exists('\Mlh\Mlh\controller\AuthManager',$args['method'])){
    $auth = new AuthManager();
    $auth->{$args['method']}($args);
}
else{
    header('Content-Type:application/json');
    echo json_encode(['error' => 'not method passed or not method exits']);
}