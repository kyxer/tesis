<?php

/**
 * Opp the class that defines a Opp
 * @author Jhesus Colmenares
 */
require_once "ActiveMongo/lib/ActiveMongo.php";


class historial extends ActiveMongo {

    public 
    $limiteInferior, 
    $cantidadPuntos, 
    $dispositivo;
   
    public function __construct($limiteInferior=null, $cantidadPuntos=null, $dispositivo=null) {


        $this->limiteInferior = $limiteInferior;
        $this->cantidadPuntos = $cantidadPuntos;
        $this->dispositivo = $dispositivo;
        $time = time();
        $this->createdAt = $time;
        $this->updateAt = $time;
        $this->dStatus = 0;
    }

  
    function getCollectionName() {
        return 'historialDescarga';
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

    public function listData($dispositivo){

        $results = $this->_getCollection()->find(array("dispositivo"=>$dispositivo));
        $results->sort(array("createdAt" => -1));
        return $this->toArray($results);
    }

}

