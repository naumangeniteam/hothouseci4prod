<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />
<script src="https://unpkg.com/dropzone"></script>
<script src="https://unpkg.com/cropperjs"></script>

<style>
  .image_area {
    position: relative;
  }

  img {
    display: block;
    max-width: 100%;
  }

  .preview {
    overflow: hidden;
    width: 160px;
    height: 160px;
    margin: 10px;
    border: 1px solid red;
  }

  .modal-lg {
    max-width: 1000px !important;
  }

  .overlay {
    position: absolute;
    bottom: 10px;
    left: 0;
    right: 0;
    background-color: rgba(255, 255, 255, 0.5);
    overflow: hidden;
    height: 0;
    transition: .5s ease;
    width: 100%;
  }

  .image_area:hover .overlay {

    cursor: pointer;
  }

  .text {
    color: #333;
    font-size: 20px;
    position: absolute;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    text-align: center;
  }

  .preview-container {
    min-height: 400px;
  }


  .fileUpload {
    position: relative;
    overflow: hidden;
  }

  .fileUpload input.upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;

    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
  }

  .btn--browse {
    border: 1px solid gray;
    border-left: 0;
    border-radius: 0 2px 2px 0;
    background-color: #ccc;
    color: black;
    /* margin-left: 50%; */
    height: 40px;
    padding: 7px 32px;
    margin-top: 28px;
  }

  .f-input {
    height: 42px;
    background-color: white;
    border: 1px solid gray;
    width: 100%;
    max-width: 400px;
    float: left;
    padding: 0 14px;
  }

  .text {
    color: #333;
    font-size: 20px;
    /* position: absolute; */
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    text-align: center;
  }


  html {
    margin: 50px;
  }

  .hide {
    display: none;
  }

  .show {
    display: block;
  }

  .select2-container--default .select2-selection--multiple .select2-selection__choice {
    color: black;
  }
</style>

