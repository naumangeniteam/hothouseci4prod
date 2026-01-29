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
    bottom: -45px !important;
    top: auto !important;
  }

  table.dataTable.nowrap th,
  table.dataTable.nowrap td {
    white-space: unset !important;
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
    height: 200px !important;
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
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="javascript:void(0);">Festival List</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h5>Manage Festival</h5>
            <a href="<?php echo base_url('hhjsitemgmt/adminmanagefestivals/addeditdata'); ?>" class="btn btn-sm btn-primary pull-right">Add Festival Content</a>
          </div>
          <div class="row">
            <div class="col-lg-12 col-sm-12 col-xs-12">
              <div class="widget">
                <div class="widget-body bordered-left bordered-warning">
                  <form class="form-inline" method="get" id="validate" novalidate="novalidate">
                    <div class="form-group"> <span class="input-icon">
                        <input type="text" class="form-control" style="margin:6px;padding:5px;" placeholder="Festival Name" name="name" value="">
                      </span>
                    </div>
                    <div class="form-group"> <span class="input-icon">
                        <input type="text" class="form-control" style="margin:4px;padding:2px;" placeholder="Festival Location name" name="location_name" value="">
                      </span>
                    </div>
                    <div class="form-group">
                      <input type="text" placeholder="MM/DD/YYYY" onfocus="this.type='date'" onblur="this.type='text'" name="start_date" style="margin:4px;padding:2px;" style="width:120px;" id="start_date" class="form-control" value="" />
                    </div>
                    <div class="form-group">
                      <input type="text" placeholder="MM/DD/YYYY" onfocus="this.type='date'" onblur="this.type='text'" name="end_date" style="margin:4px;padding:2px;" style="width:120px;" id="end" class="form-control" value="" />
                      <a class="btn btn-link reset" title="Refresh End Date"><i class=" fa fa-refresh"></i></a>
                    </div>
                    <?php
                   /*  <div class="form-group">
                      <select name="venue_id" id="venue_id" style="margin:4px;padding:2px;" class="form-control">
                        <option value="">Select Venue</option>
                        <?php
                        if (!empty($venues)) :
                          foreach ($venues as $venue) : ?>
                            <option <?php if ($EDITDATA['venue_id'] == $venue->id) {
                                      echo "selected";
                                    } ?> value="<?= $venue->id ?>"><?= $venue->venue_title ?></option>
                        <?php endforeach;
                        endif; ?>
                      </select>
                    </div> 
                     */ 
                    ?>
                    <div class="form-group">
                      <input type="submit" class="btn1" style="height:40px;" value="Search" name="festival_search">
                      <a href="<?= base_url('hhjsitemgmt/adminmanagefestivals') ?>" class="btn btn-link">View All </a>
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

                        <a href="<?php echo base_url('hhjsitemgmt/adminmanagefestivals/import'); ?>" style="margin:3px;" class="btn btn-palegreen">Import</a>

                        <a href="<?= base_url('hhjsitemgmt/adminmanagefestivals/index') ?>" style="margin:3px;" class="btn btn-palegreen">All Festival(<?= count($festivals) ?>)</a>

                        <a href="<?= base_url('hhjsitemgmt/adminmanagepublishfestival') ?>" style="margin:3px;" class="btn btn-primary pull-right">Published Festival(<?= count($publishfestival) ?>)</a>

                        <a href="<?= base_url('hhjsitemgmt/adminmanagetrashfestival') ?>" style="margin:3px;" class="btn btn-primary pull-right">Trash Festival(<?= count($trashfestival) ?>)</a>

                        <select name="bulk_option" style="margin:4px;padding:2px;" onchange="callMutlipleChangeStatus()">
                          <option value="">Bulk Option Festivals</option>
                          <option value="active">Active</option>
                          <option value="inactive">Inactive</option>
                          <option value="boost">Boost</option>
                          <option value="unboost">Unboost</option>
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
                              <th> <input type="checkbox" id="checkAll"></th>
                              <th>Sr.No.</th>
                              <th>Festival Name</th>
                              <th>Festival Location Name</th>
                              <th>Festival Location Address</th>
                              <!-- <th>Venue</th> -->
                              <th>Start Date</th>
                              <th>End Date</th>
                              <th>Status</th>
                              <th>Boost ?</th>
                              <th>Featured ?</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          
                          <tbody>
                          <?php if (!empty($ALLDATA)):  
                           $i = $first;
                              $j = 0;
                              foreach ($ALLDATA as $ALLDATAINFO) :
                                $query = $db->table('venue_tbl')
                    ->select('venue_title')
                    ->where('id', $ALLDATAINFO['venue_id'])
                    ->get()
                    ->getRow();
                
                              
                               $db = db_connect(); // ✅ Get database connection in CI4

                               $getVenuName = $db->table('venue_tbl')
                                                 ->select('venue_title')
                                                 ->where('id', $ALLDATAINFO['venue_id'])
                                                 ->get()
                                                 ->getRow();
                         
                                if ($j % 2 == 0) : $rowClass = 'odd';
                                else : $rowClass = 'even';
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
                                <?php
                                  /* <tr role="row" style="<?php echo $ALLDATAINFO['is_front'] == '1' ? 'background-color:  #ffe6e6;' : ''; ?>" class="<?php echo $rowClass; ?>">  */ 
                                ?>
                                <tr role="row" style=" " class="<?php echo $rowClass; ?>">
                                  <td><input type="checkbox" name="checkbox_mutli" class="row-checkbox" value="<?= $ALLDATAINFO['festival_id'] ?>"></td>
                                  <td style="width:50px;"><?= $i++ ?></td>
                                  <td style="width:100px;"><?= stripslashes(strip_tags($ALLDATAINFO['festival_name'])) . $copy; ?></td>
                                  <td style="width:70px;"><?= stripslashes(strip_tags($ALLDATAINFO['location_name'])) ?></td>
                                  <td style="width:70px;"><?= stripslashes(strip_tags($ALLDATAINFO['location_address'])) ?></td>
                                  <!-- <td style="width:70px;"><?php //echo  stripslashes(strip_tags($getVenuName['venue_title'])) ?></td> -->
                                  <td style="width:60px;"><?= stripslashes(strip_tags($ALLDATAINFO['start_date'])) ?></td>
                                  <td style="width:60px;"><?= stripslashes(strip_tags($ALLDATAINFO['end_date'])) ?></td>
                                  <td style="width:70px;"><?= showStatus($ALLDATAINFO['is_active']?$ALLDATAINFO['is_active']:'0') ?></td>
                                  <td style="width:70px;"><?= ($ALLDATAINFO['is_boosted'] == 1) ? 'Yes' : 'No' ?></td>
                                  <td style="width:70px;">
                                    <?php
                                    echo (isset($ALLDATAINFO['is_featured']) && $ALLDATAINFO['is_featured'] == '1') ? 'Yes' : 'No';
                                    ?>
                                  </td>
                                  <td style="width:100px;">
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                                      <ul class="dropdown-menu dropdown-menu-show" role="menu">
                                        <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagefestivals/addeditdata/' . $ALLDATAINFO['festival_id']) ?>"><i class="fas fa-edit"></i> Edit Details</a></li>

                                        <?php if ($ALLDATAINFO['is_active'] == '1') : ?>
                                          <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagefestivals/changestatus/' . $ALLDATAINFO['festival_id'] . '/0') ?>" onClick="return confirm('Want to Inactive!');"><i class="fas fa-thumbs-down"></i> Inactive</a></li>
                                        <?php elseif ($ALLDATAINFO['is_active'] == '0') : ?>
                                          <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagefestivals/changestatus/' . $ALLDATAINFO['festival_id'] . '/1') ?>" onClick="return confirm('Want to Active!');"><i class="fas fa-thumbs-up"></i> Active</a></li>
                                        <?php endif; ?>

                                        <?php if ($ALLDATAINFO['is_boosted'] == '1') : ?>
                                          <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagefestivals/statusboost/' . $ALLDATAINFO['festival_id'] . '/0') ?>"><i class="fas fa-thumbs-down"></i>Unboost</a></li>
                                        <?php elseif ($ALLDATAINFO['is_boosted'] == '0') : ?>
                                          <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagefestivals/statusboost/' . $ALLDATAINFO['festival_id'] . '/1') ?>"><i class="fas fa-thumbs-up"></i>Boost</a></li>
                                        <?php endif; ?>

                                        <?php if ($ALLDATAINFO['is_featured'] == '1') : ?>
                                          <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagefestivals/statusfeatured/' . $ALLDATAINFO['festival_id'] . '/0') ?>"><i class="fas fa-thumbs-down"></i> NotFeatured </a></li>
                                        <?php elseif ($ALLDATAINFO['is_featured'] == '0') : ?>
                                          <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagefestivals/statusfeatured/' . $ALLDATAINFO['festival_id'] . '/1') ?>"><i class="fas fa-thumbs-up"></i> Featured </a></li>
                                        <?php endif; ?>

                                        <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagefestivals/deletedata/' . $ALLDATAINFO['festival_id']) ?>" onClick="return confirm('Want to delete Permanent!');"><i class="fas fa-trash"></i>Permanent Delete</a></li>
                                        <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagefestivals/updateStatus/' . $ALLDATAINFO['festival_id']) ?>" onClick="return confirm('Want to trash!');"><i class="fas fa-trash"></i> Trash</a></li>
                                        <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagefestivals/duplicate/' . $ALLDATAINFO['festival_id']) ?>"><i class="fas fa-edit"></i>Duplicate</a></li>

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
  function callMutlipleChangeStatus() {
    var selectElement = document.querySelector('select[name="bulk_option"]');
    var statusType = selectElement.value;
    var checkedIds = getCheckedIds();

    // Check if any checkboxes are selected
    if (checkedIds.length === 0) {
      alert('Please select at least one item.');
      return;
    }

    if (statusType === "inactive") {
        if (!confirm('Want to Inactive!')) {
            return; 
        }
    } else if (statusType === "permanentdelete") {
        if (!confirm('Want to delete Permanent!')) {
            return; 
        }
    }

    console.log(statusType);

    // Call your PHP function using AJAX
    fetch('<?php echo base_url('hhjsitemgmt/adminmanagefestivals/mutlipleChangeStatus'); ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'changeStatusIds=' + JSON.stringify(checkedIds) + '&statusType=' + statusType
      })
      .then(response => {
        if (response.ok) {
          // Handle success response
          location.reload(); // Refresh the page
        } else {
          // Handle error response
          throw new Error('Error occurred');
        }
      })
      .catch(error => {
        // alert(error.message);
      });
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