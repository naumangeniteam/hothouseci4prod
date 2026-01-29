<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
<style>
    .slider {
      position: relative;
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
   }

   .slider-text {
      align-items: center;
      display: flex;
      position: absolute;
      top: 30px;
      width: 100%;
   }

   .slider-text .slider-caption h1 {
      font-family: 'Oswald', sans-serif;
      color: #fff;
      font-weight: 500;
      line-height: 60px;
      font-size: 25px;
   }

   .slider-text .slider-caption h1 span {
      display: block;
      font-size: 50px;
      text-transform: uppercase;
      color: #e52b31;
   }

   .slider-text .slider-caption p {
      font-family: 'Open Sans', sans-serif;
      font-size: 17px;
      color: #e52b31;
      font-weight: 600;
      width: 80%;
      margin-left: auto;
      margin-right: auto;
   }

   .slider-text .slider-caption {
      text-align: center;
   }
   .caption_here {
      position: absolute;
      width: 80%;
      bottom: 7%;
      color: #fff;
   }
   @media only screen and (max-width: 1024px) {
     

      .slider-text .slider-caption h1 span {
         font-size: 45px;
      }

      

   }
   @media only screen and (max-width: 768px) {
      .slider-text .slider-caption h1 {
         line-height: 45px;
         font-size: 20px;
      }

      .slider-text .slider-caption h1 span {
         font-size: 35px;
      }

      .slider-text .slider-caption p {
         font-size: 15px;
      }

   }

   @media only screen and (max-width: 375px) {
      .slider-text .slider-caption h1 {
         line-height: 55px;
         font-size: 19px;
      }

      .slider-text .slider-caption h1 span {
         font-size: 30px;
      }

      .slider-text .slider-caption p {
         font-size: 13px;
      }
   }
</style>
<!--- Home Banner ---->
<!-- <div class="container-fluid hj-home-banner">
   <div class="row">
      <?php foreach ($banner as $key => $item) : ?>
         <div class="col-md-12">
            <div class="hj-banner-img">
               <img class="show-desktop" src="<?= $ASSET_FRONT_URL ?>img/banner/home-banner.svg" class="img-fluid w-100" alt="home-banner">
               <img class="show-mobile" src="<?= $ASSET_FRONT_URL ?>img/banner/mb-home.jpg" alt="home-banner">
            </div>
            <div class="hj-banner-details">
               <h1>HOT HOUSE JAZZ GUIDE</h1>
               <h4>Comprehensive list of Jazz Events in New York</h4>
            </div>
         </div>
      <?php endforeach; ?>
   </div>
</div> -->
<?php if ($banner <> '') : ?>
   <div class="main-slider">
      <div class="full-container">
         <div class="row">
            <?php foreach ($banner as $key => $item) : ?>
               <div class="col-lg-12 col-md-12 col-12 p-0 m-0">
                  <div class="owl-slider">
                     <div id="main-carousel" class="owl-carousel">
                        <div class="item">
                           <img class="img-fluid" src="<?= $ASSET_FRONT_URL ?>img/banner/<?= $item['image'] ?>" alt="" style="height:300px!important">
                           <div class="slider-text">
                              <div class="container">
                                 <div class="slider-caption">
                                    <h1><?= $item['title'] ?></h1>
                                    <p><?= $item['content'] ?></p>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
         <?php endforeach;
         endif; ?>
         </div>
      </div>
   </div>





