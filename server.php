<?php
	$json = file_get_contents('php://input');
	$data = json_decode($json, true);
//echo var_dump($data);
	
	require_once "ActiveMongo/lib/ActiveMongo.php";
	require_once "dataGps.class.php";

	const MONGODBNAME = "tesis", MONGODBSERVER = "localhost";

	//Validaciones del Metodo
	if (!isset($_SERVER["REQUEST_METHOD"])){
  		die("error");
	}
	if($_SERVER["REQUEST_METHOD"] != "POST"){
		die("error method");
	}
	
	//Validaciones del tipo de dato
	if(!isset($data)){
		die("error en data");
	}

	try{


	//Obtener parametros via POST
	ActiveMongo::connect(MONGODBNAME, MONGODBSERVER);
	
	foreach($data as $val) {
		
		$latitud = $val["latitud"];
		$longitud = $val["longitud"];
		$altitud = $val["altitud"];
		$precision = $val["precision"];
		$tiempo = (integer)$val["tiempo"]/1000;
		$proveedor = $val["proveedor"];
		$dispositivo = $val["dispositivo"];
		$direccion = $val["direccion"];
		$velocidad = $val["velocidad"];
		$distancia = $val["distancia"];
		$tiempoCaptura = $val["tiempo_captura"];
		$registro = new dataGps($latitud, $longitud, $altitud, $precision, $tiempo, $proveedor, $dispositivo, $direccion, $velocidad, $distancia, $tiempoCaptura);

		$registro->save();

	}
	}catch(Exception $e){
		return print($e->getMessage());
	}
	
	return print("200");

?>
