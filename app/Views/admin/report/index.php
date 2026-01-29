<?php
$db = \Config\Database::connect();
?>

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
                                        <input type="hidden" name="event_title" id="event_title" value="<?= htmlspecialchars($_GET['event_title'] ?? '') ?>" />
                                        <input type="hidden" name="start_date" id="start_date" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>" />
                                        <!-- <div class="form-group"> <span class="input-icon">
                                                <input type="text" class="form-control" style="margin:6px;padding:5px;" placeholder="Event Name" name="event_name" value="<?= htmlspecialchars($_GET['event_name'] ?? '') ?>">
                                            </span>
                                        </div>
                                        <div class="form-group"> <span class="input-icon">
                                                <input type="text" class="form-control" style="margin:4px;padding:2px;" placeholder="Event Location name" name="location_name" value="<?= htmlspecialchars($_GET['location_name'] ?? '') ?>">
                                            </span>
                                        </div> -->

                                        <div class="form-group">
                                            <select name="venue_id" id="venue_id" style="margin:4px;padding:2px;" class="form-control">
                                                <option value="">Select Location</option>
                                                <?php
                                                if (!empty($venues)) :
                                                    foreach ($venues as $venue) : ?>
                                                        <option <?php if (isset($_GET['venue_id']) && $_GET['venue_id'] == $venue['id']) echo "selected"; ?> value="<?= htmlspecialchars($venue['id']) ?>"><?= htmlspecialchars($venue['venue_title']) ?></option>
                                                <?php endforeach;
                                                endif; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <select name="location_name" id="location_name" style="margin: 4px; padding: 2px;" class="form-control">
                                                <option value="">Select Venue</option>
                                                <?php
                                                if (!empty($locations)) {
                                                    foreach ($locations as $location) {
                                                        $locationName = trim($location->location_name);
                                                        if (!empty($locationName)) {
                                                ?>
                                                            <option <?= (isset($_GET['location_name']) && $_GET['location_name'] == $location->location_name) ? 'selected' : '' ?> value="<?= $location->location_name ?>"><?= $locationName ?></option>
                                                <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <select name="state" id="state" style="margin:4px;padding:2px;" class="form-control">
                                                <option value="">Select State</option>
                                                <?php
                                                if (!empty($states)) {
                                                    foreach ($states as $state) {
                                                        $stateName = trim($state->state);
                                                        if (!empty($stateName)) {
                                                ?>
                                                            <option <?= (isset($_GET['state']) && $_GET['state'] == $state->state) ? 'selected' : '' ?> value="<?= $state->state ?>"><?= $stateName ?></option>
                                                <?php
                                                        }
                                                    }
                                                }
                                                ?>

                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <select name="city" id="city" style="margin: 4px; padding: 2px;" class="form-control">
                                                <option value="">Select City</option>
                                                <?php
                                                if (!empty($cities)) {
                                                    foreach ($cities as $city) {
                                                        $cityName = trim($city->city);
                                                        if (!empty($cityName)) {
                                                ?>
                                                            <option <?= (isset($_GET['city']) && $_GET['city'] == $city->city) ? 'selected' : '' ?> value="<?= $city->city ?>"><?= $cityName ?></option>
                                                <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <select name="artist_id" id="artist_id" style="margin:4px;padding:2px;" class="form-control">
                                                <option value="">Select Artist</option>
                                                <?php
                                                if (!empty($artistTypes)) {
                                                    foreach ($artistTypes as $artistType) {
                                                        $artistName = trim($artistType->artist_name);
                                                        if (!empty($artistName)) {
                                                ?>
                                                            <option <?= (isset($_GET['artist_id']) && $_GET['artist_id'] == $artistType->id) ? 'selected' : '' ?> value="<?= $artistType->id ?>"><?= $artistName ?></option>
                                                <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" placeholder="MM/DD/YYYY" onfocus="this.type='date'" onblur="this.type='text'" name="start_date" style="margin:4px;padding:2px;" style="width:120px;" id="start_date" class="form-control" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" placeholder="MM/DD/YYYY" onfocus="this.type='date'" onblur="this.type='text'" name="end_date" style="margin:4px;padding:2px;" style="width:120px;" id="end" class="form-control" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>" />
                                            <a class="btn btn-link reset" title="Refresh End Date"><i class=" fa fa-refresh"></i></a>
                                        </div>

                                        <div class="form-group">
                                            <input type="submit" class="btn1" style="height:40px;" value="Search" name="event_search">
                                            <a href="<?= base_url('hhjsitemgmt/adminmanagereport/index') ?>" class="btn btn-link">View All </a>
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
                                                <a href="javascript:" id="export-btn" data-href="<?php echo base_url('hhjsitemgmt/adminmanagereport/export_excel'); ?>" style="margin:3px;" class="btn btn-primary pull-right">Export</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive custom-data-table-wrapper2">
                                                <table id="simpletable" class="table table-striped table-bordered nowrap dataTable custom-data-table" role="grid" aria-describedby="simpletable_info">
                                                    <thead class="text-nowrap">
                                                        <tr role="row">
                                                            <th>Sr.No.</th>
                                                            <th class="sortable sort-on" data-sort-type="event_title">Event Title
                                                                <span class="sort-icon">
                                                                    <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'event_title' && isset($_GET['order']) && $_GET['order'] == 'asc') : ?>
                                                                        <i class="fas fa-arrow-up"></i>
                                                                    <?php elseif (isset($_GET['sort_by']) && $_GET['sort_by'] == 'event_title' && isset($_GET['order']) && $_GET['order'] == 'desc') : ?>
                                                                        <i class="fas fa-arrow-down"></i>
                                                                    <?php else : ?>
                                                                        <i class="fas fa-arrow-up"></i> <i class="fas fa-arrow-down"></i>
                                                                    <?php endif; ?>
                                                                </span>
                                                            </th>
                                                            <th>Location</th>
                                                            <th>Venue</th>
                                                            <th>State</th>
                                                            <th>City</th>
                                                            <th>Artists</th>
                                                            <th class="sortable sort-on" data-sort-type="start_date">Start Date/End Date
                                                                <span class="sort-icon">
                                                                    <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'start_date' && isset($_GET['order']) && $_GET['order'] == 'asc') : ?>
                                                                        <i class="fas fa-arrow-up"></i>
                                                                    <?php elseif (isset($_GET['sort_by']) && $_GET['sort_by'] == 'start_date' && isset($_GET['order']) && $_GET['order'] == 'desc') : ?>
                                                                        <i class="fas fa-arrow-down"></i>
                                                                    <?php else : ?>
                                                                        <i class="fas fa-arrow-up"></i> <i class="fas fa-arrow-down"></i>
                                                                    <?php endif; ?>
                                                                </span>
                                                            </th>


                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (!empty($ALLDATA)): 
                                                            $i = $first;
                                                            $j = 0;
                                                            foreach ($ALLDATA as $ALLDATAINFO) :
                                                                if ($ALLDATAINFO['added_by'] != 'user' || $ALLDATAINFO['is_active'] != '0') :
                                                                    $query = $db->table('venue_tbl')
                                                                    ->select('venue_title, position')
                                                                    ->where('id', $ALLDATAINFO['venue_id'])
                                                                    ->get()
                                                                    ->getRow();
                                                                $getVenuName = $query->venue_title ?? 'N/A';

                                                                $query = $db->table('event_location_tbl')
                                                                ->select('state')
                                                                ->where('id', $ALLDATAINFO['save_location_id'])
                                                                ->get()
                                                                ->getRow();
                                                                $getStateName = $query->venue_title ?? 'N/A';

                                                                    $query = $db->table('event_location_tbl')
                                                                            ->select('city')
                                                                            ->where('id', $ALLDATAINFO['save_location_id'])
                                                                            ->get()
                                                                            ->getRow();
                                                                        $getCityName = $query->venue_title ?? 'N/A';
                                                                        
                                                                        $query = $db->table('artist_tbl')
                                                                    ->select('artist_name')
                                                                    ->where('id', $ALLDATAINFO['artist_id'])
                                                                    ->get()
                                                                    ->getRow();
                                                                $getartistName = $query->name ?? 'N/A';
                                                                
                                                                        if ($j % 2 == 0) :
                                                                        $rowClass = 'odd';
                                                                    else :
                                                                        $rowClass = 'even';
                                                                    endif;
                                                                    $copy = '';
                                                                    if ($ALLDATAINFO['no_of_copy'] != '0') {
                                                                        for ($n = 1; $n <= $ALLDATAINFO['no_of_copy']; $n++) {
                                                                            $copy .= "(copy)";
                                                                        }
                                                                    } else {
                                                                        $copy = '';
                                                                    }
                                                        ?>
                                                                    <tr role="row" style="<?php echo $ALLDATAINFO['is_front'] == '1' ? 'background-color:  #ffe6e6;' : ''; ?>" class="<?php echo $rowClass; ?>">
                                                                        <td style="width:50px;"><?= $i++ ?></td>
                                                                        <td class="sort-type" style="width:100px;"><?= stripslashes(strip_tags($ALLDATAINFO['event_title'])) . $copy; ?></td>
                                                                        <td style="width:70px;"><?= stripslashes(strip_tags($getVenuName->venue_title)) ?></td>
                                                                        <td style="width:70px;"><?= stripslashes(strip_tags($ALLDATAINFO['location_name'])) ?></td>
                                                                        <td><?= stripslashes(strip_tags($getStateName->state)) ?></td>
                                                                        <td><?= stripslashes(strip_tags($getCityName->city)) ?></td>
                                                                        <td style="width:70px;">
                                                                            <a class="artists" href="<?php echo base_url('hhjsitemgmt/adminmanageartist/addeditdata/' . $ALLDATAINFO['artist_id']) ?>" target="_blank">
                                                                                <?= stripslashes(strip_tags($getartistName->artist_name)) ?>
                                                                            </a>
                                                                        </td>

                                                                        <td class="sort-type" style="width:120px;">
                                                                            <?= stripslashes(strip_tags($ALLDATAINFO['start_date'])) . ' / ' . stripslashes(strip_tags($ALLDATAINFO['end_date'])) ?>
                                                                        </td>


                                                                    </tr>
                                                            <?php
                                                                    $j++;
                                                                endif;
                                                            endforeach;
                                                        else :
                                                            ?>
                                                            <tr>
                                                                <td colspan="8" style="text-align:center;">No Data Available In Table</td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
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
</script>