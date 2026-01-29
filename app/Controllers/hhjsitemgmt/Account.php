<?php

namespace App\Controllers\hhjsitemgmt;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\EmailtemplateModel;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\CommonModel;
use App\Models\FrontModel;
use App\Libraries\Layouts;
class Account extends BaseController
{

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
		
		//error_reporting(E_ALL ^ E_NOTICE);  
		error_reporting(0);
		$this->admin_model = new AdminModel();
		$this->emailTemplateModel = new EmailtemplateModel();
        $this->sms_model = new SmsModel();
        $this->notificationModel = new NotificationModel();
		$this->common_model = new CommonModel();
		$this->lang = service('language'); 
$this->lang->setLocale('admin');
		helper(['common','general']);
		$this->layouts = new Layouts();
        $this->session = session();
	}

	/* * *********************************************************************
	 * * Function name : maindashboard
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for main dashboard
	 * * Date : 23 JUNE 2022
	 * * **********************************************************************/
	public function maindashboard()
	{
		
		$redirect = $this->admin_model->authCheck();
		// var_dump($redirect);
		// die;
		if ($redirect instanceof \CodeIgniter\HTTP\RedirectResponse) {
			return $redirect; // Stop execution if redirect happens
		}
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'';
		$data['activeSubMenu'] 				= 	'';
		/*$where['where'] 		=	"status = 'A'";
		$tbl 					=	'contact as ftable';
		$shortField2			=	'id DESC';

		$shortField1 			=	'type_name ASC';
		$data['users'] 		= 	$this->common_model->getData('multiple', $tbl,$where,$shortField2,6,0);
		
		$where['where'] 		=	"status = 'A'";
		$tbl 					=	'book_appintment as ftable';
		$shortField2			=	'id DESC';

		$shortField1 			=	'type_name ASC';
		$data['appointment'] 		= 	$this->common_model->getData('multiple', $tbl,$where,$shortField2,6,0);*/
		//echo'<pre>';
		//print_r($data['appointment']);die;
		$data['events'] = $this->common_model->totalEvents();
		$data['newEvents'] = $this->common_model->newEventsForCurrentMonth();
		$data['newVenues'] = $this->common_model->newVenuesForCurrentMonth();
		
		$trashevent = $this->common_model->totalTrashevent();
	
		$data['trashevent'] = count($trashevent);

		$data['newUsers'] = $this->common_model->newUsersForCurrentMonth();

		$newTrashEvents = $this->common_model->trashEventsCurrentMonth();
		$data['newTrashEvents'] = count($newTrashEvents);

		$data['publishevent'] = $this->common_model->totalPublishevent();
		$this->layouts->set_title('Dashboard');
		$this->layouts->admin_view('account/maindashboard', [], $data);
	}	// END OF FUNCTION

	/* * *********************************************************************
	 * * Function name : profile
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for admin profile
	 * * Date : 23 JUNE 2022
	 * * **********************************************************************/
	public function profile()
	{
		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'';
		$data['activeSubMenu'] 				= 	'';

		$whereCon['where']			 		= 	"ftable.admin_id = '" . $this->session->get('ILCADM_ADMIN_ID') . "'";
		$shortField 						= 	"ftable.admin_id ASC";

		// $this->load->library('pagination');
		$session = \Config\Services::session();
$uri = service('uri');

$config['base_url'] = $session->get('ILCADM_ADMIN_CURRENT_PATH') . $uri->getSegment(1) . '/profile';
		// $config['base_url'] 				= 	$this->session->get('ILCADM_ADMIN_CURRENT_PATH') . $this->router->fetch_class() . '/profile';
		$tblName 							= 	'admin as ftable';
		$con 								= 	'';
		$config['total_rows'] 				= 	$this->common_model->getData('count', $tblName, $whereCon, $shortField, '0', '0');
		$config['per_page']	 				= 	10;
		$config['uri_segment'] 				= 	getUrlSegment();
		// $this->pagination->initialize($config);

		// if ($this->request->getUri()->getSegment($uriSegment)) :
		// 	$page = $this->request->getUri()->getSegment($uriSegment);
		// else :
			$page = 0;
		// endif;
		// echo "<pre>";
		// print_r($shortField);
		// echo "</pre>";
		// die;
		$data['ADMINDATA'] 					= 	$this->common_model->getData('single', $tblName, $whereCon, $shortField, $config['per_page'], $page);
		

		$this->layouts->set_title('Profile');
		$this->layouts->admin_view('account/profile', array(), $data);
	}	// END OF FUNCTION

	/* * *********************************************************************
	 * * Function name : editprofile
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for admin editprofile
	 * * Date : 23 JUNE 2022
	 * * **********************************************************************/
	public function editprofile($editId = '')
	{
		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'';
		$data['activeSubMenu'] 				= 	'';

		$data['profileuserdata']			=	$this->common_model->getDataByParticularField('admin', 'admin_id', $editId);
		
		if ($data['profileuserdata'] == '') :
			redirect($this->session->get('ILCADM_ADMIN_CURRENT_PATH') . 'maindashboard');
		endif;

		if ($this->request->getPost('SaveChanges')) :
			$error							=	'NO';
			$validation = \Config\Services::validation();
			$rules = [
				'admin_title' => [
					'label' => 'Title',
					'rules' => 'trim|required|max_length[64]',
					'errors' => [
						'required' => '{field} is required.',
						'max_length' => '{field} cannot exceed 64 characters.'
					]
				],
				'admin_first_name' => [
					'label' => 'First Name',
					'rules' => 'trim|required|max_length[64]',
					'errors' => [
						'required' => '{field} is required.',
						'max_length' => '{field} cannot exceed 64 characters.'
					]
				],
				'admin_middle_name' => [
					'label' => 'Middle Name',
					'rules' => 'trim|max_length[64]',
					'errors' => [
						'max_length' => '{field} cannot exceed 64 characters.'
					]
				],
				'admin_last_name' => [
					'label' => 'Last Name',
					'rules' => 'trim|required|max_length[64]',
					'errors' => [
						'required' => '{field} is required.',
						'max_length' => '{field} cannot exceed 64 characters.'
					]
				],
				// 'admin_email' => [
				// 	'label' => 'E-Mail',
				// 	'rules' => 'trim|required|valid_email|max_length[64]|is_unique[admin.admin_email]',
				// 	'errors' => [
				// 		'required' => '{field} is required.',
				// 		'valid_email' => 'Please enter a valid email address.',
				// 		'is_unique' => 'This {field} is already registered.'
				// 	]
				// ],
				// 'admin_phone' => [
				// 	'label' => 'Mobile Number',
				// 	'rules' => 'trim|required|min_length[10]|max_length[15]|is_unique[admin.admin_phone]',
				// 	'errors' => [
				// 		'required' => '{field} is required.',
				// 		'min_length' => '{field} must be at least 10 digits long.',
				// 		'max_length' => '{field} cannot exceed 15 digits.',
				// 		'is_unique' => 'This {field} is already registered.'
				// 	]
				// ],
				'admin_address' => [
					'label' => 'Address',
					'rules' => 'trim|max_length[512]',
					'errors' => [
						'max_length' => '{field} cannot exceed 512 characters.'
					]
				],
				'admin_city' => [
					'label' => 'City',
					'rules' => 'trim'
				],
				'admin_state' => [
					'label' => 'State',
					'rules' => 'trim'
				],
				'admin_pincode' => [
					'label' => 'Zipcode',
					'rules' => 'trim'
				]
			];
	
			// Custom regex validation for mobile number
			$testmobile = str_replace(' ', '', $this->request->getPost('admin_phone'));
			if ($this->request->getPost('admin_phone') && !preg_match('/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i', $testmobile)) {
				if (!preg_match("/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/", $testmobile)) {
					$error						=	'YES';
					$data['mobileerror'] 		= 	'Please Eneter Correct Number';
					return redirect()->back()->withInput()->with('mobileerror', 'Please enter a correct number');
				}
			}
			if (!$this->validate($rules)) {
				$this->session->setFlashdata('alert_error', 'Please enter all details');
				// if (!$this->validate($rules)) {
				// 	return view('admin_form', [
				// 		'validation' => $this->validator
				// 	]);
				// } 
				return redirect()->back()->withInput()->with('validation', $validation);
			}
		

			

				$param['admin_title']				= 	addslashes($this->request->getPost('admin_title'));
				$param['admin_first_name']			= 	addslashes($this->request->getPost('admin_first_name'));
				$param['admin_middle_name']			= 	addslashes($this->request->getPost('admin_middle_name'));
				$param['admin_last_name']			= 	addslashes($this->request->getPost('admin_last_name'));
				$param['admin_email']				= 	addslashes($this->request->getPost('admin_email'));
				$param['admin_phone']				= 	(int)($this->request->getPost('admin_phone'));
				$param['admin_address']				= 	addslashes($this->request->getPost('admin_address'));
				$param['admin_city']				= 	addslashes($this->request->getPost('admin_city'));
				$param['admin_state']				= 	addslashes($this->request->getPost('admin_state'));
				$param['admin_pincode']				= 	(int)$this->request->getPost('admin_pincode');

				$param['update_ip']					=	currentIp();
				// $param['update_date']				=	(int)$this->timezone->utc_time(); //currentDateTime();
				$param['update_date']				=	currentDateTime(); 
				$param['updated_by']				=	(int)$this->session->get('ILCADM_ADMIN_ID');
				$this->common_model->editData('admin', $param, 'admin_id', (int)$this->request->getPost('CurrentDataID'));

				$result								=	$this->admin_model->Authenticate($param['admin_email']);
				if ($result) :
					$this->session->set(array(
						'ILCADM_ADMIN_TITLE'			=>	$result['admin_title'],
						'ILCADM_ADMIN_FIRST_NAME'	=>	$result['admin_first_name'],
						'ILCADM_ADMIN_MIDDLE_NAME'	=>	$result['admin_middle_name'],
						'ILCADM_ADMIN_LAST_NAME'		=>	$result['admin_last_name'],
						'ILCADM_ADMIN_EMAIL'			=>	$result['admin_email'],
						'ILCADM_ADMIN_MOBILE'		=>	$result['admin_phone'],
						'ILCADM_ADMIN_ADDRESS'		=>	$result['admin_address'],
						'ILCADM_ADMIN_CITY'			=>	$result['admin_city'],
						'ILCADM_ADMIN_STATE'			=>	$result['admin_state'],
						'ILCADM_ADMIN_ZIPCODE'		=>	$result['admin_pincode']
					));

					$this->session->setFlashdata('alert_success', lang('statictext_lang.updatesuccess'));
					return redirect()->to($this->session->get('ILCADM_ADMIN_CURRENT_PATH') . 'profile');
				endif;
			endif;
		

		$this->layouts->set_title('Edit Profile');
		$this->layouts->admin_view('account/editprofile', array(), $data);
	}	// END OF FUNCTION

	/* * *********************************************************************
	 * * Function name : changepassword
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for change password
	 * * Date : 23 JUNE 2022
	 * * **********************************************************************/
	public function changepassword($editId = '')
	{
		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'';
		$data['activeSubMenu'] 				= 	'';

		$data['EDITDATA']					=	$this->common_model->getDataByParticularField('admin', 'admin_id', (int)$editId);
		if ($data['EDITDATA'] == '') :
			redirect($this->session->get('ILCADM_ADMIN_CURRENT_PATH') . 'maindashboard');
		endif;
		
		if ($this->request->getPost('SaveChanges')) :
			$error					=	'NO';
			$validation = \Config\Services::validation();
			
 // Define validation rules
 $rules = [
	'old_password' => [
		'label' => 'Old Password',
		'rules' => 'trim'
	],
	'current_password' => [
		'label' => 'Current Password',
		'rules' => 'trim|required|min_length[6]',
		'errors' => [
			'required' => '{field} is required.',
			'min_length' => '{field} must be at least 6 characters long.',
		]
	],
	'new_password' => [
		'label' => 'New Password',
		'rules' => 'trim|required|min_length[6]|max_length[25]',
		'errors' => [
			'required' => '{field} is required.',
			'min_length' => '{field} must be at least 6 characters long.',
			'max_length' => '{field} cannot exceed 25 characters.'
		]
	],
	'conf_password' => [
		'label' => 'Confirm Password',
		'rules' => 'trim|required|min_length[6]|matches[new_password]',
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

if (!password_verify($this->request->getPost('current_password'), $data['EDITDATA']['admin_password'])) {
	$this->session->setFlashdata('alert_error', 'Old password is incorrect');
	return redirect()->back()->withInput();
}

				$param['admin_password_otp']		=	(int)'4321'; //(int)generateRandomString(4,'n');
				$this->common_model->editData('admin', $param, 'admin_id', (int)$data['EDITDATA']['admin_id']);

				$this->sms_model->sendChangePasswordOtpSmsToUser($data['EDITDATA']['admin_phone'], $param['admin_password_otp']);

				$this->session->set(array('otpType' => 'Change Password', 'otpAdminId' => $data['EDITDATA']['admin_id'], 'otpAdminMobile' => $data['EDITDATA']['admin_phone'], 'changeNewPassword' => $this->request->getPost('new_password')));

				$this->session->setFlashdata('alert_success', lang('statictext_lang.sendotptomobile') . $data['EDITDATA']['admin_phone']);
				return redirect()->to($this->session->get('ILCADM_ADMIN_CURRENT_PATH') . 'change-password-verify-otp');
			endif;
		

		$this->layouts->set_title('Change password');
		$this->layouts->admin_view('account/changepassword', array(), $data);
	}	// END OF FUNCTION

	/* * *********************************************************************
	 * * Function name : changepasswordverifyotp
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for change password verify otp
	 * * Date : 23 JUNE 2022
	 * * **********************************************************************/
	public function changepasswordverifyotp()
	{
		$this->admin_model->authCheck();
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'';
		$data['activeSubMenu'] 				= 	'';

		/*-----------------------------------change password verify otp---------------*/
		if ($this->request->getPost('SaveChanges')) :
			//Set rules
			$validation = \Config\Services::validation();
			
			// Define validation rules
			$rules = [
			   'userOtp' => [
				   'label' => 'otp',
				   'rules' => 'trim|required|max_length[4]',
					'errors' => [
						'required' => '{field} is required.',
						'min_length' => '{field} must be at least 6 characters long.',
						'max_length' => '{field} must be at least 6 characters long.'
					]
			   ]
					];
					if (!$this->validate($rules)) {
						$this->session->setFlashdata('alert_error', 'Please enter all details');
						return redirect()->back()->withInput()->with('validation', $validation);
					}
				$result		=	$this->admin_model->checkOTP((int)$this->request->getPost('userOtp'));
				if ($result) :
					$param['admin_password']		= 	password_hash(sessionData('changeNewPassword'), PASSWORD_DEFAULT);
					$param['update_ip']				=	currentIp();
					// $param['update_date']			=	(int)$this->timezone->utc_time(); //currentDateTime();
					$param['update_date']				=	currentDateTime(); 
					$param['updated_by']			=	(int)sessionData('ILCADM_ADMIN_ID');
					$this->common_model->editData('admin', $param, 'admin_id', (int)sessionData('ILCADM_ADMIN_ID'));
					$session = \Config\Services::session();
					$session->remove(['otpType', 'otpAdminId', 'otpAdminMobile', 'changeNewPassword']);
					// $this->session->unset_userdata(array('otpType', 'otpAdminId', 'otpAdminMobile', 'changeNewPassword'));

					$this->session->setFlashdata('alert_success', lang('statictext_lang.passwordchangesuccess'));
					return redirect()->to($this->session->get('ILCADM_ADMIN_CURRENT_PATH') . 'profile');
				else :
					$data['recovererror'] = lang('statictext_lang.invalidotp');
				endif;
			
		endif;

		$this->layouts->set_title('Change password - Verify OTP');
		$this->layouts->admin_view('account/changepasswordverifyotp', array(), $data);
	}	// END OF FUNCTION
}
