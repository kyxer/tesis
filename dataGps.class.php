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
    $presicion,
    $tiempo;

   

    public function __construct($latitud, $longitud, $presicion, $tiempo) {

        $this->latitud = $latitud;
        $this->longitud = $longitud;
        $this->presicion = $presicion;
        $this->tiempo = $tiempo;
        $time = time();
        $this->createdAt = $time;
        $this->updateAt = $time;
        $this->dStatus = 0;
    }

  
    function getCollectionName() {
        return 'dataGps';
    }

}