<!--- Home Search Events ---->
<div class="container hhj-sec-padding">
   <div class="row">
      <div class="col-md-2"></div>
      <div class="col-md-8">
         <?php
         // $where5['where']       =   "is_active = '1'";
         // $tbl5                =   'setting_tbl as ftable';
         // $shortField5         =   'id DESC';
         // $shortField6          =   'type_name ASC';
         // $data['setting_tbl'] =    $this->common_model->getData('multiple', $tbl5, $where5);
         // $commonModel = new CommonModel();
         // $where = ['is_active' => '1'];
         // $data['setting_tbl'] = $commonModel->getData('multiple','setting_tbl', $where);
         ?>
         <a href="<?= base_url('home') ?>" class="hhj-home-logo"><img src="<?= $ASSET_FRONT_URL ?>img/banner/logo.svg" class="img-logo" alt="" /></a>

         <form action="<?php echo base_url('all-products'); ?>" id="event_search_form" method="GET" class="userformes hhj-home-search">
         <?= csrf_field() ?>
         <div class="hhj-search-field">
               <img class="search-icon" src="<?= $ASSET_FRONT_URL ?>icons/search.svg" alt="user Login" />
               <input class="form-control artist-event searched searched-mob" placeholder="Search by event or artist name " id="search-box" name="event_title" />
               <img class="filter-icon" src="<?= $ASSET_FRONT_URL ?>icons/filter.svg" onclick="toggleSection()" alt="user Login" />
               <div class="suggesstion-box" id="suggesstion-box"></div>
            </div>

            <div id="toggleSection">
               <div class="hhj-search-fields">
                  <div class="hhj-adv-box">
                     <input class="form-control tags" id="search-box1" value="Search by event tag" name="keyword" />
                     <div id="suggesstion-box1"></div>
                  </div>
                  <div class="hhj-adv-box">
                     <select class="form-control venues" name="venue" id="mySelect">
                        <option value="">Select a location</option>
                        <?php foreach ($location_tbl as $location) : ?>
                           <option value="<?= $location['id']; ?>"><?= $location['venue_title'] ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>
                  <div class="hhj-adv-box">
                     <select class="form-control locs" name="location" id="venue_location">
                        <option value="">Select a venue</option>
                        <?php foreach ($venue_tbl as $venue) : ?>
                           <option value="<?= $venue['id']; ?>"><?= $venue['location_name'] ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>
                  <div class="hhj-adv-box">
                     <select class="form-control jazzed" name="jazz" id="jazz">
                        <option value="">Select a Jazz</option>
                        <?php foreach ($jazzType as $jType) : ?>
                           <option value="<?= $jType->id ?>"><?= $jType->name ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>
                  <div class="clear-filter">
                     <a href="#" id="clearButton"><img src="<?= $ASSET_FRONT_URL ?>icons/eraser.svg" alt="user Login" /> Clear</a>
                  </div>
               </div>
            </div>
            <input type="hidden" id="venue" value="<?= isset($location['id']) ? esc($location['id']) : ''; ?>">
            <input type="hidden" id="artist" value="<?= isset($artist['id']) ? esc($artist['id']) : ''; ?>">
            <input type="hidden" id="selDate" value="">
         </form>
        
 <?= view('layouts/front/home-tabs') ?>

      </div>
      <div class="col-md-2"></div>
   </div>
</div>



