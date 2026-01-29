<style>
    .sort-icon {
        cursor: pointer;
    }

    .fa-refresh:before {
        content: "\f021";
    }

    .btn-danger,
    .btn-danger:focus {
        background-color: #d73d32 !important;
        border-color: #d73d32;
        color: #fff;
    }

    .b .btn1 {
        color: #444;
        background-color: #fff;
        border-color: #ccc;
        height: 50px;
    }

    .btn-palegreen,
    .btn-palegreen:focus {
        background-color: #a0d468 !important;
        border-color: #a0d468;
        color: #fff;
    }

    .dropdown-menu[x-placement="top-start"] {
        transform-origin: 0 100%;
        /*bottom: 100% !important;*/
        bottom: -25px !important;
        top: auto !important;
    }

    table.dataTable.nowrap th,
    table.dataTable.nowrap td {
        white-space: unset !important;
    }

    a.artists {
        color: #373a3c !important;
    }

    #daysInput {
        display: none;
    }

    .custom-data-table-wrapper1,
    .custom-data-table-wrapper2 {
        width: 100%;
        overflow: auto;
    }

    .custom-data-table-wrapper1 {
        background: #fff;
        height: 20px;
    }

    .custom-data-table-wrapper2::-webkit-scrollbar {
        display: none;
    }

    .custom-data-table-wrapper2 {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .dropdown-menu-show {
        height: 128px !important;
        z-index: 2 !important;
        left: -35px !important;
        bottom: -60px !important;
    }
</style>

<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Report List</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Manage Report</h5>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <div class="widget">
                                <div class="widget-body bordered-left bordered-warning">
                                    <form class="form-inline" method="get" id="validate" novalidate="novalidate">
                                    <input type="hidden" name="event_title" id="event_title" value="<?= esc($_GET['event_title'] ?? '') ?>" />
                                    <input type="hidden" name="start_date" id="start_date" value="<?= esc($_GET['start_date'] ?? '') ?>" />
                                        <!-- <div class="form-group"> <span class="input-icon">
                                                <input type="text" class="form-control" style="margin:6px;padding:5px;" placeholder="Event Name" name="event_name" value="<?= esc($_GET['event_name'] ?? '') ?>">
                                            </span>
                                        </div>
                                        <div class="form-group"> <span class="input-icon">
                                                <input type="text" class="form-control" style="margin:4px;padding:2px;" placeholder="Event Location name" name="location_name" value="<?= esc($_GET['location_name'] ?? '') ?>">
                                            </span>
                                        </div> -->

                                        <div class="form-group">
                                            <select name="venue_id" id="venue_id" style="margin:4px;padding:2px;" class="form-control" required>
                                                <option value="">Select Location</option>

                                                <?php
                                                if (!empty($venues)) :
                                                    foreach ($venues as $venue) : ?>
                                                        <option <?php if (isset($_GET['venue_id']) && $_GET['venue_id'] == $venue['id']) echo "selected"; ?> value="<?= esc($venue['id']) ?>"><?= esc($venue['venue_title']) ?></option>
                                                <?php endforeach;
                                                endif; ?>
                                                <option value="All" <?= isset($_GET['venue_id']) && $_GET['venue_id'] == 'All' ? 'selected' : '' ?>>All</option>

                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <select name="location_name" id="location_name" style="margin: 4px; padding: 2px;" class="form-control" required>
                                                <option value="">Select Venue</option>
                                                <?php
                                                if (!empty($locations)) {
                                                    foreach ($locations as $location) {
                                                        $locationName = trim($location['location_name']);
                                                        if (!empty($locationName)) {
                                                ?>
                                                            <option <?= (isset($_GET['location_name']) && $_GET['location_name'] == $location['id']) ? 'selected' : '' ?> value="<?= esc($location['id']) ?>"><?= $locationName ?></option>
                                                <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" placeholder="MM/DD/YYYY" onfocus="this.type='date'" onblur="this.type='text'" name="start_date" style="margin:4px;padding:2px;" style="width:120px;" id="start_date" class="form-control" value="<?= esc(service('request')->getGet('start_date') ?? date('Y-m-01')) ?>" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" placeholder="MM/DD/YYYY" onfocus="this.type='date'" onblur="this.type='text'" name="end_date" style="margin:4px;padding:2px;" style="width:120px;" id="end" class="form-control" value="<?= esc(service('request')->getGet('end_date') ?? date('Y-m-t')) ?>" />
                                            <a class="btn btn-link reset" title="Refresh End Date"><i class=" fa fa-refresh"></i></a>
                                        </div>

                                        <div class="form-group">
                                            <input type="submit" class="btn1" style="height:40px;" value="Search" name="event_search" href="javascript:" id="serach-btn" data-href="">
                                            <a href="<?= base_url('hhjsitemgmt/adminmanageeventreport/index') ?>" class="btn btn-link">View All </a>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="Data_Form" name="Data_Form" method="get" action="<?php echo $forAction; ?>">
                            <div class="dt-responsive table-responsive">
                                <div id="simpletable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-2 col-xs-12">
                                            <div class="dataTables_length" id="simpletable_length">
                                                <label>Show
                                                    <select name="showLength" id="showLength" class="custom-select custom-select-sm form-control form-control-sm">
                                                        <option value="2" <?php if ($perpage == '2') echo 'selected="selected"'; ?>>2</option>
                                                        <option value="10" <?php if ($perpage == '10') echo 'selected="selected"'; ?>>10</option>
                                                        <option value="25" <?php if ($perpage == '25') echo 'selected="selected"'; ?>>25</option>
                                                        <option value="50" <?php if ($perpage == '50') echo 'selected="selected"'; ?>>50</option>
                                                        <option value="100" <?php if ($perpage == '100') echo 'selected="selected"'; ?>>100</option>
                                                        <option value="All" <?php if ($perpage == 'All') echo 'selected="selected"'; ?>>All</option>
                                                    </select>
                                                    entries
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-10 col-xs-12">
                                            <div class="row" style="margin:3px;">
                                                <a href="<?= base_url('hhjsitemgmt/eventmanagement/index') ?>" style="margin:3px;" class="btn btn-palegreen">Count Event(<?= $events[0]['count'] ?>)</a>
                                                <a href="javascript:" id="export-btn" data-href="<?php echo base_url('hhjsitemgmt/adminmanageeventreport/export_excel'); ?>" style="margin:3px;" class="btn btn-primary pull-right">Export</a>
                                                <a href="javascript:" id="generate-report" data-href="<?= base_url('hhjsitemgmt/adminmanageeventreport/generate_report') ?>" style="margin:3px;" class="btn btn-palegreen">Generate Report</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive custom-data-table-wrapper2">
                                                <div class="table-responsive">
                                                    <table id="simpletable" class="table table-striped table-bordered nowrap dataTable custom-data-table" role="grid" aria-describedby="simpletable_info">
                                                        <thead class="text-nowrap">
                                                            <tr>
                                                                <th>Location</th>
                                                                <th>Venue</th>
                                                                <th>Location Address</th>
                                                                <th class="sortable" data-sort-type="event_title">Event Title
                                                                    <!-- <span class="sort-icon">
                                                                        <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'event_title' && isset($_GET['order']) && $_GET['order'] == 'asc') : ?>
                                                                            <i class="fas fa-arrow-up"></i>
                                                                        <?php elseif (isset($_GET['sort_by']) && $_GET['sort_by'] == 'event_title' && isset($_GET['order']) && $_GET['order'] == 'desc') : ?>
                                                                            <i class="fas fa-arrow-down"></i>
                                                                        <?php else : ?>
                                                                            <i class="fas fa-arrow-up"></i> <i class="fas fa-arrow-down"></i>
                                                                        <?php endif; ?>
                                                                    </span> -->
                                                                </th>
                                                                <th>Phone Number</th>
                                                                <th class="sortable" data-sort-type="start_date">Start Date/End Date
                                                                    <!-- <span class="sort-icon">
                                                                        <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'start_date' && isset($_GET['order']) && $_GET['order'] == 'asc') : ?>
                                                                            <i class="fas fa-arrow-up"></i>
                                                                        <?php elseif (isset($_GET['sort_by']) && $_GET['sort_by'] == 'start_date' && isset($_GET['order']) && $_GET['order'] == 'desc') : ?>
                                                                            <i class="fas fa-arrow-down"></i>
                                                                        <?php else : ?>
                                                                            <i class="fas fa-arrow-up"></i> <i class="fas fa-arrow-down"></i>
                                                                        <?php endif; ?>
                                                                    </span> -->
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <!-- <tbody>
                                                            <?php if (!empty($result)) : ?>
                                                                <?php
                                                                $currentMonthYear = date('F Y');
                                                                foreach ($result as $venueTitle => $locations) :
                                                                    // echo"<pre>";print_r($result);die;
                                                                    $venueRowspan = array_sum(array_map('count', $locations));
                                                                    $firstVenue = true;
                                                                    foreach ($locations as $locationName => $events) :
                                                                        $locationRowspan = count($events);
                                                                        $firstLocation = true;
                                                                        foreach ($events as $event) : ?>
                                                                            <tr>
                                                                                <?php if ($firstVenue) : ?>
                                                                                    <td rowspan="<?= $venueRowspan ?>">
                                                                                        <?= !empty($venueTitle) ? $event['position'] . ' - ' . $venueTitle : '' ?><br>
                                                                                        <?= $currentMonthYear ?>
                                                                                    </td>
                                                                                    <?php $firstVenue = false; ?>
                                                                                <?php endif; ?>
                                                                                <?php if ($firstLocation) : ?>
                                                                                    <td rowspan="<?= $locationRowspan ?>">
                                                                                        <?= !empty($locationName) ? $locationName : '' ?>
                                                                                    </td>
                                                                                    <?php $firstLocation = false; ?>
                                                                                <?php endif; ?>
                                                                                <td><?= !empty($event['location_address']) ? esc($event['location_address']) : '' ?></td>
                                                                                <td class="sort-type-event_title"><?= !empty($event['event_title']) ? esc($event['event_title']) : '' ?></td>
                                                                                <td><?= !empty($event['phone_number']) ? esc($event['phone_number']) : '' ?></td>
                                                                                <td class="sort-type-start_date"><?= !empty($event['start_date']) && !empty($event['end_date']) ? esc(stripslashes(strip_tags($event['start_date']))) . ' / ' . esc(stripslashes(strip_tags($event['end_date']))) : '' ?></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    <?php endforeach; ?>
                                                                <?php endforeach; ?>
                                                            <?php else : ?>
                                                                <tr>
                                                                    <td colspan="6" style="text-align:center;">No Data Available In Table</td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        </tbody> -->
                                                        <tbody>
                                                            <?php if (!empty($result)) : ?>
                                                                <?php
                                                                $currentMonthYear = date('F Y');
                                                                foreach ($result as $venueTitle => $locations) :
                                                                    $venueRowspan = array_sum(array_map('count', $locations));
                                                                    $firstVenue = true;
                                                                    foreach ($locations as $locationName => $events) :
                                                                        $locationRowspan = count($events);
                                                                        $firstLocation = true;
                                                                        foreach ($events as $event) : ?>
                                                                            <tr>
                                                                                <?php if ($firstVenue) : ?>
                                                                                    <td rowspan="<?= esc($venueRowspan) ?>">
                                                                                    <?= !empty($venueTitle) ? esc($event['position']) . ' - ' . esc($venueTitle) : '' ?><br>
                                                                                        <?= esc($currentMonthYear) ?>
                                                                                        </td>
                                                                                    <?php $firstVenue = false; ?>
                                                                                <?php endif; ?>
                                                                                <?php if ($firstLocation) : ?>
                                                                                    <td rowspan="<?= esc($locationRowspan) ?>">
                                                                                        <?= !empty($locationName) ? esc($locationName) : '' ?>
                                                                                    </td>
                                                                                    <?php $firstLocation = false; ?>
                                                                                <?php endif; ?>
                                                                                <td><?= !empty($event['location_address']) ? esc($event['location_address']) : '' ?></td>
                                                                                <td class="sort-type-event_title"><?= !empty($event['event_title']) ? esc($event['event_title']) : '' ?></td>
                                                                                <td><?= !empty($event['phone_number']) ? esc($event['phone_number']) : '' ?></td>
                                                                                <td class="sort-type-start_date">
                                                                                    <?= !empty($event['start_date']) && !empty($event['end_date'])
                                                                                        ? esc(stripslashes(strip_tags($event['start_date']))) . ' / ' . esc(stripslashes(strip_tags($event['end_date'])))
                                                                                        : '' ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    <?php endforeach; ?>
                                                                <?php endforeach; ?>
                                                            <?php else : ?>
                                                                <tr>
                                                                    <td colspan="6" style="text-align:center;">No Data Available In Table</td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        </tbody>

                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 col-md-5">
                                            <div class="dataTables_info" role="status" aria-live="polite"><?php echo $noOfContent; ?></div>
                                        </div>
                                        <div class="col-sm-12 col-md-7">
                                            <div class="dataTables_paginate paging_simple_numbers">
                                                <?php echo $PAGINATION; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>

</body>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const exportBtn = document.getElementById('export-btn');
        const form = document.getElementById('validate');

        exportBtn.addEventListener('click', () => {
            const actionUrl = exportBtn.getAttribute('data-href');
            form.action = actionUrl;
            form.submit();
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const exportBtn = document.getElementById('generate-report');
        const form = document.getElementById('validate');

        exportBtn.addEventListener('click', () => {
            const actionUrl = exportBtn.getAttribute('data-href');
            form.action = actionUrl;
            form.submit();
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const exportBtn = document.getElementById('serach-btn');
        const form = document.getElementById('validate');

        exportBtn.addEventListener('click', () => {
            const actionUrl = exportBtn.getAttribute('data-href');
            form.action = actionUrl;
            form.submit();
        });
    });
</script>

<!-- <script>
    $('th.sortable').on('click', function() {
        var column = $(this).index();
        var order = $(this).hasClass('asc') ? 'desc' : 'asc';
        $(this).toggleClass('asc desc');

        var rows = $('tbody tr');
        rows.sort(function(a, b) {
            var A = $(a).find('td').eq(column).text().toUpperCase();
            var B = $(b).find('td').eq(column).text().toUpperCase();

            if ($(this).data('sort-type') === 'start_date') {

                return order === 'asc' ? new Date(A) - new Date(B) : new Date(B) - new Date(A);
            } else {

                return order === 'asc' ? A.localeCompare(B) : B.localeCompare(A);
            }
        });

        $('tbody').html(rows);
    });
</script> -->
<!-- <script>
    function validateForm() {
        var locationSelect = document.getElementById('venue_id');
        if (locationSelect.value === "") {
            alert("Please select a location.");
            locationSelect.focus();
            return false;
        }
        return true;
    }
</script> -->