<?php
namespace App\Controllers\front\form;
use App\Controllers\BaseController;


class Applicationform extends BaseController {

	public function  __construct() 
	{ 
		
		//error_reporting(E_ALL ^ E_NOTICE);  
		error_reporting(0);  
		$this->load->model(array('admin_model','emailTemplateModel','sms_model','notification_model','front_model'));
		$this->lang = service('language'); 
$this->lang->setLocale('front');
		helper('common');
	} 
	
	/* * *********************************************************************
	 * * Function name 	: login
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function for application form
	 * * Date 			: 01 JULY 2022
	 * * **********************************************************************/
	public function index($id='')
	{	
		$data = array();
		/*----------------------- IS_LOGIN ---------------------*/
		if(empty($this->session->get('EFL_USER_email')) && empty($this->session->get('EFL_USER_NAME'))){
			$this->session->set('currentURL',current_url());
			redirect('login');
		}
		/*----------------------- END ---------------------*/
		if(empty($id)){ redirect('home'); }

		$this->session->set('slug_url',$id);

		$where['where'] 		=	[	'slug_url'	 =>	$id, 'status' => 'A' ];
		$tbl 					=	'course as ftable';
		$data['course'] 		= 	$this->common_model->getData('single', $tbl,$where,[],1,0);

		$wcon['where'] = [ 'created_by' => $this->session->get('EFL_USER_email'),'course_id' => $data['course']['id'] ];

		$data['EDITDATA'] = $this->common_model->getData('single','incomplete_registration as ftable',$wcon,1,0);

		$SCwhere['where'] 		=	[ 'course_id' => $data['course']['id'] ];
		$SCshortField 			=	'schedule_id DESC';
		$data['courseSchedule'] = 	$this->common_model->getData('single', 'course_schedule  as ftable',$SCwhere,$SCshortField,1,0);

		if($this->request->getPost()){
			$this->form_validation->set_rules('type','Registration','required');
			$this->form_validation->set_rules('fname','First Name','required|min_length[4]');
			$this->form_validation->set_rules('lname','last Name','required');
			$this->form_validation->set_rules('mobile','Mobile','required');
			$this->form_validation->set_rules('email','Email','required');
			$this->form_validation->set_rules('country','Country','required');
			$this->form_validation->set_rules('state','State','required');
			$this->form_validation->set_rules('city','City','required');
			$this->form_validation->set_rules('cso','School/Organization','required');

			$this->form_validation->set_error_delimiters('<p class="error">','</p>');
			if($this->request->getPost('sponsored')){
				$this->form_validation->set_rules('s_country','Country','required');
				$this->form_validation->set_rules('s_state','State','required');
				$this->form_validation->set_rules('s_city','City','required');	
				$this->form_validation->set_rules('representative','Representative','required');
				$this->form_validation->set_rules('contact','Contact','required');
				$this->form_validation->set_rules('gst','GST','required');
				$this->form_validation->set_rules('contact','Contact','required');
				$this->form_validation->set_rules('pan','PAN','required');	
			}
			if($this->form_validation->run()){
				
				$params['type'] 		= 	$this->request->getPost('type');
				$params['fname'] 		= 	$this->request->getPost('fname');
				$params['lname'] 		= 	$this->request->getPost('lname');
				$params['mobile'] 		= 	$this->request->getPost('mobile');
				$params['email'] 		= 	$this->request->getPost('email');
				$params['country'] 		= 	$this->request->getPost('country');
				$params['state_id'] 		= 	$this->request->getPost('state');
				$params['city_id'] 		= 	$this->request->getPost('city');
				$params['cso'] 			= 	$this->request->getPost('cso');
				$params['course_id'] 	= 	$this->request->getPost('course_id');
				$params['course_slug'] 	= 	$this->request->getPost('course_slug');
				if(!empty($this->request->getPost('sponsored'))){
					$params['s_country'] 		= 	$this->request->getPost('s_country');
					$params['s_state_id'] 			= 	$this->request->getPost('s_state');
					$params['s_city_id'] 			= 	$this->request->getPost('s_city');
					$params['representative'] 	= 	$this->request->getPost('representative');
					$params['contact'] 			= 	$this->request->getPost('contact');
					$params['gst'] 				= 	$this->request->getPost('gst');
					$params['pan'] 				= 	$this->request->getPost('pan');
				}
				if($this->request->getPost('CurrentDataID') == ''){
					$params['registration_number'] 	= 	'EFL-'.$this->request->getPost('course_id').'-'.date('Ymd').'-'.generateRandomString(5,'n');
					$params['status'] 			= 	'A';
					$params['created_at'] 		= 	(int)$this->timezone->utc_time();
					$params['created_ip'] 		= 	currentIp();
					$params['created_by'] 		= 	$this->session->get('EFL_USER_email');
					$insert_id = $this->common_model->addData('incomplete_registration',$params);
					$this->session->set('InsertID', $insert_id);

					$Cparams['course_id'] 		= 	$this->request->getPost('course_id');
					$Cparams['participation_no'] =	1;
					$Cparams['inc_reg_id']		=	$insert_id;
					$Cparams['user_id']			=	$this->session->get('EFL_USER_email');
					$Cparams['created_at']		=	(int)$this->timezone->utc_time();

					$insertID = $this->common_model->addData('incomplete_course_opt',$Cparams);

				}else{
					$params['status'] 			= 	'A';
					$params['updated_at'] 		= 	(int)$this->timezone->utc_time();
					$params['updated_ip'] 		= 	currentIp();
					$params['updated_by'] 		= 	$this->session->get('EFL_USER_email');
					$insert_id = $this->common_model->editData('incomplete_registration',$params, 'id', $this->request->getPost('CurrentDataID'));
					
					$this->session->set('InsertID', $this->request->getPost('CurrentDataID'));
				}

				$this->session->set('slug_url',$this->request->getPost('course_slug'));
				redirect('applicationform/program-details');
			}
		}

		$this->layouts->set_title('EFL | Application Form | Basic Information');
		$this->layouts->set_description('Help us know you better');
		$this->layouts->set_keyword('');

		$this->layouts->front_view('form/basic_info',array(),$data);
	}
	//END OF FUNCTION
	/***********************************************************************
	** Function name 	: personalDetails
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for get City List
	** Date 			: 03 AUG 2022
	************************************************************************/
	function personalDetails()
	{  
		$date = array();

		$where['where'] 		=	['status' => 'A'];
		$shortField 			=	'id DESC';
		$shortField1 			=	'type_name ASC';
		$data['courseType'] 		= 	$this->common_model->getData('multiple', 'course_type as ftable',$where,$shortField1);
		$shortField2 			=	'domain_name ASC';
		$data['domain'] 		= 	$this->common_model->getData('multiple', 'domains as ftable',$where,$shortField2);
		$data['addcourse'] 		= 	$this->common_model->getData('multiple', 'course as ftable',$where,$shortField,6,0);

		//echo $this->session->get('InsertID');die();

/*		$abcd = $this->common_model->getCoursedetails($this->session->get('InsertID'));
		echo "<pre>";print_r($abcd); die();

		$wcon['where'] = ['id' => $this->session->get('InsertID')];
		$data['course'] = $this->common_model->getData('single','incomplete_registration as ftable',$wcon,1,0);

		$course_id = explode('___',$data['course']['courses_opt']);
		$i=0;
		foreach ($course_id as $key => $value) {
			$where['where'] = [ 'id' => $value ];
			$data['course_opt'][$i] = $this->common_model->getData('single','course as ftable',$where,1,0);	
			$i++;
		}
*/
		$data['course'] = $this->common_model->getCoursedetails($this->session->get('InsertID'));
		$Cwhere['where'] 		=	[ 'id' => $data['course'][0]['course_id'] ];
		$CshortField 			=	'id DESC';
		$data['courseData'] 	= 	$this->common_model->getData('single', 'course  as ftable',$Cwhere,$CshortField,1,0);
		$SCwhere['where'] 		=	[ 'course_id' => $data['course'][0]['course_id'] ];
		$SCshortField 			=	'schedule_id DESC';
		$data['courseSchedule'] = 	$this->common_model->getData('single', 'course_schedule  as ftable',$SCwhere,$SCshortField,1,0);
		//echo "<pre>";print_r($data); die();		
		/*if($this->request->getPost()){
			$courseID 	= 	$this->request->getPost('course_id');
			$qty 		=	$this->request->getPost('nop');
			$cout = count($courseID);

			for($i=0; $i<$cout; $i++){
				$params['course_id'] 		= 	$courseID[$i];
				$params['participation_no'] =	(int)$qty[$i];
				$params['inc_reg_id']		=	$this->request->getPost('insertID');
				$params['user_id']			=	$this->session->get('EFL_USER_email');
				$params['created_at']		=	(int)$this->timezone->utc_time();

				$insertID = $this->common_model->addData('incomplete_course_opt',$params);
			}
			if($insertID){
				redirect('register');
			}
		}*/

		//echo '<pre>';  print_r($data); die;
		$this->layouts->set_title('EFL | Application Form | Personal Details');
		$this->layouts->set_description('Select the program you like');
		$this->layouts->set_keyword('');

		$this->layouts->front_view('form/program_details',array(),$data);	
	}	//END OF FUNCTION
	/***********************************************************************
	** Function name 	: register
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for register
	** Date 			: 04 AUG 2022
	************************************************************************/
	function register()
	{  
		$data 	=	array();

		$data['course'] = $this->common_model->getCoursedetails($this->session->get('InsertID'));
		$Cwhere['where'] 		=	[ 'id' => $data['course'][0]['course_id'] ];
		$CshortField 			=	'id DESC';
		$data['courseData'] 	= 	$this->common_model->getData('single', 'course  as ftable',$Cwhere,$CshortField,1,0);
		$SCwhere['where'] 		=	[ 'course_id' => $data['course'][0]['course_id'] ];
		$SCshortField 			=	'schedule_id DESC';
		$data['courseSchedule'] = 	$this->common_model->getData('single', 'course_schedule  as ftable',$SCwhere,$SCshortField,1,0);

		$this->layouts->set_title('EFL | Application Form | Personal Details');
		$this->layouts->set_description('Select the program you like');
		$this->layouts->set_keyword('');

		$this->layouts->front_view('form/registration',array(),$data);	

	}//END OF FUNCTION

