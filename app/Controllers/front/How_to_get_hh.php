<?php
namespace App\Controllers\front;
use App\Controllers\BaseController;

use \DrewM\MailChimp\MailChimp;
use App\Models\AdminModel;
use App\Models\EmailtemplateModel;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\CommonModel;
use App\Libraries\Layouts;

class How_to_get_hh extends BaseController {

	protected $adminModel;
    protected $emailTemplateModel;
    protected $smsModel;
    protected $notificationModel;
    protected $commonModel;
	protected $session;
	protected $layouts;
    protected $validation;
	public function  __construct() 
	{	
		// parent:: __construct();
		
		// $this->adminModel = new AdminModel();
		// $this->emailTemplateModel = new EmailtemplateModel();
        // $this->smsModel = new SmsModel();
		
        // $this->notificationModel = new NotificationModel();
        $this->commonModel = new CommonModel();
	
		$this->session = session();
		$this->layouts = new Layouts();
		//error_reporting(E_ALL ^ E_NOTICE);  
		// error_reporting(0);  
	 $this->lang = service('language'); 
$this->lang->setLocale('front');
		helper('common');
		$this->validation = service('validation');
	} 
	
	/* * *********************************************************************
	 * * Function name 	: index
	 * * Developed By 	: Megha Kumari
	 * * Purpose  		: This function for home page data
	 * * Date 			: 18/01/23
	 * * **********************************************************************/
	public function index()
	{
		$request = service('request');
		$data = array();
	/********************************************Banner Section******************************/
	$where['where'] 		=	['page_name' => 'How to Get'];//"status = 'A'";
	$tbl 					=	'banner_tbl as ftable';
	$shortField 			=	'id DESC';

	$shortField1 			=	'type_name ASC';
	 $data['banner'] 		= 	 $this->commonModel->getData('multiple', $tbl,$where,$shortField,6,0);
/********************************************About Section******************************/
       $where1['where'] 		=	"is_active = '1'";//"status = 'A'";
		$tbl1 					=	'about_us_tbl as ftable';
		$shortField12 			=	'id DESC';

		$shortField123 			=	'type_name ASC';
		 $data['about'] 		= 	 $this->commonModel->getData('multiple', $tbl1,$where1,$shortField12,6,0);
		 /********************************************Our Team Section******************************/
		 $where2['where'] 		=	"is_active = '1'";//"status = 'A'";
		 $tbl2 					=	'about_team_tbl as ftable';
		 $shortField2 			=	'id DESC';
 
		 $shortField222			=	'type_name ASC';
		  $data['about_team_tbl'] 		= 	 $this->commonModel->getData('multiple', $tbl2,$where2,$shortField2,6,0);
	 
		   /********************************************Annual Subscription******************************/
		 $where3['where'] 		=	"is_active = '1'";//"status = 'A'";
		 $tbl3 					=	'get_hh_tbl as ftable';
		 $shortField3 			=	'id DESC';
 
		 $shortField2223			=	'type_name ASC';
		  $data['get_hh_tbl'] 		= 	 $this->commonModel->getData('multiple', $tbl3,$where3);
		   /********************************************Our Partners Section******************************/
	 $where4['where'] 		=	"is_active = '1'";//"status = 'A'";
	 $tbl4 					=	'slider_tbl as ftable';
	 $shortField4 			=	'id DESC';

	 $shortField4			=	'type_name ASC';
	  $data['slider_tbl'] 		= 	 $this->commonModel->getData('multiple', $tbl4,$where4);
	   /********************************************Annual Subscription Section******************************/
	 $where5['where'] 		=	"is_active = '1'";//"status = 'A'";
	 $tbl5 					=	'get_hh_tbl as ftable';
	 $shortField5 			=	'id DESC';

	 $shortField5			=	'type_name ASC';
	  $data['get_hh_tbl'] 		= 	 $this->commonModel->getData('multiple', $tbl5,$where5);
	 
	  /********************************************Seo Section******************************/
	  $where6['where'] 		=	['page_name' => 'How To Get HH'];
	  $tbl6 					=	'seo_tbl as ftable';
	  $shortField5			=	'id DESC';
	
	  $shortField7			=	'type_name ASC';
	  $data['seo_section'] 		= 	 $this->commonModel->getData('single', $tbl6,$where6);
	 
/********************************************Subscribe form******************************/
if ($request->getPost('Savesubsc')) {
	$this->validation->setRules([
		'name'  => 'required',
		'email' => 'required|valid_email'
	], [
		'name'  => ['required' => 'Enter Name'],
		'email' => ['required' => 'Enter Email Address', 'valid_email' => 'Enter a valid Email Address']
	]);

	if ($this->validation->withRequest($this->request)->run()) {
		$param = [
			'email'         => $request->getPost('email'),
			'name'          => $request->getPost('name'),
			'creation_date' => date('Y-m-d h:i:s'),
			'status'        => 'A',
			'ip_address'    => $request->getIPAddress() // Get user IP
		];

		// ✅ MailChimp API Configuration
		$api_key = getenv('MAILCHIMP_API_KEY'); // Your API Key
		$server = 'us3.'; // Extracted from API key
		$list_id = 'f15ad682db'; // Your List ID
		$auth = base64_encode('user:' . $api_key);

		$data = [
			'email_address' => $param['email'],
			'status'        => 'subscribed',
			'merge_fields'  => [
				'FNAME' => $param['name'],
				'LNAME' => ''
			],
			'msg' => 'Subscribed via my system'
		];

		$json_data = json_encode($data);

		// ✅ Send Request to MailChimp API
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://{$server}api.mailchimp.com/3.0/lists/{$list_id}/members");
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
			'Authorization: Basic ' . $auth
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

		$result = curl_exec($ch);
		curl_close($ch);

		// ✅ Check if Email Already Exists in DB
		$subscribe = $this->commonModel->subscribeEmail($param['email']);

		if (empty($subscribe)) {
			$this->commonModel->addData('subscribe_tbl', $param);
			$this->session->setFlashdata('alert_success', 'Details Submitted Successfully');
		} else {
			$this->session->setFlashdata('alert_error', 'Email ID already used');
		}

		return redirect()->to('/how_to_get_hh');
	} else {
		// ✅ If Validation Fails
		$this->session->setFlashdata('alert_error', 'Please Enter All Details');
		return redirect()->back()->withInput();
	}
}
		$this->layouts->set_title($data['seo_section']['title']);
		$this->layouts->set_description($data['seo_section']['description']);
		$this->layouts->set_keyword($data['seo_section']['keywords']);
		 $this->layouts->front_view('how_to_get_hh',array(),$data);
	}
}
	
	