<!--- Home Sponsors Events ---->
<div class="hhj-sponsor-events hhj-sec-padding">
   <div class="container-fluid">
      <div class="section-title">
         <h2>SPONSORED EVENTS</h2>
         <h5><a href="#">View all sponsors events</a></h5>
      </div>

      <div class="row">
         <div class="col-md-2">
            <div class="hhj-left-sidebar">
               <span>
                  <?php foreach ($slider_tbl as $item) : ?>
                     <?php if ($item['type'] === 'banner' && $item['alignment'] === 'left') : ?>
                        <?php if (!empty($item['slide_html'])) { 
                           echo $item['slide_html'];
                        } else { ?>
                        <a href="<?= esc($item['weblink']) ?>" target="_blank">
                           <img class="img-fluid" src="<?= base_url('assets/front/img/slider/' . esc($item['image'])) ?>" alt="">
                        </a>
                        <?php } ?>
                     <?php endif; ?>
                  <?php endforeach; ?>
               </span>
            </div>
         </div>
         <div class="col-md-8 hhj-sponsor">
            <div class="row sponsor-row">
               <?php if (!empty($get_event_sponsored)) : ?>
                  <?php foreach ($get_event_sponsored as $item) : ?>
                     <?php 
                     $currentdate = strtotime(date('Y-m-d'));
                     $evetntstart = strtotime($item["start_date"]);
                     $evetntend = strtotime($item["end_date"]);

                     
                    $db = \Config\Database::connect();
                    $builder = $db->table('venue_tbl'); 
                    $venue = $builder->select('venue_title')->where('id', $item['venue_id'])->get()->getRow();
                    $venue_title = $venue ? $venue->venue_title : 'No Venue Found';
                    $getVenuName = [
                     'venue_title' => $venue ? $venue->venue_title : 'No Venue Found',
                 ];
                    $date = DateTime::createFromFormat('Y-m-d', $item['start_date']);
                    $month_name = date("F", mktime(0, 0, 0, $date->format('m'), 10));
                    $event_start_time = date('h:i a', strtotime($item['event_start_time']));
                    ?>
                    <div class="col-md-4 hhj-sponsor-box">
                       <div class="hhj-sbox sp-box" style="background-image: url('<?= base_url('assets/front/img/artistimage/' . ($item['artist_image'] ?? 'events.jpg')) ?>');">
                          <div class="sp-box-info">
                             <div class="hhj-sinfo">
                                <h3><?= esc($item['event_title']) ?></h3>
                                <p><img src="<?= base_url('assets/front/icons/house-white.svg') ?>" alt="menu" /> <?php // echo esc($venue_title) ?><?= $getVenuName->venue_title ?></p>
                                <p><img src="<?= base_url('assets/front/icons/location-white.svg') ?>" alt="menu" /> <a class="loca-map" href="https://www.google.com/maps?q=<?= urlencode($item['location_name']) ?>" target="_blank"> <?= esc($item['location_name']) ?> </a></p>
                                <?php if (!empty($item['website'])) : ?>
                                   <p><a class="loca-map web-link" href="<?= esc($item['website']) ?>" target="_blank"> <img src="<?= base_url('assets/front/icons/website-white.svg') ?>" alt="menu" /> <?= esc(parse_url($item['website'], PHP_URL_HOST)) ?> </a></p>
                                <?php endif; ?>
                                <?php if (!empty($item['phone_number'])) : ?>
                                   <p><img src="<?= base_url('assets/front/icons/phone-white.svg') ?>" alt="menu" /> <?= esc($item['phone_number']) ?></p>
                                <?php endif; ?>
                             </div>
                             <div class="hhj-box-bottom">
                                <div class="event-cost">
                                   <?php if ($item['artist_id'] > 0) : ?>
                                      <a data-id="<?= esc($item['event_id']) ?>" href="javascript:void(0)" class="more_info_artist_btn"><img src="<?= base_url('assets/front/icons/singer-white.svg') ?>" alt="menu" /> Artist Info</a>
                                   <?php endif; ?>
                                   <?php if (!empty($item['cover_charge'])) : ?>
                                      <p class="paid"><img src="<?= base_url('assets/front/icons/price-paid.svg') ?>" alt="menu" /><?= esc($item['cover_charge']) ?></p>
                                   <?php else : ?>
                                      <p class="free"><img src="<?= base_url('assets/front/icons/price-free.svg') ?>" alt="menu" /></p>
                                   <?php endif; ?>
                                </div>
                                <div class="hhj-sdate">
                                   <h6>
                                      <img src="<?= base_url('assets/front/icons/date-white.svg') ?>" alt="menu" />
                                      <?= $date->format('d') . ' ' . strtoupper(substr($month_name, 0, 3)) . ' ' . strtoupper(substr($date->format('l'), 0, 3)) ?>
                                   </h6>
                                   <h6><img src="<?= base_url('assets/front/icons/time-white.svg') ?>" alt="menu" /><?= strtoupper($event_start_time) ?></h6>
                                </div>
                             </div>
                          </div>
                       </div>
                       <div class="hhj-buttons"><a href="<?= base_url('event_detail/' . esc($item['event_id'])) ?>" class="online-btn">View event</a></div>
                    </div>
                 <?php endforeach; ?>
              <?php endif; ?>
           </div>


            <div class="mobile-ads">
               <div class="ads-block-1">
                  <?php foreach ($slider_tbl as $key => $item) : ?>
                     <?php if ($item['type'] === 'banner' && $item['alignment'] === 'left') : ?>
                        <a href="<?= $item['weblink'] ?>" target="_blank"><img class="img-fluid" src="<?= $ASSET_FRONT_URL ?>img/slider/<?= $item['image'] ?>" alt="" class="slider-image-al"></a>
                     <?php endif; ?>
                  <?php endforeach; ?>
               </div>
               <div class="ads-block-2">
                  <?php foreach ($slider_tbl as $key => $item) : ?>
                     <?php if ($item['type'] === 'banner' && $item['alignment'] === 'right') : ?>
                        <a href="<?= $item['weblink'] ?>" target="_blank"><img class="img-fluid" src="<?= $ASSET_FRONT_URL ?>img/slider/<?= $item['image'] ?>" alt=""></a>
                     <?php endif; ?>
                  <?php endforeach; ?>
               </div>
            </div>

            <!--- Home Featured Events ---->
            <div class="hhj-featured-events hhj-sec-padding">
               <div class="section-title">
                  <h2>FEATURED EVENTS</h2>
                  <h5><a href="#">View all featured events</a></h5>
               </div>
               <div class="row">
                  <?php if (!empty($get_event_featured)) :
                     $eventsDisplayed = false;
                     foreach ($get_event_featured as $key => $item) :
                        $currentdate = strtotime(date('Y-m-d'));
                        $evetntstart = strtotime($item["start_date"]);
                        $evetntend = strtotime($item["end_date"]);

                        // $getVenuName = $this->db->select('venue_title')->from('venue_tbl')->where('id', $item['venue_id'])->get()->row();
                        $db = \Config\Database::connect();
                     $builder = $db->table('venue_tbl'); 
                     $venue = $builder->select('venue_title')->where('id', $item['venue_id'])->get()->getRow();
                     $venue_title = $venue ? $venue->venue_title : 'No Venue Found';
                        $getVenuName = [
                            'venue_title' => $venue ? $venue->venue_title : 'No Venue Found',
                        ];
                        $date = DateTime::createFromFormat('Y-m-d', $item['start_date']);
                        $month_num1 = $date->format('m');
                        $month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));
                        $event_start_time = date('h:i a', strtotime($item['event_start_time']));

                        // if (in_array($item['event_id'], $displayed_event_ids)) {
                        //    continue;
                        // }

                        if ($currentdate == $evetntstart || $currentdate == $evetntend || $currentdate != $evetntstart) {

                  ?>
                           <div class="col-md-4 hhj-sponsor-box">
                              <div class="hhj-sbox">
                                 <div class="hhj-sprofile">
                                    <a href="<?php echo base_url('thumbnail-full-image'); ?>?artistId=<?php echo $item['artist_id']; ?>&eventId=<?php echo $item['event_id']; ?>">
                                       <?php if (isset($item['artist_image'])) : ?>
                                          <img src="<?php echo base_url('assets/front/img/artistimage/' . $item['artist_image']); ?>" alt="Artist Image" class="al-imgs">
                                       <?php else : ?>
                                          <img src="<?php echo base_url('assets/front/img/artistimage/events.jpg'); ?>" alt="Default Image" class="al-imgs">
                                       <?php endif; ?>
                                    </a>
                                 </div>

                                 <div class="hhj-sinfo">
                                    <?php
                                    $url = $item['website'];
                                    $url_display = preg_replace('/^(https?:\/\/)?(www\.)?/', '', $url);
                                    ?>
                                    <h3><?= $item['event_title'] ?></h3>
                                    <p><img src="<?= $ASSET_FRONT_URL ?>icons/house.svg" alt="menu" /> <?= $getVenuName->venue_title ?></p>
                                    <p><img src="<?= $ASSET_FRONT_URL ?>icons/location.svg" alt="menu" /> <a class="loca-map" href="https://www.google.com/maps?q=<?= urlencode($item['location_name']) ?>" target="_blank"><?= htmlspecialchars($item['location_name']) ?></a></p>
                                    <p>
                                       <?php if (!empty($item['website'])) : ?>
                                          <a class="loca-map web-link" href="<?= rtrim($url, '/') ?>" target="_blank"><img src="<?= $ASSET_FRONT_URL ?>icons/website.svg" alt="menu" /><?= rtrim($url_display, '/') ?></a>
                                       <?php endif; ?>
                                    </p>
                                    <?php if (!empty($item['phone_number'])) : ?>
                                       <p><img src="<?= $ASSET_FRONT_URL ?>icons/phone.svg" alt="menu" /><?= $item['phone_number'] ?> </p>
                                    <?php endif; ?>
                                 </div>

                                 <div class="hhj-box-bottom">
                                    <div class="event-cost">
                                       <?php if ($item['artist_id'] > 0) : ?>
                                          <a data-id="<?php echo $item['event_id']; ?>" href="javascript:void(0)" class="more_info_artist_btn"><img src="<?= $ASSET_FRONT_URL ?>icons/singer.svg" alt="menu" />Artist Info</a>
                                       <?php endif; ?>

                                       <?php if (!empty($item['cover_charge'])) : ?>
                                          <p class="paid"><img src="<?= $ASSET_FRONT_URL ?>icons/price-paid.svg" alt="menu" /><?php echo $item['cover_charge']; ?></p>
                                       <?php else : ?>
                                          <p class="free"><img src="<?= $ASSET_FRONT_URL ?>icons/price-free.svg" alt="menu" /></p>
                                       <?php endif; ?>
                                    </div>
                                    <div class="hhj-sdate">
                                       <h6>
                                          <img src="<?= $ASSET_FRONT_URL ?>icons/date.svg" alt="menu" />
                                          <?php echo $date->format('d'); ?>
                                          <?php echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3); ?>
                                          <?php echo substr(strtoupper($date->format("l")), 0, 3); ?>
                                       </h6>
                                       <h6><img src="<?= $ASSET_FRONT_URL ?>icons/time.svg" alt="menu" /><?php echo strtoupper($event_start_time); ?></h6>
                                    </div>
                                 </div>
                              </div>
                              <div class="hhj-buttons"><a href="<?= base_url('event_detail/' . esc($item['event_id'])) ?>" class="online-btn">View event</a></div>
                    <!-- in ci3 getCurrentControllerPath() give error -->
                              <!-- <div class="hhj-buttons"><a href="<?php //echo getCurrentControllerPath('event_detail/' . $item['event_id']); ?>" class="online-btn">View event</a></div> -->
                           </div>
                  <?php }
                     endforeach;
                  endif;
                  ?>
               </div>
            </div>
         </div>
         <div class="col-md-2">
            <div class="hhj-right-sidebar">
               <span>
                  <?= $home_image[0]['month'] ?>
                  <a href="<?= $home_image[0]['image2_weblink'] ?>" target="_blank">
                     <img class="img-fluid" src="<?= $ASSET_FRONT_URL ?>img/homeimage/<?= $home_image[0]['image2'] ?>" alt="">
                  </a>

               </span>
               <span>
                  <?php foreach ($slider_tbl as $key => $item) : ?>
                     <?php if ($item['type'] === 'banner' && $item['alignment'] === 'right') : ?>
                        <?php if(!empty($item['slide_html'])){ 
                           echo $item['slide_html'];
                        }else{?>
                        <a href="<?= $item['weblink'] ?>" target="_blank"><img class="img-fluid" src="<?= $ASSET_FRONT_URL ?>img/slider/<?= $item['image'] ?>" alt=""></a>
                        <?php
                        } 
                        ?>
                     <?php endif; ?>
                  <?php endforeach; ?>
               </span>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- <div class="container">
