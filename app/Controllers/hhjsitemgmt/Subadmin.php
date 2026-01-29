<?php


namespace App\Controllers\hhjsitemgmt;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\EmailtemplateModel;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\CommonModel;
use App\Models\FrontModel;
use App\Libraries\Layouts;
class Subadmin extends BaseController {
	protected $admin_model;
    protected $emailTemplateModel;
    protected $smsModel;
    protected $notificationModel;
	protected $common_model;
	protected $session;
	protected $layouts;
	protected $lang;
	public function  __construct() 
	{ 
		
		//error_reporting(E_ALL ^ E_NOTICE);  
		error_reporting(0); 
		$this->admin_model = new AdminModel();
		$this->emailTemplateModel = new EmailtemplateModel();
        $this->smsModel = new SmsModel();
        $this->notificationModel = new NotificationModel();
        $this->common_model = new CommonModel();
        $this->layouts = new Layouts();
        $this->session = session();
	 $this->lang = service('language'); 
		$this->lang->setLocale('admin');
		helper('common');
	} 

	/* * *********************************************************************
	 * * Function name : Subadmin
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for Subadmin
	 * * Date : 25 JUNE 2022
	 * * **********************************************************************/
	public function index()
	{	
		$this->admin_model->authCheck('view_data');
		$this->admin_model->getPermissionType($data); 
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'subadmin';
		$data['activeSubMenu'] 				= 	'users';
		
		if($this->request->getGet('searchValue')):
			$sValue							=	$this->request->getGet('searchValue');
			$whereCon['like']			 	= 	"(ftable.admin_title LIKE '%".$sValue."%'
												  OR ftable.admin_first_name LIKE '%".$sValue."%'
												  OR ftable.admin_last_name LIKE '%".$sValue."%'
												  OR ftable.admin_email LIKE '%".$sValue."%'
												  OR ftable.admin_phone LIKE '%".$sValue."%')";
			$data['searchField'] 			= 	$sField;
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;
				
		$whereCon['where']		 			= 	"ftable.admin_type = 'Sub Admin'";		
		$shortField 						= 	"ftable.admin_id ASC";
		
