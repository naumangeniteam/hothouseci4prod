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
class Adminmanagetrashfestival extends BaseController {
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
		$data['activeMenu'] 				= 	'adminmanagetrashfestival';
		$data['activeSubMenu'] 				= 	'adminmanagetrashfestival';
		
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
				
		$whereCon['where']		 			= 	"is_active = '2'";	
		//$whereCon['where']		 			= 	"";		
		$shortField 						= 	"ftable.festival_id DESC";
		
		$baseUrl 							= 	base_url().'hhjsitemgmt/adminmanagetrashfestival/index';
		$this->session->set('userILCADMData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'festival_tbl as ftable';
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
		$data['venues'] = $this->common_model->getCategory();
		$data['festivals']                  = $this->common_model->totalFestivals();
		$data['trashfestival']              = $this->common_model->totalTrashFestival();
		$data['publishfestival']            = $this->common_model->totalPublisFestival();
		//echo "<pre>"; print_r($data['ALLDATA']); die();
		$this->layouts->set_title('Manage Festival');
		$this->layouts->admin_view('trashfestival/index',array(),$data);
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
		$this->common_model->editData('event_tbl',$param,'event_id',(int)$changeStatusId);
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
	** Purpose  		: This function used for trash data
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
	/***********************************************************************
	** Function name 	: restore
	** Developed By 	: Afsar Ali
	** Purpose  		: This function used for restore data
	** Date 			: 27 JUNE 2022
	************************************************************************/
	function restore($id='')
	{  
		
		//$this->common_model->deleteData('festival_tbl','festival_id',(int)$deleteId);
		$param['is_active'] = '1';
		$whereCon = "festival_id = '".$id."' ";
		$this->common_model->editMultipleDataByMultipleCondition('festival_tbl',$param,$whereCon);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.restoresuccess'));
		
		return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
	}
}