<h2 class="text-center mt-3 mb-3">Signup Form</h2>
<iframe src="https://mailchi.mp/hothousejazz/hot-house-jazz-landing-page" data-test-id="beehiiv-embed" width="100%" height="1300" frameborder="0" scrolling="no" style="border-radius: 4px; border: 2px solid #e5e7eb; margin: 0; background-color: transparent;"></iframe>	
</div> -->


<div class="hhj-partners">
   <div class="container">
      <h2 >Spotlights</h2>
      <div class="carousel-container position-relative">
         <div class="carousel d-flex">
            <?php foreach ($home_slider_image as $key => $item) : ?>
              
                  <div class="carousel-item col-12 col-md-6 col-lg-3">
                     <div class="item-content">
                       
                        <a href="<?= $item['url'] ?>" target="_blank">
                           <img src="<?= $ASSET_FRONT_URL ?>img/homeimage/<?= $item['image'] ?>" alt="Spotlights" />
                           <div class="caption_here">
                              <?= $item['slider_content'] ?>
                           </div>
                        </a>
                       
                     </div>
                  </div>
            <?php endforeach; ?>
         </div>
         <button class="carousel-button prev btn btn-dark position-absolute" onclick="scrollCarousel(-1)">&#10094;</button>
         <button class="carousel-button next btn btn-dark position-absolute" onclick="scrollCarousel(1)">&#10095;</button>
      </div>
   </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script type='text/javascript' src='https://s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>

