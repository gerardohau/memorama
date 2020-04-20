<?php

/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 07/02/2016
 * Time: 07:55 PM
 */
define('SERVER', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', '');
define('DB', 'memorama');

class DataBaseManager {

    private $mysqli;
    private static $_instance = null;

    /**
     * DataBaseManager constructor.
     * @param $mysqli
     */
    private function __construct() {
        
    }

    public function __destruct() {
        self::$_instance = null;
        $this->mysqli = null;
    }

    public function setMysqli($new_mysqli){
        $this->mysqli = $new_mysqli;
    }

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new DataBaseManager();
        }
        return self::$_instance;
    }

    final public function __clone() {
        throw new Exception('Only one instance is allowed');
    }

    public function insertQuery($query) {
        if($query != null){
            return $this->mysqli->query($query); 
          }
          return null;
          
    }

    public function realizeQuery($query) {
        if($query != null){
            return $this->mysqli->query($query); 
          }
          return null;
    }

    public function close() {
        $this->mysqli->close();
    }

}
