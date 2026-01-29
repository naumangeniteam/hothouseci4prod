<?php if (!empty($get_new_events)) :
    foreach ($get_new_events as $key => $item) :
        //  echo"<pre>";print_r($get_new_events);
        $currentdate = strtotime(date('Y-m-d'));
        $evetntstart = strtotime($item["start_date"]);
        // print_r($evetntstart);die;

        $getVenuName = $this->db->select('venue_title')->from('venue_tbl')->where('id', $item['venue_id'])->get()->row();
        $date = DateTime::createFromFormat('Y-m-d', $item["start_date"]);
        //print_r($date);die;
        $month_num1 =  $date->format('m');
        $month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));
        $event_start_time = date('h:i a', strtotime($item['event_start_time']));
        //print_r($date);die;
        if ($currentdate == $evetntstart) {
?>

            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="calendar-box ">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                            <h5><?php echo $date->format('d');   ?> <span><?php echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3);   ?>
                                    <?php echo substr(strtoupper($date->format("l")), 0, 3);   ?></span>
                            </h5>
                            <p class="text-left"><?php echo  strtoupper($event_start_time);   ?></p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-12">
                            <div class="event-heading">
                                <h6><?= $item['event_title'] ?></h6>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo base_url('thumbnail-full-image'); ?>?artistId=<?php echo $item['artist_id']; ?>&eventId=<?php echo $item['event_id']; ?>">
                        <?php if (isset($item['artist_image'])) : ?>
                            <img src="<?php echo base_url('assets/front/img/artistimage/' . $item['artist_image']); ?>" alt="Artist Image" style="width: 100px;">
                        <?php endif; ?>
                    </a>
                    <p><?= $getVenuName->venue_title ?></p>
                    <p><i class="fas fa-map-marker-alt"></i> <?= $item['location_name'] ?>,<?= $item['location_address'] ?></p>
                    <p><?= $item['website'] ?></p>
                    <p>
                        <?php if (!empty($item['buy_now_link'])) : ?>
                            <a target="_blank" href="<?= $item['buy_now_link'] ?>" class="buy_reserve_both">Buy Now</a>
                        <?php endif; ?>
                        <?php if (!empty($item['reserve_seat_link'])) : ?>
                            <a target="_blank" href="<?= $item['reserve_seat_link'] ?>" class="buy_reserve_both">Reserve now</a>
                        <?php endif; ?>
                    </p>
                    <?php if (!empty($item['jazz_type_name'])) : ?>
                        <p><?= ucfirst($item['jazz_type_name']) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($item['event_types'])) : ?>
                        <p><?= ucfirst($item['event_types']) ?></p>
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

                    <p><a href="<?php echo getCurrentControllerPath('event_detail/' . $item['event_id']) ?>" class="online-btn">View event detail</a></p>
                </div>
            </div>

        <?php } elseif ($currentdate != $evetntstart) { ?>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="calendar-box">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                            <h5><?php echo $date->format('d');   ?> <span><?php echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3);   ?>
                                    <?php echo substr(strtoupper($date->format("l")), 0, 3);   ?></span>
                            </h5>
                            <p class="text-left"><?php echo  strtoupper($event_start_time);   ?></p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-12">
                            <div class="event-heading">
                                <h6><?= $item['event_title'] ?></h6>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo base_url('thumbnail-full-image'); ?>?artistId=<?php echo $item['artist_id']; ?>&eventId=<?php echo $item['event_id']; ?>">
                        <?php if (isset($item['artist_image'])) : ?>
                            <img src="<?php echo base_url('assets/front/img/artistimage/' . $item['artist_image']); ?>" alt="Artist Image" style="width: 100px;">
                        <?php endif; ?>
                    </a>
                    <p><?= $getVenuName->venue_title ?></p>
                    <p><i class="fas fa-map-marker-alt"></i> <?= $item['location_name'] ?>,<?= $item['location_address'] ?></p>
                    <p><?= $item['website'] ?></p>
                    <p>
                        <?php if (!empty($item['buy_now_link'])) : ?>
                            <a target="_blank" href="<?= $item['buy_now_link'] ?>" class="buy_reserve_both">Buy Now</a>
                        <?php endif; ?>
                        <?php if (!empty($item['reserve_seat_link'])) : ?>
                            <a target="_blank" href="<?= $item['reserve_seat_link'] ?>" class="buy_reserve_both">Reserve now</a>
                        <?php endif; ?>
                    </p>
                    <?php if (!empty($item['jazz_type_name'])) : ?>
                        <p><?= ucfirst($item['jazz_type_name']) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($item['event_types'])) : ?>
                        <p><?= ucfirst($item['event_types']) ?></p>
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

                    <p><a href="<?php echo getCurrentControllerPath('event_detail/' . $item['event_id']) ?>" class="online-btn">View event detail</a></p>
                </div>
            </div>
    <?php }
    endforeach;  ?>

 

<?php else : ?>
    <h3 style="text-align:center;margin-top:20px;margin-left: auto;margin-right: auto;">No Events Found</h3>
<?php endif; ?>