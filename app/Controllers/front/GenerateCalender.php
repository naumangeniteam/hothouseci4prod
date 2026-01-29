<?php
namespace App\Controllers\front;
use App\Controllers\BaseController;


class GenerateCalender extends BaseController {

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
		$data = array();
	/********************************************Banner Section******************************/
	$where['where'] 		=	['page_name' => 'Calender',  'is_active' =>'1'];//"status = 'A'";
	$tbl 					=	'banner_tbl as ftable';
	$shortField 			=	'id DESC';

	$shortField1 			=	'type_name ASC';
	 $data['banner'] 		= 	$this->common_model->getData('multiple', $tbl,$where);
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
 $where5['where'] 		=	['page_name' => 'Calendar Page',  'is_active' =>'1'];
 $tbl5 					=	'seo_tbl as ftable';
 $shortField5			=	'id DESC';

 $shortField6 			=	'type_name ASC';
 $data['seo_section'] 		= 	$this->common_model->getData('single', $tbl5,$where5);
  /********************************************Subscribe form******************************/
  if($this->request->getPost('Savesubsc')):
			
	$error					=	'NO';
		$this->form_validation->set_rules('email', 'Email Address', 'required');
	   $this->form_validation->set_message('trim|required', 'Enter %s');
	
	if($this->form_validation->run() && $error == 'NO'): 
			
		
			$param['email']				= 	$this->request->getPost('email');	
			$param['creation_date']				= 	date('Y-m-d h:i:s');
			$param['status']			=	'A'; 
			$alastInsertId				=	$this->common_model->addData('subscribe_tbl',$param);
			$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
			$this->session->setFlashdata('alert_success', 'Details Submitted Successfully');
				redirect('calendar');
		else:
		$this->session->setFlashdata('alert_error','Please Enter Email Address');
		endif;
endif; 

 /********************************************Event List Section******************************/
     
		 $tbl6 					=	'event_tbl as ftable';
		 $shortField6 			=	'id DESC';
 
		 $shortField77			=	'type_name ASC';
		  $data['event_tbl'] 		= 	$this->common_model->getData('multiple', $tbl6);
		 
	   
		$this->layouts->set_title($data['seo_section']['title']);
		$this->layouts->set_description($data['seo_section']['description']);
		$this->layouts->set_keyword($data['seo_section']['keywords']);
		 $this->layouts->front_view('calendar',array(),$data);
	}
}
	
	