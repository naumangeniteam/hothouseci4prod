<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
   #day-carousel {
      margin: 0 auto !important;
      display: flex;
      justify-content: space-evenly;
      overflow-x: unset;
   }

   .calendar-box {
      min-height: 299px;
   }

   .partner-button {
      margin-top: 0;
   }

   .owl-height {
      height: 50px !important;
   }

   .owl-stage {
      transform: translate3d(-700px, 0px, 0px) !important;
      transition: all 0.8s ease 0s !important;
      width: 2100px !important;
   }

   owl-stage-outer {
      height: 50px !important;
   }

   .selectdate>.day-box {
      background-color: #d6171f !important;
   }

   .day-box {
      transition: 600ms all;
      cursor: pointer;
   }

   #calendar-section::before {
      content: "";
      position: absolute;
      width: 100%;
      height: 100%;
      /*background: url("../front/img/loader.webp") no-repeat center #fff;*/
      top: 0;
      bottom: 0;
      z-index: 5;
      background-size: 100px;
      transition: 600ms all;
      transform: scale(1);
   }

   #calendar-section {
      position: relative;
   }

   #calendar-section.expand::before {
      transform: scale(0);
   }

   .selectdate * {
      color: #fff !important;
      border-color: #fff !important;
   }

   .owl-nav button {
      outline: none !important;
   }

   /* .chooseDay .item {
   display: table-cell; 
   }*/
   .sortAsc {
      background-color: #fbe1e1;
      color: #ba5d60;
      font-family: 'open sans', 'arial';
      font-size: 14px;
      text-transform: uppercase;
      display: inline-block;
      padding: 5px 17px;
      border-radius: 4px;
      cursor: pointer;
      font-weight: 600;
      font-style: italic;
   }

   .new {
      display: block;

      float: right;
      margin-right: -330px;
      border: none;
      margin-top: -16px;
   }

   .events-btn {
      border: 1px solid red;
      padding: 10px;
      color: #000000;
      font-weight: 500;
      cursor: pointer;
   }

   .single-banner-caption.about-caption {
      width: 500px;
      left: 0%;
      top: 30%;
      text-align: center;
   }

   .ban-text {
      left: 1% !important;
   }


   @media (max-width: 576px) {
      .sortAsc {
         padding: 8px 16px;
         font-size: 14px;
      }
   }

   /* Media query for screens smaller than 768px */
   @media (max-width: 768px) {
      .sortAsc {
         /* padding: 10px 20px; */
         /* font-size: 15px; */
         margin-left: -105px;
      }

      .de {
         margin-left: -180px;
         margin-top: -35px;
      }
   }

   /* Media query for screens smaller than 992px */
   @media (max-width: 992px) {
      .sortAsc {
         margin-top: -45px;
      }
   }

   @media (max-width: 320px) {
      .events-mob {
         height: 89px;
         font-size: 17px;
         color: #D54E21;
         font-family: 'Open Sans', sans-serif;
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
         /* display: block; */
      }

      .d-flex {
         /* display: -webkit-box !important; */
         display: -ms-flexbox !important;
         display: block !important;
         gap: 20px;
      }

      .tags {
         width: 259px !important;
      }

      .venues {
         width: 257px !important;
      }

      .locs {
         width: 257px !important;
      }

      .jazzed {
         width: 255px !important;
      }

      .form-control {
         margin-bottom: 6px !important;
      }

      .sorting_butn {
         margin-top: 0rem !important;
      }

      /* .calendar-box_mobile {
         margin-top: 166px !important;

      } */
      .newStylezz-mobile {
         margin-top: 152px;
         margin-left: auto;
         margin-right: auto;
      }
   }


   .slider {
      position: relative;
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
   }

   .caption_here {
      position: absolute;
      width: 80%;
      bottom: 7%;
      color: #fff;
   }

   #partner-carousel.owl-carousel .owl-item img {
      display: block;
      width: 35%;
      margin: auto;
   }

   .al_width {
      /* left: -37px !important; */
      width: 100% !important;
      top: 24% !important;
   }

   .al-pin-event {
      min-height: 263px !important;
      width: 570px !important;
   }

   .al-image-fluid {
      width: 161px;
      margin-top: 17px;
   }

   .al_listen-issue {
      font-size: 24px !important;
      top: 30% !important;
   }

   .listen-issue .img1 {
      width: 200px !important;
      margin-top: 15px !important;
   }

   .listen-issue .wrapv:after {
      left: 25% !important;
      top: -30px !important;
      height: 260px !important;
   }

   .img-al {
      height: 380px !important;
   }

   .listen-issue-text {
      margin-top: -41px !important;
   }

   .event-a {
      text-decoration: none !important;
   }

   .more_info_artist_btn {
      text-decoration: none;
   }

   .buy_reserve_both {
      text-decoration: none;
   }

   .event-tags-de {
      padding: 7px;
      font-size: 12px;
   }

   .clear-btn {
      width: 79px;
      height: 36px;
      border-radius: 5px;
      background: #fff;
      border: 1px solid #d9d1d1;
      font-weight: 600;
      font-size: 14px;
      color: #212529;
   }

   .c-wid {
      width: 82% !important;
   }

   .past-date {
      cursor: none;
      pointer-events: none;
   }
