<?php
//in ci3
// $this->load->model(array('admin_model', 'emailtemplate_model', 'sms_model', 'notification_model'));
$this->lang = service('language'); 
$this->lang->setLocale('front');
// helper('common');

// $where5['where'] 		=	"is_active = '1'";
// $tbl5 					=	'footer_tbl as ftable';
// $shortField5			=	'id DESC';

// $shortField6 			=	'type_name ASC';
// $data['footer_tbl'] 	= 	$this->common_model->getData('multiple', $tbl5, $where5);
// $commonModel = new CommonModel();


// in ci4
// $where = ['is_active' => '1'];
// $footerData = $commonModel->getData('footer_tbl', $where, 'id DESC');
$session = session();
?>

<!-- <div class="jazz-subscription" id="newsletter"> -->

      <!-- <div class="row" id="newsletter"> --> 
<!-- <div class="col-lg-12 col-md-12 col-sm-12 col-12">
			<h2>Subscribe to our newsletter</h2>
			<?php if ($session->getFlashdata('alert_success')): ?>
    <span class="alert alert-success"><?= esc($session->getFlashdata('alert_success')) ?></span>
<?php elseif ($session->getFlashdata('alert_error')): ?>
    <span class="alert alert-error"><?= esc($session->getFlashdata('alert_error')) ?></span>
<?php endif; ?>

            <form id="form" name="currentPageFormSubadmin" class="subscribe" method="post" action="" enctype="multipart/form-data">
              <?= csrf_field() ?>
 <div class="row">
                  <div class="col-md-5">
                     <input type="text" value="" placeholder="Enter name" name="name" class="required name form-control" id="mce-FNAME">
                  </div>
				  <div class="col-md-5">
                     <input type="email" value="" placeholder="Enter email address" name="email" class="required email form-control" id="mce-EMAIL">
                     <input type="hidden" name="Savesubsc" id="Savesubsc" value="Yes">
                  </div>
                  <div class="col-md-2">
                     <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn-black-bg">
                  </div>
                  <div id="mce-responses" class="clear">
                     <div class="response" id="mce-error-response" style="display:none"></div>
                     <div class="response" id="mce-success-response" style="display:none;"></div>
                  </div>
               </div>
            </form>
         </div> -->

<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="more_info_about_artist" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" id="artist_content">
			</div>
		</div>
	</div>
</div>
<button style="display: none;" id="openInfoModal" data-toggle="modal" data-target="#more_info_about_artist"></button>

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
<!-- </div> -->
<div class="container-fluid">
	<div class="row" id="newsletter">
		<iframe src="https://embeds.beehiiv.com/437a9a8f-46ae-4696-a9b3-36511d7da98f" data-test-id="beehiiv-embed" width="100%" height="320" frameborder="0" scrolling="no" style="border-radius: 4px; border: 2px solid #e5e7eb; margin: 0; background-color: transparent;"  sandbox="allow-scripts allow-forms allow-same-origin"></iframe>
	</div>
