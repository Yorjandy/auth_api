<?php
namespace Mlh\Mlh\controller;
use Mlh\Mlh\model\AuthConnect;
use Mlh\Mlh\controller\TokenManager;
use Exception;
use PDOException;

class AuthManager{
    private $model;
    private $tokenM;

    public function __construct(){
        $this->model = new AuthConnect();
        $this->tokenM = new TokenManager();
    }

    //metodo para iniciar sesion a un usuario
    //args: email->string password->string
    //return: token->string
    private function loginUser($email,$password){
        $user = $this->model->getUserByEmail($email);
        if($user && password_verify($password, $user['password'])){
            $token = $this->tokenM->buildToken($email);
            return $token;
        }
        else{
            throw new Exception();
        }
    }
    //metodo para registrar a un usuario en el sistema
    //args: email->string password->string
    //return: token->string
    private function signupUser($email,$password){
        if($this->model->signupUser($email,$password)){
            return $this->tokenM->buildToken($email);
        }
        else{
            throw new PDOException();
        }
    }
    //metodo para manejo de datos de entrada y salida de login
    //args: requestdata->assoc_array
    //return: token->string
    public function login($requestdata = []){
        if(isset($requestdata['email']) && isset($requestdata['password'])){
            $email = $requestdata['email'];
            $password = $requestdata['password'];
        }
        // else if(isset($_REQUEST['email']) && isset($_REQUEST['password'])){
        //     $email = $_REQUEST['email'];
        //     $password = $_REQUEST['password'];
        // }
        else{
            header('Content-Type:application/json');
            echo json_encode(['error'=>'no se pasaron los parametros indicados para hacer el registro']);
            die();
        }
        try{
            $token = $this->loginUser($email,$password);
            header('Content-Type:application/json');
            echo json_encode(['token'=>$token]);
        }
        catch(Exception){
            header('Content-Type:application/json');
            echo json_encode(['error'=>'no fue posible loguear al usuario']);
        }
    }
    //metodo para manejo de datos de entrada y salida de signup
    //args: requestdata->assoc_array
    //return: token->string
    public function signup($requestdata = []){
        if(isset($requestdata['email']) && isset($requestdata['password'])){
            $email = $requestdata['email'];
            $password = $requestdata['password'];
        }
        // else if(isset($_REQUEST['email']) && isset($_REQUEST['password'])){
        //     $email = $_REQUEST['email'];
        //     $password = $_REQUEST['password'];
        // }
        else{
            header('Content-Type:application/json');
            echo json_encode(['error'=>'no se pasaron los parametros indicados para hacer el registro']);
            die();
        }
        try{
            $token = $this->signupUser($email,$password);
            header('Content-Type:application/json');
            echo json_encode(['token'=>$token]);
        }
        catch(Exception){
            header('Content-Type:application/json');
            echo json_encode(['error'=>'no fue posible registrar al usuario']);
        }
    }
}