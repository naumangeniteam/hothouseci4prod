<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

   .select2-container {
      width: 98% !important;
   }

   .select2-selection__choice {
      color: black !important;
      background-color: #007bff;
      border: 1px solid #007bff;
      padding: 2px 5px;
      margin-right: 5px;
   }

   .select2-container--default .select2-selection--multiple .select2-selection__choice {
      background-color: transparent !important;
   }

   .select2-selection__choice__remove {
      color: black !important;
      cursor: pointer;
   }

   .add-lineup {
      border: 1px solid #808080;
      padding: 10px;
      color: #fff;
      background: #808080;
      cursor: pointer;
   }

   .datainputs {
      margin-top: 20px;
   }

   .datainputs input {
      font-size: 18px;
      line-height: 21px;
      color: #3D3D3D;
      height: 50px;
      padding: 0px 20px;
      border-radius: 5px;
      border: 1px solid #ddd;
      box-shadow: 0px 0px 1px #D8D8D8;
      margin: 0px 30px 20px 0px !important;
   }

   input:focus {
      outline: none;
   }

   .add_input,
   .inputRemove {
      display: inline-block;
      color: #3d3d3d;
      text-align: center;
      text-decoration: none;
      width: auto;
      height: 40px;
      line-height: 40px;
      border: 2px solid #3d3d3d;
      padding: 0px 15px;
      border-radius: 5px;
   }

   .inputRemove {
      cursor: pointer;
   }

   .common {
      width: 96%;
      height: 43px;
   }

   .fest-time {
      padding: 6px;
   }

   .tested {
      display: flex;
      align-items: center;
   }


   .test-days {
      display: flex;
      align-items: center;
   }

   html {
      margin: 50px;
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

   .hide {
      display: none;
   }

   .show {
      display: block;
   }
</style>
<?php
// $validation = session()->get('validation');
// print_r($validation);
// die;
?>

<?php
// print_r(session('validation'));
// die;
?>
<div class="pcoded-main-container">
   <div class="pcoded-content">
      <!-- [ breadcrumb ] start -->
      <div class="page-header">
         <div class="page-block">
            <div class="row align-items-center">
               <div class="col-md-12">
                  <div class="page-header-title">
                     <?php /* ?>
                     <h5 class="m-b-10">Welcome <?=sessionData('ILCADM_ADMIN_FIRST_NAME')?></h5>
                     <?php */ ?>
                  </div>
                  <ul class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>"> Festival List</a></li>
                     <li class="breadcrumb-item"><a href="javascript:void(0);"><?= $EDITDATA ? 'Edit' : 'Add' ?> Festival Content</a></li>
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
                  <h5><?= $EDITDATA ? 'Edit' : 'Add' ?> Festival Content</h5>
               </div>
               <div class="card-body">
                  <div class="basic-login-inner">
                     <form id="currentPageFormSubadmin" name="currentPageFormSubadmin" class="form-auth-small" method="post" action="" enctype="multipart/form-data">
                        <div class="row">
                           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>" style="margin-left: 12px;" class="btn btn-sm btn-primary pull-right">Back</a>
                              <button class="btn btn-primary  btn-sm pull-right">Submit</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>" style="margin-right: 11px;" class="btn btn-danger has-ripple btn-sm pull-right">Cancel</a>

                              <input type="hidden" name="CurrentFieldForUnique" id="CurrentFieldForUnique" value="id" />
                              <input type="hidden" name="CurrentIdForUnique" id="CurrentIdForUnique" value="<?= $EDITDATA['festival_id'] ??''?>" />
                              <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?= $EDITDATA['festival_id'] ??''?>" />
                              <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                           </div>
                        </div>

                        <div class="row">
                           <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session('validation') && session('validation')->hasError('festival_name')) : ?>error<?php endif; ?>">
                              <label>Festival Name<span class="required">*</span></label>
                              <input type="text" name="festival_name" id="festival_name" value="<?php if (old('festival_name')) : echo old('festival_name');
                                                                                                else : echo stripslashes($EDITDATA['festival_name'] ??'');
                                                                                                endif; ?>" class="form-control required" placeholder="Festivals Name">
                              <?php if (session('validation') && session('validation')->hasError('festival_name')) : ?>
                                 <span for="festival_name" generated="true" class="help-inline"><?php echo session('validation')->getError('festival_name'); ?></span>
                              <?php endif; ?>
                           </div>
                        </div>

                        <div class="row">
                           <div class="form-group-inner col-lg-4 col-md-4 col-sm-6 col-xs-12 <?php if (session('validation') && session('validation')->hasError('summary')) : ?>error<?php endif; ?>">
                              <div class="animation">
                                 <label>Description<span class="required"></span></label>
                                 <textarea name="summary" id="summary" rows="4" cols="80"><?php if (old('summary')) : echo old('summary');
                                                                                          else : echo stripslashes($EDITDATA['summary'] ??'');
                                                                                          endif; ?></textarea>
                                 <?php if (session('validation') && session('validation')->hasError('summary')) : ?>
                                    <span for="summary" generated="true" class="help-inline"><?php echo session('validation')->getError('summary'); ?></span>
                                 <?php endif; ?>
                              </div>
                           </div>
                        </div>

                        <div class="row">
                           <div class="form-group-inner col-lg-4 col-md-4 col-sm-6 col-xs-12 <?php if (session('validation') && session('validation')->hasError('lineup')) : ?>error<?php endif; ?>">
                              <div class="animation">
                                 <label>Lineup Summary<span class="required"></span></label>
                                 <textarea name="lineup" id="lineup" rows="4" cols="80"><?php if (old('lineup')) : echo old('lineup');
                                                                                          else : echo stripslashes($EDITDATA['lineup']??'');
                                                                                          endif; ?></textarea>
                                 <?php if (session('validation') && session('validation')->hasError('lineup')) : ?>
                                    <span for="lineup" generated="true" class="help-inline"><?php echo session('validation')->getError('lineup'); ?></span>
                                 <?php endif; ?>
                              </div>
                           </div>
                        </div>

                        <div class="row">
                           <div class="form-group-inner col-lg-4 col-md-4 col-sm-12 col-xs-12  <?php if (session('validation') && session('validation')->hasError('start_date')) : ?>error<?php endif; ?>">
                              <label>Start Date<span class="required">*</span></label>
                              <input type="text" autocomplete="off" id="dt1" name="start_date" class="form-control" value="<?php if (old('start_date')) : echo old('start_date');
                                                                                                                           else : echo stripslashes($EDITDATA['start_date'] ??'');
                                                                                                                           endif; ?>">
                              <?php if (session('validation') && session('validation')->hasError('start_date')) : ?>
                                 <span for="start_date" generated="true" class="help-inline"><?php echo session('validation')->getError('start_date'); ?></span>
                              <?php endif; ?>
                           </div>


                           <div class="form-group-inner col-lg-4 col-md-4 col-sm-12 col-xs-12 <?php if (session('validation') && session('validation')->hasError('end_date')) : ?>error<?php endif; ?>">
                              <label>End Date<span class="required">*</span></label>
                              <input type="text" autocomplete="off" id="dt2" name="end_date" class="form-control dateInput" value="<?php if (old('start_date')) : echo old('start_date');
                                                                                                                                    else : echo stripslashes($EDITDATA['end_date']??'');
                                                                                                                                    endif; ?>">
                              <?php if (session('validation') && session('validation')->hasError('end_date')) : ?>
                                 <span for="end_date" generated="true" class="help-inline"><?php echo session('validation')->getError('end_date');  ?></span>
                              <?php endif; ?>
                           </div>
                        </div>

                        <div class="row">
                           <div class="form-group-inner col-lg-4 col-md-4 col-sm-6 col-xs-12" <?php if (session('validation') && session('validation')->hasError('image')) : ?>error<?php endif; ?>>
                              <div class="animation">
                                 <label>Image<span class="required">*</span></label>
                                 <input value="<?php if (old('image')) : echo old('image');
                                                else : echo stripslashes($EDITDATA['image']??'');
                                                endif; ?>" id="uploadFile" name="image" class="f-input" readonly />
                                 <p style="font-family:italic; color:red;">[Image Size : 255 x 318 px in jpg/png/gif/jpeg/webp]</p>
                              </div>
                           </div>

                           <div class="col-lg-4 px-0">
                              <div class="fileUpload btn btn--browse">
                                 <span>Browse</span>
                                 <input id="uploadBtn" type="file" name="image" class="upload" />
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="form-group-inner col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session('validation') && session('validation')->hasError('content1')) : ?>error<?php endif; ?>" id="frequency">
                              <label>Choose already saved location or type new one below:<span class="required">*</span></label>
                              <select name="save_location_id" class="form-control" id="save_location_id" onchange="getLocationId()">
                                 <option value="">Select a saved location</option>
                                 <?php
                                 if (!empty($location)) :
                                    foreach ($location as $location) :
                                       if ($location->location_name != '') : ?>
                                          <option <?php if ($EDITDATA['save_location_id'] ??''== $location->id) {
                                                      echo "selected";
                                                   } ?> value="<?= $location->id ?>"><?= $location->location_name; ?></option>
                                 <?php endif;
                                    endforeach;
                                 endif; ?>
                              </select>
                              <?php if (session('validation') && session('validation')->hasError('save_location_id')) : ?>
                                 <span for="save_location_id" generated="true" class="help-inline"><?php echo session('validation')->getError('save_location_id'); ?></span>
                              <?php endif; ?>
                           </div>

                           <div class="form-group-inner  col-lg-6 col-md-6 col-sm-12 col-xs-12 <?php if (session('validation') && session('validation')->hasError('location_name')) : ?>error<?php endif; ?>">
                              <label>Festival Location Name:<span class="required">*</span></label>
                              <input type="text" name="location_name" id="location_name" readonly value="<?php if (old('location_name')) : echo old('location_name');
                                                                                                         else : echo stripslashes($EDITDATA['location_name']??'');
                                                                                                         endif; ?>" class="form-control required" placeholder="Event Location Name">
                              <?php if (session('validation') && session('validation')->hasError('location_name')) : ?>
                                 <span for="location_name" generated="true" class="help-inline"><?php echo session('validation')->getError('location_name'); ?></span>
                              <?php endif; ?>
                           </div>
                        </div>


                        <div class="row">
                           <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session('validation') && session('validation')->hasError('content1')) : ?>error<?php endif; ?>">
                              <label>Select Venue<span class="required">*</span></label>
                              <select name="venue_id" id="venue_id" class="form-control">
                                 <option value="">Select</option>
                                 <?php
                                 if (!empty($venues)) :
                                    foreach ($venues as $venue) : ?>
                                       <option <?php if ($EDITDATA['venue_id'] ??''== $venue->id) {
                                                   echo "selected";
                                                } ?> value="<?= $venue->id ?>"><?= $venue->venue_title ?></option>
                                 <?php endforeach;
                                 endif; ?>
                              </select>
                              <?php if (session('validation') && session('validation')->hasError('venue_id')) : ?>
                                 <span for="venue_id" generated="true" class="help-inline"><?php echo session('validation')->getError('venue_id'); ?></span>
                              <?php endif; ?>
                           </div>
                        </div>

                        <div class="row">
                           <div class="form-group-inner col-lg-4 col-md-4 col-sm-12 col-xs-12 <?php if (session('validation') && session('validation')->hasError('venue_title')) : ?>error<?php endif; ?>">
                              <label>Festival Location Address<span class=""></span></label>
                              <input type="text" autocomplete="off" name="location_address" id="address" value="<?php if (old('location_address')) : echo old('location_address');
                                                                                                                  else : echo stripslashes($EDITDATA['location_address']??'');
                                                                                                                  endif; ?>" class="form-control required" placeholder="Festival Location Address">
                              <?php if (session('validation') && session('validation')->hasError('location_address')) : ?>
                                 <span for="location_address" generated="true" class="help-inline"><?php echo session('validation')->getError('location_address'); ?></span>
                              <?php endif; ?>
                           </div>

                           <div class="form-group-inner col-lg-4 col-md-4 col-sm-12 col-xs-12 <?php if (session('validation') && session('validation')->hasError('longitude')) : ?>error<?php endif; ?>">
                              <label>Longitude<span class=""></span></label>
                              <input type="text" name="longitude" id="address-longitude" value="<?php if (old('longitude')) : echo old('longitude');
                                                                                                else : echo stripslashes($EDITDATA['longitude']??'');
                                                                                                endif; ?>" class="form-control required" placeholder="Longitude" readonly>
                              <?php if (session('validation') && session('validation')->hasError('longitude')) : ?>
                                 <span for="longitude" generated="true" class="help-inline"><?php echo session('validation')->getError('longitude'); ?></span>
                              <?php endif; ?>
                           </div>

                           <div class="form-group-inner col-lg-4 col-md-4 col-sm-12 col-xs-12 <?php if (session('validation') && session('validation')->hasError('latitude')) : ?>error<?php endif; ?>">
                              <label>Latitude<span class=""></span></label>
                              <input type="text" name="latitude" id="address-latitude" value="<?php if (old('latitude')) : echo old('latitude');
                                                                                                else : echo stripslashes($EDITDATA['latitude']??'');
                                                                                                endif; ?>" class="form-control required" placeholder="Latitude" readonly>
                              <?php if (session('validation') && session('validation')->hasError('latitude')) : ?>
                                 <span for="latitude" generated="true" class="help-inline"><?php echo session('validation')->getError('latitude'); ?></span>
                              <?php endif; ?>
                           </div>
                        </div>
                        <div class="row">
                           <div class="form-group-inner col-lg-4 col-md-4 col-sm-12 col-xs-12 <?php if (session('validation') && session('validation')->hasError('website')) : ?>error<?php endif; ?>">
                              <label>Url</label>
                              <input type="text" name="website" id="website" value="<?php if (old('website')) : echo old('website');
                                                                                    else : echo stripslashes($EDITDATA['website']??'');
                                                                                    endif; ?>" class="form-control required" placeholder="url">
                              <?php if (session('validation') && session('validation')->hasError('website')) : ?>
                                 <span for="website" generated="true" class="help-inline"><?php echo session('validation')->getError('website'); ?></span>
                              <?php endif; ?>
                           </div>

                           <div class="form-group-inner col-lg-4 col-md-4 col-sm-12 col-xs-12 <?php if (session('validation') && session('validation')->hasError('phone_number')) : ?>error<?php endif; ?>">
                              <label>Phone Number</label>
                              <input type="text" name="phone_number" id="phone_number" maxlength="12" minlength="12" value="<?php if (old('phone_number')) : echo old('phone_number');
                                                                                                                              else : echo stripslashes($EDITDATA['phone_number']??'');
                                                                                                                              endif; ?>" class="form-control required" placeholder="Phone Number">
                              <?php if (session('validation') && session('validation')->hasError('phone_number')) : ?>
                                 <span for="phone_number" generated="true" class="help-inline"><?php echo session('validation')->getError('phone_number'); ?></span>
                              <?php endif; ?>
                           </div>


                           <!-- <div class="form-group-inner col-lg-4 col-md-4 col-sm-12 col-xs-12 <?php if (session('validation') && session('validation')->hasError('city_state_name')) : ?>error<?php endif; ?>">
                              <label>City, State<span class=""></span></label>
                              <input type="text" name="city_state_name" id="city_state_name" value="<?php if (old('year')) : echo old('city_state_name');
                                                                                                      else : echo stripslashes($EDITDATA['city_state_name']??'');
                                                                                                      endif; ?>" class="form-control required" placeholder="City, State">
                              <?php if (session('validation') && session('validation')->hasError('city_state_name')) : ?>
                                 <span for="city_state_name" generated="true" class="help-inline"><?php echo session('validation')->getError('city_state_name'); ?></span>
                              <?php endif; ?>
                           </div> -->
                        </div>

                        <div class="row">
                           <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session('validation') && session('validation')->hasError('year')) : ?>error<?php endif; ?>">
                              <label>Year<span class="required"></span></label>
                              <input type="text" name="year" id="year" value="<?php if (old('year')) : echo old('year');
                                                                              else : echo stripslashes($EDITDATA['year']??'');
                                                                              endif; ?>" class="form-control required" placeholder="Year">
                              <?php if (session('validation') && session('validation')->hasError('year')) : ?>
                                 <span for="year" generated="true" class="help-inline"><?php echo session('validation')->getError('year'); ?></span>
                              <?php endif; ?>
                           </div>
                        </div>

                        <div class="row">
                           <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session('validation') && session('validation')->hasError('media_video_link')) : ?>error<?php endif; ?>">
                              <label>Media Video Link<span class=""></span></label>
                              <input type="text" name="media_video_link" id="video" value="<?php if (old('media_video_link')) : echo old('media_video_link');
                                                                                             else : echo stripslashes($EDITDATA['media_video_link']??'');
                                                                                             endif; ?>" class="form-control " placeholder="Media Video Link">
                              <?php if (session('validation') && session('validation')->hasError('media_video_link')) : ?>
                                 <span for="media_video_link" generated="true" class="help-inline"><?php echo session('validation')->getError('media_video_link'); ?></span>
                              <?php endif; ?>
                           </div>
                        </div>

                        <div class="row" id="req_input">
                           <?php foreach ($EDITDATALINEUP as $lineuo_key => $lineup) : ?>
                              <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session('validation') && session('validation')->hasError('artist_id[]')) : ?>error<?php endif; ?>">
                                 <div class="tested">
                                    <div class="input-group">
                                       <?php
                                       $start_time = $lineup->start_time;
                                       $end_time = $lineup->end_time;
                                       $start_hour = date('g', strtotime($start_time));
                                       $start_min = date('i', strtotime($start_time));
                                       $start_am_pm = date('A', strtotime($start_time));
                                       $end_hour = date('g', strtotime($end_time));
                                       $end_min = date('i', strtotime($end_time));
                                       $end_am_pm = date('A', strtotime($end_time));
                                       ?>
                                       <label class="fest-time">Start Time: </label>
                                       <input type="hidden" name="start_time[]" value="">
                                       <select name="event_start_hour[]" style="padding: 6px 12px;">
                                          <?php for ($i = 1; $i <= 12; $i++) : ?>
                                             <option value="<?= $i ?>" <?= $start_hour == $i ? 'selected' : '' ?>><?= $i ?></option>
                                          <?php endfor; ?>
                                       </select>
                                       <select name="event_start_min[]" style="padding: 6px 12px;">
                                          <?php for ($i = 0; $i <= 55; $i += 5) : ?>
                                             <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= $start_min == $i ? 'selected' : '' ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
                                          <?php endfor; ?>
                                       </select>
                                       <select name="event_start_M[]" style="padding: 6px 12px;">
                                          <option value="AM" <?= $start_am_pm == 'AM' ? 'selected' : '' ?>>AM</option>
                                          <option value="PM" <?= $start_am_pm == 'PM' ? 'selected' : '' ?>>PM</option>
                                       </select>
                                    </div>

                                    <div class="input-group">
                                       <label class="fest-time">End Time:</label>
                                       <input type="hidden" name="end_time[]" value="">
                                       <select name="event_end_hour[]" style="padding: 6px 12px;">
                                          <?php for ($i = 1; $i <= 12; $i++) : ?>
                                             <option value="<?= $i ?>" <?= $end_hour == $i ? 'selected' : '' ?>><?= $i ?></option>
                                          <?php endfor; ?>
                                       </select>
                                       <select name="event_end_min[]" style="padding: 6px 12px;">
                                          <?php for ($i = 0; $i <= 55; $i += 5) : ?>
                                             <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= $end_min == $i ? 'selected' : '' ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
                                          <?php endfor; ?>
                                       </select>
                                       <select name="event_end_M[]" style="padding: 6px 12px;">
                                          <option value="AM" <?= $end_am_pm == 'AM' ? 'selected' : '' ?>>AM</option>
                                          <option value="PM" <?= $end_am_pm == 'PM' ? 'selected' : '' ?>>PM</option>
                                       </select>
                                    </div>
                                 </div>

                                 <div class="test-days">
                                    <div class="input-group">
                                       <label>Add Day</label>
                                       <input class="common" name="day[]" placeholder="Enter Day" type="text" value="<?= htmlspecialchars($lineup->day) ?>">
                                    </div>

                                    <div class="input-group">
                                       <label>Add Location</label>
                                       <input class="common" name="location[]" placeholder="Enter Location" type="text" value="<?= htmlspecialchars($lineup->location) ?>">
                                    </div>
                                 </div>

                                 <label>Add Artist</label>
                                 <div class="input-group">
                                    <select name="artist_id[<?php echo $lineuo_key ?>][]" class="artist-multiple2 form-control" multiple="multiple">
                                       <option value="">Select</option>
                                       <?php
                                       if (!empty($artistTypes)) {
                                          foreach ($artistTypes as $artistType) {
                                             $selected = in_array($artistType->id, json_decode($lineup->artist_id)) ? 'selected' : '';
                                             echo "<option value=\"{$artistType->id}\" {$selected}>{$artistType->artist_name}</option>";
                                          }
                                       }
                                       ?>
                                    </select>

                                 </div>

                                 <label>Add Jazz</label>
                                 <div class="input-group">
                                    <select name="jazz_types_id[<?php echo $lineuo_key ?>][]" class="jazz-multiple2 form-control" multiple="multiple">
                                       <option value="">Select</option>
                                       <?php
                                       if (!empty($jazzTypes)) {
                                          foreach ($jazzTypes as $jazzType) {
                                             $selected = in_array($jazzType->id, json_decode($lineup->jazz_types_id)) ? 'selected' : '';
                                             echo "<option value=\"{$jazzType->id}\" {$selected}>{$jazzType->name}</option>";
                                          }
                                       }
                                       ?>
                                    </select>
                                    <input type="button" class="inputRemove mt-3" value="Remove">
                                 </div>

                              </div>
                           <?php endforeach; ?>

                        </div>
                        <a id="addmore" class="add_input">+ ADD Lineup </a>

                        <div class="row">
                           <div class="login-btn-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <div class="inline-remember-me mt-4">
                                 <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
                                 <span class="tools pull-right">Note:- <strong><span style="color:#FF0000;">*</span> Indicates Required Fields</strong> </span>
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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB9bu_dYx-Yzl6mwUxsKYSSq_p1yHJO6H8&libraries=places" async defer charset="utf-8"></script>

