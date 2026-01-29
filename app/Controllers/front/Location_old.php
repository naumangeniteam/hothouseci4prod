<?php
namespace App\Controllers\front;
use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\EmailtemplateModel;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\CommonModel;
use App\Models\FrontModel;
use App\Libraries\Layouts;


class Location_old extends BaseController {

	public function  __construct() 
	{ 
		
		//error_reporting(E_ALL ^ E_NOTICE);  
		error_reporting(0);  
		$this->admin_model = new AdminModel();
		$this->emailTemplateModel = new EmailtemplateModel();
        $this->smsModel = new SmsModel();
        $this->notificationModel = new NotificationModel();
        $this->common_model = new CommonModel();
        $this->frontModel = new FrontModel();
        $this->layouts = new Layouts();
        $this->session = session();
		$this->lang = service('language'); 
$this->lang->setLocale('front');
		helper('common');
		$this->load->library('pagination');
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
		$data['Get_id'] =$_GET['keyword'];
		//print_r($data['Get_id']);die();
		$where63['where'] 		=	['venue_title' => $this->request->getPost('keyword') , 'is_active' =>'1'];
		$tbl6 					=	'venue_tbl as ftable';
		$shortField6 			=	'id DESC';
	
		$shortField77			=	'type_name ASC';
		$shortField64 			=	'id DESC';
		 $data['venue_tbl'] 		= 	$this->common_model->getData('multiple',$tbl6,$where63);
//print_r( $data['venue_tbl']['0']['id']);die;

		 $tbl64 					=	'event_location_tbl as ftable';
		$shortField64 			=	'id DESC';
	
		$shortField774			=	'location_name ASC';
		$shortField644 			=	'id DESC';
		 $where634['where'] 		=	['venue_id' =>  $data['venue_tbl'][0]['id'] , 'is_active' =>'1' ];
		 $data['event_location_tbl'] 		= 	$this->common_model->getData('multiple',$tbl64,$where634,$shortField774);

		 $tbl645 					=	'event_location_tbl as ftable';
		$shortField646 			=	'id DESC';
	
		$shortField7746			=	'location_name DESC';
		$shortField6446 			=	'id DESC';
		 $where6340['where'] 		=	['venue_id' =>  $data['venue_tbl'][0]['id'] , 'is_active' =>'1' ];
		 $data['event_location_tbl1'] 		= 	$this->common_model->getData('multiple',$tbl645,$where6340,$shortField7746);


		 

//print_r( $data['event_location_tbl1']);die();
        $this->load->library("googlemaps");
		 //$config["center"] = '137.449, -122.1419';
		 $config["center"] = '$data["event_location_tbl"][0]["latitude"], $data["event_location_tbl"][0]["longitude"]';
		 $config["zoom"] = "auto";
		 $this->googlemaps->initialize($config);
		 $marker =array();
		 $marker["position"]= "137.429, -122.1419";
		 $this->googlemaps->add_marker($marker);
		 $data["map"] = $this->googlemaps->create_map();
		



		 //print_r( $data['event_location_tbl'] );die();
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
					$alastInsertId				=	$this->common_model->addData('subscribe_tbl',$param);
					$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
					$this->session->setFlashdata('alert_success', 'Details Submitted Successfully');
					redirect('how_to_get_hh');
			else:
			$this->session->setFlashdata('alert_error','Please Enter All Details');
			endif;
		endif; 
	
	
	$where5['where'] 		=	['page_name' => 'Calendar Page'];
	$tbl5 					=	'seo_tbl as ftable';
	$shortField5			=	'id DESC';
   
	$shortField656 			=	'type_name ASC';
	$data['seo_section'] 		= 	$this->common_model->getData('single', $tbl5,$where5);

		$this->layouts->set_title($data['seo_section']['title']);
		$this->layouts->set_description($data['seo_section']['description']);
		$this->layouts->set_keyword($data['seo_section']['keywords']);
		 $this->layouts->front_view('location',array(),$data);
		
	}
}
	
	