</style>
<link rel="stylesheet" href="css/style.css">
<link href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.theme.default.min.css" rel="stylesheet" />
<link href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet" />
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/owl.carousel.js"></script>

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
                                    <h1 style="color: white;">New Events</h1>
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

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>-->
<div class="calendar-section al-calendar-section" id="calendar-section">
    <div class="container-fluid">
        <!--<div class="row">
         <div class="col-lg-6 col-md-6 col-sm-6 col-12"></div>
         <div class="col-lg-6 col-md-6 col-sm-6 col-12" style="padding-left: 319px;
            font-size: 30px;">
            <button id="toggleButton1" class='showSortingBtn float_right corePrettyStyle sortAsc btn'>SORT OPTIONS</button>
            <ul id="list" style="display: none;" class="prettyFileSorting dropDownList unstyled de ">
               <span style="height: 26px;
                  font-size: 17px;
                  margin-top: 13px;
                  margin-left: -17px;color: #D54E21;font-family: 'Open Sans', sans-serif;
                  padding: 0;
                  margin: 0;
                  text-transform: uppercase;
                  font-size: 14px;
                  list-style-type: none;  
                  " onclick="myFunction()">
                  VENUE : ALL
                  <select class="form-control" id="mySelect">
                     <option value="">Select Venue</option>
                     <?php
                        foreach ($event_tbl as $key => $item) :
                            $getVenuName = $this->db->select('venue_title')->from('venue_tbl')->where('id', $item['venue_id'])->get()->row();
                        ?>
                     <option value="<?php echo $item['venue_id']; ?>"><?= $getVenuName->venue_title ?></option>
                     <?php endforeach; ?>
                  </select>
               </span>
            </ul>
         </div>
         </div>--->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 calendarmonthdate">
            <div id="heading-carousel" class="owl-carousel">
               <div class="monthsData">
                 
               </div>
               <div class="owl-nav">
                  <button type="button" role="presentation" class="owl-prev"> <span aria-label="Previous">‹</span> </button>
                  <button type="button" role="presentation" class="owl-next"> <span aria-label="Next">›</span> </button>
               </div>
            </div>
            <div id="day-carousel" class="owl-carousel"> </div>
           
         </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-12 onclick_Inpputs" style="margin-top: 15px;">
                <div class="sorting_butn">
                <div class="events-alls">
                  <a href="<?= $BASE_URL?>submit_event" class="event-a">
                     <span class='events-btn '>Add Your Event</span>
                  </a>
               </div>
                    <!-- <button id="toggleButton1" class='showSortingBtn float_right corePrettyStyle sortAsc btn'>SEARCH OPTIONS</button> -->
                </div>
                <ul id="list" style="display: block;" class="prettyFileSorting dropDownList unstyled form-control neww events-mob">
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
                            <input class="form-control" placeholder="Search by event or artist name " id="search-box" name="event_title" style="width: 257px;">
                            <div id="suggesstion-box"></div>

                            <input class="form-control tags" placeholder="Search event tags" id="search-box1" name="keyword" style="width: 165px;">
                            <!-- <div id="suggesstion-box1"></div> -->


                            <select class="form-control venues" name="venue" id="mySelect" style="width: 171px;">
                                <option value="">Select a location</option>
                                <?php foreach ($location_tbl as $location) : ?>
                                    <option value="<?= $location['id']; ?>"><?= $location['venue_title'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select class="form-control locs" name="location" id="venue_location" style=" width: 163px;">
                                <option value="">Select a venue</option>
                                <?php foreach ($venue_tbl as $venue) : ?>
                                    <option value="<?= $venue['id']; ?>"><?= $venue['location_name'] ?></option>
                                <?php endforeach; ?>
                            </select>

                            <select class="form-control jazzed" name="jazz" id="jazz" style="width:184px;">
                                <option value="">Select a Jazz</option>
                                <?php foreach ($jazzType as $jType) : ?>
                                    <option value="<?= $jType->id ?>"><?= $jType->name ?></option>
                                <?php endforeach; ?>
                            </select>

                            <input type="hidden" id="venue" value="<?= $location['id']; ?>">
                            <input type="hidden" id="artist" value="<?= $artist['id']; ?>">
                            <input type="hidden" id="selDate" value="">
                            <a href="#" id="clearButton"><button class="clear-btn">Clear</button></a>
                        </form>
                    </span>

                </ul>
            </div>

            <div id="current_display_div" style="display:none;">
                <!-- this di cut from here  -->
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row newStylezz newStylezz-mobile" id="html">
            <?php if (!empty($get_new_events)) :
                foreach ($get_new_events as $key => $item) :
                    // echo"<pre>";print_r($get_new_events);die;
                    $currentdate = strtotime(date('Y-m-d'));
                    $evetntstart = strtotime($item["start_date"]);
                    // print_r($evetntstart);die;

                    $getVenuName = $this->db->select('venue_title')->from('venue_tbl')->where('id', $item['venue_id'])->get()->row();
                    $date = DateTime::createFromFormat('Y-m-d', $item["start_date"]);
                    //print_r($date);die;
                    $month_num1 =  $date->format('m');
                    $month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));
                    $event_start_time = date('h:i a', strtotime($item['event_start_time']));
                    //print_r($date);die;
                    if ($currentdate == $evetntstart) {
            ?>

                        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="calendar-box ">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                        <h5><?php echo $date->format('d');   ?> <span><?php echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3);   ?>
                                                <?php echo substr(strtoupper($date->format("l")), 0, 3);   ?></span>
                                        </h5>
                                        <p class="text-left"><?php echo  strtoupper($event_start_time);   ?></p>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-12">
                                        <div class="event-heading">
                                            <h6><?= $item['event_title'] ?></h6>
                                        </div>
                                    </div>
                                </div>
                                <a href="<?php echo base_url('thumbnail-full-image'); ?>?artistId=<?php echo $item['artist_id']; ?>&eventId=<?php echo $item['event_id']; ?>">
                                    <?php if (isset($item['artist_image'])) : ?>
                                        <img src="<?php echo base_url('assets/front/img/artistimage/' . $item['artist_image']); ?>" alt="Artist Image" style="width: 100px;">
                                    <?php endif; ?>
                                </a>
                                <p><?= $getVenuName->venue_title ?></p>
                                <p><i class="fas fa-map-marker-alt"></i> <?= $item['location_name'] ?>,<?= $item['location_address'] ?></p>
                                <p><?= $item['website'] ?></p>
                                <p>
                                    <?php if (!empty($item['buy_now_link'])) : ?>
                                        <a target="_blank" href="<?= $item['buy_now_link'] ?>" class="buy_reserve_both">Buy Now</a>
                                    <?php endif; ?>
                                    <?php if (!empty($item['reserve_seat_link'])) : ?>
                                        <a target="_blank" href="<?= $item['reserve_seat_link'] ?>" class="buy_reserve_both">Reserve now</a>
                                    <?php endif; ?>
                                </p>
                                <?php if (!empty($item['jazz_type_name'])) : ?>
                                    <p><?= ucfirst($item['jazz_type_name']) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($item['event_types'])) : ?>
                                    <p><?= ucfirst($item['event_types']) ?></p>
                                <?php endif; ?>

                                <?php if ($item['artist_id'] > 0) : ?>
                                    <p>
                                        <a data-id="<?php echo $item['event_id']; ?>" href="javascript:void(0)" class="more_info_artist_btn">
                                            Artist Info
                                        </a>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($item['phone_number'])) : ?>
                                    <p><i class="fas fa-phone-alt"></i> <?= $item['phone_number'] ?> </p>
                                <?php endif; ?>

                                <p>
                                    <?php if (!empty($item['event_tags'])) : ?>
                                        <?php foreach ($item['event_tags'] as $tag) : ?>
                                            <span class="badge badge-secondary event-tags-de mt-2"><?php echo $tag; ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </p>

                                <p><a href="<?php echo getCurrentControllerPath('event_detail/' . $item['event_id']) ?>" class="online-btn">View event detail</a></p>
                            </div>
                        </div>

                    <?php } elseif ($currentdate != $evetntstart) { ?>
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="calendar-box">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                        <h5><?php echo $date->format('d');   ?> <span><?php echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3);   ?>
                                                <?php echo substr(strtoupper($date->format("l")), 0, 3);   ?></span>
                                        </h5>
                                        <p class="text-left"><?php echo  strtoupper($event_start_time);   ?></p>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-12">
                                        <div class="event-heading">
                                            <h6><?= $item['event_title'] ?></h6>
                                        </div>
                                    </div>
                                </div>
                                <a href="<?php echo base_url('thumbnail-full-image'); ?>?artistId=<?php echo $item['artist_id']; ?>&eventId=<?php echo $item['event_id']; ?>">
                                    <?php if (isset($item['artist_image'])) : ?>
                                        <img src="<?php echo base_url('assets/front/img/artistimage/' . $item['artist_image']); ?>" alt="Artist Image" style="width: 100px;">
                                    <?php endif; ?>
                                </a>
                                <p><?= $getVenuName->venue_title ?></p>
                                <p><i class="fas fa-map-marker-alt"></i> <?= $item['location_name'] ?>,<?= $item['location_address'] ?></p>
                                <p><?= $item['website'] ?></p>
                                <p>
                                    <?php if (!empty($item['buy_now_link'])) : ?>
                                        <a target="_blank" href="<?= $item['buy_now_link'] ?>" class="buy_reserve_both">Buy Now</a>
                                    <?php endif; ?>
                                    <?php if (!empty($item['reserve_seat_link'])) : ?>
                                        <a target="_blank" href="<?= $item['reserve_seat_link'] ?>" class="buy_reserve_both">Reserve now</a>
                                    <?php endif; ?>
                                </p>
                                <?php if (!empty($item['jazz_type_name'])) : ?>
                                    <p><?= ucfirst($item['jazz_type_name']) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($item['event_types'])) : ?>
                                    <p><?= ucfirst($item['event_types']) ?></p>
                                <?php endif; ?>

                                <?php if ($item['artist_id'] > 0) : ?>
                                    <p>
                                        <a data-id="<?php echo $item['event_id']; ?>" href="javascript:void(0)" class="more_info_artist_btn">
                                            Artist Info
                                        </a>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($item['phone_number'])) : ?>
                                    <p><i class="fas fa-phone-alt"></i> <?= $item['phone_number'] ?> </p>
                                <?php endif; ?>

                                <p>
                                    <?php if (!empty($item['event_tags'])) : ?>
                                        <?php foreach ($item['event_tags'] as $tag) : ?>
                                            <span class="badge badge-secondary event-tags-de mt-2"><?php echo $tag; ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </p>

                                <p><a href="<?php echo getCurrentControllerPath('event_detail/' . $item['event_id']) ?>" class="online-btn">View event detail</a></p>
                            </div>
                        </div>
                <?php }
                endforeach;  ?>


            <?php else : ?>
                <h3 style="text-align:center;margin-top:20px;margin-left: auto;margin-right: auto;">No Events Found</h3>
            <?php endif; ?>

        </div>
    </div>
