<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

   #partner-carousel.owl-carousel .owl-item img {
      display: block;
      width: 35%;
      margin: auto;
   }

   .caption_here {
      position: absolute;
      width: 80%;
      bottom: 7%;
      color: #fff;
   }

   .pin-event {
      margin-left: 17%;
      min-height: 252px;

   }

   .pin-event .vertical {
      transform: rotate(-90deg);
      font-family: 'Oswald', sans-serif;
      color: #fff;
      font-size: 30px;
      white-space: nowrap;
      position: absolute;
      text-transform: uppercase;
      /* left: 15%; */
      top: 40%;
      background: #222222;
      z-index: 2;
      padding: 10px;
   }

   .wrapv:after {
      content: '';
      width: 1px;
      height: 300px;
      background: #919191;
      position: absolute;
      left: 0%;
      top: 55px;
      right: 0;
      bottom: 0;
      z-index: 0;
   }
   
   .pin-event .img1 {
      margin-left: 93px;
      padding: 25px;
      width: 293px;
      margin-top: 15px;
   }


   .subscribe_btn a {
      font-size: 16px;
      width: fit-content;
      display: block;
      float: left;
      overflow: hidden;
      background: #000000;
      margin-right: 10px;
      border-radius: 7px;
      padding: 10px 15px;
      color: #ffffff;
      text-decoration: none;
   }

   .subscribe_btn {
      display: flex;
      justify-content: center;
      margin-top: 10px;
   }   @media only screen and (max-width: 768px) {
      .subscribe_btn {
      display: flex;
      justify-content: center;
      margin-top: 80px;
      flex-direction: column;
      align-items: center;
      width: 100%;
      float: none;
      margin-right: 0;
      margin-bottom: 15px;
      text-align: center;
    }
    
    /* Add margin to create gap between buttons */
    .subscribe_btn a {
        display: block;
        width: 80%;
        margin-bottom: 15px; /* This creates the gap between buttons */
        clear: both;
    }
   }


   .home-tab-icons a {
      font-size: 26px;
      letter-spacing: 25px;
      color: #000;
   }

   .home-tab-icons a:hover {
      font-size: 26px;
      letter-spacing: 25px;
      color: #dc3545;
   }

   .carousel-control-prev-icon,
   .carousel-control-next-icon {
      background-color: black;
      background-size: 100%, 100%;
      /* Ensures the icon is fully visible */
   }
   @media only screen and (max-width: 1024px) {
     .slider-text .slider-caption h1 span {
        font-size: 45px;
     }
     
  }
   @media only screen and (max-width: 768px) {
      .pin-event {
         margin-left: 0%;
      }

      .pin-event .img1 {
         margin-left: 50px;
      }

      .pin-event .vertical {
         left: -13%;
      }

      *,
      ::after,
      ::before {
         box-sizing: border-box;
      }

      *,
      ::after,
      ::before {
         box-sizing: border-box;
      }

      user agent stylesheet div {
         display: block;
         unicode-bidi: isolate;
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
         font-size: 35px;
      }

      .slider-text .slider-caption p {
         font-size: 13px;
      }
   }
</style>
<!-- <?php if ($banner <> ''): ?>
<?php foreach ($banner as $key => $item): ?>
   <div class="full-container page-banner" style="background: url('<?=base_url().'assets/front/img/banner/'.$item['image'] ?>');">
      <div class="page-banner-caption"><h1>Magazine</h1></div>
   </div>
<?php endforeach;
      endif; ?> -->
