<?php
$db = \Config\Database::connect();
?>
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
    height: 85px !important;
    z-index: 2 !important;
    left: -69px !important;
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
              <li class="breadcrumb-item"><a href="javascript:void(0);">Archived Event List</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h5>Manage Archived Event</h5>
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
                            <option <?php if (isset($_GET['venue_id']) && $_GET['venue_id'] == $venue->id) echo "selected"; ?> value="<?= htmlspecialchars($venue->id) ?>"><?= htmlspecialchars($venue->venue_title) ?></option>

                        <?php endforeach;
                        endif; ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <input type="submit" class="btn1" style="height:40px;" value="Search" name="event_search">
                      <a href="<?= base_url('hhjsitemgmt/adminmanagearchived') ?>" class="btn btn-link">View All </a>
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

                    <!-- <div class="col-sm-12 col-md-10 col-xs-12">
                      <div class="row" style="margin:3px;">
                        <select name="bulk_option" style="margin:4px;padding:2px;" onchange="callMutlipleChangeStatus()">
                          <option value="">Bulk Option Events</option>
                          <option value="archive">Archive</option>
                        </select>
                      </div>
                    </div> -->

                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="table-responsive custom-data-table-wrapper2">
                        
                        <table id="simpletable" class="table table-striped table-bordered nowrap dataTable custom-data-table" role="grid" aria-describedby="simpletable_info">
                          <thead class="text-nowrap">
                            <tr role="row">
                              <!-- <th><input type="checkbox" id="checkAll"></th> -->
                              <th>Sr.No.</th>
                              <th>Event Title</th>
                              <th>Location</th>
                              <th>Venue</th>
                              <th>Start Date/End Date</th>
                              <th>Repeat Event</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            if (!empty($ALLDATA)) :
                              $i = $first;
                              $j = 0;

                              foreach ($ALLDATA as $ALLDATAINFO) :
                                // Fetch venue name
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
                                $rowClass = ($j % 2 == 0) ? 'odd' : 'even';
                                $copy = ($ALLDATAINFO['no_of_copy'] != '0') ? str_repeat("(copy)", $ALLDATAINFO['no_of_copy']) : '';
                            ?>
                                <tr role="row" class="<?= $rowClass; ?>">
                                  <!-- <td><input type="checkbox" name="checkbox_mutli" class="row-checkbox" value="<?= $ALLDATAINFO['event_id'] ?>"></td> -->
                                  <td style="width:50px;"><?= $i++ ?></td>
                                  <td style="width:100px;"><?= stripslashes(strip_tags($ALLDATAINFO['event_title'])) . $copy; ?></td>
                                  <td style="width:70px;"><?= stripslashes(strip_tags($ALLDATAINFO['location_name'])) ?></td>
                                  <td style="width:70px;"><?= stripslashes(strip_tags($getVenuName->venue_title)) ?></td>
                                  <td style="width:120px;">
                                    <?= stripslashes(strip_tags($ALLDATAINFO['start_date'])) . ' / ' . stripslashes(strip_tags($ALLDATAINFO['end_date'])) ?>
                                  </td>
                                  <td style="width:40px;"><?= stripslashes(strip_tags($ALLDATAINFO['repeating_event'])) ?></td>
                                </tr>
                              <?php
                                $j++;

                              endforeach;
                            else :
                              ?>
                              <tr>
                                <td colspan="6" style="text-align:center;">No Data Available In Table</td>
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

    fetch('<?php echo base_url('hhjsitemgmt/adminmanagearchived/mutlipleChangeStatus'); ?>', {
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
        url: '<?php echo base_url('hhjsitemgmt/eventmanagement/boostDays'); ?>',
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