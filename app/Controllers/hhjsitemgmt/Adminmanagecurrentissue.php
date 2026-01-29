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
class Adminmanagecurrentissue extends BaseController {
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
		helper(['common','general']);
	} 

	
	public function index()
	{	
		
        // $this->admin_model->authCheck('view_data');
		// $this->admin_model->getPermissionType($data); 
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanagecurrentissue';
		$data['activeSubMenu'] 				= 	'adminmanagecurrentissue';
		
		// if($this->request->getGet('searchValue')):
		// 	$sValue							=	$this->request->getGet('searchValue');
		// 	$whereCon['like']			 	= 	"(ftable.name LIKE '%".$sValue."%'
        //                                          OR ftable.jazz_source LIKE '%".$sValue."%' )";
		// 	$data['searchField'] 			= 	$sField;
		// 	$data['searchValue'] 			= 	$sValue;
		// else:
		// 	$whereCon['like']		 		= 	"";
		// 	$data['searchField'] 			= 	'';
		// 	$data['searchValue'] 			= 	'';
		// endif;
				
		$whereCon['where']		 			= 	"";		
		$shortField 						= 	"ftable.id DESC";
		
		$baseUrl 							= 	getCurrentControllerPath('index');
		$this->session->set('userILCADMData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'current_issue_tbl as ftable';
		$con 								= 	'';
		//echo $tblName; die();
		$totalRows 							= 	$this->common_model->getData('count',$tblName,$whereCon,$shortField,'0','0');
		
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
       //  echo"<pre>";print_r($data['ALLDATA']);die;
		$this->layouts->set_title('Manage Current Issue');
		$this->layouts->admin_view('current_issue/index',array(),$data);
	}	// END OF FUNCTION
	
	
	public function addeditdata($editId='')
	{		
		$uri = service('uri');
		$editId = $uri->getSegment(4); 
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanagecurrentissue';
		$data['activeSubMenu'] 				= 	'adminmanagecurrentissue';
		$data['EDITDATA']		=	$this->common_model->getDataByParticularField('current_issue_tbl','id',(int)$editId);
		if($editId):
			$this->admin_model->authCheck('edit_data');
			
		else:
			$this->admin_model->authCheck('add_data');
		endif;

		if($this->request->getPost('SaveChanges')):
			
			$error					=	'NO';
			$validation = \Config\Services::validation(); 
            $validationRules = [
                    'name' => 'trim|required|max_length[256]',
                ];
				$validation->setRules($validationRules);
                if (!$validation->withRequest($this->request)->run()) {
                    // $this->session->setFlashdata('alert_error', 'Please enter all details');
                    return redirect()->back()->withInput()->with('validation',$validation);
                }
                
                $param['name']			= 	$this->request->getPost('name');
				if($this->request->getPost('CurrentDataID') ==''):
					
					// $param['creation_date']				= 	date('Y-m-d h:i:s');
					$param['is_active']			=	'1'; 
					$alastInsertId				=	$this->common_model->addData('current_issue_tbl',$param);
					$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
				else:
					$Id							=	$this->request->getPost('CurrentDataID');
					//$param['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					// $param['modified_date']				= 	date('Y-m-d h:i:s');
					$this->common_model->editData('current_issue_tbl',$param,'id',(int)$Id);
					$this->session->setFlashdata('alert_success',lang('statictext_lang.updatesuccess'));
				endif;
				return redirect()->to(getCurrentControllerPath('index'));
			
		endif;
        $data['venues'] = $this->common_model->getCategory();
        $this->layouts->set_title('Manage Jazz Types');
		$this->layouts->admin_view('current_issue/addeditdata',array(),$data);
	}	// END OF FUNCTION	


	
		function changestatus($changeStatusId='',$statusType='')
	{  
		$uri = service('uri');
		$changeStatusId = $uri->getSegment(4); 
		$statusType = $uri->getSegment(5);   
		$this->admin_model->authCheck('edit_data');
		$param['is_active']		=	$statusType;
		$this->common_model->editData('current_issue_tbl',$param,'id',(int)$changeStatusId);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.statussuccess'));
		
		return redirect()->to(getCurrentControllerPath('index'));
	}
	
	function deletedata($deleteId='')
	{  
		$uri = service('uri');
		$deleteId = $uri->getSegment(4);  
		$uri = service('uri');
		$deleteId = $uri->getSegment(4);  
		$this->admin_model->authCheck('delete_data');

		$this->common_model->deleteData('current_issue_tbl','id',(int)$deleteId);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.deletesuccess'));
		
		return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
	}

}