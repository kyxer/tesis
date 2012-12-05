<?php

/**
 * Opp the class that defines a Opp
 * @author Jhesus Colmenares
 */
require_once "ActiveMongo/lib/ActiveMongo.php";


class dataGps extends ActiveMongo {

    public 
    $latitude, 
    $longitude, 
    $altitude, 
    $accuracy,
    $altitudeAccuracy,
    $heading, 
    $speed;

   

    public function __construct($latitude, $longitude, $altitude, $accuracy, $altitudeAccuracy, $heading, $speed) {

        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->altitude = $altitude;
        $this->accuracy = $accuracy;
        $this->altitudeAccuracy = $altitudeAccuracy;
        $this->heading = $heading;
        $this->speed = $speed;

        $time = time();
        $this->createdAt = $time;
        $this->updateAt = $time;
        $this->dStatus = 0;
    }

  
    function getCollectionName() {
        return 'dataGps';
    }

}