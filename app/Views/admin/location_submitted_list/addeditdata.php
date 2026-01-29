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
              <li class="breadcrumb-item"><a href="<?php echo correctLink('webinarILCADMData', getCurrentControllerPath('index')); ?>"> Location Submitted List</a></li>
              <li class="breadcrumb-item"><a href="javascript:void(0);"><?= $EDITDATA ? 'Edit' : 'Add' ?> Location Submitted List</a></li>
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
                    <input type="hidden" name="CurrentIdForUnique" id="CurrentIdForUnique" value="<?= $EDITDATA['id'] ?>" />
                    <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?= $EDITDATA['id'] ?>" />
                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                  </div>
                </div>

                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('name')) : ?>error<?php endif; ?>">
                    <label>Name<span class="required">*</span></label>
                    <input type="text" autocomplete="off" name="location_name" id="location_name" value="<?php if (old('location_name')) : echo old('location_name');
                                                                                                          else : echo stripslashes($EDITDATA['location_name']);
                                                                                                          endif; ?>" class="form-control required" placeholder="Location Name">
                    <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('location_name')) : ?>
                      <span for="location_name" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('location_name'); ?></span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('venue_title')) : ?>error<?php endif; ?>">
                    <label>Location Address<span class="required">*</span></label>
                    <input type="text" autocomplete="off" name="location_address" id="address" value="<?php if (old('location_address')) : echo old('location_address');
                                                                                                      else : echo stripslashes($EDITDATA['location_address']);
                                                                                                      endif; ?>" class="form-control required" placeholder="Location Address">
                    <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('location_address')) : ?>
                      <span for="location_address" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('location_address'); ?></span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-12 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('longitude')) : ?>error<?php endif; ?>">
                    <label>Longitude<span class="required">*</span></label>
                    <input type="text" name="longitude" id="address-longitude" value="<?php if (old('longitude')) : echo old('longitude');
                                                                                      else : echo stripslashes($EDITDATA['longitude']);
                                                                                      endif; ?>" class="form-control required" placeholder="Longitude" readonly>
                    <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('longitude')) : ?>
                      <span for="longitude" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('longitude'); ?></span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('latitude')) : ?>error<?php endif; ?>">
                    <label>Latitude<span class="required">*</span></label>
                    <input type="text" name="latitude" id="address-latitude" value="<?php if (old('latitude')) : echo old('latitude');
                                                                                    else : echo stripslashes($EDITDATA['latitude']);
                                                                                    endif; ?>" class="form-control required" placeholder="Latitude" readonly>
                    <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('latitude')) : ?>
                      <span for="latitude" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('latitude'); ?></span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('county')) : ?>error<?php endif; ?>">
                    <label>County<span class="required">*</span></label>
                    <input type="text" name="county" id="address-county" value="<?php if (old('county')) : echo old('county');
                                                                                else : echo stripslashes($EDITDATA['county']);
                                                                                endif; ?>" class="form-control required" placeholder="county">
                    <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('county')) : ?>
                      <span for="county" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('county'); ?></span>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('state')) : ?>error<?php endif; ?>">
                    <label>State<span class="required">*</span></label>
                    <select name="state" id="state" class="form-control required">
                      <option value="">Select a State</option>
                      <?php foreach ($states as $state) : ?>
                        <option value="<?= htmlspecialchars($state->name); ?>" <?php if (old('state') == $state->name || stripslashes($EDITDATA['state']) == $state->name) echo 'selected'; ?>>
                          <?= htmlspecialchars($state->name); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('state')) : ?>
                      <span for="state" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('state'); ?></span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('website')) : ?>error<?php endif; ?>">
                    <label>Website</label>
                    <input type="text" name="website" id="website" value="<?php if (old('website')) : echo old('website');
                                                                          else : echo stripslashes($EDITDATA['website']);
                                                                          endif; ?>" class="form-control required" placeholder="Website">
                    <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('website')) : ?>
                      <span for="website" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('website'); ?></span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('phone_number')) : ?>error<?php endif; ?>">
                    <label>Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" maxlength="12" minlength="12" value="<?php if (old('phone_number')) : echo old('phone_number');
                                                                                                                  else : echo stripslashes($EDITDATA['phone_number']);
                                                                                                                  endif; ?>" class="form-control required" placeholder="Phone Number">
                    <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('phone_number')) : ?>
                      <span for="phone_number" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('phone_number'); ?></span>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('phone_number')) : ?>error<?php endif; ?>">
                    <label for="mailtype" class="col-sm-3 control-label">Select Venue<span class="required">*</span></label>
                    <select class="form-control" name="venue_id">
                      <option value="">Select Venue</option>
                      <?php
                      if (!empty($venues)) :
                        foreach ($venues as $venues) : ?>
                          <option <?php if ($EDITDATA['venue_id'] == $venues->id) {
                                    echo "selected";
                                  } ?> value="<?= $venues->id ?>"><?= $venues->venue_title; ?></option>
                      <?php endforeach;
                      endif; ?>
                    </select>
                    <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('venue_id')) : ?>
                      <span for="venue_id" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('venue_id'); ?></span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group-inner col-lg-8 col-md-8 col-sm-12 col-xs-12 <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('event_location_type_id')) : ?>error<?php endif; ?>">
                    <label for="event_location_type_id" class="col-sm-3 control-label">Select Event Location Type<span class="required">*</span></label>
                    <select class="form-control" name="event_location_type_id">
                      <option value="">Select Event Location Type</option>
                      <?php
                      if (!empty($event_location_types)) :
                        foreach ($event_location_types as $event_location_type) : ?>
                          <option <?php if (isset($EDITDATA['event_location_type_id']) && $EDITDATA['event_location_type_id'] == $event_location_type->id) {
                                    echo "selected";
                                  } ?> value="<?= $event_location_type->id ?>"><?= $event_location_type->name; ?></option>
                      <?php endforeach;
                      endif; ?>
                    </select>
                    <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('event_location_type_id')) : ?>
                      <span for="event_location_type_id" generated="true" class="help-inline"><?php echo session()->getFlashdata('validation')->getError('event_location_type_id'); ?></span>
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
  // function geocodeAddress() {
  //   var geocoder = new google.maps.Geocoder();
  //   var address = document.getElementById('address').value;

  //   geocoder.geocode({
  //     'address': address
  //   }, function(results, status) {
  //     if (status === google.maps.GeocoderStatus.OK) {
  //       var latitude = results[0].geometry.location.lat();
  //       var longitude = results[0].geometry.location.lng();
  //       // var county = results[0].geometry.location.lat();
  //       // var state = results[0].geometry.location.lng();

  //       // Assign the latitude and longitude values to the respective form fields
  //       document.getElementById('address-latitude').value = latitude;
  //       document.getElementById('address-longitude').value = longitude;

  //       // Initialize variables for county and state
  //       var county = '';
  //       var state = '';

  //       // Loop through address components to find county and state
  //       for (var i = 0; i < results[0].address_components.length; i++) {
  //         var component = results[0].address_components[i];

  //         // Check for county
  //         if (component.types.includes('administrative_area_level_2')) {
  //           county = component.long_name;
  //         }

  //         // Check for state
  //         if (component.types.includes('administrative_area_level_1')) {
  //           state = component.long_name;
  //         }
  //       }

  //       // Assign county and state values to respective form fields (if needed)
  //       document.getElementById('address-county').value = county;
  //       document.getElementById('address-state').value = state;

  //       // Optional: Display the results (for debugging or user feedback)
  //       console.log('County: ' + county);
  //       console.log('State: ' + state);
  //     } else {
  //       alert('Geocode was not successful for the following reason: ' + status);
  //     }
  //   });
  // }

  function geocodeAddress() {
    var geocoder = new google.maps.Geocoder();
    var address = document.getElementById('address').value;

    geocoder.geocode({
      'address': address
    }, function(results, status) {
      if (status === google.maps.GeocoderStatus.OK) {
        var latitude = results[0].geometry.location.lat();
        var longitude = results[0].geometry.location.lng();

        // Check for the latitude and longitude input elements
        var latitudeField = document.getElementById('address-latitude');
        if (latitudeField) {
          latitudeField.value = latitude;
        }

        var longitudeField = document.getElementById('address-longitude');
        if (longitudeField) {
          longitudeField.value = longitude;
        }

        var county = '';
        var state = '';

        for (var i = 0; i < results[0].address_components.length; i++) {
          var component = results[0].address_components[i];

          // Check for county
          if (component.types.includes('administrative_area_level_2')) {
            county = component.long_name;
          }

          // Check for state
          if (component.types.includes('administrative_area_level_1')) {
            state = component.long_name;
          }
        }

        // Assign county and state values if the fields exist
        var countyField = document.getElementById('address-county');
        if (countyField) {
          countyField.value = county;
        }

        var stateField = document.getElementById('address-state');
        if (stateField) {
          stateField.value = state;
        }

        // Set the state in the dropdown
        setSelectedState(state);

        console.log('County: ' + county);
        console.log('State: ' + state);
      } else {
        alert('Geocode was not successful for the following reason: ' + status);
      }
    });
  }

  // Function to set the selected state in the dropdown
  function setSelectedState(state) {
    var stateDropdown = document.getElementById('state');
    if (stateDropdown) {
      for (var i = 0; i < stateDropdown.options.length; i++) {
        if (stateDropdown.options[i].value === state) {
          stateDropdown.selectedIndex = i;
          break;
        }
      }
    }
  }

  // Add an event listener to trigger geocoding when the address input changes
  document.getElementById('address').addEventListener('change', geocodeAddress);
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