<?php
/**
 * Created by IntelliJ IDEA.
 * User: jonathaneduardo
 * Date: 09/04/2016
 * Time: 02:11 PM
 */

require_once("DataBaseManager.php");

class UserManager{
    private $dbManager;
    private static $_instance;

    private function __construct(){
        $this->dbManager = DataBaseManager::getInstance();
    }

    public function __destruct(){
        $this->dbManager->close();
        self::$_instance = null;
    }
    public function setDBManager($newDBManager){
        $this->dbManager = $newDBManager;
    }
    public static function getInstance(){
        if(self::$_instance == null){
            self::$_instance = new UserManager();
        }
        return self::$_instance;
    }

    public function setUser($name, $password, $tipo){
        $query ="";
        if (!(is_null($name) || is_null($password) || is_null($tipo))){
            $query = "INSERT INTO usuario (nombre, clave, tipo) VALUES('$name','$password','$tipo')";
        }

        $resultado = $this->dbManager->insertQuery($query);
        return $resultado;
    }

    public function updateUser($id,$name, $password, $tipo){
        $query ="";
        if (!(is_null($id) || is_null($name) || is_null($password) || is_null($tipo))){
         $query = "UPDATE usuario set nombre = '$name' , clave = '$password' , tipo = '$tipo' WHERE id=".intval($id);
        }
        $resultado = $this->dbManager->insertQuery($query);

        return $resultado;
    }

    public function getUser($name, $password){
        $query="";
        if(!( is_null($name) || is_null($password))){
            $query = "SELECT * FROM usuario WHERE nombre='$name' AND clave='$password'";
        }

        $resultado = $this->dbManager->realizeQuery($query);

        return $resultado;
    }

    function getUserById($id){
        $query = "SELECT * FROM usuario WHERE id='$id' ";

        $resultado = $this->dbManager->realizeQuery($query);

        return $resultado;
    }

    public function deleteUser($UserId){
        if(is_null($UserId)){
            return false;
        }
        $query = "DELETE FROM usuario WHERE id = $UserId";

        $resultado = $this->dbManager->insertQuery($query);

        return $resultado;
    }


    public function getAllUsers(){
        $query = "SELECT * FROM usuario";

        $resultado = $this->dbManager->realizeQuery($query);

        return $resultado;
    }


    private function setValuesToResult($result){
        $users = array();
        for ($i=0;$i<count($result);$i++) {
            $users['id'] = $result[$i]['id'];
            $users['name'] = $result[$i]['nombre'];
            $users['type'] = $result[$i]['tipo'];
            $users['password'] = $result[$i]['clave'];

            $usersList[] = $users;

        }

        return $usersList;
    }
}