</div>
<footer>
	<div class="footer-menu">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-12">
					<h3 class="advertise-btn" data-toggle="modal" data-target="#advertiseModal">Advertise With Us <img src="<?= $ASSET_FRONT_URL ?>icons/arrow.svg" alt="user Login" /></span></h3>
					<ul>
						<li class="hide"><a href="javascript:void(0)" data-toggle="modal" data-target="#advertiseModal">Advertise</a></li>
						<li class="hide"><a href="<?= $BASE_URL?>submit_event">Submit</a></li>
						<li class="hide"><a href="<?= $BASE_URL?>how_to_get_hh">Subscribe</a></li>
						<li><a href="<?= $BASE_URL?>contact_us">Contact</a></li>
						<li><a href="<?= $BASE_URL?>privacy-policy">Privacy</a></li>
						<li><a href="<?= $BASE_URL?>refund-policy">Refund</a></li>
						<li><a class="advertise-btn" data-toggle="modal" data-target="#reportModal" href="javascript:void(0)">Report a problem</a></li>
						<li><a target="blank" href="https://simplecirc.com/subscriber_login/hot-house-jazz-guide">Manage Account</a></li>

						<!-- <li><a href="<?= base_url('home') ?>">Home</a></li>
						<li><a href="<?= $BASE_URL?>calendar">Calendar</a></li>
						<li class="hide"><a href="<?= $BASE_URL?>submit_event">Submit Events</a></li>
						<li class="hide"><a href="<?= $BASE_URL?>how_to_get_hh">Subscribe</a></li> -->
						<!-- <li><a href="<?= $BASE_URL?>how_to_get_hh">How to get HHJ </a></li> -->
						<!-- <li class="hide"><a href="javascript:void(0)" data-toggle="modal" data-target="#advertiseModal">Advertise With Us </a></li> -->
						<!-- <li><a href="<?= $BASE_URL?>about">About Us </a></li>
						<li><a href="<?= $BASE_URL?>contact_us">Contact Us </a></li> -->
						<!--<li><a href="<?= $BASE_URL?>previous_issue">Previous Issues </a></li>
						<li><a href="<?= $BASE_URL?>bloglist">Blogs</a></li>-->
						<!-- <li><a href="<?= $BASE_URL?>privacy-policy">Privacy Policy</a></li> 
						<li><a href="<?= $BASE_URL?>refund-policy">Refund Policy</a></li> 
						<li><a class="advertise-btn" data-toggle="modal" data-target="#reportModal" href="javascript:void(0)">Report Problem</a></li>
						<li><a target="blank" href="https://simplecirc.com/subscriber_login/hot-house-jazz-guide">Login/Manage Subscription</a></li> -->
					</ul>
				</div>
			</div>
		</div>
	</div>
</footer>
<div class="page-ending">
	<div class="container">
		<div class="col-md-12 col-12 text-center">
			<p>© Copyright. Hot House Jazz <?php echo date('Y') ?>. All Right Reserved.</p>
		</div>
	</div>
</div>

