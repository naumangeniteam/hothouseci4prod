<?php
namespace App\Controllers\front;

use App\Controllers\BaseController;
use \DrewM\MailChimp\MailChimp;
use App\Models\CommonModel;
use App\Libraries\Layouts;
class Contact_us extends BaseController {

	protected $commonModel;
    protected $layouts;
	// protected $validation;
	protected $session;
	public function  __construct() 
	{ 
		// 
		//error_reporting(E_ALL ^ E_NOTICE);  
		error_reporting(0);  
		// $this->load->model(array('admin_model','emailtemplate_model','sms_model','notification_model','common_model'));
	 $this->lang = service('language'); 
$this->lang->setLocale('front');
		// helper('common');
		$this->commonModel = new CommonModel();
        $this->layouts = new Layouts();
		$this->session = session();
		helper('common');
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
	$where['where'] 		=	['page_name' => 'Contact Page',  'is_active' =>'1'];//"status = 'A'";
	$tbl 					=	'banner_tbl as ftable';
	$shortField 			=	'id DESC';

	$shortField1 			=	'type_name ASC';
	 $data['banner'] 		= 	$this->commonModel->getData('multiple', $tbl,$where,$shortField,6,0);
      /********************************************Contact Section******************************/
	 $where2['where'] 		=	"is_active = '1'";//"status = 'A'";
	 $tbl2 					=	'contact_details_tbl as ftable';
	 $shortField2 			=	'id DESC';

	 $shortField2			=	'type_name ASC';
	  $data['contact_details_tbl'] 		= 	$this->commonModel->getData('multiple', $tbl2,$where2);

	    /********************************************Advertising Section******************************/
	 $where3['where'] 		=	"is_active = '1'";//"status = 'A'";
	 $tbl3 					=	'advertisement_tbl as ftable';
	 $shortField3 			=	'id DESC';

	 $shortField3			=	'type_name ASC';
	  $data['advertisement_tbl'] 		= 	$this->commonModel->getData('multiple', $tbl3,$where3);

	  /********************************************Our Partners Section******************************/
	 $where4['where'] 		=	"is_active = '1'";//"status = 'A'";
	 $tbl4 					=	'slider_tbl as ftable';
	 $shortField4 			=	'id DESC';

	 $shortField4			=	'type_name ASC';
	  $data['slider_tbl'] 		= 	$this->commonModel->getData('multiple', $tbl4,$where4);

/********************************************Seo Section******************************/
$where5['where'] 		=	['page_name' => 'Contact Page',  'is_active' =>'1'];
$tbl5 					=	'seo_tbl as ftable';
$shortField5			=	'id DESC';

$shortField6 			=	'type_name ASC';
$data['seo_section'] 		= 	$this->commonModel->getData('single', $tbl5,$where5);


$where7['where'] 		=	"is_active = '1'";
  $tbl7 					=	'footer_tbl as ftable';
  $shortField5			=	'id DESC';

  $shortField7 			=	'type_name ASC';
  $data['footer_tbl'] 		= 	$this->commonModel->getData('multiple',$tbl7,$where7);
 // print_r($data['footer_tbl']);die();
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
			 //Mail Chimp API Code
		        $email =  $param['email'];
                $first_name = $param['name'];
                $last_name = '';
                
                $api_key = getenv('MAILCHIMP_API_KEY'); // YOUR API KEY
                
                // server name followed by a dot. 
                // We use us13 because us13 is present in API KEY
                $server = 'us3.'; 
                
                $list_id = 'f15ad682db'; // YOUR LIST ID
                
                $auth = base64_encode( 'user:'.$api_key );
                
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
                curl_setopt($ch, CURLOPT_URL, 'https://'.$server.'api.mailchimp.com/3.0/lists/'.$list_id.'/members');
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                    'Authorization: Basic '.$auth));
                curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_POST, true);    
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                
                $result = curl_exec($ch);
			 $subscribe = $this->commonModel->subscribeEmail($param['email']);
			if(empty($subscribe)){
		    	$alastInsertId				=	$this->commonModel->addData('subscribe_tbl',$param);
			    //$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
		    	$this->session->setFlashdata('alert_success', 'Details Submitted Successfully');
    		}
    		else{
    		    $this->session->setFlashdata('alert_error', 'Email Id already used');
    		}
			return redirect()->to('/contact_us');
        }
	   
		$this->layouts->set_title($data['seo_section']['title']);
		$this->layouts->set_description($data['seo_section']['description']);
		$this->layouts->set_keyword($data['seo_section']['keywords']);
		 $this->layouts->front_view('contact_us',array(),$data);
	}
}
	
	