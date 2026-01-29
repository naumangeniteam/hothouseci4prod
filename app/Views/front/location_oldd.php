<div class="container">
   <?php
      //print_r($event_location_tbl['0']['id']);die();
      if($event_location_tbl[0]['id'] !=""){  
         // echo $event_location_tbl[0]['id'];
      //echo'<pre>';
     //print_r($event_location_tbl);die;
      ?>   
   <article class="article">
      <div id="content_box" >
         <div id="content" class="hfeed">
            <div id="post-107" class="post-107 page type-page status-publish hentry g post">
               <h3 style="margin-top: 5%;margin-bottom: 4%;padding: 10px;" class="default-heading">Venue Search for <?=$venue_tbl[0]['venue_title']?></h3>
               <div class="post-content box mark-links">
                   <?php
                   $src  = "//maps.google.com/maps?q=".$venue_tbl[0]['latitude'].','.$venue_tbl[0]['longitude']."&z=15&output=embed";
                   ?>
                  <div class='prettyMapList above'>
                      <iframe src="<?=$src;?>" style="width:100%;height:450px;"></iframe >
                     <!--The Map -->
                     <!--<div id='' class='mapHolder'>
                        <?php  echo $map['js']; ?>
                        <?php  echo $map['html']; ?>
                     </div>-->
                     <br><br><br><br>
                     <!-- hidden div that gets bound -->
                     <div></div>
                     <div id='ListContainer'>
                        <!-- Search, Filters, Sorting bar -->
                        <div class='prettyFileBar clearfix'>
                           <!-- Category button -->
                           <button id="toggleButton1" class='showSortingBtn float_right corePrettyStyle sortAsc btn'><img src="img/arrow_up_red.png">Sort</button>
                           <ul id="list" style="display: none;" class="prettyFileSorting dropDownList unstyled ">
                              <li style="height: 26px;
                                 font-size: 17px;
                                 margin-top: 13px;
                                 margin-left: 9px;color: #D54E21;" onclick="myFunction()">Title<img src="img/arrow_up_red.png" id="img2" style="margin-left: 77px;margin-top: -15px;"><img src="img/arrow_down_red.png" id="img1" style="margin-left: 77px;margin-top: -12px;display:none"></li>
                              <li style="font-size: 17px;
                                 margin-left: 10px;
                                 height: 27px;
                                 margin-top: 10px;color: #D54E21;" onclick="myFunction1()">Distance<img src="img/arrow_up_red.png" id="dimg2" style="margin-left: 78px;margin-top: -12px;" ><img src="img/arrow_down_red.png" style="margin-left: 77px;margin-top: -10px;display:none" id="dimg1"></li>
                           </ul>
                           <div class='prettyFileSorting dropDownList'>
                           </div>
                        </div>
                       <ul class="" id="myDIV1" style="display:block;">
                           <?php
                              foreach($event_location_tbl as $key => $item):
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
<?php   }else{ ?>
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
<?php 
if(trim($mapLocation_All) !=''){ ?>
<script type='text/javascript'>var maplistScriptParamsKo = <?php echo $mapLocation_All ?>;</script>
<?php } ?>
<script type='text/javascript' src='js/MapListPro/maplistfront.js?ver=3.6.3'></script> 
<script type="text/javascript">
(function() {
var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
po.src = 'https://apis.google.com/js/plusone.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
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