<div class="modal fade" id="advertiseModal" tabindex="-1" aria-labelledby="advertiseModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="advertiseModalForm" method="POST" action="">
			<?= csrf_field() ?>

				<div class="modal-body">
					<h2>Advertise With Us</h2>
					<?php if ($session->getFlashdata('alert_success')){ ?>
						<label class="alert alert-success" id="successMessage"><?= esc($session->getFlashdata('alert_success')) ?></label>
						<?php } ?>
					
					<?php if ($session->getFlashdata('alert_error')){ ?>
						<label class="alert alert-error"><?= esc($session->getFlashdata('alert_error')) ?></label>
						<?php } ?>
						
					<div class="row">
						<div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<input type="text" name="name" id="name" class="form-control" placeholder="Name" required>
						</div>
					</div>
					<div class="row mt-2">
						<div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<input type="text" name="venue_name" id="venue_name" class="form-control" placeholder="Business Name/Venue Name" required>
						</div>
					</div>
					<div class="row mt-2">
						<div class="form-group-innercol-lg-12 col-md-12 col-sm-12 col-xs-12">
							<input type="text" name="location_name" id="location_name_adv" class="form-control" placeholder="Location (City/State)" required>
						</div>
					</div>
					<div class="row mt-2">
						<div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<input type="text" name="phone_number" id="phone_number_adv" class="form-control " placeholder="Phone number" required>
						</div>
					</div>
					<div class="row mt-2">
						<div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
						</div>
					</div>

					<div class="row mt-2">
						<div class="form-group-inner  col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<select name="advertising_interest" id="advertising_interest" class="form-control" required>
								<option value="">Select advertising interest</option>
								<option value="Place a Banner">Place a Banner</option>
								<option value="Boost Event Post">Boost Event Post</option>
								<option value="Advertise in Magazine">Advertise in Magazine</option>
								<option value="Other">Other</option>
							</select>
						</div>
					</div>

					<div class="row mt-2">
						<div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="animation">
								<textarea name="inquiry" placeholder="Write details..." id="inquiry" rows="4" cols="50" style="width: 100%;" required></textarea>
							</div>
						</div>
					</div>
					<div class="row mt-2">
						<div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="g-recaptcha effect-16" data-sitekey="<?= esc($capcha_key) ?>" data-callback="recaptchaCallbackAdertise"></div>
							<input type="hidden" name="captcha" id="captcha_adver">
							<div id="captcha-advertise" class="" style="color: red;"></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<input type="submit" class="btn btn-danger" id="saveAdvertiseBtn" value="Submit">
					<!-- <button type="submit" class="btn btn-danger" data-dismiss="modal" id="saveAdvertiseBtn">submit</button> -->
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form id="reportModalForm" method="POST" action="">
		<?= csrf_field() ?>
			<div class="modal-content">
				<div class="modal-body">
					<h2>Report Problem</h2>
					
					<?php if ($session->getFlashdata('alert_success')){ ?>
						<label class="alert alert-success" id="successMessage"><?= esc($session->getFlashdata('alert_success')) ?></label>
						<?php } ?>
					
					<?php if ($session->getFlashdata('alert_error')){ ?>
						<label class="alert alert-error"><?= esc($session->getFlashdata('alert_error')) ?></label>
						<?php } ?>
					<div class="row">
						<div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<input type="text" name="name" id="name_report" class="form-control" placeholder="Name" required>
							<div id="report-name" class="" style="color: red;"></div>
						</div>
					</div>

					<div class="row mt-2">
						<div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<input type="text" name="email" id="email_report" class="form-control" placeholder="Email" required>
							<div id="report-email" class="" style="color: red;"></div>
						</div>
					</div>

					<div class="row mt-2">
						<div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="animation">
								<textarea name="report_problem" placeholder="Write report details..." id="report_problem" rows="4" cols="50" required></textarea>
								<div id="report-problem" class="" style="color: red;"></div>
							</div>
						</div>
					</div>
					<div class="row mt-2">
						<div class="form-group-inner col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="g-recaptcha effect-16" data-sitekey="<?= esc($capcha_key) ?>" data-callback="recaptchaCallbackReport"></div>
							<input type="hidden" name="captcha" id="captcha-report-res">
							<div id="captcha-report" class="" style="color: red;"></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<input type="submit" class="btn btn-danger" id="saveReportBtn" value="Submit">
					<!-- <button type="button" class="btn btn-danger" data-dismiss="modal" id="saveReportBtn">submit</button> -->
				</div>
			</div>
		</form>
	</div>
</div>

<button id="scrollToTopBtn" title="Go to top"><img src="<?= $ASSET_FRONT_URL ?>icons/up-arrow.svg" alt="user Login" /></button>

</script>
<!--Scroll top JS -->
<script type="text/javascript">
	let mybutton = document.getElementById("scrollToTopBtn");

	window.onscroll = function() {
		scrollFunction();
	};

	function scrollFunction() {
		if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
			mybutton.style.display = "block";
		} else {
			mybutton.style.display = "none";
		}
	}

	mybutton.addEventListener('click', function() {
		document.body.scrollTop = 0; // For Safari
		document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
	});
</script>

<script type="text/javascript">
	(function() {

		function addSubmittedClass() {
			var className = 'mc4wp-form-submitted';
			(this.classList) ? this.classList.add(className): this.className += ' ' + className;
		}

		var forms = document.querySelectorAll('.mc4wp-form');
		for (var i = 0; i < forms.length; i++) {
			(function(f) {

				// hide honeypot
				var honeypot = f.querySelector('input[name="_mc4wp_required_but_not_really"]');
				honeypot.style.display = 'none';
				honeypot.setAttribute('type', 'hidden');

				// add class on submit
				var b = f.querySelector('[type="submit"]');
				if (b.addEventListener) {
					b.addEventListener('click', addSubmittedClass.bind(f));
				} else {
					b.attachEvent('onclick', addSubmittedClass.bind(f));
				}

			})(forms[i]);
		}
	})();
</script>

