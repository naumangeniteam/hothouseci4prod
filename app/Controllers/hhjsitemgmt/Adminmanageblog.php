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
class Adminmanageblog extends BaseController {
	protected $admin_model;
    protected $emailTemplateModel;
    protected $smsModel;
    protected $notificationModel;
	protected $common_model;
	protected $session;
	protected $layouts;
	protected $uri;
	protected $lang;
	protected $db;
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
		$this->db = \Config\Database::connect();
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
		$data['activeMenu'] 				= 	'adminmanageblog';
		$data['activeSubMenu'] 				= 	'adminmanageblog';
		
		if($this->request->getGet('searchValue')):
			$sValue							=	$this->request->getGet('searchValue');
			$whereCon['like'] = [
				'ftable.page_title' => $sValue
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
		$tblName 							= 	'blog_tbl as ftable';
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
		if(isset($_POST['activebtn'])){
			if($this->request->getPost('checkbox') == true){
				$update = $this->request->getPost('checkbox');
				for ($i=0; $i < count($update) ; $i++) { 
					$data = array('is_home' => true);  
					
					$this->db->table('blog_tbl')->where('id', $update[$i])->update($data);
             
				}
				$this->session->setFlashdata('alert_success',lang('statictext_lang.statussuccess'));
				return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
			  }
		}
		if(isset($_POST['inactivebtn'])){
				if($this->request->getPost('uncheckbox') == true){
					$update = $this->request->getPost('uncheckbox');
					//print_r($update);die;
					for ($i=0; $i < count($update) ; $i++) { 
						$data = array('is_home' => false);    
					  
						$this->db->table('blog_tbl')->where('id', $update[$i])->update($data);           
					}
					$this->session->setFlashdata('alert_success',lang('statictext_lang.statussuccess'));
					return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
				}
				 
		}

		//echo "<pre>"; print_r($data['ALLDATA']); die();
		$this->layouts->set_title('Manage Blog');
		$this->layouts->admin_view('blog/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name 	: addeditdata
	 * * Developed By 	: Megha Kumari
	 * * Purpose  		: This function used for add edit data
	 * * Date 			: 27 JUNE 2022
	 * * **********************************************************************/
	public function addeditdata($editId = '')
	{
		helper(['form', 'url']);
		$data = [
			'error'        => '',
			'activeMenu'   => 'adminmanageblog',
			'activeSubMenu'=> 'adminmanageblog',
		];
		
		// $editId = $this->request->uri->getSegment(4);
		// dd($editId);
		$validation = \Config\Services::validation();

		if ($editId) {
			$this->admin_model->authCheck('edit_data');
			$data['EDITDATA'] = $this->common_model->getDataByParticularField('blog_tbl', 'id', (int)$editId);
		} else {
			$this->admin_model->authCheck('add_data');
		}

		if ($this->request->getPost('SaveChanges')) {
			$id = $this->request->getPost('CurrentDataID');
			$isEditing = !empty($id);

			// ✅ Validation Rules
			$validationRules = [
				'page_title'      => 'trim|required|max_length[256]',
				'content'         => 'trim|required',
				'position'        => $isEditing 
					? "trim|required|is_unique[blog_tbl.position,id,{$id}]"
					: 'trim|required|is_unique[blog_tbl.position]',
				'home_slider_yes' => 'required|in_list[yes,no]',
			];

			if (!$this->validate($validationRules)) {
				return redirect()->back()->withInput()->with('validation', $validation);
			}

			$param = [
				'page_title'   => $this->request->getPost('page_title'),
				'home_content' => $this->request->getPost('home_content'),
				'content'      => $this->request->getPost('content'),
				'position'     => $this->request->getPost('position'),
				'is_home'      => false,
				'slug'         => strtolower(url_title($this->request->getPost('page_title'))),
			];

			// ✅ Handle slider fields
			if ($this->request->getPost('home_slider_yes') === 'yes') {
				$param['slider_show'] = 'yes';
				$param['slider_position'] = $this->request->getPost('slider_position');
				$param['url'] = $this->request->getPost('url');
				$param['slider_content'] = $this->request->getPost('slider_content');

				$imageFile = $this->request->getFile('image');
				if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
					$newName = $imageFile->getRandomName();
					$imageFile->move(FCPATH . 'assets/front/img/homeimage', $newName);
					$param['image'] = $newName;
				}
			} else {
				$param['slider_show'] = 'no';
			}

			// ✅ Add or Update
			if (!$isEditing) {
				$param['ip_address']    = currentIp();
				$param['created_by']    = (int)session()->get('ILCADM_ADMIN_ID');
				$param['creation_date'] = date('Y-m-d H:i:s');
				$param['is_active']     = '1';

				$this->common_model->addData('blog_tbl', $param);
				session()->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));
			} else {
				$param['modified_date'] = date('Y-m-d H:i:s');
				$this->common_model->editData('blog_tbl', $param, 'id', (int)$id);
				session()->setFlashdata('alert_success', lang('statictext_lang.updatesuccess'));
			}

			return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
		}

		$this->layouts->set_title('Manage Blog');
		return $this->layouts->admin_view('blog/addeditdata', [], $data);
	}
	

	/***********************************************************************
	** Function name : validate_checkboxes
	** Developed By : Megha Kumari
	** Purpose  : This function used for check validation
	** Date : 10 MAY 2023
	************************************************************************/

	public function validate_checkboxes(string $str, string $fields, array $data): bool
    {
        return in_array($str, ['yes', 'no']);
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
		$this->common_model->editData('blog_tbl',$param,'id',(int)$changeStatusId);
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
		$uri = service('uri');
		$deleteId = $uri->getSegment(4);  
		$this->admin_model->authCheck('delete_data');

		$this->common_model->deleteData('blog_tbl','id',(int)$deleteId);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.deletesuccess'));
		
		return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
	}

}