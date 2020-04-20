<?php
use PHPUnit\Framework\TestCase;

require_once("core\php\DataBaseManager.php");
class PuntajesManajerTest extends TestCase
{
    private $puntajesManajer;
    private $dbManager;

    private $lista = [
        0 => ["id_usuario" => "1","id_materia" => "1", "fecha" => "2020-04-18 02:04:47",
             "dificultad" => "medio","puntaje" => "20","parejas_encontradas" => "8"],
        1 => ["id_usuario" => "1","id_materia" => "1", "fecha" => "2020-04-18 02:10:47",
             "dificultad" => "medio","puntaje" => "20","parejas_encontradas" => "8"]  
    ];

    protected function setUp(): void
    {
        $this->dbManager = Mockery::mock(DatabaseManager::class);
        $this->puntajesManajer = PuntajesManajer::getInstance($this->dbManager);  
    }

    protected function tearDown(): void
    {
        $this->puntajesManajer->__destruct();
    }

    public function testSetPuntajePositivo(){
        $idUsuario =1;
        $idMateria =1;
        $fecha = date("2020-04-18 02:04:47");
        $dificultad ="medio";
        $puntaje =100;
        $foundPeers =10;
        
        $query = "INSERT INTO puntajes (id_usuario,id_materia,fecha,dificultad,puntaje,parejas_encontradas) VALUES('1','1','2020-04-18 02:04:47','medio',100,10)";
        
        $this->dbManager->shouldReceive('insertQuery')
                        ->with($query)
                        ->andReturn(true);
                
        $this->assertIsNumeric($idUsuario);
        $this->assertIsNumeric($idMateria);
        $this->assertIsString($fecha);
        $this->assertIsString($dificultad);
        $this->assertIsNumeric($puntaje);
        $this->assertIsNumeric($foundPeers);
        
        $this->assertEquals( 
            "", 
            $this->puntajesManajer->setPuntaje($idUsuario,$idMateria,$fecha,$dificultad,$puntaje,$foundPeers)
        );
       
    }

    public function testSetPuntajeNegativo(){        
        $idUsuario =null;
        $idMateria =null;
        $fecha = date("2020-04-18 02:04:47");
        $dificultad ="medio";
        $puntaje ='';
        $foundPeers =10;
        
        $query = "INSERT INTO puntajes (id_usuario,id_materia,fecha,dificultad,puntaje,parejas_encontradas) VALUES('','','2020-04-18 02:04:47','medio',,10)";

        

        $this->dbManager->shouldReceive('insertQuery')
                        ->with($query)
                        ->andReturn(false);        

        $this->assertNull($idUsuario);
        $this->assertNull($idMateria);
        $this->assertIsString($fecha);
        $this->assertIsString($dificultad);
        $this->assertIsNotNumeric($puntaje);
        $this->assertIsNumeric($foundPeers);
        
        $this->assertEquals( 
            "", 
            $this->puntajesManajer->setPuntaje($idUsuario,$idMateria,$fecha,$dificultad,$puntaje,$foundPeers)
        );
       
    }

    public function testDeletePuntajePositivo(){
        $idUsuario =1;
        $idMateria =1;
        $fecha = date("2020-04-18 02:04:47");
        $dificultad = "medio";
        
        $query = "DELETE FROM puntajes WHERE id_usuario = '1' 
        AND id_materia = '1' AND fecha='2020-04-18 02:04:47' AND dificultad = 'medio'";

        

        $this->dbManager->shouldReceive('insertQuery')
                        ->with($query)
                        ->andReturn(true);
                         
        $this->assertIsNumeric($idUsuario);
        $this->assertIsNumeric($idMateria);
        $this->assertIsString($fecha);
        $this->assertIsString($dificultad);
        
        $this->assertEquals( 
            "", 
            $this->puntajesManajer->deletePuntaje($idUsuario,$idMateria,$fecha,$dificultad)
        );
       
    }

    public function testDeletePuntajeNegativo(){
        $idUsuario =null;
        $idMateria =null;
        $fecha = date("2020-04-18 02:04:47");
        $dificultad = '';
        
        $query = "DELETE FROM puntajes WHERE id_usuario = '' 
        AND id_materia = '' AND fecha='$fecha' AND dificultad = ''";

        

        $this->dbManager->shouldReceive('insertQuery')
                        ->with($query)
                        ->andReturn(false);
                 
        $this->assertNull($idUsuario);
        $this->assertNull($idMateria);
        $this->assertIsString($fecha);
        $this->assertEmpty($dificultad);
        
        $this->assertEquals( 
            "", 
            $this->puntajesManajer->deletePuntaje($idUsuario,$idMateria,$fecha,$dificultad)
        );
       
    }

    public function testGetAllPuntajeForUsuarioPositivo(){
        $idUsuario =1;
        $query = "SELECT * FROM puntajes WHERE id_usuario='1'";
        
        

        $this->dbManager->shouldReceive('realizeQuery')
                        ->with($query)
                        ->andReturn($this->lista);
                 
        $this->assertIsNumeric($idUsuario);

        $response = $this->puntajesManajer->getAllPuntajeForUsuario($idUsuario);

        $this->assertJson(
            $response
        );
        $this->assertEquals( 
            json_encode($this->lista),
            $response
        );
       
    }

    public function testGetAllPuntajeForUsuarioNegativo(){
        $idUsuario =null;
        $query = "SELECT * FROM puntajes WHERE id_usuario=''";
        $mensaje = "tabla materia vacia";
        

        $this->dbManager->shouldReceive('realizeQuery')
                        ->with($query)
                        ->andReturn(null);
                 
        $this->assertNull($idUsuario);
        
        $this->assertEquals( 
            $mensaje,
            $this->puntajesManajer->getAllPuntajeForUsuario($idUsuario)
        );
       
    }