		$baseUrl 							= 	getCurrentControllerPath('index');
		$this->session->set('siteAdminILCADMData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'admin as ftable';
		$con 								= 	'';
		$totalRows 							= 	$this->common_model->getData('count',$tblName,$whereCon,$shortField,'0','0');
		
		if($this->request->getGet('showLength') == 'All'):
			$perPage	 					= 	$totalRows;
			$data['perpage'] 				= 	$this->request->getGet('showLength');  
		elseif($this->request->getGet('showLength')):
			$perPage	 					= 	$this->request->getGet('showLength'); 
			$data['perpage'] 				= 	$this->request->getGet('showLength'); 
		else:
			$perPage			= 	SHOW_NO_OF_DATA;
			$data['perpage'] =20;
		endif;
		$uriSegment 						= 	getUrlSegment();
	    $data['PAGINATION']					=	adminPagination($baseUrl,$suffix,$totalRows,$perPage,$uriSegment);

       if ($this->request->getUri()->getSegment($uriSegment)) :
           $page = $this->request->getUri()->getSegment($uriSegment);
       else:
           $page = 0;
       endif;
		
		$data['forAction'] 					= 	$baseUrl; 
		if($totalRows):
			$first							=	(int)($page)+1;
			$data['first']					=	$first;
			
			if($data['perpage'] == 'All'):
				$pageData 					=	$totalRows;
			else:
				$pageData 					=	$data['perpage'];
			endif;
			
			$last							=	((int)($page)+$pageData)>$totalRows?$totalRows:((int)($page)+$pageData);
			$data['noOfContent']			=	'Showing '.$first.'-'.$last.' of '.$totalRows.' items';
		else:
			$data['first']					=	1;
			$data['noOfContent']			=	'';
		endif;
		
		$data['ALLDATA'] 					= 	$this->common_model->getData('multiple',$tblName,$whereCon,$shortField,$perPage,$page); 

		$this->layouts->set_title('Manage Sub-admin Details');
		$this->layouts->admin_view('subadmin/subadmin/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name : addeditdata
	 * * Developed By : Manoj Kumar
	 * * Purpose  : This function used for add edit data
	 * * Date : 25 JUNE 2022
	 * * **********************************************************************/
	public function addeditdata($editId='')
	{		
		$uri = service('uri');
		$editId = $uri->getSegment(4); 
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'subadmin';
		$data['activeSubMenu'] 				= 	'subadmin';
		
		if($editId):
			$this->admin_model->authCheck('edit_data');
			$data['EDITDATA']		=	$this->common_model->getDataByParticularField('admin','admin_id',(int)$editId);
			$data['MODULEDATA']		=	$this->admin_model->getModulePermission((int)$editId);
		else:
			$this->admin_model->authCheck('add_data');
		endif;
		$data['Modirectory'] 		= 	$this->admin_model->getModule(); 

		if($this->request->getPost('SaveChanges')):
			$error					=	'NO';
			$this->form_validation->set_rules('department_id', 'Department', 'trim|required');
			$this->form_validation->set_rules('designation_id', 'Designation', 'trim|required');
			$this->form_validation->set_rules('admin_title', 'Title', 'trim|required|max_length[64]');
			$this->form_validation->set_rules('admin_first_name', 'First Name', 'trim|required|max_length[128]');
			$this->form_validation->set_rules('admin_middle_name', 'Middle Name', 'trim|max_length[128]');
			$this->form_validation->set_rules('admin_last_name', 'Last Name', 'trim|required|max_length[128]');
			$this->form_validation->set_rules('admin_email', 'E-Mail', 'trim|required|valid_email|max_length[64]|is_unique[admin.admin_email.admin_id]');			
			$this->form_validation->set_rules('admin_phone', 'Mobile number', 'trim|required|min_length[10]|max_length[15]|is_unique[admin.admin_phone.admin_id]');
			$testmobile		=	str_replace(' ','',$this->request->getPost('admin_phone'));
			if($this->request->getPost('admin_phone') && !preg_match('/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i',$testmobile)):
				if(!preg_match("/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/",$testmobile)):
				//if(!preg_match("/^[0-9\-\(\)\/\+\s]*$/",$testmobile)):
					$error						=	'YES';
					$data['mobileerror'] 		= 	'Please Eneter Correct Number';
				endif;
			endif;
			if($this->request->getPost('CurrentDataID')):
				if($this->request->getPost('password')):
					$this->form_validation->set_rules('password', 'lang:Password', 'trim|required|min_length[6]|max_length[25]');
					$this->form_validation->set_rules('conf_password', 'lang:Confirm Password', 'trim|required|min_length[6]|matches[password]');
				endif;
			else:
				$this->form_validation->set_rules('password', 'lang:Password', 'trim|required|min_length[6]|max_length[25]');
				$this->form_validation->set_rules('conf_password', 'lang:Confirm Password', 'trim|required|min_length[6]|matches[password]');
			endif;
			$this->form_validation->set_rules('admin_address', 'Address', 'trim|max_length[512]');
			$this->form_validation->set_rules('admin_city', 'City', 'trim');
			$this->form_validation->set_rules('admin_state', 'State', 'trim');
			$this->form_validation->set_rules('admin_pincode', 'Zipcode', 'trim');

			$pererror							=	'YES';
			if($data['Modirectory'] <> ""): 
			 	foreach($data['Modirectory'] as $MODinfo): 
					if($this->request->getPost('mainmodule_'.$MODinfo['module_id'])):
						$pererror				=	'NO';
					endif;
				endforeach;
				if($pererror == 'YES'):
					$error						=	'YES';
					$data['PERERROR'] 			= 	'Please give at least one module permission.';
				endif;
			endif;
			
			if($this->form_validation->run() && $error == 'NO'): 

				$param['admin_title']			= 	addslashes($this->request->getPost('admin_title'));
				$param['admin_first_name']		= 	addslashes($this->request->getPost('admin_first_name'));
				$param['admin_middle_name']		= 	addslashes($this->request->getPost('admin_middle_name'));
				$param['admin_last_name']		= 	addslashes($this->request->getPost('admin_last_name'));
				$param['admin_email']			= 	addslashes($this->request->getPost('admin_email'));
				$param['admin_phone']			= 	(int)($this->request->getPost('admin_phone'));
				if($this->request->getPost('password')):
					$curpassword				=	html_escape(addslashes($this->request->getPost('password')));
					$param['admin_password']	=	$this->admin_model->encriptPassword($curpassword);
					$param['admin_password_otp']=	'';
				endif;
				$param['admin_address']			= 	addslashes($this->request->getPost('admin_address'));
				$param['admin_city']			= 	addslashes($this->request->getPost('admin_city'));
				$param['admin_state']			= 	addslashes($this->request->getPost('admin_state'));
				$param['admin_pincode']			= 	(int)($this->request->getPost('admin_pincode'));

				$departmentData					=	explode('_____',$this->request->getPost('department_id'));
				$param['department_id']			= 	(int)$departmentData[0];

				$DEPparam['department_used']	=	'Y';
				$this->common_model->editData('admin_department',$DEPparam,'department_id',(int)$param['department_id']);

				$designationData				=	explode('_____',$this->request->getPost('designation_id'));
				$param['designation_id']		= 	(int)$designationData[0];

				$DEGparam['designation_used']	=	'Y';
				$this->common_model->editData('admin_designation',$DEGparam,'designation_id',(int)$param['designation_id']);
				
				if($this->request->getPost('CurrentDataID') ==''):
					$param['admin_type']		=	'Sub Admin';
					$param['creation_ip']		=	currentIp();
					$param['creation_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['created_by']		=	(int)$this->session->get('ILCADM_ADMIN_ID');
					$param['status']			=	'A'; 
					$alastInsertId				=	$this->common_model->addData('admin',$param);
					$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
					$adminId					=	$param['admin_id'];
				else:
					$adminId						=	$this->request->getPost('CurrentDataID');
					$param['update_ip']			=	currentIp();
					$param['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['updated_by']		=	(int)$this->session->get('ILCADM_ADMIN_ID');  
					$this->common_model->editData('admin',$param,'admin_id',(int)$adminId);
					$this->session->setFlashdata('alert_success',lang('statictext_lang.updatesuccess'));
				endif;

				if($data['Modirectory'] <> "" && $adminId): 
					$this->admin_model->deletePermissionData($adminId);
					foreach($data['Modirectory'] as $MODinfo):
						$mainmodperids		=	'';
						$mmc 				= 	$MODinfo['module_id']; 
						if($this->request->getPost('mainmodule_'.$mmc)):
							$MMParams['module_id']			=	$MODinfo['module_id'];
							$MMParams['module_name']		=	$MODinfo['module_name'];
							$MMParams['module_display_name']=	$MODinfo['module_display_name'];
							$MMParams['module_orders']		=	$MODinfo['module_orders'];
							$MMParams['module_icone']		=	$MODinfo['module_icone'];
							$MMParams['child_data']			=	$MODinfo['child_data'];
							$MMParams['admin_id']			=	$adminId;
							
							if($this->request->getPost('mainmodule_view_data_'.$mmc)):
								$MMParams['view_data']		=	'Y';
							else:
								$MMParams['view_data']		=	'N';
							endif;
							if($this->request->getPost('mainmodule_add_data_'.$mmc)):
								$MMParams['add_data']		=	'Y';
							else:
								$MMParams['add_data']		=	'N';
							endif;
							if($this->request->getPost('mainmodule_edit_data_'.$mmc)):
								$MMParams['edit_data']		=	'Y';
							else:
								$MMParams['edit_data']		=	'N';
							endif;
							if($this->request->getPost('mainmodule_delete_data_'.$mmc)):
								$MMParams['delete_data']	=	'Y';
							else:
								$MMParams['delete_data']	=	'N';
							endif;
							
							$MMlastInsertId					=	$this->common_model->addData('admin_module_permission',$MMParams);
							$mainModuleId					=	$MMlastInsertId;
						endif;
						
						if($MODinfo['child_data'] == 'Y' && $mainModuleId):
							$childdata 						= 	$this->admin_model->getModuleChild($MODinfo['module_id']);
							if($childdata <> ""): 
								foreach($childdata as $CDinfo):	
									 $cmc 					= 	$CDinfo['child_module_id'];
									if($this->request->getPost('childmodule_'.$mmc.'_'.$cmc)):
										$UMMUparam['child_data']		=	'Y';
										$UMMUwhere['module_id']			=	$mainModuleId;
										$this->common_model->editDataByMultipleCondition('admin_module_permission',$UMMUparam,$UMMUwhere);
									
										$CMParams['module_permission_id']	=	$mainModuleId;
										$CMParams['module_id']				=	$CDinfo['child_module_id'];
										$CMParams['module_name']			=	$CDinfo['module_name'];
										$CMParams['module_display_name']	=	$CDinfo['module_display_name'];
										$CMParams['module_orders']			=	$CDinfo['module_orders'];
										$CMParams['admin_id']				=	$adminId;
										
										if($this->request->getPost('childmodule_view_data_'.$mmc.'_'.$cmc)):
											$CMParams['view_data']			=	'Y';
										else:
											$CMParams['view_data']			=	'N';
										endif;
										if($this->request->getPost('childmodule_add_data_'.$mmc.'_'.$cmc)):
											$CMParams['add_data']			=	'Y';
										else:
											$CMParams['add_data']			=	'N';
										endif;
										if($this->request->getPost('childmodule_edit_data_'.$mmc.'_'.$cmc)):
											$CMParams['edit_data']			=	'Y';
										else:
											$CMParams['edit_data']			=	'N';
										endif;
										if($this->request->getPost('childmodule_delete_data_'.$mmc.'_'.$cmc)):
											$CMParams['delete_data']		=	'Y';
										else:
											$CMParams['delete_data']		=	'N';
										endif;
											
										$CMlastInsertId					=	$this->common_model->addData('admin_module_child_permission',$CMParams);
									endif;
								endforeach;
							endif;
						endif;
					endforeach;
				endif;
				
				redirect(correctLink('siteAdminILCADMData',getCurrentControllerPath('index')));
			endif;
		endif;
		
		$this->layouts->set_title('Edit Sub-admin Details');
		$this->layouts->admin_view('subadmin/subadmin/addeditdata',array(),$data);
	}	// END OF FUNCTION	
	
	/***********************************************************************
	** Function name : changestatus
	** Developed By : Manoj Kumar
	** Purpose  : This function used for change status
	** Date : 25 JUNE 2022
	************************************************************************/
		function changestatus($changeStatusId='',$statusType='')
	{  
		$uri = service('uri');
		$changeStatusId = $uri->getSegment(4); 
		$statusType = $uri->getSegment(5);   
		$this->admin_model->authCheck('edit_data');
		$param['status']		=	$statusType;
		$this->common_model->editData('admin',$param,'admin_id',(int)$changeStatusId);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.statussuccess'));
		
		redirect(correctLink('siteAdminILCADMData',getCurrentControllerPath('index')));
	}
}