<?php if ($banner <> '') : ?>
   <div class="main-slider">
      <div class="full-container">
         <div class="row">
            <?php foreach ($banner as $key => $item) : ?>
               <div class="col-lg-12 col-md-12 col-12 padding-zero">
                  <div class="owl-slider">
                     <div id="main-carousel" class="owl-carousel">
                        <div class="item">
                           <img class="img-fluid" src="<?= base_url('assets/front/img/banner/'.$item['image']) ?>" alt="" style="height:300px!important">
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
   <?= view('layouts/front/home-tabs') ?>

   <div class="home-history">
      <div class="container">
         <div class="row megha">
            <div class="col-lg-7 col-md-7 col-sm-7 col-12 bd-right">
               <h3 class="default-heading mt-3">Spotlights</h3>
               <div class="owl-carousel owl-theme mt-5" id="manjeet">
                  <?php foreach ($home_slider_image as $key => $item) : ?>
                     <div class="item">
                        <div class="slider">
                           <a href="<?= $item['url'] ?>" target="_blank">
                              <img src="<?= base_url('assets/front/img/homeimage/'.$item['image']) ?>" alt="">
                              <div class="caption_here">
                                 <?= ($item['slider_content']) ?>
                              </div>
                           </a>
                        </div>
                     </div>
                  <?php endforeach; ?>
               </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-12">

               <div class="pin-event">
                  <div class="wrap">
                     <div class="wrapv">
                        <div class="vertical">
                           <?= $home_image[0]['month'] ?>
                        </div>
                     </div>
                     <a href="<?= $home_image[0]['image2_weblink'] ?>" target="_blank">
                        <img class="img-fluid img1" src="<?= base_url('assets/front/img/homeimage/'. $home_image[0]['image2'] )?>" alt=""></a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="container">
      <div class="row">
         <div class="col-12 subscribe_btn">
            <a href="https://simplecirc.com/subscribe/hot-house-jazz-guide">SUBSCRIBE TO THE MAGAZINE</a>
            <a href="https://hothousejazzguide.com/hh_jazz_guide#newsletter">SUBSCRIBE TO THE NEWSLETTER</a>
         </div>
      </div>
   </div>


   <!-- <div class="hhj-sponsor-events hhj-sec-padding">
	<div class="container-fluid">
		<div class="section-title">
      <?php
         $month = date('m');
         $year = date('Y');
         ?>
			<h2>OUR CALENDER</h2>
			<h4 style="text-align:center;">LATEST LIST OF <span style="color: #FE0000;"><?php echo strtoupper(date("F", mktime(0, 0, 0, $month, 10))); ?> <?php echo $year; ?></span> EVENTS</h4>
		</div>
      <div class="container">
         <div class="row sponsor-row maz-events">
            <?php
            $i = 0;
            foreach ($event_tbl as $key => $item):
               $currentdate = strtotime(date('Y-m-d'));
               $evetntstart = strtotime($item["start_date"]);

               // $getVenuName = $this->db->select('venue_title')->from('venue_tbl')->where('id', $item['venue_id'])->get()->row();
               $db = \Config\Database::connect();
               $builder = $db->table('venue_tbl'); 
               $venue = $builder->select('venue_title')->where('id', $item['venue_id'])->get()->getRow();
               $venue_title = $venue ? $venue->venue_title : 'No Venue Found';
               $getVenuName = [
                'venue_title' => $venue ? $venue->venue_title : 'No Venue Found',
            ];
             $date = DateTime::createFromFormat('Y-m-d', $datae);
               $month_num1 =  $date->format('m');
               $month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));
               $event_start_time = date('h:i a', strtotime($item['event_start_time']));

               if ($currentdate == $evetntstart) {
                  if ($i < 3) {
            ?>
            <div class="col-md-3 hhj-sponsor-box">
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
                     <h3><?= $item['event_title'] ?></h3>
                     <p><img src="icons/house.svg" alt="menu"/> <?= $getVenuName['venue_title'] ?></p>
                     <p><img src="icons/location.svg" alt="menu"/> <?= $item['location_name'] ?>,<?= $item['location_address'] ?></p>
                     <p><img src="icons/website.svg" alt="menu"/><?= $item['website'] ?></p>
                     <?php if (!empty($item['phone_number'])) : ?>
                     <p><img src="icons/phone.svg" alt="menu"/><?= $item['phone_number'] ?> </p>
                     <?php endif; ?>
                  </div>
                  
                  <div class="hhj-box-bottom">
                     <div class="event-cost">
                        <?php if ($item['artist_id'] > 0) : ?>
                           <a data-id="<?php echo $item['event_id']; ?>" href="javascript:void(0)" class="more_info_artist_btn"><img src="icons/singer.svg" alt="menu"/>Artist Info</a>
                        <?php endif; ?>

                        <?php if (!empty($item['cover_charge'])) : ?>
                           <p class="paid"><img src="icons/price-paid.svg" alt="menu"/><?php echo $item['cover_charge']; ?></p>
                        <?php else : ?>
                           <p class="free"><img src="icons/price-free.svg" alt="menu"/>FREE</p>
                        <?php endif; ?>
                     </div>
                     <div class="hhj-sdate">
                        <h6>
                           <img src="icons/date.svg" alt="menu"/>
                           <?php echo $date->format('d'); ?>
                           <?php echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3); ?>
                           <?php echo substr(strtoupper($date->format("l")), 0, 3); ?>
                        </h6>
                        <h6><img src="icons/time.svg" alt="menu"/><?php echo strtoupper($event_start_time); ?></h6>
                     </div>
                  </div>
               </div>
               <div class="hhj-buttons"><a href="<?php //echo getCurrentControllerPath('event_detail/' . $item['event_id']) ?>" class="online-btn">View event</a></div>
            </div>
            <?php }
                  $i++;
               }
            endforeach; ?>
         </div>
      </div>
   </div>
