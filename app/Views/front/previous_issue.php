<style>
   div#partner-carousel .item img.img-fluid {
      width: 255px !important;
      height: 184px;
   }
</style>


<?php if ($banner <> ''): ?>
   <div class="signle-banner">
      <div class="full-container">
         <div class="row">
            <?php foreach ($banner as $key => $item): ?>
               <div class="col-lg-12 col-12">
                  <div class="single-banner-img">
                     <img src="img/banner/<?= $item['image'] ?>" class="img-fluid" alt="About us">
                  </div>
                  <div class="single-banner-caption about-caption">
                     <div class="container">
                        <h2><?= $item['title'] ?></h2>
                     </div>
                  </div>
               </div>
         <?php endforeach;
         endif; ?>
         </div>
      </div>
   </div>
   <div class="container previous-issue-partner">
      <div class="row">
         <div class="col-lg-9 col-md-9 col-sm-9 col-12 bd-right">
            <div class="previous-issue-month">
               <div class="container">
                  <div class="row">
                     <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <?= $previous_issues_tbl[0]['content'] ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-lg-3 col-md-3 col-sm-3 col-12">
            <div class="">
               <h3 class="default-heading" style="text-align:left;">Our Partners</h3>
               <div class="owl-slider">
                  <div id="partner-carousel" class="owl-carousel">
                     <?php foreach ($slider_tbl as $key => $item): ?>
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
   </div>
   </div>
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
            <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
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