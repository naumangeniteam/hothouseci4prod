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
class Adminmanageseo extends BaseController {
	protected $admin_model;
    protected $emailTemplateModel;
    protected $smsModel;
    protected $notificationModel;
	protected $common_model;
	protected $session;
	protected $layouts;
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
       
		$this->admin_model->authCheck('view_data');
		$this->admin_model->getPermissionType($data); 
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanageseo';
		$data['activeSubMenu'] 				= 	'adminmanageseo';
		
		if($this->request->getGet('searchValue')):
			$sValue							=	$this->request->getGet('searchValue');
			$whereCon['like'] = [
				'ftable.title' => $sValue,
				'ftable.page_name' => $sValue
			];
			
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
		$tblName 							= 	'seo_tbl as ftable';
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
		//echo "<pre>"; print_r($data['ALLDATA']); die();
		$this->layouts->set_title('Manage Seo Section');
		$this->layouts->admin_view('seo/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name 	: addeditdata
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function used for add edit data
	 * * Date 			: 27 JUNE 2022
	 * * **********************************************************************/
	public function addeditdata($editId='')
	{		
		$uri = service('uri');
		$editId = $uri->getSegment(4);
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanageseo';
		$data['activeSubMenu'] 				= 	'adminmanageseo';
		
		if($editId):
			$this->admin_model->authCheck('edit_data');
			$data['EDITDATA']		=	$this->common_model->getDataByParticularField('seo_tbl','id',(int)$editId);
		else:
			$this->admin_model->authCheck('add_data');
		endif;

		if($this->request->getPost('SaveChanges')):
           
			//print_r($this->request->getPost('cropImage')); die();
           $error					=	'NO';
			
			$validation = \Config\Services::validation();

    $rules = [
        'title' => [
            'rules' => 'trim|required|max_length[255]',
            'errors' => [
                'required' => 'The Title field is required.',
                'max_length' => 'The Title must not exceed 255 characters.'
            ]
        ],
        'page_name' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'The Page field is required.'
            ]
        ]
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('validation', $validation);
    }
              
					
				$param['page_name']				= 	$this->request->getPost('page_name');	
                $param['title']				= 	$this->request->getPost('title');	
                $param['keywords']				= 	$this->request->getPost('keyword');	
                $param['description']				= 	$this->request->getPost('description');	
				
              
				if($this->request->getPost('CurrentDataID') ==''):
            
					$param['creation_ip']		=	currentIp();
					//$param['created_at']		=	(int)$this->timezone->utc_time();//currentDateTime();
					//$param['created_at']		=	(int)$this->session->get('ILCADM_ADMIN_ID');
                    $param['created_by']		=	(int)$this->session->get('ILCADM_ADMIN_ID');
					$param['created_at']				= 	date('Y-m-d h:i:s');
					 $param['updated_at']				= 	date('Y-m-d h:i:s');
                     $param['updated_by']		=	(int)$this->session->get('ILCADM_ADMIN_ID'); 
					$param['is_active']			=	'1'; 
					$alastInsertId				=	$this->common_model->addData('seo_tbl',$param);
					$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
				else:
                   
					$Id							=	$this->request->getPost('CurrentDataID');
					$param['updation_ip']			=	currentIp();
					//$param['updated_at']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['updated_by']		=	(int)$this->session->get('ILCADM_ADMIN_ID');  
					$param['updated_at']				= 	date('Y-m-d h:i:s');
					$this->common_model->editData('seo_tbl',$param,'id',(int)$Id);
					$this->session->setFlashdata('alert_success',lang('statictext_lang.updatesuccess'));
				endif;
				return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
			endif;
		
		$this->layouts->set_title('Manage Seo Section');
		$this->layouts->admin_view('seo/addeditdata',array(),$data);
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
		$this->common_model->editData('seo_tbl',$param,'id',(int)$changeStatusId);
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
		$this->admin_model->authCheck('delete_data');

		$this->common_model->deleteData('seo_tbl','id',(int)$deleteId);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.deletesuccess'));
		
		return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
	}

}