<?php if ($banner <> '') : ?>
<?php foreach ($banner as $key => $item) : ?>
<div class="full-container page-banner" style="background: url('<?=base_url().'assets/front/img/banner/'.$item['image'] ?>');">
   <div class="page-banner-caption"><h1><?= $item['title'] ?></h1></div>
</div>
<?php endforeach;
endif; ?>
 <?= view('layouts/front/home-tabs') ?>
<div class="container">
   <div class="row">
      <div class="col-md-2"></div>
      <div class="col-md-8">
         <form name="venueSeachForm" method="get" class="venue-search" id="venueform" enctype="multipart/form-data" action="<?= $FRONT_URL?>location" onsubmit="return venueSeachValidateForm();">
            <img class="search-icon" src="<?= base_url('assets/front/icons/search.svg') ?>"  alt="user Login" />
            <input class="form-control" type="text" value="" name="keyword" placeholder="Type venue name for searching..."  style="padding: 8px;">
            <input type="submit" class="color-default-btn button-submit" value="Submit" name="submit_search">
         </form>
      </div>
      <div class="col-md-2"></div>
   </div>
</div>


<div class="container hhj-venue-row">
   <div class="row">
      <div class="col-md-12">
         <div class="row">
            <?php foreach ($venue_tbl as $key => $item) : ?>
               <div class="col-md-3 hhj-venue-col">
                  <div class="hhj-venue-box">
                     <div class="explore-img">
                        <form name="venueSeachForm" method="get" id="venueform" enctype="multipart/form-data" action="<?= $FRONT_URL?>location">
                           <input class="form-control" type="hidden" value="<?= $item['venue_title'] ?>" name="keyword1">
                           <button type="submit" name="submit_search">
                              <img src="<?=base_url().'assets/front/img/venue/'.$item['image'] ?>" class="img-fluid" alt="">
                           </button>
                        </form>
                     </div>

                     <div class="venue-title">
                        <form name="venueSeachForm" method="get" id="venueform" enctype="multipart/form-data" action="<?= $FRONT_URL?>location">
                           <input class="form-control" type="hidden" value="<?= $item['venue_title'] ?>" name="keyword1">
                           <button type="submit" name="submit_search">
                              <?= $item['venue_title'] ?></button>
                        </form>
                     </div>

                     <div class="venue-btn">
                        <form name="venueSeachForm" method="get" id="venueform" enctype="multipart/form-data" action="<?= $FRONT_URL?>location">
                           <input class="form-control" type="hidden" value="<?= $item['venue_title'] ?>" name="keyword1">
                           <button type="submit" name="submit_search">VIEW DETAILS</button>
                        </form>
                     </div>
                  </div>
               </div>
            <?php endforeach;  ?>
         </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-3 col-12 bd-left d-none">
         <h3 class="default-heading">Our Partners</h3>
         <div class="owl-slider">
            <div id="partner-carousel" class="owl-carousel">
               <?php foreach ($slider_tbl as $key => $item) : ?>
                  <?php if ($item['type'] === null || $item['type'] === 'slider') : ?>
                  <div class="item">
                     <a href="<?= $item['weblink'] ?>" target="_blank">
                        <img class="img-fluid" src="img/slider/<?= $item['image'] ?>" alt="">
                     </a>
                  </div>
                  <?php endif; ?>
               <?php endforeach;  ?>
            </div>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
 
      // Form validation code will come here.
    function venueSeachValidateForm() {
    var keyword = document.forms["venueSeachForm"]["keyword"].value;
  //  alert(keyword);
   
    if (keyword == null || keyword == "") {
        alert("Please input venue location");
   return false;
    }	
   }
  
</script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script>
