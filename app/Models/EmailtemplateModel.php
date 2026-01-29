<?php
// if (!defined('BASEPATH')) exit('No direct script access allowed');
namespace App\Models;

use CodeIgniter\Model;
use Config\Services;
class EmailtemplateModel extends Model
{
	protected $db;
	protected $email;
	public function __construct()
	{
		$this->db = \Config\Database::connect();
		// $this->load->database();
		 $this->email = Services::email();
	}

	/***********************************************************************
	 ** Function name : sendMailToCustomer
	 ** Developed By : Manoj Kumar
	 ** Purpose  : This function used for send Mail ToC ustomer
	 ** Date : 19 JUNE 2018
	 ************************************************************************/
	// function sendMailToCustomer($result = array(), $param = array())
	// {
	// 	if (!empty($result['admin_email_id']) && !empty($param['admin_password_otp'])) :

	// 		$fromMail  		= 	MAIL_FROM_MAIL;
	// 		$siteFullName	=	MAIL_SITE_FULL_NAME;
	// 		$toMail  		= 	$result['admin_email_id'];
	// 		$subject 		= 	"Password recovery mail!";
	// 		$sitefullurl	= 	base_url();

	// 		#.............................. message section ............................#
	// 		$MHtml 			= 	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	// 							<html xmlns="http://www.w3.org/1999/xhtml">
	// 							<head>
	// 							<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	// 							<title>' . $siteFullName . '</title>
	// 							</head>
	// 							<body>
	// 							    <p style="font-family: \'Raleway\', Verdana, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size:13px;line-height: 28px;color:#000;border-top: 1px solid #E4E4E4;border-bottom: 1px solid #E4E4E4;">
	// 								 Hi ' . $result['admin_name'] . ',
	// 								</p>
	// 								<p style="font-family: \'Raleway\', Verdana, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size:13px;line-height: 28px;color:#000;border-top: 1px solid #E4E4E4;border-bottom: 1px solid #E4E4E4;">
	// 								 User have get password recovery mail. The OTP is ' . $param['admin_password_otp'] . ' and <a href="' . $sitefullurl . 'password-recover">click here</a> for password recover.
	// 								</p>
	// 							</body>
	// 							</html>';
	// 		// echo $MHtml; die;
	// 		$this->email->setFrom($fromMail, $siteFullName);
	// 		$this->email->setTo($toMail);
	// 		$this->email->setSubject($subject);
	// 		$this->email->set_mailtype('html');
	// 		$this->email->setMessage($MHtml);
	// 		$this->email->send();
	// 	//echo $this->email->printDebugger(); die;
	// 	endif;
	// }	// END OF FUNCTION

	function sendReportProblem($param = array())
	{
		// $email = Services::email();

		$config = [
            'protocol'    => 'smtp',
            'SMTPHost'    => 'ssl://smtp.gmail.com',
            'SMTPPort'    => 465,
            'SMTPUser'    => 'auramaticstechnologies@gmail.com',
            'SMTPPass'    => 'awoxyotickctshla',  // Store this securely, e.g., in `.env`
            'charset'     => 'utf-8',
            'mailType'    => 'html',
            'newline'     => "\r\n",
        ];


		 $this->email->initialize($config);
		// $this->email->set_newline("\r\n");

		// Email content
		$fromEmail = 'webmaster@hhjazzguide.com';
		$siteFullName = 'HotHouseJazz';
		$toEmail =     'webmaster@hhjazzguide.com';    // 'taranveersingh.auramatics13@gmail.com';     
		// $toEmail =     'pooja.auramatics@gmail.com'; 
		$subject = 'Report Problem';

		$message = '<!DOCTYPE html>
                <html>
                <head>
                    <title>Email Subject</title>
                </head>
                <body>
                    <p>Hi ' . esc($param['name']) . ',</p>
					<p>Email - ' . esc($param['email']) . '</p>
					<p>Report Problem - ' . esc($param['report_problem']) . '</p>
                </body>
                </html>';
		// Set email parameters
		 $this->email->setFrom($fromEmail, $siteFullName);
         $this->email->setTo($toEmail);
         $this->email->setSubject($subject);
         $this->email->setMessage($message);

		// Send email
		if ( $this->email->send()) {
			// Email sent successfully
			return true;
		} else {
			// Email sending failed
			// echo  $this->email->printDebugger();
			log_message('error', 'Email sending failed: ' .  $this->email->printDebugger(['headers']));
            
			return false;
		}
	}


