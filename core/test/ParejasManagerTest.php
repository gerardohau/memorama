<?php


use PHPUnit\Framework\TestCase;

require_once("core\php\DataBaseManager.php");

class ParejasManagerTest extends TestCase{


    protected function setUp(): void
    {
        $this->dbManager = Mockery::mock(DatabaseManager::class);
        $this->parejasManager = ParejasManager::getInstance($this->dbManager);
        $this->dbManager->shouldReceive('close')->andReturn(null);
    }

    protected function tearDown(): void
    {
        $this->parejasManager->__destruct();
    }

    public function testGetPareja(){
        $this->dbManager->shouldReceive('realizeQuery')
                        ->once()
                        ->with("SELECT concepto,descripcion, FROM parejas WHERE id='1' AND idmateria= '1'");
        $response = $this->parejasManager->getPareja(1,1);

        $this->assertIsString($response);

        $this->assertEquals( 
            "tabla de parejas vacia", 
            $response, 
            "actual value is not equals to expected"
        );

    }

    public function testGetParejaNegativo(){
        $this->dbManager->shouldReceive('realizeQuery')
                        ->with("SELECT concepto,descripcion, FROM parejas WHERE id='1' AND idmateria= '1'")
                        ->once()
                        ->andReturn([
                            "id"=>"1", "idmateria"=>"1", "concepto" => "concepto", "descripcion" => "alguna descripción"
                        ]);
        $response = $this->parejasManager->getPareja(1,1);

        $this->assertJson($response);

        $this->assertJsonStringEqualsJsonString(
            json_encode(["id"=>"1", "idmateria"=>"1","concepto" => "concepto","descripcion" => "alguna descripción"
            ]),
            $response,
            "actual Json is not equals to expected Json"
        );
    }

    public function testSetPareja(){
        $this->dbManager->shouldReceive('insertQuery')
                        ->once()
                        ->with("INSERT INTO parejas (concepto,descripcion,idmateria) VALUES('concepto 1','descripcion 1','1')")
                        ->andReturn(json_encode([]));

        $response = $this->parejasManager->setPareja('1','concepto 1', 'descripcion 1');

        $this->assertJson($response);

        $this->assertJsonStringEqualsJsonString(
            json_encode([]),
            $response,
            "actual Json is not equals to expected Json"
        );
    }

    public function testSetParejaNagativo(){
        $this->dbManager->shouldReceive('insertQuery')
                        ->once()
                        ->with("INSERT INTO parejas (concepto,descripcion,idmateria) VALUES('concepto 1','descripcion 1','1')")
                        ->andReturn(false);

        $response = $this->parejasManager->setPareja('1','concepto 1', 'descripcion 1');

        $this->assertIsString($response);

        $this->assertEquals( 
            "", 
            $response, 
            "actual value is not equals to expected"
        );
    }

    public function testUpdatePareja(){
        $this->dbManager->shouldReceive('insertQuery')
                        ->once()
                        ->with("UPDATE parejas set idmateria = '1' , concepto = 'concepto 1' , descripcion = 'descripcion 1' WHERE id=1")
                        ->andReturn(json_encode([]));

        $response = $this->parejasManager->updatePareja(1,1, "concepto 1", "descripcion 1");

        $this->assertJson($response);

        $this->assertJsonStringEqualsJsonString(
            json_encode([]),
            $response,
            "actual Json is not equals to expected Json"
        );
    }

    public function testUpdateParejaNegativo(){
        $this->dbManager->shouldReceive('insertQuery')
                        ->once()
                        ->with("UPDATE parejas set idmateria = '1' , concepto = 'concepto 1' , descripcion = 'descripcion 1' WHERE id=1")
                        ->andReturn(false);

        $response = $this->parejasManager->updatePareja(1,1, "concepto 1", "descripcion 1");
        $this->assertIsString($response);

        $this->assertEquals( 
            "", 
            $response, 
            "actual value is not equals to expected"
        );
    }
    
