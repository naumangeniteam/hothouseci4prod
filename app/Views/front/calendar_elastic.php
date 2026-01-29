<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="css/calendar.css" type="text/css" media="all">
<link rel="stylesheet" href="css/style.css">

<div class="signle-banner">
   <div class="full-container">
      <div class="row">
         <?php foreach ($banner as $key => $item) : ?>
            <div class="col-lg-12 col-12">
               <div class="signle-banner">
                  <div class="full-container">
                     <div class="row">
                        <?php foreach ($banner as $key => $item) : ?>
                           <div class="col-lg-12 col-12">
                              <div class="single-banner-img">
                                 <img src="img/banner/<?= $item['image'] ?>" class="img-fluid" alt="About us">
                              </div>
                              <div class="single-banner-caption about-caption">
                                 <div class="container">
                                    <h1 style="color: white;"><?= $item['title'] ?></h1>
                                 </div>
                              </div>
                           </div>
                        <?php endforeach;  ?>
                     </div>
                  </div>
               </div>
            </div>
         <?php endforeach;  ?>

      </div>
   </div>
</div>

<?= view('layouts/front/home-tabs') ?>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>-->
<div class="calendar-section" id="calendar-section">
   <div class="container-fluid">
      <div class="row">

         <div class="col-lg-12 col-md-12 col-sm-12 col-12 calendarmonthdate">
            <div id="heading-carousel" class="owl-carousel">
               <div class="monthsData">
                  <!--<div class="item"><h1 class="default-heading"><?php echo date('F, Y'); ?></h1</div>-->
               </div>
               <div class="owl-nav">
                  <button type="button" role="presentation" class="owl-prev"> <span aria-label="Previous">‹</span> </button>
                  <button type="button" role="presentation" class="owl-next"> <span aria-label="Next">›</span> </button>
               </div>
            </div>
            <div id="day-carousel" class="owl-carousel"> </div>
            <!--<div>Hold the red line and slide it to the right or left and select your date</div>-->
         </div>

         <div class="col-lg-12 col-md-12 col-sm-12 col-12 onclick_Inpputs" style="margin-top: 15px;">

            <div class="sorting_butn">
               <div class="events-alls">
                  <a href="<?= $BASE_URL?>submit_event" class="event-a">
                     <span class='events-btn'>Add Your Event</span>
                  </a>
               </div>
               <!-- <button id="toggleButton1" class='showSortingBtn float_right corePrettyStyle sortAsc btn'>SEARCH OPTIONS</button> -->
            </div>
            <ul id="list" style="display: block;" class="prettyFileSorting dropDownList unstyled form-control neww events-mob c-wid">
               <span id="Span_form" style="height: 26px;
                  font-size: 17px;
                  color: #D54E21;font-family: 'Open Sans', sans-serif;
                  padding: 0;
                  margin: 0;
                  text-transform: uppercase;
                  font-size: 12px;
                  list-style-type: none;  
                  width: 100%;
                  gap: 10px;
                  display: flex;
                  justify-content: space-between;
                  align-items: center;
                  ">
                  <form action="<?php echo base_url('all-products'); ?>" id="event_search_form" method="GET" class="userformes d-flex justify-content-between" style="gap:10px;">
                     <input class="form-control" autocomplete=false placeholder="seach by event name" id="search-box" name="event_title" style="width: 257px;">
                     <div id="suggesstion-box"></div>

                     <input class="form-control tags" placeholder="Search event tags" id="event_tags" name="event_tags" style="width: 165px;">

                     <select class="form-control venues select2" name="venue" id="mySelect" style="width: 171px;">
                        <option value="">Select a location</option>
                        <?php foreach ($location_tbl as $location) : ?>
                           <option value="<?= $location['id']; ?>"><?= $location['venue_title'] ?></option>
                        <?php endforeach; ?>
                     </select>

                     <select class="form-control locs select2" name="location" id="venue_location" style=" width: 163px;">
                        <option value="">Select a venue</option>
                        <?php foreach ($venue_tbl as $venue) : ?>
                           <option value="<?= $venue['id']; ?>"><?= $venue['location_name'] ?></option>
                        <?php endforeach; ?>
                     </select>

                     <select class="form-control jazzed select2" name="jazz" id="jazz" style="width: auto;">
                        <option value="">Select a Jazz</option>
                        <?php foreach ($jazzType as $jType) : ?>
                           <option value="<?= $jType->id ?>"><?= $jType->name ?></option>
                        <?php endforeach; ?>
                     </select>

                     <input type="hidden" id="venue" value="<?= $location['id']; ?>">
                     <input type="hidden" id="artist" value="<?= $artist['id']; ?>">
                     <input type="hidden" id="selDate" value="">
                     <a href="<?= $BASE_URL?>festivals" data-toggle="tooltip" title="" data-placement="top" class="btn btn-danger" title="Festivals">
                        Festivals
                     </a>
                     <!-- <a href="#" id="clearButton"><button class="clear-btn">Clear</button></a> -->
                  </form>
               </span>
            </ul>
         </div>
         <div id="current_display_div" style="display:none;">
            <!-- this di cut from here  -->
         </div>
      </div>
   </div>
   <div class="container-fluid">
      <div class="row">
         <?php
         usort($slider_tbl, function ($a, $b) {
            return $a['order'] <=> $b['order'];
         });
         ?>
         <div class="col-lg-2 col-md-2 col-sm-6 all-ads">
            <span>
               <?php foreach ($slider_tbl as $key => $item) : ?>
                  <?php if ($item['type'] === 'banner' && $item['alignment'] === 'left') : ?>
                     <div class="item item-al-mob item-ra banner-left left-m order-<?= $item['order']; ?>">
                        <a href="<?= $item['weblink'] ?>" target="_blank">
                           <img class="img-fluid img-cal" src="img/slider/<?= $item['image'] ?>" alt="" class="slider-image-al">
                        </a>
                     </div>
                  <?php endif; ?>
               <?php endforeach; ?>
            </span>
         </div>
         <div class="col-lg-8 col-md-8 col-sm-8">
            <div class="row newStylezz newStylezz-mobile" id="html">

            </div>
            <div class="home-history submit-event-section al_home">
               <div class="row">
                  <div class="contain-al">
                     <div id="owl-carousel" class="owl-carousel owl-theme">
                        <?php foreach ($slider_tbl as $key => $item) : ?>
                           <?php if ($item['type'] === null || $item['type'] === 'slider') : ?>
                              <div class="item item-al-mob item-ra">
                                 <a href="<?= $item['weblink'] ?>" target="_blank">
                                    <img class="img-fluid" src="img/slider/<?= $item['image'] ?>" alt="" class="slider-image-al">
                                 </a>
                              </div>
                           <?php endif; ?>
                        <?php endforeach; ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-lg-2 col-md-2 col-sm-6 all-ads">
            <div class="listen-issue listen-issue-text listen-issue-text-mob listen-iss al_issue mt-5">
               <div class="pin-event al-pin-event al-pin-event-mob ra-pin">
                  <div class="wrap">
                     <div class="">
                        <div class="vertical al_listen-issue al_listen-issue-mob ver-al d-none">
                           <?= $home_image[0]['month'] ?>
                        </div>
                     </div>
                     <a href="<?= $home_image[0]['image2_weblink'] ?>" target="_blank">
                        <!-- <img class="img-fluid img1 img1-mob img2-wid al-image-fluid" src="https://www.hothousejazz.com/assets/front/img/homeimage/Hot_Jazz_House.jpg" alt=""></a> -->
                        <img class="img-fluid" src="img/homeimage/<?= $home_image[0]['image2'] ?>" alt=""></a>
                  </div>
               </div>
            </div>
            <span>
               <?php foreach ($slider_tbl as $key => $item) : ?>
                  <?php if ($item['type'] === 'banner' && $item['alignment'] === 'right') : ?>
                     <div class="item item-al-mob item-ra banner-right order-<?= $item['order']; ?>">
                        <a href="<?= $item['weblink'] ?>" target="_blank">
                           <img class="img-fluid right-m" src="img/slider/<?= $item['image'] ?>" alt="" class="slider-image-al">
                        </a>
                     </div>
                  <?php endif; ?>
               <?php endforeach; ?>
            </span>
         </div>
      </div>

   </div>
