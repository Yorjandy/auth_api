<?php
namespace Mlh\Mlh\demo;
//require('vendor/autoload.php');
use Exception;

class SessionManager{
    //iniciondo las sesiones
    public function __construct(){
        session_start();
    }
    //metodo para iniciar una nueva sesion
    //args: email=>string
    //return: token:string/session_pointer
    public function sessionInit($email){
        $sessiontoken = password_hash($email,PASSWORD_BCRYPT);
        $_SESSION[$sessiontoken] = $email;
        return $sessiontoken;
    }
    //metodo para recuperar la informacion de la sesion
    //args: token=>string/session_pointer
    //return: email=>string
    public function getSessionData($token){
        if(isset($_SESSION[$token])){
            return $_SESSION[$token];
        }
        else{
            throw new Exception();
        }
    }
    //metodo para eliminar una sesion de usuario
    //args: $token=>string/session_pointer
    //return: boolean
    public function removeSession($token){
        if(isset($_SESSION[$token])){
            unset($_SESSION[$token]);
            return true;
        }
        else{
            throw new Exception();
        }
    }
    //metodo para monitorear en arreglo de sesiones del sistema(testing)
    //args: void
    //return: void
    public function testSession(){
        if( session_status() === PHP_SESSION_ACTIVE){
            header('Content-Type:application/json');
            echo json_encode(['sesions'=> array($_SESSION)]);
        }
        else{
            header('Content-Type:application/json');
            echo json_encode(['error'=>'no se encontro ninguna sesion en el sistema']);
        }
    }
}