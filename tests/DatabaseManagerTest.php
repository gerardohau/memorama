<?php
use PHPUnit\Framework\TestCase;
final class DatabaseManagerTest extends TestCase
{
    public function testInsertQuery(){
      $database = DataBaseManager::getInstance();
      $this->assertEquals(  
         true, $database->insertQuery("INSERT INTO materias(nombre) VALUES ('Psicologia')")
      );
    }
   
    public function testInsertQueryFailed(){
      $database = DataBaseManager::getInstance();
      $this->assertEquals(  
         false, $database->insertQuery("INSERT INTO salones(nombre) VALUES ('Psicologia')")
      );
    }

    public function testRealizeQuery(){
      $database = DataBaseManager::getInstance();
      $result = $database->realizeQuery("SELECT * FROM materias where id = 2");
      $this->assertEquals(  
         "Semat", $result[0]['nombre']
      );
    }

    public function testRealizeQueryFailed(){
      $database = DataBaseManager::getInstance();
      $result = $database->realizeQuery("SELECT * FROM materias where id = 40");
      $this->assertEquals(  
         true, empty($result)
      );
    }
    

    
}
?>