<style type="text/css">
  .col-1,
  .col-2,
  .col-3,
  .col-4,
  .col-5,
  .col-6,
  .col-7,
  .col-8,
  .col-9,
  .col-10,
  .col-11,
  .col-12,
  .col,
  .col-auto,
  .col-sm-1,
  .col-sm-2,
  .col-sm-3,
  .col-sm-4,
  .col-sm-5,
  .col-sm-6,
  .col-sm-7,
  .col-sm-8,
  .col-sm-9,
  .col-sm-10,
  .col-sm-11,
  .col-sm-12,
  .col-sm,
  .col-sm-auto,
  .col-md-1,
  .col-md-2,
  .col-md-3,
  .col-md-4,
  .col-md-5,
  .col-md-6,
  .col-md-7,
  .col-md-8,
  .col-md-9,
  .col-md-10,
  .col-md-11,
  .col-md-12,
  .col-md,
  .col-md-auto,
  .col-lg-1,
  .col-lg-2,
  .col-lg-3,
  .col-lg-4,
  .col-lg-5,
  .col-lg-6,
  .col-lg-7,
  .col-lg-8,
  .col-lg-9,
  .col-lg-10,
  .col-lg-11,
  .col-lg-12,
  .col-lg,
  .col-lg-auto,
  .col-xl-1,
  .col-xl-2,
  .col-xl-3,
  .col-xl-4,
  .col-xl-5,
  .col-xl-6,
  .col-xl-7,
  .col-xl-8,
  .col-xl-9,
  .col-xl-10,
  .col-xl-11,
  .col-xl-12,
  .col-xl,
  .col-xl-auto {
    position: relative;
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    float: left;
  }

  .check_repeat {
    border-top: 1px solid #49CCED;
  }

  .f_check_repeat {
    border-top: 1px solid #4680ff;
  }

  .s_check_repeat {
    border-top: 1px solid #9ACD32;
  }

  .no-boder {
    border-bottom: 0px;
  }

  .module_rows .row {
    margin: 0px;
  }

  .text-aline-center {
    text-align: center;
  }

  .l_padding_no {
    padding-left: 0px;
  }

  .l_padding_15 {
    padding-left: 15px;
  }

  .l_padding_40 {
    padding-left: 40px;
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
              <li class="breadcrumb-item"><a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>">Contact Details List</a></li>
              <li class="breadcrumb-item"><a href="javascript:void(0);"><?= $EDITDATA ? 'Edit' : 'Add' ?>Contact Details Content</a></li>
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
            <h5><?= $EDITDATA ? 'Edit' : 'Add' ?> Content</h5>
          </div>
          <div class="card-body">
            <div class="basic-login-inner">
              <form id="currentPageFormSubadmin" name="currentPageFormSubadmin" class="form-auth-small" method="post" action="" enctype="multipart/form-data" autocomplete="off">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>" style="margin-left: 12px;" class="btn btn-sm btn-primary pull-right">Back</a>
                    <button class="btn btn-primary  btn-sm pull-right">Submit</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>" style="margin-right: 11px;" class="btn btn-danger has-ripple btn-sm pull-right">Cancel</a>

                    <input type="hidden" name="CurrentFieldForUnique" id="CurrentFieldForUnique" value="id" />
                    <input type="hidden" name="CurrentIdForUnique" id="CurrentIdForUnique" value="<?= $EDITDATA['event_id'] ?>" />
                    <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?= $EDITDATA['event_id'] ?>" />
                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

                  </div>
                </div>

                <div class="row mt-2">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('event_title')) : ?>error<?php endif; ?>">
                    <label>Event Title<span class="required">*</span></label>
                    <input type="text" name="event_title" id="event_title" value="<?php if (old('event_title')) : echo old('event_title');
                                                                                  else : echo stripslashes($EDITDATA['event_title']);
                                                                                  endif; ?>" class="form-control required" placeholder="Event Title">
                    <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('event_title')) : ?>
                      <span for="event_title" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('event_title'); ?></span>
                    <?php endif; ?>
                  </div>
                  <!-- <div class="form-group-inner col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <label>Select Event Type</label>
                    <select name="event_types" id="event_types" class="form-control">
                      <option value="">Select</option>
                      <option value="Auditorium/Night Club" <?php if ($EDITDATA['event_types'] == 'Auditorium/Night Club') echo "selected"; ?>>Auditorium/Night Club</option>
                      <option value="Free Concert" <?php if ($EDITDATA['event_types'] == 'Free Concert') echo "selected"; ?>>Free Concert</option>
                      <option value="Awards/Celebrations" <?php if ($EDITDATA['event_types'] == 'Awards/Celebrations') echo "selected"; ?>>Awards/Celebrations</option>
                      <option value="For Kids/Youth" <?php if ($EDITDATA['event_types'] == 'For Kids/Youth') echo "selected"; ?>>For Kids/Youth</option>
                      <option value="Festival" <?php if ($EDITDATA['event_types'] == 'Festival') echo "selected"; ?>>Festival</option>
                      <option value="Cafe" <?php if ($EDITDATA['event_types'] == 'Cafe') echo "selected"; ?>>Cafe</option>
                      <option value="Speakeasy" <?php if ($EDITDATA['event_types'] == 'Speakeasy') echo "selected"; ?>>Speakeasy</option>
                      <option value="Supper Club" <?php if ($EDITDATA['event_types'] == 'Supper Club') echo "selected"; ?>>Supper Club</option>
                      <option value="Brunch" <?php if ($EDITDATA['event_types'] == 'Brunch') echo "selected"; ?>>Brunch</option>
                      <option value="Other" <?php if ($EDITDATA['event_types'] == 'Other') echo "selected"; ?>>Other</option>
                    </select>

                  </div> -->
                </div>

                <div class="row">
                  <div class="form-group-inner col-lg-4 col-md-4 col-sm-12 col-xs-12  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('start_date')) : ?>error<?php endif; ?>">
                    <label>Start Date<span class="required">*</span></label>
                    <input type="text" autocomplete="off" id="dt1" name="start_date" class="form-control" value="<?php if (old('start_date')) : echo old('start_date');
                                                                                                                  else : echo stripslashes($EDITDATA['start_date']);
                                                                                                                  endif; ?>">
                    <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('start_date')) : ?>
                      <span for="start_date" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('start_date'); ?></span>
                    <?php endif; ?>
                  </div>

                  <div class="form-group-inner col-lg-4 col-md-4 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('end_date')) : ?>error<?php endif; ?>">
                    <label>End Date<span class="required">*</span></label>
                    <input type="text" id="dt2" name="end_date" class="form-control dateInput" value="<?php if (old('start_date')) : echo old('start_date');
                                                                                                      else : echo stripslashes($EDITDATA['end_date']);
                                                                                                      endif; ?>">
                    <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('end_date')) : ?>
                      <span for="end_date" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('end_date');  ?></span>
                    <?php endif; ?>

                  </div>
                </div>


                <?php
                $split = $EDITDATA['event_start_time'];

                $last_data = substr($split, 0, -6);
                $time = "2:00";
                $hour = explode(':', $split)[1];
                $hour1 = explode(' ', $hour)[0];
                $hour2 = explode(' ', $hour)[1];
                ?>
                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12" id="">
                    <label class="col-sm-2 control-label no-padding-right">Start Time:<span class="required">*</span> </label>
                    <input type="hidden" name="event_start_time" id="event_start_hour" value="">
                    <select id="timehr_event_end_time" name="event_start_hour" style="padding: 6px 12px;" htype="event_end_time">
                      <option value=1 <?php if ($last_data == 1) {
                                        echo "selected";
                                      } ?>>1</option>
                      <option value=2 <?php if ($last_data == 2) {
                                        echo "selected";
                                      } ?>>2</option>
                      <option value=3 <?php if ($last_data == 3) {
                                        echo "selected";
                                      } ?>>3</option>
                      <option value=4 <?php if ($last_data == 4) {
                                        echo "selected";
                                      } ?>>4</option>
                      <option value=5 <?php if ($last_data == 5) {
                                        echo "selected";
                                      } ?>>5</option>
                      <option value=6 <?php if ($last_data == 6) {
                                        echo "selected";
                                      } ?>>6</option>
                      <option value=7 <?php if ($last_data == 7) {
                                        echo "selected";
                                      } ?>>7</option>
                      <option value=8 <?php if ($last_data == 8) {
                                        echo "selected";
                                      } ?>>8</option>
                      <option value=9 <?php if ($last_data == 9) {
                                        echo "selected";
                                      } ?>>9</option>
                      <option value=10 <?php if ($last_data == 10) {
                                          echo "selected";
                                        } ?>>10</option>
                      <option value=11 <?php if ($last_data == 11) {
                                          echo "selected";
                                        } ?>>11</option>
                      <option value=12 <?php if ($last_data == 12) {
                                          echo "selected";
                                        } ?>>12</option>
                    </select>
                    <select id="timemin_event_end_time" name="event_start_min" style="padding: 6px 12px;" htype="event_end_time">
                      <option value=00 <?php if ($hour1 == 00) {
                                          echo "selected";
                                        } ?>>00</option>
                      <option value=05 <?php if ($hour1 == 05) {
                                          echo "selected";
                                        } ?>>05</option>
                      <option value=10 <?php if ($hour1 == 10) {
                                          echo "selected";
                                        } ?>>10</option>
                      <option value=15 <?php if ($hour1 == 15) {
                                          echo "selected";
                                        } ?>>15</option>
                      <option value=20 <?php if ($hour1 == 20) {
                                          echo "selected";
                                        } ?>>20</option>
                      <option value=25 <?php if ($hour1 == 25) {
                                          echo "selected";
                                        } ?>>25</option>
                      <option value=30 <?php if ($hour1 == 30) {
                                          echo "selected";
                                        } ?>>30</option>
                      <option value=35 <?php if ($hour1 == 35) {
                                          echo "selected";
                                        } ?>>35</option>
                      <option value=40 <?php if ($hour1 == 40) {
                                          echo "selected";
                                        } ?>>40</option>
                      <option value=45 <?php if ($hour1 == 45) {
                                          echo "selected";
                                        } ?>>45</option>
                      <option value=50 <?php if ($hour1 == 50) {
                                          echo "selected";
                                        } ?>>50</option>
                      <option value=55 <?php if ($hour1 == 55) {
                                          echo "selected";
                                        } ?>>55</option>
                    </select>
                    <select id="timesec_event_end_time" name="event_start_M" style="padding: 6px 12px;" htype="event_end_time">
                      <option value="PM" <?php if ($hour2 == "PM") {
                                            echo "selected";
                                          } ?>>PM</option>
                      <option value="AM" <?php if ($hour2 == "AM") {
                                            echo "selected";
                                          } ?>>AM</option>
                    </select>
                  </div>
                </div>
                <?php
                $split_end = $EDITDATA['event_end_time'];

                $last_end_data = substr($split_end, 0, -6);
                $hour_end = explode(':', $split_end)[1];
                $hour1_end = explode(' ', $hour_end)[0];
                $hour2_end = explode(' ', $hour_end)[1];

                ?>
                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if ($EDITDATA['time_permission'] == 'Yes') {
                                                                                        echo 'hide';
                                                                                      } else if ($EDITDATA['time_permission'] == 'No') {
                                                                                        echo 'show';
                                                                                      } else {
                                                                                        echo 'hide';
                                                                                      } ?>" id="end_time">
                    <label class="col-sm-2 control-label no-padding-right">End Time:<span class="required">*</span> </label>
                    <input type="hidden" name="event_end_time" id="event_end_time" value="">
                    <select id="timehr_event_end_time" name="event_end_hour" style="padding: 6px 12px;" htype="event_end_time" onchange="setTime(this);">

                      <option value=1<?php if ($last_end_data == 1) {
                                        echo "selected";
                                      } ?>>1</option>
                      <option value=2 <?php if ($last_end_data == 2) {
                                        echo "selected";
                                      } ?>>2</option>
                      <option value=3 <?php if ($last_end_data == 3) {
                                        echo "selected";
                                      } ?>>3</option>
                      <option value=4 <?php if ($last_end_data == 4) {
                                        echo "selected";
                                      } ?>>4</option>
                      <option value=5 <?php if ($last_end_data == 5) {
                                        echo "selected";
                                      } ?>>5</option>
                      <option value=6 <?php if ($last_end_data == 6) {
                                        echo "selected";
                                      } ?>>6</option>
                      <option value=7 <?php if ($last_end_data == 7) {
                                        echo "selected";
                                      } ?>>7</option>
                      <option value=8 <?php if ($last_end_data == 8) {
                                        echo "selected";
                                      } ?>>8</option>
                      <option value=9 <?php if ($last_end_data == 9) {
                                        echo "selected";
                                      } ?>>9</option>
                      <option value=10 <?php if ($last_end_data == 10) {
                                          echo "selected";
                                        } ?>>10</option>
                      <option value=11 <?php if ($last_end_data == 11) {
                                          echo "selected";
                                        } ?>>11</option>
                      <option value=12 <?php if ($last_end_data == 12) {
                                          echo "selected";
                                        } ?>>12</option>
                    </select>
                    <select id="timemin_event_end_time" name="event_end_min" style="padding: 6px 12px;" htype="event_end_time">
                      <option value=00 <?php if ($hour1_end == 00) {
                                          echo "selected";
                                        } ?>>00</option>
                      <option value=05 <?php if ($hour1_end == 05) {
                                          echo "selected";
                                        } ?>>05</option>
                      <option value=10 <?php if ($hour1_end == 10) {
                                          echo "selected";
                                        } ?>>10</option>
                      <option value=15 <?php if ($hour1_end == 15) {
                                          echo "selected";
                                        } ?>>15</option>
                      <option value=20 <?php if ($hour1_end == 20) {
                                          echo "selected";
                                        } ?>>20</option>
                      <option value=25 <?php if ($hour1_end == 25) {
                                          echo "selected";
                                        } ?>>25</option>
                      <option value=30 <?php if ($hour1_end == 30) {
                                          echo "selected";
                                        } ?>>30</option>
                      <option value=35 <?php if ($hour1_end == 35) {
                                          echo "selected";
                                        } ?>>35</option>
                      <option value=40 <?php if ($hour1_end == 40) {
                                          echo "selected";
                                        } ?>>40</option>
                      <option value=45 <?php if ($hour1_end == 45) {
                                          echo "selected";
                                        } ?>>45</option>
                      <option value=50 <?php if ($hour1_end == 50) {
                                          echo "selected";
                                        } ?>>50</option>
                      <option value=55 <?php if ($hour1_end == 55) {
                                          echo "selected";
                                        } ?>>55</option>
                    </select>
                    <select id="timesec_event_end_time" name="event_end_M" style="padding: 6px 12px;" htype="event_end_time">
                      <option value="PM" <?php if ($hour2_end == "PM") {
                                            echo "selected";
                                          } ?>>PM</option>
                      <option value="AM" <?php if ($hour2_end == "AM") {
                                            echo "selected";
                                          } ?>>AM</option>
                    </select>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('time_permission')) : ?>error<?php endif; ?>">
                    <label>Hide End Time from calendar:<span class="required">*</span></label>
                    <div class="radio">
                      <label>
                        <input type="radio" onclick="show1()" name="time_permission" class="" value="Yes" <?php echo ($EDITDATA['time_permission'] == 'Yes') ? 'checked' : 'checked'; ?> />
                        <span style="color: #333; font-size: 15px; top: 50%; left: 50%;text-align: center;">Yes</span>
                      </label>
                    </div>
                    <div class="radio">
                      <label> <input type="radio" onclick="show2()" name="time_permission" class="" value="No" <?php echo ($EDITDATA['time_permission'] == 'No') ? 'checked' : ''; ?> />
                        <span style="color: #333;font-size: 15px;top: 50%;left: 50%;text-align: center;">No</span>
                      </label>
                      <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('time_permission')) : ?>
                        <span for="time_permission" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('time_permission'); ?></span>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>



                <div class="row">
                  <h5>Note : Use below option if event spans over multiple days. <br>
                    Ex. Start -23 Dec , End 25 Dec,<br> Choose Repeating event- Yes, Frequency - Daily and No of repeats-3</h5>
                  <div class="form-group-inner col-lg-10 col-md-8 col-sm-12 col-xs-12  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('repeating_event')) : ?>error<?php endif; ?>">
                    <label>Repeating event:<span class="required">*</span></label>
                    <div class="radio">
                      <label>
                        <input type="radio" onclick="show3()" name="repeating_event" class="" value="Yes" <?php echo ($EDITDATA['repeating_event'] == 'Yes') ? 'checked' : ''; ?> />
                        <span style="color: #333; font-size: 15px; top: 50%; left: 50%;text-align: center;">Yes</span>
                      </label>
                    </div>
                    <?php if ($EDITDATA['repeating_event'] != '' && $EDITDATA['repeating_event'] == 'Yes') {  ?>
                      <div class="radio">
                        <label> <input type="radio" onclick="show4()" name="repeating_event" class="" value="No" <?php echo ($EDITDATA['repeating_event'] == 'No') ? 'checked' : ''; ?> />
                          <span style="color: #333;font-size: 15px;top: 50%;left: 50%;text-align: center;">No</span>
                        </label>
                      </div>
                  </div>
                </div>
              <?php } else { ?>
                <div class="radio">
                  <label> <input type="radio" onclick="show4()" name="repeating_event" class="" value="No" checked />
                    <span style="color: #333;font-size: 15px;top: 50%;left: 50%;text-align: center;">No</span>
                  </label>
                </div>
              <?php } ?>
              <div class="row">
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12   <?php echo ($EDITDATA['repeating_event'] == 'Yes') ? 'show' : 'hide'; ?> <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('content1')) : ?>error<?php endif; ?>" id="frequency">
                  <label>Frequency<span class="required">*</span></label>
                  <!--<select name="frequecy" id="frequecy" class="form-control" >
                                <option value= 'weekly' >weekly</option>
                                <option  value='daily' >daily</option>
                            </select>-->
                  <select class="form-control" name="frequecy" id="frequecy">
                    <option value="">Select Frequency</option>
                    <option <?php if ($EDITDATA['frequecy'] == "weekly") {
                              echo "selected";
                            } ?> value="weekly">Weekly</option>
                    <option <?php if ($EDITDATA['frequecy'] == "daily") {
                              echo "selected";
                            } ?> value="daily">Daily</option>
                  </select>
                </div>
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12  <?php echo ($EDITDATA['repeating_event'] == 'Yes') ? 'show' : 'hide'; ?> <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('no_of_repeat')) : ?>error<?php endif; ?>" id="repeat">
                  <label>Number of repeats:<span class="required">*</span></label>
                  <input type="number" min="0" step="1" name="no_of_repeat" id="no_of_repeat" value="<?= old('no_of_repeat') ?: $EDITDATA['no_of_repeat'] ?>" class="form-control required" placeholder="Number of repeats:">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('no_of_repeat')) : ?>
                    <span for="no_of_repeat" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('no_of_repeat'); ?></span>
                  <?php endif; ?>
                </div>
              </div>

              <div class="row">
                <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('description')) : ?>error<?php endif; ?>">
                  <label>Description</label>
                  <textarea name="description" id="description" class="form-control required" placeholder="Description" style="height: 200px;"><?php if (old('description')) : echo old('description');
                                                                                                                                                else : echo stripslashes($EDITDATA['description']);
                                                                                                                                                endif; ?></textarea>
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('description')) : ?>
                    <span for="description" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('description'); ?></span>
                  <?php endif; ?>
                </div>
              </div>




              <!-- <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                  <div class="form-group">
                    <label>Live Stream</label>
                    <div class="radiobox">
                      <input
                        type="checkbox"
                        id="live_stream"
                        name="live_stream"
                        value="1"
                        style="cursor: pointer;"
                        <?php echo (!empty($EDITDATA['live_stream']) && $EDITDATA['live_stream'] == 1) ? 'checked' : ''; ?>>
                      <label for="live_stream">Live Stream?</label>
                    </div>
                  </div>
                </div>
              </div> -->

              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                  <div class="form-group">
                    <label>Free Concert</label>
                    <div class="radiobox">
                      <input
                        type="checkbox"
                        id="free_concert"
                        name="free_concert"
                        value="1"
                        style="cursor: pointer;"
                        <?php echo (!empty($EDITDATA['free_concert']) && $EDITDATA['free_concert'] == 1) ? 'checked' : ''; ?>>
                      <label for="free_concert">Free Concert?</label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                  <div class="form-group">
                    <label>Virtual Event</label>
                    <div class="radiobox">
                      <input type="checkbox" id="virtual_event" name="virtual_event" value="" style="cursor: pointer;" <?php echo (!empty($EDITDATA['virtual_event_price']) && !empty($EDITDATA['virtual_event_link'])) ? 'checked' : ''; ?>>
                      <label for="virtual event">Virtual Event ?</label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-1 additional_fields" style="display: none;">
                  <label>Virtual Event Price<span class=""></span></label>
                  <input type="number" name="virtual_event_price" id="virtual_event_price" value="<?php if (old('virtual_event_price')) : echo old('virtual_event_price');
                                                                                                  else : echo stripslashes($EDITDATA['virtual_event_price']);
                                                                                                  endif; ?>" class="form-control" placeholder="Virtual Event Price">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('virtual_event_price')) : ?>
                    <span for="virtual_event_price" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('virtual_event_price'); ?></span>
                  <?php endif; ?>
                </div>
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12  additional_fields" style="display: none;">
                  <label>Virtual Event Link<span class=""></span></label>
                  <input type="text" name="virtual_event_link" id="virtual_event_link" value="<?php if (old('virtual_event_link')) : echo old('virtual_event_link');
                                                                                              else : echo stripslashes($EDITDATA['virtual_event_link']);
                                                                                              endif; ?>" class="form-control" placeholder="Virtual Event Link">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('virtual_event_link')) : ?>
                    <span for="virtual_event_link" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('virtual_event_link'); ?></span>
                  <?php endif; ?>
                </div>
              </div>

              <div class="row">
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('content1')) : ?>error<?php endif; ?>" id="frequency">
                  <label>Choose already saved location or type new one below:<span class="required">*</span></label>
                  <select name="save_location_id" class="form-control" id="save_location_id" onchange="getLocationId()">
                    <option value="">Select a saved location</option>
                    <?php
                    if (!empty($location)) :
                      foreach ($location as $location) :
                        if ($location->location_name != '') : ?>
                          <option <?php if ($EDITDATA['save_location_id'] == $location->id) {
                                    echo "selected";
                                  } ?> value="<?= $location->id ?>"><?= $location->location_name; ?></option>
                    <?php endif;
                      endforeach;
                    endif; ?>
                  </select>
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('save_location_id')) : ?>
                    <span for="save_location_id" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('save_location_id'); ?></span>
                  <?php endif; ?>
                </div>

                <div class="form-group-inner  col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('location_name')) : ?>error<?php endif; ?>">
                  <label>Event Location Name:<span class="required">*</span></label>
                  <input type="text" name="location_name" id="location_name" readonly value="<?php if (old('location_name')) : echo old('location_name');
                                                                                              else : echo stripslashes($EDITDATA['location_name']);
                                                                                              endif; ?>" class="form-control required" placeholder="Event Location Name">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('location_name')) : ?>
                    <span for="location_name" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('location_name'); ?></span>
                  <?php endif; ?>
                </div>

              </div>
              <div class="row">

                <div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <label>Select Event Type</label>
                  <select name="event_types" id="event_types" class="form-control">
                    <option value="">Select</option>
                    <?php
                    if (!empty($eventTypes)) :
                      foreach ($eventTypes as $eventType) : ?>
                        <option <?php if ($EDITDATA['event_types'] == $eventType->name) echo "selected"; ?>
                          value="<?= $eventType->name ?>">
                          <?= htmlspecialchars($eventType->name) ?>
                        </option>
                    <?php endforeach;
                    endif; ?>
                  </select>
                </div>

              </div>


              <div class="row">
                <div class="form-group-inner  col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('location_address')) : ?>error<?php endif; ?>">
                  <label>Event Location Address:<span class="required">*</span></label>
                  <input type="text" name="location_address" id="location_address" readonly value="<?php if (old('location_address')) : echo old('location_address');
                                                                                                    else : echo stripslashes($EDITDATA['location_address']);
                                                                                                    endif; ?>" class="form-control required" placeholder="Event Location Address">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('location_address')) : ?>
                    <span for="location_address" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('location_address'); ?></span>
                  <?php endif; ?>
                </div>
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('latitude')) : ?>error<?php endif; ?>">
                  <label>latitude:<span class="required">*</span></label>
                  <input type="text" name="latitude" id="latitude" readonly value="<?php if (old('latitude')) : echo old('latitude');
                                                                                    else : echo stripslashes($EDITDATA['latitude']);
                                                                                    endif; ?>" class="form-control required" placeholder="Latitude">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('latitude')) : ?>
                    <span for="latitude" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('latitude'); ?></span>
                  <?php endif; ?>
                </div>
              </div>


              <div class="row">
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('longitude')) : ?>error<?php endif; ?>">
                  <label>Longitude:<span class="required">*</span></label>
                  <input type="text" name="longitude" id="longitude" readonly value="<?php if (old('longitude')) : echo old('longitude');
                                                                                      else : echo stripslashes($EDITDATA['longitude']);
                                                                                      endif; ?>" class="form-control required" placeholder="Longitude">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('longitude')) : ?>
                    <span for="longitude" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('longitude'); ?></span>
                  <?php endif; ?>
                </div>
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('website')) : ?>error<?php endif; ?>">
                  <label>Website:</label>
                  <input type="text" name="website" id="website" readonly value="<?php if (old('website')) : echo old('website');
                                                                                  else : echo stripslashes($EDITDATA['website']);
                                                                                  endif; ?>" class="form-control required" placeholder="Website">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('website')) : ?>
                    <span for="website" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('website'); ?></span>
                  <?php endif; ?>
                </div>
              </div>

              <div class="row">
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('phone_number')) : ?>error<?php endif; ?>">
                  <label>Phone Number</label>
                  <input type="text" name="phone_number" id="phone_number" readonly maxlength="12" minlength="12" value="<?php if (old('phone_number')) : echo old('phone_number');
                                                                                                                          else : echo stripslashes($EDITDATA['phone_number']);
                                                                                                                          endif; ?>" class="form-control required" placeholder="Phone Number">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('phone_number')) : ?>
                    <span for="phone_number" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('phone_number'); ?></span>
                  <?php endif; ?>
                </div>
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('content1')) : ?>error<?php endif; ?>">
                  <label>Select Venue<span class="required">*</span></label>
                  <select name="venue_id" id="venue_id" class="form-control">
                    <option value="">Select</option>
                    <?php
                    if (!empty($venues)) :
                      foreach ($venues as $venue) : ?>
                        <option <?php if ($EDITDATA['venue_id'] == $venue->id) {
                                  echo "selected";
                                } ?> value="<?= $venue->id ?>"><?= $venue->venue_title ?></option>
                    <?php endforeach;
                    endif; ?>
                  </select>
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('venue_id')) : ?>
                    <span for="venue_id" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('venue_id'); ?></span>
                  <?php endif; ?>
                </div>
              </div>

              <div class="row">
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('cover_charge')) : ?>error<?php endif; ?>">
                  <label>Cover Charge (USD):</span></label>
                  <input type="text" name="cover_charge" id="cover_charge" value="<?php if (old('cover_charge')) : echo old('cover_charge');
                                                                                  else : echo stripslashes($EDITDATA['cover_charge']);
                                                                                  endif; ?>" class="form-control required" placeholder="Cover Charge">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('cover_charge')) : ?>
                    <span for="cover_charge" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('cover_charge'); ?></span>
                  <?php endif; ?>
                </div>
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('set_time')) : ?>error<?php endif; ?>">
                  <label>Set Time:</span></label>
                  <input type="text" name="set_time" id="set_time" value="<?php if (old('set_time')) : echo old('set_time');
                                                                          else : echo stripslashes($EDITDATA['set_time']);
                                                                          endif; ?>" class="form-control required" placeholder="Set Time">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('set_time')) : ?>
                    <span for="set_time" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('set_time'); ?></span>
                  <?php endif; ?>
                </div>
              </div>


              <div class="row">
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('video')) : ?>error<?php endif; ?>">
                  <label>Video Link 1 <span class=""></span></label>
                  <input type="text" name="video" id="video" value="<?php if (old('video')) : echo old('video');
                                                                    else : echo stripslashes($EDITDATA['video']);
                                                                    endif; ?>" class="form-control " placeholder="Video Link">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('video')) : ?>
                    <span for="video" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('video'); ?></span>
                  <?php endif; ?>
                </div>

                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('video2')) : ?>error<?php endif; ?>">
                  <label>Video Link 2 <span class=""></span></label>
                  <input type="text" name="video2" id="video2" value="<?php if (old('video2')) : echo old('video2');
                                                                      else : echo stripslashes($EDITDATA['video2']);
                                                                      endif; ?>" class="form-control " placeholder="Video Link 2">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('video2')) : ?>
                    <span for="video2" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('video2'); ?></span>
                  <?php endif; ?>
                </div>
              </div>



              <div class="row">
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('video3')) : ?>error<?php endif; ?>">
                  <label>Video Link 3 <span class=""></span></label>
                  <input type="text" name="video3" id="video3" value="<?php if (old('video3')) : echo old('video3');
                                                                      else : echo stripslashes($EDITDATA['video3']);
                                                                      endif; ?>" class="form-control " placeholder="Video Link 3">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('video3')) : ?>
                    <span for="video3" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('video3'); ?></span>
                  <?php endif; ?>
                </div>
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('buy_now_link')) : ?>error<?php endif; ?>">
                  <label>Buy Now<span class=""></span></label>
                  <input type="text" name="buy_now_link" id="buy_now_link" value="<?php if (old('buy_now_link')) : echo old('buy_now_link');
                                                                                  else : echo stripslashes($EDITDATA['buy_now_link']);
                                                                                  endif; ?>" class="form-control " placeholder="Buy Now Link">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('buy_now_link')) : ?>
                    <span for="buy_now_link" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('buy_now_link'); ?></span>
                  <?php endif; ?>
                </div>
              </div>

              <div class="row">
                <div class="form-group-innercol-lg-4 col-md-4 col-sm-12 col-xs-12" <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('cover_image')) : ?>error<?php endif; ?>>
                  <div class="animation">
                    <label>Cover Image<span class="required">*</span></label>
                    <input value="<?php if (old('cover_image')) : echo old('cover_image');
                                  else : echo stripslashes($EDITDATA['cover_image']);
                                  endif; ?>" id="uploadFile2" name="cover_image" class="f-input" readonly />
                    <p style="font-family:italic; color:red;">[Image Size : 255 x 318 px in jpg/png/gif/jpeg/webp]</p>
                  </div>
                </div>
                <input type="hidden" name="cover_existing_image" value="<?php echo stripslashes($EDITDATA['cover_image']); ?>" />
                <div class="col-lg-2 px-0">
                  <div class="fileUpload btn btn--browse">
                    <span>Browse</span>
                    <input id="uploadBtn2" type="file" name="cover_image" class="upload" />
                  </div>
                </div>
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <label>Cover Image Url<span class=""></span></label>
                  <input type="text" name="cover_url" id="cover_url" value="<?php if (old('cover_url')) : echo old('cover_url');
                                                                            else : echo stripslashes($EDITDATA['cover_url']);
                                                                            endif; ?>" class="form-control" placeholder="Cover Image Url ">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('cover_url')) : ?>
                    <span for="cover_url" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('cover_url'); ?></span>
                  <?php endif; ?>
                </div>
              </div>

              <div class="row">
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('reserve_seat_link')) : ?>error<?php endif; ?>">
                  <label>Reserve Seat Now<span class=""></span></label>
                  <input type="text" name="reserve_seat_link" id="reserve_seat_link" value="<?php if (old('reserve_seat_link')) : echo old('reserve_seat_link');
                                                                                            else : echo stripslashes($EDITDATA['reserve_seat_link']);
                                                                                            endif; ?>" class="form-control" placeholder="Reserve Seat Now Link">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('reserve_seat_link')) : ?>
                    <span for="reserve_seat_link" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('reserve_seat_link'); ?></span>
                  <?php endif; ?>
                </div>
                <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('event_tags')) : ?>error<?php endif; ?>">
                  <label>Event Tags<span class=""></span></label>
                  <?php

                  // $event_tags = array_column($EDITDATAEVENT, 'event_tags');
                  // // echo"<pre>";print_r($event_tags);die;
                  // $event_tags_string = implode(', ', $event_tags);

                  if (is_array($EDITDATAEVENT)) {
                    $event_tags = array_column($EDITDATAEVENT, 'event_tags');
                    $event_tags_string = implode(', ', $event_tags);
                  } else {
                    $event_tags = '';
                    $event_tags_string = '';
                  }
                  ?>
                  <input type="text" name="event_tags" id="event_tags" class="form-control" placeholder="Funk Jazz, Latin Jazz, Big Band" value="<?php echo htmlspecialchars($event_tags_string); ?>">
                </div>
              </div>

              <div class="row">
                <div class="form-group-inner col-lg-4 col-md-4 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('qr_code_link')) : ?>error<?php endif; ?>">
                  <label>QR Code Link<span class=""></span></label>
                  <input type="text" name="qr_code_link" id="qr_code_link" value="<?php if (old('qr_code_link')) : echo old('qr_code_link');
                                                                                  else : echo stripslashes($EDITDATA['qr_code_link']);
                                                                                  endif; ?>" class="form-control" placeholder="QR Code Link">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('qr_code_link')) : ?>
                    <span for="qr_code_link" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('qr_code_link'); ?></span>
                  <?php endif; ?>
                </div>

                <div class="form-group-inner col-lg-4 col-md-4 col-sm-12 col-xs-12">
                  <label>Select Jazz</label>
                  <select name="jazz_types_id[]" id="jazz_types_id" class="form-control" multiple="multiple">
                    <option value="">Select</option>
                    <?php
                    // Assuming $jazzTypes contains all available jazz types
                    if (!empty($jazzTypes)) :
                      foreach ($jazzTypes as $jazzType) :
                        // Check if the current jazz type is selected
                        $selected_jazz = '';
                        // If EDITDATA['jazz_types_id'] is an array of selected jazz type IDs
                        if (!empty($EDITDATA['jazz_types_id']) && in_array($jazzType->id, $EDITDATA['jazz_types_id'])) {
                          $selected_jazz = 'selected';
                        }
                    ?>
                        <option <?php echo $selected_jazz; ?> value="<?= $jazzType->id ?>"><?= $jazzType->name ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>





                <div class="form-group-inner col-lg-4 col-md-4 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('artist_id')) : ?>error<?php endif; ?>">
                  <label>Select Artist<span class=""></span></label>
                  <select name="artist_id" id="artist_id" class="form-control">
                    <option value="">Select</option>
                    <?php
                    if (!empty($artistTypes)) :
                      foreach ($artistTypes as $artistType) : ?>
                        <option <?php if ($EDITDATA['artist_id'] == $artistType->id) {
                                  echo "selected";
                                } ?> value="<?= $artistType->id ?>">
                          <?= $artistType->artist_name ?>
                        </option>
                    <?php endforeach;
                    endif; ?>
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="login-btn-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="inline-remember-me mt-4">
                    <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
                    <span class="tools pull-left">Note:- <strong><span style="color:#FF0000;">*</span> Indicates Required Fields</strong> </span>
                  </div>
                </div>
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- [ Main Content ] end -->
  </div>
