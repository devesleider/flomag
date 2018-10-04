<?php defined('_JEXEC') or die;?>
<div id="map_ruta">
  


</div>
  <script>
function initMap() {
  var directionsService = new google.maps.DirectionsService;
  var directionsDisplay = new google.maps.DirectionsRenderer;
  var map = new google.maps.Map(document.getElementById('map_ruta'), {
    zoom: 6,
    center: {lat: 41.85, lng: -87.65}
  });
  directionsDisplay.setMap(map);
  directionsDisplay.setOptions( { suppressMarkers: true } );

 // document.getElementById('submit').addEventListener('click', function() {
    calculateAndDisplayRoute(directionsService, directionsDisplay,map);
 // });
}

function calculateAndDisplayRoute(directionsService, directionsDisplay, map1) {
  var waypts = [];
  var puntos = [];
  <?php 
      $puntos = explode(",",$this->item->paradas);
      $i=0;
      foreach($puntos as $punto):
        if($i>=9) continue; 
  ?>
        waypts.push({
          location: "<?php echo $punto;?>, Colombia",
          stopover: true
        });
        puntos[<?php echo $i?>] = "<?php echo $punto;?>";
<?php $i++;endforeach;?>
puntos[-1] = "<?php echo $this->item->flota_municipios_municipio_2139145;?>";
  
 /* var checkboxArray = document.getElementById('waypoints');
  for (var i = 0; i < checkboxArray.length; i++) {
    if (checkboxArray.options[i].selected) {
      waypts.push({
        location: checkboxArray[i].value,
        stopover: true
      });
    }
  }*/

  directionsService.route({
    origin: "<?php echo $this->item->flota_municipios_municipio_2139145;?>, Colombia",//document.getElementById('origen').value,
    destination: "<?php echo $this->item->flota_municipios_municipio_2139146;?>, Colombia",//document.getElementById('destino').value,
    waypoints: waypts,
    optimizeWaypoints: true,
    travelMode: google.maps.TravelMode.DRIVING
  }, function(response, status) {
    if (status === google.maps.DirectionsStatus.OK) {
      var infowindow = new google.maps.InfoWindow();
      directionsDisplay.setDirections(response);
      var route = response.routes[0];
      var route1 = response.routes[0];
      var lat_start = route.legs[0].start_location;
      var len = route.legs.length-1;
      var lat_end = route.legs[len].end_location;
      var ruta = 0, duracion=0 ;
       for (var j = 0; j< route.legs.length; j++) {
          ruta = ruta + route.legs[j].distance.value;
          duracion = duracion + route.legs[j].duration.value;
       }   
      var marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat_end.lat(), lat_end.lng()),
            map: map1,
            icon: 'images/icono-mapa-bus.png',
            title: "<?php echo $this->item->flota_municipios_municipio_2139145;?>, Colombia"
        });
      google.maps.event.addListener(marker, 'click', (function(marker) {
                  return function() {
                    infowindow.setContent('<?php echo $this->item->flota_municipios_municipio_2139146;?>');
                    infowindow.open(map1, marker);
                  }
                })(marker)); 
      var panel_distancia = document.getElementById('distancia');
      var panel_tiempo = document.getElementById('tiempo');
      panel_distancia.innerHTML = (ruta/1000).toFixed(1)+' km';
      panel_tiempo.innerHTML = (duracion/60/60).toFixed(1)+' h';
      // For each route, display summary information.
      for (var i = 0; i < route.legs.length; i++) {
        var routeSegment = i + 1;
        var lat_mid = route.legs[i].start_location;
          var marker = new google.maps.Marker({
              position: new google.maps.LatLng(lat_mid.lat(), lat_mid.lng()),
              map: map1,
              icon: 'images/icono-mapa-bus.png',
              title: "<?php echo $this->item->flota_municipios_municipio_2139146;?>, Colombia"
            });
          google.maps.event.addListener(marker, 'click', (function(marker, i) {
                  return function() {
                    infowindow.setContent(puntos[i-1]);
                    infowindow.open(map1, marker);
                  }
                })(marker, i));
      }

    } else {
      window.alert('Directions request failed due to ' + status);
    }
  });
}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCvx0qnimyEpzZ5UZUSMXe7Iar0ewtrMsc&callback=initMap" async defer></script>