<script>
   let currentIndex = 0;
   const autoSlideInterval = 3000; // Auto slide every 3 seconds
   let autoSlideTimer;

   // Function to slide the carousel
   function scrollCarousel(direction) {
      const carousel = document.querySelector('.carousel');
      const items = document.querySelectorAll('.carousel-item');
      const itemWidth = items[0].offsetWidth;
      const visibleItems = Math.floor(carousel.parentElement.offsetWidth / itemWidth);
      const maxIndex = items.length - visibleItems;

      // Update the current index
      currentIndex += direction;

      // Loop back to the start if reaching the end
      if (currentIndex > maxIndex) {
         currentIndex = 0; // Reset to the first item
      } else if (currentIndex < 0) {
         currentIndex = maxIndex; // Loop to the last set of items
      }

      // Move the carousel
      carousel.style.transform = `translateX(${-currentIndex * itemWidth}px)`;
   }

   // Function to auto slide
   function startAutoSlide() {
      autoSlideTimer = setInterval(() => {
         scrollCarousel(1); // Move right automatically
      }, autoSlideInterval);
   }

   // Function to stop auto slide on hover
   function stopAutoSlide() {
      clearInterval(autoSlideTimer);
   }

   // Initialize auto slide on load
   document.addEventListener('DOMContentLoaded', (event) => {
      startAutoSlide();

      // Stop auto slide on hover
      const carouselContainer = document.querySelector('.carousel-container');
      carouselContainer.addEventListener('mouseover', stopAutoSlide);
      carouselContainer.addEventListener('mouseout', startAutoSlide);
   });
