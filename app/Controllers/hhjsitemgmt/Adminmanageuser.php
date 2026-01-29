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
class Adminmanageuser extends BaseController {
	protected $admin_model;
    protected $emailTemplateModel;
    protected $smsModel;
    protected $notificationModel;
	protected $common_model;
	protected $session;
	protected $layouts;
	protected $lang;
	protected $validation;
	public function  __construct() 
	{ 
		
		//error_reporting(E_ALL ^ E_NOTICE);  
		error_reporting(0); 
		$this->admin_model = new AdminModel();
		$this->emailTemplateModel = new EmailtemplateModel();
        $this->smsModel = new SmsModel();
        $this->notificationModel = new NotificationModel();
        $this->common_model = new CommonModel();
		$this->validation = service('validation');
        $this->layouts = new Layouts();
        $this->session = session();
	    $this->lang = service('language'); 
        $this->lang->setLocale('admin');
		helper(['common', 'general']);
	} 

	/* * *********************************************************************
	 * * Function name 	: index
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function used for IL Talks
	 * * Date 			: 27 JUNE 2022
	 * * **********************************************************************/
	public function index()
	{	
		// $users = $this->admin_model->getUser();
        // $data['users'] = $users;

		$this->admin_model->authCheck('view_data');
		$this->admin_model->getPermissionType($data); 
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanageuser';
		$data['activeSubMenu'] 				= 	'adminmanageuser';
		
		if($this->request->getGet('searchValue')):
			$sValue							=	$this->request->getGet('searchValue');
			$whereCon['like'] = [
				'ftable.admin_first_name' => $sValue,
				'ftable.admin_middle_name' => $sValue,
				'ftable.admin_last_name' => $sValue
			];			
		  
			$data['searchField'] = $sField ?? ''; 
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;
				
		$whereCon['where']		 			= 	"ftable.role = 2";		
		$shortField 						= 	"ftable.admin_id DESC";
		
		$baseUrl 							= 	getCurrentControllerPath('index');
		$this->session->set('userILCADMData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'admin as ftable';
		$con 								= 	'';
		
		$totalRows 							= 	$this->common_model->getData('count',$tblName,$whereCon,$shortField,'0','0');
		//echo"<pre>";print_r($totalRows);die;
		if($this->request->getGet('showLength') == 'All'):
			$perPage	 					= 	$totalRows;
			$data['perpage'] 				= 	$this->request->getGet('showLength');  
		elseif($this->request->getGet('showLength')):
			$perPage	 					= 	$this->request->getGet('showLength'); 
			$data['perpage'] 				= 	$this->request->getGet('showLength'); 
		else:
			$perPage	 					= 	SHOW_NO_OF_DATA;
			$data['perpage'] 				= 	SHOW_NO_OF_DATA; 
		endif;
		$data['perpage'] = $perPage;

		// ✅ Get the `page` parameter from the GET request
		$page = $this->request->getGet('page') ?? 1;
        $page = max(1, (int)$page);
	
		// ✅ Generate pagination links
		$suffix = '?'.http_build_query($_GET); // Preserve filters in pagination
		$data['PAGINATION'] = adminPagination($baseUrl, $suffix, $totalRows, $perPage, $page);
	
		$data['forAction'] = $baseUrl;
		if ($totalRows > 0) {
			// ✅ Ensure `$page` is always at least 1
			$page = $this->request->getGet('page') ?? 1;
			$page = max(1, (int)$page); // Ensure page starts from at least 1
		
			// ✅ Fix the offset for fetching paginated data
			$offset = ($page - 1) * $perPage;
		
			// ✅ Correct Serial Number Calculation
			$first = $offset + 1;
			$data['first'] = $first;
		
			$last = min($first + $perPage - 1, $totalRows);
			$data['noOfContent'] = "Showing $first-$last of $totalRows items";
		} else {
			$data['first'] = 1;
			$data['noOfContent'] = '';
			$offset = 0;
		}
		
		$data['ALLDATA'] 					= 	$this->common_model->getData('multiple',$tblName,$whereCon,$shortField,$perPage,$offset); 
	
		// echo "<pre>"; print_r($data['ALLDATA']); die();
		$data['newUsers'] = $this->common_model->newUsersForCurrentMonth();
		// echo "<pre>"; print_r($data['newUsers']); die();
		$this->layouts->set_title('Manage User');
		$this->layouts->admin_view('usermanagement/index',array(),$data);
		
	}
	// END OF FUNCTION

	
	/* * *********************************************************************
	 * * Function name 	: addeditdata
	 * * Developed By 	: Megha Kumari
	 * * Purpose  		: This function used for add edit data
	 * * Date 			: 27 JUNE 2022
	 * * **********************************************************************/
	public function addeditdata($editId = '') 
	{  
		$uri = service('uri');
		$editId = $uri->getSegment(4); 
		$data['EDITDATA']		=	$this->common_model->getDataByParticularField('admin','admin_id',(int)$editId);
			$data['PERMISSIONS']	=	$this->common_model->getAllDataByParticularField('module_permission','user_id',(int)$editId);
	
        if($editId):
			$this->admin_model->authCheck('edit_data');
			else :
			$this->admin_model->authCheck('add_data');
		endif;
        if($this->request->getPost('SaveChanges')):
			$error					=	'NO';
        
			$validationRules = [
				'admin_first_name' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'First Name is required'
					]
				],
				'admin_last_name' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Last Name is required'
					]
				],
				'admin_email' => [
					'rules' => 'required|valid_email',
					'errors' => [
						'required' => 'Email is required',
						'valid_email' => 'Enter a valid email address'
					]
				]
			];
			if (!$this->validate($validationRules)) {
				return redirect()->back()->withInput()
					->with('validation', $this->validation); // ✅ Store validation errors
			}
			    $param['admin_first_name']				= $this->request->getPost('admin_first_name');
                $param['admin_middle_name']             = $this->request->getPost('admin_middle_name');
                $param['admin_last_name']               = $this->request->getPost('admin_last_name');
                $param['admin_email']                   = $this->request->getPost('admin_email');
				$param['role_id']                       = intOrNull($this->request->getPost('role_id'));
                $param['admin_type']                    = 'Super Admin';
				$param['creation_ip']					= currentIp();
				
                if ($this->request->getPost('admin_password') !== '') {
					// Update the password only if it's not empty
					$param['admin_password'] = password_hash($this->request->getPost('admin_password'), PASSWORD_DEFAULT);
				}
				if($this->request->getPost('CurrentDataID') ==''):
					// $param['ip_address']		=	currentIp();
					//$param['creation_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['created_by']		=	(int)$this->session->get('ILCADM_ADMIN_ID');
					$param['creation_date']		= 	date('Y-m-d h:i:s');
					$param['role_id'] = intOrNull($this->request->getPost('role_id'));
					
					// $param['is_active']			=	'A'; 
					$alastInsertId				=	$this->common_model->addData('admin',$param);
					// $role_permissions = $this->common_model->getAllDataByParticularField('role_permission', 'role_id', $param['role_id']);
					
					// $all_permissions = $this->request->getPost('permission');
					// if (!empty($role_permissions)) {
					// 	foreach ($role_permissions as $permission) {
					// 		$p_array = ['user_id' => $alastInsertId, 'permission' => $permission['permission']];
					// 		$save_permission = $this->common_model->addData('module_permission', $p_array);
					// 	}
					// }
					$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
				
				else:
					$Id							=	$this->request->getPost('CurrentDataID');
					//$param['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['modified_date']		= 	date('Y-m-d h:i:s');
					$param['role_id'] = intOrNull($this->request->getPost('role_id'));
					$this->common_model->editData('admin',$param,'admin_id',(int)$Id);
					// $this->common_model->deleteAllPermission($Id);
					// $role_permissions = $this->common_model->getAllDataByParticularField('role_permission', 'role_id', $param['role_id']);
					// $all_permissions = $this->request->getPost('permission');
					// if (!empty($role_permissions)) {
					// 	foreach ($role_permissions as $permission) {
					// 		$p_array = ['user_id' => $Id, 'permission' => $permission['permission']];
					// 		$save_permission = $this->common_model->addData('module_permission', $p_array);
					// 	}
					// }
					$this->session->setFlashdata('alert_success',lang('statictext_lang.updatesuccess'));
                    
				endif;
				return redirect()->to(getCurrentControllerPath('index'));
			endif;
       

		$data['roles'] = $this->common_model->getAllData('role_tbl');
		//echo"here2";die;
		$this->layouts->set_title('Manage User');
		return $this->layouts->admin_view('usermanagement/addeditdata', [], $data);
	}
	
	
	/***********************************************************************
	** Function name : validate_checkboxes
	** Developed By : Megha Kumari
	** Purpose  : This function used for check validation
	** Date : 10 MAY 2023
	************************************************************************/

    public function validate_checkboxes($value) 
	{
		if ($value !== 'yes' && $value !== 'no' ) {
		  $this->form_validation->set_message('validate_checkboxes', 'Please select either Yes or No.');
		  return FALSE;
		}
		return TRUE;
	}

	/***********************************************************************
	** Function name : changestatus
	** Developed By : Megha Kumari
	** Purpose  : This function used for change status
	** Date : 25 JUNE 2022
	************************************************************************/
		function changestatus($changeStatusId='',$statusType='')
	{  
		$uri = service('uri');
		$changeStatusId = $uri->getSegment(4); 
		$statusType = $uri->getSegment(5);   
		$this->admin_model->authCheck('edit_data');
		$param['is_active']		=	$statusType;
		$this->common_model->editData('admin',$param,'admin_id',(int)$changeStatusId);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.statussuccess'));
		
		return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
	}
	/***********************************************************************
	** Function name 	: deletedata
	** Developed By 	: Megha Kumari
	** Purpose  		: This function used for delete data
	** Date 			: 27 JUNE 2022
	************************************************************************/
	function deletedata($deleteId='')
	{  
		$uri = service('uri');
		$deleteId = $uri->getSegment(4);  
		$this->admin_model->authCheck('delete_data');

		$this->common_model->deleteData('admin','admin_id',(int)$deleteId);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.deletesuccess'));
		
		return redirect()->to(getCurrentControllerPath('index'));
		
	}

	// public function get_permission_data(){
	// 	$permissionId = $_GET['permissionId'];
	// 	$permissionData = $this->admin_model->permission_detail($permissionId);
		
	// 	// echo "<prE>";print_r($_GET);die;
	// 	// echo"<pre>";print_r($permissionData);die;
	// }

	

}