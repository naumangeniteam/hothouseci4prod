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

    .dropdown-menu-show {
        height: 75px !important;
        z-index: 2 !important;
        left: -55px !important;
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
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Festival List</a></li>
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
                        <h5>Manage Festival</h5>
                        <!--<a href="event_list.php?vid=0&type=3"  class="btn btn-danger">Trash Festival List</a>-->
                        <!--<a href="<?php echo getCurrentControllerPath('addeditdata'); ?>" class="btn btn-sm btn-primary pull-right">Add Content</a>-->
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

                                            <!--<input type="date" class="form-control" style="margin:4px;padding:2px;" style="width:120px;" placeholder="Start Date" readonly="readonly" id="start" name="start_date" value="">-->
                                            <!-- <input type="date" name="start_date" placeholder="Start Date" style="margin:4px;padding:2px;" style="width:120px;" id="start_date" class="form-control" value=""/>--->
                                        </div>
                                        <div class="form-group">
                                            <input type="text" placeholder="MM/DD/YYYY" onfocus="this.type='date'" onblur="this.type='text'" name="end_date" style="margin:4px;padding:2px;" style="width:120px;" id="end" class="form-control" />

                                            <!--<input type="date" class="form-control" style="margin:4px;padding:2px;" readonly="readonly" id="end" name="end_date" value="" placeholder="End Date">-->
                                            <!-- <input type="date" name="end_date" placeholder="Start Date" style="margin:4px;padding:2px;" style="width:120px;" id="end" class="form-control" value=""/>--->
                                            <a class="btn btn-link reset" title="Refresh End Date"><i class=" fa fa-refresh"></i></a>
                                        </div>
                                        <div class="form-group">
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
                                        <div class="col-sm-12 col-md-6">
                                            <div class="row" style="margin:3px;">
                                                <a href="<?= base_url('hhjsitemgmt/adminmanagefestivals/index') ?>" style="margin:3px;" class="btn btn-palegreen">All Festival(<?= count($festivals) ?>)</a>
                                                <a href="<?= base_url('hhjsitemgmt/adminmanagepublishfestival') ?>" style="margin:3px;" class="btn btn-primary pull-right">Published Festival(<?= count($publishfestival) ?>)</a>
                                                <a href="<?= base_url('hhjsitemgmt/adminmanagetrashfestival') ?>" style="margin:3px;" class="btn btn-primary pull-right">Trash Festival(<?= count($trashfestival) ?>)</a>
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
                                                            <th>Festival Name</th>
                                                            <th>Festival Location</th>
                                                            <th>Venue</th>
                                                            <th>Start Date</th>
                                                            <th>End Date</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if ($ALLDATA <> "") : $i = $first;
                                                            $j = 0;
                                                            foreach ($ALLDATA as $ALLDATAINFO) :
                                                                 $query = $db->table('venue_tbl')
            ->select('venue_title')
            ->where('id', $ALLDATAINFO['venue_id'])
            ->get()
            ->getRow();
        $getVenuName = $query->venue_title ?? 'N/A';
                                                                if ($j % 2 == 0) : $rowClass = 'odd';
                                                                else : $rowClass = 'even';
                                                                endif;
                                                        ?>
                                                                <tr role="row" class="<?php echo $rowClass; ?>">
                                                                    <td><?= $i++ ?></td>
                                                                    <td><?= stripslashes(strip_tags($ALLDATAINFO['festival_name'])) ?></td>
                                                                    <td><?= stripslashes(strip_tags($ALLDATAINFO['location_name'])) ?></td>
                                                                    <td><?= stripslashes(strip_tags($getVenuName->venue_title)) ?></td>

                                                                    <td><?= stripslashes(strip_tags($ALLDATAINFO['start_date'])) ?></td>
                                                                    <td><?= stripslashes(strip_tags($ALLDATAINFO['end_date'])) ?></td>

                                                                    <td>
                                                                        <div class="btn-group">
                                                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                                                                            <ul class="dropdown-menu dropdown-menu-show" role="menu">
                                                                                <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagetrashfestival/deletedata/' . $ALLDATAINFO['event_id']) ?>" onClick="return confirm('Want to delete Permanent!');"><i class="fas fa-trash"></i>Permanent Delete</a></li>
                                                                                <li><a href="<?php echo base_url('hhjsitemgmt/adminmanagetrashfestival/restore/' . $ALLDATAINFO['event_id']) ?>" onClick="return confirm('Want to Restore!');"><i class="fa fa-suitcase"></i>Restore</a></li>
                                                                            </ul>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php $j++;
                                                            endforeach;
                                                        else : ?>
                                                            <tr>
                                                                <td colspan="4" style="text-align:center;">No Data Available In Table</td>
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