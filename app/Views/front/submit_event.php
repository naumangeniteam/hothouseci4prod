<?php
$siteKey = getenv('RECAPTCHA_SITE_KEY');
$secretKey = getenv('RECAPTCHA_SECRET_KEY');
$session = session();
?>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-J3KMGPG70L"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>

<?= view('layouts/front/home-tabs') ?>
<div class="hhj-submit-event">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="submit-event">
					<h2>Submission Guidelines</h2>
					<?php
					if ($session->getFlashdata('success')) { ?>
						<label class="alert alert-success"><?= $session->getFlashdata('success') ?>
						</label>
					<?php } ?>

					<?php
					if ($session->getFlashdata('error')) { ?>
						<label class="alert alert-error"><?= $session->getFlashdata('error') ?></label>
					<?php } ?>

					<form id="form" name="currentPageFormSubadmin" class="form-auth-small" method="post" action="" enctype="multipart/form-data" autocomplete="off">
					<?= csrf_field(); ?>
					<input type="hidden" name="CurrentFieldForUnique" id="CurrentFieldForUnique" value="id" />
						<input type="hidden" name="CurrentIdForUnique" id="CurrentIdForUnique" value="<?= $EDITDATA['event_id'] ?>" />
						<input type="hidden" name="CurrentDataID" id="CurrentDataID" value="<?= $EDITDATA['event_id'] ?>" />
						<input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
						<input type="text" name="trap" style="display:none;" tabindex="-1" autocomplete="off" value=""> 
						<div class="submit-eventbox">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group <?= session('validation') && session('validation')->hasError('event_title') ? 'error' : '' ?>">
										<input type="text" value="<?php if (old('event_title')) : echo old('event_title');
																	else : echo stripslashes($EDITDATA['event_title']);
																	endif; ?>" class="form-control" placeholder="Event title" name="event_title" required>
									
										<?php if (session('validation') && session('validation')->hasError('event_title')) : ?>
											<span for="event_title" generated="true" class="help-inline"><?= session('validation')->getError('event_title') ?></span>
										<?php endif; ?>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group <?= session('validation') && session('validation')->hasError('description') ? 'error' : '' ?>">
										<textarea class="form-control" name="description" placeholder="Description"></textarea>
										
										<?php if (session('validation') && session('validation')->hasError('description')) : ?>
											<span for="description" generated="true" class="help-inline"><?= session('validation')->getError('description') ?></span>
										<?php endif; ?>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<input type="text" name="event_tags" id="event_tags" class="form-control" placeholder="Event tags(ex)- Funk jazz, Latin jazz, Big band" value="">
									</div>
								</div>

								<div class="form-group col-md-6">
									<select name="event_types" id="event_types" class="form-control">
										<option value="">Select event type</option>
										<option value="Auditorium/Night Club" <?php if ($EDITDATA['event_types'] == 'Auditorium/Night Club') echo "selected"; ?>>Auditorium/Night Club</option>
										<option value="Free Concert" <?php if ($EDITDATA['event_types'] == 'Free Concert') echo "selected"; ?>>Free Concert</option>
										<option value="Awards/Celebrations" <?php if ($EDITDATA['event_types'] == 'Awards/Celebrations') echo "selected"; ?>>Awards/Celebrations</option>
										<option value="For Kids/Youth" <?php if ($EDITDATA['event_types'] == 'For Kids/Youth') echo "selected"; ?>>For Kids/Youth</option>
										<option value="Festival" <?php if ($EDITDATA['event_types'] == 'Festival') echo "selected"; ?>>Festival</option>
										<option value="Other" <?php if ($EDITDATA['event_types'] == 'Other') echo "selected"; ?>>Other</option>
									</select>
								</div>

								<div class="col-md-6">
									<div class="form-group event-date <?= session('validation') && session('validation')->hasError('start_date') ? 'error' : '' ?>">
										<label>Event start date</label>
										<input type="date" class="form-control" name="start_date" required placeholder="Event start date" />
									
										<?php if (session('validation') && session('validation')->hasError('start_date')) : ?>
											<span for="start_date" generated="true" class="help-inline"><?= session('validation')->getError('start_date') ?></span>
										<?php endif; ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group event-date <?= session('validation') && session('validation')->hasError('end_date') ? 'error' : '' ?>">
										<label>Event end date</label>
										<input type="date" class="form-control" name="end_date" required placeholder="Event end date" />
										
										<?php if (session('validation') && session('validation')->hasError('end_date')) : ?>
											<span for="end_date" generated="true" class="help-inline"><?= session('validation')->getError('end_date') ?></span>
										<?php endif; ?>
									</div>
								</div>
								<!--<div class="col-lg-6 col-md-6 col-sm-6 col-12">
								<div class="form-group <?= session('validation') && session('validation')->hasError('event_start_time') ? 'error' : '' ?>">
									<label>Event Start Time<span class="required">*</span></label>
									<input type="time" class="form-control" name="event_start_time" placeholder="Event Start Time"/>
									
									<?php if (session('validation') && session('validation')->hasError('event_start_time')) : ?>
										<span class="help-inline" for="event_start_time" ><?= session('validation')->getError('event_start_time') ?></span>
									<?php endif; ?>
								</div>
								</div>-->

								<div class="col-md-6 event-date">
									<label>Event start time<span class="required">*</span></label>
									<input type="hidden" name="event_start_time" id="event_start_hour" value="">
									<select id="timehr_event_end_time" name="event_start_hour" htype="event_end_time">
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
									<select id="timemin_event_end_time" name="event_start_min" htype="event_end_time">
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
									<select id="timesec_event_end_time" name="event_start_M" htype="event_end_time">
										<option value="PM" <?php if ($hour2 == "PM") {
																echo "selected";
															} ?>>PM</option>
										<option value="AM" <?php if ($hour2 == "AM") {
																echo "selected";
															} ?>>AM</option>
									</select>
								</div>

								<!--<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<div class="form-group <?= session('validation') && session('validation')->hasError('event_end_time') ? 'error' : '' ?>">
										<label>Event End Time<span class="required">*</span></label>
										<input type="time" class="form-control" name="event_end_time" placeholder="Event End Time"/>
										
										<?php if (session('validation') && session('validation')->hasError('event_end_time')) : ?>
											<span class="help-inline" for="event_end_time" ><?= session('validation')->getError('event_end_time') ?></span>
										<?php endif; ?>
									</div>
								</div>-->


								<div class="col-md-6 event-date">
									<label>Event End Time<span class="required">*</span></label>
									<input type="hidden" name="event_end_time" id="event_end_time" value="">
									<select id="timehr_event_end_time" name="event_end_hour" htype="event_end_time" onchange="setTime(this);">

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
									<select id="timemin_event_end_time" name="event_end_min" htype="event_end_time">
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
									<select id="timesec_event_end_time" name="event_end_M" htype="event_end_time">
										<option value="PM" <?php if ($hour2_end == "PM") {
																echo "selected";
															} ?>>PM</option>
										<option value="AM" <?php if ($hour2_end == "AM") {
																echo "selected";
															} ?>>AM</option>
									</select>
								</div>

								<label class="single-label">Repeating event</label>
								<div class="col-md-12 repeated-event">
									<div class="form-group <?= session('validation') && session('validation')->hasError('repeating_event') ? 'error' : '' ?>">
										<div class="radiobox">
											<input type="radio" id="test1" name="repeating_event" value="yes"> <label for="test1">Yes</label>
											
											<?php if (session('validation') && session('validation')->hasError('repeating_event')) : ?>
											<span for="repeating_event" generated="true" class="help-inline"><?= session('validation')->getError('repeating_event') ?></span>
										<?php endif; ?>
										</div>
									</div>
									<div class="form-group <?= session('validation') && session('validation')->hasError('repeating_event') ? 'error' : '' ?>">
										<div class="radiobox mt-31">
											<input type="radio" id="test2" name="repeating_event" value="no" checked> <label for="test2">No</label>
											
											<?php if (session('validation') && session('validation')->hasError('repeating_event')) : ?>
											<span for="repeating_event" generated="true" class="help-inline"><?= session('validation')->getError('repeating_event') ?></span>
										<?php endif; ?>
										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<div class="radiobox virtual-event">
											<input type="checkbox" id="virtual_event" name="virtual_event" value="virtual event"><label for="virtual event">Virtual event?</label>
										</div>
									</div>
								</div>

								<div class="col-md-12 additional_fields" style="display: none;">
									<div class="form-group <?= session('validation') && session('validation')->hasError('virtual_event_price') ? 'error' : '' ?>">
										<input type="number" class="form-control" name="virtual_event_price" value="" placeholder="Virtual event price">
										
										<?php if (session('validation') && session('validation')->hasError('virtual_event_price')) : ?>
											<span for="virtual_event_price" generated="true" class="help-inline"><?= session('validation')->getError('virtual_event_price') ?></span>
										<?php endif; ?>
									</div>
								</div>

								<div class="col-md-12 additional_fields" style="display: none;">
									<div class="form-group <?= session('validation') && session('validation')->hasError('virtual_event_link') ? 'error' : '' ?>">
										<input type="text" class="form-control" name="virtual_event_link" value="" placeholder="Virtual event link">
										
										<?php if (session('validation') && session('validation')->hasError('virtual_event_link')) : ?>
											<span for="virtual_event_link" generated="true" class="help-inline"><?= session('validation')->getError('virtual_event_link') ?></span>
										<?php endif; ?>
									</div>
								</div>

								<div class="col-md-12">
									<div class="add-new-vanue">
										<a href="<?= base_url('submit_venue') ?>" class="color-default-btn" onclick="addvenue()">Add Venue</a>
										<p><i>Add your venue if you dont see the venue name in our existing list and then add your event.</i></p>
									</div>

									<div class="form-group <?= session('validation') && session('validation')->hasError('save_location_id') ? 'error' : '' ?>">
										
										<select class="form-control" name="save_location_id" id="save_location_id" required>
										<option value="">Select the saved venue</option>
										<?php if (!empty($location)) : ?>
											<?php foreach ($location as $loc) : ?>
												<?php if (!empty($loc['location_name'])) : ?>
													<option <?php if (isset($save_location_id) && $save_location_id == $loc['id']) {
																echo "selected";
															} ?> value="<?= $loc['id'] ?>">
														<?= $loc['location_name']; ?>
													</option>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>

										<?php if (session('validation') && session('validation')->hasError('save_location_id')) : ?>
											<span for="save_location_id" generated="true" class="help-inline"><?= session('validation')->getError('save_location_id') ?></span>
										<?php endif; ?>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group <?= session('validation') && session('validation')->hasError('location_name') ? 'error' : '' ?>">
										<input type="text" name="location_name" id="location_name" readonly class="form-control" placeholder="Event venue name" required>
										
										<?php if (session('validation') && session('validation')->hasError('location_name')) : ?>
											<span for="location_name" generated="true" class="help-inline"><?= session('validation')->getError('location_name') ?></span>
										<?php endif; ?>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group <?= session('validation') && session('validation')->hasError('location_address') ? 'error' : '' ?>">
										<input type="text" name="location_address" id="location_address" readonly class="form-control" placeholder="Event venue address" required>
										
										<?php if (session('validation') && session('validation')->hasError('location_address')) : ?>
											<span for="location_address" generated="true" class="help-inline"><?= session('validation')->getError('location_address') ?></span>
										<?php endif; ?>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group <?= session('validation') && session('validation')->hasError('latitude') ? 'error' : '' ?>">
										<input type="text" name="latitude" id="latitude" readonly class="form-control" placeholder="Latitude" required>
										
										<?php if (session('validation') && session('validation')->hasError('latitude')) : ?>
											<span for="latitude" generated="true" class="help-inline"><?= session('validation')->getError('latitude') ?></span>
										<?php endif; ?>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group <?= session('validation') && session('validation')->hasError('longitude') ? 'error' : '' ?>">
										<input type="text" name="longitude" id="longitude" readonly class="form-control" placeholder="Longitude" required>
										
										<?php if (session('validation') && session('validation')->hasError('longitude')) : ?>
											<span for="longitude" generated="true" class="help-inline"><?= session('validation')->getError('longitude') ?></span>
										<?php endif; ?>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group <?= session('validation') && session('validation')->hasError('website') ? 'error' : '' ?>">
										<input type="text" name="website" id="website" readonly class="form-control" placeholder="Venue website">
										
										<?php if (session('validation') && session('validation')->hasError('website')) : ?>
											<span for="website" generated="true" class="help-inline"><?= session('validation')->getError('website') ?></span>
										<?php endif; ?>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group <?= session('validation') && session('validation')->hasError('phone_number') ? 'error' : '' ?>">
										<input type="text" name="phone_number" id="phone_number" readonly class="form-control" placeholder="Venue phone number">
										
										<?php if (session('validation') && session('validation')->hasError('phone_number')) : ?>
											<span for="phone_number" generated="true" class="help-inline"><?= session('validation')->getError('phone_number') ?></span>
										<?php endif; ?>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group <?= session('validation') && session('validation')->hasError('venue_id') ? 'error' : '' ?>">
									
									<select class="form-control" id="venue_id" name="venue_id" required>
											<option value="">Select Location</option>
											<?php
											if (!empty($venues)) :
												foreach ($venues as $venue) : ?>
													<option value="<?= $venue['id'] ?>"><?= $venue['venue_title'] ?></option>
											<?php endforeach;
											endif; ?>
										</select>
									
										<?php if (session('validation') && session('validation')->hasError('venue_id')) : ?>
											<span for="venue_id" generated="true" class="help-inline"><?= session('validation')->getError('venue_id') ?></span>
										<?php endif; ?>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group <?= session('validation') && session('validation')->hasError('cover_charge') ? 'error' : '' ?>">
										<input type="text" class="form-control" name="cover_charge" placeholder="Cover charger">
										
										<?php if (session('validation') && session('validation')->hasError('cover_charge')) : ?>
											<span for="cover_charge" generated="true" class="help-inline"><?= session('validation')->getError('cover_charge') ?></span>
										<?php endif; ?>
									</div>
								</div>

								<div class="col-md-12 artist-row">
									<div class="form-group <?= session('validation') && session('validation')->hasError('artist_id') ? 'error' : '' ?>">
									
									<select name="artist_id" id="artist_id" class="form-control">
											<option value="">Select artist</option>
											<?php
											if (!empty($artistTypes)) :
												foreach ($artistTypes as $artistType) : ?>
													<option <?php if ($EDITDATA['artist_id'] == $artistType['id']) {
																echo "selected";
															} ?> value="<?= $artistType['id'] ?>">
														<?= $artistType['artist_name'] ?>
													</option>
											<?php endforeach;
											endif; ?>
										</select>
										
										<?php if (session('validation') && session('validation')->hasError('artist_id')) : ?>
											<span for="artist_id" generated="true" class="help-inline"><?= session('validation')->getError('artist_id') ?></span>
										<?php endif; ?>
									</div>
									<div class="add-artist">
										<span class="artist-btn" data-toggle="modal" data-target="#artistModal">Add New Artist</span>
									</div>
									<div class="boost-cont">
										<input type="checkbox" name="requested_boost" value="1">
										<p class="boost-text">Want to boost?</p>
									</div>
								</div>


								<div class="col-md-4">
									<div class="form-group <?= session('validation') && session('validation')->hasError('video') ? 'error' : '' ?>">
										<input type="text" class="form-control" name="video" placeholder="Video link">
									
										<?php if (session('validation') && session('validation')->hasError('video')) : ?>
											<span for="video" generated="true" class="help-inline"><?= session('validation')->getError('video') ?></span>
										<?php endif; ?>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group <?= session('validation') && session('validation')->hasError('qr_code_link') ? 'error' : '' ?>">
										<input type="text" class="form-control" name="qr_code_link" placeholder="QR code link">
			
										<?php if (session('validation') && session('validation')->hasError('qr_code_link')) : ?>
											<span for="qr_code_link" generated="true" class="help-inline"><?= session('validation')->getError('qr_code_link') ?></span>
										<?php endif; ?>
									</div>
								</div>

								<!-- <div class="col-md-4">
									<div class="form-group <?= session('validation') && session('validation')->hasError('jazz_types_id') ? 'error' : '' ?>">
										<select name="jazz_types_id" id="jazz_types_id" class="form-control">
											<option value="">Select Jazz</option>
											<?php
											if (!empty($jazzTypes)) :
												foreach ($jazzTypes as $jazzType) : ?>
													<?php $selected_jazz = '';
													if ((!empty($EDITDATA['jazz_types_id']) && $EDITDATA['jazz_types_id'] == $jazzType->id) || (empty($EDITDATA['jazz_types_id']) && $jazzType->id == 30)) {
														$selected_jazz = 'selected';
													}
													?>
													<option <?php echo "$selected_jazz"; ?> value="<?= $jazzType->id ?>" jazz="<?= $jazzType->id ?>"><?= $jazzType->name ?></option>
											<?php endforeach;
											endif; ?>
										</select>
										
										<?php if (session('validation') && session('validation')->hasError('jazz_types_id')) : ?>
											<span for="jazz_types_id" generated="true" class="help-inline"><?= session('validation')->getError('jazz_types_id') ?></span>
										<?php endif; ?>
									</div>
								</div> -->
								<div class="col-md-4">
									<div class="form-group">
										<select name="jazz_types_id[]" id="jazz_types_id" class="form-control" multiple="multiple">
											<option value="" disabled>Select Jazz</option>
											<?php
											// Assuming $jazzTypes contains all available jazz types
											if (!empty($jazzTypes)) :
												foreach ($jazzTypes as $jazzType) :
													// Check if the current jazz type is selected
													$selected_jazz = '';
													// If EDITDATA['jazz_types_id'] is an array of selected jazz type IDs
													if (!empty($EDITDATA['jazz_types_id']) && in_array($jazzType['id'], $EDITDATA['jazz_types_id'])) {
														$selected_jazz = 'selected';
													}
											?>
													<option <?php echo $selected_jazz; ?> value="<?= $jazzType['id'] ?>"><?= $jazzType['name'] ?></option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group <?= session('validation') && session('validation')->hasError('contact_person') ? 'error' : '' ?>">
										<input type="text" class="form-control" name="contact_person" placeholder="Contact person first name" required>
										
										<?php if (session('validation') && session('validation')->hasError('contact_person')) : ?>
											<span for="contact_person" generated="true" class="help-inline"><?= session('validation')->getError('contact_person') ?></span>
										<?php endif; ?>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group <?= session('validation') && session('validation')->hasError('contact_lastname') ? 'error' : '' ?>">
										<input type="text" class="form-control" name="contact_lastname" placeholder="Contact person last name" required>
										
										<?php if (session('validation') && session('validation')->hasError('contact_lastname')) : ?>
											<span for="contact_lastname" generated="true" class="help-inline"><?= session('validation')->getError('contact_lastname') ?></span>
										<?php endif; ?>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group <?= session('validation') && session('validation')->hasError('email') ? 'error' : '' ?>">
										<input type="text" class="form-control" name="email" placeholder="Contact email" required>
										
										<?php if (session('validation') && session('validation')->hasError('email')) : ?>
											<span for="email" generated="true" class="help-inline"><?= session('validation')->getError('email') ?></span>
										<?php endif; ?>
									</div>
								</div>
								<div class="col-md-12 gcaptcha">
									<div class="g-recaptcha effect-16" data-sitekey="<?= esc($capcha_key) ?>" data-callback="recaptchaCallback"></div>
									<input type="hidden" name="captcha" id="captcha" required>
								
										<?php if (session('validation') && session('validation')->hasError('captcha')) : ?>
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