</div>

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
               "><?= $this->session->getFlashdata('alert_success') ?></span>
            <?php  } else {
            ?>
               <span class="alert alert-error" style="width:100%;text-align: center;
               font-family: 'Oswald', sans-serif;
               font-weight: 500;
               font-style: italic;
               font-size: 25px;
               color: red;;
               "><?= $this->session->getFlashdata('alert_error') ?></span>
            <?php }
            ?>
            <form id="form" name="currentPageFormSubadmin" class="subscribe" method="post" action="" enctype="multipart/form-data">
               <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
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



         <script type='text/javascript' src='https://s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
         <script type='text/javascript'>
            (function($) {
               window.fnames = new Array();
               window.ftypes = new Array();
               fnames[0] = 'EMAIL';
               ftypes[0] = 'email';
               fnames[1] = 'FNAME';
               ftypes[1] = 'text';
               fnames[2] = 'LNAME';
               ftypes[2] = 'text';
            }(jQuery));
            var $mcj = jQuery.noConflict(true);
         </script>
      </div>
   </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js"></script>
<!-- 
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script> -->


<script>
   document.getElementById('event_search_form').addEventListener('submit', function(event) {
      event.preventDefault();
      console.log('Form submission prevented');
   });
</script>
<script>
   //$("#html").hide();

   window.onload = function() {
      setTimeout(function() {
         $("#html").show();
      }, 2000);
      $('#calendar-section').addClass("expand");
      //$("#html").show();
   };
