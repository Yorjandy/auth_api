<?php
namespace Mlh\Mlh\model;
//require('vendor/autoload.php');
use PDO;
use Exception;
use PDOException;

class AuthConnect{
    private $host = 'localhost';
    private $user = 'auth_user';
    private $password = '0000';
    private $db = 'auth_db';
    private $connect;
    //constructor para establecer la conexion con la base de datos
   public function __construct() {
    try{
        $this->connect = new PDO("pgsql:host=$this->host;dbname=$this->db;",$this->user,$this->password);
    }
    catch(Exception $e){
        echo($e);
    }
   }
    //metodo para obtener un usuario pasado su id
    public function getUserById($id){
        $query = "select * from public.users where id = :id";
        $stm = $this->connect->prepare($query);
        $stm->bindParam(':id',$id);
        $stm->execute();
        return $stm->fetch(PDO::FETCH_ASSOC);
    }
    //metodo para obtener un usuario pasado su email
    public function getUserByEmail($email){
        $query = "select * from public.users where email = :email";
        $stm = $this->connect->prepare($query);
        $stm->bindParam(':email',$email);
        $stm->execute();
        return $stm->fetch(PDO::FETCH_ASSOC);
    }
    //metodo para guardar un usuario en la base de datos
    public function signupUser($email,$password){
        $query = "call signupUser(:email,:password)";
        $stm = $this->connect->prepare($query);
        $stm->bindParam(':email',$email);
        $pass = password_hash($password, PASSWORD_BCRYPT);
        $stm->bindParam(':password',$pass);
        if(!$stm->execute()){
            throw new PDOException();
        }
        else{
            return true;
        }
    }
}