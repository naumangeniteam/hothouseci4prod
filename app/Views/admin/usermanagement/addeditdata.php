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

   .all-text {
      font-weight: bold;
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
                     <?php /* ?>
                     <h5 class="m-b-10">Welcome <?=sessionData('ILCADM_ADMIN_FIRST_NAME')?></h5>
                     <?php */ ?>
                  </div>
                  <ul class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>"> User List</a></li>
                     <li class="breadcrumb-item"><a href="javascript:void(0);"><?= $EDITDATA ? 'Edit' : 'Add' ?> User</a></li>
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
                  <h5><?= $EDITDATA ? 'Edit' : 'Add' ?> User</h5>
               </div>
               <div class="card-body">
                  <div class="basic-login-inner">
                     <form id="currentPageFormSubadmin" name="currentPageFormSubadmin" class="form-auth-small" method="post" action="" enctype="multipart/form-data">
                        <div class="row">
                           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>" style="margin-left: 12px;" class="btn btn-sm btn-primary pull-right">Back</a>
                              <button class="btn btn-primary  btn-sm pull-right">Submit</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>" style="margin-right: 11px;" class="btn btn-danger has-ripple btn-sm pull-right">Cancel</a>
                              <input type="hidden" name="CurrentFieldForUnique" id="CurrentFieldForUnique" value="admin_id" />
                              <input type="hidden" name="CurrentIdForUnique" id="CurrentIdForUnique" value="<?= $EDITDATA['admin_id'] ?>" />
                              <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?= $EDITDATA['admin_id'] ?>" />
                              <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                           </div>
                        </div>

                        <div class="row">
                           <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('admin_first_name')) : ?>error<?php endif; ?>">
                              <label>First Name<span class="required">*</span></label>
                              <input type="text" name="admin_first_name" id="admin_first_name" value="<?php if (old('admin_first_name')) : echo old('admin_first_name');
                                                                                                      else : echo stripslashes($EDITDATA['admin_first_name']);
                                                                                                      endif; ?>" class="form-control required" placeholder="First Name">
                              <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('admin_first_name')) : ?>
                                 <span for="admin_first_name" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('admin_first_name'); ?></span>
                              <?php endif; ?>
                           </div>
                        </div>
                        <div class="row">
                           <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('admin_middle_name')) : ?>error<?php endif; ?>">
                              <label>Middle Name<span class="required"></span></label>
                              <input type="text" name="admin_middle_name" id="admin_middle_name" value="<?php if (old('admin_middle_name')) : echo old('admin_middle_name');
                                                                                                         else : echo stripslashes($EDITDATA['admin_middle_name']);
                                                                                                         endif; ?>" class="form-control required" placeholder="Middle Name">
                              <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('admin_middle_name')) : ?>
                                 <span for="admin_middle_name" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('admin_middle_name'); ?></span>
                              <?php endif; ?>
                             
                           </div>
                        </div>

                        <div class="row">
                           <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('admin_last_name')) : ?>error<?php endif; ?>">
                              <label>Last Name<span class="required">*</span></label>
                              <input type="text" name="admin_last_name" id="admin_last_name" value="<?php if (old('admin_last_name')) : echo old('admin_last_name');
                                                                                                      else : echo stripslashes($EDITDATA['admin_last_name']);
                                                                                                      endif; ?>" class="form-control required" placeholder="Last Name">
                              <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('admin_last_name')) : ?>
                                 <span for="admin_last_name" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('admin_last_name'); ?></span>
                              <?php endif; ?>

                           </div>
                        </div>

                        <div class="row">
                           <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('admin_email')) : ?>error<?php endif; ?>">
                              <label>Email<span class="required">*</span></label>
                              <input type="text" name="admin_email" id="admin_email" value="<?php if (old('admin_email')) : echo old('admin_email');
                                                                                             else : echo stripslashes($EDITDATA['admin_email']);
                                                                                             endif; ?>" class="form-control required" placeholder="Email">
                              <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('admin_email')) : ?>
                                 <span for="admin_email" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('admin_email'); ?></span>
                              <?php endif; ?>
                           </div>
                        </div>

                        <div class="row">
                           <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('admin_password')) : ?>error<?php endif; ?>">
                              <label>Password<span class="required"></span></label>
                              <input type="password" name="admin_password" id="admin_password" value="" class="form-control required" placeholder="Password">
                              <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('admin_password')) : ?>
                                 <span for="admin_password" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('admin_password'); ?></span>
                              <?php endif; ?>
                           </div>
                        </div>

                        <div class="row">
                           <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('content1')) : ?>error<?php endif; ?>">
                              <label>Select Role<span class=""></span></label>
                              <select name="role_id" id="role_id" class="form-control more_info_permission_btn">
                                 <option value="">Select</option>
                                 <?php if (!empty($roles)) : ?>
                                    <?php foreach ($roles as $role) : ?>
                                       <option <?php if ($EDITDATA['role_id'] == $role['id']) : ?>selected<?php endif; ?> value="<?php echo $role['id']; ?>"><?php echo $role['role_name']; ?></option>
                                    <?php endforeach; ?>
                                 <?php endif; ?>
                              </select>
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
   function checkboxlimit() {
      var totaldata = $("#is_home_count").val();
      var limit = 8;
      if (totaldata >= limit) {
         alert("You can only select a maximum of " + limit + " blog in homepage")
         $('.is_homecl').prop('checked', false);
      }
   }
</script>


<!-- <script>
    $(document).ready(function() {
        $('#role_id').change(function() {
            var roleId = $(this).val();
      
            if (roleId !== '') {
                $.ajax({
                    type: 'GET',
                    url: '<?php echo base_url('hhjsitemgmt/adminmanageuser/get_permission_data'); ?>', 
                    data: {role_id: roleId},
                    success: function(response) {
                     console.log(response)
                        $('#permisssion_content').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            } else {
                $('#permisssion_content').html(''); 
            }
        });
    });
</script> -->