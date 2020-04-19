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

    public function test_EjecucionDelMetodoGetParejaConResultadosVacios(){
        $this->dbManager->shouldReceive('realizeQuery')
                        ->with("SELECT concepto,descripcion, FROM parejas WHERE id='1' AND idmateria= '1'");
        $response = $this->parejasManager->getPareja(1,1);

        $this->assertIsString($response);

        $this->assertEquals( 
            "tabla de parejas vacia", 
            $response, 
            "actual value is not equals to expected"
        );

    }

    public function test_EjecucionDelMetodoGetParejaRetornandoDatos(){
        $this->dbManager->shouldReceive('realizeQuery')
                        ->with("SELECT concepto,descripcion, FROM parejas WHERE id='1' AND idmateria= '1'")
                        ->andReturn([
                            "id"=>"1", 
                            "idmateria"=>"1",
                            "concepto" => "concepto",
                            "descripcion" => "alguna descripción"
                        ]);
        $response = $this->parejasManager->getPareja(1,1);

        $this->assertJson($response);

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                "id"=>"1", 
                "idmateria"=>"1",
                "concepto" => "concepto",
                "descripcion" => "alguna descripción"
            ]),
            $response,
            "actual Json is not equals to expected Json"
        );
    }

    public function test_EjecucionDelMetodoSetPareja(){
        $this->dbManager->shouldReceive('insertQuery')
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

    public function test_EjecucionDelMetodoSetParejaFalse(){
        $this->dbManager->shouldReceive('insertQuery')
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

    public function test_EjecucionDelMetodoUpdateParejas(){
        $this->dbManager->shouldReceive('insertQuery')
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

    public function test_EjecucionDelMetodoUpdateParejaFalse(){
        $this->dbManager->shouldReceive('insertQuery')
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
    
    public function test_EjecucionDelMetodoDeleteParejas(){
        $this->dbManager->shouldReceive('insertQuery')
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

    public function test_EjecucionDelMetodoDeleteParejaFalse(){
        $this->dbManager->shouldReceive('insertQuery')
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

    


}