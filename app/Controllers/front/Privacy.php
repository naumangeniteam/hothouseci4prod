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


class Privacy extends BaseController {

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
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function for home page data
	 * * Date 			: 04 JULY 2022
	 * * **********************************************************************/
	public function index()
	{	
		$data = array();

		$where['where'] 		=	['page' => 'Privacy Policy'];//['status' => 'A'];
		$tbl 					=	'banners as ftable';
		$shortField 			=	'id DESC';
       $data['banner'] 		= 	$this->common_model->getData('multiple', $tbl,$where,$shortField,6,0);

	  
		$where['where'] 		=	"status = 'A'";//['status' => 'A'];
		$tbl 					=	'privacy as ftable';
		$shortField 			=	'id DESC';
       $data['privacy'] 		= 	$this->common_model->getData('multiple', $tbl,$where,$shortField,6,0);

	   $where8['where'] 		=	"status = 'A'";//['status' => 'A'];
	   $tbl8 					=	'contact_section as ftable';
	   $data['contact_section'] 		= 	$this->common_model->getData('multiple', $tbl8,$where8);
   
	
	$where['where'] 		=	['page_name' => 'Home'];
	$tbl 					=	'seo_section as ftable';
	$shortField2			=	'id DESC';

	$shortField1 			=	'type_name ASC';
	$data['seo_section'] 		= 	$this->common_model->getData('single', $tbl,$where,$shortField2,6,0);
	
	$this->layouts->set_title($data['seo_section']['title']);
	$this->layouts->set_description($data['seo_section']['description']);
	$this->layouts->set_keyword($data['seo_section']['keywords']);
        $this->layouts->front_view('privacy',array(),$data);

	}
	

	/* * *********************************************************************
	 * * Function name 	: subscribe
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function for newslater subscription
	 * * Date 			: 04 JULY 2022
	 * * **********************************************************************/
	public function subscribe()
	{	
		$return = '';
		if($this->request->getPost('subscribe_email')){	
			$param['name'] 				=	$this->request->getPost('subscribe_name');
			$param['emal'] 				=	$this->request->getPost('subscribe_email');
			$param['creation_date'] 	=	(int)$this->timezone->utc_time();
			$param['ip_address'] 		=	currentIp();
			$res = '';
			if($param['emal']){	
				$res = $this->common_model->getDataByParticularField('subscription', 'emal', $param['emal']);
				if($res != ''){		
					$return = 'NO';//lang('statictext_lang.ALREDY_EXIST');
				}else{	
					$nsertID = $this->common_model->addData('subscription', $param);
					$return = 'YES';//lang('statictext_lang.SUBSCRIBED');
				}
			}
		}
		echo $return; die;
	}

	/* * *********************************************************************
	 * * Function name 	: getintuch
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function for get in tuch
	 * * Date 			: 04 JULY 2022
	 * * **********************************************************************/
	public function getintuch()	
	{	
		if($this->request->getPost('subscribe_email')){	
			$param['name']			=	ucwords($this->request->getPost('name'));
			$param['email']			=	$this->request->getPost('email');
			$param['mobile']		=	$this->request->getPost('mobile');
			//$param['reason']		=	$this->request->getPost('reason');
			$param['message']		=	$this->request->getPost('message');
			$param['created_at']	=	(int)$this->timezone->utc_time();
			$param['created_ip']	=	currentIp();

			$isInsert 	=	$this->common_model->addData('contact',$param);
		}
		echo 'Thank you for sharing this with us.'; die;
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

		$this->layouts->front_view('successstories',array(),$data);
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
		if($this->request->getPost('domainList')):
			$where['where'] 		.=	" AND domain IN ('".implode("','",$this->request->getPost('domainList'))."')";
		endif;
		if($this->request->getPost('courseTypeList')):
			$where['where'] 		.=	" AND course_type IN ('".implode("','",$this->request->getPost('courseTypeList'))."')";
		endif;
		$tbl 						=	'course as ftable';
		$shortField 				=	'id DESC';

		$data['course'] 			= 	$this->common_model->getData('multiple', $tbl,$where,$shortField,6,0);

		$this->layouts->front_view('getcoursebyajax',array(),$data,'onlyview');
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
		if($this->request->getPost('menudomainList')):
			$where['where'] 		.=	" AND domain IN ('".implode("','",$this->request->getPost('menudomainList'))."')";
		endif;
		if($this->request->getPost('menucourseTypeList')):
			$where['where'] 		.=	" AND course_type IN ('".implode("','",$this->request->getPost('menucourseTypeList'))."')";
		endif;
		$tbl 						=	'course as ftable';
		$shortField 				=	'id DESC';

		$data['course'] 			= 	$this->common_model->getData('multiple', $tbl,$where,$shortField,6,0);

		$this->layouts->front_view('getmenucoursebyajax',array(),$data,'onlyview');
	}
}