	/***********************************************************************
	** Function name 	: getCityList
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for get City List
	** Date 			: 04 AUG 2022
	************************************************************************/
	function getCityList($stateId='')
	{  
		echo $this->admin_model->getCity($stateId,''); 
	} //END OF FUNCTION
	/***********************************************************************
	** Function name 	: getstateList
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for get City List
	** Date 			: 04 AUG 2022
	************************************************************************/
	function getstateList()
	{  
		echo $this->admin_model->getState(); 
	} //END OF FUNCTION
	/***********************************************************************
	** Function name 	: updateParticipants
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for update Participants
	** Date 			: 04 AUG 2022
	************************************************************************/
	function updateParticipants()
	{  
		if($this->request->getPost()){
			$params['participation_no'] = (int)$this->request->getPost('nop');
			$isUpdate = $this->common_model->editData('incomplete_course_opt',$params,'id',(int)$this->request->getPost('insertID'));	
		}
	} //END OF FUNCTION

	/***********************************************************************
	** Function name 	: removeCourse
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for remove Course
	** Date 			: 04 AUG 2022
	************************************************************************/
	function removeCourse($id)
	{  
		$this->common_model->deleteData('incomplete_course_opt','id',(int)$id);	
		redirect('applicationform/program-details');
	} //END OF FUNCTION