	function sendEventActive($param = array())
	{
		// echo"<pre>";print_r($param);die;
		

		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'ssl://smtp.gmail.com';
		$config['smtp_port'] = '465';
		$config['smtp_user'] = 'auramaticstechnologies@gmail.com';
		$config['smtp_pass'] = 'awoxyotickctshla';
		$config['charset'] = 'utf-8';
		$config['mailtype'] = 'html';
		$config['newline'] = "\r\n";

		$this->email->initialize($config);
	// 	$this->email->set_newline("\r\n");

		$relativePath = 'event_detail/' . $param['event_id'];
		$eventLink = base_url($relativePath);


		// Email content
		$fromEmail = 'auramaticstechnologies@gmail.com';
		$siteFullName = 'HotHouseJazz';
		$toEmail = $param['email'];
		$subject = 'Event Activated';

		$message = '<!DOCTYPE html>
                <html>
                <head>
                    <title>Email Subject</title>
                </head>
				
				<body>
                    <p>Hi ' . $param['user_first_name'] . ',</p>
                    <p>Your event "<strong>' . $param['event_title'] . '</strong>" is now active.</p>
                    <p>You can view the event details by clicking the following link:</p>
                    <p><a href="' . $eventLink . '">View Event Details</a></p>
                </body>
                </html>';

		// Set email parameters
		$this->email->setFrom($fromEmail, $siteFullName);
		$this->email->setTo($toEmail);
		$this->email->setSubject($subject);
		$this->email->setMessage($message);
		
		// Send email
		if ($this->email->send()) {
			// Email sent successfully
			
			return true;
		} else {
			// Email sending failed
			echo $this->email->printDebugger();
			
			return false;
		}
	}
	function userVerifyEmail($param = array())
	{
		

		// SMTP Configuration
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'ssl://smtp.gmail.com';
		$config['smtp_port'] = '465';
		$config['smtp_user'] = 'auramaticstechnologies@gmail.com';
		$config['smtp_pass'] = 'awoxyotickctshla';
		$config['charset'] = 'utf-8';
		$config['mailtype'] = 'html';
		$config['newline'] = "\r\n";

		$this->email->initialize($config);
	// 	$this->email->set_newline("\r\n");

		// Generate verification token
		//$verificationToken = md5(uniqid(rand(), true)); // Generate a random verification token
		//$this->common_model->updateData('user', ['verification_token' => $verificationToken], ['user_email' => $param['user_email']]);

		// Verification link
		//$verificationLink = base_url('verifyemail?token=' . $verificationToken . '&email=' . urlencode($param['user_email']));
		$verificationLink = base_url('verifyemail?email=' . urlencode($param['user_email']));

		// Email content
		$fromEmail = 'auramaticstechnologies@gmail.com';
		$siteFullName = 'HotHouseJazz';
		$toEmail = $param['user_email'];  // Send to user's email
		//$toEmail = 'pooja.auramatic@gmail.com';
		$subject = 'Verify Your Account';

		$message = '<!DOCTYPE html>
                <html>
                <head>
                    <title>Account Verification</title>
                </head>
                <body>
                    <p>Hi ' . $param['user_first_name'] . ' ' . $param['user_last_name'] . ',</p>
                    <p>Thank you for signing up with <strong>' . $siteFullName . '</strong>.</p>
                    <p>Please verify your email address by clicking the link below:</p>
                    <p><a href="' . $verificationLink . '">Verify Your Account</a></p>
                    <p>If you did not sign up, please ignore this email.</p>
                </body>
                </html>';

		// Set email parameters
		$this->email->setFrom($fromEmail, $siteFullName);
		$this->email->setTo($toEmail);
		$this->email->setSubject($subject);
		$this->email->setMessage($message);

		// Send email
		if ($this->email->send()) {
			return true;
		} else {
			echo $this->email->printDebugger();
			return false;
		}
	}

