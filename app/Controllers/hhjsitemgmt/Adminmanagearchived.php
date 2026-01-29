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
class Adminmanagearchived extends BaseController
{
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
		$this->admin_model = new AdminModel();
		$this->emailTemplateModel = new EmailtemplateModel();
        $this->smsModel = new SmsModel();
        $this->notificationModel = new NotificationModel();
		$this->common_model = new CommonModel();
		error_reporting(0);
		// $this->load->model(array('admin_model', 'emailtemplate_model', 'sms_model', 'notification_model'));
	 $this->lang = service('language'); 
$this->lang->setLocale('admin');
		$this->layouts = new Layouts();
		$this->session = session();
		helper(['common','general']);
	}

	/* * *********************************************************************
	 * * Function name 	: index
	 * * Developed By 	: Megha Kumari
	 * * Purpose  		: This function used for IL Talks
	 * * Date 			: 27 JUNE 2022
	 * * **********************************************************************/

	public function index()
	{
		

		$this->admin_model->authCheck('view_data');
		$this->admin_model->getPermissionType($data);
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanagearchived';
		$data['activeSubMenu'] 				= 	'adminmanagearchived';

		if ($this->request->getGet('searchValue')) :
			$sValue							=	$this->request->getGet('searchValue');
			$whereCon['like'] = [
				'ftable.event_title' => $sValue
			];
												  
			$data['searchField'] = $sField ?? '';
			$data['searchValue'] 			= 	$sValue;
		else :
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;

		$whereCon['where']		 			= 	"start_date >= '$date' ";
		$shortField 						= 	"ftable.event_id DESC";
		$baseUrl 							= 	base_url() . 'hhjsitemgmt/adminmanagearchived/index';
		$this->session->set('userILCADMData', currentFullUrl());
		$qStringdata						=	explode('?', currentFullUrl());
		$suffix								= 	$qStringdata[1] ? '?' . $qStringdata[1] : '';
		$tblName 							= 	'archived_tbl as ftable';
		$con 								= 	'';
		$totalRows 							= 	$this->common_model->getData_archive('count', $tblName, $whereCon, $shortField, '0', '0');

		if($this->request->getGet('showLength') == 'All'):
			$perPage	 					= 	$totalRows;
			
			$data['perpage'] 				= 	$this->request->getGet('showLength');  
		elseif($this->request->getGet('showLength')):
			$perPage	 					= 	$this->request->getGet('showLength'); 
			// echo"<pre>";print_r($perPage);die;
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
		//echo"<pre>";print_r($data['PAGINATION']);die;
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
		

		$data['ALLDATA'] 					= 	$this->common_model->getData_archive('multiple', $tblName, $whereCon, $shortField, $perPage, $offset);
		// echo"<pre>";print_r($data['ALLDATA']);die;
		$data['venues'] = $this->common_model->getCategory(false);
		$data['cities'] = $this->common_model->getCategoryCity();
		$data['states'] = $this->common_model->getCategoryState();
		$data['jazzTypes'] = $this->common_model->getCategoryJazz();
		$data['artistTypes'] = $this->common_model->getCategoryArtist();
		$data['events'] = $this->common_model->totalEvents();
		$data['newEvents'] = $this->common_model->newEventsForCurrentMonth();
		// $data['newEventsCount'] = count($data['newEvents']);
		$data['trashevent'] = $this->common_model->totalTrashevent();
		$data['newTrashEvents'] = $this->common_model->trashEventsCurrentMonth();
		$data['publishevent'] = $this->common_model->totalPublishevent();
		$this->layouts->set_title('Manage Archive');
		$this->layouts->admin_view('archived/index', array(), $data);
	}

	// END OF FUNCTION
}