</div>



<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Crop Image Before Upload</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="preview-container">
          <div class="row">
            <div class="col-md-8">
              <img src="" id="sample_image" />
            </div>
            <div class="col-md-4">
              <div class="preview"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="crop" class="btn btn-primary">Crop</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  /*function validateDate() {
  const dateInputs = document.getElementsByClassName("dateInput");
  const pattern = /^(\d{2})-(\d{2})-(\d{4})$/;
  for (let i = 0; i < dateInputs.length; i++) {
    if (!pattern.test(dateInputs[i].value)) {
      alert("Please enter a date in the dd-mm-yyyy format");
      dateInputs[i].value = "";
    }
  }
}*/
</script>

<script>
  $(document).ready(function() {
    $('#jazz_types_id').select2(); // Initialize select2 on correct ID
  });
</script>
<script>
  function showDate() {

    const selectedDate = new Date(document.getElementById("start_date").value);
    const day = selectedDate.getDate().toString().padStart(2, '0');
    const month = (selectedDate.getMonth() + 1).toString().padStart(2, '0');
    const year = selectedDate.getFullYear().toString().substr(-4);
    const formattedDate = `${day}- ${month} - ${year}`;
    const formattedDate1 = `${month} - ${day} - ${year}`;

    document.getElementById("end_date").value = formattedDate1;
    document.getElementById("start_date1").value = formattedDate1;
    document.getElementById('start_date1').style.display = 'inline-block';
    document.getElementById('start_date').style.display = 'none';
  }

  function showDate1() {
    document.getElementById('start_date1').style.display = 'none';
    document.getElementById('start_date').style.display = 'inline-block';

  }
