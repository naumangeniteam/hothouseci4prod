<div class="hhj-event-banner">
   <?php if (isset($event_tbl[0]['event_image']) && $event_tbl[0]['event_image'] != '') { ?>
      <img src="<?= base_url('assets/front/img/eventimage/' . $event_tbl[0]['event_image']); ?>" class="img-fluid w-100" style="object-fit: contain; height: 400px;" alt="Cover image">
   <?php } elseif (isset($event_tbl[0]['cover_url']) && $event_tbl[0]['cover_url'] != '') { ?>
      <img src="<?= $event_tbl[0]['cover_url'] ?>" class="img-fluid w-100" style="object-fit: contain; height: 400px;" alt="Cover image">
   <?php } else { ?>
      <img src="<?= base_url('assets/front/img/artistimage/event-dummy.jpg'); ?>" class="img-fluid w-100" style="object-fit: contain; height: 400px;" alt="home-banner">
   <?php } ?>
</div>

<?= view('layouts/front/home-tabs'); ?>

<div class="container hhj-single-event">
   <div class="row">
      <?php foreach ($event_tbl as $item): ?>
         <?php 
          $db = \Config\Database::connect();
          $builder = $db->table('venue_tbl'); 
          $venue = $builder->select('venue_title')->where('id', $item['venue_id'])->get()->getRow();
          $venue_title = $venue ? $venue->venue_title : 'No Venue Found';
          $getVenuName = [
           'venue_title' => $venue ? $venue->venue_title : 'No Venue Found',
       ];
            $date = new DateTime($item['start_date']);
            $date_str = strtotime($item['start_date']);
            $year = date('Y', $date_str);
            $month_num = date('m', $date_str);
            $day = date('d', $date_str);
            $month_name = date('F', $date_str);
            $day_name = date('l', $date_str);
            $event_start_time = date('h:i A', strtotime($item['event_start_time']));
            $event_end_time = date('h:i A', strtotime($item['event_end_time']));
         ?>
         <div class="col-md-2"></div>
         <div class="col-md-8">
            <h2><?= esc($item['event_title']); ?></h2>
            <div id="countdown" class="d-none">
               <ul>
                  <li><span id="days"></span> days</li>
                  <li><span id="hours"></span> Hours</li>
                  <li><span id="minutes"></span> Minutes</li>
                  <li><span id="seconds"></span> Seconds</li>
               </ul>
            </div>
            <div class="events-details">
               <div class="event-descriptions">
                  <h3>Event Details:</h3>
                  <?php $item['description']; ?>
                  <p><?= !empty($item['description']) ? strip_tags($item['description']) : ' '; ?></p>
               </div>
               <ul>
                  <li><img src="<?= base_url('assets/front/icons/date.svg') ?>" class="img-fluid" alt="event-icon"><b>Date:</b> <?= $day . ' ' . strtoupper(substr($month_name, 0, 3)) . ' ' . $year; ?></li>
                  <!-- <li><b>Time:</b> <?= strtoupper($event_start_time); ?> - <?= strtoupper($event_end_time); ?></li> -->
                  <li>
                     <img src="<?= base_url('assets/front/icons/time.svg') ?>" class="img-fluid" alt="event-icon">
                     <span>
                        <b>Time:</b> 
                       
                        (<?php echo strtoupper(substr(date('l', strtotime($item['start_date'])), 0, 3)); ?>) 
                        <?php echo strtoupper(date('h:i A', strtotime($item['event_start_time']))); ?> 
                        - 
                       
                        (<?php echo strtoupper(substr(date('l', strtotime($item['end_date'])), 0, 3)); ?>) 
                        <?php echo strtoupper(date('h:i A', strtotime($item['event_end_time']))); ?>
                     </span>
                  </li>
                  <li><img src="<?= base_url('assets/front/icons/house.svg') ?>" class="img-fluid" alt="event-icon"><b>Venue:</b> <?= esc($venue_title); ?></li>
                  <li><img src="<?= base_url('assets/front/icons/location.svg') ?>" class="img-fluid" alt="event-icon"><b>Location:</b> <?= esc($item['location_name'] ?? ''); ?>, <?= esc($item['location_address'] ?? ''); ?></li>
                  <?php if (!empty($item['phone_number'])): ?>
                     <li><img src="<?= base_url('assets/front/icons/phone.svg') ?>" class="img-fluid" alt="event-icon"><b>Phone:</b> <?= esc($item['phone_number']); ?></li>
                  <?php endif; ?>
                  <?php if (isset($item['jazz_types']) && !empty($item['jazz_types'])): ?>
                     <ul class="jazz-list">
                        <li>
                           <img src="<?= base_url('assets/front/icons/jazz.svg') ?>" class="img-fluid" alt="event-icon">
                           <span><b>Type of jazz:</b></span>
                           <?php foreach ($item['jazz_types'] as $index => $jazz_type): ?>
                              <span class="jazz-type"><?php echo htmlspecialchars($jazz_type); ?></span>
                              <?php if ($index < count($item['jazz_types']) - 1): ?>
                                 <span>,</span>
                              <?php endif; ?>
                           <?php endforeach; ?>
                        </li>
                     </ul>
                  <?php endif; ?>
                  <?php if (isset($item['event_types']) && !empty($item['event_types'])) : ?>
                     <li><img src="<?= base_url('assets/front/icons/music.svg') ?>" class="img-fluid" alt="event-icon"><span><b>Event type:</b> <?= $item['event_types'] ?></span></li>
                  <?php endif; ?>
                  <?php if (isset($item['event_tags']) && !empty($item['event_tags'])) : ?>
                     <li><img src="<?= base_url('assets/front/icons/tag.svg') ?>" class="img-fluid" alt="event-icon"><span><b>Event tags:</b>
                           <?php if (!empty($item['event_tags'])) : ?>
                              <?php foreach ($item['event_tags'] as $index => $tag) : ?>
                                 <span><?= htmlspecialchars($tag) ?></span>
                                 <?php if ($index !== array_key_last($item['event_tags'])) echo ', '; ?>
                              <?php endforeach; ?>
                           <?php endif; ?>
                        </span></li>
                  <?php endif; ?>
                  <?php if (isset($item['website']) && !empty($item['website'])) : ?>
                     <li>
                        <img src="<?= base_url('assets/front/icons/website.svg') ?>" class="img-fluid" alt="event-icon">
                        <span><a href="<?= $item['website'] ?>" target="_blank"><?= $item['website'] ?></a></span>
                     </li>
                  <?php endif; ?>

                  <?php if (isset($item['video1']) && !empty($item['video1'])) : ?>
                     <li>
                        <img src="<?= base_url('assets/front/icons/media.svg') ?>" class="img-fluid" alt="event-icon">
                        <span><a href="<?= $item['video1'] ?>" target="_blank">Watch Video 01</a></span>
                     </li>
                  <?php endif; ?>

                  <?php if (isset($item['video2']) && !empty($item['video2'])) : ?>
                     <li>
                        <img src="<?= base_url('assets/front/icons/media.svg') ?>" class="img-fluid" alt="event-icon">
                        <span><a href="<?= $item['video2'] ?>" target="_blank">Watch Video 02</a></span>
                     </li>
                  <?php endif; ?>

                  <?php if (isset($item['video3']) && !empty($item['video3'])) : ?>
                     <li>
                        <img src="<?= base_url('assets/front/icons/media.svg') ?>" class="img-fluid" alt="event-icon">
                        <span><a href="<?= $item['video3'] ?>" target="_blank">Watch Video 03</a></span>
                     </li>
                  <?php endif; ?>
              
               </ul>
               <div class="get-direction">
                  <form action="https://maps.google.com/maps" method="get" target="_blank">
                     <input type="hidden" name="daddr" value="510 E 11th St, New York, NY 10009">
                     <input class="evoInput" type="text" name="saddr" placeholder="Type your address" value="">
                     <button type="submit" class="submit" title="Click here to get directions">Get directions</button>
                  </form>
               </div>

               <div class="buy-links">
                  <ul>
                     <?php if (isset($item['buy_now_link']) && !empty($item['buy_now_link'])) : ?>
                        <li class="buy"><a href="<?= $item['buy_now_link'] ?>" target="_blank">Buy Now</a></li>
                     <?php endif; ?>
                     <?php if (isset($item['reserve_seat_link']) && !empty($item['reserve_seat_link'])) : ?>
                        <li class="reserve"><a href="<?= $item['reserve_seat_link'] ?>" target="_blank">Reserve Now</a></li>
                     <?php endif; ?>
                  </ul>
               </div>
               <h4 class="d-none">Share Event With social media</h4>
               <div class="social-media d-none">
                  <div class="evo_sm FacebookShare"><a class="fb evo_ss" target="_blank" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" href="https://www.facebook.com/sharer.php?s=100&p[url]=https%3A%2F%2Fhothousejazz.com%2Event_detail.php%3Feid%3D79056&p[title]=Keyed Up! ft. Richard Clements Band&p[summary]=&display=popup"><img src="<?= base_url('assets/front/icons/facebook.svg') ?>" class="img-fluid" alt="event-icon"></a></div>
                  <div class="evo_sm Twitter"><a class="tw evo_ss" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" href="https://twitter.com/share?original_referer=https%3A%2F%2Fhothousejazz.com%2Event_detail.php%3Feid%3D79056&text= Stephane Wrembel" title="Share on Twitter" rel="nofollow" target="_blank"><img src="<?= base_url('assets/front/icons/twitter.svg') ?>" class="img-fluid" alt="event-icon"></a></div>
                  <div class="evo_sm LinkedIn"><a class="li evo_ss" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=https%3A%2F%2Fhothousejazz.com%2Event_detail.php%3Feid%3D79056&title=Keyed Up! ft. Richard Clements Band&summary=" target="_blank"><img src="<?= base_url('assets/front/icons/linkedin.svg') ?>" class="img-fluid" alt="event-icon"></a></div>
               </div>
            </div>
         </div>
         <div class="col-md-2"></div>
      <?php endforeach; ?>
   </div>
