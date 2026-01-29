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
class Adminmanagerole extends BaseController {
	protected $admin_model;
    protected $emailTemplateModel;
    protected $smsModel;
    protected $notificationModel;
	protected $common_model;
	protected $session;
	protected $layouts;
	protected $uri;
	protected $lang;
	protected $validation;


	public function  __construct() 
	{ 
		$this->uri = service('uri');
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
		helper(['common', 'general']);
		  $this->validation = service('validation');
	} 

	/* * *********************************************************************
	 * * Function name 	: index
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function used for IL Talks
	 * * Date 			: 27 JUNE 2022
	 * * **********************************************************************/
	public function index()
	{	
       
		$this->admin_model->authCheck('view_data');
		$this->admin_model->getPermissionType($data); 
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanagerole';
		$data['activeSubMenu'] 				= 	'adminmanagerole';
		
		if($this->request->getGet('searchValue')):
			$sValue							=	$this->request->getGet('searchValue');
			$sValue = $this->request->getGet('searchValue') ?? '';
			$whereCon = [];
		
			if (!empty($sValue)) {
				$whereCon['like'] = [
					'role_name' => $sValue
				];
			}
			//$whereCon['like']			 	= 	"(ftable.role_name LIKE '%".$sValue."%')";
			//$data['searchField'] 			= 	$sField;
			$data['searchField'] = $sField ?? ''; 
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;
				
		$whereCon['where']		 			= 	"";		
		$shortField 						= 	"ftable.id DESC";
		
		$baseUrl 							= 	getCurrentControllerPath('index');
		$this->session->set('userILCADMData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'role_tbl as ftable';
		$con 								= 	'';
		//echo $tblName; die();
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
		$this->layouts->set_title('Manage Role Section');
		$this->layouts->admin_view('role/index',array(),$data);
	}	// END OF FUNCTION


	/* * *********************************************************************
	 * * Function name 	: addeditdata
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function used for add edit data
	 * * Date 			: 27 JUNE 2022
	 * * **********************************************************************/
	public function addeditdata($editId='')
	{		
		$editId = $this->uri->getSegment(4); 
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanagerole';
		$data['activeSubMenu'] 				= 	'adminmanagerole';
		
		$data['EDITDATA']		=	$this->common_model->getDataByParticularField('role_tbl','id',(int)$editId);
		$data['PERMISSIONROLE']	=	$this->common_model->getAllDataByParticularField('role_permission','role_id',(int)$editId);

		if($editId):
			$this->admin_model->authCheck('edit_data');
			
            // echo"<pre>";print_r( $data['PERMISSIONROLE']);die;
			
		else:
			$this->admin_model->authCheck('add_data');
		endif;

		if($this->request->getPost('SaveChanges')):
           
			//print_r($this->request->getPost('cropImage')); die();
          
			$error					=	'NO';
			
            $validationRules = [
				'role_name' => 'required|trim|max_length[255]'
			];
			if (!$this->validate($validationRules)) {
				return redirect()->back()->withInput()
					->with('validation', $this->validation); // ✅ Store validation errors
			}
			

              
				$param['role_name']				= 	$this->request->getPost('role_name');	
              
				if($this->request->getPost('CurrentDataID') ==''):
                    
                    
					$param['creation_date']				= 	date('Y-m-d h:i:s');
                  
					$param['is_active']			=	'1'; 
					$alastInsertId				=	$this->common_model->addData('role_tbl',$param);
                    $all_permissions = $this->request->getPost('permission');
					
					if($all_permissions && count($all_permissions)):
						foreach($all_permissions as $permission){
                            
							$p_array = ['role_id'=>$alastInsertId,'permission'=>$permission];
							
							$save_permission = $this->common_model->addData('role_permission',$p_array);
							
						}
					endif;
					// echo "<pre>";print_r($_POST);die;
				
					$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
				else:
                   
					$Id							=	$this->request->getPost('CurrentDataID');
					$param['modified_date']				= 	date('Y-m-d h:i:s');
					$this->common_model->editData('role_tbl',$param,'id',(int)$Id);

                    $this->common_model->deleteAllPermissions($Id);
					$all_permissions = $this->request->getPost('permission');
					if(count($all_permissions)):
						foreach($all_permissions as $permission){
                           
							$p_array = ['role_id'=>$Id	,'permission'=>$permission];
							
							$save_permission = $this->common_model->addData('role_permission',$p_array);
						}
					endif;
					
					$this->session->setFlashdata('alert_success',lang('statictext_lang.updatesuccess'));
				endif;
				return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
			
		endif;

		$this->layouts->set_title('Manage Role Section');
		$this->layouts->admin_view('role/addeditdata',array(),$data);
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
		$param['is_active']		=	$statusType;
		$this->common_model->editData('role_tbl',$param,'id',(int)$changeStatusId);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.statussuccess'));
		
		return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
	}
	/***********************************************************************
	** Function name 	: deletedata
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for delete data
	** Date 			: 27 JUNE 2022
	************************************************************************/
	function deletedata($deleteId='')
	{  
		$uri = service('uri');
		$deleteId = $uri->getSegment(4);  
		$uri = service('uri');
		$deleteId = $uri->getSegment(4);  
		$this->admin_model->authCheck('delete_data');

		$this->common_model->deleteData('role_tbl','id',(int)$deleteId);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.deletesuccess'));
		
		return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
	}

   

}