<?php
namespace Mlh\Mlh\demo;
//require('vendor/autoload.php');
use Mlh\Mlh\demo\SessionManager;
use Mlh\Mlh\model\AuthConnect;
use Exception;
use PDOException;

class AuthManager{
    private $sessions;
    private $connect;
    //iniciando el manejador de sessionnes y la conexion con los datos
    public function __construct(){
        $this->sessions = new SessionManager();
        $this->connect = new AuthConnect();
    }
    //metodo para hacer login
    //args: email=>string password=>string
    //return token=>string/session_token
    private function logingUser($email,$password){
        $user = $this->connect->getUserByEmail($email);
        if($user && password_verify($password,$user['password'])){
            return $this->sessions->sessionInit($email);
        }
        else{
            throw new Exception();
        }
    }
    //metodo para registrar un usuario
    //args: email=>string password=>string
    //return token=>string/session_token
    private function signupUser($email,$password){
        if($this->connect->signupUser($email,$password)){
            return $this->sessions->sessionInit($email);
        }
        else{
            throw new PDOException();
        }
    }
    //metodo para cerrar una sesion de usuario
    //args: void
    //return void
    private function logoutUser($token = false){
        if(!$token){
            $authheader = getallheaders()['Authorization'];
            if(isset($authheader) && strpos($authheader,'Bearer ') === 0){
                $token = trim(str_replace('Bearer ','',$authheader));
            }
            else{
                throw new Exception('el encabezado de validacion del token no existe, prueba a loguearse en el sistema o iniciar sesion');
            }
        }
        if(!$this->sessions->removeSession($token)){
            throw new Exception('no fue posible cerrar la sesion debido a que e token de sesion no es valido');
        }
    }
    //metodo de entrada de datos para inicio de sesion
    //args: request
    //return token
    public function login(){
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
        try{
            $token = $this->logingUser($email,$password);
            header('Content-Type:application/json');
            echo json_encode(['data' => $token]);
        }
        catch(Exception){
            header('Content-Type:application/json');
            echo json_encode(['error' => 'los datos de usuario no son correctos, por favor corrijalos']);
        }
    }
    //metodo de entrada de datos para crear sesion
    //args: request
    //return token
    public function signup(){
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
        try{
            $token = $this->signupUser($email,$password);
            header('Content-Type:application/json');
            echo json_encode(['data' => $token]);
        }
        catch(PDOException $e){
            header('Content-Type:application/json');
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    //metodo de entrada de datos para cerrar sesion
    //args: token
    //return: void
    public function logout(){
        $token = $_REQUEST['token'];
        try{
            $this->logoutUser($token);
            header('Content-Type:application/json');
            echo json_encode(['data' => 'usuario deslogueado del sistema']);
        }
        catch(Exception){
            header('Content-Type:application/json');
            echo json_encode(['error' => 'no fue posible desloguear al usuario del sistema']);
        }
    }
    //testing de sesiones existentes en el sistema
    public function test(){
        $this->sessions->testSession();
    }
}