</div>

<!-- Event countdown JS [added by imran] --->
<script>
   (function() {
      const second = 1000,
         minute = second * 60,
         hour = minute * 60,
         day = hour * 24;

      let today = new Date(),
         dd = String(today.getDate()).padStart(2, "0"),
         mm = String(today.getMonth() + 1).padStart(2, "0"),
         yyyy = today.getFullYear(),
         nextYear = yyyy + 1,
         dayMonth = "09/30/",
         eventcount = dayMonth + yyyy;

      today = mm + "/" + dd + "/" + yyyy;
      if (today > eventcount) {
         eventcount = dayMonth + nextYear;
      }

      const countDown = new Date(eventcount).getTime(),
         x = setInterval(function() {
            const now = new Date().getTime(),
               distance = countDown - now;

            document.getElementById("days").innerText = Math.floor(distance / (day)),
               document.getElementById("hours").innerText = Math.floor((distance % (day)) / (hour)),
               document.getElementById("minutes").innerText = Math.floor((distance % (hour)) / (minute)),
               document.getElementById("seconds").innerText = Math.floor((distance % (minute)) / second);

            //do something later when date is reached
            if (distance < 0) {
               document.getElementById("headline").innerText = "Event is started!";
               document.getElementById("countdown").style.display = "none";
               document.getElementById("content").style.display = "block";
               clearInterval(x);
            }
            //seconds
         }, 0)
   }());
</script>