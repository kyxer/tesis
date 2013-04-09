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
	if(isset($_GET["limiteInferior"]))
		$limiteInferior = $_GET["limiteInferior"];
	if(isset($_GET["cantidadPuntos"]))
		$cantidadPuntos = $_GET["cantidadPuntos"];
	if(isset($_GET["section"]))
		$section = $_GET["section"];


	try{
	ActiveMongo::connect(MONGODBNAME, MONGODBSERVER);
	$dataGps = new dataGps();

	switch ($section) {
		case "index":
			$data = $dataGps->listData($pageIndex,$pageSize,$dispositivo);
			break;
		
		case "count":
			require_once("historial.class.php");
			$data["count"] = $dataGps->totalDispositivo($dispositivo);
			$historial = new historial();
			$data["historial"] = $historial->listData($dispositivo);
			break;

		case "descarga":
			require_once("excel/PHPExcel.php");
			require_once("historial.class.php");
			require_once("excel/PHPExcel/Writer/Excel2007.php");
			$data = $dataGps->listDataRange($limiteInferior,$cantidadPuntos,$dispositivo);
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
		    $objPHPExcel->getActiveSheet()->setCellValue("C1", "Precision");
		    $objPHPExcel->getActiveSheet()->setCellValue("D1", "Tiempo");
		    $i = 2;
			foreach($data as $row){
				$objPHPExcel->getActiveSheet()->SetCellValue("A".$i, $row["latitud"] );
		    	$objPHPExcel->getActiveSheet()->SetCellValue("B".$i, $row["longitud"]);
		    	if(isset($row["precision"]))
		    		$precision = $row["precision"];
		    	else
		    		$precision = $row["presicion"];

		    	$objPHPExcel->getActiveSheet()->setCellValue("C".$i, $precision);
		    	$objPHPExcel->getActiveSheet()->setCellValue("D".$i, $row["tiempo"]);
		    	$i++;
			}

			//Titulo del libro y seguridad 
			$objPHPExcel->getActiveSheet()->setTitle('DataApp');
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
	}

	

	}catch(Exception $e){
		return print($e->getMessage());
	}
	
	echo json_encode($data);
?>
