<style>
   .video-deatils {
      display: flex;
      align-items: center;
      /* font-size: 3px; */
      gap: -6px;
      /* justify-content: center; */
      text-align: center;
      align-items: baseline;
      padding: 0px;
   }

   .reverse_now {
      font-size: 12px;
   }

   .buy_now {
      font-size: 12px;
   }


   /*al-ui */
   .btn-primary-bg {
      background-color: #bd1e2e !important;
      border: 1px solid #bd1e2e !important;

   }

   .a-text {
      color: #e52b31;
   }
</style>

<?php if (!empty($event_tbl)) :
   foreach ($event_tbl as $key => $item) :

      $currentdate = strtotime($datae);
      $evetntstart = strtotime($item["start_date"]);
      $evetntend = strtotime($item["end_date"]);

      $getVenuName = $this->db->select('venue_title')->from('venue_tbl')->where('id', $item['venue_id'])->get()->row();
      $date = DateTime::createFromFormat('Y-m-d', $datae);
      $month_num1 =  $date->format('m');
      $month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));

      $event_start_time1 = date('h:i a', strtotime($item['event_start_time']));
      $lastTwoDigits = substr($item['event_start_time'], -2);


      if ($lastTwoDigits == 'PM' || $lastTwoDigits == 'AM') {
         $event_start_time = $item['event_start_time'];  // Output: 7:00:00 PM
      } else {
         $event_start_time = $item['event_start_time'] . ' PM';  // Output: 7:00:00 PM
      }
      if ($currentdate >= $evetntstart && $currentdate <= $evetntend) {
?>
         <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="calendar-box">
               <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                     <div class="event_texts">
                        <h5 class="al-date"><?php echo $date->format('d');   ?> <span><?php echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3);   ?>
                              <?php echo substr(strtoupper($date->format("l")), 0, 3);   ?></span>
                        </h5>
                        <p class="text-left"><?php echo  strtoupper($event_start_time);   ?></p>
                     </div>
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-8 col-12">
                     <div class="event-heading">
                        <h6><?= $item['event_title'] ?></h6>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <?php if (isset($item['artist_image']) && $item['artist_image'] != '') { ?>
                     <div class="col-6">
                        <a href="<?php echo base_url('thumbnail-full-image'); ?>?artistId=<?php echo $item['artist_id']; ?>&eventId=<?php echo $item['event_id']; ?>">
                           <img src="<?php echo base_url('assets/front/img/artistimage/' . $item['artist_image']); ?>" alt="Thumb Image" style="width: 103px">
                        </a>
                     </div>
                  <?php } elseif (isset($item['artist_url']) && $item['artist_url'] != '') { ?>
                     <div class="col-6">
                        <a href="<?php echo base_url('thumbnail-full-image'); ?>?artistId=<?php echo $item['artist_id']; ?>">
                           <img src="<?php echo $item['artist_id']; ?>" alt="Thumb Image" style="width: 103px">
                        </a>
                     </div>
                  <?php } else { ?>
                     <div class="col-6">
                        <a href="<?php echo base_url('thumbnail-full-image'); ?>?artistId=<?php echo $item['artist_id']; ?>">
                           <img src="<?php echo base_url('assets/front/img/artistimage/events.jpg'); ?>" alt="Default Image" style="width: 103px">
                        </a>
                     </div>
                  <?php } ?>
                  <div class="<?php if (isset($item['artist_image']) && $item['artist_image'] != '') { ?> col-6 <?php } else { ?> col-12 <?php } ?>">
                     <p><?= $getVenuName->venue_title ?></p>
                     <p>
                        <i class="fas fa-map-marker-alt"></i>
                        <a class="loca-map" href="https://www.google.com/maps?q=<?= urlencode($item['location_name']) ?>" target="_blank">
                           <?= htmlspecialchars($item['location_name']) ?>
                        </a>
                     </p>
                     <?php
                     $url = $item['website'];
                     $url_display = preg_replace('/^(https?:\/\/)?(www\.)?/', '', $url);
                     ?>
                     <div class="text-center">
                        <a class="loca-map web-link" href="<?= rtrim($url, '/') ?>" target="_blank"><?= rtrim($url_display, '/') ?></a>
                     </div>
                     <p>
                        <?php if (!empty($item['buy_now_link'])) : ?>
                           <a target="_blank" href="<?= $item['buy_now_link'] ?>" class="buy_reserve_both">Buy Now</a>
                        <?php endif; ?>
                        <?php if (!empty($item['reserve_seat_link'])) : ?>
                           <a target="_blank" href="<?= $item['reserve_seat_link'] ?>" class="buy_reserve_both">Reserve now</a>
                        <?php endif; ?>
                     </p>
                     <?php if (!empty($item['jazz_type_name'])) : ?>
                        <div class="text-center">
                           <span class="badge badge-danger mt-2"><?= ucfirst($item['jazz_type_name']) ?></span>
                        </div>
                     <?php endif; ?>

                     <?php if (!empty($item['event_types'])) : ?>
                        <p><?= ucfirst($item['event_types']) ?></p>
                     <?php endif; ?>

                     <?php if (!empty($item['cover_charge'])) : ?>
                        <p class="al-free"><?php echo $item['cover_charge']; ?></p>
                     <?php else : ?>
                        <!-- <p class="al-free">FREE</p> -->
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

                  </div>
               </div>
               <p><a href="<?php echo getCurrentControllerPath('event_detail/' . $item['event_id']) ?>" class="online-btn">View Event</a></p>
            </div>

         </div>

      <?php } elseif ($currentdate != $evetntstart) { ?>
         <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="calendar-box">
               <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                     <div class="event_texts">
                        <h5 class="al-date"><?php echo $date->format('d');   ?> <span><?php echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3);   ?>
                              <?php echo substr(strtoupper($date->format("l")), 0, 3);   ?></span>
                        </h5>
                        <p class="text-left"><?php echo  strtoupper($event_start_time);   ?></p>
                     </div>
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-8 col-12">
                     <div class="event-heading">
                        <h6><?= $item['event_title'] ?></h6>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <?php if (isset($item['artist_image']) && $item['artist_image'] != '') { ?>
                     <div class="col-6">
                        <a href="<?php echo base_url('thumbnail-full-image'); ?>?artistId=<?php echo $item['artist_id']; ?>&eventId=<?php echo $item['event_id']; ?>">
                           <img src="<?php echo base_url('assets/front/img/artistimage/' . $item['artist_image']); ?>" alt="Thumb Image" style="width: 103px">
                        </a>
                     </div>
                  <?php } else { ?>
                     <div class="col-6">
                        <a href="<?php echo base_url('thumbnail-full-image'); ?>?artistId=<?php echo $item['artist_id']; ?>&eventId=<?php echo $item['event_id']; ?>">
                           <img src="<?php echo base_url('assets/front/img/artistimage/events.jpg'); ?>" alt="Default Image" style="width: 103px">
                        </a>
                     </div>
                  <?php } ?>

                  <div class="<?php if (isset($item['artist_image']) && $item['artist_image'] != '') { ?> col-6 <?php } else { ?> col-12 <?php } ?>">
                     <p><?= $getVenuName->venue_title ?></p>
                     <p>
                        <i class="fas fa-map-marker-alt"></i>
                        <a class="loca-map" href="https://www.google.com/maps?q=<?= urlencode($item['location_name']) ?>" target="_blank">
                           <?= htmlspecialchars($item['location_name']) ?>
                        </a>
                     </p>
                     <?php
                     $url = $item['website'];
                     $url_display = preg_replace('/^(https?:\/\/)?(www\.)?/', '', $url);
                     ?>
                     <div class="text-center">
                        <a class="loca-map web-link" href="<?= rtrim($url, '/') ?>" target="_blank"><?= rtrim($url_display, '/') ?></a>
                     </div>
                     <p>
                        <?php if (!empty($item['buy_now_link'])) : ?>
                           <a target="_blank" href="<?= $item['buy_now_link'] ?>" class="buy_reserve_both">Buy Now</a>
                        <?php endif; ?>
                        <?php if (!empty($item['reserve_seat_link'])) : ?>
                           <a target="_blank" href="<?= $item['reserve_seat_link'] ?>" class="buy_reserve_both">Reserve now</a>
                        <?php endif; ?>
                     </p>
                     <?php if (!empty($item['jazz_type_name'])) : ?>
                        <div class="text-center">
                           <span class="badge badge-danger mt-2"><?= ucfirst($item['jazz_type_name']) ?></span>
                        </div>
                     <?php endif; ?>

                     <?php if (!empty($item['event_types'])) : ?>
                        <p><?= ucfirst($item['event_types']) ?></p>
                     <?php endif; ?>

                     <?php if (!empty($item['cover_charge'])) : ?>
                        <p><?php echo $item['cover_charge']; ?></p>
                     <?php else : ?>
                        <p class="al-free">FREE</p>
                     <?php endif; ?>

                     <img src="<?php echo base_url('assets/front/img/artistimage/' . $item['artist_image']); ?>" alt="Thumb Image" style="width: 103px">
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

                  </div>
               </div>
               <p><a href="<?php echo getCurrentControllerPath('event_detail/' . $item['event_id']) ?>" class="online-btn">View Event</a></p>
            </div>
         </div>

   <?php }
   endforeach;  ?>

<?php else : ?>
   <h3 style="text-align:center;margin-top:20px;margin-left: auto;margin-right: auto;">No
      Events Found</h3>
<?php endif; ?>

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