</script>
<script type="text/javascript">
  $(document).ready(function() {

    var $modal = $('#modal');

    var image = document.getElementById('sample_image');

    var cropper;

    $('#upload_image').change(function(event) {
      var files = event.target.files;

      var done = function(url) {
        image.src = url;
        $modal.modal('show');
      };

      if (files && files.length > 0) {
        reader = new FileReader();
        reader.onload = function(event) {
          done(reader.result);
        };
        reader.readAsDataURL(files[0]);
      }
    });

    $modal.on('shown.bs.modal', function() {
      cropper = new Cropper(image, {
        aspectRatio: 2,
        viewMode: 3,
        preview: '.preview'
      });
    }).on('hidden.bs.modal', function() {
      cropper.destroy();
      cropper = null;
    });

    $('#crop').click(function() {
      canvas = cropper.getCroppedCanvas({
        width: 1022,
        height: 412
      });

      canvas.toBlob(function(blob) {
        url = URL.createObjectURL(blob);

        var reader = new FileReader();
        reader.readAsDataURL(blob);
        reader.onloadend = function() {
          var data = reader.result;
          var base64data = reader.result;

          $modal.modal('hide');
          $('#cropImage').empty().val(base64data);
          $("#uploaded_image").attr("src", reader.result);
        };
      });
    });

    function UnicodeDecodeB64(str) {
      return decodeURIComponent(atob(str));
    }
  });