    public function testDeletePareja(){
        $this->dbManager->shouldReceive('insertQuery')
                        ->once()
                        ->with("DELETE FROM parejas WHERE id='1' AND idmateria='1'")
                        ->andReturn(json_encode([]));

        $response = $this->parejasManager->deletePareja(1, 1);

        $this->assertJson($response);

        $this->assertJsonStringEqualsJsonString(
            json_encode([]),
            $response,
            "actual Json is not equals to expected Json"
        );
    }

    public function testDeleteParejaNegativo(){
        $this->dbManager->shouldReceive('insertQuery')
                        ->once()
                        ->with("DELETE FROM parejas WHERE id='1' AND idmateria='1'")
                        ->andReturn(false);

        $response = $this->parejasManager->deletePareja(1, 1);
        $this->assertIsString($response);

        $this->assertEquals( 
            "", 
            $response, 
            "actual value is not equals to expected"
        );
    }

    public function testGetAllParejasTheMateria(){
        $this->dbManager->shouldReceive('realizeQuery')
                        ->once()
                        ->with("SELECT concepto,descripcion FROM parejas WHERE idmateria = 1")
                        ->andReturn(
                            [
                                ["id"=>"1", "idmateria"=>"1","concepto" => "concepto 1","descripcion" => "descripción 1"],

                                ["id"=>"2", "idmateria"=>"1","concepto" => "concepto 2","descripcion" => "descripción 2"]
                            ]);
        $response = $this->parejasManager->getAllParejasTheMateria(1);

        $this->assertJson($response);
        $this->assertCount(2, json_decode($response));
        $this->assertJsonStringEqualsJsonString(
        json_encode([
                ["id"=>"1", "idmateria"=>"1","concepto" => "concepto 1","descripcion" => "descripción 1"],
                
                ["id"=>"2", "idmateria"=>"1","concepto" => "concepto 2","descripcion" => "descripción 2"]
            ]),
        $response,
        "actual Json is not equals to expected Json"
        );
    }
    
    public function testGetAllParejasTheMateriaNegativo(){
        $this->dbManager->shouldReceive('realizeQuery')
                        ->once()
                        ->with("SELECT concepto,descripcion FROM parejas WHERE idmateria = 1")
                        ->andReturn(null);
        $response = $this->parejasManager->getAllParejasTheMateria(1);

        $this->assertIsString($response);

        $this->assertEquals( 
            "", 
            $response, 
            "actual value is not equals to expected"
        );
    }

    public function testGetAllParejas(){
        $this->dbManager->shouldReceive('realizeQuery')
        ->with("SELECT * FROM parejas")
        ->once()
        ->andReturn(
            [
                ["id"=>"1", "idmateria"=>"1","concepto" => "concepto 1","descripcion" => "descripción 1"],

                ["id"=>"2", "idmateria"=>"2","concepto" => "concepto 2","descripcion" => "descripción 2"],

                ["id"=>"3", "idmateria"=>"3","concepto" => "concepto 3","descripcion" => "descripción 3"]
            ]);
        $response = $this->parejasManager->getAllParejas();

        $this->assertJson($response);
        $this->assertCount(1, json_decode($response));
        $this->assertJsonStringEqualsJsonString(
        json_encode([[
            ["id"=>"1", "idMatter"=>"1","concept" => "concepto 1","definition" => "descripción 1"],

            ["id"=>"2", "idMatter"=>"2","concept" => "concepto 2","definition" => "descripción 2"],
            
            ["id"=>"3", "idMatter"=>"3","concept" => "concepto 3","definition" => "descripción 3"]
        ]]),
        $response,
        "actual Json is not equals to expected Json");

        $this->assertTrue(true);

        
    }
    
    public function testGetAllParejasNegativo(){
        $this->dbManager->shouldReceive('realizeQuery')
        ->with("SELECT * FROM parejas")
        ->once()
        ->andReturn(null);
        $response = $this->parejasManager->getAllParejas();

        $this->assertIsString($response);

        $this->assertEquals( 
            "tabla materia vacia", 
            $response, 
            "actual value is not equals to expected"
        );
        
    }


}