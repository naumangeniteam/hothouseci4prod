<?php

namespace App\Controllers\hhjsitemgmt;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\EmailtemplateModel;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\CommonModel;
use App\Libraries\Layouts;
// use App\Libraries\Layouts;

class Login extends BaseController {

	protected $admin_model;
    protected $emailTemplateModel;
    protected $sms_model;
    protected $notificationModel;
	protected $common_model;
	protected $session;
	protected $layouts;
	protected $lang;
	public function  __construct() 
	{ 
		
		error_reporting(E_ALL ^ E_NOTICE);  
		// error_reporting(0);  
		$this->admin_model = new AdminModel();
		$this->emailTemplateModel = new EmailtemplateModel();
        $this->sms_model = new SmsModel();
        $this->notificationModel = new NotificationModel();
		$this->common_model = new CommonModel();
	 	$this->lang = service('language'); 
		$this->lang->setLocale('admin');
		helper(['common','form', 'url','general','cookie']);
		$this->layouts = new Layouts();
        $this->session = session();
	} 
	
	/* * *********************************************************************
	 * * Function name : login
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function for login
	 * * Date : 23 JUNE 2022
	 * * **********************************************************************/
	public function index()
	{
				// if ($this->session->get('ILCADM_ADMIN_ID')) {
				// 	return redirect()->to($this->session->get('ILCADM_ADMIN_CURRENT_PATH') . 'maindashboard');
				// }
		if ($this->session->get('ILCADM_ADMIN_ID')) {
			$path = $this->session->get('ILCADM_ADMIN_CURRENT_PATH');

			// Ensure the path ends with a slash
			if (!str_ends_with($path, '/')) {
				$path .= '/';
			}
			return redirect()->to($path . 'maindashboard');
		}

		
		$data['error'] 						= 	'';
		
		/*-----------------------------------Login ---------------*/
		if($this->request->getMethod() == 'POST' && $this->request->getPost('loginFormSubmit') == "Yes"):	
			
			//Set rules
			$validation = \Config\Services::validation();
		
			// Set validation rules
			$validation->setRules([
				'userEmail' => 'required|valid_email',
				'userPassword' => 'required'
			]);
			
			
			if ($validation->withRequest($this->request)->run()) {
				
				$result = $this->admin_model->Authenticate($this->request->getPost('userEmail'));

				if ($result) {
					if(!password_verify($this->request->getPost('userPassword'), $result['admin_password'])):
						$data['error'] = lang('statictext_lang.invalidpassword');
					// if($this->admin_model->decryptsPassword($result['admin_password']) != $this->request->getPost('userPassword')):
					// 	$data['error'] = lang('statictext_lang.invalidpassword');
					elseif($result['status'] != 'A'):	
						$data['error'] = lang('statictext_lang.accountblock');
					else:	
						$loginParam['admin_id']				=	(int)$result['admin_id'];
						$loginParam['admin_token']			=	generateToken();
						$loginParam['login_status']			=	'Login';
						// $loginParam['login_datetime']		=	(int)$this->timezone->utc_time();//currentDateTime(); // in ci3
						$loginParam['login_datetime']		=	currentDateTime();
						$loginParam['login_ip']				=	currentIp();
						
						$logininsertId						=	$this->common_model->addData('admin_login_log',$loginParam);
						
						$currentPath 		=	getCurrentBasePath().'/hhjsitemgmt/';
						$this->session->set([
						'ILCADM_ADMIN_LOGGED_IN'		=>	true,
						'ILCADM_ADMIN_ID'				=>	$result['admin_id'],
						'ILCADM_ADMIN_ROLE'				=>	$result['role'],
						'ILCADM_ADMIN_ROLE_ID'			=>	$result['role_id'],
						'ILCADM_ADMIN_TITLE'			=>	$result['admin_title'],
						'ILCADM_ADMIN_FIRST_NAME'		=>	$result['admin_first_name'],
						'ILCADM_ADMIN_MIDDLE_NAME'		=>	$result['admin_middle_name'],
						'ILCADM_ADMIN_LAST_NAME'		=>	$result['admin_last_name'],
						'ILCADM_ADMIN_EMAIL'			=>	$result['admin_email'],
						'ILCADM_ADMIN_MOBILE'			=>	$result['admin_phone'],
						'ILCADM_ADMIN_IMAGE'			=>	$result['admin_image'],
						'ILCADM_ADMIN_ADDRESS'			=>	$result['admin_address'],
						'ILCADM_ADMIN_CITY'				=>	$result['admin_city'],
						'ILCADM_ADMIN_STATE'			=>	$result['admin_state'],
						'ILCADM_ADMIN_COUNTRY'			=>	$result['admin_country'],
						'ILCADM_ADMIN_ZIPCODE'			=>	$result['admin_pincode'],
						'ILCADM_ADMIN_TYPE'				=>	$result['admin_type'],
						'ILCADM_ADMIN_CURRENT_PATH'		=>	$currentPath,
						'ILCADM_ADMIN_USER_TYPE'		=>	'',
						'ILCADM_ADMIN_LAST_LOGIN'		=>	$result['last_login_date'].' ('.$result['last_login_ip'].')'
                    	]);
						setcookie('ILCADM_ADMIN_LOGIN_TOKEN', $loginParam['admin_token'], time() + (60 * 60 * 24 * 100), '/');
					helper('cookie');
					$referencePage = get_cookie('ILCADM_ADMIN_REFERENCE_PAGES');
					log_message('info', "currentpath: $currentPath");
					if (!empty($referencePage)) {
						return redirect()->to(base_url($referencePage));
					} else {
						log_message("debug","redirect to currentpath/maindahsboard");
						return redirect()->to($currentPath . 'maindashboard');
					}
                	endif;
				} else {
					$data['error'] = lang('statictext_lang.invalidlogindetails');
				}
			} else {
				$data['error'] = $validation->getErrors();
			}
		endif;
		
		$this->layouts->set_title('Login');
		$this->layouts->admin_view('account/login',[],$data,'login');
	}

