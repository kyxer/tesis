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
   </head>
   <style type="text/css">
      h1, h2{
        text-align: center;
      }
      table{
        color: black;
      }
    </style>
   <body>

    <div id="container" style="position:relative; left:50%; top:50%; height:auto; width:800px; margin-left:-400px; margin-top:100px;">
        <!--<h1><?php echo PHP_INT_MAX ?></h1> -->
        <h1>Datos a descargar --- <a style="color:black" href="index.php">Grafic Data</a></h1>
        <p><label>Seleccione dispositivo:</label>
          <select id="dispositivo" name="dispositivo">
            <option value="">Seleccione</option>
          <?php 
            foreach ($diff as $value) {
              echo "<option value='$value' >$value</option>";
            }
          ?>
        </select>
        <label>Total de puntos existentes:</label><label id="tPuntos" style="color: black; margin-left:20px">0</label>
        </p>
        <p><label>Limite inferior:</label> <input id="limiteInferior" type="text" /></p>
        <p><label>Cantidad de puntos:</label> <input id="cantidadPuntos" type="text" /></p>
        <p style="text-align:center"><input id="bDescarga" type="button" value="Descargar" /></p>
        <h2>Historial de descarga</h2>
        <div id="containerHistorial">

        </div>
    </div>
   </body>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.2.min.js"><\/script>')</script>
  <script>
    $(document).ready(function(){
      $("#dispositivo").on("change",function(){
        var dispositivo = $(this).val()

        if(dispositivo == ""){
          alert("Recuerde seleccionar un dispositivo");
        }else{
          $.ajax({ url: "show.php", type: "GET", data:{dispositivo:dispositivo, section:"count"}, dataType: "json", success: function(data){
                
                $("#tPuntos").html(data.count);
                __CANTIDADPUNTOS = data.count;
                if(data.historial.length>0){
                  var list="<table><tr><th>Limite inferior</th><th>Cantidad de Puntos</th><th>Dispositivo</th></tr>";
                  $.each(data.historial,function(key, val){
                    list+="<tr><td>"+val.limiteInferior+"</td><td>"+val.cantidadPuntos+"</td><td>"+val.dispositivo+"</td></tr>";
                  });
                  list+="</table>";
                  $("#containerHistorial").html(list);
                }else{
                  $("#containerHistorial").html("<h2 style='color:black'>No hay historial disponible</h2>");
                }
              },
              error: function(data){
                  console.log(arguments);
              }});
        } 

      });

      $("#bDescarga").on("click", function(){
        var dispositivo = $("#dispositivo").val(), 
        limiteInferior = $("#limiteInferior").val(), 
        cantidadPuntos = $("#cantidadPuntos").val(),
        isValid = true;

        if(!$.isNumeric(cantidadPuntos)){
          alert("Cantidad de Puntos debe ser un numero valido");
          isValid = false;

        }else if(cantidadPuntos > __CANTIDADPUNTOS){
          alert("Recuerde que no puede sobrepasar el total de puntos existente: "+__CANTIDADPUNTOS);
          isValid = false;
        }

        if(!$.isNumeric(limiteInferior)){
          alert("Limite Inferior debe ser un numero valido");
          isValid = false;

        }else if(limiteInferior >= __CANTIDADPUNTOS){
          alert("Debe colocar un limite inferior por debajo de "+__CANTIDADPUNTOS);
          isValid = false;
        }

        
        
        if(dispositivo == ""){
          alert("Debe selecionar un dispositivo");
          isValid = false;
        }

        if(isValid){
          if(limiteInferior < 0) 
              limiteInferior *= -1;
          limiteInferior|= 0;
          if(cantidadPuntos < 0) 
            cantidadPuntos*= -1;
          cantidadPuntos|= 0;

          window.location = "http://tesis.codesign.me/show.php?cantidadPuntos="+cantidadPuntos+"&limiteInferior="+limiteInferior+"&dispositivo="+dispositivo+"&section=descarga";

        }



      });

    });
  </script>
</html>
