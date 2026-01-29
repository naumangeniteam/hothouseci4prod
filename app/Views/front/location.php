
<?php
$markers = array();
if(isset($event_location_tbl1))
{
foreach( $event_location_tbl1 as $rs_location ) {
      $markers[] = array(
      $rs_location['location_name'],
      floatval($rs_location['latitude']),
      floatval($rs_location['longitude'])
   );
}
}
?>

<?php if(isset($event_location_tbl1) && count($event_location_tbl1) >0){ ?>
   <div class="container-fluid hhj-venue-map">
      <div id="map_wrapper"><div id="map_canvas" class="mapping"></div></div> 
   </div>

   <div class="container">
      <h2 class="venue-name">Venue Search for: <?php echo $Get_id; ?></h2>
   </div>

   <div class="hhj-venue-list">
      <div class="container">
         <div class="venue-locations" id='ListContainer'>
            <ul class="list-block" id="myDIV1" style="display:block;">
               <?php
                  foreach($event_location_tbl1 as $key => $item):
               ?>
                  <li>
                     <div class="map-pin"><img src="<?= esc($ASSET_URL) ?>front/icons/location-pin.svg"></div>
                     <?php  echo '  <span class="toggleButton" data-id="' . $item['id'] . '" >'?><?=$item['location_name']?> <img src="<?= esc($ASSET_URL) ?>front/icons/down-arrow.svg"></span><span></span>
                     <?php echo ' <div class="mapLocationDetail clearfix " id="element_' . $item['id'] . '"  style="display:none;" >'?>

                     <div class="location-details">
                        <p><?=$item['location_address']?></p>
                        <a href="<?=$item['website']?>" class="viewLocationPage btn corePrettyStyle"  target="_blank">View location detail</a>
                     </div>

                     <div class="getDirections">
                        <input class="directionsPostcode" type="text" placeholder="Get directions from" value="" size="10">
                        <a href="" class="getdirections btn corePrettyStyle">Go</a>
                        <img src="<?= esc($ASSET_URL) ?>front/img/target.png">
                        <div class="mapLocationDirectionsHolder"></div>
                     </div>
                  </li>
               <?php endforeach;  ?>
            </ul>

            <ul class="list-search-block" id="myDIV" style="display:none;">
               <?php
                  foreach($event_location_tbl1 as $key => $item):
               ?>
                  <li>
                     <img src="img/map.png">
                     <?php echo '  <span class="toggleButton12" data-id="' . $item['id'] . '" >'?><?=$item['location_name']?></span><span ></span>
                     <?php echo ' <div class="mapLocationDetail clearfix " id="element1_' . $item['id'] . '"  style="display:none;" >'?>
                     <div class="mapDescription clearfix">
                        <div class="description float_left">
                           <div class="address">
                              <p><?=$item['location_address']?></p>
                           </div>
                        </div>
                     </div>
                     <a href="<?=$item['website']?>" class="viewLocationPage btn corePrettyStyle"  target="_blank">View location detail</a>
                     <div class="getDirections">
                        Get directions from
                        <input class="directionsPostcode" type="text" value="" size="10">
                        <a href="" class="getdirections btn corePrettyStyle">Go</a>
                        <img src="img/target.png">
                        <div class="mapLocationDirectionsHolder"></div>
                     </div>
                  </li>
               <?php endforeach;  ?>
            </ul>
            <div class="blog-pagination">
               <div class=""><?php //echo $links; ?></div>
            </div>
         </div>
      </div>
   </div>
   <?php }else{ ?>
   
   <div class="venue-not-found">
      <h3>Venue Search for <?php echo $Get_id; ?></h3>
      <p>No Venue found. Did you enter wrong spelling of any word? Only venue names are accepted in search field.   </p>
   </div>
<?php }  ?>

<link rel='stylesheet' id='maplistCoreStyleSheets-css'  href='css/MapListProCore.css' type='text/css' media='all' />
<link rel='stylesheet' id='maplistStyleSheets-css'  href='css/Grey_shiny.css' type='text/css' media='all' />

<link rel='stylesheet' id='maplistStyleSheets-css'  href='styles/MapListPro/styles/Grey_shiny.css' type='text/css' media='all' />
<script type="text/javascript" src="js/eventon_init_gmap.js"></script> 
<script type='text/javascript' src='js/MapListPro/knockout-3.0.0.js?ver=4.0'></script> 
<script type='text/javascript' src='js/MapListPro/markerclusterer_compiled.js?ver=4.0'></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type='text/javascript' src='js/MapListPro/maplistfront.js?ver=3.6.3'></script> 
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB9bu_dYx-Yzl6mwUxsKYSSq_p1yHJO6H8&libraries=places" async defer charset="utf-8"></script>

