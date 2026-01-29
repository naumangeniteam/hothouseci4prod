<?php
namespace App\Controllers\front;
use App\Controllers\BaseController;
use \DrewM\MailChimp\MailChimp;


class Login extends BaseController {

	public function  __construct() 
	{ 
		
		//error_reporting(E_ALL ^ E_NOTICE);  
		error_reporting(0);  
		$this->load->model(array('front_model','emailtemplate_model','sms_model','notification_model'));
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
		/*----------------------- CHECK ---------------------*/
		if($this->session->get('EFL_USER_email') && $this->session->get('EFL_USER_NAME')){
			redirect('home');
		}
		/*----------------------- LOGIN ---------------------*/
		if($this->request->getPost()):
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');

			if($this->form_validation->run()):

				$user = $this->front_model->check($this->request->getPost('email'));

				//echo "<pre>"; print_r($user); die();

				if(!empty($user)):
					if($user['status'] == 'A'):
						if($user['password'] == md5($this->request->getPost('password'))):
						    //echo "working"; die();
							$this->session->set('EFL_USER_NAME', $user['user_name']);
							$this->session->set('EFL_USER_email', $user['user_email']);
							if(!empty($this->session->get('currentURL'))){
								redirect($this->session->get('currentURL'));
							}
							redirect('home');
						else:
							$this->session->setFlashdata('error',lang('statictext_lang.WRONG_PASSWORD'));	
						endif;
					elseif($user['status'] == 'I'):
						$this->session->setFlashdata('error',lang('statictext_lang.INACTIVE'));
						redirect('login');
					elseif($user['status'] == 'B'):
						$this->session->setFlashdata('error',lang('statictext_lang.BLOCK'));
						redirect('login');
					endif;
				endif;
			endif;
			
		endif;

		$this->layouts->set_title('EFL | Login');
		$this->layouts->set_description('');
		$this->layouts->set_keyword('');

		$this->layouts->front_view('login',array(),$data);

	}
	//END OF FUNCTION

/* * *********************************************************************
	 * * Function name 	: login
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function for login
	 * * Date 			: 01 JULY 2022
	 * * **********************************************************************/
	public function logout()
	{	
		$this->session->unset_userdata('EFL_USER_NAME');
		$this->session->unset_userdata('EFL_USER_email');

		redirect('login');
	}
	//END OF FUNCTION

	
}