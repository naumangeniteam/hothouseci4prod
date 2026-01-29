
<?php
/*$markers = array(
    // your example['Palace of Westminster, London', 51.499633,-0.124755]
    array(
        'Palace of Westminster, London',
        51.499633,
        -0.124755
    ),
    array(
        'Westminster Abbey, London',
        51.4992453,
        -0.1272561
    ),
    array(
        'QEII Centre, London',
        51.4997296,
        -0.128683
    ),
    array(
        'Winston Churchill Statue, London',
        51.5004308,
        -0.1275243
    ),
    array(
        'Fitzroy Lodge Amature Boxing Club, London',
        51.4954215,
        -0.1154758
    ),
    array(
        'Balham Boxing Club, London',
        51.4419539,
        -0.1336075
    )
);*/

// use this for your code


$markers = array();

foreach( $event_location_tbl1 as $rs_location ) {
    $markers[] = array(
        $rs_location['location_name'],
        floatval($rs_location['latitude']),
        floatval($rs_location['longitude'])
    );

}

?>

<style>

    #map_wrapper {
        height: 400px;
    }

    #map_canvas {
        width: 100%;
        height: 60vh;
    }


</style>
<div class="container">
   <?php
      if(count($event_location_tbl1) >0){  
         
      ?>   
   <article class="article">
      <div id="content_box" >
         <div id="content" class="hfeed">
            <div id="post-107" class="post-107 page type-page status-publish hentry g post">
               <h3 style="margin-top: 5%;margin-bottom: 4%;padding: 10px;" class="default-heading">Venue Search for <?php echo $Get_id; ?></h3>
               <div class="post-content box mark-links">
                   <?php
                   $src  = "//maps.google.com/maps?q=".$venue_tbl[0]['latitude'].','.$venue_tbl[0]['longitude']."&z=15&output=embed";
                   ?>
                  <div class='prettyMapList above'>
                     <div id="map_wrapper">
                        <div id="map_canvas" class="mapping"></div>
                     </div>
                    
                       <ul class="" id="myDIV1" style="display:block;">
                           <?php
                              foreach($event_location_tbl1 as $key => $item):
                              ?>
                           <li class="corePrettyStyle prettylink map location loc-1365 queens">
                              <img src="img/map.png">
                              <?php  echo '  <span class="toggleButton" data-id="' . $item['id'] . '" >'?><?=$item['location_name']?></span><span ></span>
                              <?php echo ' <div class="mapLocationDetail clearfix " id="element_' . $item['id'] . '"  style="display:none;" >'?>
                              <div class="mapDescription clearfix">
                                 <div class="description float_left">
                                    <div class="address">
                                       <p><?=$item['location_address']?></p>
                                    </div>
                                 </div>
                              </div>
                              <a href="https://www.jazzthursdayslive.com/" class="viewLocationPage btn corePrettyStyle"  target="_blank">View location detail</a>
                              <div class="getDirections">
                                 Get directions from
                                 <input class="directionsPostcode" type="text" value="" size="10">
                                 <a href="" class="getdirections btn corePrettyStyle">Go</a>
                                 <img src="img/target.png">
                                 <div class="mapLocationDirectionsHolder"></div>
                              </div>
                     </div>
                     </li>
                     <?php endforeach;  ?>
                     </ul>



                     <ul class="" id="myDIV" style="display:none;">
                           <?php
                              foreach($event_location_tbl1 as $key => $item):
                              ?>
                           <li class="corePrettyStyle prettylink map location loc-1365 queens">
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
                              <a href="https://www.jazzthursdayslive.com/" class="viewLocationPage btn corePrettyStyle"  target="_blank">View location detail</a>
                              <div class="getDirections">
                                 Get directions from
                                 <input class="directionsPostcode" type="text" value="" size="10">
                                 <a href="" class="getdirections btn corePrettyStyle">Go</a>
                                 <img src="img/target.png">
                                 <div class="mapLocationDirectionsHolder"></div>
                              </div>
                     </div>
                     </li>
                     <?php endforeach;  ?>
                     </ul>

                     <div class="blog-pagination">
                        <div class="">
                           <?php echo $links; ?>
                        </div>
                     </div>
                     <style>
                        .homeblogdata {max-height:500Px; overflow:hidden}
                        .homeblogdata div {display:inline-block !important;}
                        .blogdata img{max-height:200px;width:auto !important ;}
                        .pagei
                        {
                        position: relative;
                        margin-left: 641px;
                        color: #007bff;
                        background-color: #fff;
                        margin-top: -20px;
                        }
                        .blog-pagination {
                        margin-bottom: 50px;
                        }
                        .blog-pagination li:first-child .page-link, .blog-pagination li:last-child .page-link {
                        background: #e62229;
                        color: #fff;
                        }
                        .pagination{
                        -webkit-box-pack: center!important;
                        -ms-flex-pack: center!important;
                        justify-content: center!important;
                        }
                     </style>
                  </div>
               </div>
            </div>
         </div>
      </div>
</div>
</div>
</article>
<?php  }else{ ?>
<article class="article">
   <h3 style="margin-top: 5%;margin-bottom: 4%;padding: 10px;" class="default-heading">Venue Search for <?php echo $Get_id; ?></h3>
   <p style="color: red;
      margin-bottom: 219px;
      margin-left: 145px;">No Venue found. Did you enter wrong spelling of any word? Only venue names are accepted in search field.   </p>
</article>
<?php  }  ?>
</div>
<link rel="stylesheet" href="css/style1.css">
<div class="subscribe-section">
   <div class="container">
      <div class="row">
         <div class="col-lg-5 col-md-5 col-sm-5 col-12">
            <h3 class="default-heading">Subscribe To Our <span> Newsletter</span></h3>
            <p>Subscribe to our newsletter for daily updates.</p>
         </div>
         <div class="col-lg-7 col-md-7 col-sm-7 col-12">
            <?php
               if ($this->session->getFlashdata('alert_success')) { ?>
            <span class="alert alert-success" style="width:100%;text-align: center;
               font-family: 'Oswald', sans-serif;
               font-weight: 500;
               font-style: italic;
               font-size: 25px;
               color: green;
               "><?=$this->session->getFlashdata('alert_success')?></span>
            <?php  }else{
               ?>
            <span class="alert alert-error" style="width:100%;text-align: center;
               font-family: 'Oswald', sans-serif;
               font-weight: 500;
               font-style: italic;
               font-size: 25px;
               color: red;;
               "><?=$this->session->getFlashdata('alert_error')?></span>
            <?php }
               ?>					   
            <form id="form" name="currentPageFormSubadmin" class="subscribe" method="post" action="" enctype="multipart/form-data">
               <input type="hidden" name="<?php echo csrf_token();?>" value="<?php csrf_hash();?>">
               <div class="row">
                  <div class="col-lg-5 col-md-5 col-sm-5 col-12">
                     <input type="text" value="" placeholder="Enter Name" name="name" class="required name form-control" id="mce-FNAME">
                  </div>
                  <div class="col-lg-7 col-md-7 col-sm-7 col-12">
                     <input type="email" value="" placeholder="Enter Email Address" name="email" class="required email form-control" id="mce-EMAIL">
                     <input type="hidden" name="Savesubsc" id="Savesubsc" value="Yes">  
                     <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
                  </div>
                  <div id="mce-responses" class="clear">
                     <div class="response" id="mce-error-response" style="display:none"></div>
                     <div class="response" id="mce-success-response" style="display:none;color: green;text-align: center;margin-left: 189px;margin-top: 14px;"></div>
                  </div>
               </div>
            </form>
         </div>
         <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
      </div>
   </div>
</div>
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
if(trim($mapLocation_All) !=''){ ?>
<script type='text/javascript'>var maplistScriptParamsKo = <?php echo $mapLocation_All ?>;</script>
<?php } ?><script type="text/javascript">
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
