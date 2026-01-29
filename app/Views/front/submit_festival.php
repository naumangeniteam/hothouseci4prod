<?php
$siteKey = getenv('RECAPTCHA_SITE_KEY');
$secretKey = getenv('RECAPTCHA_SECRET_KEY');
$session = session();
?>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-J3KMGPG70L"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>

<?= view('layouts/front/home-tabs') ?>
<style>
    .radiobox {
        display: flex;
        align-items: center;
    }

    .radiobox label {
        margin-bottom: 0px !important;
        margin-left: 15px;
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
        height: 40px;
        padding: 7px 32px;
        margin-top: 28px;
    }

    .required,
    .help-inline {
        color: red;
    }
</style>

<div class="hhj-submit-event">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="submit-event">
                    <h2>Jazz Festival Information</h2>
                    <?php if ($session->getFlashdata('success')): ?>
                        <label class="alert alert-success"><?= $session->getFlashdata('success') ?></label>
                    <?php endif; ?>

                    <?php if ($session->getFlashdata('error')): ?>
                        <label class="alert alert-danger"><?= $session->getFlashdata('error') ?></label>
                    <?php endif; ?>

                    <form id="form" name="currentPageFormSubadmin" class="form-auth-small" method="post" action="" enctype="multipart/form-data" autocomplete="off">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="CurrentFieldForUnique" id="CurrentFieldForUnique" value="id" />
                        <input type="hidden" name="CurrentIdForUnique" id="CurrentIdForUnique" value="" />
                        <input type="hidden" name="CurrentDataID" id="CurrentDataID" value="" />
                        <input type="text" name="trap" style="display:none;" tabindex="-1" autocomplete="off" value="">
                        
                        <div class="submit-eventbox">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('festival_title') ? 'error' : '' ?>">
                                        <label>Festival Title <span class="required">*</span></label>
                                        <input type="text" class="form-control" name="festival_title" required value="<?= old('festival_title') ?>">
                                        <?php if (session('validation') && session('validation')->hasError('festival_title')): ?>
                                            <span for="festival_title" generated="true" class="help-inline"><?= session('validation')->getError('festival_title') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('festival_description') ? 'error' : '' ?>">
                                        <label>Festival Description/Info <span class="required">*</span></label>
                                        <textarea class="form-control" name="festival_description" required><?= old('festival_description') ?></textarea>
                                        <?php if (session('validation') && session('validation')->hasError('festival_description')): ?>
                                            <span for="festival_description" generated="true" class="help-inline"><?= session('validation')->getError('festival_description') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group event-date <?= session('validation') && session('validation')->hasError('festival_start_date') ? 'error' : '' ?>">
                                        <label>Start Date <span class="required">*</span></label>
                                        <input type="date" class="form-control" name="festival_start_date" required value="<?= old('festival_start_date') ?>" />
                                        <?php if (session('validation') && session('validation')->hasError('festival_start_date')): ?>
                                            <span for="festival_start_date" generated="true" class="help-inline"><?= session('validation')->getError('festival_start_date') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group event-date <?= session('validation') && session('validation')->hasError('festival_end_date') ? 'error' : '' ?>">
                                        <label>End Date <span class="required">*</span></label>
                                        <input type="date" class="form-control" name="festival_end_date" value="<?= old('festival_end_date') ?>" required />
                                        <?php if (session('validation') && session('validation')->hasError('festival_end_date')): ?>
                                            <span for="festival_end_date" generated="true" class="help-inline"><?= session('validation')->getError('festival_end_date') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('festival_date_info') ? 'error' : '' ?>">
                                        <label>More information about dates</label>
                                        <textarea class="form-control" name="festival_date_info"><?= old('festival_date_info') ?></textarea>
                                        <?php if (session('validation') && session('validation')->hasError('festival_date_info')): ?>
                                            <span for="festival_date_info" generated="true" class="help-inline"><?= session('validation')->getError('festival_date_info') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row w-100">
                                    <div class="form-group-inner col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="animation">
                                            <label>Image<span class="required">*</span></label>
                                            <input id="uploadFile" name="image_display" class="f-input w-100" readonly="" required>
                                            <p style="font-family:italic; color:red;">[Image Size : 255 x 318 px in jpg/png/gif/jpeg]</p>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 px-0">
                                        <div class="fileUpload btn btn--browse">
                                            <span>Browse</span>
                                            <input id="uploadBtn" type="file" name="image" class="upload" accept="image/*">
                                        </div>
                                    </div>
                                    <?php if (session('validation') && session('validation')->hasError('image')): ?>
                                        <span for="image" generated="true" class="help-inline"><?= session('validation')->getError('image') ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('festival_website') ? 'error' : '' ?>">
                                        <label>Website <span class="required">*</span></label>
                                        <input type="text" class="form-control" name="festival_website" value="<?= old('festival_website') ?>" required>
                                        <?php if (session('validation') && session('validation')->hasError('festival_website')): ?>
                                            <span for="festival_website" generated="true" class="help-inline"><?= session('validation')->getError('festival_website') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('festival_headliners') ? 'error' : '' ?>">
                                        <label>Headliners <span class="required">*</span></label>
                                        <input type="text" class="form-control" name="festival_headliners" value="<?= old('festival_headliners') ?>" required>
                                        <?php if (session('validation') && session('validation')->hasError('festival_headliners')): ?>
                                            <span for="festival_headliners" generated="true" class="help-inline"><?= session('validation')->getError('festival_headliners') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('festival_lineup') ? 'error' : '' ?>">
                                        <label>Lineup <span class="required">*</span></label>
                                        <textarea class="form-control" name="festival_lineup" required><?= old('festival_lineup') ?></textarea>
                                        <?php if (session('validation') && session('validation')->hasError('festival_lineup')): ?>
                                            <span for="festival_lineup" generated="true" class="help-inline"><?= session('validation')->getError('festival_lineup') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('festival_city') ? 'error' : '' ?>">
                                        <label>Festival City<span class="required">*</span></label>
                                        <input type="text" class="form-control" name="festival_city" value="<?= old('festival_city') ?>" required>
                                        <?php if (session('validation') && session('validation')->hasError('festival_city')): ?>
                                            <span for="festival_city" generated="true" class="help-inline"><?= session('validation')->getError('festival_city') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('festival_state') ? 'error' : '' ?>">
                                        <label>Festival State<span class="required">*</span></label>
                                        <input type="text" class="form-control" name="festival_state" value="<?= old('festival_state') ?>" required>
                                        <?php if (session('validation') && session('validation')->hasError('festival_state')): ?>
                                            <span for="festival_state" generated="true" class="help-inline"><?= session('validation')->getError('festival_state') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('festival_ticket_price') ? 'error' : '' ?>">
                                        <label>Ticket Price Range<span class="required">*</span></label>
                                        <input type="text" class="form-control" name="festival_ticket_price" value="<?= old('festival_ticket_price') ?>" required>
                                        <?php if (session('validation') && session('validation')->hasError('festival_ticket_price')): ?>
                                            <span for="festival_ticket_price" generated="true" class="help-inline"><?= session('validation')->getError('festival_ticket_price') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <p class="ml-3">
                                    <i>Either select a saved venue or enter a new address manually <span class="required">*</span></i>
                                </p><br>

                                <div class="col-md-12">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('venue_address') ? 'error' : '' ?>">
                                        <label>Venue Address</label>
                                        <textarea class="form-control" name="venue_address" id="venue_address"><?= old('venue_address') ?></textarea>
                                        <?php if (session('validation') && session('validation')->hasError('venue_address')): ?>
                                            <span for="venue_address" generated="true" class="help-inline"><?= session('validation')->getError('venue_address') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <input type="hidden" name="latitude" id="latitude" readonly class="form-control" placeholder="Latitude " value="<?= old('latitude') ?>">
                                <input type="hidden" name="longitude" id="longitude" readonly class="form-control" placeholder="Longitude" value="<?= old('longitude') ?>">

                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('save_location_id') ? 'error' : '' ?>">
                                        <label>Select the saved venue</label>
                                        <select class="form-control" name="save_location_id" id="save_location_id" onchange="getLocationId()">
                                            <option value="">Select the saved venue</option>
                                            <?php if (!empty($location)): ?>
                                                <?php foreach ($location as $loc): ?>
                                                    <?php if (!empty($loc['location_name'])): ?>
                                                        <option value="<?= $loc['id'] ?>" <?= old('save_location_id') == $loc['id'] ? 'selected' : '' ?>>
                                                            <?= $loc['location_name']; ?>
                                                        </option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <?php if (session('validation') && session('validation')->hasError('save_location_id')): ?>
                                            <span for="save_location_id" generated="true" class="help-inline"><?= session('validation')->getError('save_location_id') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('location_name') ? 'error' : '' ?>">
                                        <label>Event Venue Name</label>
                                        <input type="text" name="location_name" id="location_name" readonly class="form-control" placeholder="Event Venue Name">
                                        <?php if (session('validation') && session('validation')->hasError('location_name')): ?>
                                            <span for="location_name" generated="true" class="help-inline"><?= session('validation')->getError('location_name') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('location_address') ? 'error' : '' ?>">
                                        <label>Event Venue Address</label>
                                        <input type="text" name="location_address" id="location_address" readonly class="form-control" placeholder="Event Venue Address">
                                        <?php if (session('validation') && session('validation')->hasError('location_address')): ?>
                                            <span for="location_address" generated="true" class="help-inline"><?= session('validation')->getError('location_address') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('location_latitude') ? 'error' : '' ?>">
                                        <label>Latitude</label>
                                        <input type="text" name="location_latitude" id="location_latitude" readonly class="form-control" placeholder="Latitude">
                                        <?php if (session('validation') && session('validation')->hasError('location_latitude')): ?>
                                            <span for="location_latitude" generated="true" class="help-inline"><?= session('validation')->getError('location_latitude') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('location_longitude') ? 'error' : '' ?>">
                                        <label>Longitude</label>
                                        <input type="text" name="location_longitude" id="location_longitude" readonly class="form-control" placeholder="Longitude">
                                        <?php if (session('validation') && session('validation')->hasError('location_longitude')): ?>
                                            <span for="location_longitude" generated="true" class="help-inline"><?= session('validation')->getError('location_longitude') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('location_website') ? 'error' : '' ?>">
                                        <label>Venue Website</label>
                                        <input type="text" name="location_website" id="location_website" readonly class="form-control" placeholder="Venue Website">
                                        <?php if (session('validation') && session('validation')->hasError('location_website')): ?>
                                            <span for="location_website" generated="true" class="help-inline"><?= session('validation')->getError('location_website') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('location_phone_number') ? 'error' : '' ?>">
                                        <label>Venue Phone Number</label>
                                        <input type="text" name="location_phone_number" id="location_phone_number" readonly class="form-control" placeholder="Venue Phone Number">
                                        <?php if (session('validation') && session('validation')->hasError('location_phone_number')): ?>
                                            <span for="location_phone_number" generated="true" class="help-inline"><?= session('validation')->getError('location_phone_number') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('venue_id') ? 'error' : '' ?>">
                                        <label>Select Location<span class="required">*</span></label>
                                        <select class="form-control" id="venue_id" name="venue_id" required>
                                            <option value="">Select Location</option>
                                            <?php if (!empty($venues)): ?>
                                                <?php foreach ($venues as $venue): ?>
                                                    <option value="<?= $venue['id'] ?>" <?= old('venue_id') == $venue['id'] ? 'selected' : '' ?>><?= $venue['venue_title'] ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <?php if (session('validation') && session('validation')->hasError('venue_id')): ?>
                                            <span for="venue_id" generated="true" class="help-inline"><?= session('validation')->getError('venue_id') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('festival_contact_title') ? 'error' : '' ?>">
                                        <label>Contact Title<span class="required">*</span></label>
                                        <input type="text" class="form-control" name="festival_contact_title" required value="<?= old('festival_contact_title') ?>">
                                        <?php if (session('validation') && session('validation')->hasError('festival_contact_title')): ?>
                                            <span for="festival_contact_title" generated="true" class="help-inline"><?= session('validation')->getError('festival_contact_title') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('festival_contact_name') ? 'error' : '' ?>">
                                        <label>Contact Name<span class="required">*</span></label>
                                        <input type="text" class="form-control" name="festival_contact_name" required value="<?= old('festival_contact_name') ?>">
                                        <?php if (session('validation') && session('validation')->hasError('festival_contact_name')): ?>
                                            <span for="festival_contact_name" generated="true" class="help-inline"><?= session('validation')->getError('festival_contact_name') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('festival_contact_email') ? 'error' : '' ?>">
                                        <label>Contact Email<span class="required">*</span></label>
                                        <input type="email" class="form-control" name="festival_contact_email" required value="<?= old('festival_contact_email') ?>">
                                        <?php if (session('validation') && session('validation')->hasError('festival_contact_email')): ?>
                                            <span for="festival_contact_email" generated="true" class="help-inline"><?= session('validation')->getError('festival_contact_email') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group <?= session('validation') && session('validation')->hasError('festival_contact_phone') ? 'error' : '' ?>">
                                        <label>Contact Phone<span class="required">*</span></label>
                                        <input type="text" class="form-control" name="festival_contact_phone" required value="<?= old('festival_contact_phone') ?>">
                                        <?php if (session('validation') && session('validation')->hasError('festival_contact_phone')): ?>
                                            <span for="festival_contact_phone" generated="true" class="help-inline"><?= session('validation')->getError('festival_contact_phone') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="form-group">
                                        <div class="radiobox">
                                            <input type="hidden" name="direct_list" value="0">
                                            <input type="checkbox" id="direct_list" name="direct_list" value="1" style="cursor: pointer;" <?= old('direct_list') == '1' ? 'checked' : '' ?>>
                                            <label for="direct_list">Jazz Festival Directory Listing?</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Interested in Advertising?</label>
                                        <select name="festival_advertise" id="festival_advertise" class="form-control">
                                            <option value="">-- Select --</option>
                                            <option value="1" <?= old('festival_advertise') === '1' ? 'selected' : '' ?>>Yes</option>
                                            <option value="0" <?= old('festival_advertise') === '0' ? 'selected' : '' ?>>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 gcaptcha">
                                    <div class="g-recaptcha effect-16" data-sitekey="<?= esc($capcha_key) ?>" data-callback="recaptchaCallback"></div>
                                    <input type="hidden" name="captcha" id="captcha" required>
                                    <?php if (session('validation') && session('validation')->hasError('captcha')): ?>
                                        <span for="captcha" generated="true" class="help-inline"><?= session('validation')->getError('captcha') ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" name="is_front" value="1">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group submit-events">
                                        <a href="#">Reset</a> &nbsp;
                                        <input type="hidden" name="SaveChanges" id="SaveChanges" value="Yes">
                                        <input type="submit" class="color-default-btn" value="Submit">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
    function recaptchaCallback() {
        var v = grecaptcha.getResponse();
        $('#captcha').val(v);
    }
