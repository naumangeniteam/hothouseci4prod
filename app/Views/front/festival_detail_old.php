<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" id="evcal_cal_default-css" href="css/eventon_styles.css" type="text/css" media="all">

<div class="signle-banner">
    <div class="full-container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="single-banner-img">
                    <img src="<?php echo base_url('assets/front/img/festivalimage/' . $festival_tbl[0]['image']); ?>" class="img-fluid" alt="">
                </div>
                <div class="single-banner-caption about-caption">
                    <div class="container">
                    </div>
                </div>
            </div>
        </div>

        <?php
        foreach ($banner as $key => $item) : ?>

        <?php endforeach;  ?>
    </div>
</div>
<div id="content_box" class="container mt-5">
    <div class="entry-content">
        <div class="eventon_main_section">
            <div id="evcal_single_event_29493" class="ajde_evcal_calendar eventon_single_event evo_sin_page ">
                <div class="evo-data" data-mapformat="roadmap" data-mapzoom="18" data-mapscroll="false" data-evc_open="1"></div>
                <br />
                <?php
                $date = $festival_tbl[0]["start_date"];
                $month = date("F", strtotime($date));
                $month_uppercase = strtoupper($month);
                $year = date('Y');

                ?>
                <!-- <div id="evcal_head" class="calendar_header">
                        <p id="evcal_cur" class="default-heading"><?php echo $month_uppercase;   ?> <?php echo $year; ?></p>
                     </div> -->
                <br />
                <?php foreach ($festival_tbl as $key => $item) :

                    // echo"<pre>";print_r($festival_tbl);die;

                    $getVenuName = $this->db->select('venue_title')->from('venue_tbl')->where('id', $item['venue_id'])->get()->row();

                    $date = DateTime::createFromFormat('Y-m-d', $item["start_date"]);

                    $date_str = strtotime($item["start_date"]);
                    $date_str1 = strtotime($item["start_date"]);
                    //print_r($date_str);die;
                    $date_arr = explode("-", $date_str); // split the string into an array
                    $year = $date_arr[0]; // get the year (yy)
                    $month_num = $date_arr[1]; // get the month number (mm)
                    $day = $date_arr[2]; // get the day (dd)
                    $month_name = date("F", $date_str); // get the month name from the date string
                    $day_name = date('l', $date_str);
                    //  print_r($day_name);die;

                    //$month_name1 = date("F", mktime(0, 0, 0, $month_name, 10));
                    $month_num1 =  $date->format('m');
                    //	$month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));
                    //$date12 = DateTime::createFromFormat('Y-m-d', $item["end_date"]);

                    $date12 = strtotime($item["end_date"]);
                    $date_arr1 = explode("-", $date12);
                    $day1 = $date_arr1[2]; // get the day (dd)
                    $day_name1 = date('l', $date12);

                    //$month_num12=  $date12->format('m');
                    // $month_num12 = date("F",$date_arr1);
                    $month_name12 = date("F", mktime(0, 0, 0, $month_num12, 10));
                    $month_name1 = date("F", mktime(0, 0, 0, $month_num1, 10));
                    $event_start_time1 = date('h:i a', strtotime($item['event_start_time']));
                    $lastTwoDigits = substr($item['event_start_time'], -2);


                    if ($lastTwoDigits == PM  || $lastTwoDigits == AM) {
                        $event_start_time = $item['event_start_time'];  // Output: 7:00:00 PM
                    } else {
                        $event_start_time = $item['event_start_time'] . ' PM';  // Output: 7:00:00 PM
                    }
                    $event_end_time = date('h:i a', strtotime($item['event_end_time']));

                    $youtubeUrl = $item['media_video_link'];
                    $youtubePattern = "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i";
                    function youtubeEmbedCallback($matches)
                    {
                        if (!isset($matches[2])) {
                            return '';
                        }
                        $videoId = $matches[2];
                        return 'https://www.youtube.com/embed/' . $videoId;
                    }
                    $media_intro_video = preg_replace_callback($youtubePattern, "youtubeEmbedCallback", $youtubeUrl);

                    // Get lineup details
                    $lineup_details = [];
                    foreach ($lineup_tbl as $lineup) {
                        // echo"<pre>";print_r($lineup_tbl);die;

                        if ($lineup['festival_id'] == $item['festival_id']) {

                            $lineup_details[] = $lineup;
                        }
                    }
                ?>
                    <div id="evcal_list" class="eventon_events_list evo_sin_event_list">
                        <div id="event_29493" class="eventon_list_event" event_id="29493" itemscope="" itemtype="https://schema.org/Event">
                            <div class="clear"></div>
                        </div>
                    </div>
                    <ul class="nav nav-tabs flex sm:flex-wrap -mb-px w-full mb-3" id="myTab" role="tablist">
                        <li class="nav-item flex-auto mr-0" role="presentation">
                            <a class="nav-link active w-full relative inline-block p-6 w-full rounded-t-lg border-b border-gray dark:text-blue-500" id="home-tab" data-toggle="tab" data-target="#home" type="" role="tab" aria-controls="home" aria-selected="true">
                                <h6>About</h6>
                            </a>
                        </li>
                        <?php if (!empty($media_intro_video)) : ?>
                            <li class="nav-item flex-auto mr-0" role="presentation">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="" role="tab" aria-controls="profile" aria-selected="false">
                                    <h6>Media</h6>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item flex-auto mr-0" role="presentation">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" data-target="#contact" type="" role="tab" aria-controls="contact" aria-selected="false">
                                <h6>Lineup</h6>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div id="section">
                                <div class="article mt-3">
                                    <p>
                                        <?= $item['summary'] ?>
                                    </p>
                                    <p class="moretext">
                                        <?= $item['summary'] ?>
                                    </p>
                                    <a class="moreless-button">READ MORE</a>
                                </div>
                            </div>

                            <div class="container map-loct mt-3">
                                <div class="n-map mb-4">
                                    <div id="map"></div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade mt-3 mb-3" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <?php if (!empty($media_intro_video)) : ?>
                                <iframe width="350" height="315" src="<?= $media_intro_video ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            <?php endif; ?>
                        </div>

                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="line-d mt-2">
                                <?php if (!empty($item['lineup'])) : ?>
                                    <p> <?= $item['lineup'] ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if ($total_artist_count >= 1) : ?>
                                <div class="artist-details mt-3 mb-4">
                                    <div dir="auto" class="css-1rynq56 r-jwli3a r-1gknse6 r-adyw6z r-135wba7 r-1pcdyqj"><?= $total_artist_count ?></div>
                                    <div dir="auto" class="css-1rynq56 r-jwli3a r-yv33h5 r-adyw6z r-14yzgew">Artists</div>
                                    <div class="css-175oi2r r-4mkfoz r-4v7adb r-vlx1xi r-z2qzgk r-1hbcis3"></div>
                                    <div dir="auto" class="css-1rynq56 r-jwli3a r-1gknse6 r-adyw6z r-135wba7 r-1pcdyqj">-</div>
                                    <div dir="auto" class="css-1rynq56 r-jwli3a r-yv33h5 r-adyw6z r-14yzgew">Headliners</div>
                                </div>
                            <?php endif; ?>

                            <div class="lineup-data">
                                <?php foreach ($lineup_details as $key => $lineup) : ?>
                                    <div class="lineup-details mb-3">
                                        <div class="art-name mb-4">
                                            <?php foreach ($lineup['artist_names'] as $artist_key => $name) : ?>
                                                <span class="festival-artist-name">
                                                    <?php if (!empty($name)) : ?>
                                                        <span class="txt"><?= htmlspecialchars($name) ?></span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($lineup['artist_images'][$artist_key])) : ?>
                                                        <img src="<?php echo base_url('assets/front/img/artistimage/' . htmlspecialchars($lineup['artist_images'][$artist_key])); ?>" class="artist-img" style="width: 43px; height: 42px;">
                                                    <?php endif; ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>

                                        <p><strong>Day:</strong> <?= htmlspecialchars($lineup['day']) ?></p>
                                        <p><strong>Location:</strong> <?= htmlspecialchars($lineup['location']) ?></p>
                                        <p><strong>Start Time:</strong> <?= date('h:i A', strtotime($lineup['start_time'])) ?></p>
                                        <p><strong>End Time:</strong> <?= date('h:i A', strtotime($lineup['end_time'])) ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
            </div>
        <?php endforeach;  ?>
        </div>
    </div>
