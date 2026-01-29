<?php
// if(!defined('BASEPATH')) exit('No direct script access allowed');
namespace App\Models;
use Config\Services;

use CodeIgniter\Model;
class SmsModel extends Model
{
	protected $email;
	public function __construct()
	{
		 $this->email = Services::email();
	}

	/* * *********************************************************************
	 * * Function name : sendMessageFunction 
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function use for send Message Function
	 * * Date : 08 APRIL 2021
	 * * **********************************************************************/
	public function sendMessageFunction($phone='',$message='',$type='') {
		if(!empty($phone) && !empty($message) && (SMS_SEND_STATUS == 'YES' || $type =='default')):
			$mobileno	=	$phone;
			$sentmessage=	urlencode($message);
			$authkey	=	SMS_AUTH_KEY;
			$url		=	"http://api.msg91.com/api/sendhttp.php";
			$hiturl		=	$url."?country=".SMS_COUNTRY_CODE."&sender=".SMS_SENDER."&route=4&mobiles=".$mobileno."&authkey=".$authkey."&message=".$sentmessage;	
			$ch = curl_init();
			// set URL and other appropriate options
			curl_setopt($ch, CURLOPT_URL, $hiturl);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			// grab URL and pass it to the browser
			curl_exec($ch);
			// close cURL resource, and free up system resources
			curl_close($ch);
			//$homepage = file_get_contents($hiturl);
			//return $homepage;
		endif;
	}

	public function sendEmailMessageFunction($email, $otp, $username = 'User')
	{
		if (empty($email) || empty($otp)) {
			log_message('error', 'Email or OTP missing in sendEmailMessageFunction');
			return false;
		}


		// SMTP configuration (store credentials in .env file)
		$config = [
			'protocol'    => 'smtp',
			'SMTPHost'    => getenv('MAIL_FROM_HOST') ?: 'smtp.gmail.com',
			'SMTPPort'    => getenv('MAIL_SMTP_PORT') ? (int)getenv('MAIL_SMTP_PORT') : 587,
			'SMTPUser'    => getenv('MAIL_SMTP_USER'),
			'SMTPPass'    => getenv('MAIL_SMTP_PASS'),
			'charset'     => 'utf-8',
			'mailType'    => 'html',
			'newline'     => "\r\n",
		];
				// Load email library
		$this->email->initialize($config);

		// Email details
		$fromEmail = getenv('MAIL_FROM_MAIL');
		$siteFullName = getenv('MAIL_SITE_FULL_NAME') ?: 'Your Website Name';
		$toEmail = $email;
		$subject = 'Your One-Time Password (OTP) for Password Reset';

		// Email Template
		$message = '
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>' . esc($subject) . '</title>
			<style>
				body {
					font-family: Arial, sans-serif;
					background-color: #f7f7f7;
					color: #333;
					padding: 30px;
				}
				.container {
					background: #ffffff;
					border-radius: 8px;
					box-shadow: 0 0 10px rgba(0,0,0,0.1);
					padding: 25px;
					max-width: 500px;
					margin: 0 auto;
				}
				.header {
					border-bottom: 2px solid #4CAF50;
					padding-bottom: 10px;
					margin-bottom: 20px;
				}
				.otp {
					background: #4CAF50;
					color: #fff;
					font-size: 22px;
					letter-spacing: 2px;
					text-align: center;
					padding: 10px;
					border-radius: 5px;
					width: 150px;
					margin: 20px auto;
				}
				.footer {
					font-size: 12px;
					color: #777;
					text-align: center;
					margin-top: 20px;
				}
			</style>
		</head>
		<body>
			<div class="container">
				<div class="header">
					<h2>Password Reset Request</h2>
				</div>
				<p>Hi ' . esc($username) . ',</p>
				<p>We received a request to reset your password. Please use the following One-Time Password (OTP) to complete the process:</p>
				
				<div class="otp">' . esc($otp) . '</div>

				<p>This OTP is valid for <strong>10 minutes</strong>. Please do not share it with anyone.</p>
				<p>If you didn’t request a password reset, please ignore this email.</p>

				<p>Best Regards,<br><strong>' . esc($siteFullName) . '</strong></p>

				<div class="footer">
					&copy; ' . date('Y') . ' ' . esc($siteFullName) . '. All rights reserved.
				</div>
			</div>
		</body>
		</html>
		';

		// Send Email
		$this->email->setFrom($fromEmail, $siteFullName);
		$this->email->setTo($toEmail);
		$this->email->setSubject($subject);
		$this->email->setMessage($message);

		if ($this->email->send()) {
			return true;
		} else {
			log_message('error', 'Email sending failed: ' . $this->email->printDebugger(['headers']));
			return false;
		}
	}


	/***********************************************************************
	** Function name : sendLoginOtpSmsToUser
	** Developed By : Manoj Kumar
	** Purpose  : This is use for send Login Otp Sms To User
	** Date : 08 APRIL 2021
	************************************************************************/
	function sendLoginOtpSmsToUser($mobileNumber='',$otp='') {  
		if($mobileNumber && $otp):  
			$message		=	"Your One Time Password for Login is ".$otp.".";
			$returnMessage	=	$this->sendMessageFunction($mobileNumber,$message);
			return $returnMessage;
		endif;
	}

