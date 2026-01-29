<?php
if (!empty($event_tbl)) :
   foreach ($event_tbl as $key => $items) :
      $item = $items['_source'];
      $currentdate = strtotime(date('Y-m-d'));
      $evetntstart = strtotime($item["start_date"]);

      $getVenuName = $item['venue']['venue_name'];//$this->db->select('venue_title')->from('venue_tbl')->where('id', $item['venue_id'])->get()->row();
      $date = DateTime::createFromFormat('Y-m-d', $item["start_date"]);
      $month_num1 =  $date->format('m');
      $month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));
      $event_start_time = date('h:i a', strtotime($item['event_start_time']));
      if ($currentdate == $evetntstart) {
?>

         <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="calendar-box al-calendar-box">
               <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                     <!-- <a href="<?php echo base_url('thumbnail-full-image'); ?>?artistId=<?php echo $item['artist_id']; ?>&eventId=<?php echo $items['_id']; ?>"> -->
                        <?php if (isset($item['artist_image'])) { ?>
                           <?php if (isset($item['cover_image']) && !empty($item['cover_image'])) : ?>
                              <img src="<?php echo base_url('assets/front/img/eventimage/' . $item['cover_image']); ?>" alt="Event Image" class="al-imgs">
                           <?php else : ?>
                              <img src="<?php echo !empty($item['cover_url']) ? $item['cover_url'] : base_url('assets/front/img/artistimage/events.jpg'); ?>" alt="Default Image" class="al-imgs">
                           <?php endif; ?>
                        <?php } ?>
                     <!-- </a> -->
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-8 col-12">
                     <div class="event-heading al-event-heading">
                        <h6><?= $item['event_title'] ?></h6>
                     </div>
                  </div>
               </div>

               <div>
                  <div class="event_texts">
                     <h5 class="al-date"><?php echo $date->format('d');   ?> <span><?php echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3);   ?>
                           <?php echo substr(strtoupper($date->format("l")), 0, 3);   ?></span>
                     </h5>
                     <p class="text-left"><?php echo  strtoupper($event_start_time);   ?></p>
                  </div>
               </div>

               <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                     <p><?= $getVenuName ?></p>
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
                        <p class="al-free"></p>
                     <?php endif; ?>

                     <?php if ($item['artist_id'] > 0) : ?>
                        <p>
                           <a data-id="<?php echo $items['_id']; ?>" href="javascript:void(0)" class="more_info_artist_btn">
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

                     <p><a href="<?php echo getCurrentControllerPath('event_detail/' . $items['_id']) ?>" class="online-btn">View Event</a></p>
                  </div>
               </div>

            </div>
         </div>

      <?php } elseif ($currentdate != $evetntstart) { ?>
         <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="calendar-box al-calendar-box">
               <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                     <!-- <a href="<?php echo base_url('thumbnail-full-image'); ?>?artistId=<?php echo $item['artist_id']; ?>&eventId=<?php echo $items['_id']; ?>"> -->
                     <?php if (isset($item['cover_image']) && !empty($item['cover_image'])) : ?>
                        <img src="<?php echo base_url('assets/front/img/eventimage/' . $item['cover_image']); ?>" alt="Event Image" class="al-imgs">
                     <?php else : ?>
                        <img src="<?php echo !empty($item['cover_url']) ? $item['cover_url'] : base_url('assets/front/img/artistimage/events.jpg'); ?>" alt="Default Image" class="al-imgs">
                     <?php endif; ?>
                     <!-- </a> -->
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-8 col-12">
                     <div class="event-heading al-event-heading">
                        <h6><?= $item['event_title'] ?></h6>
                     </div>
                  </div>
               </div>

               <div>
                  <div class="event_texts">
                     <h5 class="al-date"><?php echo $date->format('d');   ?> <span><?php echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3);   ?>
                           <?php echo substr(strtoupper($date->format("l")), 0, 3);   ?></span>
                     </h5>
                     <p class="text-left"><?php echo  strtoupper($event_start_time);   ?></p>
                  </div>
               </div>

               <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                     <p><?= $getVenuName ?></p>
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

                     <?php if ($item['artist_id'] > 0) : ?>
                        <p>
                           <a data-id="<?php echo $items['_id']; ?>" href="javascript:void(0)" class="more_info_artist_btn">
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

                     <p><a href="<?php echo getCurrentControllerPath('event_detail/' . $items['_id']) ?>" class="online-btn">View Event</a></p>
                  </div>
               </div>

            </div>
         </div>
   <?php }
   endforeach;  ?>

<?php else : ?>
   <h3 style="text-align:center;margin-top:20px;margin-left: auto;margin-right: auto;">No Events Found</h3>
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