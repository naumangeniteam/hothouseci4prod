
<style>
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
    height: 175px !important;
    z-index: 2 !important;
    left: -69px !important;
    bottom: -60px !important;
  }

  /* .pagination {
    display: flex;
    justify-content: center;
    gap: 8px; 
    padding: 10px 0; 
 }

  .pagination li {
      list-style: none;
      padding: 0 5px; 
  }
  .pagination li a {
      padding: 8px 15px; 
      border-radius: 5px;
      border: 1px solid #ddd;
      color: #007bff;
      text-decoration: none;
      background-color: white;
      transition: background 0.3s ease;
  }

  .pagination li.active a {
      background-color: #007bff;
      color: white;
      border-color: #007bff;
  }

  .pagination li a:hover {
      background-color: #f8f9fa;
  } */


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
              <li class="breadcrumb-item"><a href="javascript:void(0);">Comedy and Spoken Word List</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h5>Manage Comedy and Spoken Word</h5>
            <a href="<?php echo base_url('hhjsitemgmt/adminmanagecomedyandspokenword/addeditdata'); ?>" class="btn btn-sm btn-primary pull-right">Add Content</a>
          </div>
          <div class="row">
            <div class="col-lg-12 col-sm-12 col-xs-12">
              <div class="widget">
                <div class="widget-body bordered-left bordered-warning">
                  <form class="form-inline" method="get" id="validate" novalidate="novalidate">
                    <div class="form-group"> <span class="input-icon">
                        <input type="text" class="form-control" style="margin:6px;padding:5px;" placeholder="Event Name" name="event_name" value="<?= htmlspecialchars($_GET['event_name'] ?? '') ?>">
                      </span>
                    </div>
                    <div class="form-group"> <span class="input-icon">
                        <input type="text" class="form-control" style="margin:4px;padding:2px;" placeholder="Event Location name" name="location_name" value="<?= htmlspecialchars($_GET['location_name'] ?? '') ?>">
                      </span>
                    </div>
                    <div class="form-group">
                      <input type="text" placeholder="MM/DD/YYYY" onfocus="this.type='date'" onblur="this.type='text'" name="start_date" style="margin:4px;padding:2px;" style="width:120px;" id="start_date" class="form-control" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>" />
                    </div>
                    <div class="form-group">
                      <input type="text" placeholder="MM/DD/YYYY" onfocus="this.type='date'" onblur="this.type='text'" name="end_date" style="margin:4px;padding:2px;" style="width:120px;" id="end" class="form-control" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>" />
                      <a class="btn btn-link reset" title="Refresh End Date"><i class=" fa fa-refresh"></i></a>
                    </div>
                    <div class="form-group">
                      <select name="venue_id" id="venue_id" style="margin:4px;padding:2px;" class="form-control">
                        <option value="">Select Venue</option>
                        <?php
                        if (!empty($venues)) :
                          foreach ($venues as $venue) : ?>
                            <option <?php if (isset($_GET['venue_id']) && $_GET['venue_id'] == $venue['id']) echo "selected"; ?> value="<?= htmlspecialchars($venue['id']) ?>"><?= htmlspecialchars($venue['venue_title']) ?></option>

                        <?php endforeach;
                        endif; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <select name="artist_id" id="artist_id" style="margin:4px;padding:2px;" class="form-control">
                        <option value="">Select Artist</option>
                        <?php
                        if (!empty($artistTypes)) {
                          foreach ($artistTypes as $artistType) {
                            $artistName = trim($artistType['artist_name']);
                            if (!empty($artistName)) {
                        ?>
                              <option <?= (isset($_GET['artist_id']) && $_GET['artist_id'] == $artistType['id']) ? 'selected' : '' ?> value="<?= $artistType['id'] ?>"><?= $artistName ?></option>
                        <?php
                            }
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="form-group d-none">
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

                    <div class="form-group d-none">
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
                    <div class="form-group p-2">
                      <div class="form-check">
                        <input name="search_boosted" class="form-check-input" type="checkbox" id="checkbox1" value=1 <?php if (isset($_GET['search_boosted']) && $_GET['search_boosted'] == 1) echo "checked"; ?>>
                        <label class="form-check-label" for="checkbox1">
                          See Boosted/Sponsored
                        </label>
                      </div>
                    </div>
                    <div class="form-group p-2">
                      <div class="form-check">
                        <input name="search_featured" class="form-check-input" type="checkbox" id="checkbox1" value=1 <?php if (isset($_GET['search_featured']) && $_GET['search_featured'] == 1) echo "checked"; ?>>
                        <label class="form-check-label" for="checkbox1">
                          See Featured
                        </label>
                      </div>
                    </div>
                    <!-- <div class="form-group p-2">
                      <div class="form-check">
                        <input name="search_api_data" class="form-check-input" type="checkbox" id="checkbox1" value=1 <?php if (isset($_GET['search_api_data']) && $_GET['search_api_data'] == 1) echo "checked"; ?>>
                        <label class="form-check-label" for="checkbox1">
                          See API Imported
                        </label>
                      </div>
                    </div> -->
                    <div class="form-group">
                      <input type="submit" class="btn1" style="height:40px;" value="Search" name="event_search">
                      <a href="<?= base_url('hhjsitemgmt/adminmanagecomedyandspokenword') ?>" class="btn btn-link">View All </a>
                    </div>
                  </form>

                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <?php
            $currentURL = current_url(); //http://myhost/main

            $params   = $_SERVER['QUERY_STRING']; //my_id=1,3

            $fullURL = $currentURL . '?' . $params;

            $fullURL;   //http://myhost/main?my_id=1,3
            ?>
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

                        <!-- <a href="<?php echo base_url('hhjsitemgmt/adminmanagecomedyandspokenword/import'); ?>" style="margin:3px;" class="btn btn-palegreen">Import</a> -->

                        <a href="<?= base_url('hhjsitemgmt/adminmanagecomedyandspokenword/index') ?>" style="margin:3px;" class="btn btn-palegreen">All Comedy and Spoken Word(<?= count($comedyandspokenword) ?>)</a>

                        <a href="<?= base_url('hhjsitemgmt/adminmanagepublishcomedyandspokenword') ?>" style="margin:3px;" class="btn btn-primary pull-right">Published Comedy and Spoken Word(<?= count($publishcomedyandspokenword) ?>)</a>

                        <a href="<?= base_url('hhjsitemgmt/adminmanagetrashcomedyandspokenword') ?>" style="margin:3px;" class="btn btn-primary pull-right">Trash Comedy and Spoken Word(<?= is_array($trashcomedyandspokenword) ? count($trashcomedyandspokenword) : 0 ?>
                        )</a>

                        <select name="bulk_option" style="margin:4px;padding:2px;" onchange="callMutlipleChangeStatus()">
                          <option value="">Bulk Option Comedy and Spoken Word</option>
                          <option value="active">Active</option>
                          <option value="inactive">Inactive</option>
                         
                          <option value="unboost">Unboost/Unsponsor</option> 
                          <option value="featured">Featured</option>
                          <option value="unfeatured">UnFeatured</option>
                          <option value="permanentdelete">Permanent Delete</option>
                        </select>

                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="table-responsive custom-data-table-wrapper2">
                        <table id="simpletable" class="table table-striped table-bordered nowrap dataTable custom-data-table" role="grid" aria-describedby="simpletable_info">
                          <thead class="text-nowrap">
                            <tr role="row">
                              <th><input type="checkbox" id="checkAll"></th>
                              <!-- <th>Sr.No.</th> -->
                              <th>Event Title</th>
                              <th>Source</th>
                              <th>Location</th>
                              <th>Venue</th>
                              <th>Start Date<br>End Date</th>
                              <th>Repeat?</th>
                              <th>Action</th>
                              <th>Artist</th>
                              <th>Status</th>
                              <th>Boost?</th>
                              <th>Featured?</th>

                            </tr>
                          </thead>
                          <tbody>
                            <?php
                           
                            $db = \Config\Database::connect();
                           
                            if (!empty($ALLDATA)): 
                              $i = $first;
                              $j = 0;
                              foreach ($ALLDATA as $ALLDATAINFO) :
                          
                                // if ($ALLDATAINFO['added_by'] != 'user' || $ALLDATAINFO['is_active'] != '0' ) :
                                 $query = $db->table('venue_tbl')
                                    ->select('venue_title')
                                    ->where('id', $ALLDATAINFO['venue_id'])
                                    ->get()
                                    ->getRow();
                                $getVenuName = $query->venue_title ?? 'N/A';
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
                                <tr role="row" class="<?php echo $rowClass; ?>">
                                  <td><input type="checkbox" name="checkbox_mutli" class="row-checkbox" value="<?= $ALLDATAINFO['event_id'] ?>"></td>
                                  <!-- <td style="width:50px;"><?= $i++ ?></td> -->
                                  <td style="width:100px;"><?= stripslashes(strip_tags($ALLDATAINFO['event_title'])) . $copy; ?></td>
                                  <td>
                                    <?php
                                    if (!empty($ALLDATAINFO['event_source'])) {
                                      echo '<b>' . stripslashes(strip_tags($ALLDATAINFO['event_source'])) . '</b>';
                                    } else {
                                      echo '<b>' . stripslashes(strip_tags($ALLDATAINFO['added_by'])) . '</b>';
                                    }
                                    ?>
                                  </td>
                                  <td style="width:70px;"><?= stripslashes(strip_tags($ALLDATAINFO['location_name'])) ?></td>
                                  <td style="width:70px;"><?= stripslashes(strip_tags($getVenuName->venue_title)) ?></td>
                                  <td style="width:120px;">
                                    <?= stripslashes(strip_tags($ALLDATAINFO['start_date'])) . ' / ' . stripslashes(strip_tags($ALLDATAINFO['end_date'])) ?>
                                  </td>
                                  <td style="width:40px;"><?= stripslashes(strip_tags($ALLDATAINFO['repeating_event'])) ?></td>
                                  <td style="width:100px;">
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                                      <ul class="dropdown-menu dropdown-menu-show" role="menu">
                                        <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagecomedyandspokenword/addeditdata/' . $ALLDATAINFO['event_id']) ?>"><i class="fas fa-edit"></i> Edit Details</a></li>

                                        <?php if ($ALLDATAINFO['is_active'] == '1') : ?>
                                          <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagecomedyandspokenword/changestatus/' . $ALLDATAINFO['event_id'] . '/0') ?>"><i class="fas fa-thumbs-down"></i> Inactive</a></li>
                                        <?php elseif ($ALLDATAINFO['is_active'] == '0') : ?>
                                          <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagecomedyandspokenword/changestatus/' . $ALLDATAINFO['event_id'] . '/1') ?>"><i class="fas fa-thumbs-up"></i> Active</a></li>
                                        <?php endif; ?>

                                        <?php if ($ALLDATAINFO['is_featured'] == '1') : ?>
                                          <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagecomedyandspokenword/statusfeatured/' . $ALLDATAINFO['event_id'] . '/0') ?>"><i class="fas fa-thumbs-down"></i> NotFeatured </a></li>
                                        <?php elseif ($ALLDATAINFO['is_featured'] == '0') : ?>
                                          <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagecomedyandspokenword/statusfeatured/' . $ALLDATAINFO['event_id'] . '/1') ?>"><i class="fas fa-thumbs-up"></i> Featured </a></li>
                                        <?php endif; ?>

                                        <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagecomedyandspokenword/deletedata/' . $ALLDATAINFO['event_id']) ?>" onClick="return confirm('Want to delete Permanent!');"><i class="fas fa-trash"></i>Permanent Delete</a></li>
                                        <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagecomedyandspokenword/updateStatus/' . $ALLDATAINFO['event_id']) ?>" onClick="return confirm('Want to trash!');"><i class="fas fa-trash"></i> Trash</a></li>
                                        <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagecomedyandspokenword/duplicate/' . $ALLDATAINFO['event_id']) ?>"><i class="fas fa-edit"></i>Duplicate</a></li>
                                      </ul>
                                    </div>
                                  </td>
                                  <td style="width:70px;">
                                    <a class="artists" href="<?php echo base_url('hhjsitemgmt/adminmanageartist/addeditdata/' . $ALLDATAINFO['artist_id']) ?>" target="_blank">
                                      <?= stripslashes(strip_tags($getartistName->artist_name)) ?>
                                  
                                     <?= esc(model('App\Models\CommonModel')->artistData($ALLDATAINFO['artist_id'])); ?>


                                    </a>
                                  </td>

                                  <td style="width:70px;"><?= showStatus($ALLDATAINFO['is_active']) ?></td>

                                  <td data-event-id="<?php echo $ALLDATAINFO['event_id']; ?>">
                                    <h6>Boost Requested - <?= ($ALLDATAINFO['requested_boost'] == 1) ? 'Yes' : 'No' ?></h6>
                                    <select class="boostSelect" onchange="handleSelectChange(this)">
                                      <option value="">Boost</option>
                                      <option value="yes" <?php if ($ALLDATAINFO['is_boosted'] == 1) echo ' selected'; ?>>Yes</option>
                                      <option value="no" <?php if ($ALLDATAINFO['is_boosted'] == 0) echo ' selected'; ?>>No</option>
                                    </select>
                                    <input type="number" class="daysInput number mt-2" name="boost_days" placeholder="Enter number of days" <?php if ($ALLDATAINFO['is_boosted'] == 0) echo ' style="display: none;"'; ?> oninput="saveDays()" <?php if ($ALLDATAINFO['boost_days'] != 0) echo 'value="' . $ALLDATAINFO['boost_days'] . '"'; ?>>

                                    <input type="date" class="dateInput mt-2" name="boost_date" <?php if (empty($ALLDATAINFO['boost_date']) || $ALLDATAINFO['is_boosted'] == 0) echo ' style="display: none;"'; ?> onchange="saveDate()" <?php if (!empty($ALLDATAINFO['boost_date']) && $ALLDATAINFO['boost_date'] != '0000-00-00') echo 'value="' . $ALLDATAINFO['boost_date'] . '"'; ?>>
                                  </td>

                                  <td style="width:70px;">
                                    <?php
                                    echo (isset($ALLDATAINFO['is_featured']) && $ALLDATAINFO['is_featured'] == '1') ? 'Yes' : 'No';
                                    ?>
                                  </td>

                                </tr>
                              <?php
                                $j++;
                              // endif;

                              endforeach;

                            else :
                              ?>
                              <tr>
                                <td colspan="12" style="text-align:center;">No Data Available In Table</td>
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
  document.getElementById('checkAll').addEventListener('change', function() {
    var checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
      checkboxes[i].checked = this.checked;
    }
  });
</script>

<script>
  function getCheckedIds() {
    var checkedIds = [];
    var checkboxes = document.querySelectorAll('input[name="checkbox_mutli"]:checked');
    for (var i = 0; i < checkboxes.length; i++) {
      checkedIds.push(checkboxes[i].value);
    }
    return checkedIds;
  }
</script>

<script>
  function callMutlipleChangeStatus() {
    var selectElement = document.querySelector('select[name="bulk_option"]');
    var statusType = selectElement.value;
    var checkedIds = getCheckedIds();
    if (checkedIds.length === 0) {
      alert('Please select at least one item.');
      return;
    }

    console.log(statusType);

    fetch('<?php echo base_url('hhjsitemgmt/adminmanagecomedyandspokenword/mutlipleChangeStatus'); ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'changeStatusIds=' + JSON.stringify(checkedIds) + '&statusType=' + statusType
      })
      .then(response => {
        if (response.ok) {
          location.reload();
        } else {

          throw new Error('Error occurred');
        }
      })
      .catch(error => {});
  }