	/* * *********************************************************************
	 * * Function name : loginverifyotp
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for admin password recover
	 * * Date : 23 JUNE 2022
	 * * **********************************************************************/
	//spublic function loginverifyotp()
	// {	
	// 	if($this->session->get('ILCADM_ADMIN_ID')) redirect($this->session->get('ILCADM_ADMIN_CURRENT_PATH').'maindashboard');
	// 	$data['error'] 						= 	'';
	// 	$data['recovererror'] 				=	''; 

	// 	/*-----------------------------------recover password ---------------*/
	// 	if($this->request->getPost('otpVerificationFormSubmit')):	
	// 		//Set rules
	// 		$this->form_validation->set_rules('userOtp', 'otp', 'trim|required|min_length[4]|max_length[4]');
			
	// 		if($this->form_validation->run()):	
	// 			$result		=	$this->admin_model->checkOTP($this->request->getPost('userOtp'));
	// 			if($result): 
	// 				$this->session->unset_userdata(array('otpType','otpAdminId','otpAdminMobile'));

	// 				$param['last_login_ip']				=	currentIp();
	// 				$param['last_login_date']			=	(int)$this->timezone->utc_time();//currentDateTime();
	// 				$param['admin_password_otp']		=	'';
	// 				$this->common_model->editData('admin',$param,'admin_id',$result['admin_id']);

	// 				############	LOGOUT IN PRIVIOUS SYSTEM 	#######
	// 				/*
	// 				$logoutParam['admin_token']			=	'';
	// 				$logoutParam['login_status']		=	'Logout';
	// 				$logoutParam['logout_datetime']		=	(int)$this->timezone->utc_time();//currentDateTime();
	// 				$logoutParam['logout_ip']			=	currentIp();

