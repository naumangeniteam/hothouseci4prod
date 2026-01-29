<style>
  .dropdown-menu-show {
    height: 90px !important;
    z-index: 2 !important;
    left: -65px !important;
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
              <li class="breadcrumb-item"><a href="javascript:void(0);">Our Partner Slider List</a></li>
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
            <h5>Manage Our Partner Slider</h5>
            <a href="<?php echo getCurrentControllerPath('addeditdata'); ?>" class="btn btn-sm btn-primary pull-right">Add Our Partner</a>
          </div>
          
          <div class="card-body">
            <form id="Data_Form" name="Data_Form" method="get" action="<?php echo $forAction; ?>">
              <div class="dt-responsive table-responsive">
                <div id="simpletable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                  <div class="row">
                          <form class="form-inline" method="get" id="validate" novalidate="novalidate">
                              
                            <div class="form-group col-3">
                                <select name="pages" id="page" class="form-control">
                                  <option value="">Select Page</option>
                                  <option <?= (isset($_GET['pages']) && $_GET['pages'] == '1') ? 'selected' : '' ?> value="1">Home</option>
                                  <option <?= (isset($_GET['pages']) && $_GET['pages'] == '2') ? 'selected' : '' ?> value="2">Calendar</option>
                                  <option <?= (isset($_GET['pages']) && $_GET['pages'] == '3') ? 'selected' : '' ?> value="3">Festival</option>
                                  <option <?= (isset($_GET['pages']) && $_GET['pages'] == '4') ? 'selected' : '' ?> value="4">Magazine</option>

                                </select>
                              </div>
                              <div class="form-group col-3">
                                <select name="type" id="type" class="form-control">
                                  <option value="">Select Type</option>
                                  <option <?= (isset($_GET['type']) && $_GET['type'] == 'slider') ? 'selected' : '' ?> value="slider">Slider</option>
                                  <option <?= (isset($_GET['type']) && $_GET['type'] == 'banner') ? 'selected' : '' ?> value="banner">Ad Banner</option>

                                </select>
                              </div>

                              <div class="form-group col-3">
                                <select name="alignment" id="alignment" class="form-control">
                                  <option value="">Select Alignment</option>
                                  <option <?= (isset($_GET['alignment']) && $_GET['alignment'] == 'left') ? 'selected' : '' ?> value="left">Left</option>
                                  <option <?= (isset($_GET['alignment']) && $_GET['alignment'] == 'right') ? 'selected' : '' ?> value="right">Right</option>

                                </select>
                              </div>
                              
                              <div class="form-group col-3">
                                <input type="submit" class="btn1" style="height:40px;" value="Search" name="event_search">
                                <a href="<?= base_url('hhjsitemgmt/adminourpartnerslider/index') ?>" class="btn btn-link">View All </a>
                              </div>
                            </form>
                  </div>
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
                    </div>

                   


                    <div class="col-sm-12 col-md-6 d-none">
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
                        <table id="simpletable" class="table table-striped table-bordered nowrap dataTable " role="grid" aria-describedby="simpletable_info">
                          <thead>
                            <tr role="row">
                              <th>Sr.No.</th>
                              <th>Slider Image</th>
                              <th>Weblink</th>
                              <th>HTML Code</th>
                              <th>Type</th>
                              <th>Page</th>
                              <th>Alignment</th>
                              <th>Order</th>
                              <th>Status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php if (!empty($ALLDATA)): 
                              $i = $first;
                              $j = 0;
                              foreach ($ALLDATA as $ALLDATAINFO) :
                                if ($j % 2 == 0) : $rowClass = 'odd';
                                else : $rowClass = 'even';
                                endif;
                            ?>
                                <tr role="row" class="<?php echo $rowClass; ?>">
                                  <td><?= $i++ ?></td>

                                  <?php if ($ALLDATAINFO['image'] == '') { ?>
                                    <td><img src="<?= $ASSET_URL?>/front/img/image213X213.jpg" width="100px"></td>
                                  <?php } else { ?>
                                    <td><img src="<?= $ASSET_URL?>/front/img/slider/<?= $ALLDATAINFO['image'] ?>" width="200px"></td>
                                  <?php } ?>
                                  <!-- <td><img src="<?= $ASSET_URL?>/front/img/banners/<?= $ALLDATAINFO['image'] ?>" width="200px"></td> -->
                                  <td><?= !empty($ALLDATAINFO['weblink']) ? "<a target='_blank' href='".$ALLDATAINFO['weblink']."'>View</a>" : '' ?></td>
                                  <td><?php if(!empty($ALLDATAINFO['slide_html'])){ echo $ALLDATAINFO['slide_html'];}?></td>
                                  <td><?= ucfirst($ALLDATAINFO['type']) ?></td>
                                  <?php 
                                  $pages = array(
                                    1=>'Home',
                                    2=>'Calender',
                                    3=>'Festivals',
                                    4=>'Magazine'
                                  )
                                  ?>
                                  <td><?= ucfirst($pages[$ALLDATAINFO['page']]) ?></td>
                                  <td><?= ucfirst($ALLDATAINFO['alignment']) ?></td>
                                  <td><?= $ALLDATAINFO['order'] ?></td>
                                  <td><?= showStatus($ALLDATAINFO['is_active']) ?></td>
                                  <td>
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                                      <ul class="dropdown-menu dropdown-menu-show" role="menu">
                                        <li><a href="<?php echo getCurrentControllerPath('addeditdata/' . $ALLDATAINFO['id']) ?>"><i class="fas fa-edit"></i> Edit Details</a></li>
                                        <?php if ($ALLDATAINFO['is_active'] == '1' || $ALLDATAINFO['is_active'] == 't') : ?>
                                          <li><a href="<?php echo getCurrentControllerPath('changestatus/' . $ALLDATAINFO['id'] . '/0') ?>"><i class="fas fa-thumbs-down"></i> Inactive</a></li>
                                        <?php elseif ($ALLDATAINFO['is_active'] == '0' || $ALLDATAINFO['is_active'] == 'f') : ?>
                                          <li><a href="<?php echo getCurrentControllerPath('changestatus/' . $ALLDATAINFO['id'] . '/1') ?>"><i class="fas fa-thumbs-up"></i> Active</a></li>
                                        <?php endif; ?>
                                        <li><a href="<?php echo getCurrentControllerPath('duplicateBanner/' . $ALLDATAINFO['id']) ?>"><i class="fas fa-edit"></i> Duplicate Banner</a></li>

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