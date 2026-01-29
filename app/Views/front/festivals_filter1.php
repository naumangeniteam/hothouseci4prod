<?php if (!empty($festival_tbl)) : ?>
    <?php $today = new DateTime('today'); ?>

    <?php foreach ($festival_tbl as $item) : ?>
        <?php
        // Background
        $bg_image = !empty($item['image'])
            ? base_url('assets/front/img/festivalimage/' . $item['image'])
            : base_url('assets/front/img/festivalimage/festival.jpg');

        // Parse dates safely
        $start = !empty($item['start_date']) ? date_create($item['start_date']) : null;
        $end   = !empty($item['end_date'])   ? date_create($item['end_date'])   : null;

        $hasStart = $start instanceof DateTime;
        $hasEnd   = $end instanceof DateTime;

        // Guard against end < start (fallback to same day)
        if ($hasStart && $hasEnd && $end < $start) {
            $end = clone $start;
        }

        // Build date line and days
        $dateLine = 'Dates TBD';
        $daysLine = '';
        if ($hasStart && $hasEnd) {
            $dateLine = $start->format('m-d-Y') . ' to ' . $end->format('m-d-Y');
            $daysLine = '+ ' . ($start->diff($end)->days + 1) . ' Days';
        } elseif ($hasStart) {
            $dateLine = 'Starts ' . $start->format('m-d-Y');
        } elseif ($hasEnd) {
            $dateLine = 'Ends ' . $end->format('m-d-Y');
        } else {
            // Don’t spam the UI—log for debugging instead
            log_message('notice', 'Festival has no dates: {name}', ['name' => $item['festival_name'] ?? '']);
        }

        $startsToday = $hasStart && $today->format('Y-m-d') === $start->format('Y-m-d');
        ?>

        <div class="col-md-4">
            <div class="home-box" style="background: rgba(0,0,0,0.1) url('<?= $bg_image ?>');">
                <div class="calendar-box">
                    <div class="festivals-content">
                        <h6 class="festival-heading"><?= esc($item['festival_name']) ?></h6>

                        <?php if (!empty($item['location_address'])) : ?>
                            <p class="loc-fes"><i class="fas fa-map-marker-alt"></i><?= esc($item['location_address']) ?></p>
                        <?php endif; ?>

                        <?php if (!empty($item['phone_number'])) : ?>
                            <p class="phone-fes"><i class="fas fa-phone-alt"></i> <?= esc($item['phone_number']) ?></p>
                        <?php endif; ?>

                        <?php if ($daysLine) : ?>
                            <p class="text-fes"><?= $daysLine ?></p>
                        <?php endif; ?>

                        <?php if (!empty($item['artist_name'])) : ?>
                            <p class="artist-names"><?= esc($item['artist_name']) ?></p>
                        <?php endif; ?>

                        <p class="phone-fes"><?= $dateLine ?></p>
                        <?php if ($startsToday) : ?>
                            <span class="badge badge-success">Starts Today</span>
                        <?php endif; ?>

                        <p><a href="<?= getCurrentControllerPath('festival_detail/' . $item['festival_id']) ?>" class="online-btn">View Festival</a></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

<?php else : ?>
    <h3 class="not-found">No festivals found</h3>
<?php endif; ?>
