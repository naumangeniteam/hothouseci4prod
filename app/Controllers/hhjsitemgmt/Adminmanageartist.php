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
class Adminmanageartist extends BaseController
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
		helper(['common','general']);
		$this->uri = service('uri');
		$this->lang = service('language'); 
		$this->lang->setLocale('admin');
		$this->layouts = new Layouts();
	}

	/* * *********************************************************************
	 * * Function name 	: index
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function used for IL Talks
	 * * Date 			: 27 JUNE 2022
	 * * **********************************************************************/
	public function index()
	{

		// $artists = $this->admin_model->getArtist();
		// // echo "<pre>"; print_r($artists); die();
		// $data['artists'] = $artists;
		$this->admin_model->authCheck('view_data');
		$this->admin_model->getPermissionType($data);
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanageartist';
		$data['activeSubMenu'] 				= 	'adminmanageartist';

		if($this->request->getGet('searchValue')):
			$sValue							=	$this->request->getGet('searchValue');
			
			$whereCon['like'] = [
			  'ftable.artist_name' => $sValue,
			   'ftable.artist_bio'  => $sValue
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
		$this->session->set('userILCADMData', currentFullUrl());
		$qStringdata						=	explode('?', currentFullUrl());
		//$suffix								= 	$qStringdata[1] ? '?' . $qStringdata[1] : '';
		$suffix = isset($qStringdata[1]) && !empty($qStringdata[1]) ? '?' . $qStringdata[1] : '';
		$tblName 							= 	'artist_tbl as ftable';
		$con 								= 	'';

		// print_r($whereCon);

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

		// echo"<pre>"; print_r($data['ALLDATA']); die;

		$this->layouts->set_title('Manage Artist');
		$this->layouts->admin_view('artist/index', array(), $data);
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
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanageartist';
		$data['activeSubMenu'] 				= 	'adminmanageartist';
		$data['EDITDATA']		=	$this->common_model->getDataByParticularField('artist_tbl', 'id', (int)$editId);
		
		if ($editId):
			$this->admin_model->authCheck('edit_data');
			else:
			$this->admin_model->authCheck('add_data');
		endif;

		if ($this->request->getPost('SaveChanges')):

			$validation = \Config\Services::validation(); 
			$rules = [
				'artist_name' => 'trim|required|max_length[256]',
			];
				$messages = [
					'artist_name' => [
						'required'   => 'Artist Name is required.',
						'max_length' => 'Artist Name must not exceed 256 characters.'
					]
				];
		
				// Set validation rules and messages
				$validation->setRules($rules, $messages);
		
				if (!$validation->withRequest($this->request)->run()) {
					return redirect()->back()->withInput()->with('validation', $validation);
				}
		
				// File Upload Handling
				$param = [];
		
				// Upload Artist Image
				$file1 = $this->request->getFile('artist_image');
				if ($file1 && $file1->isValid() && !$file1->hasMoved()) {
					$newName1 = $file1->getRandomName();
					$file1->move('assets/front/img/artistimage', $newName1);
					$param['artist_image'] = $newName1;
				}
		
				// Upload Cover Image
				$file2 = $this->request->getFile('cover_image');
				if ($file2 && $file2->isValid() && !$file2->hasMoved()) {
					$newName2 = $file2->getRandomName();
					$file2->move('assets/front/img/artistimage', $newName2);
					$param['cover_image'] = $newName2;
				}
		
				$param['artist_name']			= 	$this->request->getPost('artist_name');
				$param['buy_now_link']			= 	$this->request->getPost('buy_now_link');
				$param['website_link']		    = 	$this->request->getPost('website_link');
				$param['artist_bio']			= 	$this->request->getPost('artist_bio');
				$param['artist_url']			= 	$this->request->getPost('artist_url');
				$param['cover_url']				= 	$this->request->getPost('cover_url');
				if ($this->request->getPost('CurrentDataID') == ''):

					//$param['creation_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					// $param['created_by']		=	(int)$this->session->get('ILCADM_ADMIN_ID');
					$param['creation_date']				= 	date('Y-m-d h:i:s');
					$param['is_active']			=	'1';
					$alastInsertId				=	$this->common_model->addData('artist_tbl', $param);
					$this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));
				else:
					$Id							=	$this->request->getPost('CurrentDataID');
					//$param['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['modified_date']				= 	date('Y-m-d h:i:s');
					$this->common_model->editData('artist_tbl', $param, 'id', (int)$Id);
					$this->session->setFlashdata('alert_success', lang('statictext_lang.updatesuccess'));
				endif;
				return redirect()->to(getCurrentControllerPath('index'));
			endif;
		
		$data['venues'] = $this->common_model->getCategory(false);
		$this->layouts->set_title('Manage Artist');
		$this->layouts->admin_view('artist/addeditdata', array(), $data);
	}	// END OF FUNCTION	


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
		$this->common_model->editData('artist_tbl', $param, 'id', (int)$changeStatusId);
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
		$deleteId = $this->uri->getSegment(4);
		$this->admin_model->authCheck('delete_data');

		$this->common_model->deleteData('artist_tbl', 'id', (int)$deleteId);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.deletesuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}

	function mutlipleChangeStatus()
	{
		$changeStatusIds = json_decode($_POST['changeStatusIds']);
		$statusType = $_POST['statusType'];
		// echo"<pre>";  print_r($changeStatusIds); die;
		// print_r($changeStatusIds); die;
		if ($statusType !== "permanentdelete") {
			// $this->admin_model->authCheck('edit_data');
			foreach ($changeStatusIds as $changeStatusId) {
				// $param['is_active'] = $statusType;
				if ($statusType == 'inactive') {
					$param['is_active'] = 0;
				} else if ($statusType == 'active') {
					$param['is_active'] = 1;
				}

				$this->common_model->editData('artist_tbl', $param, 'id', (int)$changeStatusId);
				$this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));
			}
		} else {
			foreach ($changeStatusIds as $changeStatusId) {
				$this->admin_model->authCheck('delete_data');
				$this->common_model->deleteData('artist_tbl', 'id', (int)$changeStatusId);

				$this->session->setFlashdata('alert_success', lang('statictext_lang.deletesuccess'));
			}
		}
}
}