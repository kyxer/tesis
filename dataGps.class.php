<?php

/**
 * Opp the class that defines a Opp
 * @author Jhesus Colmenares
 */
require_once "ActiveMongo/lib/ActiveMongo.php";


class dataGps extends ActiveMongo {

    public 
    $latitud, 
    $longitud,  
    $tiempo,
    $proveedor,
    $precision,
    $dispositivo;
   

    public function __construct($latitud, $longitud, $precision, $tiempo, $proveedor, $dispositivo) {

        $this->latitud = $latitud;
        $this->longitud = $longitud;
        $this->precision = $precision;
        $this->tiempo = $tiempo;
        $this->proveedor = $proveedor;
        $this->dispositivo = $dispositivo;
        $time = time();
        $this->createdAt = $time;
        $this->updateAt = $time;
        $this->dStatus = 0;
    }

  
    function getCollectionName() {
        return 'dataGps';
    }

}