</script>
<!-- <script>
   var toggleButton1 = document.getElementById("toggleButton1");
   var list = document.getElementById("list");

   toggleButton1.addEventListener("click", function() {
      if (list.style.display === "none") {
         list.style.display = "block";
      } else {
         list.style.display = "none";
      }
   });
</script> -->
<script>
   /* TOP Menu Stick
   --------------------- */
   // var s = $("#sticker");
   // var pos = s.position();
   // $(window).scroll(function() {
   //    var windowpos = $(window).scrollTop();
   //    if (windowpos > pos.top) {
   //       s.addClass("stick");
   //    } else {
   //       s.removeClass("stick");
   //    }
   // });
</script>
<!-- Main Slider Js -->
<script>
   jQuery("#heading-carousel").owlCarousel({
      autoplay: false,
      loop: true,
      margin: 0,
      /*
     animateOut: 'fadeOut',
     animateIn: 'fadeIn',
     */
      responsiveClass: true,
      autoHeight: true,
      //autoplayTimeout: 7000,
      smartSpeed: 800,
      nav: true,
      dots: false,
      responsive: {
         0: {
            items: 1,
         },
         600: {
            items: 1,
         },

         1024: {
            items: 1,
         },

         1366: {
            items: 1
         }
      }
   });
</script>
<script>
   jQuery("#day-carousel").owlCarousel({
      autoplay: true,
      loop: true,
      margin: 0,
      /*
     animateOut: 'fadeOut',
     animateIn: 'fadeIn',
     */
      responsiveClass: true,
      autoHeight: true,
      autoplayTimeout: 7000,
      smartSpeed: 800,
      nav: true,
      dots: false,
      responsive: {
         0: {
            items: 5
         },

         600: {
            items: 10
         },

         1024: {
            items: 15
         },

         1366: {
            items: 15
         }
      }
   });
</script>
<script type="text/javascript">
   document.addEventListener("DOMContentLoaded", function(event) {
      //alert('test');
      document.getElementsByClassName('default-heading').innerHTML = "hello";
   });
</script>

<script>
   $(document).ready(function() {
      generateCalender();

   });

   function generateCalender(year, month) {

      // var year = year;
      // var month = month;
      if (year == undefined || year == '') {
         year = "<?php echo date('Y') ?>";
      }
      if (month == undefined || month == '') {
         month = "<?php echo date('m') ?>";

      }
      //alert(month);
      if (year != '' && month != '') {
         var url = "<?php echo base_url('calendar/getdate') ?>";
         // alert('year : '+year+'   month : '+month);
         var data = 'what=calender&year=' + year + '&month=' + month;

         $.ajax({
            type: 'GET',
            dataType: 'json',
            url: url,
            data: data,
            success: function(data) {
               // alert(data.dates);

               $('#day-carousel').html(data.dates);
               $('#heading-carousel .owl-prev').attr("onclick", "generateCalender(" + data.prev + ")");
               $('#heading-carousel .owl-next').attr("onclick", "generateCalender(" + data.next + ")");
               $('#heading-carousel .monthsData').html('<div class="item"><h1 class="default-heading test">' + data.str + '</h1></div>');
               setdatescroller();
               var x = $('.evo_day.on_focus', '#day-carousel')[0];
               $(x).trigger('click');
            }
         });
      } else {
         alert('invalid date');
         return false;
      }
   }

   function setdatescroller() {
      $('.evo_day', '#day-carousel').each(function(index, element) {
         if ($(element).hasClass("on_focus")) {

            var i = Number(index) + 1;

            if (i < 13) {

               $("#day-carousel").css('margin-left', '0px');
            } else if (i >= 13 && i < 16) {

               $("#day-carousel").css('margin-left', '-115px');
            } else if (i >= 16 && i <= 25) {

               $("#day-carousel").css('margin-left', '-430px');
            } else if (i > 25) {

               $("#day-carousel").css('margin-left', '-640px');
            }
            return false;
         }
      });
   }
