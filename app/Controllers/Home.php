<?php
namespace App\Controllers;
// 

use \DrewM\MailChimp\MailChimp;

use App\Models\AdminModel;
use App\Models\EmailtemplateModel;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\CommonModel;
use App\Models\FrontModel;
use App\Libraries\Layouts;

use App\Libraries\Elastichh;
class Home extends BaseController
{

	protected $adminModel;
    protected $emailTemplateModel;
    protected $smsModel;
    protected $notificationModel;
    protected $commonModel;
    protected $elastichh;
	protected $frontModel;
	protected $session;
	protected $layouts;
	protected $lang;
	public function  __construct()
	{
		
		// 
		// $this->adminModel = new AdminModel();
		$this->frontModel = new FrontModel();
		$this->session = session();

        $this->emailTemplateModel = new EmailtemplateModel();
        // $this->smsModel = new SmsModel();
        // $this->notificationModel = new NotificationModel();
        $this->commonModel = new CommonModel();

        // Load Library
        $this->elastichh = new Elastichh(); // Custom Library

        // Load Helper
        helper('common','url'); 
        // Load Language
        // $this->lang = \Config\Services::language();
        $this->lang = service('language'); 
        $this->lang->setLocale('front');

		error_reporting(E_ALL ^ E_NOTICE);  
		error_reporting(0);
		// $this->load->model(array('admin_model', 'emailTemplateModel', 'sms_model', 'notification_model', 'common_model'));
		// $this->lang->load('statictext', 'front', 'email');
		// helper('common');
		// // $this->load->library('elastichh');
		$this->layouts = new Layouts();
	}
	public function index()
	{
		$data = [];
		
		/******************************************** Banner Section ******************************/
		$where = ['where' => ['page_name' => 'Home Page', 'is_active' => '1']];
		$data['banner'] = $this->commonModel->getData('multiple', 'banner_tbl', $where, 'id DESC', 1, 0);
		
	
        /******************************************** About Section ******************************/
        $data['about'] = $this->commonModel->getData('multiple', 'about_us_tbl', ['is_active' => '1'], 'id DESC', 6, 0);
        
        /******************************************** Our Team Section ******************************/
        $data['about_team_tbl'] = $this->commonModel->getData('multiple', 'about_team_tbl', ['is_active' => '1'], 'id DESC', 6, 0);
        
        /******************************************** Img Section ******************************/
        $data['home_image'] = $this->commonModel->getData('multiple', 'home_image', ['is_active' => '1']);
        
        /******************************************** Seo Section ******************************/
        $data['seo_section'] = $this->commonModel->getData('single', 'seo_tbl', ['page_name' => 'Calendar Page', 'is_active' => '1']);
        
        /******************************************** Location ******************************/
        $data['location_tbl'] = $this->commonModel->getLastOrderByFields1('multiple', 'position', 'venue_tbl', 'is_active', '1', 'id, venue_title, image');
        
        /******************************************** Venue ******************************/
        $data['venue_tbl'] = $this->commonModel->getData('multiple', 'event_location_tbl', ['is_active' => '1'], 'location_name ASC');
        
        /******************************************** Our Partners Section ******************************/
      
		
		$where2 = ['where' => ['is_active' => 1, 'page' => 1]];
       $data['slider_tbl'] = $this->commonModel->getData('multiple', 'slider_tbl', $where2, 'order ASC');

      

        /******************************************** Artist ******************************/
        $data['artist_name'] = $this->frontModel->event_artist();
        $data['event_tags'] = $this->frontModel->event_artist1();
        $data['get_event_sponsored'] = $this->frontModel->get_event_sponsored($this->request->getPost());
	
        $data['get_event_featured'] = $this->frontModel->get_event_featured($this->request->getPost());

        $dateSelected = date('Y-m-d');
        $data['event_tbl'] = $this->frontModel->get_eventsBySearch($dateSelected, $this->request->getPost());
		
		 //done
		// foreach ($data['event_tbl'] as &$event) {
		// 	$event_id = $event['event_id'];

		// 	$this->db->select('*');
		// 	$this->db->from('event_tags_tbl');
		// 	$this->db->where('event_id', $event_id);
		// 	$event_tag_query = $this->db->get();

		// 	$events_data = $event_tag_query->result_array();
		// 	$event_tag_names = [];
		// 	foreach ($events_data as $event_data) {
		// 		$event_tag_names[] = $event_data['event_tags'];
		// 	}
		// 	$event_tag_names = array_unique($event_tag_names);

		// 	$event['event_tags'] = $event_tag_names;
		// }
		
		foreach ($data['event_tbl'] as &$event) {
            $event['event_tags'] = array_column($this->commonModel->getData('multiple', 'event_tags_tbl', ['event_id' => $event['event_id']]), 'event_tags');
        }
		
		/********************************************Subscribe form******************************/
		if ($this->request->getPost('Savesubsc')) {
            $validationRules = [
                'email' => 'required|valid_email',
                'name'  => 'required'
            ];

            if (!$this->validate($validationRules)) {
                $this->session->setFlashdata('alert_error', 'Please enter all details');
                return redirect()->to('/');
            }

            $param = [
                'email'         => $this->request->getPost('email'),
                'name'          => $this->request->getPost('name'),
                'creation_date' => date('Y-m-d H:i:s'),
                'status'        => 'A',
                'ip_address'    =>$this->request->getIPAddress()
            ];

            // Mailchimp API integration
            $email      = $param['email'];
            $first_name = $param['name'];
            $last_name  = '';

            $api_key  = getenv("MAILCHIMP_API_KEY"); // Your API Key
			if (!$api_key) {
				$this->session->setFlashdata('alert_error', 'API Key not set');
				return redirect()->to('/');
			}
            $server   = 'us3.'; // Extracted from API Key
            $list_id  = 'f15ad682db'; // Your List ID
            $auth     = base64_encode('user:' . $api_key);

            $data = [
                'apikey'        => $api_key,
                'email_address' => $email,
                'status'        => 'subscribed',
                'merge_fields'  => [
                    'FNAME' => $first_name,
                    'LNAME' => $last_name
                ]
            ];

            $json_data = json_encode($data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://{$server}api.mailchimp.com/3.0/lists/{$list_id}/members");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Basic ' . $auth
            ]);
            curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

            $result = curl_exec($ch);
            curl_close($ch);

            // Save to Database
            $subscribe = $this->commonModel->subscribeEmail($param['email']);

            if (empty($subscribe)) {
                $this->commonModel->addData('subscribe_tbl', $param);
                $this->session->setFlashdata('alert_success', 'Details Submitted Successfully');
            } else {
                $this->session->setFlashdata('alert_error', 'Email ID already used');
            }

            return redirect()->to('/');
        }
		/********************************************Event List Section******************************/

