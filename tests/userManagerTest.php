<?php
use PHPUnit\Framework\TestCase;
final class userManagerTest extends TestCase
{

    private $UserManager;
    private $dbManager;

    private function setupMockito(){
        $this->UserManager = UserManager::getInstance();
        $this->dbManager = Mockery::mock(DatabaseManager::class);
        $this->dbManager->shouldReceive('close')->andReturn(null);
        $this->dbManager->shouldReceive('insertQuery')->once()->with("")->andReturn(false);
        $this->UserManager->setDBManager($this->dbManager);
    }
    public function testSetUser(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('insertQuery')->with("INSERT INTO usuario (nombre, clave, tipo) VALUES('Tony','password','0')")->andReturn(json_encode('{}'));
        $this->assertJson( 
            $this->UserManager->SetUser("Tony","password",0)
        );    
        $this->assertEquals(  
            false,$this->UserManager->SetUser(null,"password",0)
        );
    }
    public function testUpdateUser(){
       
        $this->setupMockito();
        $this->dbManager->shouldReceive('insertQuery')->with("UPDATE usuario set nombre = 'Tony' , clave = 'nuevaPassword' , tipo = '3' WHERE id=1")->andReturn(json_encode('{}'));
        $this->assertJson(  
            $this->UserManager->UpdateUser(1,"Tony","nuevaPassword",3)
         );
        $this->assertEquals(  
            false,$this->UserManager->UpdateUser(null,"Juan","nuevaPassword",3)
        );
    }

    public function testGetUser(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario WHERE nombre='Tony' AND clave='nuevaPassword'")->andReturn(json_encode('{}'));
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario WHERE nombre='Juan' AND clave='aaa'")->andReturn(false);
        $this->assertJson(  
            $this->UserManager->GetUser("Tony","nuevaPassword")
         );
        $this->assertEquals(  
        false,$this->UserManager->GetUser("Juan","aaa")
        );
    }

    public function testGetUserById(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario WHERE id='1' ")->andReturn(json_encode('{}'));
        $this->dbManager->shouldReceive('realizeQuery')->with( "SELECT * FROM usuario WHERE id='2' ")->andReturn(false);
        $this->assertJson(  
            $this->UserManager->GetUserById(1)
         );
        $this->assertEquals(  
            false,$this->UserManager->GetUserById(2)
        );
    }

    public function testDeleteUser(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('insertQuery')->with("DELETE FROM usuario WHERE id = 1")->andReturn(json_encode('{}'));
        $this->dbManager->shouldReceive('insertQuery')->with("DELETE FROM usuario WHERE id = 2")->andReturn(false);
        
        $this->assertJson(  
            $this->UserManager->DeleteUser(1)
        );
        $this->assertEquals(  
            false,$this->UserManager->DeleteUser(2)
        );
    }
 
    public function testgetAllUsersPositive(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->once()->with("SELECT * FROM usuario")->andReturn(json_encode('{}'));
        
        $this->assertJson(  
            $this->UserManager->GetAllUsers()
         );
    }   
    public function testgetAllUsersNegative(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->once()->with("SELECT * FROM usuario")->andReturn(false);

        $this->assertEquals(  
        false,$this->UserManager->GetAllUsers()
        );
    }
}
?>