</script>
<script>
   function change(year, month, date) {
      var start_date = year + '-' + month + '-' + date;
      var today = new Date();

      var selected_date_obj = new Date(start_date.replace(/-/g, "/"));

      if (selected_date_obj < today.setHours(0, 0, 0, 0)) {
         // alert("You cannot select a past date.");
         return;
      }

      var month = '' + (selected_date_obj.getMonth() + 1),
         day = '' + date,
         year = selected_date_obj.getFullYear();

      if (month.length < 2)
         month = '0' + month;
      if (day.length < 2)
         day = '0' + day;

      var selected_date = [year, month, day].join('-');
      var ur = "<?= base_url('search/calendar-filter-artist') ?>";

      $.ajax({
         url: ur,
         method: "POST",
         dataType: 'json',
         data: $('#event_search_form').serialize() + "&Selected_Date_=" + start_date + "&selected_date=" + selected_date,
         success: function(data) {
            //console.log(data);
            $('.selectdate').removeClass('selectdate');
            $("#" + data.selected_date).addClass("selectdate");
            document.getElementById('current_display_div').style.display = 'none';
            document.getElementById(data.selected_date).style.backgroundColor = '';
            $('#html').html(data.data);
            $('#selDate').val(data.selected_date);
         }
      });
   }
</script>



<script>
   const selectElement = document.getElementById("mySelect");
   selectElement.addEventListener("change", function() {
      getartist();
   });

   const selectElement_location = document.getElementById("venue_location");
   selectElement_location.addEventListener("change", function() {
      getartist();
   });

   const selectElement_name = document.getElementById("jazz");
   selectElement_name.addEventListener("change", function() {
      getartist();
   });
   // when serach by event name, description or tags
   $(document).ready(function() {
      $("#search-box").keyup(function() {
         var textVal = $(this).val();
         if (textVal.length == "") {
            getartist();
         } else if (textVal.length >= 2) {
            getartist();
         }
      });
   });
   //To select a country name
   function selectProduct(val) {
      $("#search-box").val(val);
      getartist();
      $("#suggesstion-box").hide();
   }
   $(document).on('keyup', '#event_tags', function() {
      getartist();
   })

   function getartist() {
      var Selected_Date_ = '';

      var url = '<?= base_url('search/calendar-filter-artist') ?>';
      $.ajax({
         url: url,
         method: "POST",
         data: $('#event_search_form').serialize() + "&Selected_Date_=" + Selected_Date_,
         dataType: "json",
         success: function(data) {
            $('#html').html(data.data);
            console.log(data);
            // $('#venue_location').html(data.data2);

         }
      });
   }
</script>

<script>
   function showPopup(id) {
      $.ajax({
         url: '<?php echo base_url('get-artist-data'); ?>',
         data: {
            id: id
         },
         type: 'GET',
         success: function(response) {
            // Append the popup HTML to the body
            $('#artist_content').html(response);
            $('#openInfoModal').trigger('click');
         }
      });
   }
   $(document).on('click', '.more_info_artist_btn', function() {
      var id = $(this).attr('data-id')
      showPopup(id);
   })
</script>

<script>
   // document.getElementById("clearButton").addEventListener("click", function(event) {

   //    event.preventDefault();

   //    clearAllData();

   //    location.reload();
   // });

   function clearAllData() {

      console.log("All data cleared");
   }
</script>

<script>
   $('#owl-carousel').owlCarousel({
      autoplay: true,
      loop: true,
      margin: 20,
      dots: true,
      nav: false,
      items: 4,
   })
</script>