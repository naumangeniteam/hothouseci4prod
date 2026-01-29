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
class Adminmanagehomeimage extends BaseController {
	protected $admin_model;
    protected $emailTemplateModel;
    protected $smsModel;
    protected $notificationModel;
	protected $common_model;
	protected $session;
	protected $layouts;
	protected $lang;
	protected $uri;

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
		$this->uri = service('uri');
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
		$data['activeMenu'] 				= 	'adminmanagehomeimage';
		$data['activeSubMenu'] 				= 	'adminmanagehomeimage';
	
		if($this->request->getGet('searchValue')):
			$svalue1=$this->request->getGet('searchValue');
			$sValue = strtolower(trim($this->request->getGet('searchValue'))); // Normalize input

			// Convert 'inactive' to 0 and 'active' to 1
			if ($sValue === 'inactive') {
				$sValue = '0';
			} elseif ($sValue === 'active') {
				$sValue = '1';
			}
			$whereCon['like'] = [
				'ftable.is_active' => $sValue
			  ];									
			$data['searchField'] = $sField ?? '';
			$data['searchValue'] 			= 	$svalue1;
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
		$tblName 							= 	'home_image as ftable';
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
		$this->layouts->set_title('Manage Home Page Image');
		$this->layouts->admin_view('homepageimage/index',array(),$data);
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
		$data['activeMenu'] 				= 	'adminmanagehomeimage';
		$data['activeSubMenu'] 				= 	'adminmanagehomeimage';
		$data['EDITDATA']		=	$this->common_model->getDataByParticularField('home_image','id',(int)$editId);
		
		if($editId):
			$this->admin_model->authCheck('edit_data');
		else:
			$this->admin_model->authCheck('add_data');
		endif;

		if($this->request->getPost('SaveChanges')):
			$validation = \Config\Services::validation();
			$postData = $this->request->getPost();
			$error = 'NO';
	
			// Define validation rules
			$rules = [
				'image_weblink'    => 'required',
				'image2_weblink'   => 'required',
				'month'            => 'required'
			];
	
			$messages = [
				'image_weblink'  => ['required' => 'The Image Weblink is required.'],
				'image2_weblink' => ['required' => 'The Image2 Weblink is required.'],
				'month'          => ['required' => 'The Month & Year is required.']
			];
	
			// Image validation (only required if no web links are given)
			if (empty($postData['image_weblink']) && empty($_FILES['image']['name'])) {
				if($this->request->getFile('image')){
					session()->setFlashdata('alert_error', lang('uploadimgerror'));
				}
				$rules['image'] = 'uploaded[image]|is_image[image]';
				$messages['image'] = [
					'uploaded' => 'Image is required when no Image Weblink is provided.',
					'is_image' => 'Please upload a valid image file.',
				];
			}
	
			if (empty($postData['image2_weblink']) && empty($_FILES['image2']['name'])) {
				$rules['image2'] = 'uploaded[image2]|is_image[image2]';
				$messages['image2'] = [
					'uploaded' => 'Image2 is required when no Image2 Weblink is provided.',
					'is_image' => 'Please upload a valid image file.',
				];
			}
	
			// Set validation rules and messages
			$validation->setRules($rules, $messages);
	
			if (!$validation->withRequest($this->request)->run()) {
				return redirect()->back()->withInput()->with('errors', $validation->getErrors());
			}
	
			// File Upload Handling
			$param = [];
	
			// Upload first image
			$file1 = $this->request->getFile('image');
			if ($file1 && $file1->isValid() && !$file1->hasMoved()) {
				$newName1 = $file1->getRandomName();
				$file1->move('assets/front/img/homeimage', $newName1);
				$param['image'] = $newName1;
			}
	
			// Upload second image
			$file2 = $this->request->getFile('image2');
			if ($file2 && $file2->isValid() && !$file2->hasMoved()) {
				$newName2 = $file2->getRandomName();
				$file2->move('assets/front/img/homeimage', $newName2);
				$param['image2'] = $newName2;
			}
	
			// Prepare data to insert/update
			$param['image_weblink']  = $postData['image_weblink'];
			$param['image2_weblink'] = $postData['image2_weblink'];
			$param['month']          = $postData['month'];

				if($this->request->getPost('CurrentDataID') ==''):
					$param['ip_address']		=	currentIp();
					//$param['creation_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['created_by']		=	(int)$this->session->get('ILCADM_ADMIN_ID');
					$param['creation_date']				= 	date('Y-m-d h:i:s');
					$param['is_active']			=	'1'; 
					$alastInsertId				=	$this->common_model->addData('home_image',$param);
					$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
				else:
					$Id							=	$this->request->getPost('CurrentDataID');
					//$param['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['modified_date']				= 	date('Y-m-d h:i:s');
					$this->common_model->editData('home_image',$param,'id',(int)$Id);
					$this->session->setFlashdata('alert_success',lang('statictext_lang.updatesuccess'));
				endif;
				return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
			endif;
		
		$this->layouts->set_title('Manage Home Page Image');
		$this->layouts->admin_view('homepageimage/addeditdata',array(),$data);
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
		$this->common_model->editData('home_image',$param,'id',(int)$changeStatusId);
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

		$this->common_model->deleteData('home_image','id',(int)$deleteId);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.deletesuccess'));
		
		return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
	}

}