<div class="modal fade" id="artistModal" tabindex="-1" aria-labelledby="artistModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<span id="artError"></span>
				<?php
				if ($session->getFlashdata('success_msg')) { ?>
					<label class="alert alert-success" id="successMessage" style="width:100%;text-align: center;
						font-family: 'Oswald', sans-serif;
						font-weight: 500;
						font-style: italic;
						font-size: 25px;
						color: green;
						background: #fff;
						border: none;
						"><?= $session->getFlashdata('success_msg') ?></label>
				<?php  } ?>
				<?php
				if ($session->getFlashdata('error_msg')) { ?>
					<label class="alert alert-error" style="width:100%;text-align: center;
						font-family: 'Oswald', sans-serif;
						font-weight: 500;
						font-style: italic;
						font-size: 25px;
						color: red;
					"><?= $session->getFlashdata('error_msg') ?></label>
				<?php  } ?>
				<div class="row">
				<input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">

				<!-- <input type="text" name="trap"  id="trap" style="display:none;" tabindex="-1" autocomplete="off" value="">  -->
					<div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label>Artist Name<span class="required"></span></label>
						<input type="text" name="artist_name" id="artist_name" value="" class="form-control required" placeholder="Artist Name" autocomplete="off" required>
					</div>
				</div>
				<!-- <div class="row mt-2">
					<div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label>Artist Email<span class="required"></span></label>
						<input type="email" name="artist_email" id="artist_email" value="" class="form-control required" placeholder="Artist Email" />
					</div>
				</div> -->
				<div class="row mt-2">
					<div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label>Thumb Image Url<span class=""></span></label>
						<input type="text" name="artist_url" id="artist_url" value="" class="form-control" placeholder="Thumb Image Url ">
					</div>
				</div>
				<div class="row mt-2">
					<div class="form-group-innercol-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label>Cover Image Url<span class=""></span></label>
						<input type="text" name="cover_url" id="cover_url" value="" class="form-control" placeholder="Cover Image Url ">
					</div>
				</div>
				<div class="row mt-2">
					<div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label>Buy Link<span class=""></span></label>
						<input type="text" name="buy_now_link" id="buy_now_link" value="" class="form-control " placeholder="Buy Link">
					</div>
				</div>

				<div class="row mt-2">
					<div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label>Website Link<span class=""></span></label>
						<input type="text" name="website_link" id="website_link" value="" class="form-control" placeholder="Website Link">
					</div>
				</div>

				<div class="row mt-2">
					<div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="animation">
							<label>Artist Bio<span class=""></span></label>
							<textarea name="artist_bio" id="artist_bio" rows="4" cols="50" style="width: 100%;" required></textarea>
						</div>
					</div>
				</div>
				<div class="row mt-2">
					<div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="g-recaptcha effect-16" data-sitekey="<?= esc($capcha_key) ?>" data-callback="recaptchaCallbackFestival"></div>
						<input type="hidden" name="captcha" id="captcha_fes" required>
						<div id="captcha-festival" class="" style="color: red;"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal" id="saveArtistBtn">submit</button>
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
	$('#jazz_types_id').select2({
		placeholder: "Select Jazz",
		allowClear: true // Allows the user to clear the selection
	});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("save_location_id").addEventListener("change", function() {
        var save_location_id = this.value; 
		$("#location_name").val('');
		$("#location_address").val('');
		// $("#longitude").val('');
		// $("#latitude").val('');
		// $("#phone_number").val('');
		// $("#website").val('');
		// $("#venue_id").val('');
		var url_link = "<?= base_url('front/submit_event/get-venue-location') ?>";
		var data = 'LocationId=' + save_location_id;
		$.ajax({
			type: 'GET',
			dataType: 'json',
			url: "<?= base_url('get-venue-location'); ?>",
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
	});
});
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

