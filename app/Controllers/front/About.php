<?php

namespace App\Controllers\front;
use App\Controllers\BaseController;
use \DrewM\MailChimp\MailChimp;
class About extends BaseController {

	public function  __construct() 
	{ 
		
		//error_reporting(E_ALL ^ E_NOTICE);  
		error_reporting(0);  
		$this->load->model(array('admin_model','emailtemplate_model','sms_model','notification_model','common_model'));
		$this->lang = service('language'); 
$this->lang->setLocale('front');
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
	$where['where'] 		=	['page_name' => 'About Page',  'is_active' =>'1'];//"status = 'A'";
	$tbl 					=	'banner_tbl as ftable';
	$shortField 			=	'id DESC';

	$shortField1 			=	'type_name ASC';
	 $data['banner'] 		= 	$this->common_model->getData('multiple', $tbl,$where,$shortField,6,0);
/********************************************About Section******************************/
       $where1['where'] 		=	"is_active = '1'";//"status = 'A'";
		$tbl1 					=	'about_us_tbl as ftable';
		$shortField12 			=	'id DESC';

		$shortField123 			=	'type_name ASC';
		 $data['about'] 		= 	$this->common_model->getData('multiple', $tbl1,$where1,$shortField12,6,0);
		 /********************************************Our Team Section******************************/
		 $where2['where'] 		=	"is_active = '1'";//"status = 'A'";
		 $tbl2 					=	'about_team_tbl as ftable';
		 $shortField2 			=	'id DESC';
 
		 $shortField222			=	'type_name ASC';
		  $data['about_team_tbl'] 		= 	$this->common_model->getData('multiple', $tbl2,$where2,$shortField2,6,0);
	 /********************************************Seo Section******************************/
	 $where5['where'] 		=	['page_name' => 'About Page' ,  'is_active' =>'1'];
	 $tbl5 					=	'seo_tbl as ftable';
	 $shortField5			=	'id DESC';
   
	 $shortField6 			=	'type_name ASC';
	 $data['seo_section'] 		= 	$this->common_model->getData('single', $tbl5,$where5);
	  /********************************************Subscribe form******************************/
	  if($this->request->getPost('Savesubsc')):
			
		$error					=	'NO';
			$this->form_validation->set_rules('email', 'Email Address', 'required');
			$this->form_validation->set_rules('name', 'Name', 'required');
		   $this->form_validation->set_message('trim|required', 'Enter %s');
		
		if($this->form_validation->run() && $error == 'NO'): 
				
			
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
				$subscribe = $this->common_model->subscribeEmail($param['email']);
			if(empty($subscribe)){
		    	$alastInsertId				=	$this->common_model->addData('subscribe_tbl',$param);
			    //$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
			    $this->session->setFlashdata('alert_success', 'Details submitted successfully');
    		}
    		else{
    		    $this->session->setFlashdata('alert_error', 'Email Id already used');
    		}
				redirect('about');
		else:
		$this->session->setFlashdata('alert_error','Please enter all details');
		endif;
	endif; 

		$this->layouts->set_title($data['seo_section']['title']);
		$this->layouts->set_description($data['seo_section']['description']);
		$this->layouts->set_keyword($data['seo_section']['keywords']);
		 $this->layouts->front_view('about',array(),$data);
	}
}
	
	