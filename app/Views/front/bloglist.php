<?php if($banner <> ''):?>
<div class="signle-banner">
   <div class="full-container">
      <div class="row">
	  <?php foreach($banner as $key => $item): ?>
         <div class="col-lg-12 col-12">
            <div class="single-banner-img">
               <img src="img/banner/<?=esc($item['image'])?>" class="img-fluid" alt="Venue">
            </div>
            <div class="single-banner-caption about-caption">
               <div class="container">
			   <h2><?=esc($item['title'])?></h2>
               </div>
            </div>
         </div>
		 <?php endforeach; endif; ?>
      </div>
   </div>
</div>
<?= view('layouts/front/home-tabs') ?>
<div class="home-artist">
   <div class="container">
       <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="history-search">
               <form name="seachform"  method="get" id="seachform"  enctype="multipart/form-data" action="<?= esc($FRONT_URL)?>search" onsubmit="return seachValidateForm();">
                  <ul>
                     <li>
                        <select class="form-control" name="id" style="height: 42px;">
                           <!--	<option value='1' <?php echo (isset($_GET['id']) && $_GET['id'] == 1)?'Selected':'' ?>>Event</option>--->
                           <option value='2' <?php echo (isset($_GET['id']) && $_GET['id'] == 2)?'Selected':'' ?>>Blog</option>
                        </select>
                     </li>
                     <li>
                        <div class="search-box">
                           <div class="form-group">
                              <input style="height: 42px;" type="text" class="form-control" value="<?= esc(service('request')->getGet('keyword') ?? '') ?>" name="keyword" placeholder="Search..">
                              <button type="submit" name="submit_search"><i class="fas fa-search"></i></button>
                           </div>
                        </div>
                     </li>
                  </ul>
               </form>
            </div>
         </div>
      </div>
  <?php
  //$val=krsort($authors);
   $i=1;
   foreach ($authors as $d):
      if($i%2==0): $blogAlign = 'left-align'; else: $blogAlign = 'right-align'; endif;
   ?>
      <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="<?php echo $blogAlign; ?>">
               <h4><a style="color:#e52b31!important;" href="<?php echo getCurrentControllerPath('blog_detail/'.$d->slug)?>"><?= esc($d->page_title) ?></a></h4>
                  <?= esc($d->home_content) ?>
             		<a style="color:#e52b31!important;" href="<?php echo getCurrentControllerPath('blog_detail/'.$d->slug)?>">Continue Reading...</a>
            </div>
         </div>
      </div>
	   <?php  $i++; endforeach; ?>
   </div>
</div>

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
    height: 47px;
    font-size: 22px;
}
.pagination{
-webkit-box-pack: center!important;
    -ms-flex-pack: center!important;
    justify-content: center!important;
}
.active{
   height: 47px;
    font-size: 22px;
    margin-top: 6px;
    padding-left: 7px;
}
</style>
</div>
</div><link rel="stylesheet" href="css/style1.css">

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
"><?=esc($this->session->getFlashdata('alert_success'))?></span>
               <?php  }else{
?>
               <span class="alert alert-error" style="width:100%;text-align: center;
               font-family: 'Oswald', sans-serif;
               font-weight: 500;
               font-style: italic;
               font-size: 25px;
               color: red;;
            "><?=esc($this->session->getFlashdata('alert_error'))?></span>
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