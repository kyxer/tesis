<?php
	
	//echo var_dump($data);
	require_once "ActiveMongo/lib/ActiveMongo.php";
	require_once "dataGps.class.php";
	const MONGODBNAME = "tesis", MONGODBSERVER = "localhost";
	if($_SERVER["REQUEST_METHOD"] != "GET"){
		die("error method");
	}
	
	if(isset($_GET["pagina"]))
		$pageIndex = (int)$_GET["pagina"];
	if(isset($_GET["puntos"]))
		$pageSize = (int)$_GET["puntos"];
	if(isset($_GET["dispositivo"]))
		$dispositivo = $_GET["dispositivo"];
	if(isset($_GET["tiempoCaptura"]))
		$tiempoCaptura = $_GET["tiempoCaptura"];
	if(isset($_GET["limiteInferior"]))
		$limiteInferior = $_GET["limiteInferior"];
	if(isset($_GET["cantidadPuntos"]))
		$cantidadPuntos = $_GET["cantidadPuntos"];
	if(isset($_GET["section"]))
		$section = $_GET["section"];
	if(isset($_GET["repeat"]))
		$repeat = 1;
	else
		$repeat = 0;


	try{
	ActiveMongo::connect(MONGODBNAME, MONGODBSERVER);
	$dataGps = new dataGps();

	switch ($section) {
		case "index":
			$data = $dataGps->listData($pageIndex,$pageSize,$dispositivo, $tiempoCaptura);
			$endLatitud = 0;
		    $endLongitud = 0;
			foreach($data as $key => &$row){

				if($key == 0){
		    		$distanciaServer = 0;
		    		$startLatitud = $row["latitud"];
					$startLongitud = $row["longitud"];
		    	}else{
		    		if($endLatitud == 0){
						$endLatitud = $row["latitud"];
						$endLongitud = $row["longitud"];
						
					}else{
						$startLatitud = $endLatitud;
						$startLongitud = $endLongitud;
						$endLatitud = $row["latitud"];
						$endLongitud =  $row["longitud"];
					}
					 
					$radius = 6378137; // earth mean radius defined by WGS84 in meters
  					$dlon = $startLongitud - $endLongitud; 
  					$distanciaServer = acos( sin(deg2rad($startLatitud)) * sin(deg2rad($endLatitud)) +  cos(deg2rad($startLatitud)) * cos(deg2rad($endLatitud)) * cos(deg2rad($dlon))) * $radius; 
		    		
		    	}
		    	$row["distanciaServer"] = $distanciaServer;

			}

			break;
		
		case "count":
			require_once("historial.class.php");
			$data["count"] = $dataGps->totalDispositivo($dispositivo, $tiempoCaptura);
			$historial = new historial();
			$data["historial"] = $historial->listData($dispositivo, $tiempoCaptura);
			break;

		case "descarga":
			require_once("excel/PHPExcel.php");
			require_once("historial.class.php");
			require_once("excel/PHPExcel/Writer/Excel2007.php");
			$data = $dataGps->listDataRange($limiteInferior,$cantidadPuntos,$dispositivo, $tiempoCaptura);
			$historial = new historial($limiteInferior,$cantidadPuntos,$dispositivo);
			$historial->save();

			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setCreator("German Mendoza, Julio Arismendi");
			$objPHPExcel->getProperties()->setLastModifiedBy("German Mendoza, Julio Arismendi");
			$objPHPExcel->getProperties()->setTitle("Data App Android");
			$objPHPExcel->getProperties()->setDescription("Contiene la data capturada por la aplicacion");

			//Trabajamos con la hoja activa principal
			$objPHPExcel->setActiveSheetIndex(0);

			//iteramos para los resultados
			$objPHPExcel->getActiveSheet()->SetCellValue("A1", "Latitud" );
		    $objPHPExcel->getActiveSheet()->SetCellValue("B1", "Longitud");
		    $objPHPExcel->getActiveSheet()->setCellValue("C1", "Altitud");
		    $objPHPExcel->getActiveSheet()->setCellValue("D1", "Precision");
		    $objPHPExcel->getActiveSheet()->setCellValue("E1", "Distancia App");
		    $objPHPExcel->getActiveSheet()->setCellValue("F1", "Distancia Server");
		    $objPHPExcel->getActiveSheet()->setCellValue("G1", "Velocidad");
		    $objPHPExcel->getActiveSheet()->setCellValue("H1", "Direccion");
		    $objPHPExcel->getActiveSheet()->setCellValue("I1", "Tiempo de Captura");
		    $objPHPExcel->getActiveSheet()->setCellValue("J1", "Tiempo");
		    $objPHPExcel->getActiveSheet()->setCellValue("K1", "Tiempo entendible");
		    $i = 2;
		    $endLatitud = 0;
		    $endLongitud = 0;
			foreach($data as $key => $row){
				
		    	if(isset($row["precision"]))
		    		$precision = $row["precision"];
		    	else
		    		$precision = $row["presicion"];

		    	if(isset($row["altitud"]))
		    		$altitud = $row["altitud"];
		    	else
		    		$altitud = "";


		    	if($key == 0){
		    		$distanciaServer = 0;
		    		$startLatitud = $row["latitud"];
					$startLongitud = $row["longitud"];
		    	}else{
		    		if($endLatitud == 0){
						$endLatitud = $row["latitud"];
						$endLongitud = $row["longitud"];
						
					}else{
						$startLatitud = $endLatitud;
						$startLongitud = $endLongitud;
						$endLatitud = $row["latitud"];
						$endLongitud =  $row["longitud"];
					}
					 
					$radius = 6378137; // earth mean radius defined by WGS84 in meters
  					$dlon = $startLongitud - $endLongitud; 
  					$distanciaServer = acos( sin(deg2rad($startLatitud)) * sin(deg2rad($endLatitud)) +  cos(deg2rad($startLatitud)) * cos(deg2rad($endLatitud)) * cos(deg2rad($dlon))) * $radius; 
		    		
		    	}

		    	if($key == 0 || $repeat == 1){

	    			$objPHPExcel->getActiveSheet()->SetCellValue("A".$i, $row["latitud"] );
			    	$objPHPExcel->getActiveSheet()->SetCellValue("B".$i, $row["longitud"]);
			    	$objPHPExcel->getActiveSheet()->SetCellValue("C".$i, $altitud);
			    	$objPHPExcel->getActiveSheet()->setCellValue("D".$i, $precision);
			    	$objPHPExcel->getActiveSheet()->SetCellValue("E".$i, $row["distancia"]);
			    	$objPHPExcel->getActiveSheet()->SetCellValue("F".$i, $distanciaServer);
			    	$objPHPExcel->getActiveSheet()->setCellValue("G".$i, $row["velocidad"]);
			    	$objPHPExcel->getActiveSheet()->SetCellValue("H".$i, $row["direccion"]);
			    	$objPHPExcel->getActiveSheet()->setCellValue("I".$i, $row["tiempoCaptura"]);
			    	$objPHPExcel->getActiveSheet()->setCellValue("J".$i, $row["tiempo"]-16200);
			    	$objPHPExcel->getActiveSheet()->setCellValue("K".$i, gmdate("d-m-Y H:i:s", $row["tiempo"]-16200));
			    	$i++;
		    	}else{
		    		if(!is_nan($distanciaServer) && $distanciaServer != 0 ){
		    			$objPHPExcel->getActiveSheet()->SetCellValue("A".$i, $row["latitud"] );
				    	$objPHPExcel->getActiveSheet()->SetCellValue("B".$i, $row["longitud"]);
				    	$objPHPExcel->getActiveSheet()->SetCellValue("C".$i, $altitud);
				    	$objPHPExcel->getActiveSheet()->setCellValue("D".$i, $precision);
				    	$objPHPExcel->getActiveSheet()->SetCellValue("E".$i, $row["distancia"]);
				    	$objPHPExcel->getActiveSheet()->SetCellValue("F".$i, $distanciaServer);
				    	$objPHPExcel->getActiveSheet()->setCellValue("G".$i, $row["velocidad"]);
				    	$objPHPExcel->getActiveSheet()->SetCellValue("H".$i, $row["direccion"]);
				    	$objPHPExcel->getActiveSheet()->setCellValue("I".$i, $row["tiempoCaptura"]);
				    	$objPHPExcel->getActiveSheet()->setCellValue("J".$i, $row["tiempo"]-16200);
				    	$objPHPExcel->getActiveSheet()->setCellValue("K".$i, gmdate("d-m-Y H:i:s", $row["tiempo"]-16200));
				    	$i++;

		    		}
		    	}
			}

			//Titulo del libro y seguridad 
			$objPHPExcel->getActiveSheet()->setTitle('Data App');
			$objPHPExcel->getSecurity()->setLockWindows(true); 
			$objPHPExcel->getSecurity()->setLockStructure(true);
			 
			 
			// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="reporteApp.xlsx"');
			header('Cache-Control: max-age=0');
			 
			//Creamos el Archivo .xlsx
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			return true;
			break;

		case "tiempoCaptura":
			$data["tiempoCaptura"] = $dataGps->diferenteDispositivo(array("distinct" => "dataGps", "key" => "tiempoCaptura", "query" =>array("dispositivo"=>$dispositivo)));
		break;
	}

	

	}catch(Exception $e){
		return print($e->getMessage());
	}
	
	echo json_encode($data);
?>