</div> -->

  
 <div class="hhj-featured-artist">
      <div class="container">
         <div class="section-title">
            <h2>FEATURED ARTISTS</h2>
         </div>
         <div class="row">
            <div class="col-lg-9 col-md-12">
               <div class="blog-post">
                  <div class="artist-sec">
                     <h4><?= $blog_tbl1[0]['page_title'] ?></h4>
                     <div class="contents"><?= $blog_tbl1[0]['home_content'] ?></div>
                  </div>
                  <a href="<?php echo getCurrentControllerPath('blog_detail/' . $blog_tbl1[0]['slug']) ?>">Continue Reading...</a>
               </div>
               <?php foreach ($blog_tbl as $key => $item):
                  if ($key > 0) {
               ?>
                     <div class="blog-post">
                        <div class="artist-sec">
                           <h4><?= ($item['page_title']) ?></h4>
                           <div class="contents"><?= ($item['home_content']) ?></div>
                        </div>
                        <a href="<?php echo getCurrentControllerPath('blog_detail/' . $item['slug']) ?>">Continue Reading...</a>
                     </div>
               <?php }
               endforeach; ?>
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12">
               <div class="fb-block">
                  <h3 class="default-heading">Like Our Facebook Page</h3>
                  <div class="fb-page-iframe">
                     <iframe src="https://www.facebook.com/plugins/page.php?href=hothousejazzguide/&tabs=timeline&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" width="100%" height="500px" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                  </div>
               </div>
               <div class="fb-block mt-5">
                  <h3 class="default-heading">Like Our Instagram Page</h3>
                  <!-- Instagram Icon with Link -->
                  <a href="https://www.instagram.com/hothousejazzguide/" target="_blank" rel="noopener noreferrer">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#E1306C">
                        <path d="M12 2.2c3.2 0 3.6.01 4.9.07 1.17.05 1.95.24 2.41.4.62.24 1.07.53 1.54 1 .47.47.76.92 1 1.54.16.46.35 1.24.4 2.41.06 1.29.07 1.68.07 4.91s-.01 3.61-.07 4.9c-.05 1.17-.24 1.95-.4 2.41-.24.62-.53 1.07-1 1.54-.47.47-.92.76-1.54 1-.46.16-1.24.35-2.41.4-1.29.06-1.68.07-4.9.07s-3.61-.01-4.9-.07c-1.17-.05-1.95-.24-2.41-.4-.62-.24-1.07-.53-1.54-1-.47-.47-.76-.92-1-1.54-.16-.46-.35-1.24-.4-2.41C2.21 15.6 2.2 15.2 2.2 12s.01-3.61.07-4.9c.05-1.17.24-1.95.4-2.41.24-.62.53-1.07 1-1.54.47-.47.92-.76 1.54-1 .46-.16 1.24-.35 2.41-.4C8.4 2.21 8.8 2.2 12 2.2zm0-2.2C8.7 0 8.25 0 7 0c-1.36.01-2.73.07-4.09.16C1.14.26.39.54.14 1.26.01 1.61 0 2.14 0 3.41v17.18c0 1.27.01 1.8.14 2.15.25.72.99 1 2.77 1.1 1.36.09 2.73.15 4.09.16h10c1.35-.01 2.73-.07 4.09-.16 1.78-.1 2.52-.38 2.77-1.1.13-.35.14-.88.14-2.15V3.41c0-1.27-.01-1.8-.14-2.15-.25-.72-.99-1-2.77-1.1C19.73.07 18.35.01 17 0H12zM12 5.5c-3.59 0-6.5 2.91-6.5 6.5s2.91 6.5 6.5 6.5 6.5-2.91 6.5-6.5-2.91-6.5-6.5-6.5zm0 10.8c-2.37 0-4.3-1.93-4.3-4.3s1.93-4.3 4.3-4.3 4.3 1.93 4.3 4.3-1.93 4.3-4.3 4.3zm6.58-10.79c-.79 0-1.43.64-1.43 1.43s.64 1.43 1.43 1.43 1.43-.64 1.43-1.43-.64-1.43-1.43-1.43z" />
                     </svg>
                  </a>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="hhj-partners">
      <div class="container">
         <h2>Our Partners</h2>
         <!-- <div class="carousel-container position-relative">
            <div class="carousel d-flex">
               <?php foreach ($slider_tbl as $key => $item) : ?>
                  <?php if ($item['type'] === 'slider' && $item['page'] == 4) : ?>
                     <div class="carousel-item col-12 col-md-6 col-lg-3">
                        <div class="item-content">

                           <?php if (!empty($item['slide_html'])) {
                              echo $item['slide_html'];
                           } else { ?>
                              <a href="<?= ($item['weblink']) ?>"><img src="<?= base_url('assets/front/img/slider/'.$item['image'] )?>" alt="partners" /></a>
                           <?php
                           }
                           ?>
                        </div>
                     </div>
                  <?php endif; ?>
               <?php endforeach; ?>
            </div>
            <button class="carousel-button prev btn btn-dark position-absolute" onclick="scrollCarousel(-1)">&#10094;</button>
            <button class="carousel-button next btn btn-dark position-absolute" onclick="scrollCarousel(1)">&#10095;</button>
         </div> -->
         <div id="carouselExample" class="carousel slide mb-3" data-ride="carousel">
            <div class="carousel-inner row w-100 mx-auto">
               <?php foreach ($slider_tbl as $key => $item) : ?>
                  <?php if ($item['type'] === 'slider' && $item['page'] == 4) : ?>
                     <div class="carousel-item col-12 col-md-6 col-lg-3 <?= $key === 0 ? 'active' : '' ?>">
                        <div class="item-content d-flex justify-content-center">
                           <?php if (!empty($item['slide_html'])) {
                              echo $item['slide_html'];
                           } else { ?>
                              <a href="<?= ($item['weblink']) ?>"><img src="<?= base_url('assets/front/img/slider/'.$item['image']) ?>" alt="partners" style="width:400px; height:300px" /></a>
                           <?php } ?>
                        </div>
                     </div>
                  <?php endif; ?>
               <?php endforeach; ?>
            </div>
            <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
               <span class="carousel-control-prev-icon" aria-hidden="true"></span>
               <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
               <span class="carousel-control-next-icon" aria-hidden="true"></span>
               <span class="sr-only">Next</span>
            </a>
         </div>

      </div>
   </div>





   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
   <script>
      $('#manjeet').owlCarousel({
         loop: true,
         // margin: 10,
         nav: false,
         autoplay: true,
         autoplayTimeout: 6000,
         // stagePadding: 100,
         dots: false,
         responsive: {
            0: {
               items: 1
            },
            600: {
               items: 1
            },
            1000: {
               items: 1
            }
         }
      })
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