</script>
<script>
  $("#page_section").change(function() {
    var selectedVal = $(this).val();

    if (selectedVal == "about") {

      $('#show').show();
    } else {

      $('#show').hide();
    }
  });
</script>
<script>
  document.getElementById("uploadBtn2").onchange = function() {
    document.getElementById("uploadFile2").value = this.value.replace("C:\\fakepath\\", "");
  };

  function show1() {
    document.getElementById('end_time').style.display = 'none';
  }

  function show2() {
    document.getElementById('end_time').style.display = 'inline-block';
  }

  function show4() {
    document.getElementById('repeat').style.display = 'none';
    document.getElementById('frequency').style.display = 'none';
  }

  function show3() {
    document.getElementById('repeat').style.display = 'inline-block';
    document.getElementById('frequency').style.display = 'inline-block';
  }
</script>

<script>
  function getLocationId() {

    var save_location_id = $('#save_location_id').val();
    $("#location_name").val('');
    $("#location_address").val('');
    // $("#longitude").val('');
    // $("#latitude").val('');
    // $("#phone_number").val('');
    // $("#website").val('');
    // $("#venue_id").val('');
    var data = 'LocationId=' + save_location_id;
    $.ajax({
      type: 'GET',
      dataType: 'json',
      url: "<?= base_url('hhjsitemgmt/eventmanagement/location') ?>",
      data: data,
      success: function(response) {
        ///alert(data.name);
        console.log(data);
        var data = response.data;
        $("#location_name").val(data.location_name);
        $("#location_address").val(data.location_address);
        $("#longitude").val(data.longitude);
        $("#latitude").val(data.latitude);
        $("#phone_number").val(data.phone_number);
        $("#website").val(data.website);
        $("#venue_id").val(data.venue_id);
        $("#jazz_types_id").val(data.jazz_types_id);
        $("#event_types").val(data.event_location_type_name);
        $("#artist_id").val(data.artist_id);

      }

    });
  }
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script>
  $(document).ready(function() {
    $("#dt1").datepicker({
      dateFormat: "yy-mm-dd",
      minDate: 0,
      onSelect: function() {
        var dt2 = $('#dt2');
        var startDate = $(this).datepicker('getDate');
        //add 30 days to selected date
        startDate.setDate(startDate.getDate() + 30);
        var minDate = $(this).datepicker('getDate');
        var dt2Date = dt2.datepicker('getDate');
        //difference in days. 86400 seconds in day, 1000 ms in second
        var dateDiff = (dt2Date - minDate) / (86400 * 1000);

        //dt2 not set or dt1 date is greater than dt2 date
        if (dt2Date == null || dateDiff < 0) {
          dt2.datepicker('setDate', minDate);
        }
        //dt1 date is 30 days under dt2 date
        else if (dateDiff > 30) {
          dt2.datepicker('setDate', startDate);
        }
        //sets dt2 maxDate to the last day of 30 days window
        //dt2.datepicker('option', 'minDate', startDate);
        //first day which can be selected in dt2 is selected date in dt1
        //dt2.datepicker('option', 'maxDate', minDate);
        dt2.datepicker('setDate', minDate);
      }
    });
    $('#dt2').datepicker({
      dateFormat: "yy-mm-dd",
      minDate: 0
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var checkbox = document.getElementById("virtual_event");
    var additionalFields = document.getElementsByClassName("additional_fields");

    function toggleAdditionalFields() {
      if (checkbox.checked) {
        for (var i = 0; i < additionalFields.length; i++) {
          additionalFields[i].style.display = "block";
        }
      } else {
        for (var i = 0; i < additionalFields.length; i++) {
          additionalFields[i].style.display = "none";
        }
      }
    }

    toggleAdditionalFields();

    checkbox.addEventListener("change", toggleAdditionalFields);
  });
</script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>