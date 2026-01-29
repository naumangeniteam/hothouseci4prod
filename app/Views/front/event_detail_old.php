<link rel="stylesheet" id="evcal_cal_default-css" href="css/eventon_styles.css" type="text/css" media="all">
<div id="content_box" class="container">
   <article class="ss-full-width">
      <div id="content_box" >
         <article id="post-29493" class="post-29493 ajde_events type-ajde_events status-publish hentry">
            <div class="entry-content">
               <div class="eventon_main_section">
                  <div id="evcal_single_event_29493" class="ajde_evcal_calendar eventon_single_event evo_sin_page ">
                     <div class="evo-data" data-mapformat="roadmap" data-mapzoom="18" data-mapscroll="false" data-evc_open="1"></div>
                     <br/>
                     <?php   
                     	$datetoday=date('Y-m-d');
                     $dt=	DateTime::createFromFormat('Y-m-d', $datetoday);
                     		$monthtoday=  $dt->format('m');
                     $monthtoyr=  $dt->format('Y');
                     ?>
                     <div id="evcal_head" class="calendar_header">
                        <p id="evcal_cur" class="default-heading"> <?php   echo substr(strtoupper(date("F", mktime(0, 0, 0, $monthtoday, 10))), 0, 15);?>   <?php  echo $monthtoyr;?></p>
                     </div>
                     <br/>
                     <?php foreach($event_tbl as $key => $item):
						$getVenuName = $this->db->select('venue_title')->from('venue_tbl')->where('id', $item['venue_id'])->get()->row();
						$date = DateTime::createFromFormat('Y-m-d', $item["start_date"]);

                  $date_str = strtotime($item["start_date"]); 
                  $date_str1 = strtotime($item["start_date"]);
                 
                              $date_arr = explode("-", $date_str); // split the string into an array
                              $year = $date_arr[0]; // get the year (yy)
                              $month_num = $date_arr[1]; // get the month number (mm)
                              $day = $date_arr[2]; // get the day (dd)
                              $month_name = date("F",$date_str); // get the month name from the date string
                              $day_name = date('l', $date_str);
                          
                  
                         $month_name1 = date("F", mktime(0, 0, 0, $month_name, 10));
						$month_num1=  $date->format('m');
				
                        $date12 = strtotime($item["end_date"]);
                        $date_arr1 = explode("-", $date12);
                        $day1 = $date_arr1[2]; // get the day (dd)
                        $day_name1 = date('l', $date12);
 
						//$month_num12=  $date12->format('m');
                  $$month_num12 = date("F",$date_arr1);
						$month_name12 = date("F", mktime(0, 0, 0, $month_num12, 10));
						$month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));
						$event_start_time = date('h:i a', strtotime($item['event_start_time']));
						$event_end_time = date('h:i a', strtotime($item['event_end_time']));
                ?>
                     <div id="evcal_list" class="eventon_events_list evo_sin_event_list">
                        <div id="event_29493" class="eventon_list_event" event_id="29493" itemscope="" itemtype="https://schema.org/Event">
                           <div class="evo_event_schema" style="display:none">
                              <a href="" itemprop="url"></a> 
                              <item style="display:none" itemprop="location" itemscope="" itemtype="">
                                 <span itemprop="address" itemscope="" itemtype="">
                                    <item itemprop="streetAddress"> 
                                        </item>
                                 </span>
                              </item>
                           </div>
                         
                           <a id="evc_141496200013450" class="evcal_list_a desc_trig mul_val gmaponload evo_brooklyn" data-exlk="1" style="border-color: #ffa800" data-gmtrig="1" data-ux_val="none" data-gmstat="1">
                            <span class="evcal_cblock" bgcolor="#ffa800" smon="november" syr="2014"> <em class="evo_date"> <em class="evo_day"><?php   echo substr(strtoupper($date->format("l")),0 , 3);   ?></em> 
                            <span class="start"><?php   echo $date->format('d');   ?><em> <?php   echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3);   ?></em></span> <span class="end">-<?php   echo $date->format('d');   ?></span> </em> </span> 
                            <span class="evcal_desc"  latlon="1" latlng="40.7280118,-73.98129159999999" add_str="510 E 11th St, New York, NY 10009" data-location_name="11th Street Bar"> 
                                <span class="evcal_desc2 evcal_event_title" itemprop="name"><?=$item['event_title']?></span>
                               
                                 <span class="evcal_desc_info"> <em class="evcal_time"><?php echo  strtoupper($event_start_time);   ?>  - <?php if($item['time_permission'] !="Yes"){ echo  strtoupper($event_end_time);}   ?> 
                                 <span class="evcal_desc_info"> <em class="evcal_time"><?php echo  strtoupper($event_start_time);   ?> - <?php if($item['time_permission'] !="Yes"){ echo  strtoupper($event_end_time);}   ?>
                                </em> <em class="evcal_location"><i class="fas fa-map-marker-alt"></i> <?=$item['location_name']?></em> <em class="evcal_location event_location_name"><?=$item['location_address']?></em> </span> 
                                <span class="evcal_desc3">
                           <span class="evcal_event_types"> <em><i>Venue:</i></em> <em><?=$getVenuName->venue_title?></em> <i class="clear"></i> </span><b class="clear"></b>
                           <em class="evcal_cmd"><i>Phone Number:</i> <?=$item['phone_number']?></em>
                           </span> </span> <em class="clear"></em> </a>
                           <div class="event_description evcal_eventcard">
                              <div class="evorow bordb evo_metarow_time_location">
                                 <div class="tb">
                                    <div class="tbrow">
                                       <div class="evcal_col50 bordr">
                                          <div class="evcal_evdata_row evo_time">
                                             <span class="evcal_evdata_icons"><i class="fa fa-clock-o"></i></span>
                                             <div class="evcal_evdata_cell">
                                                <h3 class="evo_h3">Time</h3>
                                                <p>
                                             
                                                <?php   echo $day;   ?>  (<?php   echo substr(strtoupper($day_name),0 , 3);   ?>) <?php echo  strtoupper($event_start_time);   ?> -  <?php   echo $day1;   ?> (<?php   echo substr(strtoupper($day_name),0 , 3);  ?>) <?php if($item['time_permission'] !="Yes"){ echo  strtoupper($event_end_time);}   ?>                                
                                                </p>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="evcal_col50">
                                          <div class="evcal_evdata_row evo_location">
                                             <span class="evcal_evdata_icons"><i class="fa fa-map-marker"></i></span>
                                             <div class="evcal_evdata_cell">
                                                <h3 class="evo_h3">Location</h3>
                                                <p class="evo_location_name"><?=$item['location_name']?></p>
                                                <p><?=$item['location_address']?></p>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="clear"></div>
                                    </div>
                                 </div>
                              </div>
                              <!--<div class="evorow evcal_gmaps bordb evo_metarow_gmap" id="evc1414962000545858c78b2f113450_gmap"> </div>-->
                              <div class="evorow evcal_evdata_row bordb evcal_evrow_sm ">
                                 <span class="evcal_evdata_custometa_icons"><i class="fa fa-phone"></i></span>
                                 <div class="evcal_evdata_cell">
                                    <h3 class="evo_h3">Phone Number</h3>
                                    <div class="evo_custom_content evo_data_val">
                                       <p><?=$item['phone_number']?></p>
                                    </div>
                                 </div>
                              </div>
                              <div class="evorow bordb evo_metarow_time_location">
                                 <div class="tb">
                                    <div class="tbrow">
                                       <div class="clear"></div>
                                    </div>
                                 </div>
                              </div>
                              <div class="evorow bordb">
                                 <div class="tb">
                                    <div class="tbrow">
                                       <a class="evcal_col50 dark1 bordr evo_clik_row" href=<?=$item['website']  ?> target="_blank">
                                          <span class="evcal_evdata_row ">
                                             <span class="evcal_evdata_icons"><i class="fa fa-link"></i></span>
                                             <h3 class="evo_h3">Learn More</h3>
                                          </span>
                                       </a>
                                       <div class="evo_ics evcal_col50 dark1 evo_clik_row">
                                          <!--		 <a href="#" class="evo_ics_nCal" title="Add to your calendar">Calendar</a> -->
                                          <a href="https://www.google.com/calendar/event?
                                             action=TEMPLATE&text=Keyed+Up%21+ft.+Richard+Clements+Band&dates=20230102T190000Z/20230102T220000Z&details=&location=510+E+11th+St%2C+New+York%2C+NY+10009" target="_blank" rel="nofollow" class="evo_ics_gCal" title="Add to google calendar">GoogleCal </a>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="evorow evcal_evdata_row bordb evcal_evrow_sm getdirections">
                                 <form action="https://maps.google.com/maps" method="get" target="_blank">
                                    <input type="hidden" name="daddr" value="510 E 11th St, New York, NY 10009">
                                    <p>
                                       <input class="evoInput" type="text" name="saddr" placeholder="Type your address to get directions" value="">
                                       <button type="submit" class="evcal_evdata_icons evcalicon_9" title="Click here to get directions"><i class="fa fa-road"></i></button>
                                    </p>
                                 </form>
                              </div>
                              <!-- Go to www.addthis.com/dashboard to customize your tools -->
                              <div class="bordb evo_metarow_socialmedia evcal_evdata_row">
                                 <!-- 		  <div class="addthis_sharing_toolbox"></div> -->
                                 <div class="evo_sm FacebookShare"><a class="fb evo_ss" target="_blank" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" href="https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fhothousejazz.com%2Fevent_detail.php%3Feid%3D79056&p[title]=Keyed Up! ft. Richard Clements Band&p[summary]=&display=popup"><i class="fab fa-facebook-f"></i></a></div>
                                 <div class="evo_sm Twitter"><a class="tw evo_ss" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" href="https://twitter.com/share?original_referer=https%3A%2F%2Fhothousejazz.com%2Fevent_detail.php%3Feid%3D79056&text= Stephane Wrembel" title="Share on Twitter" rel="nofollow" target="_blank"><i class="fab fa-twitter"></i></a></div>
                                 <div class="evo_sm LinkedIn"><a class="li evo_ss" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=https%3A%2F%2Fhothousejazz.com%2Fevent_detail.php%3Feid%3D79056&title=Keyed Up! ft. Richard Clements Band&summary=" target="_blank"><i class="fab fa-linkedin-in"></i></a></div>
                                 <div class="clear"></div>
                              </div>
                           </div>
                           <div class="clear"></div>
                        </div>
                     </div>
                  </div>
                  <?php endforeach;  ?>
               </div>
            </div>
         </article>
      </div>
   </article>
</div>
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
