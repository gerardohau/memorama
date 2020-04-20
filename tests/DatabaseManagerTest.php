<?php
use PHPUnit\Framework\TestCase;
final class DatabaseManagerTest extends TestCase
{
  private $mysqli;
  private $dbManager;

  protected function setUp(): void{
      $this->dbManager = DataBaseManager::getInstance();
      $this->mysqli = Mockery::mock(Mysqli::class);
      $this->mysqli->shouldReceive('query')->once()->with("")->andReturn(false);
      $this->mysqli->shouldReceive('close')->andReturn(true);
      $this->dbManager->setMysqli($this->mysqli);
  } 

  protected function tearDown(): void {
    $this->dbManager->__destruct();
  }
  
  public function testInsertQuery(){
      $this->mysqli->shouldReceive('query')->with("INSERT INTO materias(nombre) VALUES ('Psicologia')")->andReturn(true);
      $this->assertJson( 
        $this->dbManager->insertQuery("INSERT INTO materias(nombre) VALUES ('Psicologia')")
      ); 
      $this->assertEquals( 
        false, $this->dbManager->insertQuery("")
      );   
      
  }
   
    public function testRealizeQuery(){
      $this->mysqli->shouldReceive('query')->with("SELECT * FROM materias where id = 2")->andReturn(array());
      $this->assertEquals( 
        array(),$this->dbManager->realizeQuery("SELECT * FROM materias where id = 2")
      ); 
      $this->assertEquals( 
        false, $this->dbManager->realizeQuery("")
      ); 
    }

    public function testClose(){
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