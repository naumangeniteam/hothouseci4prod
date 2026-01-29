 <div class="col-12 d-flex justify-content-center mb-3">
    <div class="align-content-center justify-content-center lang_toggle ms-3  d-flex d-lg-flex d-md-none-mobile">
       <button id="gridBtn" class="active">Grid</button>
       <button id="listBtn">List</button>
    </div>
 </div>
 <style>
    .lang_toggle button {
       background: #ffffff;
       border: 0;
       font-family: "Poppins";
       font-style: normal;
       font-weight: 500;
       font-size: 14px;
       line-height: 24px;
       display: flex;
       align-items: center;
       text-align: center;
       text-transform: capitalize;
       color: #000000;
       padding: 5px 14px;
       border: 1px solid #ffffff;
       border-radius: 48px;
    }

    .lang_toggle button.active {
       background: #FE0000;
       border: 1px solid #ffffff;
       border-radius: 48px;
       color: #ffffff;
       padding: 5px 20px;
    }

    .hidden {
       display: none !important;
    }


    .event-list-maindiv {
       background-color: white;
       border-radius: 20px;
       border: 2px solid black;
       padding-right: 0;
    }

    .event-list-card {
       display: flex;
    }

    .event-list-image img {
       width: 100%;
       border-radius: 20px;
       height: 200px;
    }


    .event-list-details p {
       margin: 0px;
       font-size: 15px;
       display: flex;
       gap: 10px;
       align-items: center;
       white-space: nowrap;
       overflow: hidden !important;
       text-overflow: ellipsis;
       margin-bottom: 5px;
    }

    .event-list-details p img {
       width: 18px;
       height: 22px;
    }

    .event-list-title {
       font-size: 22px;
    }

    .event-list-datetime img {
       width: 18px;
       height: 22px;
    }

    .event-list-details p a {
       display: flex;
       gap: 10px;
       align-items: normal;
       font-size: 15px;
       white-space: nowrap;
       overflow: hidden !important;
       text-overflow: ellipsis;
       color: #000000;
    }

    .view-listbtn {
       border-radius: 10px;
       color: #ffff;
       background: #FE0000;
       padding: 2px 10px;
       font-size: small;
    }

    .view-listbtn:hover {
       color: #ffff !important;
    }

    .event-list-date {
       font-size: larger;
       background: #000000;
       height: 116px;
       color: #ffff;
       display: flex;
       justify-content: center;
       align-items: center;
       border-top-right-radius: 10px;
    }

    .list-time {
       font-size: larger;
       background: #000000;
       color: #ffff;
       height: 116px;
       display: flex;
       justify-content: center;
       align-items: center;
       border-bottom-right-radius: 10px;
    }

    .paid-list {
       color: #FE0000;
    }

    .free-list {
       color: #2eb82e;
    }

    @media screen and (max-width: 550px) {
       .event-list-image img {
          height: 200px;
       }

       .event-list-title {
          font-size: 17px !important;
       }

       .event-list-details p {
          font-size: 14px;
          display: flex;
          gap: 7px;
       }

       .event-list-details p img {
          width: 20px;
          height: 25px;
       }

       .event-list-details p a {
          text-wrap: wrap;
          gap: 7px;
          font-size: 14px;
       }

       .view-listbtn {
          padding: 3px 8px;
       }

       .event-list-date {
          font-size: small;
          height: 110px;
       }

       .list-time {
          font-size: small;
          height: 114px;
       }
    }

    @media screen and (max-width: 450px) {
       .event-list-maindiv {
          padding: 0;
       }

       .event-list-image img {
          height: 165px;
       }

       .event-list-title {
          font-size: 15px !important;
       }

       .event-list-details-div {
          padding: 0;
       }

       .event-list-details p {
          font-size: 12px;
          gap: 2px;
          margin-bottom: 2px;
       }

       .event-list-details p img {
          width: 15px;
          height: 20px;
       }

       .event-list-details p a {
          gap: 2px;
          font-size: 12px;
          text-wrap: wrap;
       }

       .view-listbtn {
          font-size: 12px;
          padding: 3px 8px;
       }

       .event-list-date {
          font-size: smaller;
          height: 95px;
       }

       .list-time {
          font-size: smaller;
          height: 95px;
       }
    }

    @media screen and (max-width: 350px) {
       .event-list-maindiv {
          padding: 0;
       }

       .event-list-image {
          margin-bottom: 1.0rem !important;
          margin-top: 1.0rem !important;
       }

       .event-list-image img {
          height: 150px;
       }

       .event-list-date {
          font-size: smaller;
          height: 83px;
       }

       .list-time {
          font-size: smaller;
          height: 83px;
       }
    }
 </style>
 <!-- <div id="grid_event" class="w-100 ">
    <div class="row">
      <?php
      // Add this near the beginning of the file
      $datae = $datae ?? date('Y-m-d');  // Use the variable if set, otherwise use current date
      ?>
       <?php if (!empty($event_tbl)) :
            foreach ($event_tbl as $key => $items) :
               $item = $items['_source'];

               $currentdate = strtotime($datae);
               $evetntstart = strtotime($item["start_date"]);
               $evetntend = strtotime($item["end_date"]);


               $getVenuName =  $item['venue']['venue_name'];
               $date = DateTime::createFromFormat('Y-m-d', $datae);
               $sdate = DateTime::createFromFormat('Y-m-d', $item["start_date"]);
               $month_num1 =  $sdate->format('m');
               $month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));
               $event_start_time = date('h:i a', $item['start_date_time']);
               $event_start_time1 = date('h:i a', $item['start_date_time']);


               if ($currentdate == $evetntstart) {  //&& $currentdate <= $evetntend

         ?>

                <div class="col-md-4 hhj-sponsor-box cal-col">
                   <div class="hhj-sbox">
                      <div class="hhj-sprofile">
                        
                         <?php if (isset($item['cover_image']) && !empty($item['cover_image'])) : ?>
                            <img src="<?php echo base_url('assets/front/img/eventimage/' . $item['cover_image']); ?>" alt="Event Image" class="al-imgs">
                         <?php else : ?>
                            <img src="<?php echo !empty($item['cover_url']) ? $item['cover_url'] : base_url('assets/front/img/artistimage/events.jpg'); ?>" alt="Default Image" class="al-imgs">
                         <?php endif; ?>
                        
                      </div>

                      <div class="hhj-sinfo">
                         <?php
                           $url = $item['website'];
                           $url_display = preg_replace('/^(https?:\/\/)?(www\.)?/', '', $url);
                           ?>
                         <h3><?= $item['event_title'] ?></h3>
                         <p><img src="assets/front/icons/house.svg" alt="menu" /> <?= $getVenuName ?></p>
                         <p><img src="assets/front/icons/location.svg" alt="menu" /> <a class="loca-map" href="https://www.google.com/maps?q=<?= urlencode($item['locations']['location_name']) ?>" target="_blank"><?= htmlspecialchars($item['locations']['location_name']) ?></a></p>
                         <p>
                            <?php if (!empty($item['website'])) : ?>
                               <a class="loca-map web-link" href="<?= rtrim($url, '/') ?>" target="_blank"><img src="assets/front/icons/website.svg" alt="menu" /><?= rtrim($url_display, '/') ?></a>
                            <?php endif; ?>
                         </p>
                         <?php if (!empty($item['phone_number'])) : ?>
                            <p><img src="assets/front/icons/phone.svg" alt="menu" /><?= $item['phone_number'] ?> </p>
                         <?php endif; ?>
                      </div>

                      <div class="hhj-box-bottom">
                         <div class="event-cost">
                            <?php if (!empty($item['cover_charge'])) : ?>
                               <p class="paid">
                                  <img src="assets/front/icons/price.svg" alt="menu" />
                                  <?php echo $item['cover_charge']; ?>
                               </p>
                            <?php elseif (!empty($item['free_concert']) && $item['free_concert'] == 1) : ?>
                               <p class="free">
                                  <img src="assets/front/icons/price.svg" alt="menu" />
                                  FREE
                               </p>
                            <?php else : ?>
                               <p class="empty"></p>
                            <?php endif; ?>
                         </div>

                         <div class="hhj-sdate">
                            <h6>
                               <img src="assets/front/icons/date.svg" alt="menu" />
                               <?php echo $sdate->format('d'); ?>
                               <?php echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3); ?>
                               <?php echo substr(strtoupper($sdate->format("l")), 0, 3); ?>
                            </h6>
                            <h6><img src="assets/front/icons/time.svg" alt="menu" /><?php echo strtoupper($event_start_time); ?></h6>
                         </div>
                      </div>
                   </div>
                   <div class="hhj-buttons"><a href="<?php echo getCurrentControllerPath('event_detail/' . $items['_id']) ?>" class="online-btn">View event</a></div>
                </div>

                 <?php } elseif ($currentdate != $evetntstart) { ?>
                <div class="col-md-4 hhj-sponsor-box">
                   <div class="hhj-sbox">
                      <div class="hhj-sprofile">
                         
                         <?php if (isset($item['artist_image'])) : ?>
                            <img src="<?php echo base_url('assets/front/img/artistimage/' . $item['artist_image']); ?>" alt="Artist Image" class="al-imgs">
                         <?php else : ?>
                            <img src="<?php echo base_url('assets/front/img/artistimage/events.jpg'); ?>" alt="Default Image" class="al-imgs">
                         <?php endif; ?>
                     
                      </div>

                      <div class="hhj-sinfo">
                         <?php
                           $url = $item['website'];
                           $url_display = preg_replace('/^(https?:\/\/)?(www\.)?/', '', $url);
                           ?>
                         <h3><?= $item['event_title'] ?></h3>
                         <p><img src="assets/front/icons/house.svg" alt="menu" /> <?= $getVenuName ?></p>
                         <p><img src="assets/front/icons/location.svg" alt="menu" /> <a class="loca-map" href="https://www.google.com/maps?q=<?= urlencode($item['locations']['location_name']) ?>" target="_blank"><?= htmlspecialchars($item['locations']['location_name']) ?></a></p>
                         <p>
                            <?php if (!empty($item['website'])) : ?>
                               <a class="loca-map web-link" href="<?= rtrim($url, '/') ?>" target="_blank"><img src="assets/front/icons/website.svg" alt="menu" /><?= rtrim($url_display, '/') ?></a>
                            <?php endif; ?>
                         </p>
                         <?php if (!empty($item['phone_number'])) : ?>
                            <p><img src="assets/front/icons/phone.svg" alt="menu" /><?= $item['phone_number'] ?> </p>
                         <?php endif; ?>
                      </div>

                      <div class="hhj-box-bottom">
                         <div class="event-cost">
                            <?php if (!empty($item['cover_charge'])) : ?>
                               <p class="paid">
                                  <img src="assets/front/icons/price.svg" alt="menu" />
                                  <?php echo $item['cover_charge']; ?>
                               </p>
                            <?php elseif (!empty($item['free_concert']) && $item['free_concert'] == 1) : ?>
                               <p class="free">
                                  <img src="assets/front/icons/price.svg" alt="menu" />
                                  FREE
                               </p>

                            <?php else : ?>
                               <p class="empty"></p>
                            <?php endif; ?>
                         </div>
                         <div class="hhj-sdate">
                            <h6>
                               <img src="assets/front/icons/date.svg" alt="menu" />
                               <?php echo $sdate->format('d'); ?>
                               <?php echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3); ?>
                               <?php echo substr(strtoupper($sdate->format("l")), 0, 3); ?>
                            </h6>
                            <h6><img src="assets/front/icons/time.svg" alt="menu" /><?php echo strtoupper($event_start_time); ?></h6>
                         </div>
                      </div>
                   </div>
                   <div class="hhj-buttons"><a href="<?php echo getCurrentControllerPath('event_detail/' . $items['_id']) ?>" class="online-btn">View event</a></div>
                </div>
          <?php }
            endforeach;  ?>

       <?php else : ?>
          <h3 class="not-found">No events found</h3>
       <?php endif; ?>
    </div>
 </div> -->
 <div id="grid_event" class="w-100">

    <?php if (!empty($event_tbl)) :
         foreach ($event_tbl as $key => $items) :
            $item = $items['_source'];
          
            $currentdate = strtotime(date('Y-m-d'));
            $evetntstart = strtotime($item["start_date"]);

            $getVenuName = $item['venue']['venue_name'];
            $date = DateTime::createFromFormat('Y-m-d', $item["start_date"]);
            $month_num1 =  $date->format('m');
            $month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));
            $event_start_time = date('h:i', $item['start_date_time'])." PM";
            if ($currentdate == $evetntstart) {
      ?>

             <div class="container mt-5">
                <div class="row d-flex justify-content-center">
                   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-maindiv">
                      <div class="event-list-card">
                         <div class="col-4">
                            <div class="event-list-image mt-4 mb-4">
                               <?php if (isset($item['cover_image']) && !empty($item['cover_image'])) : ?>
                                  <img src="<?php echo base_url('assets/front/img/eventimage/' . $item['cover_image']); ?>" alt="Event Image" class="al-imgs">
                               <?php else : ?>
                                  <img src="<?php echo !empty($item['cover_url']) ? $item['cover_url'] : base_url('assets/front/img/artistimage/events.jpg'); ?>" alt="Default Image" class="al-imgs">
                               <?php endif; ?>
                            </div>
                         </div>
                         <div class="col-6 event-list-details-div">
                            <div class="event-list-details mt-4">
                               <?php
                                 $url = $item['website'];
                                 $url_display = preg_replace('/^(https?:\/\/)?(www\.)?/', '', $url);
                                 ?>
                               <h3 class="event-list-title"><?= $item['event_title'] ?></h3>
                               <p><img src="assets/front/icons/house.svg" alt="menu" /><?= $getVenuName ?></p>
                               <p><img src="assets/front/icons/location.svg" alt="menu" /> <a class="loca-map" href="https://www.google.com/maps?q=<?= urlencode($item['locations']['location_name']) ?>" target="_blank"><?= htmlspecialchars($item['locations']['location_name']) ?></a></p>
                               <p>
                                  <?php if (!empty($item['website'])) : ?>
                                     <a class="loca-map web-link" href="<?= rtrim($url, '/') ?>" target="_blank"><img src="assets/front/icons/website.svg" alt="menu" /><?= rtrim($url_display, '/') ?></a>
                                  <?php endif; ?>
                               </p>
                               <?php if (!empty($item['phone_number'])) : ?>
                                  <p><img src="assets/front/icons/phone.svg" alt="menu" /><?= $item['phone_number'] ?> </p>
                               <?php endif; ?>
                               <?php if (!empty($item['cover_charge'])) : ?>
                                  <p class="paid" style="color: #FE0000;">
                                     <img src="assets/front/icons/price.svg" alt="menu" />
                                     <?php echo $item['cover_charge']; ?>
                                  </p>
                               <?php elseif (!empty($item['free_concert']) && $item['free_concert'] == 1) : ?>
                                  <p class="free" style="color: #2eb82e;">
                                     <img src="assets/front/icons/price.svg" alt="menu" />
                                     FREE
                                  </p>
                               <?php else : ?>
                                  <p class="empty"></p>
                               <?php endif; ?>
                               <a href="<?php echo getCurrentControllerPath('event_detail/' . $items['_id']) ?>" class="btn btn-sm view-listbtn">View event</a>
                            </div>
                         </div>
                         <div class="col-2 p-0">
                            <div class="event-list-datetime ">
                               <div class="event-list-date col-12">

                                  <?php echo $date->format('d'); ?>
                                  <?php echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3); ?>
                                  <?php echo substr(strtoupper($date->format("l")), 0, 3); ?>
                               </div>
                               <div class="list-time col-12 mt-3">

                                  <?php echo strtoupper($event_start_time); ?>
                               </div>
                            </div>
                         </div>
                      </div>
                   </div>
                </div>


             </div>
          <?php } elseif ($currentdate != $evetntstart) { ?>
             <div class="container mt-5">
                <div class="row d-flex justify-content-center">
                   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 event-list-maindiv">
                      <div class="event-list-card">
                         <div class="col-4">
                            <div class="event-list-image mt-4 mb-4">
                               <?php if (isset($item['artist_image'])) : ?>
                                  <img src="<?php echo base_url('assets/front/img/artistimage/' . $item['artist_image']); ?>" alt="Artist Image" class="al-imgs">
                               <?php else : ?>
                                  <img src="<?php echo base_url('assets/front/img/artistimage/events.jpg'); ?>" alt="Default Image" class="al-imgs">
                               <?php endif; ?>
                            </div>
                         </div>
                         <div class="col-6 event-list-details-div">
                            <div class="event-list-details mt-4">
                               <?php
                                 $url = $item['website'];
                                 $url_display = preg_replace('/^(https?:\/\/)?(www\.)?/', '', $url);
                                 ?>
                               <h3 class="event-list-title"><?= $item['event_title'] ?></h3>
                               <p><img src="assets/front/icons/house.svg" alt="menu" /> <?= $getVenuName ?></p>
                               <p><img src="assets/front/icons/location.svg" alt="menu" /> <a class="loca-map" href="https://www.google.com/maps?q=<?= urlencode($item['locations']['location_name']) ?>" target="_blank"><?= htmlspecialchars($item['locations']['location_name']) ?></a></p>
                               <p>
                                  <?php if (!empty($item['website'])) : ?>
                                     <a class="loca-map web-link" href="<?= rtrim($url, '/') ?>" target="_blank"><img src="assets/front/icons/website.svg" alt="menu" /><?= rtrim($url_display, '/') ?></a>
                                  <?php endif; ?>
                               </p>
                               <?php if (!empty($item['phone_number'])) : ?>
                                  <p><img src="assets/front/icons/phone.svg" alt="menu" /><?= $item['phone_number'] ?> </p>
                               <?php endif; ?>

                               <?php if (!empty($item['cover_charge'])) : ?>
                                  <p class="paid" style="color: #FE0000;">
                                     <img src="assets/front/icons/price.svg" alt="menu" />
                                     <?php echo $item['cover_charge']; ?>
                                  </p>
                               <?php elseif (!empty($item['free_concert']) && $item['free_concert'] == 1) : ?>
                                  <p class="free" style="color: #2eb82e;">
                                     <img src="assets/front/icons/price.svg" alt="menu" />
                                     FREE
                                  </p>
                               <?php else : ?>
                                  <p class="empty"></p>
                               <?php endif; ?>
                               <a href="<?php echo getCurrentControllerPath('event_detail/' . $items['_id']) ?>" class="btn btn-sm view-listbtn">View event</a>
                            </div>
                         </div>
                         <div class="col-2 p-0">
                            <div class="event-list-datetime ">
                               <div class="event-list-date col-12">

                                  <?php echo $date->format('d'); ?>
                                  <?php echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3); ?>
                                  <?php echo substr(strtoupper($date->format("l")), 0, 3); ?>
                               </div>
                               <div class="list-time col-12 mt-3">

                                  <?php echo strtoupper($event_start_time); ?>
                               </div>
                            </div>
                         </div>
                      </div>
                   </div>
                </div>
             </div>

       <?php }
         endforeach;  ?>

    <?php else : ?>
       <h3 class="not-found">No events found</h3>
    <?php endif; ?>

 </div>


 <div id="list_event" class="w-100 hidden">
    <?php if (!empty($event_tbl)) : ?>
       <div class="container-fluid">
          <div class="row d-flex justify-content-center">
             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="table table-striped table-bordered">
                   <thead>
                      <tr>
                         <th style="width:10%">Start Date</th>
                         <th>Time</th>
                         <th>Event Title</th>
                         <th>Location</th>
                         <th>Venue</th>
                         <th>Cover Charge</th>
                         <th>Phone</th>
                         <th>Website</th>
                      </tr>
                   </thead>
                   <tbody>
                      <?php foreach ($event_tbl as $key => $items) :
                           $item = $items['_source'];
                           $currentdate = strtotime(date('Y-m-d'));
                           $eventstart = strtotime($item["start_date"]);
                           $date = DateTime::createFromFormat('Y-m-d', $item["start_date"]);
                           $event_start_time = date('h:i', $item['start_date_time'])." PM";
                           $getVenueName = $item['venue']['venue_name'];
                           if ($currentdate == $evetntstart) {  //&& $currentdate <= $evetntend

                        ?>

                            <tr>
                               <td>
                                  <?php echo $sdate->format('d'); ?>
                                  <?php echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3); ?>
                                  <?php echo substr(strtoupper($sdate->format("l")), 0, 3); ?>
                               </td>
                               <td><?= strtoupper($event_start_time) ?></td>
                               <td><?= htmlspecialchars($item['event_title']) ?></td>
                               <td><?= htmlspecialchars($getVenueName) ?></td>
                               <td>
                                  <a class="loca-map" href="https://www.google.com/maps?q=<?= urlencode($item['locations']['location_name']) ?>" target="_blank">
                                     <?= htmlspecialchars($item['locations']['location_name']) ?>
                                  </a>
                               </td>
                               <td>
                                  <?php if (!empty($item['cover_charge'])) : ?>
                                     <span style="color: #FE0000;"><?= htmlspecialchars($item['cover_charge']) ?></span>
                                  <?php elseif (!empty($item['free_concert']) && $item['free_concert'] == 1) : ?>
                                     <span style="color: #2eb82e;">FREE</span>
                                  <?php else : ?>
                                     <span class="empty"></span>
                                  <?php endif; ?>
                               </td>
                               <td><?= !empty($item['phone_number']) ? htmlspecialchars($item['phone_number']) : '-' ?></td>
                               <td>
                                  <?php if (!empty($item['website'])) :
                                       $url = $item['website'];
                                       $url_display = preg_replace('/^(https?:\/\/)?(www\.)?/', '', $url);
                                    ?>
                                     <a href="<?= rtrim($url, '/') ?>" target="_blank"><?= htmlspecialchars(rtrim($url_display, '/')) ?></a>
                                  <?php endif; ?>
                               </td>
                               <td>
                                  <a href="<?= getCurrentControllerPath('event_detail/' . $items['_id']) ?>" class="btn btn-sm btn-primary">More Details...</a>
                               </td>
                            </tr>
                         <?php } elseif ($currentdate != $evetntstart) { ?>
                            <tr>
                               <td>
                                  <?php echo $sdate->format('d'); ?>
                                  <?php echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3); ?>
                                  <?php echo substr(strtoupper($sdate->format("l")), 0, 3); ?>
                               </td>
                               <td><?= strtoupper($event_start_time) ?></td>
                               <td><?= htmlspecialchars($item['event_title']) ?></td>
                               <td><?= htmlspecialchars($getVenueName) ?></td>
                               <td>
                                  <a class="loca-map" href="https://www.google.com/maps?q=<?= urlencode($item['locations']['location_name']) ?>" target="_blank">
                                     <?= htmlspecialchars($item['locations']['location_name']) ?>
                                  </a>
                               </td>
                               <td>
                                  <?php if (!empty($item['cover_charge'])) : ?>
                                     <span style="color: #FE0000;"><?= htmlspecialchars($item['cover_charge']) ?></span>
                                  <?php elseif (!empty($item['free_concert']) && $item['free_concert'] == 1) : ?>
                                     <span style="color: #2eb82e;">FREE</span>
                                  <?php else : ?>
                                     <span class="empty"></span>
                                  <?php endif; ?>
                               </td>
                               <td><?= !empty($item['phone_number']) ? htmlspecialchars($item['phone_number']) : '-' ?></td>
                               <td>
                                  <?php if (!empty($item['website'])) :
                                       $url = $item['website'];
                                       $url_display = preg_replace('/^(https?:\/\/)?(www\.)?/', '', $url);
                                    ?>
                                     <a href="<?= rtrim($url, '/') ?>" target="_blank"><?= htmlspecialchars(rtrim($url_display, '/')) ?></a>
                                  <?php endif; ?>
                               </td>



                               <td>
                                  <a href="<?= getCurrentControllerPath('event_detail/' . $items['_id']) ?>" class="btn btn-sm btn-primary">More Details...</a>
                               </td>
                            </tr>
                      <?php }
                        endforeach; ?>
                   </tbody>
                </table>
             </div>
          </div>
       </div>
    <?php else : ?>
       <h3 class="not-found text-center">No events found</h3>
    <?php endif; ?>
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
 <!-- JavaScript to Handle Toggle -->
 <script>
    // Get references to buttons and divs
    const gridBtn = document.getElementById('gridBtn');
    const listBtn = document.getElementById('listBtn');
    const grid = document.getElementById('grid_event');
    const list = document.getElementById('list_event');

    // Function to handle Grid view
    function showGridView() {
       list.classList.add('hidden'); // Show list
       grid.classList.remove('hidden'); // Hide list
       gridBtn.classList.add('active'); // Add active to Grid button
       listBtn.classList.remove('active'); // Remove active from List button
       $('#view_type').val(1);
    }

    // Function to handle List view
    function showListView() {
       list.classList.remove('hidden'); // Show list
       grid.classList.add('hidden'); // Hide grid
       listBtn.classList.add('active'); // Add active to List button
       gridBtn.classList.remove('active'); // Remove active from Grid button
       $('#view_type').val(2);
    }

    //     gridBtn.addEventListener('click', function () {
    //          $('#view_type').val(1);
    //          renderView(1); // Use cached data to render grid view
    //       });
    //       listBtn.addEventListener('click', function () {
    //     $('#view_type').val(2);
    //     renderView(2); // Use cached data to render list view
    // });
    // Add event listeners for buttons
    gridBtn.addEventListener('click', showGridView);
    listBtn.addEventListener('click', showListView);


    //  showGridView();
 </script>