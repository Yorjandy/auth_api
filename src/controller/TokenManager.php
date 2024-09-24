<?php
namespace Mlh\Mlh\controller;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class TokenManager{
    private $key = 'fldsmdfr';

    //metodo para lanzar un nuevo token
    //args: email->string
    //return: token->string
    public function buildToken($email){
        $payload = array(
            'isd' => 'localhost',
            'aud' => 'localhost',
            'email' => $email
        );
        $token = JWT::encode($payload,$this->key,'HS256');
        return $token;
    }
    //metodo para obtener la informacion de un token
    //args: $token->string
    //return: info->stdClass
    public function getTokenInfo($token){
        $info = JWT::decode($token,new Key($this->key,'HS256'));
        if(isset($info)){
            return $info;
        }
        else{
            throw new Exception();
        }
    }
}