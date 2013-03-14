<!DOCTYPE html>
<html>
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
    <div id="map_canvas" style="width:100%; height:500px"></div>
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
      myloc = "Lat "+data[i].latitud+" Lon "+data[i].longitud;
      markers.push(new google.maps.Marker({
          map: map,
          position: current,
          title: myloc
      }));
      markers[i]['infowin'] = new google.maps.InfoWindow({
        content: '<div>This is a marker in ' + myloc + '</div>'
      });

      google.maps.event.addListener(markers[i], 'click', function() {
        this['infowin'].open(map, this);
    });

    }
  }
    $(document).ready(function(){
        var DATA;
        $.ajax({ url: "show.php", type: "GET", dataType: "json", success: function(data){
                    DATA = data;
                    initialize(data)
                  },
                  error: function(data){
                      console.log(arguments);
                  }
          
              });  
     

    });
  </script>

</html>
