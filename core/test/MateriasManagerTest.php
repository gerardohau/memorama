<?php
use PHPUnit\Framework\TestCase;

require_once("core\php\DataBaseManager.php");
final class MateriasManagerTest extends TestCase
{
    private $materiasManager;
    private $dbMock;

    protected function setUp() : void
    {
        $this->dbMock = Mockery::mock(DatabaseManager::class);
        $this->dbMock->shouldReceive('close')->andReturn(null);

        $this->materiasManager = MateriasManager::getInstance($this->dbMock);
        $this->materiasManager->setDBManager($this->dbMock);
    }

    public function testGetMateria(){
        $idmateria = 1;
        $this->dbMock->shouldReceive('realizeQuery')->with("SELECT * FROM materias WHERE id = ".$idmateria)->once()->andReturn(json_decode('[{"id":"1","nombre":"Psicologia"}]'));
        $this->assertEquals(
            '[{"id":"1","nombre":"Psicologia"}]', $this->materiasManager->getMateria(1), "No se recibió la materia correcta"
        );

    }

    public function testGetMateriaNegativo(){
        $idmateria = 1;
        $this->dbMock->shouldReceive('realizeQuery')->with("SELECT * FROM materias WHERE id = ".$idmateria)->once()->andReturn(null);
        $this->assertEquals(
            'Tabla de materias esta vacia', $this->materiasManager->getMateria(1), "No se recibió la materia correcta"
        );

    }

    public function testSetMateria(){
        $nameTest = 'Espanol';
        $this->dbMock->shouldReceive('insertQuery')->with("INSERT INTO materias (nombre) VALUES('".$nameTest."')")->once()->andReturn(true);
        $this->assertEquals(  
            '',$this->materiasManager->setMateria($nameTest), "La respuesta de setMateria no es una cadena vacía"
        );
    }
    public function testSetMateriaNegativo(){
        $nameTest = 'Espanol';
        $this->dbMock->shouldReceive('insertQuery')->with("INSERT INTO materias (nombre) VALUES('".$nameTest."')")->once()->andReturn("Error");
        $this->assertEquals(
            "Error",$this->materiasManager->setMateria($nameTest), "La respuesta de setMateria no fue correcta"
        );
    }

    public function testUpdateMateria(){
        $idmateria = 1;
        $nameTest = 'Espanol';
        $this->dbMock->shouldReceive('insertQuery')->with("UPDATE materias set nombre= '$nameTest' WHERE id =".intval($idmateria))->once()->andReturn(true);
        $this->assertEquals(  
            '',$this->materiasManager->updateMateria($idmateria,$nameTest), "La respuesta de updateMateria no es una cadena vacía"
        );
    }

    public function testUpdateMateriaNegativo(){
        $idmateria = 1;
        $nameTest = 'Espanol';
        $this->dbMock->shouldReceive('insertQuery')->with("UPDATE materias set nombre= '$nameTest' WHERE id =".intval($idmateria))->once()->andReturn("Error");
        $this->assertEquals(  
            'Error',$this->materiasManager->updateMateria($idmateria,$nameTest), "La respuesta de updateMateria no es igual al resultado"
        );
    }

    public function testDeleteMateria(){
        $idmateria = 1;
        $this->dbMock->shouldReceive('insertQuery')->with("DELETE FROM materias WHERE id = '$idmateria'")->once()->andReturn(true);
        $this->assertEquals(  
            '',$this->materiasManager->deleteMateria($idmateria), "La respuesta deleteMateria no es una cadena vacía"
        );
    }

    public function testDeleteMateriaNegativo(){
        $idmateria = 1;
        $this->dbMock->shouldReceive('insertQuery')->with("DELETE FROM materias WHERE id = '$idmateria'")->once()->andReturn("Error");
        $this->assertEquals(  
            'Error',$this->materiasManager->deleteMateria($idmateria), "La respuesta deleteMateria no es igual al resultado enviado"
        );
    }

    public function testGetAllMateria(){
        $this->dbMock->shouldReceive('realizeQuery')->with("SELECT * FROM materias")->once()->andReturn(json_decode('[{"id":"1","nombre":"filosofia cuantica"},{"id":"2","nombre":"Semat"}]', true));
        $this->assertEquals(  
           '[[{"id":"1","name":"filosofia cuantica"},{"id":"2","name":"Semat"}]]',$this->materiasManager->getAllMateria()
        );

    }

    public function testGetAllMateriaNegativo(){
        $this->dbMock->shouldReceive('realizeQuery')->with("SELECT * FROM materias")->once()->andReturn(null);
        $this->assertEquals(  
           'tabla materia vacia',$this->materiasManager->getAllMateria()
        );

    }

    protected function tearDown() : void
    {
        $this->materiasManager->__destruct();
    }
}