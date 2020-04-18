<?php
use PHPUnit\Framework\TestCase;
final class DatabaseManagerTest extends TestCase
{
  private $mysqli;
  private $dbManager;
  private function setupMockito(){
      $this->dbManager = DataBaseManager::getInstance();
      $this->mysqli = Mockery::mock(Mysqli::class);
      $this->mysqli->shouldReceive('query')->once()->with("")->andReturn(false);
      $this->mysqli->shouldReceive('close')->andReturn(true);
      $this->dbManager->setMysqli($this->mysqli);
  } 
  
  public function testInsertQuery(){
      $this->setupMockito();
      $this->mysqli->shouldReceive('query')->with("INSERT INTO materias(nombre) VALUES ('Psicologia')")->andReturn(true);
      $this->assertJson( 
        $this->dbManager->insertQuery("INSERT INTO materias(nombre) VALUES ('Psicologia')")
      ); 
      $this->assertEquals( 
        false, $this->dbManager->insertQuery("")
      );   
      
  }
   
    public function testRealizeQuery(){
      $this->setupMockito();
      $this->mysqli->shouldReceive('query')->with("SELECT * FROM materias where id = 2")->andReturn(json_encode('{}'));
      $this->assertJson( 
        $this->dbManager->realizeQuery("SELECT * FROM materias where id = 2")
      ); 
      $this->assertEquals( 
        false, $this->dbManager->realizeQuery("")
      ); 
    }

    public function testClose(){
      $this->setupMockito();
      $this->mysqli->shouldReceive('close')->andReturn(false);
      $this->assertEquals( 
        null,$this->dbManager->close()
      ); 
      $this->assertEquals( 
        false, $this->dbManager->close()
      ); 
    }
    
}
?>