<!-- ALL JS FILES HERE -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://unpkg.com/aos@2.3.0/dist/aos.js"></script>
<script type='text/javascript' src='https://s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-J3KMGPG70L"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<!--DOMPurify here -->
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.3/dist/purify.min.js"></script>
<script>
	! function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (!d.getElementById(id)) {
			js = d.createElement(s);
			js.id = id;
			js.src = "//platform.twitter.com/widgets.js";
			fjs.parentNode.insertBefore(js, fjs);
		}
	}(document, "script", "twitter-wjs");
</script>
<script type="text/javascript">
	(function() {
		var po = document.createElement('script');
		po.type = 'text/javascript';
		po.async = true;
		po.src = 'https://apis.google.com/js/plusone.js';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(po, s);
	})();
</script>

<script>
	$(".lazyimage").hide();
	setTimeout(function() {
		$('.lazyimage').each(function() {
			var $current = $(this);
			$(this).show();
		});
	}, 4000);
</script>

<!--Facebook Like Button Script------>
<script>
	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s);
		js.id = id;
		js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=136911316406581";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>
<!--Google Analytics Script------>
<script type="text/javascript">
	$(function() {
		if ($('#inp_time').length > 0) {
			var nd = new Date();
			$('#inp_time').datetimepicker({
				'dateFormat': 'yy-mm-dd',
				'defaultValue': formatDate(nd),
				'onSelect': function(data) {
					$('#inp_time').val(data);
				}
			});
		}
	});

	function formatDate(date) {
		var year = date.getFullYear(),
			month = date.getMonth() + 1, // months are zero indexed
			day = date.getDate(),
			hour = date.getHours(),
			minute = date.getMinutes(),
			second = date.getSeconds(),
			hourFormatted = hour % 12 || 12, // hour returned in 24 hour format
			month = month < 10 ? "0" + month : month,
			day = day < 10 ? "0" + day : day,
			hour = hour < 10 ? "0" + hour : hour,
			minute = minute < 10 ? "0" + minute : minute,
			morning = hour < 12 ? "am" : "pm";

		return year + "-" + month + "-" + day + " " + hour + ":" + minute;
	}
</script>

<script>
	AOS.init({
		duration: 1200,
	})
</script>
<script>
	/* TOP Menu Stick
		--------------------- */
	// var s = $("#sticker");
	// var pos = s.position();
	// console.log(" pos = ");
	// console.log(pos);
	// $(window).scroll(function() {
	// 	var windowpos = $(window).scrollTop();
	// 	if (windowpos > pos.top) {
	// 		s.addClass("stick");
	// 	} else {
	// 		s.removeClass("stick");
	// 	}
	// });
</script>
<!-- Main Slider Js -->
<script>
	if ($('#main-carousel').length) {
		jQuery("#main-carousel").owlCarousel({
			autoplay: true,
			loop: false,
			margin: 0,
			/*
			  animateOut: 'fadeOut',
			  animateIn: 'fadeIn',
			  */
			responsiveClass: true,
			autoHeight: true,
			autoplayTimeout: 7000,
			smartSpeed: 800,
			nav: true,
			dots: true,
			responsive: {
				0: {
					items: 1
				},

				600: {
					items: 1
				},

				1024: {
					items: 1
				},

				1366: {
					items: 1
				}
			}
		});
	}
</script>
<!-- Partner Slider Js -->
<!--<script>
		jQuery("#partner-carousel").owlCarousel({
		  autoplay: true,
		  loop: true,
		  margin: 20,
		   /*
		  animateOut: 'fadeOut',
		  animateIn: 'fadeIn',
		  */
		  responsiveClass: true,
		  autoHeight: true,
		  autoplayTimeout: 7000,
		  smartSpeed: 800,
		  nav: false,
		  dots:true,
		  responsive: {
			0: {
			  items: 1
			},

			600: {
			  items: 1
			},

			1024: {
			  items: 2.5
			},

			1366: {
			  items: 2.5
			}
		  }
		});
