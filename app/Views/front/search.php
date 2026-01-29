<link rel="stylesheet" id="evcal_google_fonts-css" href="https://hothousejazz.com/styles/css.css" type="text/css" media="screen">
<link rel="stylesheet" id="evcal_cal_default-css" href="https://hothousejazz.com/styles/eventon_styles.css" type="text/css" media="all">
<link rel="stylesheet" id="evo_font_icons-css" href="https://hothousejazz.com/fonts/font-awesome.css" type="text/css" media="all">
<link rel="stylesheet" id="evo_dv_styles-css" href="https://hothousejazz.com/styles/dv_styles.css" type="text/css" media="all">
<script type="text/javascript" src="https://hothousejazz.com/js/jquery-2.js"></script>
<link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
<body id ="blog" class="home page page-id-2 page-template-default main">
   <div id="fb-root"></div>
   <?php
   //print_r($event_tbl_list->event_title);die();
   // print_r($blog_tbl->page_title);die();
      //if($blog_tbl->page_title !="" || $event_tbl->event_title !=""){
       if($Get_id == 1 && $event_tbl[0]['event_title'] !="" ){  ?>
   <div id="content_box" >
      <div id="content" class="hfeed">
         <div id="post-23" class="post-23 page type-page status-publish hentry g post">
            <header>
               <h1 class="title">Event Search</h1>
            </header>
            <div class="post-content box mark-links">
               <span style="color: #7b7b7b;">
                  <div id="content_box">
                     <div id="content" class="hfeed">
                        <div id="post-23" class="post-23 page type-page status-publish hentry g post">
                           <div class="post-content box mark-links">
                              <p><span class="shortcode"></span></p>
                              <p><span style="color: #7b7b7b;"></span></p>
                              <div id="evcal_calendar_1" class="ajde_evcal_calendar   evoDV">
                                 <div class="evo-data" data-cyear="2015" data-cmonth="08" data-runajax="1" data-evc_open="0" data-cal_ver="2.2.14" data-mapscroll="false" data-mapformat="roadmap" data-mapzoom="18" data-ev_cnt="0" data-sort_by="sort_date" data-filters_on="false" data-range_start="0" data-range_end="0" data-send_unix="0" data-ux_val="00" data-accord="0"></div>
                                 <div id="eventon_loadbar_section">
                                    <div id="eventon_loadbar"></div>
                                 </div>
                                 <div id="evcal_list" class="eventon_events_list ">
                                    <div id="event_11865" class="eventon_list_event">
                                       <?php 
                                    //   print_r($event_tbl);die;
                                          foreach($event_tbl as $key => $item):
                                                        $getVenuName = $this->db->select('venue_title')->from('venue_tbl')->where('id', $item['venue_id'])->get()->row();
                                                        $date = DateTime::createFromFormat('Y-m-d', $item["start_date"]);
                                                        $month_num1=  $date->format('m');
                                                        $month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));
                                                        $date12 = DateTime::createFromFormat('Y-m-d', $item["end_date"]);
                                                        $month_num12=  $date12->format('m');
                                                        $month_name12 = date("F", mktime(0, 0, 0, $month_num12, 10));
                                                        $month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));
                                                        $event_start_time = date('h:i a', strtotime($item['event_start_time']));
                                                        $event_end_time = date('h:i a', strtotime($item['event_end_time']));
                                                        ?>
                                       <a id="evc_141314400011865" class="evcal_list_a desc_trig mul_val  evo_midtown-manhattan" data-exlk="1" href="event_detail.php?eid=80136" > <span class="evcal_cblock" bgcolor="#ffa800" smon="august" syr="2015"> <em class="evo_date"> <em class="evo_day"><?php   echo substr(strtoupper($date->format("l")),0 , 3);   ?></em> <span class="start"> <em><?php   echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3);   ?></em> </span> <span class="end"> - <?php   echo $date->format('d');   ?></span> </em> <em class="evo_time"> <em class="evo_day"><?php   echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3);   ?> <?php   echo $date->format('d');   ?> - <?php   echo $date->format('Y');   ?></em> <span class="start"><?php echo  strtoupper($event_start_time);   ?> </span>
                                       <span class="end"> - <?php echo  strtoupper($event_end_time);   ?> </span>
                                       </em><em class="clear"></em> </span> <span class="evcal_desc"> <span class="evcal_desc2 evcal_event_title" itemprop="name"> <?=$item['event_title']?></span> <span class="evcal_desc_info"></span> <span class="evcal_desc_info"> <em class="clear"></em> <em class="evcal_time"><?php echo  strtoupper($event_start_time);   ?> - <?php echo  strtoupper($event_end_time);   ?> </em> <em class="evcal_location"><?=$item['location_name']?></em> <em class="evcal_location event_location_name"><?=$item['location_address']?></em> </span> <span class="evcal_desc3"><?=$getVenuName->venue_title?><b class="clear"></b>
                                       <em class="evcal_cmd"><i>Phone Number:</i> <?=$item['phone_number']?> </em>
                                       </span> </span> <em class="clear"></em> </a>
                                       <?php endforeach;  ?>
                                       <div class="clear"></div>
                                    </div>
                                 </div>
                                 <div class="clear"></div>
                              </div>
                              <br>
                              <p></p>
                              <p><span class="pln">&nbsp;</span></p>
                              <p><span class="shortcode">&nbsp;</span></p>
                              <p>&nbsp;</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </span>
               </p>
               <p>&nbsp;</p>
            </div>
            <!--.post-content box mark-links--> 
         </div>
         <!-- You can start editing here. --> 
      </div>
   </div>
   <?php   }
      
       else if ($Get_id ==2 && $blog_tbl[0]['page_title'] !=""){ 
        // print_r($event_tbl_list->event_title);die(); ?>
   <article class="article">
      <div id="content_box" >
         <div id="content" class="hfeed">
            <div class='homeblogdata'>
               <h4><a href="<?php echo getCurrentControllerPath('blog_detail/'.$blog_tbl[0]['slug'])?>"><?=$blog_tbl[0]['page_title']?></a></h4>
               <p><?=$blog_tbl[0]['home_content']?></p>
               <a  href="<?php echo getCurrentControllerPath('blog_detail/'.$blog_tbl[0]['slug'])?>">Continue Reading...</a>	
            </div>
         </div>
      </div>
   </article>
   <style>
      .homeblogdata {max-height:500Px; overflow:hidden}
      .homeblogdata div {display:inline-block !important;}
   </style>
   <aside class="sidebar c-4-12">
      <div id="sidebars" class="g">
         <div class="sidebar">
            <ul class="sidebar_list">
               <li class="widget widget-sidebar">
                  <h3>Hot House Jazz e-Alerts</h3>
                  <!-- Form by MailChimp for WordPress plugin v2.2.9 - https://mc4wp.com/ -->
                  <div id="mc4wp-form-1" class="form mc4wp-form">
                     <!-- Begin MailChimp Signup Form -->
                     <link href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css">
                     <style type="text/css">
                        #mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
                        /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
                        We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
                     </style>
                    
<div id="mc_embed_signup">
<form action="//hothousejazz.us3.list-manage.com/subscribe/post?u=53e9cf8c7d892b08a99c1e421&amp;id=f15ad682db" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
    <div id="mc_embed_signup_scroll">
	<h2>Subscribe to our mailing list</h2>
<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
<div class="mc-field-group">
	<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
</label>
	<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
</div>
<div class="mc-field-group">
	<label for="mce-FNAME">First Name </label>
	<input type="text" value="" name="FNAME" class="" id="mce-FNAME">
</div>
	<div id="mce-responses" class="clear">
		<div class="response" id="mce-error-response" style="display:none"></div>
		<div class="response" id="mce-success-response" style="display:none"></div>
	</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
    <div style="position: absolute; left: -5000px;"><input type="text" name="b_53e9cf8c7d892b08a99c1e421_f15ad682db" tabindex="-1" value=""></div>
    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
    </div>
</form>
</div> <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
                     <!--End mc_embed_signup-->
                  </div>
                  <!-- / MailChimp for WP Plugin -->
               </li>
               <!--<li class="widget widget-sidebar">
                  <div id='evcal_widget'>
                     <div id="evcal_calendar_" class="ajde_evcal_calendar  ul">
                       <div class="evcal_month_line">
                           <p><?php  
                           /* $month_num51= date('m');
                           
                           echo strtoupper(date("F", mktime(0, 0, 0, $month_num51, 10)));*/   ?> </p>
                        </div>
                        <div class="eventon_events_list">
                           <div class="eventon_events_list">
                                <div id="event_11865" class="eventon_list_event">
                                  <?php 
                                      /*foreach($event_tbl_list as $key => $item):
                                        $getVenuName = $this->db->select('venue_title')->from('venue_tbl')->where('id', $item['venue_id'])->get()->row();
                                        $date = DateTime::createFromFormat('Y-m-d', $item["start_date"]);
                                        $month_num1=  $date->format('m');
                                        $month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));
                                        $date12 = DateTime::createFromFormat('Y-m-d', $item["end_date"]);
                                        $month_num12=  $date12->format('m');
                                        $month_name12 = date("F", mktime(0, 0, 0, $month_num12, 10));
                                        $month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));
                                        $event_start_time = date('h:i a', strtotime($item['event_start_time']));
                                        $event_end_time = date('h:i a', strtotime($item['event_end_time']));*/
                                    ?>
                                 <a id="evc_141314400011865" class="evcal_list_a desc_trig mul_val  evo_midtown-manhattan" data-exlk="1" href="event_detail.php?eid=80135" style="border-color: #ffa800" data-gmtrig="1" data-ux_val="4">
                                    <span class="evcal_cblock" bgcolor="#ffa800" smon="january" syr="1970">
                                       <em class="evo_date">
                                       <em class="evo_day"><?php   echo substr(strtoupper($date->format("l")),0 , 3);   ?></em>
                                       <span class="start"><em><?php   echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3);   ?></em>
                                       </span>
                                       <span class="end"> - <?php   echo $date->format('d');   ?></span>
                                       </em>
                                       <em class="evo_time">
                                        
                                          <span class="start"><?php echo  strtoupper($event_start_time);   ?></span>
                                          <span class="end"> - <?php echo  strtoupper($event_end_time);   ?></span>
                                       </em>
                                       <em class="clear"></em>
                                    </span>
                                    <span class="evcal_desc">
                                    <span class="evcal_desc2 evcal_event_title" itemprop="name"><?=$item['event_title']?></span>
                                    <span class="evcal_desc_info">
                                    <em class="evcal_location""><?=$item['location_name']?></em>
                                    <em class="evcal_location event_location_name"><?=$item['location_address']?></em>
                                    </span>
                                    <span class="evcal_desc3"><b class="clear"></b>
                                    <?=$getVenuName->venue_title?><em class="evcal_cmd"><i>Phone Number:</i> <?=$item['phone_number']?> </em> 
                                    </span>
                                    </span><em class="clear"></em>
                                 </a>
                                 <?php //endforeach;  ?>
                                 <div class="clear"></div>
                              </div>
                           </div>
                           <div class="clear"></div>
                        </div>
                     </div>
                  </div>
               </li>-->
               <li class="widget widget-sidebar">
                  <h3>Like Us!</h3>
                  <div class="fb-page" data-href="https://www.facebook.com/hothousejazz" data-tabs="timeline" data-height="720" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                     <div class="fb-xfbml-parse-ignore">
                        <blockquote cite="https://www.facebook.com/hothousejazz"><a href="https://www.facebook.com/hothousejazz">Hot House Jazz Magazine</a></blockquote>
                     </div>
                  </div>
               </li>
            </ul>
         </div>
      </div>
      <!--sidebars--> 
   </aside>
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
                     <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button" style="    margin-left: 313px;
    margin-top: 0px;
">
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
<?php    }else{ ?>
<article class="article">
   <h3 style="margin-top: 5%;margin-bottom: 4%;padding: 10px;" class="default-heading">Search Data Not Found</h3>
   <p style="color: red;
      margin-bottom: 219px;
      margin-left: 145px;">No search data found. Did you enter wrong spelling of any word? Only Blog name and Event  name are accepted in search field.   </p>
</article>



   <?php } ?>