	// 				$logoutuWhere['login_status']		=	'Login';
	// 				$logoutuWhere['admin_id']			=	(int)$result['admin_id'];
	// 				$this->common_model->editDataByMultipleCondition('ILCADM_admin_login_log',$logoutParam,$logoutuWhere);	
	// 				*/
	// 				############	LOGIN IN NEW SYSTEM 	############
	// 				$loginParam['admin_id']				=	(int)$result['admin_id'];
	// 				$loginParam['admin_token']			=	generateToken();
	// 				$loginParam['login_status']			=	'Login';
	// 				$loginParam['login_datetime']		=	(int)$this->timezone->utc_time();//currentDateTime();
	// 				$loginParam['login_ip']				=	currentIp();
	// 				$logininsertId						=	$this->common_model->addData('admin_login_log',$loginParam);

	// 				$currentPath 		=	getCurrentBasePath().'admin/';
	// 				$this->session->set(array(
	// 									'ILCADM_ADMIN_LOGGED_IN'		=>	true,
	// 									'ILCADM_ADMIN_ID'				=>	$result['admin_id'],
	// 									'ILCADM_ADMIN_TITLE'			=>	$result['admin_title'],
	// 									'ILCADM_ADMIN_FIRST_NAME'		=>	$result['admin_first_name'],
	// 									'ILCADM_ADMIN_MIDDLE_NAME'		=>	$result['admin_middle_name'],
	// 									'ILCADM_ADMIN_LAST_NAME'		=>	$result['admin_last_name'],
	// 									'ILCADM_ADMIN_EMAIL'			=>	$result['admin_email'],
	// 									'ILCADM_ADMIN_MOBILE'			=>	$result['admin_phone'],
	// 									'ILCADM_ADMIN_IMAGE'			=>	$result['admin_image'],
	// 									'ILCADM_ADMIN_ADDRESS'			=>	$result['admin_address'],
	// 									'ILCADM_ADMIN_CITY'				=>	$result['admin_city'],
	// 									'ILCADM_ADMIN_STATE'			=>	$result['admin_state'],
	// 									'ILCADM_ADMIN_COUNTRY'			=>	$result['admin_country'],
	// 									'ILCADM_ADMIN_ZIPCODE'			=>	$result['admin_pincode'],
	// 									'ILCADM_ADMIN_TYPE'				=>	$result['admin_type'],
	// 									'ILCADM_ADMIN_CURRENT_PATH'		=>	$currentPath,
	// 									'ILCADM_ADMIN_USER_TYPE'		=>	'',
	// 									'ILCADM_ADMIN_LAST_LOGIN'		=>	$result['last_login_date'].' ('.$result['last_login_ip'].')'));

	// 				setcookie('ILCADM_ADMIN_LOGIN_TOKEN',$loginParam['admin_token'],time()+60*60*24*100,'/');

	// 				if($_COOKIE['ILCADM_ADMIN_REFERENCE_PAGES']):
	// 					redirect(base_url().$_COOKIE['ILCADM_ADMIN_REFERENCE_PAGES']);
	// 				else:
	// 					redirect($currentPath.'maindashboard');
	// 				endif;
	// 			else:
	// 				$data['recovererror'] = lang('statictext_lang.invalidotp');
	// 			endif;
	// 		endif;
	// 	endif;
		
	// 	$this->layouts->set_title('Password Recover');
	// 	$this->layouts->admin_view('account/loginverifyotp',array(),$data,'login');
	// }	// END OF FUNCTION

