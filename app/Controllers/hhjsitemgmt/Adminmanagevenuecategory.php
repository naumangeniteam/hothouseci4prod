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
use CodeIgniter\HTTP\RequestInterface;

class Adminmanagevenuecategory extends BaseController {
    
    protected $admin_model;
    protected $common_model;
    protected $session;
	protected $layouts;
	protected $lang;
    public function __construct() 
    { 
		
        helper(['common', 'url', 'form','general']);
        $this->admin_model = new AdminModel();
        $this->common_model = new CommonModel();
		$this->session = \Config\Services::session();
		$this->layouts = new Layouts();
		$this->lang = service('language'); 
		$this->lang->setLocale('admin');
		
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
		$data['activeMenu'] 				= 	'adminmanagevenuecategory';
		$data['activeSubMenu'] 				= 	'adminmanagevenuecategory';
		
		if ($this->request->getGet('searchValue')) {
			$sValue = $this->request->getGet('searchValue');
		
			// Use Query Builder for safe LIKE condition
			$whereCon['like'] = ['ftable.venue_title' => $sValue];
		
			$data['searchField'] = $sField ?? '';
			$data['searchValue'] = $sValue;
		} else {
			$whereCon['like'] = [];
			$data['searchField'] = '';
			$data['searchValue'] = '';
		}
				
		$whereCon['where']		 			= 	"";		
		$shortField 						= 	"ftable.id DESC";
		
		$baseUrl 							= 	getCurrentControllerPath('index');
		$this->session->set('userILCADMData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
	
		$suffix = isset($qStringdata[1]) && !empty($qStringdata[1]) ? '?' . $qStringdata[1] : '';
		$tblName 							= 	'venue_tbl as ftable';
		$con 								= 	'';
	
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
		// Fetch paginated data
		$data['ALLDATA'] = $this->common_model->getData('multiple', $tblName, $whereCon, $shortField, $perPage, $offset);

		//echo "<pre>"; print_r($data['ALLDATA']); die();
		$data['newVenues'] = $this->common_model->newVenuesForCurrentMonth();
		// echo "<pre>"; print_r($data['newVenues']); die();
		$this->layouts->set_title('Manage Venue Category');
		$this->layouts->admin_view('venuecategory/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name 	: addeditdata
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function used for add edit data
	 * * Date 			: 27 JUNE 2022
	 * * **********************************************************************/
	public function addeditdata($editId= null)
	{		
		
		$uri = service('uri');
		$editId = $uri->getSegment(4); 
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanagevenuecategory';
		$data['activeSubMenu'] 				= 	'adminmanagevenuecategory';
		
		if($editId):
			$this->admin_model->authCheck('edit_data');
			
			$data['EDITDATA']		=	$this->common_model->getDataByParticularField('venue_tbl','id',(int)$editId);
		else:
			
			$this->admin_model->authCheck('add_data');
		endif;
		
		if($this->request->getPost('SaveChanges')):
			
			//print_r($this->request->getPost('cropImage')); die();
			if($this->request->getPost('image') == ''){
				$this->session->setFlashdata('alert_error',lang('statictext_lang.uploadimgerror'));
			}
			$error					=	'NO';
			$validationRules = [
				'venue_title' => 'trim|required|max_length[256]',
				'position'    => 'required|numeric'
			];

			// ✅ Only require image upload if adding (no $editId)
			if (empty($editId)) {
				$validationRules['image'] = [
					'rules'  => 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/gif,image/webp]',
					'errors' => [
						'uploaded' => 'Please upload an image.',
						'is_image' => 'The file must be a valid image.',
						'mime_in'  => 'Allowed file types are jpg, jpeg, png, gif, webp.'
					]
				];
			} else {
				// On edit: only validate if a new file is uploaded
				if (!empty($_FILES['image']['name'])) {
					$validationRules['image'] = [
						'rules'  => 'is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/gif,image/webp]',
						'errors' => [
							'is_image' => 'The file must be a valid image.',
							'mime_in'  => 'Allowed file types are jpg, jpeg, png, gif, webp.'
						]
					];
				}
			}
				
			$validationMessages = [
				'venue_title' => [
					'required' => 'The Venue Title field is required.',
				],
				'position' => [
					'required' => 'The Position field is required.',
				]
			];
	
			if (!$this->validate($validationRules, $validationMessages)) {
				return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
			}
			/*if ($this->request->getPost('CurrentDataID') =='' && $this->request->getPost('cropImage') =='') {
				$this->form_validation->set_rules('cropImage', 'Image', 'trim');
			}*/
			
				// if(!empty($_FILES['image']['name'])){
				// 	$config = [
				// 			'upload_path'   => './assets/front/img/venue',
				// 			'allowed_types' => 'jpg|png|gif|jpeg|webp',
				// 			//'encrypt_name'  => TRUE
				// 		];
                //     $this->load->library('upload', $config); 
                //     $image = '';
                //     if($this->upload->do_upload('image')){
                //         $img_data = $this->upload->data();
                //         $param['image'] = $img_data['file_name'];	
                //     }
				
                // }
				$param = [];
				$imageFile = $this->request->getFile('image');
				if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
					$newName = $imageFile->getRandomName();
					$imageFile->move('assets/front/img/venue', $newName);
					$param['image'] = $newName;
				}
				
				$param['venue_title'] = $this->request->getPost('venue_title');
				$param['position'] = $this->request->getPost('position');
				$param['last_modified_date'] = date('Y-m-d H:i:s');
		
				// if ($this->request->getPost('CurrentDataID') == '') {
				if($editId == '')
				{
					// ✅ Insert New Record
					$param['ip_address'] = $this->request->getIPAddress();
					$param['created_by'] = (int) session()->get('ILCADM_ADMIN_ID');
					$param['creation_date'] = date('Y-m-d H:i:s');
					$param['is_active'] = '1';
					$this->common_model->addData('venue_tbl', $param);
					$this->session->setFlashdata('alert_success', lang('statictext_lang.admin/statictext.addsuccess'));
				} else {
					// ✅ Update Existing Record
					// $Id = (int) $this->request->getPost('CurrentDataID');
					$Id =$editId;
					$this->common_model->editData('venue_tbl', $param, 'id', $Id);
					$this->session->setFlashdata('alert_success', lang('statictext_lang.admin/statictext.updatesuccess'));
				}
				// return redirect()->to(base_url('admin/manageVenue'));
				return redirect()->to(getCurrentControllerPath('index'));
			
		endif;
		$this->layouts->set_title('Manage Venue Category');
		$this->layouts->admin_view('venuecategory/addeditdata',array(),$data);
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
		$this->common_model->editData('venue_tbl',$param,'id',(int)$changeStatusId);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.statussuccess'));
	
		return redirect()->to(getCurrentControllerPath('index'));
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

		$this->common_model->deleteData('venue_tbl','id',(int)$deleteId);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.deletesuccess'));
		return redirect()->to(getCurrentControllerPath('index'));
	}

}