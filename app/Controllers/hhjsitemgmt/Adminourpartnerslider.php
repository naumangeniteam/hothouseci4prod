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
class Adminourpartnerslider extends BaseController
{
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
		$this->admin_model = new AdminModel();
		$this->emailTemplateModel = new EmailtemplateModel();
        $this->smsModel = new SmsModel();
        $this->notificationModel = new NotificationModel();
		$this->common_model = new CommonModel();
		$this->session = session();
		$this->layouts = new Layouts();
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
		$data['activeMenu'] 				= 	'adminourpartnerslider';
		$data['activeSubMenu'] 				= 	'adminourpartnerslider';
		$whereCon = [];

		// ✅ Search Value (LIKE Query)
		if ($this->request->getGet('searchValue')) {
			$sValue = $this->request->getGet('searchValue');
			$whereCon['like'] = ['ftable.type' => $sValue];
			$data['searchValue'] = $sValue;
		} else {
			$whereCon['like'] = [];
			$data['searchValue'] = '';
		}
		
		// ✅ Filters for `page`, `type`, and `alignment`
		if ($this->request->getGet('pages')) {
			$whereCon['where']['ftable.page'] = $this->request->getGet('pages');
			$data['filterPage'] = $this->request->getGet('pages');
		} else {
			$data['filterPage'] = '';
		}

		if ($this->request->getGet('type')) {
			$whereCon['where']['ftable.type'] = $this->request->getGet('type');
			$data['filterType'] = $this->request->getGet('type');
		} else {
			$data['filterType'] = '';
		}
        if ($this->request->getGet('alignment')) {
			$whereCon['where']['ftable.alignment'] = $this->request->getGet('alignment');
			$data['filterAlignment'] = $this->request->getGet('alignment');
		} else {
			$data['filterAlignment'] = '';
		}
		//$whereCon['where']		 			= 	$where;

		$shortField 						= 	"ftable.order ASC";

		$baseUrl 							= 	base_url() . 'hhjsitemgmt/adminourpartnerslider/index';
		$this->session->set('userILCADMData', currentFullUrl());
		$qStringdata						=	explode('?', currentFullUrl());
		//$suffix								= 	$qStringdata[1] ? '?' . $qStringdata[1] : '';
		$suffix = isset($qStringdata[1]) && !empty($qStringdata[1]) ? '?' . $qStringdata[1] : '';
		$tblName 							= 	'slider_tbl as ftable';
		$con 								= 	'';
		//echo $tblName; die();
		$totalRows 							= 	$this->common_model->getData('count', $tblName, $whereCon, $shortField, '0', '0');

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