</script>
<!-- Filter Search show onclick [added by imran] --->
<script>
   function toggleSection() {
      var section = document.getElementById('toggleSection');
      if (section.style.display === 'none' || section.style.display === '') {
         section.style.display = 'block';
      } else {
         section.style.display = 'none';
      }
   }
</script>

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
   // jQuery("#heading-carousel").owlCarousel({
   //    autoplay: false,
   //    loop: true,
   //    margin: 0,
   //    /*
   //   animateOut: 'fadeOut',
   //   animateIn: 'fadeIn',
   //   */
   //    responsiveClass: true,
   //    autoHeight: true,
   //    //autoplayTimeout: 7000,
   //    smartSpeed: 800,
   //    nav: true,
   //    dots: false,
   //    responsive: {
   //       0: {
   //          items: 1,
   //       },
   //       600: {
   //          items: 1,
   //       },

   //       1024: {
   //          items: 1,
   //       },

   //       1366: {
   //          items: 1
   //       }
   //    }
   // });
</script>
<!-- <script>
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
</script> -->
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
<script>
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

      $("#search-box").keyup(function(event) {
         var current_val = $(this).val();
         var url = '<?= base_url() ?>';
         if (event.keyCode == 13) {
            window.location.href = url + 'global-search/h?keyword=' + current_val;
         }

         // var Selected_Date_ = $("#selDate").val();
         // var current_val = $(this).val();
         // // alert(current_val);
         // var ur = '<?= base_url() ?>';
         // $.ajax({
         //    type: "POST",
         //    url: ur + "global-search",
         //    data: {
         //       keyword: current_val,
         //       Selected_Date_: Selected_Date_
         //    },
         //    /*beforeSend: function() {
         //       $("#search-box").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
         //    },*/
         //    success: function(data) {
         //       // alert(data);
         //       $("#suggesstion-box").show();
         //       $("#suggesstion-box").html(data);
         //       $("#search-box").css("background", "#FFF");
         //    }
         // });
      });

   });
   //To select a country name
   function selectProduct(val) {
      $("#search-box").val(val);
      getartist();
      $("#suggesstion-box").hide();
   }

   function getartist() {
      var Selected_Date_ = $("#selDate").val();

      var url = '<?= base_url('calendar-filter-artist') ?>';
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
      items: 5,
   })
</script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
   <script>