</div>

</div>
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
                    "><?= $this->session->getFlashdata('alert_success') ?></span>
                <?php  } else {
                ?>
                    <span class="alert alert-error" style="width:100%;text-align: center;
                    font-family: 'Oswald', sans-serif;
                    font-weight: 500;
                    font-style: italic;
                    font-size: 25px;
                    color: red;;
                    "><?= $this->session->getFlashdata('alert_error') ?></span>
                <?php }
                ?>
                <form id="form" name="currentPageFormSubadmin" class="subscribe" method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

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
            <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
            <script type='text/javascript'>
                (function($) {
                    window.fnames = new Array();
                    window.ftypes = new Array();
                    fnames[0] = 'EMAIL';
                    ftypes[0] = 'email';
                    fnames[1] = 'FNAME';
                    ftypes[1] = 'text';
                    fnames[2] = 'LNAME';
                    ftypes[2] = 'text';
                }(jQuery));
                var $mcj = jQuery.noConflict(true);
            </script>

            <script>
                $('.moreless-button').click(function() {
                    $('.moretext').slideToggle();
                    if ($('.moreless-button').text() == "READ MORE") {
                        $(this).text("READ LESS")
                    } else {
                        $(this).text("READ MORE")
                    }
                });
            </script>

            <script>
                function initMap() {
                    var lat = <?php echo $festival_tbl[0]['latitude']; ?>;
                    var lng = <?php echo $festival_tbl[0]['longitude']; ?>;

                    if (lat === 0 && lng === 0) {
                        document.getElementById('map').style.display = 'none';
                        return;
                    }

                    var festivalLocation = {
                        lat: lat,
                        lng: lng
                    };

                    var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 15,
                        center: festivalLocation
                    });

                    var contentString = '<div id="content">' +
                        '<p class="fest-loc"><?php echo $festival_tbl[0]['location_name']; ?></p>' +
                        '<p><?php echo $festival_tbl[0]['location_address']; ?></p>' +
                        '<p><a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($festival_tbl[0]['location_name'] . ', ' . $festival_tbl[0]['location_address']); ?>" target="_blank">View on Google Maps</a></p>' +
                        '</div>';

                    var infowindow = new google.maps.InfoWindow({
                        content: contentString
                    });

                    var marker = new google.maps.Marker({
                        position: festivalLocation,
                        map: map
                    });

                    marker.addListener('click', function() {
                        infowindow.open(map, marker);
                    });
                }
            </script>

            <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPyWbxCqalUPsqs3f2bY1w_FDh5rAAXEE&callback=initMap"></script>

        </div>
    </div>
</div>