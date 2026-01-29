<style>
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
    height: 120px !important;
    z-index: 2 !important;
    left: -97px !important;
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
              <li class="breadcrumb-item"><a href="javascript:void(0);">Event Submitted List</a></li>

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
            <h5>Manage Event Submitted List</h5>
            <a href="<?php echo base_url('hhjsitemgmt/adminmanageuserpost/addeditdata'); ?>" class="btn btn-sm btn-primary pull-right">Add Content</a>
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
                        <input type="text" name="searchValue" id="searchValue" value="<?php echo $searchValue; ?>" class="form-control form-control-sm" placeholder="Enter Search Text">
                    </div>
                    <div class="col-sm-12 col-md-3">
                      <select name="bulk_option" style="margin:4px;padding:2px;" onchange="callMutlipleChangeStatus()">
                        <option value="">Bulk Option Events</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <!-- <option value="boost">Boost</option>
                        <option value="unboost">Unboost</option> -->
                        <option value="featured">Featured</option>
                        <option value="unfeatured">UnFeatured</option>
                        <option value="importEvent">Import Event</option>
                        <option value="permanentdelete">Permanent Delete</option>
                      </select>
                    </div>
                    <div class="col-sm-12 col-md-3">
                    <a href="<?php echo base_url('hhjsitemgmt/adminmanageuserpost/export_excel'); ?>" class="btn btn-sm btn-primary pull-right">Export</a>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="table-responsive custom-data-table-wrapper2">
                        <table id="simpletable" class="table table-striped table-bordered nowrap dataTable custom-data-table" role="grid" aria-describedby="simpletable_info">
                          <thead class="text-nowrap">
                            <tr role="row">
                              <th><input type="checkbox" id="checkAll"></th>
                              <th>Sr.No.</th>
                              <th> Title</th>
                              <th>Start Date <br /> End Date</th>
                              <!-- <th>Event End Date</th> -->
                              <th>Start Time</th>
                              <th>IP </th>
                              <!--<th>Repeating Event</th>-->
                              <!--<th>Choose Saved Venue</th>-->
                              <th>Action</th>
                              <th> Venue Name</th>
                              <th> Venue Address</th>
                              <th>Contact Person Name</th>
                              <th>Contact Email</th>
                              <th>Status</th>
                              <th>Boost ?</th>
                              <th>Featured ?</th>

                            </tr>
                          </thead>
                          <tbody>
                          <?php if (!empty($ALLDATA)): 
                            $i = $first;
                              $j = 0;
                              foreach ($ALLDATA as $ALLDATAINFO) :
                                // echo"<pre>";print_r($ALLDATAINFO);die;
                                if ($j % 2 == 0) : $rowClass = 'odd';
                                else : $rowClass = 'even';
                                endif;
                            ?>
                                <tr role="row" style="<?php echo $ALLDATAINFO['added_by'] == 'user' ? 'background-color:  #ffe6e6;' : ''; ?>" class="<?php echo $rowClass; ?>">
                                  <td><input type="checkbox" name="checkbox_mutli" class="row-checkbox" value="<?= $ALLDATAINFO['event_id'] ?>"></td>
                                  <td><?= $i++ ?></td>
                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['event_title'])) ?></td>
                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['start_date'])) . ' / ' . stripslashes(strip_tags($ALLDATAINFO['end_date'])) ?></td>
                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['event_start_time'])) ?></td>
                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['ip_address'])) ?></td>
                                  <!--<td><?= stripslashes(strip_tags($ALLDATAINFO['repeating_event'])) ?></td>
                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['save_location_id'])) ?></td>-->

                                  <td>
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>

                                      <ul class="dropdown-menu dropdown-menu-show" role="menu">
                                        <li><a href="<?php echo base_url('hhjsitemgmt/adminmanageuserpost/addeditdata/' . $ALLDATAINFO['event_id']) ?>"><i class="fas fa-edit"></i> Edit Details</a></li>
                                        <?php if ($ALLDATAINFO['is_active'] == '1') : ?>
                                          <li><a href="<?php echo base_url('hhjsitemgmt/adminmanageuserpost/changestatus/' . $ALLDATAINFO['event_id'] . '/0') ?>"><i class="fas fa-thumbs-down"></i> Inactive</a></li>
                                        <?php elseif ($ALLDATAINFO['is_active'] == '0') : ?>
                                          <li><a href="<?php echo base_url('hhjsitemgmt/adminmanageuserpost/changestatus/' . $ALLDATAINFO['event_id'] . '/1') ?>"><i class="fas fa-thumbs-up"></i> Active</a></li>
                                        <?php endif; ?>

                                        <!-- <?php if ($ALLDATAINFO['is_boosted'] == '1') : ?>
                                          <li><a href="<?php echo base_url('hhjsitemgmt/adminmanageuserpost/statusboost/' . $ALLDATAINFO['event_id'] . '/0') ?>"><i class="fas fa-thumbs-down"></i>Unboost</a></li>
                                        <?php elseif ($ALLDATAINFO['is_boosted'] == '0') : ?>
                                          <li><a href="<?php base_url('hhjsitemgmt/adminmanageuserpost/statusboost/' . $ALLDATAINFO['event_id'] . '/1') ?>"><i class="fas fa-thumbs-up"></i>Boost</a></li>
                                        <?php endif; ?> -->

                                        <?php if ($ALLDATAINFO['is_featured'] == '1') : ?>
                                          <li><a href="<?php echo base_url('hhjsitemgmt/adminmanageuserpost/statusfeatured/' . $ALLDATAINFO['event_id'] . '/0') ?>"><i class="fas fa-thumbs-down"></i> NotFeatured </a></li>
                                        <?php elseif ($ALLDATAINFO['is_featured'] == '0') : ?>
                                          <li><a href="<?php echo base_url('hhjsitemgmt/adminmanageuserpost/statusfeatured/' . $ALLDATAINFO['event_id'] . '/1') ?>"><i class="fas fa-thumbs-up"></i> Featured </a></li>
                                        <?php endif; ?>

                                        <li><a href="<?php echo base_url('hhjsitemgmt/adminmanageuserpost/deletedata/' . $ALLDATAINFO['event_id']) ?>" onClick="return confirm('Want to delete!');"><i class="fas fa-trash"></i>Permanent Delete</a></li>
                                      </ul>
                                    </div>
                                  </td>

                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['location_name'])) ?></td>
                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['location_address'])) ?></td>

                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['user_first_name'])) ?> <?= stripslashes(strip_tags($ALLDATAINFO['user_last_name'])) ?></td>
                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['email'])) ?></td>
                                  <!--<td><?= stripslashes(strip_tags($ALLDATAINFO['added_by'])) ?></td>-->
                                  <td><?= showStatus($ALLDATAINFO['is_active']) ?></td>

                                  <td data-event-id="<?php echo $ALLDATAINFO['event_id']; ?>" style="width:70px;">
                                    <h6>Boost Requested - <?= ($ALLDATAINFO['requested_boost'] == 1) ? 'Yes' : 'No' ?></h6>
                                    <select class="boostSelect" onchange="handleSelectChange(this)">
                                      <option value="">Boost</option>
                                      <option value="yes" <?php if ($ALLDATAINFO['boost_days'] > 0) echo ' selected'; ?>>Yes</option>
                                      <option value="no" <?php if ($ALLDATAINFO['boost_days'] === 0) echo ' selected'; ?>>No</option>
                                    </select>
                                    <input type="number" class="daysInput number mt-2" name="boost_days" placeholder="Enter number of days" <?php if ($ALLDATAINFO['boost_days'] == 0) echo ' style="display: none;"'; ?> oninput="saveDays()" <?php if ($ALLDATAINFO['boost_days'] != 0) echo 'value="' . $ALLDATAINFO['boost_days'] . '"'; ?>>

                                    <input type="date" class="dateInput mt-2" name="boost_date" <?php if (empty($ALLDATAINFO['boost_date']) || $ALLDATAINFO['boost_date'] == '0000-00-00') echo ' style="display: none;"'; ?> onchange="saveDate()" <?php if (!empty($ALLDATAINFO['boost_date']) && $ALLDATAINFO['boost_date'] != '0000-00-00') echo 'value="' . $ALLDATAINFO['boost_date'] . '"'; ?>>
                                  </td>
                                  <td style="width:70px;">
                                    <?php
                                    echo (isset($ALLDATAINFO['is_featured']) && $ALLDATAINFO['is_featured'] == '1') ? 'Yes' : 'No';
                                    ?>
                                  </td>

                                </tr>
                              <?php $j++;
                              endforeach;
                            else : ?>
                              <tr>
                                <td colspan="14" style="text-align:center;">No Data Available In Table</td>
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
      sendData(select);
    }
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
        url: '<?php echo base_url('hhjsitemgmt/adminmanageuserpost/boostDays'); ?>',
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
    if (statusType === "active") {
      if (!confirm('Want to Active!')) {
        return;
      }
    } else if (statusType === "permanentdelete") {
      if (!confirm('Want to delete Permanent!')) {
        return;
      }
    }

    console.log(statusType);

    fetch('<?php echo base_url('hhjsitemgmt/adminmanageuserpost/mutlipleChangeStatus'); ?>', {
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