<?php 
// if(trim($mapLocation_All) !=''){ 
?>
<script type='text/javascript'>var maplistScriptParamsKo = <?php //echo $mapLocation_All ?>;</script>
<?php 
// }
 ?><script type="text/javascript">
(function() {
var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
po.src = 'https://apis.google.com/js/plusone.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script>


<script>

	function initialize() {
  var map;
  var bounds = new google.maps.LatLngBounds();
  var mapOptions = {
    mapTypeId: "roadmap",
    center: new google.maps.LatLng(51.5074, -0.1278),
  };

  // Display a map on the page
  map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

  var markers = <?php echo json_encode($markers); ?>;
  console.log('marker value ========', markers);

  // Info Window Content
  var infoWindowContent = [
    ['<div class="info_content">' +
     '<h3>London Eye</h3>' +
     '<p>The London Eye is a giant Ferris wheel situated on the banks of the River Thames in London, England.</p>' +
     '</div>'],
    ['<div class="info_content">' +
     '<h3>Palace of Westminster</h3>' +
     '<p>The Palace of Westminster is the meeting place of the House of Commons and the House of Lords, the two houses of the Parliament of the United Kingdom.</p>' +
     '</div>'],
    ['<div class="info_content">' +
     '<h3>Tower Bridge</h3>' +
     '<p>Tower Bridge is a combined bascule and suspension bridge in London built between 1886 and 1894.</p>' +
     '</div>'],
    // ...
  ];

  // Display multiple markers on a map
  var infoWindow = new google.maps.InfoWindow();
  var marker, i;

  // Loop through our array of markers
  for (i = 0; i < markers.length; i++) {
    var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
    bounds.extend(position);
    marker = new google.maps.Marker({
      position: position,
      map: map,
      title: markers[i][0],
    });

    // Allow each marker to have an info window and zoom on click
    google.maps.event.addListener(marker, "click", (function(marker, i) {
      return function() {
        infoWindow.setContent(infoWindowContent[i][0]);
        infoWindow.open(map, marker);
        map.setCenter(marker.getPosition());
        map.setZoom(15);
      };
    })(marker, i));
  }

  // Automatically center the map fitting all markers on the screen
  map.fitBounds(bounds);
}

// Load the map on window load
window.addEventListener('load', initialize);


</script>
<script>
   var toggleButtons = document.getElementsByClassName("toggleButton");
   
   // Add click event listener to each toggle button
   for (var i = 0; i < toggleButtons.length; i++) {
   toggleButtons[i].addEventListener("click", function(){
    var id = this.getAttribute("data-id");
    var element = document.getElementById("element_" + id);
    if (element.style.display === "none") {
      element.style.display = "block";
    } else {
      element.style.display = "none";
    }
   });
   }
   var toggleButtons12 = document.getElementsByClassName("toggleButton12");
   
   // Add click event listener to each toggle button
   for (var i = 0; i < toggleButtons12.length; i++) {
   toggleButtons12[i].addEventListener("click", function(){
    var id = this.getAttribute("data-id");
    var element = document.getElementById("element1_" + id);
    if (element.style.display === "none") {
      element.style.display = "block";
    } else {
      element.style.display = "none";
    }
   });
   }
   var toggleButton1 = document.getElementById("toggleButton1");
   var list = document.getElementById("list");
   
   toggleButton1.addEventListener("click", function() {
   if (list.style.display === "none") {
    list.style.display = "block";
   } else {
    list.style.display = "none";
   }
   });


   function myFunction() {
  var x = document.getElementById('myDIV');
  var img1 = document.getElementById('img1');
  var img2 = document.getElementById('img2');
  var y = document.getElementById('myDIV1');
  if (x.style.display === 'none' && y.style.display === 'block') {
    x.style.display = 'block';
    img1.style.display = 'block';
    img2.style.display = 'none';
    y.style.display = 'none';
  } else {
    y.style.display = 'block';
    x.style.display = 'none';
    img2.style.display = 'block';
    img1.style.display = 'none';
  }
}
function myFunction1() {
  var x = document.getElementById('myDIV');
  var y = document.getElementById('myDIV1');
  var dimg1 = document.getElementById('dimg1');
  var dimg2 = document.getElementById('dimg2');
  if (x.style.display === 'none' && y.style.display === 'block') {
    x.style.display = 'block';
    y.style.display = 'none';
    dimg1.style.display = 'block';
    dimg2.style.display = 'none';
  } else {
    y.style.display = 'block';
    x.style.display = 'none';
    dimg2.style.display = 'block';
    dimg1.style.display = 'none';
  }
}
</script>