		/*$tbl6 					=	'event_tbl as ftable';
		  //$shortField6 = 'ftable.event_start_time DESC'; 
		 $where2['where'] 		=	"is_active = '1' AND date != ''";
		 $wcon['where_gte']     =   array('start_date' => date('Y-m-d') , 'end_date' => date('Y-m-d'));
		 $shortField77			=	'type_name ASC';
		 $data['event_tbl'] 	= 	$this->commonModel->getData('multiple', $tbl6,$where2);*/

		
        /******************************************** Additional Data ******************************/
		
        $data['datae'] = date('Y-m-d');
        $data['jazzType'] = $this->commonModel->getCategoryJazz(false);
		
        $data['artistType'] = $this->commonModel->getCategoryArtist();
    
		$where3 = ['where' => ['slider_show' => 'yes', 'is_active' => '1']];
		$data['home_slider_image'] = $this->commonModel->getData('multiple', 'blog_tbl', $where3, 'slider_position ASC');

        /******************************************** Load View ******************************/
	
        $this->layouts->set_title($data['seo_section']['title']);
		
        $this->layouts->set_description($data['seo_section']['description']);
        $this->layouts->set_keyword($data['seo_section']['keywords']);
		$this->layouts->front_view('home_calendar',[], $data);
	}



	/* * *********************************************************************
	 * * Function name 	: index
	 * * Developed By 	: Megha Kumari
	 * * Purpose  		: This function for home page data
	 * * Date 			: 18/01/23
	 * * **********************************************************************/
	public function hh_jazz_guide()
	{
		
		$data = [];
		$data['location_tbl']   = 	$this->commonModel->getMapLoc();
	
		$data['festival_tbl'] = $this->commonModel->getFestLoc();

		/********************************************Banner Section******************************/
		$where['where'] 		=	['page_name' => 'Home Page',  'is_active' => '1']; //"status = 'A'";
		$tbl 					=	'banner_tbl as ftable';
		$shortField 			=	'id DESC';

		$shortField1 			=	'type_name ASC';
		$data['banner'] 		= 	$this->commonModel->getData('multiple', $tbl, $where, $shortField, 6, 0);

		/********************************************About Section******************************/
		$where1['where'] 		=	"is_active = '1'"; //"status = 'A'";
		$tbl1 					=	'about_us_tbl as ftable';
		$shortField12 			=	'id DESC';

		$shortField123 			=	'type_name ASC';
		$data['about'] 		= 	$this->commonModel->getData('multiple', $tbl1, $where1, $shortField12, 6, 0);
		/********************************************Our Partners Section******************************/
		$where2['where'] 		=	array('is_active' => '1', 'page' => '4'); //"status = 'A'";
		$tbl2 					=	'slider_tbl as ftable';
		$data['slider_tbl'] 		= 	$this->commonModel->getData('multiple', $tbl2, $where2);

		$wherepartner['where'] 		=	array('is_active' => '1', 'page' => '1', 'type' => 'slider'); //"status = 'A'";
		$tbl2 					=	'slider_tbl as ftable';
		$data['slider_tbl_partner'] 		= 	$this->commonModel->getData('multiple', $tbl2, $wherepartner);

		/********************************************Img Section******************************/
		$where3['where'] 		=	"is_active = '1'"; //"status = 'A'";
		$tbl3 					=	'home_image as ftable';
		$shortField2 			=	'id DESC';

		$shortField3			=	'type_name ASC';
		$data['home_image'] 		= 	$this->commonModel->getData('multiple', $tbl3, $where3);
		/********************************************Feactrure Section******************************/
		$where4['where'] 		=	['is_home' => '1',  'is_active' => '1']; //"status = 'A'";
		$tbl4 					=	'blog_tbl as ftable';
		$shortField4 			=	'position ASC';

		$data['blog_tbl'] 		= 	$this->commonModel->getData('multiple', $tbl4, $where4, $shortField4);

		$where44['where'] 		=	["page_title" => $data["blog_tbl"][0]["page_title"], 'is_active' => '1']; //"status = 'A'";
		$tbl44 					=	'blog_tbl as ftable';
		$shortField44 			=	'id DESC';

		$shortField44			=	'type_name ASC';
		$data['blog_tbl1'] 		= 	$this->commonModel->getData('multiple', $tbl44, $where44);
		/********************************************Seo Section******************************/
		$where5['where'] 		=	['page_name' => 'Home Page',   'is_active' => '1'];
		$tbl5 					=	'seo_tbl as ftable';
		$shortField5			=	'id DESC';

		$shortField6 			=	'type_name ASC';
		$data['seo_section'] 		= 	$this->commonModel->getData('single', $tbl5, $where5);
		/********************************************Subscribe form******************************/
	
		$validation = service('validation'); 
		if ($this->request->getPost('Savesubsc')) :
			$validation->setRules([
                'name'  => 'required',
                'email' => 'required|valid_email'
            ], [
                'name'  => ['required' => 'Enter Name'],
                'email' => ['required' => 'Enter Email Address', 'valid_email' => 'Enter a valid Email Address']
            ]);
			
            if ($validation->withRequest($this->request)->run()) {
                $param = [
				'name'  			=> 	$this->request->getPost('name'),
				'email'				=> 	$this->request->getPost('email'),
				'creation_date'		=>	date('Y-m-d h:i:s'),
				'ip_address' 		=>	$this->request->getIPAddress(),
				'status'			=>	'A',
				];
				//Mail Chimp API Code
				$email =  $param['email'];
				$first_name = $param['name'];
				$last_name = '';

				$api_key = getenv('MAILCHIMP_API_KEY'); // YOUR API KEY

				// server name followed by a dot. 
				// We use us13 because us13 is present in API KEY
				$server = 'us3.';

				$list_id = 'f15ad682db'; // YOUR LIST ID

				$auth = base64_encode('user:' . $api_key);

				$data = array(
					'apikey'        => $api_key,
					'email_address' => $email,
					'status'        => 'subscribed',
					'msg' => 'Suscribe By me',
					'merge_fields'  => array(
						'FNAME' => $first_name,
						'LNAME'    => $last_name
					)

				);
				$json_data = json_encode($data);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://' . $server . 'api.mailchimp.com/3.0/lists/' . $list_id . '/members');
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Authorization: Basic ' . $auth
				));
				curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

				$result = curl_exec($ch);


				$subscribe = $this->commonModel->subscribeEmail($param['email']);

				if (empty($subscribe)) {
					$alastInsertId				=	$this->commonModel->addData('subscribe_tbl', $param);
					//$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
					$this->session->setFlashdata('alert_success', 'Details submitted successfully');
				} else {
					$this->session->setFlashdata('alert_error', 'Email Id already used');
				}
				return redirect()->to('/hh_jazz_guide');
			}
			else{

				$this->session->setFlashdata('alert_error', 'Please enter all details');
			}
		endif;
		
		$tbl6 					=	'event_tbl as ftable';
		//$shortField6 = 'ftable.event_start_time ASC'; 
		$where24['where'] 		=	"is_active = '1' AND date != ''";
		$wcon['where_gte']     =   array('start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'));
		//$shortField77			=	'type_name ASC';
		$data['event_tbl'] 	= 	$this->commonModel->getData('multiple', $tbl6, $where24);
		//array_multisort($startTime, $data['event_tbl']);
		


		$tbl7 					=	'festival_tbl as ftable';
		//$shortField6 = 'ftable.event_start_time ASC'; 
		$where25['where'] 		=	"is_active = '1' AND date != ''";
		$wcon['where_gte']     =   array('start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'));
		//$shortField77			=	'type_name ASC';
		$data['festival_tbl'] 	= 	$this->commonModel->getData('multiple', $tbl7, $where25);
	

		$data['datae'] 		=   date('Y-m-d');

		$where5568['where'] 		=	['slider_show' => 'yes',  'is_active' => '1'];
		$tbl5568 					=	'blog_tbl as ftable';

		$shortField6568 			= '	slider_position ASC';
		$data['home_slider_image']  = 	$this->commonModel->getData('multiple', $tbl5568, $where5568, $shortField6568);

		$this->layouts->set_title($data['seo_section']['title']);
		$this->layouts->set_description($data['seo_section']['description']);
		$this->layouts->set_keyword($data['seo_section']['keywords']);
		
		$this->layouts->front_view('index', [], $data);
	}

	public function listing()
	{
		$data = array();
		/********************************************Location******************************/

		$where5['where'] 		=	"is_active = '1'"; //"status = 'A'";
		$tbl5 					=	'venue_tbl as ftable';
		$field 					=	'position';
		$fieldName 					=	'is_active';
		$fieldValue                        = '1';

		$shortField5			=	'id,venue_title , image';
		$data['location_tbl'] 		= 	$this->commonModel->getLastOrderByFields1('multiple', $field, $tbl5, $fieldName, $fieldValue, $shortField5);

		/********************************************Venue******************************/

		$where51['where'] 		=	['is_active' => '1'];
		$tbl51					=	'event_location_tbl as ftable';
		//$shortField5			=	'id DESC';

		$shortField51 			=	'location_name ASC';
		$data['venue_tbl'] 		= 	$this->commonModel->getData('multiple', $tbl51, $where51, $shortField51);

		$data['jazzType']      = $this->commonModel->getCategoryJazz();

		$this->layouts->set_title($data['seo_section']['title']);
		$this->layouts->set_description($data['seo_section']['description']);
		$this->layouts->set_keyword($data['seo_section']['keywords']);
		$this->layouts->front_view('listing', array(), $data);
	}
	public function current_isssue_of_magazine()
	{
		$data = array();
		/********************************************Location******************************/

		$where5['where'] 		=	"is_active = '1'"; //"status = 'A'";
		$tbl5 					=	'venue_tbl as ftable';
		$field 					=	'position';
		$fieldName 					=	'is_active';
		$fieldValue                        = '1';

		$shortField5			=	'id,venue_title , image';
		$data['location_tbl'] 		= 	$this->commonModel->getLastOrderByFields1('multiple', $field, $tbl5, $fieldName, $fieldValue, $shortField5);

		/********************************************Venue******************************/

		$where51['where'] 		=	['is_active' => '1'];
		$tbl51					=	'event_location_tbl as ftable';
		//$shortField5			=	'id DESC';

		$shortField51 			=	'location_name ASC';
		$data['venue_tbl'] 		= 	$this->commonModel->getData('multiple', $tbl51, $where51, $shortField51);

		$data['jazzType']      = $this->commonModel->getCategoryJazz();

		$this->layouts->set_title($data['seo_section']['title']);
		$this->layouts->set_description($data['seo_section']['description']);
		$this->layouts->set_keyword($data['seo_section']['keywords']);
		$this->layouts->front_view('current_isssue_of_magazine', array(), $data);
	}
	public function hotspots()
	{
		$data = array();
		/********************************************Location******************************/

		$where5['where'] 		=	"is_active = '1'"; //"status = 'A'";
		$tbl5 					=	'venue_tbl as ftable';
		$field 					=	'position';
		$fieldName 					=	'is_active';
		$fieldValue                        = '1';

		$shortField5			=	'id,venue_title , image';
		$data['location_tbl'] 		= 	$this->commonModel->getLastOrderByFields1('multiple', $field, $tbl5, $fieldName, $fieldValue, $shortField5);

		/********************************************Venue******************************/

		$where51['where'] 		=	['is_active' => '1'];
		$tbl51					=	'event_location_tbl as ftable';
		//$shortField5			=	'id DESC';

		$shortField51 			=	'location_name ASC';
		$data['venue_tbl'] 		= 	$this->commonModel->getData('multiple', $tbl51, $where51, $shortField51);

		$data['jazzType']      = $this->commonModel->getCategoryJazz();

		$this->layouts->set_title($data['seo_section']['title']);
		$this->layouts->set_description($data['seo_section']['description']);
		$this->layouts->set_keyword($data['seo_section']['keywords']);
		$this->layouts->front_view('hotspots', array(), $data);
	}
	public function current_issue()
	{
		$data = array();

		/********************************************Current Issue******************************/

		// Define the where conditions
		$whereCon['where'] = array('is_active' => '1'); // Condition to fetch active records
		$shortField = '';   //'ftable.id DESC'; 

		$tblName = 'current_issue_tbl as ftable';
		// Fetch paginated data
		$data['ALLDATA'] = $this->commonModel->getData('multiple', $tblName, $whereCon, $shortField);

		// Debugging the data (optional, you can remove this later)
		// echo "<pre>";
		// print_r($data['ALLDATA']);
		

		// Setting SEO values (ensure that $data['seo_section'] exists and contains these keys)
		$this->layouts->set_title($data['seo_section']['title']);
		$this->layouts->set_description($data['seo_section']['description']);
		$this->layouts->set_keyword($data['seo_section']['keywords']);

		// Load the front view with the current issue data
		$this->layouts->front_view('current_issue', array(), $data);
	}


	/* * *********************************************************************
	 * * Function name 	: subscribe
	 * * Developed By 	: Megha Kumari
	 * * Purpose  		: This function for newslater subscription
	 * * Date 			: 18/01/23
	 * * **********************************************************************/
	public function subscribe()
	{
		$return = '';
		if ($this->request->getPost('subscribe_email')) {
			$param['name'] 				=	$this->request->getPost('subscribe_name');
			$param['emal'] 				=	$this->request->getPost('subscribe_email');
			$param['creation_date'] 	=	(int)$this->timezone->utc_time();
			$param['ip_address'] 		=	$this->request->getIPAddress();
			$res = '';
			if ($param['emal']) {
				$res = $this->commonModel->getDataByParticularField('subscription', 'emal', $param['emal']);
				if ($res != '') {
					$return = 'NO'; //lang('statictext_lang.ALREDY_EXIST');
				} else {
					$nsertID = $this->commonModel->addData('subscription', $param);
					$return = 'YES'; //lang('statictext_lang.SUBSCRIBED');
				}
			}
		}
		echo $return;
		die;
	}

	/* * *********************************************************************
	 * * Function name 	: getintuch
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function for get in tuch
	 * * Date 			: 04 JULY 2022
	 * * **********************************************************************/
	public function getintuch()
	{
		if ($this->request->getPost('subscribe_email')) {
			$param['name']			=	ucwords($this->request->getPost('name'));
			$param['email']			=	$this->request->getPost('email');
			$param['mobile']		=	$this->request->getPost('mobile');
			//$param['reason']		=	$this->request->getPost('reason');
			$param['message']		=	$this->request->getPost('message');
			$param['created_at']	=	(int)$this->timezone->utc_time();
			$param['created_ip']	=	$this->request->getIPAddress();

			$isInsert 	=	$this->commonModel->addData('contact', $param);
		}
		echo 'Thank you for sharing this with us.';
		die;
	}

	/* * *********************************************************************
	 * * Function name 	: successStories
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function for success Stories
	 * * Date 			: 04 JULY 2022
	 * * **********************************************************************/
	public function successStories()
	{
		$data = array();

		$this->layouts->set_title('EFL | Educator for Life');
		$this->layouts->set_description('');
		$this->layouts->set_keyword('');

		$this->layouts->front_view('successstories', array(), $data);
	}

	/* * *********************************************************************
	 * * Function name 	: getcoursebyajax
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function for get course by ajax
	 * * Date 			: 04 JULY 2022
	 * * **********************************************************************/
	public function getcoursebyajax()
	{
		$where['where'] 			=	"status = 'A'";
		if ($this->request->getPost('domainList')) :
			$where['where'] 		.=	" AND domain IN ('" . implode("','", $this->request->getPost('domainList')) . "')";
		endif;
		if ($this->request->getPost('courseTypeList')) :
			$where['where'] 		.=	" AND course_type IN ('" . implode("','", $this->request->getPost('courseTypeList')) . "')";
		endif;
		$tbl 						=	'course as ftable';
		$shortField 				=	'id DESC';

		$data['course'] 			= 	$this->commonModel->getData('multiple', $tbl, $where, $shortField, 6, 0);

		$this->layouts->front_view('getcoursebyajax', array(), $data, 'onlyview');
	}
	/* * *********************************************************************
	 * * Function name 	: getcoursebyajax
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function for get course by ajax
	 * * Date 			: 06 JULY 2022
	 * * **********************************************************************/
	public function getmenucoursebyajax()
	{
		$where['where'] 			=	"status = 'A'";
		if ($this->request->getPost('menudomainList')) :
			$where['where'] 		.=	" AND domain IN ('" . implode("','", $this->request->getPost('menudomainList')) . "')";
		endif;
		if ($this->request->getPost('menucourseTypeList')) :
			$where['where'] 		.=	" AND course_type IN ('" . implode("','", $this->request->getPost('menucourseTypeList')) . "')";
		endif;
		$tbl 						=	'course as ftable';
		$shortField 				=	'id DESC';

		$data['course'] 			= 	$this->commonModel->getData('multiple', $tbl, $where, $shortField, 6, 0);

		$this->layouts->front_view('getmenucoursebyajax', array(), $data, 'onlyview');
	}

	/* * *********************************************************************
	   * * Function name 	: privacypolicy
	   * * Developed By 	: Ritu Mishra
	   * * Purpose  		: This function for Privacy Policy  page data
	   * * Date 			: 20 Dec 2023
	   * * **********************************************************************/

	public function privacypolicy()
	{

		$this->layouts->set_title('Privacy Policy');
		$this->layouts->set_description('');
		$this->layouts->set_keyword('');
		// $this->layouts->front_view('privacy-policy', array(), $data);
		$this->layouts->front_view('privacy-policy', array(), []);
	}
	public function refundpolicy()
	{

		$this->layouts->set_title('Refund Policy');
		$this->layouts->set_description('');
		$this->layouts->set_keyword('');
		// $this->layouts->front_view('refund-policy', array(), $data);
		$this->layouts->front_view('refund-policy', array(), []);
	}


	public function save_advertise_detail()
	{
		$validation = \Config\Services::validation();

		$validation->setRules([
			'name'                 => ['label' => 'Name', 'rules' => 'required'],
			'venue_name'           => ['label' => 'Venue Name', 'rules' => 'required'],
			'location_name'        => ['label' => 'Location Name', 'rules' => 'required'],
			'email'                => ['label' => 'Email', 'rules' => 'required|valid_email'],
			'phone_number'         => ['label' => 'Phone Number', 'rules' => 'required'],
			'advertising_interest' => ['label' => 'Advertising Interest', 'rules' => 'required'],
			'inquiry'              => ['label' => 'Inquiry', 'rules' => 'required']
		]);
	
		if (!$validation->withRequest($this->request)->run()) {
			$this->session->setFlashdata('error', 'Failed to submit advertise details');
			return $this->response->setJSON([
				'status'  => 'error',
				'message' => $validation->getErrors() // Returns all validation errors
			]);
		}

			$param['name'] = $this->request->getPost('name');
			$param['venue_name'] = $this->request->getPost('venue_name');
			$param['location_name'] = $this->request->getPost('location_name');
			$param['email'] = $this->request->getPost('email');
			$param['phone_number'] = $this->request->getPost('phone_number');
			$param['advertising_interest'] = $this->request->getPost('advertising_interest');
			$param['inquiry'] = $this->request->getPost('inquiry');
			$param['is_active'] = '1';

			if (empty($this->request->getPost('CurrentDataID'))) {
				$alastInsertId = $this->commonModel->addData('advertise_tbl', $param);

				if ($alastInsertId) {
					$this->emailTemplateModel->sendSimpleEmail($param);
					$this->session->setFlashdata('success', 'Advertise Details Submitted Successfully');
					echo json_encode(array('message' => 'Advertise Details Submitted Successfully'));
				} else {
					$this->session->setFlashdata('error', 'Failed to submit details');
					echo json_encode(array('message' => 'Failed to submit advertise details'));
				}
			}
		
	}

	
	public function save_report_problem()
	{

		// $validation = \Config\Services::validation();
		$validationRules = [
			'email' => 'required|valid_email',
			'name'  => 'required',
			'report_problem' => 'required',
		];
		if (!$this->validate($validationRules)) {
			$this->session->setFlashdata('alert_error', 'Please enter all details');
			return $this->response->setJSON([
				'message' => $this->validator->getErrors(), // Returns all errors as an array
				'status'  => 'error'
			]);
		}
			$param['name'] = $this->request->getPost('name');
			$param['email'] = $this->request->getPost('email');
			$param['report_problem'] = $this->request->getPost('report_problem');
			$param['is_active'] = '1';

			if (empty($this->request->getPost('CurrentDataID'))) {
				$alastInsertId = $this->commonModel->addData('report_problem_tbl', $param);

				if ($alastInsertId) {
					$this->emailTemplateModel->sendReportProblem($param);
					$this->session->setFlashdata('success', 'Report Problem Submitted Successfully');
					echo json_encode(array('message' => 'Report Problem submitted successfully.'));
				} else {
					$this->session->setFlashdata('error', 'Failed to submit report');
					echo json_encode(array('message' => 'Failed to submit report details'));
				}
			}
		
	}
	//this code archives all events where end date is older than 30 days, some events do not have end date so check on start date in that case
	public function archive_events()
	{
		log_message("info", "archive_events called");
		$currentDate = date("Y-m-d");
		$thirtyDaysAgo = date("Y-m-d", strtotime("-30 days", strtotime($currentDate)));
		$oldEvents = $this->commonModel->getUpdateOldEvents($thirtyDaysAgo, $currentDate);
	}

	public function inactive_events_festivals()
	{


		$currentDate = date("Y-m-d");
		$oneDaysAgo = date("Y-m-d", strtotime("-1 day", strtotime($currentDate)));
		$inactivate = $this->commonModel->markEventsFestsInactive($oneDaysAgo, $currentDate);
	}

	public function delete_event_index($id)
	{

		// echo $id;
		$this->elastichh->deleteSingleEventFromIndex('events', (int)$id);
	}
	public function signup()
	{
		if ($this->session->get('logged_in')) {
			// Redirect to main dashboard if already logged in
			redirect('maindashboard');
		}
		$data = [];
		$data['error'] = '';

		if ($this->request->getPost('signupFormSubmit')) {
			$error = 'NO';

			// Validation rules
			$this->form_validation->set_rules('firstName', 'First Name', 'required|trim');
			$this->form_validation->set_rules('lastName', 'Last Name', 'required|trim');
			$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email|is_unique[user.user_email]');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
			$this->form_validation->set_rules('confirmPassword', 'Confirm Password', 'required|matches[password]');
			$this->form_validation->set_rules('profession', 'Profession', 'required');

			// Custom error messages
			$this->form_validation->set_message('required', '%s is required');
			$this->form_validation->set_message('valid_email', 'Enter a valid %s');
			$this->form_validation->set_message('is_unique', '%s already exists');
			$this->form_validation->set_message('matches', '%s does not match Password');
			$this->form_validation->set_message('min_length', '%s must be at least 6 characters long');

			if ($this->form_validation->run() && $error == 'NO') {
				// Collect input
				$param['user_first_name'] = $this->request->getPost('firstName', true);
				$param['user_last_name'] = $this->request->getPost('lastName', true);
				$param['user_email'] = $this->request->getPost('email', true);
				$param['user_password'] = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT); // Encrypt password
				$param['profession'] = $this->request->getPost('profession', true);
				//$param['created_at'] = date('Y-m-d H:i:s');
				$param['ip_address'] = $this->request->getIPAddress();
				


				// Insert data into database
				$userExists = $this->commonModel->getDataByParticularField('user', 'user_email', $param['user_email']);
				if (empty($userExists)) {

					$this->commonModel->addData('user', $param);
					$this->emailTemplateModel->userVerifyEmail($param);
					$this->session->setFlashdata('alert_success', 'Signup successful. Please check your indox to verify your email and Signin.');
					//redirect('login');
				} else {
					$this->session->setFlashdata('alert_error', 'Email already registered.');
				}
			} else {
				$data['error'] = validation_errors('<div class="alert alert-danger">', '</div>');
			}
		}

		// Load view
		//$this->layouts->front_view('signup');
		// Use 'login' viewtype to exclude header and footer
		$this->layouts->front_view('signup', [], $data, 'login');
	}


	public function signin()
	{
		$data = [];
		$data['error'] = '';
		if ($this->request->getPost('signinform')) {
			$error = 'NO';
			// Validation rules
			$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if ($this->form_validation->run() && $error == 'NO') {
				$email = $this->request->getPost('email', true);
				$password = $this->request->getPost('password', true);

				// Fetch user
				$user = $this->commonModel->getDataByParticularField('user', 'user_email', $email);

				if ($user) {
					// Check password
					if (password_verify($password, $user['user_password'])) {
						// Check if email is verified
						if ($user['email_verified_at'] != 1) {
							$this->session->setFlashdata('alert_error', 'Your email is not verified. Please check your inbox to verify your email.');
						} else {
							// Update last login time
							$updateData = [
								'last_login_date_time' => date('Y-m-d H:i:s'),
								//'updated_at' => date('Y-m-d H:i:s'),
							];
						
							$this->commonModel->editData('user', $updateData, 'user_id', $user['user_id']);

							// Set session
							$sessionData = [
								'user_id' => $user['user_id'],
								'user_name' => $user['user_first_name'] . ' ' . $user['user_last_name'],
								'user_email' => $user['user_email'],
								'logged_in' => true,
							];
							$this->session->set($sessionData);
                         
							//redirect(' ');
							redirect('maindashboard');
							return; // Ensure no further code is executed
						}
					} else {
						$this->session->setFlashdata('alert_error', 'Incorrect password. Please try again.');
					}
				} else {
					$this->session->setFlashdata('alert_error', 'No account found with this email address.');
				}
			}else {
				$data['error'] = validation_errors('<div class="alert alert-danger">', '</div>');
			}

			// Redirect back to the signin page to show the error
			//redirect('signin');
		}

		// Load the signin view
		//$this->layouts->front_view('signin');
		 // Use 'login' viewtype to exclude header and footer
		 $this->layouts->front_view('signin', [], $data, 'login');
	}

    

	public function verifyemail()
	{
		//$token = $this->request->getPost('token');
		$email = urldecode($this->request->getPost('email'));
	
		// Fetch user based on the token and email
		$user = $this->commonModel->getDataByParticularField('user', 'user_email', $email);

		if ($user['user_email'] == $email) {
		
			// Update email_verified_at to 1 using editData function
			$updateData = [
				'email_verified_at' => 1,  // Indicating email is verified
			];
			$this->commonModel->editData('user', $updateData, 'user_email', $email);
			

			// Set a success flash message and redirect to the login page
			$this->session->setFlashdata('alert_success', 'Your email has been verified successfully. You can now log in.');
			redirect('signin');
		} else {
			// Set an error flash message and redirect to the signup page
			$this->session->setFlashdata('alert_error', 'Invalid verification link or expired token.');
			redirect('signup');
		}
	}

	public function logout()
{
    // Unset specific session data
   // $this->session->unset_userdata(['user_id', 'user_name', 'user_email', 'logged_in']);
    
    // Optionally, if you want to completely destroy all session data, use:
     $this->session->sess_destroy();
    
    
    redirect('signin');
}


	/*	public function test()
	{
		
		$data = array(
    'added_by' => 'admin',
    'event_title' => 'rituuuu',
    'save_location_id' => '674',
    'location_name' => 'Mezzrow',
    'location_address' => '163 W 10th St, New York, NY 10014',
    'latitude' => '40.7345655',
    'longitude' => '-74.0019412',
    'description' => '',
    'start_date' => '2023-05-23',
    'end_date' => '2023-05-23',
    'event_start_time' => '10:30:00',
    'event_end_time' => '1:00 PM',
    'time_permission' => 'Yes',
    'repeating_event' => 'No',
    'website' => 'https://www.smallslive.com',
    'phone_number' => '646-476-4346',
    'venue_id' => '10',
    'frequecy' => '0',
    'no_of_repeat' => '0',
    'no_of_copy' => '0',
    'is_active' => '1',
    'created_by' => '1',
    'creation_date' => '2023-04-26 01:19:04',
    'ip_address' => '100.8.118.168',
    'last_modified_date' => '2023-04-26 01:19:29',
    'set_time' => '2nd set 12:30am',
    'cover_charge' => '',
    'user_first_name' => '',
    'user_last_name' => '',
    'email' => ''
);

$this->db->insert('event_tbl', $data);
echo 'inserted';

	}*/

	/*	public function test()
	{
		//2463 
		$dl= 0;
		$current_date = date("Y-m-d");
		$final_date = date("Y-m-d", strtotime($current_date." -2 months"));
		$this->db->where('start_date <=', $final_date);
		$del = $this->db->delete('event_tbl');
		if($del){
			$dl++;
		}
		echo "Total Deleted: ".$dl;
	}*/


	/*public function test()
	{

		$data = $this->db->select('event_id,start_date,event_start_time')->from('event_tbl')->get()->result_array();
		
		$t = 0;
		foreach($data as $data){
			//echo $data['start_date'];exit;
			///echo $data['event_start_time'];exit;
			$combined_date_and_time = $data['start_date'] . ' ' . $data['event_start_time'];
			$param['date'] = strtotime($combined_date_and_time);
			$this->db->set('date', $param['date']);
			$this->db->where('event_id', $data['event_id']);
			$updte = $this->db->update('event_tbl');
			if($updte){
				$t++;
			}
			echo "updated".$t;

		}
	}*/
}