	/***********************************************************************
	** Function name : sendForgotPasswordOtpSmsToUser
	** Developed By : Manoj Kumar
	** Purpose  : This is use for send Forgot Password Otp Sms To User
	** Date : 08 APRIL 2021
	************************************************************************/
	function sendForgotPasswordOtpSmsToUser($mobileNumber='',$otp='') {  
		if($mobileNumber && $otp):
			$message		=	"Your One Time Password for Forgot Password is ".$otp.".";
			$returnMessage	=	$this->sendMessageFunction($mobileNumber,$message);
			return $returnMessage;
		endif;
	}
	public function sendPasswordResetLink($email, $username = 'User')
	{
		if (empty($email)) {
			log_message('error', 'Email missing in sendPasswordResetLink');
			return false;
		}

		helper('text');
		$token = bin2hex(random_bytes(32));
		$expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));

		// $this->db->table('password_resets')->where('email', $email)->delete();
		// $this->db->table('password_resets')->insert([
		// 	'email' => $email,
		// 	'token' => $token,
		// 	'expires_at' => $expiresAt
		// ]);

		$config = [
			'protocol'    => getenv('MAIL_PROTOCOL') ?: 'smtp',
			'SMTPHost'    => getenv('MAIL_FROM_HOST') ?: 'sandbox.smtp.mailtrap.io',
			'SMTPPort'    => getenv('MAIL_SMTP_PORT') ? (int)getenv('MAIL_SMTP_PORT') : 587,
			'SMTPUser'    => getenv('MAIL_SMTP_USER') ?: 'd709e41c363bd6',
			'SMTPPass'    => getenv('MAIL_SMTP_PASS') ?: '22c09ea76c21fa',
			'charset'     => 'utf-8',
			'mailType'    => 'html',
			'newline'     => "\r\n",
		];

		$this->email->initialize($config);

		$baseUrl = getenv('app.baseURL') ?: base_url();
		$resetLink = $baseUrl . 'hhjsitemgmt/reset-password?token=' . urlencode($token) . '&email=' . urlencode($email);

		$subject = 'Reset Your Password - ' . getenv('MAIL_SITE_FULL_NAME');
		$siteFullName = getenv('MAIL_SITE_FULL_NAME') ?: 'Your Website';
		$fromEmail = getenv('MAIL_FROM_MAIL');

		$message = '
			<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta charset="UTF-8">
				<title>Password Reset Request</title>
				<style>
					body { font-family: Arial, sans-serif; background-color: #f7f7f7; color: #333; padding: 30px; }
					.container { background: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); padding: 25px; max-width: 500px; margin: 0 auto; }
					.header { border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
					.footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; }
				</style>
			</head>
			<body>
				<div class="container">
					<div class="header">
						<h2>Password Reset Request</h2>
					</div>
					<p>Hi ' . esc($username) . ',</p>
					<p>We received a request to reset your password. Please click the button below to reset it:</p>

					<p style="text-align: center;">
						<a href="' . esc($resetLink) . '" 
							style="background-color: #007bff; color: #ffffff; padding: 12px 24px; 
								text-decoration: none; border-radius: 5px; display: inline-block; 
								font-weight: bold;">
							Reset Password
						</a>
					</p>

					<p style="text-align: center; font-size: 14px; color: #555;">
						If the button above doesn’t work, please copy and paste this link into your browser:<br>
						<a href="' . esc($resetLink) . '" style="color: #007bff; word-break: break-all;">
							' . esc($resetLink) . '
						</a>
					</p>

					<p>If you didn’t request this, please ignore the email.</p>

					<p>Best Regards,<br><strong>' . esc($siteFullName) . '</strong></p>

					<div class="footer">
						&copy; ' . date('Y') . ' ' . esc($siteFullName) . '. All rights reserved.
					</div>
				</div>
			</body>
			</html>';


		// ✅ Step 7. Send email
		$this->email->setFrom($fromEmail, $siteFullName);
		$this->email->setTo($email);
		$this->email->setSubject($subject);
		$this->email->setMessage($message);

		if ($this->email->send()) {
			return true;
		} else {
			log_message('error', 'Email sending failed: ' . $this->email->printDebugger(['headers']));
			return false;
		}
	}

	function sendEmailForgotPasswordOtpSmsToUser($Email='',$otp='',$fullName='User') {  
		if($Email && $otp):
			$message		=	"Your One Time Password for Forgot Password is ".$otp.".";
			// $returnMessage	=	$this->sendEmailMessageFunction($Email,$message,$fullName);
			$returnMessage	=	$this->sendPasswordResetLink($Email,$fullName);
			return $returnMessage;
		endif;
	}
	/***********************************************************************
	** Function name : sendChangePasswordOtpSmsToUser
	** Developed By : Manoj Kumar
	** Purpose  : This is use for send Change Password Otp Sms To User
	** Date : 08 APRIL 2021
	************************************************************************/
	function sendChangePasswordOtpSmsToUser($mobileNumber='',$otp='') {  
		if($mobileNumber && $otp):
			$message		=	"Your One Time Password for Change Password is ".$otp.".";
			$returnMessage	=	$this->sendMessageFunction($mobileNumber,$message);
			return $returnMessage;
		endif;
	}
}	