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
              <li class="breadcrumb-item"><a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>"> Artist List</a></li>
              <li class="breadcrumb-item"><a href="javascript:void(0);"><?= $EDITDATA ? 'Edit' : 'Add' ?> Artist Content</a></li>
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
                <a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>" style="margin-left: 12px;" class="btn btn-sm btn-primary pull-right">Back</a>
                <button class="btn btn-primary  btn-sm pull-right">Submit</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>" style="margin-right: 11px;" class="btn btn-danger has-ripple btn-sm pull-right">Cancel</a>

                <input type="hidden" name="CurrentFieldForUnique" id="CurrentFieldForUnique" value="id" />
                <input type="hidden" name="CurrentIdForUnique" id="CurrentIdForUnique" value="<?= $EDITDATA['id'] ?>" />
                <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?= $EDITDATA['id'] ?>" />
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                <div class="row">
                <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('artist_name')) : ?>error<?php endif; ?>">
                  <label>Name<span class="required"></span></label>
                  <input type="text" name="artist_name" id="artist_name" value="<?php if (old('artist_name')) : echo old('artist_name'); else : echo stripslashes($EDITDATA['artist_name']); endif; ?>" class="form-control required" placeholder="Name">
                  <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('artist_name')) : ?>
                    <span for="artist_name" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('artist_name'); ?></span>
                  <?php endif; ?>
                </div>
                </div>
                
                <div class="row">
                  <div class="form-group-inner col-lg-4 col-md-4 col-sm-6 col-xs-12" <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('reserve_seat_link')) : ?>error<?php endif; ?>>
                    <div class="animation">
                      <label>Thumb Image<span class="required">*</span></label>
                      <input value="<?php if (old('artist_image')) : echo old('artist_image');
                                    else : echo stripslashes($EDITDATA['artist_image']);
                                    endif; ?>" id="uploadFile" name="artist_image" class="f-input" readonly />
                      <p style="font-family:italic; color:red;">[Image Size : 255 x 318 px in jpg/png/gif/jpeg/webp]</p>
                    </div>
                  </div>
                  <div class="col-lg-4 px-0">
                    <div class="fileUpload btn btn--browse">
                      <span>Browse</span>
                      <input id="uploadBtn" type="file" name="artist_image" class="upload" />
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12" <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('artist_url')) : ?>error<?php endif; ?>>
                    <label>Thumb Image Url<span class=""></span></label>
                    <input type="text" name="artist_url" id="artist_url" value="<?php if (old('artist_url')) : echo old('url');
                                                                                else : echo stripslashes($EDITDATA['artist_url']);
                                                                                endif; ?>" class="form-control" placeholder="Thumb Image Url ">
                    <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('artist_url')) : ?>
                      <span for="url" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('artist_url'); ?></span>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group-inner col-lg-4 col-md-4 col-sm-6 col-xs-12" <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('cover_image')) : ?>error<?php endif; ?>>
                    <div class="animation">
                      <label>Cover Image<span class="required">*</span></label>
                      <input value="<?php if (old('cover_image')) : echo old('cover_image');
                                    else : echo stripslashes($EDITDATA['cover_image']);
                                    endif; ?>" id="uploadFile2" name="cover_image" class="f-input" readonly />
                      <p style="font-family:italic; color:red;">[Image Size : 255 x 318 px in jpg/png/gif/jpeg/webp]</p>
                    </div>
                  </div>
                  <div class="col-lg-4 px-0">
                    <div class="fileUpload btn btn--browse">
                      <span>Browse</span>
                      <input id="uploadBtn2" type="file" name="cover_image" class="upload" />
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12" <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('cover_url')) : ?>error<?php endif; ?>>
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
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12" <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('buy_now_link')) : ?>error<?php endif; ?>>
                    <label>Buy link<span class="required">*</span></label>
                    <input type="text" name="buy_now_link" id="buy_now_link" value="<?php if (old('buy_now_link')) : echo old('buy_now_link');
                                                                                    else : echo stripslashes($EDITDATA['buy_now_link']);
                                                                                    endif; ?>" class="form-control " placeholder="Buy Now Link">
                    <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('buy_now_link')) : ?>
                      <span for="buy_now_link" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('buy_now_link'); ?></span>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12" <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('website_link')) : ?>error<?php endif; ?>>
                    <label>Website Link<span class="required">*</span></label>
                    <input type="text" name="website_link" id="website_link" value="<?php if (old('website_link')) : echo old('website_link');
                                                                                              else : echo stripslashes($EDITDATA['website_link']);
                                                                                              endif; ?>" class="form-control" placeholder="Buy Link">
                    <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('website_link')) : ?>
                      <span for="website_link" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('website_link'); ?></span>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group-inner col-lg-4 col-md-4 col-sm-6 col-xs-12" <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('artist_bio')) : ?>error<?php endif; ?>>
                    <div class="animation">
                      <label>Artist Bio<span class="required">*</span></label>
                      <textarea name="artist_bio" id="artist_bio" rows="4" cols="70"><?php if (old('artist_bio')) : echo old('artist_bio');
                                                                                      else : echo stripslashes($EDITDATA['artist_bio']);
                                                                                      endif; ?></textarea>
                      <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('artist_bio')) : ?>
                        <span for="artist_bio" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('artist_bio'); ?></span>
                      <?php endif; ?>
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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB9bu_dYx-Yzl6mwUxsKYSSq_p1yHJO6H8&libraries=places" async defer charset="utf-8"></script>

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
<!--<script>
     var today = new Date();
var dd = today.getDate();
var mm = today.getMonth() + 1;
var yyyy = today.getFullYear();
 if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 

today = yyyy+'-'+mm+'-'+dd;
document.getElementById("start_date").setAttribute("min", today);


var today = new Date();
var dd = today.getDate();
var mm = today.getMonth() + 1;
var yyyy = today.getFullYear();
 if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 

today = yyyy+'-'+mm+'-'+dd;
document.getElementById("end_date").setAttribute("min", today);

function show1(){
  document.getElementById('end_time').style.display ='none';
}
function show2(){
  document.getElementById('end_time').style.display = 'inline-block';
}
function show4(){
  document.getElementById('repeat').style.display ='none';
  document.getElementById('frequency').style.display ='none';
}
function show3(){
  document.getElementById('repeat').style.display = 'inline-block';
  document.getElementById('frequency').style.display = 'inline-block';
}
</script>-->
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
      success: function(data) {
        ///alert(data.name);
        // console.log(data);
        $("#location_name").val(data.location_name);
        $("#location_address").val(data.location_address);
        $("#longitude").val(data.longitude);
        $("#latitude").val(data.latitude);
        $("#phone_number").val(data.phone_number);
        $("#website").val(data.website);
        $("#venue_id").val(data.venue_id);
        $("#jazz_types_id").val(data.jazz_types_id);

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