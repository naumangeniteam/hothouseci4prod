<?php

namespace App\Controllers\front;
use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Libraries\Layouts;
use App\Models\AdminModel;
use App\Models\EmailtemplateModel;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\FrontModel;
use App\Libraries\Elastichh;
use \DrewM\MailChimp\MailChimp;

class Festival_detail extends BaseController
{
	protected $emailTemplateModel;
	protected $admin_model;
	protected $front_model;
	protected $smsModel;
	protected $notificationModel;
	protected $common_model;
	protected $layouts;
	protected $session;
	protected $uri;
	protected $lang;
	protected $db;
	public function  __construct()
	{
		$this->admin_model = new AdminModel();
		$this->emailTemplateModel = new EmailtemplateModel();
		$this->smsModel = new SmsModel();
		$this->notificationModel = new NotificationModel();
		$this->common_model = new CommonModel();
		$this->front_model = new FrontModel();
		$this->session = session();
		helper(['common', 'url', 'form' , 'general']); // Load helpers
		$this->layouts = new Layouts();
		$this->uri = service('uri');
		$this->lang = service('language');
		$this->lang->setLocale('admin');
		//error_reporting(E_ALL ^ E_NOTICE);  
		error_reporting(0);
		//$this->load->model(array('admin_model', 'emailtemplate_model', 'sms_model', 'notification_model', 'common_model'));
		$this->lang = service('language'); 
        $this->lang->setLocale('front');
		$this->db =  \Config\Database::connect();
		// helper('common');
	}

	/* * *********************************************************************
	 * * Function name 	: index
	 * * Developed By 	: Megha Kumari
	 * * Purpose  		: This function for home page data
	 * * Date 			: 18/01/23
	 * * **********************************************************************/
	public function index($editId = '')
	{
		$uri = service('uri');
		$editId = $uri->getSegment(2);
		$data = array();
		/********************************************Banner Section******************************/
		$where['where'] 		=	['page_name' => 'Calender',  'is_active' => '1']; //"status = 'A'";
		$tbl 					=	'banner_tbl as ftable';
		$shortField 			=	'id DESC';

		$shortField1 			=	'type_name ASC';
		$data['banner'] 		= 	$this->common_model->getData('multiple', $tbl, $where);
		/********************************************About Section******************************/
		$where1['where'] 		=	"is_active = '1'"; //"status = 'A'";
		$tbl1 					=	'about_us_tbl as ftable';
		$shortField12 			=	'id DESC';

		$shortField123 			=	'type_name ASC';
		$data['about'] 		= 	$this->common_model->getData('multiple', $tbl1, $where1, $shortField12, 6, 0);
		/********************************************Our Team Section******************************/
		$where2['where'] 		=	"is_active = '1'"; //"status = 'A'";
		$tbl2 					=	'about_team_tbl as ftable';
		$shortField2 			=	'id DESC';

		$shortField222			=	'type_name ASC';
		$data['about_team_tbl'] 		= 	$this->common_model->getData('multiple', $tbl2, $where2, $shortField2, 6, 0);

		/********************************************Seo Section******************************/
		$where5['where'] 		=	['page_name' => 'Calendar Page',  'is_active' => '1'];
		$tbl5 					=	'seo_tbl as ftable';
		$shortField5			=	'id DESC';

		$shortField6 			=	'type_name ASC';
		$data['seo_section'] 		= 	$this->common_model->getData('single', $tbl5, $where5);

		$data['artist_name'] 		= 	$this->front_model->festival_artist();
		
		//echo"<pre>";print_r($data['artist_name'] );die;
		$data['festival_tbl']    = $this->common_model->getFestLoc();
		
		// echo "<pre>";print_r($data["festival_tbl"]);die;
		$data['artists'] = $this->common_model->totalArtist();
		// echo "<pre>";print_r($data["artists"]);die;
		//echo"herere5r343sdfs";die;
		/********************************************Subscribe form******************************/
		if ($this->request->getPost('Savesubsc')) :

			$error					=	'NO';
			$this->form_validation->set_rules('email', 'Email Address', 'required');
			$this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_message('trim|required', 'Enter %s');

			if ($this->form_validation->run() && $error == 'NO') :


				$param['email']				= 	$this->request->getPost('email');
				$param['name']				= 	$this->request->getPost('name');
				$param['creation_date']				= 	date('Y-m-d h:i:s');
				$param['status']			=	'A';
				$param['ip_address'] 		=	currentIp();
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
				$subscribe = $this->common_model->subscribeEmail($param['email']);
				if (empty($subscribe)) {
					$alastInsertId				=	$this->common_model->addData('subscribe_tbl', $param);
					//$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
					$this->session->setFlashdata('alert_success', 'Details Submitted Successfully');
				} else {
					$this->session->setFlashdata('alert_error', 'Email Id already used');
				}
				redirect('festival_detail');
			else :
				$this->session->setFlashdata('alert_error', 'Please Enter All Details');
			endif;
		endif;

		/********************************************Event List Section******************************/

		if(is_numeric($editId)) {
			
					$query1 = $this->db->table('festival_tbl')
							->where('festival_id', $editId)
							->where('is_active', "1")
							->get();
			
					if (!$query1) {
						log_message('error', 'Query failed in festival detail: ' . print_r($this->db->error(), true));
					} else {
						log_message('debug', 'Query success in festival detail: ' . print_r($query1->getResultArray(), true));
						if ($query1->getNumRows() > 0) {
							$festival_data = $query1->getRowArray();
							
							$data["festival_tbl"] = [$festival_data];
						}
					}
			
					$query3 = $this->db->table('lineup_tbl')
							->where('festival_id', $editId)
							->get();
			
			if ($query3->getNumRows() > 0) {
				$lineup_data = $query3->getResultArray();
				$total_artist_count = 0;
			
				foreach ($lineup_data as &$lineup) {
					$artist_ids = json_decode($lineup['artist_id'], true);
					$jazz_ids = json_decode($lineup['jazz_types_id'], true);
			
					if (!empty($artist_ids)) {
						$query4 = $this->db->table('artist_tbl')
										->select('artist_name, artist_image')
										->whereIn('id', $artist_ids)
										->get();
			
						$artists = $query4->getResultArray();
			
						$artist_names = array_column($artists, 'artist_name');
						$artist_images = array_column($artists, 'artist_image');
			
						$lineup['artist_names'] = $artist_names;
						$lineup['artist_images'] = $artist_images;
			
						$total_artist_count += count($artist_names);
					} else {
						$lineup['artist_names'] = [];
						$lineup['artist_images'] = [];
					}
			
					if (!empty($jazz_ids)) {
						$query4 = $this->db->table('jazz_types')
										->select('name')
										->whereIn('id', $jazz_ids)
										->get();
			
						$jazzs = $query4->getResultArray();
						$jazz_names = array_column($jazzs, 'name');
			
						$lineup['jazz_names'] = $jazz_names;
					} else {
						$lineup['jazz_names'] = [];
					}
				}
			
				$data["lineup_tbl"] = $lineup_data;
			}
		} else {
			$data["festival_tbl"] = [];
			$data["lineup_tbl"] = [];
			$total_artist_count = 0;
			log_message("warning", "editId: ".$editId);
		}


		$data['total_artist_count'] = $total_artist_count;
		//   echo"<pre>";print_r($data['total_artist_count']);die;
		$this->layouts->set_title($data['seo_section']['title']);
		$this->layouts->set_description($data['seo_section']['description']);
		$this->layouts->set_keyword($data['seo_section']['keywords']);
		$this->layouts->front_view('festival_detail', array(), $data);
	}
}
