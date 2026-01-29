<?php if (!empty($festival_tbl)) :
   foreach ($festival_tbl as $key => $item) :
   $currentdate = strtotime(date('Y-m-d'));
   $evetntstart = strtotime($item["start_date"]);
   $evetntend = strtotime($item["end_date"]);

   $start_date = new DateTime($item["start_date"]);
   $end_date = new DateTime($item["end_date"]);
   $interval = $start_date->diff($end_date);
   $total_days = $interval->days + 1;

   $getVenuName = $this->db->select('venue_title')->from('venue_tbl')->where('id', $item['venue_id'])->get()->row();

   $formatted_start_date = $start_date->format('m-d-Y');
   $formatted_end_date = $end_date->format('m-d-Y');

   $date = DateTime::createFromFormat('Y-m-d', $datae);
   $month_num1 =  $date->format('m');
   $month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));
   $event_start_time = date('h:i a', strtotime($item['event_start_time']));
   $bg_image = !empty($item['image']) ? base_url('assets/front/img/festivalimage/') . $item['image'] : base_url('assets/front/img/festivalimage/festival.jpg');
   if ($currentdate == $evetntstart || $currentdate == $evetntend) {
?>
<div class="col-md-4">
   <div class="home-box" style="background: #111 url('<?php echo $bg_image; ?>') 0 0 no-repeat; background-size: cover;">
      <div class="calendar-box" >
         <div class="festivals-content">
            <h6 class="festival-heading"><?= $item['festival_name'] ?></h6>
            <?php if (!empty($item['location_address'])) : ?>
               <p class="loc-fes"><i class="fas fa-map-marker-alt"></i><?= $item['location_address'] ?></p>
            <?php endif; ?>
            <?php if (!empty($item['phone_number'])) : ?>
               <p class="phone-fes">
                  <i class="fas fa-phone-alt"></i> <?= $item['phone_number'] ?>
               </p>
            <?php endif; ?>
            <p class="text-fes">+ <?= $total_days ?> Days</p>
            <p class="artist-names">
               <?= $item['artist_name'] ?>
            </p>
            <p class="phone-fes">
               <?= $formatted_start_date ?> to <?= $formatted_end_date ?>
            </p>

            <p><a href="<?php echo getCurrentControllerPath('festival_detail/' . $item['festival_id']) ?>" class="online-btn">View festival detail</a></p>
         </div>
      </div>
   </div>
</div>
<?php } elseif ($currentdate != $evetntstart) { ?>
   <div class="col-md-4">
      <div class="home-box" style="background: #111 url('<?php echo $bg_image; ?>') 0 0 no-repeat; background-size: cover;">
         <div class="calendar-box" >
            <div class="festivals-content">
               <h6 class="festival-heading"><?= $item['festival_name'] ?></h6>
               <?php if (!empty($item['location_address'])) : ?>
                  <p class="loc-fes"><i class="fas fa-map-marker-alt"></i><?= $item['location_address'] ?></p>
               <?php endif; ?>
               <?php if (!empty($item['phone_number'])) : ?>
                  <p class="phone-fes">
                     <i class="fas fa-phone-alt"></i> <?= $item['phone_number'] ?>
                  </p>
               <?php endif; ?>
               <p class="text-fes">+ <?= $total_days ?> Days</p>
               <p class="artist-names">
                  <?= $item['artist_name'] ?>
               </p>
               <p class="phone-fes">
                  <?= $formatted_start_date ?> to <?= $formatted_end_date ?>
               </p>
               <p><a href="<?php echo getCurrentControllerPath('festival_detail/' . $item['festival_id']) ?>" class="online-btn">View festival detail</a></p>
            </div>
         </div>
      </div>
   </div>
<?php }
endforeach;  ?>

<?php else : ?>
   <h3 class="not-found">No festivals found</h3>
<?php endif; ?>