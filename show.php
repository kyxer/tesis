<?php
	
	//echo var_dump($data);
	require_once "ActiveMongo/lib/ActiveMongo.php";
	require_once "dataGps.class.php";
	const MONGODBNAME = "tesis", MONGODBSERVER = "localhost";
	if($_SERVER["REQUEST_METHOD"] != "GET"){
		die("error method");
	}

	$pageIndex = (int)$_GET["pagina"];
	$pageSize = (int)$_GET["puntos"];
	$dispositvo = $_GET["dispositivo"];


	try{
	ActiveMongo::connect(MONGODBNAME, MONGODBSERVER);
	$dataGps = new dataGps();
	$data = $dataGps->listData($pageIndex,$pageSize,$dispositvo);

	}catch(Exception $e){
		return print($e->getMessage());
	}
	
	echo json_encode($data);
?>
