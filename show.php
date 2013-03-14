<?php
	
	//echo var_dump($data);
	require_once "ActiveMongo/lib/ActiveMongo.php";
	require_once "dataGps.class.php";
	const MONGODBNAME = "tesis", MONGODBSERVER = "localhost";
	if($_SERVER["REQUEST_METHOD"] != "GET"){
		die("error method");
	}
	try{
	ActiveMongo::connect(MONGODBNAME, MONGODBSERVER);
	$dataGps = new dataGps();
	$data = $dataGps->listData(0,100);

	
	}catch(Exception $e){
		return print($e->getMessage());
	}
	
	echo json_encode($data);
?>