</script>-->
<script>
	if ($('#partner-carousel').length) {
		jQuery("#partner-carousel").owlCarousel({
			autoplay: true,
			loop: true,
			margin: 20,
			/*
			  animateOut: 'fadeOut',
			  animateIn: 'fadeIn',
			  */
			responsiveClass: true,
			autoHeight: true,
			autoplayTimeout: 7000,
			smartSpeed: 800,
			nav: false,
			dots: true,
			responsive: {
				0: {
					items: 1
				},

				600: {
					items: 1
				},

				1024: {
					items: 1
				},

				1366: {
					items: 1
				}
			}
		});
	}
</script>


<script type="text/javascript">
	function recaptchaCallbackAdertise() {
		var v = grecaptcha.getResponse();
		$('#captcha_adver').val(v);
	}
</script>
<script type="text/javascript">
	function recaptchaCallbackReport() {
		var v = grecaptcha.getResponse();
		$('#captcha-report-res').val(v);
	}
</script>

<script>
	$(document).ready(function() {
		$(document).on('submit', '#advertiseModalForm', function(e) {
		// $('#advertiseModalForm').submit(function(e) {
			var captchaResponse = grecaptcha.getResponse();
			// e.preventDefault();
			if (captchaResponse === "") {
				$('#captcha-advertise').text("Please complete the captcha.").show();
				e.preventDefault();
				console.log("Captcha is empty. Form submission prevented.");
				return;
			}
			console.log("Captcha completed. Form submission allowed.");

			var Name = $('#name').val();
			var venueName = $('#venue_name').val();
			var locationName = $('#location_name_adv').val();
			var phoneNumber = $('#phone_number_adv').val();
			var Email = $('#email').val();
			var advertisingInterest = $('#advertising_interest').val();
			var Inquiry = $('#inquiry').val();
			var url = '<?php echo base_url('save-advertise-detail'); ?>';
			// e.preventDefault();
			$.ajax({
				url: url,
				type: "POST",
				data: {
					name: Name,
					venue_name: venueName,
					location_name: locationName,
					phone_number: phoneNumber,
					email: Email,
					advertising_interest: advertisingInterest,
					inquiry: Inquiry,
					captcha: captchaResponse
				},
				success: function(response) {
					console.log(response);
					var jsonResponse = JSON.parse(response);
					if (jsonResponse.message) {
						$('#successMessage').text(jsonResponse.message).show();
						$('#name').val('');
						$('#venue_name').val('');
						$('#location_name_adv').val('');
						$('#phone_number_adv').val('');
						$('#email').val('');
						$('#advertising_interest').val('');
						$('#inquiry').val('');
					}
					$('#advertiseModal').modal('hide');
				},
				error: function(xhr, status, error) {
					console.error(xhr.responseText);
				}
			});
		});

	});
</script>

<script>
	$(document).ready(function() {
		$(document).on('submit', '#reportModalForm', function(event) {
			// event.preventDefault();
			console.log("AJAX request triggered"); 
			var captchaResponse = grecaptcha.getResponse();
			// if (captchaResponse === "") {
			// 	$('#captcha-report').text("Please complete the captcha.").show();
			// 	e.preventDefault();
			// 	console.log("Captcha is empty. Form submission prevented.");
			// 	return;
			// }

			var Name = $('#name_report').val();
			var Email = $('#email_report').val();
			var reportProblem = $('#report_problem').val();
			var url = '<?php echo base_url('save-report-problem'); ?>';

			$.ajax({
				url: url,
				type: "POST",
				data: {
					name: Name,
					email: Email,
					report_problem: reportProblem,
					captcha: captchaResponse

				},
				success: function(response) {
					console.log("hear is response");
					console.log(response);
					var jsonResponse = JSON.parse(response);
					if (jsonResponse.message) {
						$('#successMessage').text(jsonResponse.message).show();
						$('#name_report').val('');
						$('#email_report').val('');
						$('#report_problem').val('');

					}
					$('#reportModal').modal('hide');
				},
				error: function(xhr, status, error) {
					console.error(xhr.responseText);
				}
			});
		});
	
	});
</script>
<script>
	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();
	});
</script>