<?= view('layouts/front/home-tabs') ?><div class="hhj-single-festival">
    <div class="container">
        <?php
        $firstDate = !empty($festival_tbl[0]["start_date"])
            ? new DateTime($festival_tbl[0]["start_date"])
            : null;
        $month_uppercase = $firstDate ? strtoupper($firstDate->format('F')) : '';
        $year = $firstDate ? $firstDate->format('Y') : date('Y');
        ?>

        <?php foreach ($festival_tbl as $key => $item) :
            // DB (use the current item's venue_id)
            $db = \Config\Database::connect();
            $query = $db->table('venue_tbl')
                ->select('venue_title')
                ->where('id', $item['venue_id'] ?? null)
                ->get()
                ->getRow();
            $getVenuName = $query->venue_title ?? 'N/A';

            // Dates: parse safely
            $start = !empty($item['start_date'])
                ? DateTime::createFromFormat('Y-m-d', $item['start_date'])
                : null;
            if (!$start && !empty($item['start_date'])) { // fallback if format isn't exactly Y-m-d
                $ts = strtotime($item['start_date']);
                $start = $ts ? (new DateTime())->setTimestamp($ts) : null;
            }

            $end = !empty($item['end_date'])
                ? DateTime::createFromFormat('Y-m-d', $item['end_date'])
                : null;
            if (!$end && !empty($item['end_date'])) {
                $ts = strtotime($item['end_date']);
                $end = $ts ? (new DateTime())->setTimestamp($ts) : null;
            }

            // Formatted strings (guard against null)
            $formatted_start_date = $start ? $start->format('m-d-Y') : '';
            $formatted_end_date   = $end   ? $end->format('m-d-Y')   : '';

            // Useful parts (if you need them)
            $month_name   = $start ? $start->format('F') : '';
            $day_name     = $start ? $start->format('l') : '';
            $month_name12 = $end   ? $end->format('F')   : '';
            $day_name1    = $end   ? $end->format('l')   : '';

            // Event times: format robustly (works whether original has AM/PM or 24h)
            $event_start_time = !empty($item['event_start_time'])
                ? date('h:i A', strtotime($item['event_start_time']))
                : '';
            $event_end_time = !empty($item['event_end_time'])
                ? date('h:i A', strtotime($item['event_end_time']))
                : '';

            // YouTube embed
            $youtubeUrl = $item['media_video_link'] ?? '';
            $youtubePattern = "/\s*[a-zA-Z\/\/:\.]*youtu(be\.com\/watch\?v=|\.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i";
            $media_intro_video = preg_replace_callback(
                $youtubePattern,
                function ($matches) {
                    if (!isset($matches[2])) return '';
                    return 'https://www.youtube.com/embed/' . $matches[2];
                },
                $youtubeUrl
            );

            // Get lineup details for this festival
            $lineup_details = [];
            foreach ($lineup_tbl as $lineup) {
                if (($lineup['festival_id'] ?? null) == ($item['festival_id'] ?? null)) {
                    $lineup_details[] = $lineup;
                }
            }
        ?>

            <div class="row hhj-festival-details">
                <div class="col-md-6">
                    <div class="festival-img">
                        <?php if (isset($festival_tbl[0]['image']) && !empty($festival_tbl[0]['image'])) { ?>
                            <img src="<?php echo base_url('assets/front/img/festivalimage/' . $festival_tbl[0]['image']); ?>" class="img-fluids" alt="">
                        <?php } else { ?>
                            <img src="<?php echo base_url('assets/front/img/festivalimage/festival.jpg'); ?>" class="img-fluids" alt="">
                        <?php  } ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="festival-info">
                        <?php if (!empty($item['festival_name'])) : ?>
                            <h4 class="festival-title"><?= $item['festival_name'] ?></h4>
                        <?php endif; ?>
                        <div class="festival-list">
                            <p><img src="<?php echo base_url('assets/front/icons/schedule.svg'); ?>" alt="icon">
                                <?= htmlspecialchars($formatted_start_date) ?> to <?= htmlspecialchars($formatted_end_date) ?>
                            </p>
                            <?php if (!empty($item['location_address'])) : ?>
                                <p><img src="<?php echo base_url('assets/front/icons/location.svg'); ?>" alt="icon"> <?= $item['location_address'] ?></p>
                            <?php endif; ?>
                            <?php if (!empty($item['phone_number'])) : ?>
                                <p><img src="<?php echo base_url('assets/front/icons/phone.svg'); ?>" alt="icon"> <?= $item['phone_number'] ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="festival-website">
                            <?php
                            if (!empty($item['website'])) :
                                $website = str_replace('http://localhost/', '', $item['website']);
                                $website = 'https://' . ltrim($website, '/');
                            ?>
                                <p><a href="<?= $website ?>" target="_blank" type="button"><img src="<?php echo base_url('assets/front/icons/website.svg'); ?>" alt="icon"> View Website</a></p>
                            <?php endif; ?>
                        </div>

                        <div class="festival-descriptions">
                            <h4>Description</h4>
                            <p><?= $item['summary'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="festival-lineup">
                    <h3>Lineup</h3>
                    <?php if (!empty($item['lineup'])) : ?>
                        <p> <?= $item['lineup'] ?></p>
                    <?php endif; ?>
                </div>

                <?php if ($total_artist_count >= 1) : ?>
                    <div class="festival-artist">
                        <div><?= $total_artist_count ?></div>
                        <div>Artists</div>
                        <div></div>
                        <div>-</div>
                        <div>Headliners</div>
                    </div>
                <?php endif; ?>

                <div class="festival-lineup-data">
                    <?php foreach ($lineup_details as $key => $lineup) : ?>
                        <div class="lineup-details">
                            <div class="art-name">
                                <?php foreach ($lineup['artist_names'] as $artist_key => $name) : ?>
                                    <span class="festival-artist-name">
                                        <?php if (!empty($name)) : ?>
                                            <span><?= htmlspecialchars($name) ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($lineup['artist_images'][$artist_key])) : ?>
                                            <img src="<?php echo base_url('assets/front/img/artistimage/' . htmlspecialchars($lineup['artist_images'][$artist_key])); ?>">
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

            <div class="row">
                <ul class="nav nav-tabs flex sm:flex-wrap -mb-px w-full mb-3" id="myTab" role="tablist">
                    <?php if (!empty($media_intro_video)) : ?>
                        <li class="nav-item flex-auto mr-0" role="presentation">
                            <a class="nav-link <?= !empty($media_intro_video) ? 'active' : '' ?>" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="<?= !empty($media_intro_video) ? 'true' : 'false' ?>">
                                <h6>Media</h6>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($festival_tbl[0]['latitude'] != 0 || $festival_tbl[0]['longitude'] != 0) : ?>
                        <li class="nav-item flex-auto mr-0" role="presentation">
                            <a class="nav-link <?= empty($media_intro_video) ? 'active' : '' ?>" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="<?= empty($media_intro_video) ? 'true' : 'false' ?>">
                                <h6>How to get there</h6>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade mt-3 mb-3 <?= !empty($media_intro_video) ? 'show active' : '' ?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <?php if (!empty($media_intro_video)) : ?>
                            <iframe width="100%" height="500px" src="<?= $media_intro_video ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen sandbox="allow-scripts allow-same-origin"></iframe>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane fade mt-3 mb-3 <?= empty($media_intro_video) ? 'show active' : '' ?>" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="container map-loct">
                            <div class="n-map mb-4">
                                <div id="map"></div>
                                <div id="no-location" style="display:none;">
                                    <!-- <p>No location available.</p> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;  ?>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var showChar = 250;
        var ellipsestext = "";
        var moretext = "Show More <i class='fa fa-angle-down' aria-hidden='true'></i>";
        var lesstext = "Show Less <i class='fa fa-angle-up' aria-hidden='true'></i>";

        $('.more').each(function() {
            var content = $(this).html();

            if (content.length > showChar) {

                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);

                var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<p class="morelink moreless-buttons">' + moretext + '</p></span>';

                $(this).html(html);
            }

        });

        $(".morelink").click(function() {
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    });
</script>

<script>
    function initMap() {
        var lat = <?php echo $festival_tbl[0]['latitude']; ?>;
        var lng = <?php echo $festival_tbl[0]['longitude']; ?>;

        if (lat === 0 && lng === 0) {
            document.getElementById('map').style.display = 'none';
            document.getElementById('no-location').style.display = 'block'; // Show no-location div
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
            '<p><?php echo $festival_tbl[0]['location_address']; ?></p>' +
            '<p><?php echo $festival_tbl[0]['city_state_name']; ?></p>' +
            '<p><a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($festival_tbl[0]['location_address'] . ', ' . $festival_tbl[0]['city_state_name']); ?>" target="_blank">View on Google Maps</a></p>' +
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