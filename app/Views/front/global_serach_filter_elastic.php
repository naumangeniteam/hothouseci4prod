<div class="col-md-12">
    <h2>Events</h2>
</div>
<?php if (!empty($event_tbl)) :
    foreach ($event_tbl as $key => $items) :
        $item = $items['_source'];
        $currentdate = strtotime(date('Y-m-d'));
        $evetntstart = strtotime($item["start_date"]);

        $getVenuName = $item['venue']['venue_name']; //$this->db->select('venue_title')->from('venue_tbl')->where('id', $item['venue_id'])->get()->row();
        $date = DateTime::createFromFormat('Y-m-d', $item["start_date"]);
        $month_num1 =  $date->format('m');
        $month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));
        $event_start_time = date('h:i a', strtotime($item['event_start_time']));
?>
        <div class="col-md-4 hhj-sponsor-box">
            <div class="hhj-sbox">
                <div class="hhj-sprofile">
                    <a href="<?php echo base_url('thumbnail-full-image'); ?>?artistId=<?php echo $item['artist_id']; ?>&eventId=<?php echo $items['_id']; ?>">
                        <?php if (isset($item['artist_image'])) : ?>
                            <img src="<?php echo base_url('/assets/front/img/artistimage/' . $item['artist_image']); ?>" alt="Artist Image" class="al-imgs">
                        <?php else : ?>
                            <img src="<?php echo base_url('/assets/front/img/artistimage/events.jpg'); ?>" alt="Default Image" class="al-imgs">
                        <?php endif; ?>
                    </a>
                </div>

                <div class="hhj-sinfo">
                    <?php
                    $url = $item['website'];
                    $url_display = preg_replace('/^(https?:\/\/)?(www\.)?/', '', $url);
                    ?>
                    <h3><?= $item['event_title'] ?></h3>
                    <p><img src="/assets/front/icons/house.svg" alt="menu" /> <?= $getVenuName ?></p>
                    <p><img src="/assets/front/icons/location.svg" alt="menu" /> <a class="loca-map" href="https://www.google.com/maps?q=<?= urlencode($item['locations']['location_name']) ?>" target="_blank"><?= htmlspecialchars($item['locations']['location_name']) ?></a></p>
                    <p>
                        <?php if (!empty($item['website'])) : ?>
                            <a class="loca-map web-link" href="<?= rtrim($url, '/') ?>" target="_blank"><img src="/assets/front/icons/website.svg" alt="menu" /><?= rtrim($url_display, '/') ?></a>
                        <?php endif; ?>
                    </p>
                    <?php if (!empty($item['phone_number'])) : ?>
                        <p><img src="/assets/front/icons/phone.svg" alt="menu" /><?= $item['phone_number'] ?> </p>
                    <?php endif; ?>
                </div>

                <div class="hhj-box-bottom">
                    <div class="event-cost">
                        <?php if (!empty($item['cover_charge'])) : ?>
                            <p class="paid"><img src="/assets/front/icons/price.svg" alt="menu" /><?php echo $item['cover_charge']; ?></p>
                        <?php else : ?>
                            <p class="free"><img src="/assets/front/icons/price.svg" alt="menu" />FREE</p>
                        <?php endif; ?>
                    </div>
                    <div class="hhj-sdate">
                        <h6>
                            <img src="/assets/front/icons/date.svg" alt="menu" />
                            <?php echo $date->format('d'); ?>
                            <?php echo substr(strtoupper(date("F", mktime(0, 0, 0, $month_num1, 10))), 0, 3); ?>
                            <?php echo substr(strtoupper($date->format("l")), 0, 3); ?>
                        </h6>
                        <h6><img src="/assets/front/icons/time.svg" alt="menu" /><?php echo strtoupper($event_start_time); ?></h6>
                    </div>
                </div>
            </div>
            <div class="hhj-buttons"><a href="<?php echo getCurrentControllerPath('event_detail/' . $items['_id']) ?>" class="online-btn">View event</a></div>
        </div>
    <?php
    endforeach;  ?>

<?php else : ?>
    <div class="col-md-12">
        <h3 class="not-found">No events found</h3>
    </div>
<?php endif; ?>
</div>
<div class="col-md-12">
    <div class="row hhj-festival" style="margin-right: -15px;margin-left: -15px;">

        <div class="col-md-12">
            <h2>Festivals</h2>
        </div>

        <?php if (!empty($festival_tbl)) :
            //echo"<pre>";print_r($festival_tbl);
            foreach ($festival_tbl as $key => $items) :
                $item = $items['_source'];

                if (!empty($item['festival_name'])) {
                    $currentdate = strtotime(date('Y-m-d'));
                    $evetntstart = strtotime($item["start_date"]);

                    $start_date = new DateTime($item["start_date"]);
                    $end_date = new DateTime($item["end_date"]);
                    $interval = $start_date->diff($end_date);
                    $total_days = $interval->days + 1;

                    $getVenuName = $this->db->select('venue_title')->from('venue_tbl')->where('id', $item['venue_id'])->get()->row();
                    $formatted_start_date = $start_date->format('m-d-Y');
                    $formatted_end_date = $end_date->format('m-d-Y');
                    $date = DateTime::createFromFormat('Y-m-d', $item["start_date"]);

                    $month_num1 =  $date->format('m');
                    $month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));
                    $event_start_time = date('h:i a', strtotime($item['event_start_time']));
                    $bg_image = !empty($item['image']) ? base_url('assets/front/img/festivalimage/') . $item['image'] : base_url('assets/front/img/festivalimage/festival.jpg');
        ?>

                    <div class="col-md-4">
                        <div class="home-box" style="background: rgb(0,0,0,0.1) url('<?php echo $bg_image; ?>');">
                            <div class="calendar-box">
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
                                    <p><a href="<?php echo getCurrentControllerPath('festival_detail/' . $items['_id']) ?>" class="online-btn">View Festival</a></p>
                                </div>
                            </div>
                        </div>
                    </div>

            <?php }
            endforeach;  ?>

        <?php else : ?>
            <div class="col-md-12">
                <h3 class="not-found">No festivals found</h3>
            </div>
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