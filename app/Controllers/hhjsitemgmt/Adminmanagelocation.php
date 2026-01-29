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

class Adminmanagelocation extends BaseController
{
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
		$this->admin_model = new AdminModel();
		$this->emailTemplateModel = new EmailtemplateModel();
        $this->smsModel = new SmsModel();
        $this->notificationModel = new NotificationModel();
		$this->common_model = new CommonModel();
		$this->session = session();
		$this->layouts = new Layouts();
	    $this->lang = service('language'); 
        $this->lang->setLocale('admin');
		$this->validation = service('validation');
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
		$data['activeMenu'] 				= 	'adminmanagelocation';
		$data['activeSubMenu'] 				= 	'adminmanagelocation';

		if ($this->request->getGet('searchValue')) :
			$sValue = $this->request->getGet('searchValue');
		
			
			$whereCon['like'] = [
				'ftable.name' => $sValue,
				'ftable.address' => $sValue
			];
		
			$data['searchField'] = $sField ?? ''; 
			$data['searchValue'] = $sValue;
			
		else :
			$whereCon['like'] = [];
			$data['searchField'] = '';
			$data['searchValue'] = '';
		endif;
		

		$whereCon['where']		 			= 	"";
		$shortField 						= 	"ftable.id DESC";

		$baseUrl 							= 	getCurrentControllerPath('index');
		$this->session->set('userILCADMData', currentFullUrl());
		$qStringdata						=	explode('?', currentFullUrl());
		//$suffix								= 	$qStringdata[1] ? '?' . $qStringdata[1] : '';
		$suffix = isset($qStringdata[1]) && !empty($qStringdata[1]) ? '?' . $qStringdata[1] : '';
		$tblName 							= 	'location_tbl as ftable';
		$con 								= 	'';
		
		$totalRows 							= 	$this->common_model->getData('count', $tblName, $whereCon, $shortField, '0', '0');

		// if ($this->request->getGet('showLength') == 'All') :
		// 	$perPage	 					= 	$totalRows;
		// 	$data['perpage'] 				= 	$this->request->getGet('showLength');
		// elseif ($this->request->getGet('showLength')) :
		// 	$perPage	 					= 	$this->request->getGet('showLength');
		// 	$data['perpage'] 				= 	$this->request->getGet('showLength');
		// else :
		// 	$perPage			= 	SHOW_NO_OF_DATA;
		// 	$data['perpage'] 				= 	SHOW_NO_OF_DATA;
		// endif;
		// $uriSegment 						= 	getUrlSegment();
		// $data['PAGINATION']					=	adminPagination($baseUrl, $suffix, $totalRows, $perPage, $uriSegment);

		// if ($this->request->getUri()->getSegment($uriSegment)) :
		// 	$page = $this->request->getUri()->getSegment($uriSegment);
		// else :
		// 	$page = 0;
		// endif;

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
		
		$data['ALLDATA'] = $this->common_model->getData('multiple', $tblName, $whereCon, $shortField, $perPage, $offset);
		$this->layouts->set_title('Manage Location');
		$this->layouts->admin_view('location/index', array(), $data);
	}	// END OF FUNCTION

	/* * *********************************************************************
	 * * Function name 	: addeditdata
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function used for add edit data
	 * * Date 			: 27 JUNE 2022
	 * * **********************************************************************/
		// END OF FUNCTION	
    public function addeditdata($editId = '')
	{
		$uri = service('uri');
		$editId = $uri->getSegment(4);
		$data['error'] = '';
		$data['activeMenu'] = 'adminmanagelocation';
		$data['activeSubMenu'] = 'adminmanagelocation';

		if ($editId) {
			$this->admin_model->authCheck('edit_data');
			$data['EDITDATA'] = $this->common_model->getDataByParticularField('location_tbl', 'id', (int) $editId);
		} else {
			$this->admin_model->authCheck('add_data');
		}

		if ($this->request->getPost('SaveChanges')) {
			$error = 'NO';

			$rules = [
				'name'    => 'required|max_length[256]',
				'address' => 'required',
				'info'    => 'required',
				'latitude' => 'required',
				'longitude' => 'required',
			];
			$messages = [
				'name' => [
					'required'   => 'The Name field is required.',
					'max_length' => 'The Name cannot exceed 256 characters.',
				],
				'address' => ['required' => 'The Address field is required.'],
				'info'    => ['required' => 'The Info field is required.'],
				'latitude' => ['required' => 'Please select location properly.'],
				'longitude' => ['required' => 'Please select location properly.'],
			];


			if (! $this->validate($rules, $messages)) {
				$error = 'YES';
				// ❗️Key change: redirect back WITH input + validation in flashdata
				return redirect()->back()->withInput()->with('validation', $this->validation)->with('errors', $this->validator->getErrors());
			}

			if ($error == 'NO') {
				
			// if ($this->form_validation->run() && $error == 'NO') :
             // echo"<pre>";print_r($this->request->getPost());die;
				$param['name']			= 	$this->request->getPost('name');
				$param['address']		= 	$this->request->getPost('address');
				$param['latitude']		= 	$this->request->getPost('latitude');
				$param['longitude']		= 	$this->request->getPost('longitude');
				$param['zipcode']		= 	$this->request->getPost('zipcode');
				$param['info']		    = 	$this->request->getPost('info');

				if ($this->request->getPost('CurrentDataID') == '') {

					//$param['creation_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					// $param['created_by']		=	(int)$this->session->get('ILCADM_ADMIN_ID');
					$param['creation_date']				= 	date('Y-m-d h:i:s');
					$param['is_active']			=	'1';
					$alastInsertId				=	$this->common_model->addData('location_tbl', $param);
					$this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));
				} else {
					// Update Existing Record
					$Id = $this->request->getPost('CurrentDataID');
					$param['modified_date'] = date('Y-m-d h:i:s');
					$this->common_model->editData('location_tbl', $param, 'id', (int) $Id);
					$this->session->setFlashdata('alert_success', lang('statictext_lang.updatesuccess'));
				}

				return redirect()->to(getCurrentControllerPath('index'));
			}
		}

		$this->layouts->set_title('Manage Location');
		$this->layouts->admin_view('location/addeditdata', [], $data);
	}


	/***********************************************************************
	 ** Function name : changestatus
	 ** Developed By : Manoj Kumar
	 ** Purpose  : This function used for change status
	 ** Date : 25 JUNE 2022
	 ************************************************************************/
	function changestatus($changeStatusId = '', $statusType = '')
	{
		$uri = service('uri');
		$changeStatusId = $uri->getSegment(4); 
		$statusType = $uri->getSegment(5);
		$this->admin_model->authCheck('edit_data');
		$param['is_active']		=	$statusType;
		$this->common_model->editData('location_tbl', $param, 'id', (int)$changeStatusId);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));

		return redirect()->to(getCurrentControllerPath('index'));
	}
	/***********************************************************************
	 ** Function name 	: deletedata
	 ** Developed By 	: Afsar Ali
	 ** Purpose  		: This function used for delete data
	 ** Date 			: 27 JUNE 2022
	 ************************************************************************/
	function deletedata($deleteId = '')
	{
		$uri = service('uri');
		$deleteId = $uri->getSegment(4); 
		$this->admin_model->authCheck('delete_data');

		$this->common_model->deleteData('location_tbl', 'id', (int)$deleteId);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.deletesuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}
}
