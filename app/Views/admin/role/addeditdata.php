<?php $validation = session()->getFlashdata('validation'); ?>
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


   html {
      margin: 50px;
   }

   .all-text {
      font-weight: bold;
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
                     <li class="breadcrumb-item"><a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>">Role List</a></li>
                     <li class="breadcrumb-item"><a href="javascript:void(0);"><?= $EDITDATA ? 'Edit' : 'Add' ?> Role</a></li>
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
                  <h5><?= $EDITDATA ? 'Edit' : 'Add' ?> Role</h5>
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
                              <input type="hidden" name="CurrentIdForUnique" id="CurrentIdForUnique" value="<?= $EDITDATA['id'] ?>" />
                              <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?= $EDITDATA['id'] ?>" />
                              <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                           </div>
                        </div>


                        <div class="row">
                           <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('role_name')) : ?>error<?php endif; ?>">
                              <label>Role Name<span class="required">*</span></label>
                              <input type="text" name="role_name" id="role_name" value="<?php if (old('role_name')) : echo old('role_name');
                                                                                          else : echo stripslashes($EDITDATA['role_name']);
                                                                                          endif; ?>" class="form-control required" placeholder="Role Name">
                              <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('role_name')) : ?>
                                 <span for="role_name" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('role_name'); ?></span>
                              <?php endif; ?>
                           </div>
                        </div>

                        <div class="row">
                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Select All</label>
                              <div class="check-details">
                                 <input type="checkbox" id="checkAll">
                              </div>
                           </div>
                        </div>

                        <div class="row">
                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Dashboard</label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="maindashboard" <?php echo in_array("maindashboard", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Dashboard</span>
                              </div>
                           </div>

                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Venue<span class=""></span></label>
                              <div class="check-details">
                                 <div class="check-details">
                                    <input type="checkbox" name="permission[]" value="adminmanagevenuecategory/index" <?php echo in_array("adminmanagevenuecategory/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                    <span>List</span>
                                 </div>
                                 <div class="check-details">
                                    <input type="checkbox" name="permission[]" value="adminmanagevenuecategory/addeditdata" <?php echo in_array("adminmanagevenuecategory/addeditdata", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                    <span>Add</span>
                                 </div>
                                 <div class="check-details">
                                    <input type="checkbox" name="permission[]" value="adminmanagevenuecategory/addeditdata/*" <?php echo in_array("adminmanagevenuecategory/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                    <span>Edit</span>
                                 </div>
                                 <div class="check-details">
                                    <input type="checkbox" name="permission[]" value="adminmanagevenuecategory/deletedata/*" <?php echo in_array("adminmanagevenuecategory/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                    <span>Delete</span>
                                 </div>
                              </div>
                           </div>

                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Event Location<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageeventlocation/index" <?php echo in_array("adminmanageeventlocation/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageeventlocation/addeditdata" <?php echo in_array("adminmanageeventlocation/addeditdata", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Add</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageeventlocation/addeditdata/*" <?php echo in_array("adminmanageeventlocation/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageeventlocation/deletedata/*" <?php echo in_array("adminmanageeventlocation/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>

                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Event<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="eventmanagement/index" <?php echo in_array("eventmanagement/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="eventmanagement/addeditdata" <?php echo in_array("eventmanagement/addeditdata", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Add</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="eventmanagement/addeditdata/*" <?php echo in_array("eventmanagement/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="eventmanagement/deletedata/*" <?php echo in_array("eventmanagement/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>
                        </div>

                        <div class="row mt-2">
                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Festival Management<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagefestivals/index" <?php echo in_array("adminmanagefestivals/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagefestivals/addeditdata" <?php echo in_array("adminmanagefestivals/addeditdata", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Add</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagefestivals/addeditdata/*" <?php echo in_array("adminmanagefestivals/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagefestivals/deletedata/*" <?php echo in_array("adminmanagefestivals/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>
                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Artist Management<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageartist/index" <?php echo in_array("adminmanageartist/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageartist/addeditdata" <?php echo in_array("adminmanageartist/addeditdata", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Add</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageartist/addeditdata/*" <?php echo in_array("adminmanageartist/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageartist/deletedata/*" <?php echo in_array("adminmanageartist/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>

                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Top Home Page Image<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagebanner/index" <?php echo in_array("adminmanagebanner/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagebanner/addeditdata/*" <?php echo in_array("adminmanagebanner/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagebanner/deletedata/*" <?php echo in_array("adminmanagebanner/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>
                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Manage Logo<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagelogo/index" <?php echo in_array("adminmanagelogo/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagelogo/addeditdata" <?php echo in_array("adminmanagelogo/addeditdata", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Add</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagelogo/addeditdata/*" <?php echo in_array("adminmanagelogo/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagelogo/deletedata/*" <?php echo in_array("adminmanagelogo/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>

                        </div>

                        <div class="row mt-2">
                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Home Page Slider Image<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagehomeslider/index" <?php echo in_array("adminmanagehomeslider/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagehomeslider/addeditdata" <?php echo in_array("adminmanagehomeslider/addeditdata", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Add</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagehomeslider/addeditdata/*" <?php echo in_array("adminmanagehomeslider/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagehomeslider/deletedata/*" <?php echo in_array("adminmanagehomeslider/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>

                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Home Page Image<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagehomeimage/index" <?php echo in_array("adminmanagehomeimage/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagehomeimage/addeditdata" <?php echo in_array("adminmanagehomeimage/addeditdata", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Add</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagehomeimage/addeditdata/*" <?php echo in_array("adminmanagehomeimage/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagehomeimage/deletedata/*" <?php echo in_array("adminmanagehomeimage/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>

                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">About Us<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageaboutus/index" <?php echo in_array("adminmanageaboutus/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageaboutus/addeditdata" <?php echo in_array("adminmanageaboutus/addeditdata", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Add</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageaboutus/addeditdata/*" <?php echo in_array("adminmanageaboutus/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageaboutus/deletedata/*" <?php echo in_array("adminmanageaboutus/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>

                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Our Team<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageourteam/index" <?php echo in_array("adminmanageourteam/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageourteam/addeditdata" <?php echo in_array("adminmanageourteam/addeditdata", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Add</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageourteam/addeditdata/*" <?php echo in_array("adminmanageourteam/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageourteam/deletedata/*" <?php echo in_array("adminmanageourteam/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>
                        </div>

                        <div class="row mt-2">
                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">How To Get HH<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagegethh/index" <?php echo in_array("adminmanagegethh/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagegethh/addeditdata" <?php echo in_array("adminmanagegethh/addeditdata", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Add</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagegethh/addeditdata/*" <?php echo in_array("adminmanagegethh/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagegethh/deletedata/*" <?php echo in_array("adminmanagegethh/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>

                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Previous Issue<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagepreviousissues/index" <?php echo in_array("adminmanagepreviousissues/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagepreviousissues/addeditdata/*" <?php echo in_array("adminmanagepreviousissues/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagepreviousissues/deletedata/*" <?php echo in_array("adminmanagepreviousissues/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>

                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Our Partner Slider<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminourpartnerslider/index" <?php echo in_array("adminourpartnerslider/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminourpartnerslider/addeditdata" <?php echo in_array("adminourpartnerslider/addeditdata", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Add</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminourpartnerslider/addeditdata/*" <?php echo in_array("adminourpartnerslider/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminourpartnerslider/deletedata/*" <?php echo in_array("adminourpartnerslider/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>

                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Contact Details<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagecontactdetails/index" <?php echo in_array("adminmanagecontactdetails/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagecontactdetails/addeditdata/*" <?php echo in_array("adminmanagecontactdetails/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagecontactdetails/deletedata/*" <?php echo in_array("adminmanagecontactdetails/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>
                        </div>

                        <div class="row mt-2">
                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Advertisement Section<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageadvertisement/index" <?php echo in_array("adminmanageadvertisement/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageadvertisement/addeditdata/*" <?php echo in_array("adminmanageadvertisement/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageadvertisement/deletedata/*" <?php echo in_array("adminmanageadvertisement/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>
                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Blog<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageblog/index" <?php echo in_array("adminmanageblog/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageblog/addeditdata" <?php echo in_array("adminmanageblog/addeditdata", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Add</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageblog/addeditdata/*" <?php echo in_array("adminmanageblog/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageblog/deletedata/*" <?php echo in_array("adminmanageblog/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>
                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Event Submission<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageuserpost/deletedata/*" <?php echo in_array("adminmanageuserpost/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>

                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Subscribe Email List<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagesubsc/deletedata/*" <?php echo in_array("adminmanagesubsc/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>
                        </div>

                        <div class="row mt-2">
                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Manage Seo<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageseo/index" <?php echo in_array("adminmanageseo/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageseo/addeditdata" <?php echo in_array("adminmanageseo/addeditdata", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Add</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageseo/addeditdata/*" <?php echo in_array("adminmanageseo/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanageseo/deletedata/*" <?php echo in_array("adminmanageseo/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>

                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Manage CMS<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagefooter/index" <?php echo in_array("adminmanagefooter/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagefooter/addeditdata" <?php echo in_array("adminmanagefooter/addeditdata", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Add</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagefooter/addeditdata/*" <?php echo in_array("adminmanagefooter/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagefooter/deletedata/*" <?php echo in_array("adminmanagefooter/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>
                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Map Location<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagelocation/index" <?php echo in_array("adminmanagelocation/index", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagelocation/addeditdata" <?php echo in_array("adminmanagelocation/addeditdata", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Add</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagelocation/addeditdata/*" <?php echo in_array("adminmanagelocation/addeditdata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="adminmanagelocation/deletedata/*" <?php echo in_array("adminmanagelocation/deletedata/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Delete</span>
                              </div>
                           </div>
                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Profile<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="profile" <?php echo in_array("profile", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>List</span>
                              </div>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="editprofile/*" <?php echo in_array("editprofile/*", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Edit</span>
                              </div>
                           </div>
                        </div>

                        <div class="row mt-2">
                           <div class="form-group-inner col-lg-3 col-md-3 col-sm-3 col-xs-12">
                              <label class="all-text">Logout<span class=""></span></label>
                              <div class="check-details">
                                 <input type="checkbox" name="permission[]" value="logout" <?php echo in_array("logout", array_column($PERMISSIONROLE, 'permission')) ? 'checked' : ''; ?>>
                                 <span>Logout</span>
                              </div>
                           </div>
                        </div>

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

<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" /> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> -->


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
            aspectRatio: 3,
            viewMode: 3,
            preview: '.preview'
         });
      }).on('hidden.bs.modal', function() {
         cropper.destroy();
         cropper = null;
      });

      $('#crop').click(function() {
         canvas = cropper.getCroppedCanvas({
            width: 1730,
            height: 577
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
   document.getElementById("uploadBtn").onchange = function() {
      document.getElementById("uploadFile").value = this.value.replace("C:\\fakepath\\", "");
   };
</script>

<script>
   document.addEventListener("DOMContentLoaded", function() {
      var checkAllCheckbox = document.getElementById('checkAll');
      var checkboxes = document.querySelectorAll('input[type="checkbox"][name="permission[]"]');
      checkAllCheckbox.addEventListener('change', function() {
         checkboxes.forEach(function(checkbox) {
            checkbox.checked = checkAllCheckbox.checked;
         });
      });
   });
</script>
