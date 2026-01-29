<?php
namespace App\Controllers\front;
use App\Controllers\BaseController;


class Search extends BaseController {

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
	} 
	
	/* * *********************************************************************
	 * * Function name 	: index
	 * * Developed By 	: Megha Kumari
	 * * Purpose  		: This function for home page data
	 * * Date 			: 18/01/23
	 * * **********************************************************************/
	public function index()
	{
		if($_GET['id'] == 1){
		$data = array();
		$data['Get_id'] =$_GET['id'];
		//print_r($data['Get_id']);die();
		/*$where63['where'] 		=	['event_title' => $this->request->getPost('keyword')];
		/$tbl6 					=	'event_tbl as ftable';
		$shortField6 			=	'event_id DESC';
	
		$shortField77			=	'type_name ASC';
		$shortField64 			=	'event_id DESC';
		 $data['event_tbl'] 		= 	$this->common_model->getData('multiple',$tbl6,$where63);*/
		// print_r($data['event_tbl']);die;
		}else if($_GET['id'] == 2){
            $data = array();
			$data['Get_id'] =$_GET['id'];
			$keyword = $this->request->getPost('keyword');
		//print_r($data['Get_id']);die();
		$where2['where'] 		=	"is_active = '1'";//"status = 'A'";
		$tbl2 					=	'slider_tbl as ftable';
		$shortField2 			=	'id DESC';
   
		$shortField2			=	'type_name ASC';
		 $data['slider_tbl'] 		= 	$this->common_model->getData('multiple', $tbl2,$where2);

		$where637['where'] 		=	['page_title' => $this->request->getPost('keyword')];
		$tbl67 					=	'blog_tbl as ftable';
		$shortField678 			=	'event_id DESC';
	
		$shortField777			=	'type_name ASC';
		$shortField647 			=	'event_id DESC';
		 //$data['blog_tbl'] 		= 	$this->common_model->getData('multiple',$tbl67,$where637);
		 $data['blog_tbl'] 		= 	$this->common_model->getDataBlog('multiple',$tbl67,'',$keyword);

		 /*$dates = date('Y-m-d') ;
		 $where635['where'] 		=['start_date' => $dates];
		$tbl656 					=	'event_tbl as ftable';
		$shortField665 			=	'event_id DESC';
	
		$shortField7745			=	'type_name ASC';
		$shortField6466 			=	'event_id DESC';
		 $data['event_tbl_list'] 		= 	$this->common_model->getData('multiple',$tbl656,$where635);*/
		  /********************************************Subscribe form******************************/
	if($this->request->getPost('Savesubsc')):
			
		$error					=	'NO';
			$this->form_validation->set_rules('email', 'Email Address', 'required');
			$this->form_validation->set_rules('name', 'Name', 'required');
		   $this->form_validation->set_message('trim|required', 'Enter %s');
		
		if($this->form_validation->run() && $error == 'NO'): 
				
			
			    $param['email']				= 	$this->request->getPost('email');	
			    $param['name']				= 	$this->request->getPost('name');	
				$param['creation_date']		= 	date('Y-m-d h:i:s');
				$param['status']			=	'A'; 
				$param['ip_address'] 		=	currentIp();
				$alastInsertId				=	$this->common_model->addData('subscribe_tbl',$param);
				$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
				$this->session->setFlashdata('alert_success', 'Details Submitted Successfully');
				
		else:
		$this->session->setFlashdata('alert_error','Please Enter All Details');
		endif;
	endif; 
	}
	
	$where5['where'] 		=	['page_name' => 'Calendar Page'];
	$tbl5 					=	'seo_tbl as ftable';
	$shortField5			=	'id DESC';
   
	$shortField656 			=	'type_name ASC';
	$data['seo_section'] 		= 	$this->common_model->getData('single', $tbl5,$where5);

		$this->layouts->set_title($data['seo_section']['title']);
		$this->layouts->set_description($data['seo_section']['description']);
		$this->layouts->set_keyword($data['seo_section']['keywords']);
		 $this->layouts->front_view('search',array(),$data);
		
	}
}
	
	