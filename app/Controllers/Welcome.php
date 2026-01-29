<?php
namespace App\Controllers;


class Welcome extends BaseController {

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
$this->lang->setLocale('admin');
		helper('common');
	} 
	
	/*  *********************************************************************
	   Function name 	: index
	   Developed By 	: Afsar Ali
	   Purpose  		: This function for home page data
	   Date 			: 04 JULY 2022
	   ********************************************************************** */
	public function index()
	{	
		return redirect('home');
	}
}
?>