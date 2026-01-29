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
class Adminmanagereportproblems extends BaseController {
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
		//$this->lang->load('statictext', 'admin');
		helper(['common', 'general', 'uri']);
		
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
		$data['activeMenu'] 				= 	'adminmanagereportproblems';
		$data['activeSubMenu'] 				= 	'adminmanagereportproblems';
		$sValue = $this->request->getGet('searchValue') ?? '';
		if (!empty($sValue)) {
				// ✅ Corrected search condition
				$whereCon['like'] = ["ftable.name" => $sValue];
		} else {
				$whereCon['like'] = [];
		}
		$data['searchValue'] = $sValue;
		
		$whereCon['where'] = [];	
		$whereCon['where']		 			= 	"";		
		$shortField 						= 	"ftable.id DESC";
		
		$baseUrl 							= 	getCurrentControllerPath('index');
		$this->session->set('userILCADMData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'report_problem_tbl as ftable';
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

		// Fetch paginated data
		$data['ALLDATA'] =$this->common_model->getData('multiple', $tblName, $whereCon, $shortField, $perPage, $offset);
		// echo "<pre>"; print_r($data['ALLDATA']); die();
		$this->layouts->set_title('User Submitted Report Problems');
		$this->layouts->admin_view('user_submitted_report_problems/index',array(),$data);
	}	// END OF FUNCTION
	
    
    


		
	public function deletedata($deleteId = null)
	{		
		$uri = service('uri');
		$deleteId = $uri->getSegment(4); 
		$this->admin_model->authCheck('delete_data');

		$this->common_model->deleteData('report_problem_tbl','id',(int)$deleteId);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.deletesuccess'));
		//echo"<pre>";print_r(getCurrentControllerPath('index'));die;
// 		echo "<pre>"; 
//    print_r(correctLink('userILCADMData', getCurrentControllerPath('index'))); 
//       die();
		return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
	}



}