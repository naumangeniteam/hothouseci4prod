<?php
namespace App\Controllers\front;

use \DrewM\MailChimp\MailChimp;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\EmailtemplateModel;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\CommonModel;
use App\Libraries\Layouts;

class Submit_venue extends BaseController
{
	protected $adminModel;
    protected $emailtemplateModel;
    protected $smsModel;
    protected $notificationModel;
    protected $common_model;
    protected $layouts;
	protected $validation;
	protected $db;
	protected $session;
	protected $lang;

	public function  __construct()
	{
		
		//error_reporting(E_ALL ^ E_NOTICE);  
		error_reporting(0);
	 $this->lang = service('language'); 
$this->lang->setLocale('front');
		

		$this->adminModel = new AdminModel();
        $this->emailtemplateModel = new EmailtemplateModel();
        $this->smsModel = new SmsModel();
        $this->notificationModel = new NotificationModel();
        $this->common_model = new CommonModel();
        $this->layouts = new Layouts();
        helper(['common']);
        // $session =service('session');
		// $session = \Config\Services::session();
		$this->session= session();
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
		
		$data = array();
		/********************************************Banner Section******************************/
		$where['where'] 		=	['page_name' => 'Submit Event',  'is_active' => '1']; //"status = 'A'";
		$tbl 					=	'banner_tbl as ftable';
		$shortField 			=	'id DESC';

		$shortField1 			=	'type_name ASC';
		$data['banner'] 		= 	$this->common_model->getData('multiple', $tbl, $where, $shortField, 6, 0);
		/********************************************Our Partners Section******************************/
		$where2['where'] 		=	"is_active = '1'"; //"status = 'A'";
		$tbl2 					=	'slider_tbl as ftable';
		$shortField2 			=	'id DESC';

		$shortField2			=	'type_name ASC';
		$data['slider_tbl'] 		= 	$this->common_model->getData('multiple', $tbl2, $where2);

		$data['location'] = $this->common_model->getLocation();
		$data['venues'] = $this->common_model->getCategory();

		/*****************************************************Add Venue**********************************************************/
	
		if (isset($_POST['captcha'])) {
			if ($this->request->getPost('SaveChanges')) :
				//echo'oiu';die;

				$error					=	'NO';
				// Define validation rules
				$rules = [
					'location_name_'          => 'trim|required',
					'location_address_'       => 'trim|required|max_length[256]',
					'longitude_'              => 'trim|required',
					'latitude_'               => 'trim|required|max_length[256]',
					'venue_id_'               => 'trim|required',
					'contact_person_name'     => 'trim|required',
					'contact_person_email'    => 'trim|required|valid_email',
					'contact_person_phone_number' => 'trim|required',
					'contact_person_title'    => 'trim|required',
					'captcha'                 => 'required',
				];
		
				// Custom error messages
				$messages = [
					'location_name_'          => ['required' => 'Enter Location Name'],
					'location_address_'       => ['required' => 'Enter Location Address'],
					'longitude_'              => ['required' => 'Enter Longitude'],
					'latitude_'               => ['required' => 'Enter Latitude'],
					'venue_id_'               => ['required' => 'Enter Venue Location'],
					'contact_person_name'     => ['required' => 'Enter Contact Person Name'],
					'contact_person_email'    => ['required' => 'Enter Contact Person Email', 'valid_email' => 'Enter a valid email address'],
					'contact_person_phone_number' => ['required' => 'Enter Contact Person Phone Number'],
					'contact_person_title'    => ['required' => 'Enter Contact Person Title'],
					'captcha'                 => ['required' => 'Captcha is required'],
				];
                // $trap = $this->request->getPost('trap');
				// //echo"<pre>";print_r($trap);die;
				// if (!empty($trap)) {
				// 	session()->setFlashdata('error', 'Spam detected. Submission rejected.');
				// 	return redirect()->to('submit_venue');
				// }
				
				if ($this->validate($rules, $messages) && $error == 'NO') :
					// echo'iiii';die; 

					$param['location_name']				= 	$this->request->getPost('location_name_');
					$param['location_address']				= 	$this->request->getPost('location_address_');
					$param['longitude']				= 	$this->request->getPost('longitude_');
					$param['latitude']				= 	$this->request->getPost('latitude_');
					$param['county']				= 	$this->request->getPost('county');
					$param['website']				= 	$this->request->getPost('website_');
					$param['phone_number']				= 	$this->request->getPost('phone_number_');
					$param['venue_id']				= 	$this->request->getPost('venue_id_');
					$param['contact_person_name'] = $this->request->getPost('contact_person_name');
					$param['contact_person_email'] = $this->request->getPost('contact_person_email');
					$param['contact_person_phone_number'] = $this->request->getPost('contact_person_phone_number');
					$param['contact_person_title'] = $this->request->getPost('contact_person_title');

					$param['creation_date']				= 	date('Y-m-d h:i:s');
					$param['location_source']='front_user';
					$param['is_active']			=	'0';


					$lastInsertId				=	$this->common_model->addData('event_location_tbl', $param);
				
					
					if ($lastInsertId) {
						//echo'if';die;
						$this->emailtemplateModel->sendnewVenueEmail($param);
						// $this->session->setFlashdata('success', 'Thanks for adding your venue name,now the venue will be visible in our event addition page.');
						$this->session->setFlashdata('success', 'Thank you for adding your venue name. Your request has been received and will be processed within 24-48 hours. We will contact you if we need additional information.');

						return redirect()->to('submit_event');
						//}
					} else {
						//echo'else';die;
						$this->session->setFlashdata('error', 'Please try again');
						return redirect()->to('submit_venue');
					}
				else:
					return redirect()->to('submit_venue')->withInput()->with('errors', $this->validator->getErrors());
						
				endif;
			endif;
		} else {
			$data['captchaerror'] = "Captcha doesn't match.";
		}

		/********************************************Subscribe form******************************/
		if ($this->request->getPost('Savesubsc')) :

			$error					=	'NO';
			$this->validation->setRules([
				'email' => 'required|valid_email',
				'name'  => 'required'
			]);
			if ($this->validation->withRequest($this->request)->run() && $error == 'NO') :
			// if ($this->form_validation->run() && $error == 'NO') :


				$param['email']				= 	$this->request->getPost('email');
				$param['name']				= 	$this->request->getPost('name');
				$param['creation_date']				= 	date('Y-m-d h:i:s');
				$param['status']			=	'A';
				$param['ip_address'] 		=	currentIp();
				//Mail Chimp API Code
				$email =  $param['email'];
				$first_name = $param['name'];
				$last_name = '';

					$api_key = env('MAILCHIMP_API_KEY'); // YOUR API KEY

				// server name followed by a dot. 
				// We use us13 because us13 is present in API KEY
				$server = 'us3.';

				$list_id = 'f15ad682db'; // YOUR LIST ID

				$auth = base64_encode('user:' . $api_key);

				$data = array(
					'apikey'        => $api_key,
					'email_address' => $email,
					'status'        => 'subscribed',
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

				//	$subscribe = $this->db->select('id')->from('subscribe_tbl')->where('email',$param['email'])->get()->row();
				$subscribe = $this->common_model->subscribeEmail($param['email']);
				if (empty($subscribe)) {
					$lastInsertId				=	$this->common_model->addData('subscribe_tbl', $param);
					//$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
					$this->session->setFlashdata('alert_success', 'Details Submitted Successfully');
				} else {
					$this->session->setFlashdata('alert_error', 'Email Id already used');
				}
				return redirect()->to('submit_event');
			else :
				$this->session->setFlashdata('alert_error', 'Please Enter All Details');
			endif;
			
		endif;
		/********************************************Seo Section******************************/
		$where5['where'] 		=	['page_name' => 'Submit Event Page',  'is_active' => '1'];
		$tbl5 					=	'seo_tbl as ftable';
		$shortField5			=	'id DESC';

		$shortField6 			=	'type_name ASC';
		$data['seo_section'] 		= 	$this->common_model->getData('single', $tbl5, $where5);


		$this->layouts->set_title($data['seo_section']['title']);
		$this->layouts->set_description($data['seo_section']['description']);
		$this->layouts->set_keyword($data['seo_section']['keywords']);
		$this->layouts->front_view('submit_venue', array(), $data);
	}
}