</div>


<div class="subscribe-section mt-3">
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

            <!-- Modal -->
            <div class="modal fade" id="more_info_about_artist" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body" id="artist_content">
                        </div>
                    </div>
                </div>
            </div>
            <button style="display: none;" id="openInfoModal" data-toggle="modal" data-target="#more_info_about_artist"></button>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js"></script>
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
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>-->
<script>
    document.getElementById('event_search_form').addEventListener('submit', function(event) {
        event.preventDefault();
        console.log('Form submission prevented');
    });
</script>
<script>
    $("#html").hide();

    window.onload = function() {
        setTimeout(function() {
            $("#html").show();
        }, 2000);
        $('#calendar-section').addClass("expand");
        $("#html").show();
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
    //     var windowpos = $(window).scrollTop();
    //     if (windowpos > pos.top) {
    //         s.addClass("stick");
    //     } else {
    //         s.removeClass("stick");
    //     }
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
            //alert('year : '+year+'   month : '+month);
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
<!-- <script>
    function change(year, month, date) {
        var start_date = year + '-' + month + '-' + date;
        // alert(start_date)
        var d = new Date(start_date.replace(/-/g, "/")),
            month = '' + (d.getMonth() + 1),
            //day = '' + d.getDate(),
            day = '' + date,
            year = d.getFullYear();
        // alert(d)


        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;
        var selected_date = [year, month, day].join('-');
        // alert(selected_date)
        var ur = "<?= base_url('calendar-filter-artist') ?>"
        // alert(ur);
        $.ajax({
            url: ur,
            method: "POST",
            dataType: 'json',
            data: $('#event_search_form').serialize() + "&Selected_Date_=" + start_date + "&selected_date=" + selected_date,
            success: function(data) {
                // alert(data)
                $('.selectdate').removeClass('selectdate');
                var elements = document.getElementsByClassName('item selectdate');
                $("#" + data.selected_date).addClass("selectdate");
                document.getElementById('current_display_div').style.display = 'none';
                //document.getElementById(data.selected_date).style.backgroundColor = 'grey';
                document.getElementById(data.selected_date).style.backgroundColor = '';
                $('#html').html(data.data);

                $('#selDate').val(data.selected_date);
            }
        });
    }
</script> -->

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
      var ur = "<?= base_url('event-filter-artist') ?>";

      $.ajax({
         url: ur,
         method: "POST",
         dataType: 'json',
         data: $('#event_search_form').serialize() + "&Selected_Date_=" + start_date + "&selected_date=" + selected_date,
         success: function(data) {
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

    $(document).ready(function() {

        $("#search-box").keyup(function() {
            var Selected_Date_ = $("#selDate").val();
            var current_val = $(this).val();
            // alert(current_val);
            var ur = '<?= base_url() ?>';
            $.ajax({
                type: "POST",
                url: ur + "global-search",
                data: {
                    keyword: current_val,
                    Selected_Date_: Selected_Date_
                },
                /*beforeSend: function() {
                   $("#search-box").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                },*/
                success: function(data) {
                    // alert(data);
                    $("#suggesstion-box").show();
                    $("#suggesstion-box").html(data);
                    $("#search-box").css("background", "#FFF");
                }
            });
        });

    });
    //To select a country name
    function selectProduct(val) {
        $("#search-box").val(val);
        getartist();
        $("#suggesstion-box").hide();
    }

    function getartist() {
       var Selected_Date_ = '';

        var url = '<?= base_url('event-filter-artist') ?>';
        $.ajax({
            url: url,
            method: "POST",
            data: $('#event_search_form').serialize() + "&Selected_Date_=" + Selected_Date_,
            dataType: "json",
            success: function(data) {
                $('#html').html(data.data);
                // $('#venue_location').html(data.data2);

            }
        });
    }
</script>

<script>
    $(document).ready(function() {
        $("#search-box1").keyup(function() {
            getartist();
        });
    });
    $(document).ready(function() {
        $("#search-box").keyup(function() {
            if (this.value == "") {
                getartist();
            }
        });
    });
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
    document.getElementById("clearButton").addEventListener("click", function(event) {

        event.preventDefault();

        clearAllData();

        location.reload();
    });

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