</script>

<script>
  // document.addEventListener('DOMContentLoaded', (event) => {
  //   var savedSelectValue = localStorage.getItem('boostSelectValue');
  //   var savedDaysValue = localStorage.getItem('daysInputValue');
  //   var savedDateValue = localStorage.getItem('dateInputValue');

  //   var selectElements = document.querySelectorAll('.boostSelect');

  //   selectElements.forEach((select) => {
  //     if (savedSelectValue && select.value === savedSelectValue) {
  //       select.value = savedSelectValue;
  //       handleSelectChange(select);
  //     }

  //     var daysInput = select.nextElementSibling;
  //     var dateInput = daysInput.nextElementSibling;

  //     if (savedDaysValue) {
  //       daysInput.value = savedDaysValue;
  //     }

  //     if (savedDateValue) {
  //       dateInput.value = savedDateValue;
  //     }
  //   });
  // });

  function handleSelectChange(select) {
    var daysInput = select.nextElementSibling;
    var dateInput = daysInput.nextElementSibling;

    console.log(select.value);

    // localStorage.setItem('boostSelectValue', select.value);

    if (select.value === 'yes') {
      daysInput.style.display = 'block';
      dateInput.style.display = 'block';
    } else {
      daysInput.style.display = 'none';
      dateInput.style.display = 'none';
      // Clear the input fields and localStorage if 'No' is selected
      daysInput.value = '';
      dateInput.value = '';
      // localStorage.removeItem('daysInputValue');
      // localStorage.removeItem('dateInputValue');

    }
    sendData(select);
  }

  function saveDays() {
    var days = event.target.value;
    // localStorage.setItem('daysInputValue', days);
    sendData(event.target.previousElementSibling);
  }

  function saveDate() {
    var date = event.target.value;
    // localStorage.setItem('dateInputValue', date);
    sendData(event.target.previousElementSibling.previousElementSibling);
  }

  function sendData(select) {
    var selectValue = select.value;
    if (selectValue !== '') {
      var daysInput = select.nextElementSibling;
      var dateInput = daysInput.nextElementSibling;
      var days = daysInput.value;
      var date = dateInput.value;
      var eventId = select.closest('td[data-event-id]').dataset.eventId;
      // console.log('Event ID:', eventId);

      // if (days && date) {
      $.ajax({
        url: '<?php echo base_url('hhjsitemgmt/adminmanagecomedyandspokenword/boostDays'); ?>',
        type: 'POST',
        data: {
          is_boosted: selectValue === "yes" ? 1 : 0,
          boost_days: days,
          boost_date: date,
          event_id: eventId
        },
        success: function(response) {
          console.log('Response:', response);
        },
        error: function(error) {
          console.error('Error saving data:', error);
        }
      });
      // }
    }
  }
