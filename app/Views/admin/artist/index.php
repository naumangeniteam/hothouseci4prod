<style>
  .dropdown-menu-show {
    height: 90px !important;
    z-index: 2 !important;
    left: 0 !important;
    bottom: -60px !important;
  }

  .b .btn1 {
    color: #444;
    background-color: #fff;
    border-color: #ccc;
    height: 50px;
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
              <li class="breadcrumb-item"><a href="javascript:void(0);">Artist List</a></li>
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
            <h5>Manage Artist </h5>
            <a href="<?php echo getCurrentControllerPath('addeditdata'); ?>" class="btn btn-sm btn-primary pull-right">Add Content</a>
          </div>
          <div class="card-body">

            <form id="Data_Form" name="Data_Form" method="get" id="validate" action="<?php echo $forAction; ?>" novalidate="novalidate">
              <!-- // -->
              <div class="dt-responsive table-responsive">

                <div id="simpletable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                  <div class="row mb-3">
                  <div class="col-sm-2 col-md-2">
                      <div class="dataTables_length text-center" id="simpletable_length">
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
                    <div class="col-sm-2 col-md-2">
                      <input type="text" name="searchValue" id="searchValue" value="<?php echo $searchValue; ?>" class="form-control form-control-sm" placeholder="Enter Search Text">
                    </div>

                    <div class="col-sm-2 col-md-2">

                      <select name="status" id="status" class="form-control form-control-sm">
                        <option value="100">Select Status</option>
                        <option <?php if (isset($_GET['status']) && $_GET['status'] == 1) echo "selected"; ?> value="1">Active</option>
                        <option <?php if (isset($_GET['status']) && $_GET['status'] == 0) echo "selected"; ?> value="0">Inactive</option>
                      </select>

                    </div>
                    

                    
                    <div class="form-group">
                      <input type="submit" class="btn1" style="height:40px;" value="Search" name="artist_search">
                      <a href="<?= base_url('hhjsitemgmt/adminmanageartist/index') ?>" class="btn btn-link">View All </a>
                    </div>
                    <div class="col-sm-2 col-md-2">
                      <select name="bulk_option" class="form-control form-control-sm" onchange="callMutlipleChangeStatus()">
                        <option value="">Bulk Option Artist </option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="permanentdelete">Permanent Delete</option>
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
                              <th>Sr.No.</th>
                              <th>Name</th>
                              <th>Buy Now</th>
                              <th>Website Link</th>
                              <th>Status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if (!empty($ALLDATA)): 
                              $i = $first;

                              $j = 0;
                              foreach ($ALLDATA as $ALLDATAINFO):
                                if ($j % 2 == 0): $rowClass = 'odd';
                                else: $rowClass = 'even';
                                endif;
                            ?>
                                <tr role="row" class="<?php echo $rowClass; ?>">
                                  <td><input type="checkbox" name="checkbox_mutli" class="row-checkbox" value="<?= $ALLDATAINFO['id'] ?>"></td>
                                  <td><?= $i++ ?></td>
                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['artist_name'])) ?></td>
                                  <!-- <td>
                                    <?php if (!empty($ALLDATAINFO['buy_now_link'])): ?>
                                      <a href="<?= htmlspecialchars($ALLDATAINFO['buy_now_link']) ?>" target="_blank">View</a>
                                    <?php else: ?>
                                      <a href="#" class="disabled-link" style="pointer-events: none; color: gray;">View</a>
                                    <?php endif; ?>
                                  </td>

                                  <td>
                                    <?php if (!empty($ALLDATAINFO['website_link'])): ?>
                                      <a href="<?= htmlspecialchars($ALLDATAINFO['website_link']) ?>" target="_blank">View</a>
                                    <?php else: ?>
                                      <a href="#" class="disabled-link" style="pointer-events: none; color: gray;">View</a>
                                    <?php endif; ?>
                                  </td> -->
                                  <td>
                                    <?php
                                    if (!empty($ALLDATAINFO['buy_now_link'])):
                                      $buy_now_link = $ALLDATAINFO['buy_now_link'];
                                      // Add https:// if the link does not already have http or https
                                      if (!preg_match("~^(?:f|ht)tps?://~i", $buy_now_link)) {
                                        $buy_now_link = "https://" . $buy_now_link;
                                      }
                                    ?>
                                      <a href="<?= htmlspecialchars($buy_now_link) ?>" target="_blank">View</a>
                                    <?php else: ?>
                                      <a href="#" class="disabled-link" style="pointer-events: none; color: gray;">View</a>
                                    <?php endif; ?>
                                  </td>

                                  <td>
                                    <?php
                                    if (!empty($ALLDATAINFO['website_link'])):
                                      $website_link = $ALLDATAINFO['website_link'];
                                      // Add https:// if the link does not already have http or https
                                      if (!preg_match("~^(?:f|ht)tps?://~i", $website_link)) {
                                        $website_link = "https://" . $website_link;
                                      }
                                    ?>
                                      <a href="<?= htmlspecialchars($website_link) ?>" target="_blank">View</a>
                                    <?php else: ?>
                                      <a href="#" class="disabled-link" style="pointer-events: none; color: gray;">View</a>
                                    <?php endif; ?>
                                  </td>


                                  <td><?= showStatus($ALLDATAINFO['is_active']) ?></td>
                                  <td>
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                                      <ul class="dropdown-menu dropdown-menu-show" role="menu">
                                        <li><a href="<?php echo getCurrentControllerPath('addeditdata/' . $ALLDATAINFO['id']) ?>"><i class="fas fa-edit"></i> Edit Details</a></li>
                                        <?php if ($ALLDATAINFO['is_active'] == '1'): ?>
                                          <li><a href="<?php echo getCurrentControllerPath('changestatus/' . $ALLDATAINFO['id'] . '/0') ?>"><i class="fas fa-thumbs-down"></i> Inactive</a></li>
                                        <?php elseif ($ALLDATAINFO['is_active'] == '0'): ?>
                                          <li><a href="<?php echo getCurrentControllerPath('changestatus/' . $ALLDATAINFO['id'] . '/1') ?>"><i class="fas fa-thumbs-up"></i> Active</a></li>
                                        <?php endif; ?>
                                        <li><a href="<?php echo getCurrentControllerPath('deletedata/' . $ALLDATAINFO['id']) ?>" onClick="return confirm('Want to delete!');"><i class="fas fa-trash"></i> Delete</a></li>
                                      </ul>
                                    </div>
                                  </td>
                                </tr>
                              <?php $j++;
                              endforeach;
                            else: ?>
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

    fetch('<?php echo base_url('hhjsitemgmt/Adminmanageartist/mutlipleChangeStatus'); ?>', {
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