	function sendVenueactiveEmail($param = array())
	{
		// echo"<pre>";print_r($param);die;
		

		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'ssl://smtp.gmail.com';
		$config['smtp_port'] = '465';
		$config['smtp_user'] = 'auramaticstechnologies@gmail.com';
		$config['smtp_pass'] = 'awoxyotickctshla';
		$config['charset'] = 'utf-8';
		$config['mailtype'] = 'html';
		$config['newline'] = "\r\n";

		$this->email->initialize($config);
	// 	$this->email->set_newline("\r\n");

		$relativePath = 'event_detail/' . $param['event_id'];
		$eventLink = base_url($relativePath);


		// Email content
		$fromEmail = 'auramaticstechnologies@gmail.com';
		$siteFullName = 'HotHouseJazz';
		$toEmail = $param['contact_person_email'];
		//$toEmail =    'pooja.auramatic@gmail.com';      
		$subject = 'Venue Activated';

		$message = '<!DOCTYPE html>
                <html>
                <head>
                    <title>Email Subject</title>
                </head>
				
				<body>
                    <p>Hi ' . $param['contact_person_name'] . ',</p>
                    <p>Your Venue name"<strong>' . $param['location_name'] . '</strong>" is now active.</p>
                    <p>Now you can add the event by clicking the following link:</p>
                    <p><a href=" https://hothousejazzguide.com/submit_event ">Add Event</a></p>
					<p>Thank you!</p>
                </body>
                </html>';

		// Set email parameters
		$this->email->setFrom($fromEmail, $siteFullName);
		$this->email->setTo($toEmail);
		$this->email->setSubject($subject);
		$this->email->setMessage($message);
// die($message);
		// Send email
		if ($this->email->send()) {
			// Email sent successfully
			return true;
		} else {
			// Email sending failed
			echo $this->email->printDebugger();
			return false;
		}
	}

	function sendnewVenueEmail($param = array())
	{
		

		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'ssl://smtp.gmail.com';
		$config['smtp_port'] = '465';
		$config['smtp_user'] = 'auramaticstechnologies@gmail.com';
		$config['smtp_pass'] = 'awoxyotickctshla';
		$config['charset'] = 'utf-8';
		$config['mailtype'] = 'html';
		$config['newline'] = "\r\n";

		$this->email->initialize($config);
	// 	$this->email->set_newline("\r\n");
		// Email content
		$fromEmail = 'advertise@hhjazzguide.com';
		$siteFullName = 'HotHouseJazz';
		$toEmail =    'advertise@hhjazzguide.com';  
		//$toEmail =    'pooja.auramatic@gmail.com';         
		$subject = 'New Venue';

		$message = '<!DOCTYPE html>
                <html>
                <head>
                    <title>Email Subject</title>
                </head>
                <body>
               <p>Hi Admin,</p>
              <p>A new venue has been added by a user. Please review and take action.</p>
             <p>To view the details, click the button below:</p>
             <p>
             <a href="https://hothousejazzguide.com/hhjsitemgmt/adminmanagelocationsubmittedlist/index" 
              style="display: inline-block; padding: 5px 10px; color: #fff; background-color: #007bff; 
                  text-decoration: none; border-radius: 5px; font-size: 12px;">
             View Venue
            </a>
         </p>
        <p>Thank you!</p>
        </body>
                </html>';

		// Set email parameters
		$this->email->setFrom($fromEmail, $siteFullName);
		$this->email->setTo($toEmail);
		$this->email->setSubject($subject);
		$this->email->setMessage($message);

		// Send email
		if ($this->email->send()) {
			// Email sent successfully
			return true;
		} else {
			// Email sending failed
			echo $this->email->printDebugger(); // Print debug info for troubleshooting
			return false;
		}
	}
	function sendnewArtistEmail($param = array())
	{
		

		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'ssl://smtp.gmail.com';
		$config['smtp_port'] = '465';
		$config['smtp_user'] = 'auramaticstechnologies@gmail.com';
		$config['smtp_pass'] = 'awoxyotickctshla';
		$config['charset'] = 'utf-8';
		$config['mailtype'] = 'html';
		$config['newline'] = "\r\n";

		$this->email->initialize($config);
	// 	$this->email->set_newline("\r\n");
		// Email content
		$fromEmail = 'advertise@hhjazzguide.com';
		$siteFullName = 'HotHouseJazz';
		$toEmail =    'advertise@hhjazzguide.com';  
		//$toEmail =    'pooja.auramatic@gmail.com';         
		$subject = 'New Artist';

		$message = '<!DOCTYPE html>
                <html>
                <head>
                    <title>Email Subject</title>
                </head>
                <body>
               <p>Hi Admin,</p>
              <p>A new artist has been added by a user. Please review and take action.</p>
             <p>To view the details, click the button below:</p>
             <p>
             <a href="https://hothousejazzguide.com/hhjsitemgmt/adminmanageartist/index" 
              style="display: inline-block; padding: 5px 10px; color: #fff; background-color: #007bff; 
                  text-decoration: none; border-radius: 5px; font-size: 12px;">
             View Artist
            </a>
         </p>
        <p>Thank you!</p>
        </body>
                </html>';

		// Set email parameters
		$this->email->setFrom($fromEmail, $siteFullName);
		$this->email->setTo($toEmail);
		$this->email->setSubject($subject);
		$this->email->setMessage($message);

		// Send email
		if ($this->email->send()) {
			// Email sent successfully
			return true;
		} else {
			// Email sending failed
			echo $this->email->printDebugger(); // Print debug info for troubleshooting
			return false;
		}
	}

