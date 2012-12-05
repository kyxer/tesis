<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        <h1>Datos</h1>
        <p>
        	<label>Latitude</label>:
        	<input type="text" id="latitude"/>
        </p>
        <p>
        	<label>Longitude</label>:
        	<input type="text" id="longitude" />
        </p>
        <p>
        	<label>Altitude</label>:
        	<input type="text" id="altitude" />
        </p>
        <p>
        	<label>Accuracy</label>:
        	<input type="text" id="accuracy" />
        </p>
        <p>
        	<label>Altitude Accuracy</label>:
        	<input type="text" id="altitudeAccuracy" />
        </p>
        <p>
        	<label>Heading</label>:
        	<input type="text" id="heading" />
        </p>
        <p>
        	<label>Speed</label>:
        	<input type="text" id="speed" />
        </p>
        <p>
        	<button type="button" id="enviar">Enviar</button>
        </p>


        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.2.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        <script>
        	$(document).ready(function(){

        		$("#enviar").on("click", function(){
        			$.ajax({
                        url: "server.php",
                        type: "POST",
                        dataType: "json",
                        data: { 
                        	"data":{
                        		0:{
	                        		latitude: $("#latitude").val(),
	                        		longitude: $("#longitude").val(),
	                        		altitude: $("#altitude").val(),
	                        		accuracy: $("#accuracy").val(),
	                        		altitudeAccuracy: $("#altitudeAccuracy").val(),
	                        		heading: $("#heading").val(),
	                        		speed: $("#speed").val()
                        		}
                        	}
                        }
                        ,
                        success: function(data){
                                                  
                         	alert("exito");
                        },
                        error: function(data){
                            console.log('error');
                        }
                
                    });  
        		});

        	});
        </script>
    </body>
</html>
