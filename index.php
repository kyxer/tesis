<?php 
  require_once "ActiveMongo/lib/ActiveMongo.php";
  require_once "dataGps.class.php";
  ActiveMongo::connect("tesis", "localhost");
  $registro = new dataGps();
  $diff = $registro->diferenteDispositivo(array("distinct" => "dataGps", "key" => "dispositivo"));
?>
<!DOCTYPE html>
<html style="color: #04819E; font-family:'Arial', Verdana, sans-serif">
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 500px }
    </style>
    <script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyB625fM8VwIPW6tTcyXJ5tN0SGiY7GL1r4&sensor=false&call">
    </script>
  </head>
  <body>
    <div id="map_canvas" style="width:100%; height:500px">
        <h1 style="text-align: center;">ELIJA LOS DATOS A GRAFICAR</h1>
    </div>
    <div style="height:200px; width:100%; float:left; text-align:center;">
      <p>
        <h1>Data para graficar --- <a style="color:black" href="descargar.php">Descargar DATA</a> </h1>

        <p>
          <label style="color: #FF9F40">Numero de puntos: </label><input id="puntos" type="text" />
        </p>
        <p>
        <label>Indica el numero de marcas que aparecera en el mapa, <span style="font-weight: bold; color: #A65200">seran graficados los mas recientes capturados</span></label>
        </p>
        <p>
          <label style="color: #FF9F40">Inicio de los puntos: </label><input value="0" id="pagina" type="text" />
        </p>
        <p>
        <label>Indica el a partir de que pagina de la base de datos se graficaran</label>
        </p>
        <p><label style="color: #FF9F40">Dispositivo: </label><select id="dispositivo" name="dispositivo">
          <option value="">Seleccione</option>
          <?php 
            foreach ($diff as $value) {

              echo "<option value='$value' >$value</option>";
            }
          ?>
        </select>
        <label>-----</label>
        <label style="color: #FF9F40">Tiempo de captura: <label id="contTiempoCaptura"></label></label>
        <div ><label style="color: #FF9F40">Total de puntos existentes:</label><label id="tPuntos" style="color: black; margin-left:20px"></label></div>
      </p>
        <p><label>Indica el telefono o equipo que capturo los puntos</label></p>
        <p>
          <input id="graficar" style="font-size:18px;" value = "Graficar" type="button" />
        </p>
    </div>
  </body>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.2.min.js"><\/script>')</script>
  <script src="js/plugins.js"></script>
  <script src="js/main.js"></script>
  <script>
  function initialize(data) {
      console.log(arguments);
      var markers = [];
      var mapOptions = {
        center: new google.maps.LatLng(data[0].latitud, data[0].longitud),
        zoom: 8,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };
  var map = new google.maps.Map(document.getElementById("map_canvas"),
      mapOptions);


    for(var i = 0; i<data.length; i++){

      current = new google.maps.LatLng(data[i].latitud, data[i].longitud);
      myloc = "<span style='font-weight:bold;color:black;'>Indice: </span>"+i
      +"<br><span style='font-weight:bold;color:black;'> Velocidad: </span>"
      +data[i].velocidad+"<br><span style='font-weight:bold;color:black;'> DistanciaApp: </span> "
      + data[i].distancia+"<br><span style='font-weight:bold;color:black;'> DistanciaServer: </span>"
      + data[i].distanciaServer+"<br>"
      +"<span style='font-weight:bold;color:black;'> Direccion: </span> "
      +data[i].direccion+"<br><span style='font-weight:bold;color:black;'> Lat:</span> "+data[i].latitud+"<br>"+
      "<span style='font-weight:bold;color:black;'>Lon:</span> "+data[i].longitud+"<br>"+
      "<span style='font-weight:bold;color:black;'> Pres:</span> "+ data[i].precision;
      markers.push(new google.maps.Marker({
          map: map,
          position: current,
          title: myloc
      }));
      markers[i]['infowin'] = new google.maps.InfoWindow({
        content: '<div>Esta marca es ' + myloc + '</div>'
      });

      google.maps.event.addListener(markers[i], 'click', function() {
        this['infowin'].open(map, this);
    });

    }
  }


    $("#dispositivo").on("change", function(){
      var val = $(this).val();
      if(val != ""){
        $.ajax({ url: "show.php", type: "GET", 
          data:{dispositivo:val, section:"tiempoCaptura"}, 
          dataType: "json", 
          success: function(data){
            var $sel = $("#contTiempoCaptura"),
            html = "<select id='tiempoCaptura'>"
            $.each(data["tiempoCaptura"], function(key, val){
              html +="<option value="+val+">"+val+"</option>";
            });
            $sel.html(html);
            findTotal(val, data["tiempoCaptura"][0]);
          }
        });
      }
      
    });

    $("#tiempoCaptura").live("change",function(){
          var dispositivo = $("#dispositivo").val(), tiempoCaptura = $(this).val();
          findTotal(dispositivo, tiempoCaptura);
      });

    var findTotal = function (dispositivo, tiempoCaptura) {
        $.ajax({ url: "show.php", type: "GET", data:{tiempoCaptura:tiempoCaptura ,dispositivo:dispositivo, section:"count"}, dataType: "json", success: function(data){
                
                $("#tPuntos").html(data.count);
                __CANTIDADPUNTOS = data.count;
              },
              error: function(data){
                  console.log(arguments);
              }});
      }


    $(document).ready(function(){
        $("#graficar").on("click", function(){
          var dispositivo, puntos, tiempoCaptura;
          tiempoCaptura = $("#tiempoCaptura").val();
          dispositivo = $("#dispositivo").val();
          puntos = $("#puntos").val();
          pagina = $("#pagina").val();
          if(dispositivo == ""){
            alert("Debe seleccionar un dispositivo");
          }else{
            if(!$.isNumeric(puntos)){
              alert("Puntos debe ser un numero");
            }else{
              if(!$.isNumeric(pagina)){ 
                alert("Pagina debe ser un numero");
              }else{
                if(puntos < 0) 
                puntos *= -1;
                puntos|= 0;
                if(pagina < 0) 
                    pagina*= -1;
                    pagina|= 0;

                //$("body").append("<div style='position:absolute; background:white; opacity:.8; width:100%: height:100%;' id='bg'><h1>Cargando</h1><</div>");
                $.ajax({ url: "show.php", type: "GET", data:{tiempoCaptura: tiempoCaptura, puntos:puntos, pagina: pagina,dispositivo:dispositivo, section:"index"}, dataType: "json", success: function(data){
                  initialize(data);
                  //$("#bg").remove();
                },
                error: function(data){
                  //$("#bg").remove();
                    console.log(arguments);
                }});

              }
            }
          }
          


        });
          
     

    });
  </script>

</html>