</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    let parentDivForTableTopScrollBar = document.createElement(
      "div"
    );
    parentDivForTableTopScrollBar.classList.add(
      "custom-data-table-wrapper1",
      "sticky-top"
    );

    let innerDivForTableTopScrollBar = document.createElement(
      "div"
    );
    innerDivForTableTopScrollBar.classList.add(
      "custom-data-table-top-scrollbar"
    );

    parentDivForTableTopScrollBar.appendChild(
      innerDivForTableTopScrollBar
    );

    let customDataTableWrapper2 = document.querySelector(
      ".custom-data-table-wrapper2"
    );
    customDataTableWrapper2.parentNode.insertBefore(
      parentDivForTableTopScrollBar,
      customDataTableWrapper2
    );

    let customDataTableWrapper1 = document.querySelector(
      ".custom-data-table-wrapper1"
    );

    // Add a scroll event listener to customDataTableWrapper1
    customDataTableWrapper1.addEventListener(
      "scroll",
      function() {
        customDataTableWrapper2.scrollLeft =
          customDataTableWrapper1.scrollLeft;
      },
      false
    );

    // Add a scroll event listener to customDataTableWrapper2
    customDataTableWrapper2.addEventListener(
      "scroll",
      function() {
        customDataTableWrapper1.scrollLeft =
          customDataTableWrapper2.scrollLeft;
      },
      false
    );

    let customDataTable = document.querySelector(
      ".custom-data-table"
    );
    let customDataTableWidth = customDataTable.offsetWidth;
    document.querySelector(
      ".custom-data-table-top-scrollbar"
    ).style.width = customDataTableWidth + "px";
  });
</script>