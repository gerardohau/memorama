<?php
use PHPUnit\Framework\TestCase;
final class DatabaseManagerTest extends TestCase
{
    public function testActualizar(){
      $actualiza=new Suma();
     
      $this->assertEquals(  
         true,$actualiza->actualizar(1,2)
      );
    }
}
?>