<script>
   function geocodeAddress() {
      var geocoder = new google.maps.Geocoder();
      var address = document.getElementById('address').value;

      geocoder.geocode({
         'address': address
      }, function(results, status) {
         if (status === google.maps.GeocoderStatus.OK) {
            var latitude = results[0].geometry.location.lat();
            var longitude = results[0].geometry.location.lng();

            // Assign the latitude and longitude values to the respective form fields
            document.getElementById('address-latitude').value = latitude;
            document.getElementById('address-longitude').value = longitude;
         } else {
            alert('Geocode was not successful for the following reason: ' + status);
         }
      });
   }

   // Add an event listener to trigger geocoding when the address input changes
   document.getElementById('address').addEventListener('change', geocodeAddress);
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
   document.getElementById("uploadBtn").onchange = function() {
      document.getElementById("uploadFile").value = this.value.replace("C:\\fakepath\\", "");
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
            console.log(response);
            var data =response.data;
            $("#location_name").val(data.location_name);
            $("#address").val(data.location_address);
            $("#location_address").val(data.location_address);
            $("#address-latitude").val(data.longitude);
            $("#address-longitude").val(data.latitude);
            $("#phone_number").val(data.phone_number);
            $("#website").val(data.website);
            $("#venue_id").val(data.venue_id);
            //$("#jazz_types_id").val(data.jazz_types_id);
            //$("#artist_id").val(data.artist_id);

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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
   $(document).ready(function() {
      $('.artist-multiple2').select2({
         tags: true
      });
   });
</script>
<script>
   $(document).ready(function() {
      $('.jazz-multiple2').select2();
   });
</script>

<script>
   $(document).ready(function() {

      <?php
      if (count($EDITDATALINEUP)) { ?>
         var count = parseInt(<?php echo count($EDITDATALINEUP)  ?>);
      <?php } else { ?>
         var count = 0;
      <?php }
      ?>
      $("#addmore").click(function() {
         const newInput = `
        <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session('validation') && session('validation')->hasError('artist_id[]')) : ?>error<?php endif; ?>">
         
         <div class="tested">
         <div class="input-group">
               <?php
               // $split = $EDITDATALINEUP['start_time'];
               // $last_data = substr($split, 0, -6);
               // $time = "2:00";
               // $hour = explode(':', $split)[1];
               // $hour1 = explode(' ', $hour)[0];
               // $hour2 = explode(' ', $hour)[1];

               $split = $EDITDATALINEUP['start_time'] ?? null; 

if ($split) { 
    $last_data = substr($split, 0, -6);
    $time = "2:00";
    
    $hourParts = explode(':', $split);
    
    if (isset($hourParts[1])) {
        $hour = $hourParts[1];
        $hourParts2 = explode(' ', $hour);
        
        $hour1 = $hourParts2[0] ?? ''; // Avoid undefined index error
        $hour2 = $hourParts2[1] ?? ''; // Avoid undefined index error
    } else {
        $hour1 = '';
        $hour2 = '';
    }
} else {
    $last_data = $time = $hour1 = $hour2 = ''; // Set default empty values
}
               ?>
              <label class="fest-time">Start Time: </label>
                  <input type="hidden" name="start_time[]" id="event_start_hour" value="">
                        <select id="timehr_event_end_time" name="event_start_hour[]" style="padding: 6px 12px;" htype="event_end_time" onchange="setTime(this);">
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
										<select id="timemin_event_end_time" name="event_start_min[]" style="padding: 6px 12px;" htype="event_end_time">
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
                  <select id="timesec_event_end_time" name="event_start_M[]" style="padding: 6px 12px;" htype="event_end_time">
                     <option value="PM" <?php if ($hour2 == "PM") {
                                             echo "selected";
                                          } ?>>PM</option>
                     <option value="AM" <?php if ($hour2 == "AM") {
                                             echo "selected";
                                          } ?>>AM</option>
                  </select>
            </div>
            <?php
         $split_end = $EDITDATALINEUP['end_time'] ?? ''; // Default to empty string if key is missing

         $last_end_data = substr($split_end, 0, -6);
         
         if (!empty($split_end)) { 
             $hour_end = explode(':', $split_end)[1] ?? ''; 
             $hour1_end = explode(' ', $hour_end)[0] ?? ''; 
             $hour2_end = explode(' ', $hour_end)[1] ?? ''; 
         } else {
             $hour1_end = $hour2_end = ''; // Provide default values if 'end_time' is missing
         }
         
            ?>

            <div class="input-group">
            <label class="fest-time">End Time:</label>
            <input type="hidden" name="end_time[]" id="event_end_time" value="">
                           <select id="timehr_event_end_time" name="event_end_hour[]" style="padding: 6px 12px;" htype="event_end_time" onchange="setTime(this);">

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
               <select id="timemin_event_end_time" name="event_end_min[]" style="padding: 6px 12px;" htype="event_end_time">
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
               <select id="timesec_event_end_time" name="event_end_M[]" style="padding: 6px 12px;" htype="event_end_time">
               <option value="PM" <?php if ($hour2_end == "PM") {
                                       echo "selected";
                                    } ?>>PM</option>
               <option value="AM" <?php if ($hour2_end == "AM") {
                                       echo "selected";
                                    } ?>>AM</option>
               </select>				
            </div>
         </div>
            <br>
            
            <div class="test-days">
            
            <div class="input-group">
            <label>Add Day</label>
                <input class="common" name="day[]" placeholder="Enter Day" type="text" value="<?php if (old('day')) : echo old('day');
                                                                                                else : echo stripslashes($EDITDATALINEUP['day']??'');
                                                                                                endif; ?>">
            </div>
            <br>
           
            <div class="input-group">
            <label>Add Location</label>
                <input class="common" name="location[]" placeholder="Enter Location" type="text" value="<?php if (old('location')) : echo old('location');
                                                                                                         else : echo stripslashes($EDITDATALINEUP['location']??'');
                                                                                                         endif; ?>">
            </div>

            </div>
           
            <br>
            <label>Add Artist</label>
            <div class="input-group">
            <select name="artist_id[${count}][]" class="artist-multiple2 form-control" multiple="multiple">
               <option value="">Select</option>
               <?php if (!empty($artistTypes)) : ?>
                  <?php foreach ($artistTypes as $artistType) : ?>
                     <?php
                     $selected = '';
                     if (!empty($EDITDATALINEUP['artist_id'])) {
                        if (in_array($artistType->id, json_decode($EDITDATALINEUP['artist_id']))) {
                           $selected = 'selected';
                        }
                     }
                     ?>
                     <option value="<?= htmlspecialchars($artistType->id) ?>" <?= $selected ?>>
                        <?= htmlspecialchars($artistType->artist_name) ?>
                     </option>
                  <?php endforeach; ?>
               <?php endif; ?>
            </select>


            <label>Add Jazz</label>
            <div class="input-group">
            <select name="jazz_types_id[${count}][]" class="jazz-multiple2 form-control" multiple="multiple">
               <option value="">Select</option>
               <?php
               if (!empty($jazzTypes)) :
                  foreach ($jazzTypes as $jazzType) :
                     $selected = '';
                     if (!empty($EDITDATALINEUP['jazz_types_id'])) {
                        if (in_array($jazzType->id, json_decode($EDITDATALINEUP['jazz_types_id']))) {
                           $selected = 'selected';
                        }
                     }
               ?>
                                       <option value="<?= $jazzType->id ?>" <?= $selected ?>>
                                          <?= $jazzType->name ?>
                                       </option>
                                 <?php endforeach;
                           endif; ?>
            </select>
                <input type="button" class="inputRemove mt-3" value="Remove">
            </div>
        </div>
        `;
         $("#req_input").append(newInput);
         count++
         $('.artist-multiple2').select2({
            tags: true
         });
         $('.jazz-multiple2').select2();
      });

      $('#req_input').on('click', '.inputRemove', function() {
         $(this).closest('.form-group-inner').remove();
      });
   });
</script>