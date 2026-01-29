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
              <li class="breadcrumb-item"><a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>">Location List</a></li>
            
              <li class="breadcrumb-item">
                  <a href="javascript:void(0);">
                      <?= isset($EDITDATA) && !empty($EDITDATA) ? 'Edit' : 'Add' ?> Location Content
                  </a>
              </li>
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
          <h5><?= isset($EDITDATA) && !empty($EDITDATA) ? 'Edit' : 'Add' ?> Content</h5>
          </div>
          <div class="card-body">
            <div class="basic-login-inner">
              <form id="currentPageFormSubadmin" name="currentPageFormSubadmin" class="form-auth-small" method="post" action="" enctype="multipart/form-data" autocomplete="off">
                <?php
                  $errors = session('errors') ?? [];
                ?>
                <?= csrf_field() ?>
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>" style="margin-left: 12px;" class="btn btn-sm btn-primary pull-right">Back</a>
                    <button class="btn btn-primary  btn-sm pull-right">Submit</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>" style="margin-right: 11px;" class="btn btn-danger has-ripple btn-sm pull-right">Cancel</a>

                    <input type="hidden" name="CurrentFieldForUnique" id="CurrentFieldForUnique" value="id" />
                    <input type="hidden" name="CurrentIdForUnique" id="CurrentIdForUnique" value="<?= isset($EDITDATA['id']) ? $EDITDATA['id'] : '' ?>" />
                    <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?= isset($EDITDATA['id']) ? $EDITDATA['id'] : '' ?>" />

                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                  </div>
                </div>

                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?= isset($errors['name']) ? 'error' : '' ?>">
                    <label>Name<span class="required">*</span></label>
                    <input type="text" name="name" id="name" value="<?= old('name', isset($EDITDATA['name']) ? esc($EDITDATA['name']) : '') ?>" class="form-control" placeholder="Name">
                    
                    <?php if (isset($errors['name'])) : ?>
                        <span for="name" generated="true" class="help-inline"><?= esc($errors['name']) ?></span>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12  <?= isset($errors['address']) ? 'error' : '' ?>">
                    <label>Address<span class="required">*</span></label>
                    <input type="text" name="address" id="autocomplete" value="<?= old('address', isset($EDITDATA['address']) ? esc($EDITDATA['address']) : '') ?>" class="form-control" placeholder="Address">
                    
                    <?php if (isset($errors['address'])) : ?>
                        <span for="address" generated="true" class="help-inline"><?= esc($errors['address']); ?></span>
                    <?php endif; ?>
                    <?php if (isset($errors['latitude'])) : ?>
                        <span for="latitude" generated="true" class="help-inline"><?= esc($errors['latitude']); ?></span>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12" id="latitudeArea">
                    <label>Latitude<span class=""></span></label>
                    <input type="text" class="form-control" name="latitude" id="latitude" value="<?= old('latitude', isset($EDITDATA['latitude']) ? esc($EDITDATA['latitude']) : '')  ?>">
                    <?php if (isset($errors['latitude'])) : ?>
                        <span for="latitude" generated="true" class="help-inline"><?= esc($errors['latitude']); ?></span>
                    <?php endif; ?>
                  </div>

                </div>

                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12" id="longitudeArea">
                    <label>Longitude<span class=""></span></label>
                    <input type="text" class="form-control" name="longitude" id="longitude" value="<?= old('longitude', isset($EDITDATA['longitude']) ? esc($EDITDATA['longitude']) : '') ?>">
                    <?php if (isset($errors['longitude'])) : ?>
                        <span for="latitude" generated="true" class="help-inline"><?= esc($errors['longitude']); ?></span>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12" id="zipcodeArea">
                    <label>Zipcode<span class=""></span></label>
                    <input type="text" class="form-control" name="zipcode" id="zipcode" value="<?= old('zipcode', isset($EDITDATA['zipcode']) ? esc($EDITDATA['zipcode']) : '') ?>">
                  </div>
                </div>
               
                <div class="row">
                  <div class="form-group-inner col-lg-4 col-md-4 col-sm-6 col-xs-12 <?= isset($errors['info']) ? 'error' : '' ?>">
                    <div class="animation">
                      <label>Info<span class="required">*</span></label>
                      <textarea name="info" id="info" rows="4" cols="80"><?=old('info', isset($EDITDATA['info']) ? esc($EDITDATA['info']) : '') ?></textarea>
                      <?php if (isset($errors['info'])) : ?>
                        <span for="info" generated="true" class="help-inline"><?= esc($errors['info']); ?></span>
                      <?php endif; ?>
                    </div>

                  </div>
                </div>

                <div class="row">
                  <div class="login-btn-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="inline-remember-me mt-4">
                      <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
                      <span class="tools pull-right">Note:- <strong><span style="color:#FF0000;">*</span> Indicates Required Fields</strong></span>
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

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyDPyWbxCqalUPsqs3f2bY1w_FDh5rAAXEE&libraries=places&callback=initAutocomplete"></script>

