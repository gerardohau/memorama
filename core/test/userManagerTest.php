<?php
use PHPUnit\Framework\TestCase;
final class userManagerTest extends TestCase
{

    private $UserManager;
    private $dbManager;

    protected function setUp(): void{
        $this->UserManager = UserManager::getInstance();
        $this->dbManager = Mockery::mock(DatabaseManager::class);
        $this->dbManager->shouldReceive('close')->andReturn(null);
        $this->dbManager->shouldReceive('insertQuery')->once()->with("")->andReturn(false);
        $this->UserManager->setDBManager($this->dbManager);
    }
    protected function tearDown(): void {
        $this->UserManager->__destruct();
    }
    public function testSetUser(){
        $this->dbManager->shouldReceive('insertQuery')->with("INSERT INTO usuario (nombre, clave, tipo) VALUES('Tony','password','0')")->andReturn(["1","Tony","0","password"]);
        $this->assertIsArray( 
            $this->UserManager->SetUser("Tony","password",0)
        );    
        
    }

    public function testSetUserNegativo(){
        $this->dbManager->shouldReceive('insertQuery')->with("INSERT INTO usuario (nombre, clave, tipo) VALUES('Juan','password','0')")->andReturn(false);
        $this->assertEquals(
            "",$this->UserManager->SetUser("Juan","password",0)
        );
    }


    public function testUpdateUser(){
        $this->dbManager->shouldReceive('insertQuery')->with("UPDATE usuario set nombre = 'Tony' , clave = 'nuevaPassword' , tipo = '3' WHERE id=1")->andReturn(["1","Tony","4","nuevaPassword"]);
        $this->assertIsArray(  
            $this->UserManager->UpdateUser(1,"Tony","nuevaPassword",3)
         );
    }

    public function testUpdateUserNegativo(){
        $this->dbManager->shouldReceive('insertQuery')->with("UPDATE usuario set nombre = 'Juan' , clave = 'nuevaPassword' , tipo = '3' WHERE id=4")->andReturn(false);
       
        $this->assertEquals(
            "",$this->UserManager->UpdateUser(4,"Juan","nuevaPassword",3)
        );
        
    }

    public function testGetUser(){
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario WHERE nombre='Tony' AND clave='nuevaPassword'")->andReturn(["1","Tony","4","nuevaPassword"]);
        $this->assertJson(  
            $this->UserManager->GetUser("Tony","nuevaPassword")
         );
    }

    public function testGetUserNegativo(){
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario WHERE nombre='Juan' AND clave='aaa'")->andReturn(false);
        
        $this->assertEquals(
            "Tabla usuario vacia",$this->UserManager->GetUser("Juan","aaa")
        );
    }

    public function testGetUserById(){
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario WHERE id='1' ")->andReturn(["1","Tony","4","nuevaPassword"]);
        $this->assertJson(  
            $this->UserManager->GetUserById(1)
         );
    }

    public function testGetUserByIdNegativo(){
        $this->dbManager->shouldReceive('realizeQuery')->with( "SELECT * FROM usuario WHERE id='4' ")->andReturn(false);
        $this->assertEquals(  
            "Tabla usuario vacia",$this->UserManager->GetUserById(4)
        );
    }

    public function testDeleteUser(){
        $this->dbManager->shouldReceive('insertQuery')->with("DELETE FROM usuario WHERE id = 1")->andReturn(["1","Tony","4","nuevaPassword"]);
        
        $this->assertIsArray(  
            $this->UserManager->DeleteUser(1)
        );
    }

    public function testDeleteUserNegativo(){
        $this->dbManager->shouldReceive('insertQuery')->with("DELETE FROM usuario WHERE id = 4")->andReturn(false);
        
        $this->assertEquals(  
            "",$this->UserManager->DeleteUser(4)
        );
    }

    public function testgetAllUsersPositive(){
        $this->dbManager->shouldReceive('realizeQuery')->once()->with("SELECT * FROM usuario")->andReturn([['id'=>"1",'nombre'=>"Tony",'tipo'=>"4",'clave'=>"nuevaPassword"]]);
        
        $this->assertJson(  
            $this->UserManager->GetAllUsers()
         );
    }  
    

    public function testgetAllUsersNegative(){
        $this->dbManager->shouldReceive('realizeQuery')->once()->with("SELECT * FROM usuario")->andReturn(false);

        $this->assertEquals(  
        "Tabla usuario vacia",$this->UserManager->GetAllUsers()
        );
    }
}
?>