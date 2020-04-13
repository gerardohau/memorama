<?php
class Suma{

    public function __construct(){
        echo "Actualizando";
    }

    public function actualizar($primero,$segundo){
        $suma = $primero + $segundo;
        if($suma == 3){
            return true;
        }

        else{
            return false;
        }

    }
}

    
?>