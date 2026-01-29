<?php
$db = \Config\Database::connect();
?>
<style>
  .sort-icon {
    cursor: pointer;
  }

  .dropdown-menu-show {
    height: 90px !important;
    z-index: 2 !important;
    left: -60px !important;
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
              <?php /* ?><h5 class="m-b-10">Welcome <?=sessionData('ILCADM_ADMIN_FIRST_NAME')?></h5><?php */ ?>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="javascript:void(0);">Location Submitted List</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h5>Manage Location Submitted List</h5>
            <!-- <a href="<?php echo getCurrentControllerPath('addeditdata'); ?>" class="btn btn-sm btn-primary pull-right">Add Event Location</a> -->
          </div>
          <div class="card-body">
            <form id="Data_Form" name="Data_Form" method="get" action="<?php echo $forAction; ?>">
              <div class="dt-responsive table-responsive">
                <div id="simpletable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                  <div class="row">
                    <div class="col-sm-12 col-md-3">
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
                    <div class="col-sm-12 col-md-3">
                      <div class="row" style="margin:0px;">
                        <div class="col-sm-12 col-md-8">
                          <input type="text" name="searchValue" id="searchValue" value="<?php echo $searchValue; ?>" class="form-control form-control-sm" placeholder="Enter Search Text">
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-3">
                      <div class="form-group" style="margin-left: 5px;">
                        <select name="location_name" id="location_name" style="margin: 4px; padding: 2px;" class="form-control">
                          <option value="">Select Venue</option>
                          <?php
                          if (!empty($locations)) {
                            foreach ($locations as $location) {
                              $locationName = trim($location['location_name']);
                              if (!empty($locationName)) {
                          ?>
                                <option <?= (isset($_GET['location_name']) && $_GET['location_name'] == $location['location_name']) ? 'selected' : '' ?> value="<?= $location['location_name'] ?>"><?= $locationName ?></option>
                          <?php
                              }
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-3">
                      <div class="row">
                        <div class="form-group d-flex ">
                          <select name="county" id="county" style="margin: 4px; padding: 2px;" class="form-control">
                            <option value="">Select County</option>
                            <?php if (!empty($counties)): ?>
                              <?php foreach ($counties as $county): ?>
                                <option value="<?php echo $county['county']; ?>" <?php echo ($county['county'] == $countyValue) ? 'selected' : ''; ?>><?php echo $county['county']; ?>
                                </option>
                              <?php endforeach; ?>
                            <?php endif; ?>
                          </select>
                          <button type="submit" class="btn btn-primary btn-sm " style="margin-left: 10px; margin-top: 5px;height: 40px;">Submit</button>
                        </div>

                      </div>
                    </div>
                    <div class="col-sm-12 col-md-3">
                      <select name="bulk_option" style="margin:4px;padding:2px;" onchange="moveToVenue()">
                        <option value="">Move to..</option>
                        <?php
                        if (!empty($venues)) :
                          foreach ($venues as $venue) : ?>
                            <option value="<?= $venue['id'] ?>"><?= $venue['venue_title']; ?></option>
                        <?php endforeach;
                        endif; ?>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="table-responsive">
                        <table id="simpletable" class="table table-striped table-bordered nowrap dataTable" role="grid" aria-describedby="simpletable_info">
                          <thead>
                            <tr role="row">
                              <th><input type="checkbox" id="checkAll"></th>
                              <th>Location Name</th>
                              <th>Location Address</th>
                              <th>State</th>
                              <th>County</th>
                              <th class="sortable sort-on" data-sort-type="venue_id">Venue
                                <span class="sort-icon">
                                  <?php if (isset($_GET['sort_by']) && $_GET['sort_by'] == 'venue_id' && isset($_GET['order']) && $_GET['order'] == 'asc') : ?>
                                    <i class="fas fa-arrow-up"></i>
                                  <?php elseif (isset($_GET['sort_by']) && $_GET['sort_by'] == 'venue_id' && isset($_GET['order']) && $_GET['order'] == 'desc') : ?>
                                    <i class="fas fa-arrow-down"></i>
                                  <?php else : ?>
                                    <i class="fas fa-arrow-up"></i> <i class="fas fa-arrow-down"></i>
                                  <?php endif; ?>
                                </span>
                              </th>
                              <th>Event Location Type</th>
                              <th>Contact Person Details</th>
                              <th>Status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php if (!empty($ALLDATA)): $i = $first;

                              $j = 0;
                              // echo"<pre>"; print_r($ALLDATA); die;
                              foreach ($ALLDATA as $ALLDATAINFO) :
                                if ($ALLDATAINFO['added_by'] != 'user' || $ALLDATAINFO['is_active'] != '0') {
                                  $query = $db->table('venue_tbl')
                                    ->select('venue_title, position')
                                    ->where('id', $ALLDATAINFO['venue_id'])
                                    ->get()
                                    ->getRow();
                                  $getVenuName = $query->venue_title ?? 'N/A';
                                }
                                $query = $db->table('event_location_type')
                                  ->select('name, is_active')
                                  ->where('id', $ALLDATAINFO['event_location_type_id'])
                                  ->get()
                                  ->getRow();
                                $getEventLocationName = $query->name ?? 'N/A';
                                // echo"<pre>"; print_r($getEventLocationName); die;

                                if ($j % 2 == 0) : $rowClass = 'odd';
                                else : $rowClass = 'even';
                                endif;
                                // echo"<pre>"; print_r($ALLDATAINFO); die;
                            ?>
                                <tr role="row" style="<?php echo $ALLDATAINFO['is_front'] == '1' ? 'background-color:  #ffe6e6;' : ''; ?>" class="<?php echo $rowClass; ?>">
                                  <td><input type="checkbox" name="checkbox_mutli" class="row-checkbox" value="<?= $ALLDATAINFO['id'] ?>"></td>
                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['location_name'])) ?></td>
                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['location_address'])) ?></td>
                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['state'])) ?></td>
                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['county'])) ?></td>
                                  <?php if (!empty($getVenuName->venue_title)) : ?>
                                    <td class="sort-type"> <?= stripslashes(strip_tags($getVenuName->venue_title)) ?></td>
                                  <?php else : ?>
                                    <td class="sort-type"> No Venue </td>
                                  <?php endif; ?>
                                  <td><?= !empty($getEventLocationName->name) ? htmlspecialchars($getEventLocationName->name) : 'No Type' ?></td>
                                  <td>
                                    <?= stripslashes(strip_tags($ALLDATAINFO['contact_person_name'])) ?><br>
                                    <?= stripslashes(strip_tags($ALLDATAINFO['contact_person_email'])) ?><br>
                                    <?= stripslashes(strip_tags($ALLDATAINFO['contact_person_phone_number'])) ?><br>
                                    <?= stripslashes(strip_tags($ALLDATAINFO['contact_person_title'])) ?>
                                  </td>
                                  <td><?= showStatus($ALLDATAINFO['is_active']) ?></td>
                                  <td>
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                                      <ul class="dropdown-menu dropdown-menu-show" role="menu">
                                        <li><a href="<?php echo getCurrentControllerPath('addeditdata/' . $ALLDATAINFO['id']) ?>"><i class="fas fa-edit"></i> Edit Details</a></li>
                                        <?php if ($ALLDATAINFO['is_active'] == '1') : ?>
                                          <li><a href="<?php echo getCurrentControllerPath('changestatus/' . $ALLDATAINFO['id'] . '/0') ?>"><i class="fas fa-thumbs-down"></i> Inactive</a></li>
                                        <?php elseif ($ALLDATAINFO['is_active'] == '0') : ?>
                                          <li><a href="<?php echo getCurrentControllerPath('changestatus/' . $ALLDATAINFO['id'] . '/1') ?>"><i class="fas fa-thumbs-up"></i> Active</a></li>
                                        <?php endif; ?>

                                        <li><a href="<?php echo getCurrentControllerPath('deletedata/' . $ALLDATAINFO['id']) ?>" onClick="return confirm('Want to delete!');"><i class="fas fa-trash"></i> Delete</a></li>

                                      </ul>
                                    </div>
                                  </td>
                                </tr>
                              <?php $j++;
                              endforeach;
                            else : ?>
                              <tr>
                                <td colspan="10" style="text-align:center;">No Data Available In Table</td>
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
  function moveToVenue() {
    var selectElement = document.querySelector('select[name="bulk_option"]');
    var venueId = selectElement.value;
    var checkedIds = getCheckedIds();
    if (checkedIds.length === 0) {
      alert('Please select at least one item.');
      return;
    }
    if (!confirm('This will move related events too! Continue ?')) {
      return;
    }

    // console.log(statusType);
    // return;

    fetch('<?php echo base_url('hhjsitemgmt/adminmanagelocationsubmittedlist/moveToVenue'); ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'changeStatusIds=' + JSON.stringify(checkedIds) + '&venueId=' + venueId
      })
      .then(response => {
        if (response.ok) {
          // location.reload();
        } else {

          throw new Error('Error occurred');
        }
      })
      .catch(error => {});
  }
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

      return order === 'asc' ? A.localeCompare(B) : B.localeCompare(A);

    });

    $('tbody').html(rows);
  });
</script>