<!-- <script>
		document.getElementById("virtual_event").addEventListener("change", function() {
			var additionalFields = document.getElementById("additional_fields");
			if (this.checked) {
				additionalFields.style.display = "block";
			} else {
				additionalFields.style.display = "none";
			}
		});
	</script> -->

<script>
	document.getElementById("virtual_event").addEventListener("change", function() {
		var additionalFields = document.getElementsByClassName("additional_fields");
		if (this.checked) {
			for (var i = 0; i < additionalFields.length; i++) {
				additionalFields[i].style.display = "block";
			}
		} else {
			for (var i = 0; i < additionalFields.length; i++) {
				additionalFields[i].style.display = "none";
			}
		}
	});
</script>
<script type="text/javascript">
	function recaptchaCallbackFestival() {
		var v = grecaptcha.getResponse();
		$('#captcha_fes').val(v);
	}
</script>

<script>
	$(document).ready(function() {
		//$('#jazz_types_id').select2(); 

		var csrfName = '<?= csrf_token(); ?>';
		var csrfHash = '<?= csrf_hash(); ?>';

		$('#saveArtistBtn').on('click', function(e) {
			e.preventDefault();
			[csrfName]= csrfHash; // CSRF token
			var artistName = $('#artist_name').val();
			var artistUrl = $('#artist_url').val();
			var coverUrl = $('#cover_url').val();
			var buyNowLink = $('#buy_now_link').val();
			var websiteLink = $('#website_link').val();
			var artistBio = $('#artist_bio').val();
            // var trap =  $('#trap').val();
			var url = '<?= base_url('save-artist-name') ?>';
			$('#artError').html('');
			$.ajax({
				url: url,
				type: 'POST',
				data: {
					artist_name: artistName,
					artist_url: artistUrl,
					cover_url: coverUrl,
					buy_now_link: buyNowLink,
					website_link: websiteLink,
					artist_bio: artistBio,
					// trap:trap,
				},
				success: function(response) {
					var result = JSON.parse(response);

					if (result.success == false) {
						$('#artError').html(`<label class="alert alert-error" style="width:100%;text-align: center;
										font-family: 'Oswald', sans-serif;
										font-weight: 500;
										font-style: italic;
										font-size: 25px;
										color: red;
									">${result.message}</label>`);
						return false;
					}

					// $('#artist_id').append(`<option selected value="${result.id}">${result.name}</option>`)

					console.log('Data saved successfully.');
					$('#artistModal').modal('hide');
					// location.reload(); 
				},
				error: function(xhr, status, error) {
					console.error('Error occurred:', error);
					// location.reload();
				}
			});
		});

	});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script>