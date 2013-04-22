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
    $altitud,
    $precision,
    $proveedor,
    $dispositivo,
    $distancia,
    $tiempoCaptura,
    $velocidad,
    $direccion,
    $tiempo;
   
    public function __construct($latitud=null, $longitud=null, $altitud=null, $presicion=null, $tiempo=null, $proveedor=null, $dispositivo=null, $direccion=null, $velocidad =null, $distancia= null, $tiempoCaptura = null) {


        $this->latitud = $latitud;
        $this->longitud = $longitud;
        $this->altitud = $altitud;
        $this->precision = $presicion;
        $this->tiempo = $tiempo;
        $this->proveedor = $proveedor;
        $this->dispositivo = $dispositivo;
        $this->distancia = $distancia;
        $this->direccion = $direccion;
        $this->velocidad = $velocidad;
        $this->tiempoCaptura = $tiempoCaptura;
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

    public function listData($page_start_index, $page_size, $dispositivo){

        $results = $this->_getCollection()->find(array("dispositivo"=>$dispositivo));
        $results->skip($page_start_index * $page_size)->limit($page_size);
        $results->sort(array("createdAt" => -1));
        return $this->toArray($results);
    }

    public function diferenteDispositivo($command){

        $results = $this->_getConnection()->command($command);

        return $results["values"];

    }

    public function totalDispositivo($dispositivo){
        return $this->_getCollection()->find(array("dispositivo"=>$dispositivo))->count();
    }

    public function listDataRange($limiteInferior, $cantidadPuntos, $dispositivo){

        $results = $this->_getCollection()->find(array("dispositivo"=>$dispositivo));
        $results->skip($limiteInferior)->limit($cantidadPuntos);
        //$results->sort(array("createdAt" => -1));
        return $this->toArray($results);
    }


}

