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
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Subscribe  Email List</a></li>
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
                <h5>Manage Subscribe  email</h5>
              </div>
              <div class="card-body">
                <form id="Data_Form" name="Data_Form" method="get" action="<?php echo $forAction; ?>">
                  <div class="dt-responsive table-responsive">
                    <div id="simpletable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                      <div class="row">
                        <div class="col-sm-12 col-md-4">
                          <div class="dataTables_length" id="simpletable_length">
                            <label>Show 
                              <select name="showLength" id="showLength" class="custom-select custom-select-sm form-control form-control-sm">
                                <option value="2" <?php if($perpage == '2')echo 'selected="selected"'; ?>>2</option>
                                <option value="10" <?php if($perpage == '10')echo 'selected="selected"'; ?>>10</option>
                                <option value="25" <?php if($perpage == '25')echo 'selected="selected"'; ?>>25</option>
                                <option value="50" <?php if($perpage == '50')echo 'selected="selected"'; ?>>50</option>
                                <option value="100" <?php if($perpage == '100')echo 'selected="selected"'; ?>>100</option>
                                <option value="All" <?php if($perpage == 'All')echo 'selected="selected"'; ?>>All</option>
                              </select>
                              entries
                            </label>
                          </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-4">
                          <div class="row" style="margin:0px;">
                            <div class="col-sm-12 col-md-8">
                              <input type="text" name="searchValue" id="searchValue" value="<?php echo $searchValue; ?>" class="form-control form-control-sm" placeholder="Enter Search Text">
                            </div>
                          </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-4">
                          <div class="row" style="margin:0px;">
                            <div class="col-sm-12 col-md-8">
                              <a href="<?php echo base_url('hhjsitemgmt/adminmanagesubsc/export_excel'); ?>" class="btn btn-sm btn-primary pull-right">Export To Excel</a>
                            </div>
                          </div>
                        </div>
                       
                      </div>
                      <div class="row">
                        <div class="col-sm-12">
							          <div class="table-responsive">
								          <table id="simpletable" class="table table-striped table-bordered nowrap dataTable" role="grid" aria-describedby="simpletable_info">
                            <thead>
                              <tr role="row">
                                <th>Sr.No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>IP Address</th>
                                <th>Created Date</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($ALLDATA)): 
                              $i=$first; 
                              $j=0; 
                              foreach($ALLDATA as $ALLDATAINFO): 
                                if($j%2==0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;
                              ?>
                               <tr role="row" class="<?php echo $rowClass; ?>">
                                  <td><?=$i++?></td>
                                  <td><?=stripslashes(strip_tags($ALLDATAINFO['name']))?></td>
                                  <td><?=stripslashes(strip_tags($ALLDATAINFO['email']))?></td>
                                  <td><?=stripslashes(strip_tags($ALLDATAINFO['ip_address']))?></td>
                                  <td><?=stripslashes(strip_tags($ALLDATAINFO['creation_date']))?></td>
                                  <td>
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                                      <ul class="dropdown-menu" role="menu">
                                       
                                          <li><a href="<?php echo getCurrentControllerPath('deletedata/'.$ALLDATAINFO['id'])?>" onClick="return confirm('Want to delete!');"><i class="fas fa-trash"></i> Delete</a></li>
                                        
                                      </ul>
                                    </div>
                                  </td>
                                </tr>
                              <?php $j++; endforeach; else: ?>
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