    public function testGetAllPuntajeForMateriaPositivo(){
        $idMateria =1;
        $query = "SELECT * FROM puntajes WHERE id_materia='1'";
        
        
                 
        $this->dbManager->shouldReceive('realizeQuery')
                        ->with($query)
                        ->andReturn($this->lista);
    
        $this->assertIsNumeric($idMateria);

        $response = $this->puntajesManajer->getAllPuntajeForMateria($idMateria);

        $this->assertJson(
            $response
        );
        $this->assertEquals( 
            json_encode($this->lista),
            $response
        );
       
    }

    public function testGetAllPuntajeForMateriaNegativo(){
        $idMateria =null;
        $query = "SELECT * FROM puntajes WHERE id_materia=''";
        $mensaje = "tabla materia varia";
        
        

        $this->dbManager->shouldReceive('realizeQuery')
                        ->with($query)
                        ->andReturn(null);
                 
        $this->assertNull($idMateria);
        
        $this->assertEquals( 
            $mensaje,
            $this->puntajesManajer->getAllPuntajeForMateria($idMateria)
        );
       
    }

    public function testGetAllPuntajeForUsuarioAndMateriaPositivo(){
        $idMateria =1;
        $idUsuario = 1;
        $query = "SELECT * FROM puntajes WHERE id_usuario='1' AND id_materia='1'";
        
        
                 
        $this->dbManager->shouldReceive('realizeQuery')
                        ->with($query)
                        ->andReturn($this->lista);
    
        $this->assertIsNumeric($idMateria);
        $this->assertIsNumeric($idUsuario);

        $response = $this->puntajesManajer->getAllPuntajeForUsuarioAndMateria($idUsuario,$idMateria);

        $this->assertJson(
            $response
        );
        $this->assertEquals( 
            json_encode($this->lista),
            $response
        );
       
    }

    public function testGetAllPuntajeForUsuarioAndMateriaNegativo(){
        $idMateria =null;
        $idUsuario = null;
        $query = "SELECT * FROM puntajes WHERE id_usuario='' AND id_materia=''";
        $mensaje = "tabla materia varia";
        
        

        $this->dbManager->shouldReceive('realizeQuery')
                        ->with($query)
                        ->andReturn(null);
                 
        $this->assertNull($idMateria);
        $this->assertNull($idUsuario);

        $this->assertEquals( 
            $mensaje,
            $this->puntajesManajer->getAllPuntajeForUsuarioAndMateria($idUsuario,$idMateria)
        );
       
    }
    
    public function testGetAllPuntajeForMateriaAndDificultadPositivo(){
        $idMateria =1;
        $dificultad = "medio";
        $query = "SELECT * FROM puntajes WHERE id_materia='1' AND dificultad='medio'";
        
                 
        $this->dbManager->shouldReceive('realizeQuery')
                        ->with($query)
                        ->andReturn($this->lista);
    
        $this->assertIsNumeric($idMateria);
        $this->assertIsString($dificultad);

        $response = $this->puntajesManajer->getAllPuntajeForMateriaAndDificultad($idMateria,$dificultad);

        $this->assertJson(
            $response
        );
        $this->assertEquals( 
            json_encode($this->lista),
            $response
        );
       
    }

    public function testGetAllPuntajeForMateriaAndDificultadNegativo(){
        $idMateria =null;
        $dificultad = '';
        $query = "SELECT * FROM puntajes WHERE id_materia='' AND dificultad=''";
        $mensaje = "tabla materia varia";
        
        

        $this->dbManager->shouldReceive('realizeQuery')
                        ->with($query)
                        ->andReturn(null);
                 
        $this->assertNull($idMateria);
        $this->assertEmpty($dificultad);

        $this->assertEquals( 
            $mensaje,
            $this->puntajesManajer->getAllPuntajeForMateriaAndDificultad($idMateria,$dificultad)
        );
       
    }
    
    public function testGetAllPuntajeForUsuarioAndMateriaAndDificultadPositivo(){
        $idUsuario =1;
        $idMateria =1;
        $dificultad = "medio";
        $query = "SELECT * FROM puntajes WHERE id_usuario='1' AND id_materia='1' 
        AND dificultad='medio'";
        
        
                 
        $this->dbManager->shouldReceive('realizeQuery')
                        ->with($query)
                        ->andReturn($this->lista);
    
        $this->assertIsNumeric($idMateria);
        $this->assertIsNumeric($idUsuario);
        $this->assertIsString($dificultad);

        $response = $this->puntajesManajer->getAllPuntajeForUsuarioAndMateriaAndDificultad($idUsuario,$idMateria,$dificultad);

        $this->assertJson(
            $response
        );
        $this->assertEquals( 
            json_encode($this->lista),
            $response
        );
       
    }

    public function testGetAllPuntajeForUsuarioAndMateriaAndDificultadNegativo(){
        $idUsuario =null;
        $idMateria =null;
        $dificultad = '';
        $query = "SELECT * FROM puntajes WHERE id_usuario='' AND id_materia='' 
        AND dificultad=''";
        $mensaje = "tabla materia varia";
        

        $this->dbManager->shouldReceive('realizeQuery')
                        ->with($query)
                        ->andReturn(null);
                 
        $this->assertNull($idMateria);
        $this->assertNull($idUsuario);
        $this->assertEmpty($dificultad);

        $this->assertEquals( 
            $mensaje,
            $this->puntajesManajer->getAllPuntajeForUsuarioAndMateriaAndDificultad($idUsuario,$idMateria,$dificultad)
        );
       
    }   

}