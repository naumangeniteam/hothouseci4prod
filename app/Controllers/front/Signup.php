<?php
namespace App\Controllers\front;
use App\Controllers\BaseController;


class Signup extends BaseController {

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
	 * * Function name 	: login
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function for login
	 * * Date 			: 01 JULY 2022
	 * * **********************************************************************/
	public function index()
	{	
		$data = array();
	
		if($this->request->getPost()){
			$this->form_validation->set_rules('name', 'Name', 'required|min_length[4]');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.user_email]');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');

			if($this->form_validation->run()){
				$param['user_name']		=	ucwords($this->request->getPost('name'));
				$param['user_email']	=	$this->request->getPost('email');
				$param['password']		=	md5($this->request->getPost('password'));
				$param['created_at']	=	(int)$this->timezone->utc_time();
				$param['created_ip']	=	currentIp();
				$param['created_by']	=	'self';
				$param['status']		=	'A';

				$isInsert 	=	$this->common_model->addData('users',$param);

				$this->session->setFlashdata('success', lang('statictext_lang.USER_CREATED'));
				redirect('login');
			}
		}
		$this->layouts->set_title('EFL | Login');
		$this->layouts->set_description('');
		$this->layouts->set_keyword('');

		$this->layouts->front_view('signup',array(),$data);

	}
	//END OF FUNCTION
	
}