	/* * *********************************************************************
	 * * Function name : resendotp
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for resend otp
	 * * Date : 23 JUNE 2022
	 * * **********************************************************************/
	public function resendotp()
	{	
		if(sessionData('otpType') && sessionData('otpAdminId') && sessionData('otpAdminMobile')):
			$param['admin_password_otp']	=	(int)'4321';//(int)generateRandomString(4,'n');
			$this->common_model->editData('admin',$param,'admin_id',(int)sessionData('otpAdminId'));

			if(sessionData('otpType') == 'Login'):
				$this->sms_model->sendLoginOtpSmsToUser(sessionData('otpAdminMobile'),$param['admin_password_otp']);
			elseif(sessionData('otpType') == 'Forgot Password'):
				$this->sms_model->sendForgotPasswordOtpSmsToUser(sessionData('otpAdminMobile'),$param['admin_password_otp']);
			elseif(sessionData('otpType') == 'Change Password'):
				$this->sms_model->sendChangePasswordOtpSmsToUser(sessionData('otpAdminMobile'),$param['admin_password_otp']);
			endif;

			$this->session->setFlashdata('alert_success',lang('statictext_lang.sendotptomobile').sessionData('otpAdminMobile'));
		endif;
		return redirect()->to($_SERVER['HTTP_REFERER']);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : forgotpassword
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for admin forgot password
	 * * Date : 23 JUNE 2022
	 * * **********************************************************************/
	public function forgotpassword()
	{	
		if($this->session->get('ILCADM_ADMIN_ID')) redirect($this->session->get('ILCADM_ADMIN_CURRENT_PATH').'maindashboard');
		$data['error'] 						= 	'';
		$data['forgoterror'] 				= 	'';
		$data['forgotsuccess'] 				= 	'';
		/*-----------------------------------Forgot password ---------------*/
		
		if($this->request->getPost('recoverformSubmit')):	
			
				$validation = \Config\Services::validation();

        // Set validation rules
        $rules = [
            // 'forgotMobile' => [
            //     'label' => 'Mobile',
            //     'rules' => 'trim|required|min_length[10]|max_length[12]|numeric',
            //     'errors' => [
            //         'required' => 'The {field} number is required.',
            //         'min_length' => '{field} must be exactly 10 digits long.',
            //         'max_length' => '{field} must be exactly 12 digits long.',
            //         'numeric' => '{field} must contain only numbers.'
            //     ]
            // ]
			'forgotEmail' => [
                'label' => 'Email',
                'rules' => 'trim|required|valid_email',
                'errors' => [
                    'required' => 'The {field} is required.',
                    'valid_email' => 'Enter a valid Email Address.',
                ]
            ]
        ];

       
            if (!$this->validate($rules)) {
                $this->session->setFlashdata('alert_error', 'Please enter all details');
       			 return redirect()->to('forgot-password')->with('validation', $validation);
            }
       

				$result		=	$this->common_model->getDataByParticularField('admin','admin_email',$this->request->getPost('forgotEmail'));
				
				if($result):
					if($result['status'] != 'A'):	
						$data['forgoterror'] = lang('statictext_lang.accountblock');	
					else:
						$param['admin_password_otp']		=	(int)'4321';//(int)generateRandomString(4,'n');
						$this->common_model->editData('admin',$param,'admin_id',(int)$result['admin_id']);

						//$this->sms_model->sendForgotPasswordOtpSmsToUser($result['admin_phone'],$param['admin_password_otp']);
						$fullName = $result['admin_first_name'].' '.$result['admin_last_name'];
						$this->sms_model->sendEmailForgotPasswordOtpSmsToUser($result['admin_email'],$param['admin_password_otp'],$fullName);


						$this->session->set(array('otpType'=>'Forgot Password','otpAdminId'=>$result['admin_id'],'otpAdminMobile'=>$result['admin_phone']));

						// $this->session->setFlashdata('alert_success',lang('statictext_lang.sendotptomobile').$result['admin_phone']);
					
						$this->session->setFlashdata('alert_success',lang('statictext_lang.sendforgotpassmail').$result['admin_email']);
						return redirect()->to(getCurrentBasePath().'/hhjsitemgmt');
					endif;
				else:
					$data['forgoterror'] = lang('statictext_lang.invalidemail');
				endif;
			endif;
		
		
		$this->layouts->set_title('Forgot Password');
		$this->layouts->admin_view('account/forgotpassword',array(),$data,'login');
	}	// END OF FUNCTION

	public function resetPassword()
    {
        $token = $this->request->getGet('token');
        $email = $this->request->getGet('email');

        if (!$token || !$email) {
            return redirect()->to('/')->with('error', 'Invalid password reset link.');
        }

        // // Validate token & email
        // $reset = $this->db->table('password_resets')
        //     ->where(['token' => $token, 'email' => $email])
        //     ->get()->getRowArray();

        // if (!$reset || strtotime($reset['expires_at']) < time()) {
        //     return redirect()->to('/')->with('error', 'Reset link has expired or is invalid.');
        // }
		return redirect()->to(getCurrentBasePath() . "/hhjsitemgmt/password-recover?token={$token}&email={$email}");

        // return view('auth/reset_password', [
        //     'token' => $token,
        //     'email' => $email
        // ]);
    }

    // Update Password (by token + email)
    public function updatePassword()
    {

        $token = $this->request->getPost('token');
        $email = $this->request->getPost('userEmail');
        $password = $this->request->getPost('userPassword');
        $confirm = $this->request->getPost('userConfPassword');
        if (!$token || !$email) {
            return redirect()->back()->with('error', 'Invalid password reset request.');
        }

        if ($password !== $confirm) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        // $reset = $this->db->table('password_resets')
        //     ->where(['token' => $token, 'email' => $email])
        //     ->get()->getRowArray();

        // if (!$reset || strtotime($reset['expires_at']) < time()) {
        //     return redirect()->to('/')->with('error', 'Invalid or expired reset link.');
        // }

        // Update user password
        // $userModel = new UserModel();
        // $userModel->where('email', $email)
        //     ->set(['password' => password_hash($password, PASSWORD_DEFAULT)])
        //     ->update();

		$param = [];
		$param['admin_password'] = password_hash($password, PASSWORD_DEFAULT);

		$success = $this->common_model->editData('admin',$param,'admin_email',$email);
		if($success) {
			$this->session->setFlashdata('alert_success', 'Password changed successfully. Please login with your new password.');
			return redirect()->to('/hhjsitemgmt/login');
		} else {
			$this->session->setFlashdata('alert_error', 'Failed to change password. Please try again.');
			return redirect()->back()->withInput();
		}

    }

	/* * *********************************************************************
	 * * Function name : passwordrecover
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for admin password recover
	 * * Date : 23 JUNE 2022
	 * * **********************************************************************/
	public function passwordrecover()
	{	
		if($this->session->get('ILCADM_ADMIN_ID')) redirect($this->session->get('ILCADM_ADMIN_CURRENT_PATH').'maindashboard');
		$data['error'] 						= 	'';
		$data['recovererror'] 				= 	'';
		$data['forgotsuccess'] 				= 	'';
		$data['recoversuccess'] 				= 	'';

		/*-----------------------------------recover password ---------------*/
		if($this->request->getPost('passwordRecoverFormSubmit')):	
			//Set rules
			$validation = \Config\Services::validation();
			$rules = [
				'userOtp' => [
					'label' => 'OTP',
					'rules' => 'trim|required|min_length[4]|max_length[4]|numeric',
					'errors' => [
						'required' => 'The {field} is required.',
						'min_length' => '{field} must be exactly 4 digits long.',
						'max_length' => '{field} must be exactly 4 digits long.',
						'numeric' => '{field} must contain only numbers.'
					]
				],
				'userPassword' => [
					'label' => 'New Password',
					'rules' => 'trim|required|min_length[6]|max_length[25]',
					'errors' => [
						'required' => '{field} is required.',
						'min_length' => '{field} must be at least 6 characters long.',
						'max_length' => '{field} cannot exceed 25 characters.'
					]
				],
				'userConfPassword' => [
					'label' => 'Confirm Password',
					'rules' => 'trim|required|min_length[6]|matches[userPassword]',
					'errors' => [
						'required' => '{field} is required.',
						'min_length' => '{field} must be at least 6 characters long.',
						'matches' => '{field} must match the New Password.'
					]
				]
			];
			if (!$this->validate($rules)) {
				$this->session->setFlashdata('alert_error', 'Please enter all details');
				return redirect()->back()->withInput()->with('validation', $validation);
			}
				$result		=	$this->admin_model->checkOTP($this->request->getPost('userOtp'));
				if($result):
					// $this->session->unset_userdata(array('otpType','otpAdminId','otpAdminMobile'));
					$session = \Config\Services::session();
					$session->remove(['otpType', 'otpAdminId', 'otpAdminMobile']);
					
					$param['admin_password'] = password_hash($this->request->getPost('userPassword'), PASSWORD_DEFAULT);
					$param['admin_password_otp']	=	'';
					$this->common_model->editData('admin',$param,'admin_id',(int)$result['admin_id']);
							
					$this->session->setFlashdata('alert_success',lang('statictext_lang.passrecoversuccess'));
					return redirect()->to(getCurrentBasePath().'/hhjsitemgmt/login');
				else:
					$data['recovererror'] = lang('statictext_lang.invalidotp');
				endif;
			endif;

		if($_GET['token'] && $_GET['email']):
			$data['token'] = $_GET['token'];
			$data['email'] = $_GET['email'];
		endif;

		$this->layouts->set_title('Password Recover');
		$this->layouts->admin_view('account/passwordrecover',array(),$data,'login');
	}	// END OF FUNCTION
	
	/***********************************************************************
	** Function name : logout
	** Developed By : Manoj Kumar
	** Purpose  : This function used for logout
	** Date : 23 JUNE 2022
	************************************************************************/
	public function logout()
{
	 helper('cookie');
    
    // Get cookie value properly using the helper
    $adminToken = get_cookie('ILCADM_ADMIN_LOGIN_TOKEN') ?? '';
	
    // Prepare the logout parameters
    $logoutParam = [
        'admin_token'     => '',
        'login_status'    => 'Logout',
        'logout_datetime' => currentDateTime(),
        'logout_ip'       => currentIp()
    ];
	
    // Prepare the condition for updating the record
    $logoutuWhere = [
        'login_status' => 'Login',
        'admin_token'  => $adminToken,
        'admin_id'     => (int) $this->session->get('ILCADM_ADMIN_ID')
    ];
	
    // Update the admin_login_log table
    $this->common_model->editDataByMultipleCondition('admin_login_log', $logoutParam, $logoutuWhere);
	
    // Clear cookies
    // set_cookie('ILCADM_ADMIN_LOGIN_TOKEN', '', time() - 60 * 60 * 24 * 100, '/');
    // set_cookie('ILCADM_ADMIN_REFERENCE_PAGES', '', time() - 60 * 60 * 24 * 100, '/');

	delete_cookie('ILCADM_ADMIN_LOGIN_TOKEN');
	delete_cookie('ILCADM_ADMIN_REFERENCE_PAGES');

    // Unset session variables
    $this->session->remove([
        'otpType', 'otpAdminId', 'otpAdminMobile', 'changeNewPassword',
        'ILCADM_ADMIN_LOGGED_IN', 'ILCADM_ADMIN_ID', 'ILCADM_ADMIN_TITLE',
        'ILCADM_ADMIN_FIRST_NAME', 'ILCADM_ADMIN_MIDDLE_NAME', 'ILCADM_ADMIN_LAST_NAME',
        'ILCADM_ADMIN_EMAIL', 'ILCADM_ADMIN_MOBILE', 'ILCADM_ADMIN_IMAGE',
        'ILCADM_ADMIN_ADDRESS', 'ILCADM_ADMIN_CITY', 'ILCADM_ADMIN_STATE',
        'ILCADM_ADMIN_COUNTRY', 'ILCADM_ADMIN_ZIPCODE', 'ILCADM_ADMIN_TYPE',
        'ILCADM_ADMIN_CURRENT_PATH', 'ILCADM_ADMIN_USER_TYPE', 'ILCADM_ADMIN_LAST_LOGIN'
    ]);

    // Destroy session
    $this->session->destroy();

    // Redirect to login
    return redirect()->to(getCurrentBasePath() . '/hhjsitemgmt/login');
}
	// END OF FUNCTION
}