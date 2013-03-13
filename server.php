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
		$presicion = $val["presicion"];
		$tiempo = $val["tiempo"];
		$proveedor = $val["proveedor"];
		$dispositivo = $val["dispositivo"];
		$registro = new dataGps($latitud, $longitud, $presicion, $tiempo, $proveedor, $dispositivo);
		$registro->save();

	}
	}catch(Exception $e){
		return print($e->toString());
	}
	
	return print(200);
?>