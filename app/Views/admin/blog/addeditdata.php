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
                     <li class="breadcrumb-item"><a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>"> Blog List</a></li>
                     <li class="breadcrumb-item"><a href="javascript:void(0);"><?= $EDITDATA ? 'Edit' : 'Add' ?> Blog Content</a></li>
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
                     <form id="currentPageFormSubadmin" name="currentPageFormSubadmin" class="form-auth-small" method="post" action="" enctype="multipart/form-data">
                        <?= csrf_field() ?>
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
                           <div class="form-group-inner col-lg-6 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('page_title')) : ?>error<?php endif; ?>">
                              <label>Page Title<span class="required">*</span></label>
                              <input type="text" name="page_title" id="page_title" value="<?php if (old('page_title')) : echo old('page_title');
                                                                                          else : echo stripslashes($EDITDATA['page_title']);
                                                                                          endif; ?>" class="form-control required" placeholder="Blog Title">
                              <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('page_title')) : ?>
                                 <span for="page_title" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('page_title'); ?></span>
                              <?php endif; ?>
                           </div>
                           <div class="form-group-inner col-lg-6 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('position')) : ?>error<?php endif; ?>">
                              <label>Blog Position <span class="required">*</span></label>
                              <input type="text" name="position" id="position" value="<?php if (old('position')) : echo old('position');
                                                                                       else : echo stripslashes($EDITDATA['position']);
                                                                                       endif; ?>" class="form-control required" placeholder="Blog Position">
                              <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('position')) : ?>
                                 <span for="position" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('position'); ?></span>
                              <?php endif; ?>
                           </div>
                        </div>
                        <div class="row">
                           <div class="form-group-inner col-lg-6 col-md-8 col-sm-12 col-xs-12">
                              <label>Show Home Page Slider<span class="required">*</span></label>
                              <br><input style="margin-left: 23px;" type="checkbox" <?php if (old('slider_show') == 'yes' || $EDITDATA['slider_show'] == 'yes') echo 'checked'; ?> name="home_slider_yes" id="checkboxYes" value="yes" class="required">
                              <label>Yes</label>
                              <input type="checkbox" style="margin-left: 38px;" <?php if (old('slider_show') == 'no' || $EDITDATA['slider_show'] == 'no') echo 'checked'; ?> name="home_slider_yes" id="checkboxNo" value="no" class="required">
                              <label>No</label>
                              <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('home_slider_yes')) : ?>
                                 <span for="home_slider_yes" generated="true" class="help-inline"><p><?php echo session()->getFlashdata('validation')->getError('home_slider_yes'); ?></p></span>
                              <?php endif; ?>
                           </div>
                        </div>
                        <div class="row" style="">
                           <div class="form-group-inner col-lg-4 col-md-4 col-sm-12 col-xs-12 <?php echo ($EDITDATA['slider_show'] == 'yes') ? 'show' : 'hide'; ?> <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('slider_position')) : ?>error<?php endif; ?>" id="show_position">
                              <label>Slider Position<span class="required">*</span></label>
                              <input type="text" name="slider_position" id="slider_position" value="<?php if (old('slider_position')) : echo old('slider_position');
                                                                                                      else : echo stripslashes($EDITDATA['slider_position']);
                                                                                                      endif; ?>" class="form-control required" placeholder="Slider Position">
                              <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('slider_position')) : ?>
                                 <span for="slider_position" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('slider_position'); ?></span>
                              <?php endif; ?>
                           </div>
                           <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php echo ($EDITDATA['slider_show'] == 'yes') ? 'show' : 'hide'; ?> <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('url')) : ?>error<?php endif; ?>" id="show_url">
                              <label>Slider Url<span class="required">*</span></label>
                              <input type="text" name="url" id="url" value="<?php if (old('url')) : echo old('url');
                                                                              else : echo stripslashes($EDITDATA['url']);
                                                                              endif; ?>" class="form-control required" placeholder="Url">
                              <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('url')) : ?>
                                 <span for="url" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('url'); ?></span>
                              <?php endif; ?>
                           </div>
                        </div>
                        <div class="row" style="">
                           <div class="form-group-inner col-lg-4 col-md-3 col-sm-3 col-xs-12 <?php echo ($EDITDATA['slider_show'] == 'yes') ? 'show' : 'hide'; ?> <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('image')) : ?>error<?php endif; ?>" id="show_image">
                              <div class="animation">
                                 <label>Slider Image<span class="required">*</span></label>
                                 <input value="<?php if (old('image')) : echo old('image');
                                                else : echo stripslashes($EDITDATA['image']);
                                                endif; ?>" id="uploadFile" name="image" class="f-input" readonly />
                                 <p style="font-family:italic; color:red;">[Image Size : 255 x 318 px in jpg/png/gif/jpeg/webp]</p>
                              </div>
                           </div>
                           <div class="col-lg-4 px-0 <?php echo ($EDITDATA['slider_show'] == 'yes') ? 'show' : 'hide'; ?>" id="show_image_b">
                              <div class="fileUpload btn btn--browse">
                                 <span>Browse</span>
                                 <input id="uploadBtn" type="file" name="image" class="upload" />
                              </div>
                           </div>
                        </div>
                        <div class="row" style="">
                           <div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php echo ($EDITDATA['slider_show'] == 'yes') ? 'show' : 'hide'; ?> <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('slider_content')) : ?>error<?php endif; ?>" id="show_content">
                              <label>slider Content<span class="required">*</span></label>
                              <textarea name="slider_content" id="slider_content" class="form-control" placeholder="slider_content"><?php if (old('slider_content')) : echo old('slider_content');
                                                                                                                                    else : echo stripslashes($EDITDATA['slider_content']);
                                                                                                                                    endif; ?></textarea>
                              <script>
                                 $(function() {
                                    create_editor_for_textarea('slider_content');
                                 });
                              </script>
                              <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('slider_content')) : ?>
                                 <span for="slider_content" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('slider_content'); ?></span>
                              <?php endif; ?>
                           </div>
                        </div>
                        <div class="row" id="show">
                           <div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('home_content')) : ?>error<?php endif; ?>" id="show">
                              <label>Small Content<span class="required">*</span></label>
                              <textarea name="home_content" id="home_content" class="form-control" placeholder="home_content"><?php if (old('home_content')) : echo old('home_content');
                                                                                                                              else : echo stripslashes($EDITDATA['home_content']);
                                                                                                                              endif; ?></textarea>
                              <script>
                                 $(function() {
                                    create_editor_for_textarea('home_content');
                                 });
                              </script>
                              <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('home_content')) : ?>
                                 <span for="home_content" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('home_content'); ?></span>
                              <?php endif; ?>
                           </div>
                        </div>
                        <div class="row" id="show">
                           <div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('content')) : ?>error<?php endif; ?>" id="show">
                              <label>Detailed Content<span class="required">*</span></label>
                              <textarea name="content" id="content" class="form-control" placeholder="content"><?php if (old('content')) : echo old('content');
                                                                                                               else : echo stripslashes($EDITDATA['content']);
                                                                                                               endif; ?></textarea>
                              <script>
                                 $(function() {
                                    create_editor_for_textarea('content');
                                 });
                              </script>
                              <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('content')) : ?>
                                 <span for="content" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('content'); ?></span>
                              <?php endif; ?>
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
<script>
   const checkboxYes = document.getElementById('checkboxYes');
   const checkboxNo = document.getElementById('checkboxNo');
   const myDiv = document.getElementById('show_position');
   const myDiv5 = document.getElementById('show_url');
   const myDiv2 = document.getElementById('show_content');
   const myDiv3 = document.getElementById('show_image');
   const myDiv4 = document.getElementById('show_image_b');

   checkboxYes.addEventListener('change', handleCheckboxYesChange);

   function handleCheckboxYesChange() {
      if (checkboxYes.checked) {
         checkboxNo.checked = false;
         myDiv.style.display = 'block';
         myDiv2.style.display = 'block';
         myDiv3.style.display = 'block';
         myDiv4.style.display = 'block';
         myDiv5.style.display = 'block';
      } else {
         myDiv.style.display = 'none';
         myDiv2.style.display = 'none';
         myDiv3.style.display = 'none';
         myDiv4.style.display = 'none';
         myDiv5.style.display = 'none';
      }
   }

   checkboxNo.addEventListener('change', handleCheckboxNoChange);

   function handleCheckboxNoChange() {
      if (checkboxNo.checked) {
         checkboxYes.checked = false;
         myDiv.style.display = 'none';
         myDiv2.style.display = 'none';
         myDiv3.style.display = 'none';
      }
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