	function sendSimpleEmail($param = array())
	{
		 $this->email = Services::email();

		$emailConfig = [
			'protocol'    => 'smtp',
			'SMTPHost'    => 'ssl://smtp.gmail.com',
			'SMTPPort'    => 465,
			'SMTPUser'    => 'auramaticstechnologies@gmail.com',
			'SMTPPass'    => 'awoxyotickctshla',  // Store in .env instead
			'charset'     => 'utf-8',
			'mailType'    => 'html',
			'newline'     => "\r\n",
		];
	
		 $this->email->initialize($emailConfig);
		// $this->email->set_newline("\r\n");
		// Email content
		$fromEmail = 'advertise@hhjazzguide.com';
		$siteFullName = 'HotHouseJazz';
		$toEmail =    'advertise@hhjazzguide.com';  //'taranveersingh.auramatics13@gmail.com';          
		$subject = 'Advertise Details';

		$message = '<!DOCTYPE html>
                <html>
                <head>
                    <title>Email Subject</title>
                </head>
                <body>
                    <p>Hi ' . $param['name'] . ',</p>
                    <p>Venue - ' . $param['venue_name'] . '</p>
					<p>location - ' . $param['location_name'] . '</p>
					<p>Email - ' . $param['email'] . '</p>
					<p>Phone -  ' . $param['phone_number'] . '</p>
					<p>Advertise Interest - ' . $param['advertising_interest'] . '</p>
					<p>Details - ' . $param['inquiry'] . '</p>
                </body>
                </html>';

		// Set email parameters
		 $this->email->setFrom($fromEmail, $siteFullName);
		 $this->email->setTo($toEmail);
		 $this->email->setSubject($subject);
		 $this->email->setMessage($message);

		// Send email
		if ( $this->email->send()) {
			// Email sent successfully
			return true;
		} else {
			// Email sending failed
			echo  $this->email->printDebugger(['headers']); // Print debug info for troubleshooting
			return false;
		}
	}
	/***********************************************************************
	 ** Function name : sendMailToAdmin
	 ** Developed By : Manoj Kumar
	 ** Purpose  : This function used for send Mail To Admin
	 ** Date : 19 JUNE 2018
	 ************************************************************************/
	function sendMailToAdmin($result = array(), $param = array())
	{
		if (!empty($result['admin_email_id']) && !empty($param['admin_password_otp'])) :

			$fromMail  		= 	MAIL_FROM_MAIL;
			$siteFullName	=	MAIL_SITE_FULL_NAME;
			$toMail  		= 	$result['admin_email_id'];
			$subject 		= 	"Password recovery mail!";
			$sitefullurl	= 	base_url();

			#.............................. message section ............................#
			$MHtml 			= 	'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
								<html xmlns="http://www.w3.org/1999/xhtml">
								<head>
								<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
								<title>' . $siteFullName . '</title>
								</head>
								<body>
								    <p style="font-family: \'Raleway\', Verdana, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size:13px;line-height: 28px;color:#000;border-top: 1px solid #E4E4E4;border-bottom: 1px solid #E4E4E4;">
									 Hi ' . $result['admin_name'] . ',
									</p>
									<p style="font-family: \'Raleway\', Verdana, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size:13px;line-height: 28px;color:#000;border-top: 1px solid #E4E4E4;border-bottom: 1px solid #E4E4E4;">
									 User have get password recovery mail. The OTP is ' . $param['admin_password_otp'] . ' and <a href="' . $sitefullurl . 'password-recover">click here</a> for password recover.
									</p>
								</body>
								</html>';
			//echo $MHtml; die;
			$this->email->setFrom($fromMail, $siteFullName);
			$this->email->setTo($toMail);
			$this->email->setSubject($subject);
			$this->email->set_mailtype('html');
			$this->email->setMessage($MHtml);
			$this->email->send();
		//echo $this->email->printDebugger(); die;
		endif;
	}	// END OF FUNCTION
}
