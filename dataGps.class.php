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

   

    public function __construct($latitud=null, $longitud=null, $presicion=null, $tiempo=null, $proveedor=null, $dispositivo=null) {

        $this->latitud = $latitud;
        $this->longitud = $longitud;
        $this->presicion = $presicion;
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

    public function toArray($results){
            $values  = array();
            $results = iterator_to_array($results);
            foreach ($results as $result) {
                $result["id"] = $result["_id"]->{'$id'};
                unset($result["_id"]);
                array_push($values, $result);
            }

            return $values;
    }

    public function listData($page_start_index, $page_size){

        $results = $this->_getCollection()->find();
        $results->skip($page_start_index * $page_size)->limit($page_size);
        $results->sort(array("createdAt" => -1));
        return $this->toArray($results);
    }


}