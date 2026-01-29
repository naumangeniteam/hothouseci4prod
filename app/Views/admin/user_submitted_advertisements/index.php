<style>
  table.dataTable.nowrap th,
  table.dataTable.nowrap td {
    white-space: unset !important;
  }

  .dropdown-menu-show {
    height: 43px !important;
    z-index: 2 !important;
    left: -70px !important;
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
              <li class="breadcrumb-item"><a href="javascript:void(0);">User submitted Advertisements
                </a></li>
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
            <h5>Manage User submitted Advertisements</h5>
            <!-- <a href="<?php echo getCurrentControllerPath('addeditdata'); ?>" class="btn btn-sm btn-primary pull-right">Add About Us</a> -->
          </div>
          <div class="card-body">
            <form id="Data_Form" name="Data_Form" method="get" action="<?php echo $forAction; ?>">
              <div class="dt-responsive table-responsive">
                <div id="simpletable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                  <div class="row">
                    <div class="col-sm-12 col-md-6">
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
                      <div class="col-sm-12 col-md-10 col-xs-12">
                        <div class="row" style="margin:3px;">
                          <a href="javascript:" id="export-btn" data-href="<?php echo base_url('hhjsitemgmt/adminmanageadvertisements/export_excel'); ?>" style="margin:3px;" class="btn btn-primary pull-right">Export</a>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                      <div class="row" style="margin:0px;">
                        <div class="col-sm-12 col-md-8">
                          <input type="text" name="searchValue" id="searchValue" value="<?php echo $searchValue; ?>" class="form-control form-control-sm" placeholder="Enter Search Text">
                        </div>
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
                              <th>Name</th>
                              <th>Venue<br />/Location</th>
                              <th>Phone Number<br />/Email</th>
                              <th>Advertising Interest</th>
                              <th>Details</th>
                              <th>Submitted on</th>
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
                                  <td><?= $i++ ?></td>
                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['name'])) ?></td>
                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['venue_name'])) ?><br />-<?= stripslashes(strip_tags($ALLDATAINFO['location_name'])) ?></td>
                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['phone_number'])) ?><br /><?= stripslashes(strip_tags($ALLDATAINFO['email'])) ?>
                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['advertising_interest'])) ?></td>
                                  <td><?= stripslashes(strip_tags($ALLDATAINFO['inquiry'])) ?></td>
                                  <td>
                                    <?= stripslashes(strip_tags($ALLDATAINFO['creation_date'])) ?>
                                  </td>
                                  <td>
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                                      <ul class="dropdown-menu dropdown-menu-show" role="menu">
                                        <li><a href="<?php echo getCurrentControllerPath('deletedata/' . $ALLDATAINFO['advertise_id']) ?>" onClick="return confirm('Want to delete!');"><i class="fas fa-trash"></i> Delete</a></li>
                                      </ul>
                                    </div>
                                  </td>
                                </tr>
                              <?php $j++;
                              endforeach;
                            else: ?>
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
                          <div class="dataTables_info" role="status" aria-live="polite">
                              <?= isset($noOfContent) ? esc($noOfContent) : 'No records found'; ?>
                          </div>
                      </div>
                      <div class="col-sm-12 col-md-7 d-flex justify-content-end">
                          <nav aria-label="Page navigation">
                              <?= isset($PAGINATION) ? $PAGINATION : ''; ?>
                          </nav>
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
        const form = document.getElementById('Data_Form');

        exportBtn.addEventListener('click', () => {
            const actionUrl = exportBtn.getAttribute('data-href');
            form.action = actionUrl;
            form.submit();
        });
    });
</script>