<script>
  $(document).ready(function() {
    $("#latitudeArea").addClass("d-none");
    $("#longitudeArea").addClass("d-none");
    initialize();
  });

  function initialize() {
    var input = document.getElementById('autocomplete');
    var autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.addListener('place_changed', function() {
      var place = autocomplete.getPlace();

      $('#latitude').val(place.geometry.location.lat());
      $('#longitude').val(place.geometry.location.lng());
    
      $("#latitudeArea").removeClass("d-none");
      $("#longitudeArea").removeClass("d-none");
     
    });
  }
</script>

<script>
  $(document).ready(function() {
    $("#latitudeArea").addClass("d-none");
    $("#longitudeArea").addClass("d-none");
    $("#zipcodeArea").addClass("d-none");
    initialize();
  });

  function initialize() {
    var input = document.getElementById('autocomplete');
    var autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.addListener('place_changed', function() {
      var place = autocomplete.getPlace();
      console.log("Place:", place);

      if (!place.geometry || !place.geometry.location) {
        console.error("Place or place geometry is missing:", place);
        return;
      }

      $('#latitude').val(place.geometry.location.lat());
      $('#longitude').val(place.geometry.location.lng());

      // Fetch address details using reverse geocoding
      var geocoder = new google.maps.Geocoder();
      geocoder.geocode({ location: place.geometry.location }, function(results, status) {
        if (status === 'OK') {
          // Iterate through address components to find postal code
          for (var i = 0; i < results[0].address_components.length; i++) {
            var types = results[0].address_components[i].types;
            if (types.includes('postal_code')) {
              $('#zipcode').val(results[0].address_components[i].long_name);
              console.log("Zipcode:", results[0].address_components[i].long_name);
              $("#zipcodeArea").removeClass("d-none");
              break;
            }
          }
        } else {
          console.error('Geocoder failed due to: ' + status);
        }
      });

      $("#latitudeArea").removeClass("d-none");
      $("#longitudeArea").removeClass("d-none");
      
    });
  }
</script>

<!-- <script>
  $(document).ready(function() {
    $("#latitudeArea").addClass("d-none");
    $("#longitudeArea").addClass("d-none");
    $("#zipcodeArea").addClass("d-none");

    // Keep original values in case address is not updated
    var originalLatitude = $("#latitude").val();
    var originalLongitude = $("#longitude").val();
    var originalZipcode = $("#zipcode").val();

    initialize(originalLatitude, originalLongitude, originalZipcode);
  });

  function initialize(originalLatitude, originalLongitude, originalZipcode) {
    var input = document.getElementById('autocomplete');
    var autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.addListener('place_changed', function() {
      var place = autocomplete.getPlace();

      if (!place.geometry || !place.geometry.location) {
        console.error("Place or place geometry is missing:", place);
        return;
      }

      var newLatitude = place.geometry.location.lat();
      var newLongitude = place.geometry.location.lng();

      // Only update if address is changed, else retain old values
      if ($("#autocomplete").val() !== "<?= isset($EDITDATA['address']) ? $EDITDATA['address'] : '' ?>") {
        $('#latitude').val(newLatitude);
        $('#longitude').val(newLongitude);
      } else {
        $('#latitude').val(originalLatitude);
        $('#longitude').val(originalLongitude);
      }

      // Reverse geocoding to fetch Zipcode
      var geocoder = new google.maps.Geocoder();
      geocoder.geocode({ location: place.geometry.location }, function(results, status) {
        if (status === 'OK') {
          for (var i = 0; i < results[0].address_components.length; i++) {
            var types = results[0].address_components[i].types;
            if (types.includes('postal_code')) {
              var newZipcode = results[0].address_components[i].long_name;
              $('#zipcode').val(newZipcode);
              console.log("Zipcode:", newZipcode);
              $("#zipcodeArea").removeClass("d-none");
              break;
            }
          }
        } else {
          console.error('Geocoder failed due to: ' + status);
        }
      });

      $("#latitudeArea").removeClass("d-none");
      $("#longitudeArea").removeClass("d-none");
    });

    // If no change in address, keep old values
    if (!$("#latitude").val()) $("#latitude").val(originalLatitude);
    if (!$("#longitude").val()) $("#longitude").val(originalLongitude);
    if (!$("#zipcode").val()) $("#zipcode").val(originalZipcode);
  }
</script> -->



