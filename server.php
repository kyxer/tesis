<?php
	
	require_once "ActiveMongo/lib/ActiveMongo.php";
	require_once "dataGps.class.php";

	const MONGODBNAME = "tesis", MONGODBSERVER = "localhost";

	/*Validaciones del Metodo*/
	if (!isset($_SERVER["REQUEST_METHOD"])){
  		die("error");
	}
	if($_SERVER["REQUEST_METHOD"] != "POST"){
		die("error method");
	}
	/*-----------------*/
	/*Validaciones del tipo de dato*/
	if(!isset($_POST["data"])){
		die("error en data");
	}

	/**Obtener parametros via POST*/
	ActiveMongo::connect(MONGODBNAME, MONGODBSERVER);
	$data = $_POST["data"];
	foreach($data as $val) {
		$latitude = $val["latitude"];
		$longitude = $val["longitude"];
		$altitude = $val["altitude"];
		$accuracy = $val["accuracy"];
		$altitudeAccuracy = $val["altitudeAccuracy"];
		$heading = $val["heading"];
		$speed = $val["speed"];
		$registro = new dataGps($latitude, $longitude, $altitude, $accuracy, $altitudeAccuracy,$heading,$speed);
		$registro->save();
	}
	

	

	
?>