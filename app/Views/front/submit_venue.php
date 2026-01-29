<style>
    .single-banner-caption.about-caption {
        position: absolute;
        width: 550px;
        left: 57%;
        top: 30%;
        text-align: center;
    }

    .single-banner-caption h1 {
        color: red;
        font-size: 54px;
    }

    .single-banner-caption p {
        color: white !important;
        font-size: 24px;
    }

    .single-banner-img {
        position: relative;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
    }

    span,
    .help-inline,
    p {
        color: red !important;
    }

    .error {
        color: red !important;
    }

    p {

        color: red;
    }

    div#partner-carousel .item img.img-fluid {
        width: 255px !important;
        height: 184px;
    }

    .submit_btn {
        background: #000000;
        color: #ffffff !important;
        padding: 10px 30px;
        width: fit-content;
        border-radius: 7px;
        font-weight: 500;
        font-size: 16px;
        text-decoration: none;
        cursor: pointer;
        border: none;
    }
</style>
<?php
/*echo '<pre>';
print_r($rcontent); die();*/
$siteKey = getenv('RECAPTCHA_SITE_KEY');
$secretKey = getenv('RECAPTCHA_SECRET_KEY');
// $session = session();
?>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-J3KMGPG70L"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>


<?php if ($banner <> ''): ?>
    <div class="signle-banner">
        <div class="full-container">
            <div class="row">
                <?php foreach ($banner as $key => $item): ?>
                    <div class="col-lg-12 col-12">
                        <div class="single-banner-img">
                            <img src="<?= base_url().'assets/front/img/banner/'. $item['image'] ?>" class="img-fluid" alt="About us">
                        </div>
                        <div class="single-banner-caption about-caption">
                            <div class="container">
                                <h1><?= $item['title'] ?></h1>
                                <p><?= $item['content'] ?></p>
                            </div>
                        </div>
                    </div>
            <?php endforeach;
            endif; ?>
            </div>
        </div>
    </div>
    <?= view('layouts/front/home-tabs') ?>
    <div class="home-history submit-event-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-9 col-12">
                    <div class="submit-event-block">
                        <h4>Submission Guidelines</h4>
                        <?php
                        if (session()->getFlashdata('success')) { ?>
                            <label class="alert alert-success" style="width:100%;text-align: center;font-family: 'Oswald', sans-serif;font-weight: 500;font-style: italic; font-size: 25px;color: green;"><?= session()->getFlashdata('success') ?></label>
                        <?php  } ?>
                        <form id="form" name="currentPageFormSubadmin" class="form-auth-small" method="post" action="<?= base_url().'submit_venue' ?>" enctype="multipart/form-data" autocomplete="off">
                            <input type="hidden" name="CurrentFieldForUnique" id="CurrentFieldForUnique" value="id" />
                            <input type="hidden" name="CurrentIdForUnique" id="CurrentIdForUnique" value="<?= $EDITDATA['event_id'] ?>" />
                            <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?= $EDITDATA['event_id'] ?>" />
                            <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                            <input type="text" name="trap" style="display:none;" tabindex="-1" autocomplete="off" value=" "> 
                            <div class="submit-eventbox">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group <?= session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('location_name_') ? 'error' : '' ?>">
                                            <label>Venue Name<span class="required">*</span></label>
                                            <input type="text" name="location_name_" id="location_name_" class="form-control" placeholder="Event Venue Name" required>
                                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('location_name_')): ?>
                                                <span for="location_name_" generated="true" class="help-inline"> <?= session()->getFlashdata('validation')->getError('location_name_'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="formsubmitform-group <?= session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('location_address_') ? 'error' : '' ?>">
                                            <label>Venue Address<span class="required">*</span></label>
                                            <input type="text" name="location_address_" id="location_address_" class="form-control" placeholder="Event Venue Address" onchange="geocodeAddress()" required>
                                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('location_address_')): ?>
                                                <span for="location_address_" generated="true" class="help-inline"> <?= session()->getFlashdata('validation')->getError('location_address_'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group <?= session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('latitude') ? 'error' : '' ?>">
                                            <label>Latitude<span class="required">*</span></label>
                                            <input type="text" name="latitude_" id="latitude_" class="form-control" placeholder="Latitude" readonly required>
                                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('latitude_')): ?>
                                                <span for="latitude" generated="true" class="help-inline"> <?= session()->getFlashdata('validation')->getError('latitude_'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group <?= session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('longitude') ? 'error' : '' ?>">
                                            <label>Longitude<span class="required">*</span></label>
                                            <input type="text" name="longitude_" id="longitude_" class="form-control" placeholder="Longitude" readonly required>
                                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('longitude_')): ?>
                                                <span for="longitude_" generated="true" class="help-inline"> <?= session()->getFlashdata('validation')->getError('longitude_'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <!-- Hidden County Input -->
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12" style="display: none;">
                                        <div class="form-group <?= session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('county') ? 'error' : '' ?>">
                                            <label>County</label>
                                            <input type="text" name="county" id="county_" class="form-control" placeholder="County" readonly>
                                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('county')): ?>
                                                <span for="county" generated="true" class="help-inline"> <?= session()->getFlashdata('validation')->getError('county'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group <?= session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('website') ? 'error' : '' ?>">
                                            <label>Website</label>
                                            <input type="text" name="website_" id="website_" class="form-control" placeholder="Website">
                                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('website')): ?>
                                                <span for="website" generated="true" class="help-inline"> <?= session()->getFlashdata('validation')->getError('website'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group <?= session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('phone_number') ? 'error' : '' ?>">
                                            <label>Phone Number</label>
                                            <input type="text" name="phone_number_" id="phone_number_" class="form-control" placeholder="Phone number">
                                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('phone_number')): ?>
                                                <span for="longitude" generated="true" class="help-inline"> <?= session()->getFlashdata('validation')->getError('phone_number'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group <?= session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('venue_id_') ? 'error' : '' ?>">
                                            <label>Select Location<span class="required">*</span></label>
                                            <select class="form-control" id="venue_id_" name="venue_id_" required>
                                                <option value="">Select Location</option>
                                                <?php
                                                if (!empty($venues)):
                                                    foreach ($venues as $venue): ?>
                                                        <option value="<?= $venue['id'] ?>"><?= $venue['venue_title'] ?></option>
                                                <?php endforeach;
                                                endif; ?>
                                            </select>
                                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('venue_id_')): ?>
                                                <span for="venue_id_" generated="true" class="help-inline"> <?= session()->getFlashdata('validation')->getError('venue_id_'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group <?= session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('contact_person_name') ? 'error' : '' ?>">
                                            <label>Contact Person Name</label>
                                            <input type="text" name="contact_person_name" id="contact_person_name" class="form-control" placeholder="Contact person name" required>
                                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('contact_person_name')): ?>
                                                <span class="help-inline"> <?= session()->getFlashdata('validation')->getError('contact_person_name'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group <?= session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('contact_person_email') ? 'error' : '' ?>">
                                            <label>Contact Person Email</label>
                                            <input type="email" name="contact_person_email" id="contact_person_email" class="form-control" placeholder="Contact person email" required>
                                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('contact_person_email')): ?>
                                                <span class="help-inline"> <?= session()->getFlashdata('validation')->getError('contact_person_email'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group <?= session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('contact_person_phone_number') ? 'error' : '' ?>">
                                            <label>Contact Person Phone Number</label>
                                            <input type="text" name="contact_person_phone_number" id="contact_person_phone_number" class="form-control" placeholder="Contact person phone number"required >
                                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('contact_person_phone_number')): ?>
                                                <span class="help-inline"> <?= session()->getFlashdata('validation')->getError('contact_person_phone_number'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group <?= session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('contact_person_title') ? 'error' : '' ?>">
                                            <label>Contact Person Title</label>
                                            <input type="text" name="contact_person_title" id="contact_person_title" class="form-control" placeholder="Contact person title" required>
                                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('contact_person_title')): ?>
                                                <span class="help-inline"> <?= session()->getFlashdata('validation')->getError('contact_person_title'); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12" style="margin-left: 0px;margin-top: 10px;width: 100%;">
                                        <div class="g-recaptcha effect-16" data-sitekey="<?= esc($capcha_key) ?>" data-callback="recaptchaCallback"></div>
                                        <input type="hidden" name="captcha" id="captcha" required>
                                        <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('captcha')): ?>
                                            <label for="captcha" generated="true" class="error"> <?= session()->getFlashdata('validation')->getError('captcha'); ?></label>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <p class="float-right">
                                                <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
                                                <input type="submit" class="color-default-btn submit_btn" value="Submit">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-12 bd-left">
                    <h3 class="default-heading">Our Partners</h3>
                    <div class="owl-slider">
                        <div id="partner-carousel" class="owl-carousel">
                            <?php foreach ($slider_tbl as $key => $item): ?>
                                <?php if ($item['type'] === null || $item['type'] === 'slider') : ?>
                                    <div class="item">
                                        <a href="<?= $item['weblink'] ?>" target="_blank">
                                            <img class="img-fluid" src="<?= base_url().'assets/front/img/slider/'.$item['image'] ?>" alt="">
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach;  ?>
                        </div>
                    </div>
                    <button class="color-default-btn" style="margin-top:80px;" onclick="addvenue()"><i class="fa fa-plus" style="padding-right:px;"></i>Add Venue</button>
                    <p style="width: 180%;margin-top:8px;color:#000!important;"><i>Add your venue if you dont see the venue name in our existing list and then add your event.</i></p>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="css/style1.css">

    <!-- <div class="subscribe-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-5 col-12">
                    <h3 class="default-heading">Subscribe To Our <span> Newsletter</span></h3>
                    <p>Subscribe to our newsletter for daily updates.</p>

                </div>
                <div class="col-lg-7 col-md-7 col-sm-7 col-12">
                    <?php
                    if (session()->getFlashdata('alert_success')) { ?>
                        <span class="alert alert-success" style="width:100%;text-align: center;font-family: 'Oswald', sans-serif;font-weight: 500;font-style: italic;font-size: 25px;color: green;
                            "><?= session()->getFlashdata('alert_success') ?></span>
                    <?php  } else {
                    ?>
                        <span class="alert alert-error" style="width:100%;text-align: center;
					font-family: 'Oswald', sans-serif;
					font-weight: 500;
					font-style: italic;
					font-size: 25px;
					color: red;;
				"><?= session()->getFlashdata('alert_error') ?></span>
                    <?php }
                    ?>
                    <form id="form" name="currentPageFormSubadmin" class="subscribe" method="post" action="" enctype="multipart/form-data">
                        <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-12">
                                <input type="text" value="" placeholder="Enter Name" name="name" class="required name form-control" id="mce-FNAME">
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-12">
                                <input type="email" value="" placeholder="Enter Email Address" name="email" class="required email form-control" id="mce-EMAIL">

                                <input type="hidden" name="Savesubsc" id="Savesubsc" value="Yes">
                                <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
                            </div>
                            <div id="mce-responses" class="clear">
                                <div class="response" id="mce-error-response" style="display:none"></div>
                                <div class="response" id="mce-success-response" style="display:none;color: green;text-align: center;margin-left: 189px;margin-top: 14px;"></div>
                            </div>
                        </div>

                    </form>
                </div>
                <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
                <script type='text/javascript'>
                    (function($) {
                        window.fnames = new Array();
                        window.ftypes = new Array();
                        fnames[0] = 'EMAIL';
                        ftypes[0] = 'email';
                        fnames[1] = 'FNAME';
                        ftypes[1] = 'text';
                        fnames[2] = 'LNAME';
                        ftypes[2] = 'text';
                    }(jQuery));
                    var $mcj = jQuery.noConflict(true);
                </script>

            </div>
        </div>
    </div> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB9bu_dYx-Yzl6mwUxsKYSSq_p1yHJO6H8&libraries=places" async defer charset="utf-8"></script>
    <script type="text/javascript">
        function recaptchaCallback() {
            var v = grecaptcha.getResponse();
            $('#captcha').val(v);
        }
    </script>
    <script>
        function geocodeAddress() {
            var geocoder = new google.maps.Geocoder();
            var address = document.getElementById('location_address_').value;

            geocoder.geocode({
                'address': address
            }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    var latitude = results[0].geometry.location.lat();
                    var longitude = results[0].geometry.location.lng();

                    // Check for the latitude and longitude input elements
                    var latitudeField = document.getElementById('latitude_');
                    if (latitudeField) {
                        latitudeField.value = latitude;
                    }

                    var longitudeField = document.getElementById('longitude_');
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

                        //   // Check for state
                        //   if (component.types.includes('administrative_area_level_1')) {
                        //     state = component.long_name;
                        //   }
                    }

                    // Assign county and state values if the fields exist
                    var countyField = document.getElementById('county_');
                    if (countyField) {
                        countyField.value = county;
                    }

                    // var stateField = document.getElementById('address-state');
                    // if (stateField) {
                    //   stateField.value = state;
                    // }

                    // Set the state in the dropdown
                    // setSelectedState(state);

                    console.log('County: ' + county);
                    // console.log('State: ' + state);
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
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
                url: "<?= base_url('front/submit_event/location') ?>",
                data: data,
                success: function(data) {
                    ///alert(data.name);
                    $("#location_name").val(data.location_name);
                    $("#location_address").val(data.location_address);
                    $("#longitude").val(data.longitude);
                    $("#latitude").val(data.latitude);
                    $("#phone_number").val(data.phone_number);
                    $("#website").val(data.website);
                    $("#venue_id").val(data.venue_id);

                }

            });
        }
    </script>
    <script>
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd
        }
        if (mm < 10) {
            mm = '0' + mm
        }

        today = yyyy + '-' + mm + '-' + dd;
        document.getElementById("start_date").setAttribute("min", today);
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd
        }
        if (mm < 10) {
            mm = '0' + mm
        }

        today = yyyy + '-' + mm + '-' + dd;
        document.getElementById("end_date").setAttribute("min", today);
    </script>
    <script>
        function allow_alphabets(element) {
            let textInput = element.value;
            textInput = textInput.replace(/[^A-Za-z ]*$/gm, "");
            element.value = textInput;
        }
    </script>
    <script>
        function allow_alphabets_lastname(element) {
            let textInput = element.value;
            textInput = textInput.replace(/[^A-Za-z ]*$/gm, "");
            element.value = textInput;
        }
    </script>