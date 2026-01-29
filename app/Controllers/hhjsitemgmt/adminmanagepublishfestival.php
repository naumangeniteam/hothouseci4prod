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
class Adminmanagepublishfestival extends BaseController {
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
		helper('common');
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
		$data['activeMenu'] 				= 	'adminmanagepublishfestival';
		$data['activeSubMenu'] 				= 	'adminmanagepublishfestival';
		
		if($this->request->getGet('searchValue')):
			$sValue							=	$this->request->getGet('searchValue');
			$whereCon['like']			 	= 	"(ftable.festival_name LIKE '%".$sValue."%'
												)";
			$data['searchField'] 			= 	$sField;
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;
				
		$whereCon['where']		 			= 	"is_active = '1'";	
		//$whereCon['where']		 			= 	"";		
		$shortField 						= 	"ftable.festival_id DESC";
		
		$baseUrl 							= 	base_url().'hhjsitemgmt/adminmanagepublishfestival/index';
		$this->session->set('userILCADMData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'festival_tbl as ftable';
		$con 								= 	'';
		// echo $tblName; die();
		$totalRows 							= 	$this->common_model->getData('count',$tblName,$whereCon,$shortField,'0','0');
		
		if($this->request->getGet('showLength') == 'All'):
			$perPage	 					= 	$totalRows;
			$data['perpage'] 				= 	$this->request->getGet('showLength');  
		elseif($this->request->getGet('showLength')):
			$perPage	 					= 	$this->request->getGet('showLength'); 
			$data['perpage'] 				= 	$this->request->getGet('showLength'); 
		else:
			$perPage			= 	SHOW_NO_OF_DATA;
			$data['perpage'] =20;
		endif;
		$uriSegment 						= 	getUrlSegment();
	    $data['PAGINATION']					=	adminPagination($baseUrl,$suffix,$totalRows,$perPage,$uriSegment);

       if ($this->request->getUri()->getSegment($uriSegment)) :
           $page = $this->request->getUri()->getSegment($uriSegment);
       else:
           $page = 0;
       endif;
		
		$data['forAction'] 					= 	$baseUrl; 
		if($totalRows):
			$first							=	(int)($page)+1;
			$data['first']					=	$first;
			
			if($data['perpage'] == 'All'):
				$pageData 					=	$totalRows;
			else:
				$pageData 					=	$data['perpage'];
			endif;
			
			$last							=	((int)($page)+$pageData)>$totalRows?$totalRows:((int)($page)+$pageData);
			$data['noOfContent']			=	'Showing '.$first.'-'.$last.' of '.$totalRows.' items';
		else:
			$data['first']					=	1;
			$data['noOfContent']			=	'';
		endif;
		
		$data['ALLDATA'] 					= 	$this->common_model->getData('multiple',$tblName,$whereCon,$shortField,$perPage,$page); 
		$data['venues']                     = $this->common_model->getCategory();
		$data['festivals']                  = $this->common_model->totalFestivals();
		$data['trashfestival']              = $this->common_model->totalTrashFestival();
		$data['publishfestival']            = $this->common_model->totalPublisFestival();
		//echo "<pre>"; print_r($data['ALLDATA']); die();
		$this->layouts->set_title('Manage Festival');
		$this->layouts->admin_view('publishfestival/index',array(),$data);
	}	// END OF FUNCTION
	
	/* * *********************************************************************
	 * * Function name 	: addeditdata
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function used for add edit data
	 * * Date 			: 27 JUNE 2022
	 * * **********************************************************************/
	public function addeditdata($editId='')
	{		
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanagepublishfestival';
		$data['activeSubMenu'] 				= 	'adminmanagepublishfestival';
		
		if($editId):
			$this->admin_model->authCheck('edit_data');
			$data['EDITDATA']		=	$this->common_model->getDataByParticularField('festival_tbl','festival_id',(int)$editId);
		else:
			$this->admin_model->authCheck('add_data');
		endif;

		if($this->request->getPost('SaveChanges')):
			$error					=	'NO';
            $this->form_validation->set_rules('festival_name', 'Festival Name', 'trim|required|max_length[256]');
            $this->form_validation->set_rules('save_location_id', 'Location', 'trim|required');
			$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
			$this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
			$this->form_validation->set_rules('no_of_repeat', 'No of Repeats', 'trim|required');
            $this->form_validation->set_rules('location_name', 'Location Name', 'trim|required');
			$this->form_validation->set_rules('location_address', 'Location Address', 'trim|required');
			$this->form_validation->set_rules('latitude', 'Latitude', 'trim|required');
			$this->form_validation->set_rules('longitude', 'Longitude', 'trim|required');
			$this->form_validation->set_rules('website', 'Website', 'trim|required');
			$this->form_validation->set_rules('phone_number', 'Phone number', 'trim|required');
			$this->form_validation->set_rules('venue_id', 'Venue', 'trim|required');
			//$this->form_validation->set_rules('cover_charge', 'Cover Charge', 'trim|required');
			//$this->form_validation->set_rules('set_time', 'Set Time', 'trim|required');
			if($this->form_validation->run() && $error == 'NO'): 
				$param['festival_name']				= 	$this->request->getPost('festival_name');
				/*$hour 								= 	$this->request->getPost('event_start_hour');
				$min			= 	$this->request->getPost('event_start_min');
				$event_start_M			= 	$this->request->getPost('event_start_M');*/
				$param['save_location_id']				= 	$this->request->getPost('save_location_id');
                $param['description']				= 	$this->request->getPost('description');
                $param['start_date']				= 	$this->request->getPost('start_date');
				$param['end_date']				= 	$this->request->getPost('end_date');
				echo $param['event_start_time']				= 	$this->request->getPost('time');exit;
				//$param['event_start_time']				= 	$hour.':'.$min.':'.$event_start_M;
				//$param['event_start_time']				= 	$this->request->getPost('event_start_time');
				//$param['event_end_time']				= 	$this->request->getPost('event_end_time');
				$param['no_of_repeat']				= 	$this->request->getPost('no_of_repeat');
				$param['location_name']				= 	$this->request->getPost('location_name');
				$param['location_address']				= 	$this->request->getPost('location_address');
				$param['latitude']				= 	$this->request->getPost('latitude');
				$param['longitude']				= 	$this->request->getPost('longitude');
				$param['website']				= 	$this->request->getPost('website');
				$param['phone_number']				= 	$this->request->getPost('phone_number');
				$param['venue_id']				= 	$this->request->getPost('venue_id');
				$param['set_time']				= 	$this->request->getPost('set_time');
				$param['lineup']				    = 	$this->request->getPost('lineup');
                $param['summary']				    = 	$this->request->getPost('summary');
                $param['year']				        = 	$this->request->getPost('year');
				
				if($this->request->getPost('CurrentDataID') ==''):
					$param['ip_address']		=	currentIp();
					//$param['creation_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['created_by']		=	(int)$this->session->get('ILCADM_ADMIN_ID');
					$param['creation_date']				= 	date('Y-m-d h:i:s');
               		//$param['modified_date']				= 	date('Y-m-d h:i:s');
					$param['is_active']			=	'1'; 
					$alastInsertId				=	$this->common_model->addData('festival_tbl',$param);
					$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
				else:
					$Id							=	$this->request->getPost('CurrentDataID');
					//$param['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['last_modified_date']				= 	date('Y-m-d h:i:s');
					$this->common_model->editData('festival_tbl',$param,'festival_id',(int)$Id);
					$this->session->setFlashdata('alert_success',lang('statictext_lang.updatesuccess'));
				endif;
				return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
			endif;
		endif;
		$data['location'] = $this->common_model->getLocation();
		$data['venues'] = $this->common_model->getCategory();
	
		$this->layouts->set_title('Manage Festivals');
		$this->layouts->admin_view('festivals/addeditdata', array(), $data);
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
		$this->common_model->editData('festival_tbl',$param,'festival_id',(int)$changeStatusId);
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

		$this->common_model->deleteData('festival_tbl','festival_id',(int)$deleteId);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.deletesuccess'));
		
		return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
	}

	function location(){
		$location_id = $_GET['LocationId'];
		//print_r($location_id);die;
		$dataQuery = $this->db->select('*')->from('event_location_tbl')->where('id', $location_id)->get()->row();
				// $("#name").val(data.name);
		echo json_encode(array('location_name'=>$dataQuery->location_name, 'location_address'=>$dataQuery->location_address, 'latitude'=>$dataQuery->latitude, 'longitude'=>$dataQuery->longitude, 'website'=>$dataQuery->website, 'phone_number'=>$dataQuery->phone_number, 'venue_id'=>$dataQuery->venue_id));
	}

	/***********************************************************************
	** Function name 	: updateStatus
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for delete data
	** Date 			: 27 JUNE 2022
	************************************************************************/
	function updateStatus($id='')
	{  
		
		//$this->common_model->deleteData('festival_tbl','festival_id',(int)$deleteId);
		$param['is_active'] = '2';
		$whereCon = "festival_id = '".$id."' ";
		$this->common_model->editMultipleDataByMultipleCondition('festival_tbl',$param,$whereCon);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.deletesuccess'));
		
		return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
	}
}