	/* * *********************************************************************
	 * * Function name 	: getaddcoursebyajax
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function for get course by ajax
	 * * Date 			: 05 AUG 2022
	 * * **********************************************************************/
	public function getaddcoursebyajax()
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

		$this->layouts->front_view('getaddmenucoursebyajax',array(),$data,'onlyview');
	}

	/***********************************************************************
	** Function name 	: addcourse
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for register
	** Date 			: 05 AUG 2022
	************************************************************************/
	function addcourse($id)
	{  
		if(!empty($id)){
			$where['where'] = [	'course_id' => $id, 
								 'inc_reg_id' => $this->session->get('InsertID')	];
			$check = $this->common_model->getData('single','incomplete_course_opt as ftable',$where);
			if(empty($check)){
				$params['course_id'] 		=	$id;
				$params['participation_no']	=	1;
				$params['user_id']			=	$this->session->get('EFL_USER_email');
				$params['inc_reg_id']		=	$this->session->get('InsertID');
				$params['created_at']		=	(int)$this->timezone->utc_time();
				$this->common_model->addData('incomplete_course_opt',$params);
			}else{
				$params['participation_no'] =	$check['participation_no']+1;
				$this->common_model->editData('incomplete_course_opt', $params, 'id', $check['id']);
			}
		}
		redirect('applicationform/program-details');
	}//END OF FUNCTION

	/***********************************************************************
	** Function name 	: finalsubmit
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for finalsubmit
	** Date 			: 05 AUG 2022
	************************************************************************/
	function finalsubmit()
	{  
		$data 	=	array();
		$where1['where'] = [	'id' => $this->session->get('InsertID') ];
		$Uparams = $this->common_model->getData('single','incomplete_registration as ftable',$where1);

		$where2['where'] = [	'inc_reg_id' => $this->session->get('InsertID') ];
		$params = $this->common_model->getData('multiple','incomplete_course_opt as ftable',$where2);

		$this->common_model->addData('efl_registration',$Uparams);
		$this->common_model->deleteData('efl_incomplete_registration','id', $Uparams['id']);
		foreach ($params as $key => $value) {
			$this->common_model->addData('efl_course_opt',$value);
			$this->common_model->deleteData('efl_incomplete_course_opt','id', $value['id']);
		}

		$this->emailTemplateModel->sendMailToCustomer();
		$this->emailTemplateModel->sendMailToAdmin();

		$this->session->setFlashdata('success','Course registration complete. We will contact you soon.');

		redirect('home');

		
	}//END OF FUNCTION	
	
}