		$data['ALLDATA'] 					= 	$this->common_model->getData('multiple', $tblName, $whereCon, $shortField, $perPage, $offset);
		// echo "<pre>"; print_r($data['ALLDATA']); die();
		$this->layouts->set_title('Manage our Partner Slider');
		$this->layouts->admin_view('slider/index', array(), $data);
	}	// END OF FUNCTION

	/* * *********************************************************************
	 * * Function name 	: addeditdata
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function used for add edit data
	 * * Date 			: 27 JUNE 2022
	 * * **********************************************************************/

	
	public function addeditdata($editId = '')
	{
		
		$editId = $this->uri->getSegment(4);
		$data['error'] = '';
		$data['activeMenu'] = 'adminourpartnerslider';
		$data['activeSubMenu'] = 'adminourpartnerslider';
		$data['EDITDATA'] = $this->common_model->getDataByParticularField('slider_tbl', 'id', (int)$editId);
		if ($editId) :
			$this->admin_model->authCheck('edit_data');
			
		else :
			$this->admin_model->authCheck('add_data');
		endif;
	
	
		
		if ($this->request->getPost('SaveChanges')) {
			$validation = \Config\Services::validation();
			$postData = $this->request->getPost();
			$error = 'NO';
	
			// Define validation rules
			$rules = [
				'type'       => 'required'
			];
	
			$messages = [
				'type'       => ['required' => 'Type is required.']
			];

			// If slide_html is empty, require image and weblink
			$slideHtml = $this->request->getPost('slide_html');
			$visibleText = trim(strip_tags($slideHtml));

			if ($visibleText === '') {
				$rules['image']   = 'uploaded[image]|is_image[image]';
				$rules['weblink'] = 'required';
	
				$messages['image'] = [
					'uploaded' => 'Image is required when HTML Code is empty.',
					'is_image' => 'Please upload a valid image file.',
				];
				$messages['weblink'] = ['required' => 'Weblink is required when HTML Code is empty.'];
			}
	
			// If both image and slide_html are empty, enforce at least one is required
			if (empty($postData['image']) && $visibleText === '') {
				$rules['image'] = 'uploaded[image]|is_image[image]';
				$messages['image'] = [
					'uploaded' => 'Either an Image, Weblink, or HTML Code is required.',
					'is_image' => 'Please upload a valid image file.',
				];
			}
	
			// Additional fields validation for 'banner' type
			if ($postData['type'] == 'banner') {
				$rules['alignment'] = 'required';
				$rules['page']      = 'required';
				$rules['order']     = 'required';
	
				$messages['alignment'] = ['required' => 'Alignment is required for banners.'];
				$messages['page']      = ['required' => 'Page selection is required for banners.'];
				$messages['order']     = ['required' => 'Order is required for banners.'];
			}
	
			// Set validation rules and messages
			$validation->setRules($rules, $messages);
	
			if (!$validation->withRequest($this->request)->run()) {
				return redirect()->back()->withInput()->with('errors', $validation->getErrors());
			}
	
			// File Upload Handling
			$param = [];
			$file = $this->request->getFile('image');
			if ($file && $file->isValid() && !$file->hasMoved()) {
				$newName = $file->getRandomName();
				$file->move('assets/front/img/slider', $newName);
				$param['image'] = $newName;
			}
	
			// Prepare data to insert/update
			$param['weblink']     = $postData['weblink'];
			$param['type']        = $postData['type'];
			$param['page']        = $postData['page'];
			$param['slide_html']  = $postData['slide_html'];
	
			if ($postData['type'] == 'banner') {
				$param['alignment'] = $postData['alignment'];
				$param['order']     = $postData['order'];
			} else {
				$param['alignment'] = NULL;
				$param['order'] = NULL;
			}
	
				if ($this->request->getPost('CurrentDataID') == '') :
					$param['ip_address'] = currentIp();
					$param['created_by'] = (int)$this->session->get('ILCADM_ADMIN_ID');
					$param['creation_date'] = date('Y-m-d h:i:s');
					$param['is_active'] = '1';
					$this->common_model->addData('slider_tbl', $param);
					$this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));
				else :
					$Id = $this->request->getPost('CurrentDataID');
					$param['modified_date'] = date('Y-m-d h:i:s');
					$this->common_model->editData('slider_tbl', $param, 'id', (int)$Id);
					$this->session->setFlashdata('alert_success', lang('statictext_lang.updatesuccess'));
				endif;
	
				return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
			}
	
		$this->layouts->set_title('Manage Our Partner Slider');
	
		// Store validation object in $data before passing to view
		// $data['validation'] = $validation; 
	
		return $this->layouts->admin_view('slider/addeditdata',array(), $data);
	}
	
	

	/***********************************************************************
	 ** Function name : changestatus
	 ** Developed By : Manoj Kumar
	 ** Purpose  : This function used for change status
	 ** Date : 25 JUNE 2022
	 ************************************************************************/
	function changestatus($changeStatusId = '', $statusType = '')
	{
		$changeStatusId = $this->uri->getSegment(4); 
		$statusType = $this->uri->getSegment(5);
		$this->admin_model->authCheck('edit_data');
		$param['is_active']		=	$statusType;
		$this->common_model->editData('slider_tbl', $param, 'id', (int)$changeStatusId);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}
	/***********************************************************************
	 ** Function name 	: deletedata
	 ** Developed By 	: Afsar Ali
	 ** Purpose  		: This function used for delete data
	 ** Date 			: 27 JUNE 2022
	 ************************************************************************/
	function deletedata($deleteId = '')
	{
		$deleteId = $this->uri->getSegment(4);
		$this->admin_model->authCheck('delete_data');

		$this->common_model->deleteData('slider_tbl', 'id', (int)$deleteId);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.deletesuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}

	function duplicateBanner($duplicateId = '')
	{
		$duplicateId = $this->uri->getSegment(4);
		$this->admin_model->authCheck('add_data');
		$data = $this->common_model->getDataByParticularField('slider_tbl', 'id', (int)$duplicateId);

		   unset($data['id']); 
		//  echo"<pre>"; 	print_r($data); die;
		$this->common_model->DuplicateData('slider_tbl', $data);
		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));

	}
}