</script>

<script>
    document.getElementById("uploadBtn").onchange = function() {
        document.getElementById("uploadFile").value = this.value.replace("C:\\fakepath\\", "");
    };
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB9bu_dYx-Yzl6mwUxsKYSSq_p1yHJO6H8&libraries=places" async defer charset="utf-8"></script>

<script>
    function geocodeAddress() {
        var geocoder = new google.maps.Geocoder();
        var address = document.getElementById('venue_address').value;

        geocoder.geocode({
            'address': address
        }, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                var latitude = results[0].geometry.location.lat();
                var longitude = results[0].geometry.location.lng();

                document.getElementById('latitude').value = latitude;
                document.getElementById('longitude').value = longitude;
            } else {
                alert('Geocode was not successful for the following reason: ' + status);
            }
        });
    }

    document.getElementById('venue_address').addEventListener('change', geocodeAddress);
</script>

<script>
    function getLocationId() {
        var save_location_id = $('#save_location_id').val();
        $("#location_name").val('');
        $("#location_address").val('');
        var data = 'LocationId=' + save_location_id;
        
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: "<?= base_url('submit_festival/location') ?>",
            data: data,
            success: function(data) {
                $("#location_name").val(data.location_name);
                $("#location_address").val(data.location_address);
                $("#location_longitude").val(data.longitude);
                $("#location_latitude").val(data.latitude);
                $("#location_phone_number").val(data.phone_number);
                $("#location_website").val(data.website);
                $("#venue_id").val(data.venue_id);
            }
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>