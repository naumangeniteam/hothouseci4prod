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
class Adminmanagehomeslider extends BaseController {
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
		$data['activeMenu'] 				= 	'adminmanagehomeslider';
		$data['activeSubMenu'] 				= 	'adminmanagehomeslider';
		
		if($this->request->getGet('searchValue')):
			$sValue							=	$this->request->getGet('searchValue');
			$whereCon['like']			 	= 	"(ftable.venue_title LIKE '%".$sValue."%'
												)";
			$data['searchField'] 			= 	$sField;
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
		$tblName 							= 	'home_slider_image as ftable';
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
		
		if(isset($_POST['activebtn'])){
			if($this->request->getPost('checkbox') == true){
				$update = $this->request->getPost('checkbox');
				for ($i=0; $i < count($update) ; $i++) { 
					$data = array('is_active' => 1);            
					$this->db->where('id', $update[$i]);
					$this->db->update('home_slider_image', $data);               
				}
				$this->session->setFlashdata('alert_success',lang('statictext_lang.statussuccess'));
				return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
			  }
		}
		if(isset($_POST['inactivebtn']))
		{
				if($this->request->getPost('uncheckbox') == true){
					$update = $this->request->getPost('uncheckbox');
					//print_r($update);die;
					for ($i=0; $i < count($update) ; $i++) { 
						$data = array('is_active' =>'0');    
						//print_r($data);die;        
						$this->db->where('id', $update[$i]);
						$this->db->update('home_slider_image', $data);               
					}
					$this->session->setFlashdata('alert_success',lang('statictext_lang.statussuccess'));
					return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
				}	 
		}

		$data['ALLDATA'] 					= 	$this->common_model->getData('multiple',$tblName,$whereCon,$shortField,$perPage,$page); 
		//echo "<pre>"; print_r($data['ALLDATA']); die();
		$this->layouts->set_title('Manage Home Page Slider Image');
		$this->layouts->admin_view('homepageimageslider/index',array(),$data);
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
		$data['activeMenu'] 				= 	'adminmanagehomeslider';
		$data['activeSubMenu'] 				= 	'adminmanagehomeslider';
		
		if($editId):
			$this->admin_model->authCheck('edit_data');
			$data['EDITDATA']		=	$this->common_model->getDataByParticularField('home_slider_image','id',(int)$editId);
		else:
			$this->admin_model->authCheck('add_data');
		endif;

		if($this->request->getPost('SaveChanges')):
			//print_r($this->request->getPost('cropImage')); die();
			if($this->request->getPost('image') == ''){
				$this->session->setFlashdata('alert_error',lang('statictext_lang.uploadimgerror'));
			}
			$error					=	'NO';
            $this->form_validation->set_rules('image','Image', 'required');
            $this->form_validation->set_rules('content','Content', 'required');
              $this->form_validation->set_rules('position','Position', 'required');
               $this->form_validation->set_rules('url','Url', 'required');
			$this->form_validation->set_message('trim|required', 'Enter %s');
			/*if ($this->request->getPost('CurrentDataID') =='' && $this->request->getPost('cropImage') =='') {
				$this->form_validation->set_rules('cropImage', 'Image', 'trim');
			}*/
			if($this->form_validation->run() && $error == 'NO'): 
				if(!empty($_FILES['image']['name'])){
					$config = [
							'upload_path'   => './assets/front/img/homeimage',
							'allowed_types' => 'jpg|png|gif|jpeg|webp',
							//'encrypt_name'  => TRUE
						];
                    $this->load->library('upload', $config); 
                    $image = '';
                    if($this->upload->do_upload('image')){
                        $img_data = $this->upload->data();
                        $param['image'] = $img_data['file_name'];	
                    }
				
                }
              
				$param['content']				= 	$this->request->getPost('content');
					$param['position']				= 	$this->request->getPost('position');
					$param['url']				= 	$this->request->getPost('url');
				if($this->request->getPost('CurrentDataID') ==''):
					$param['ip_address']		=	currentIp();
					//$param['creation_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['created_by']		=	(int)$this->session->get('ILCADM_ADMIN_ID');
					$param['creation_date']				= 	date('Y-m-d h:i:s');
					$param['is_active']			=	'0'; 
					$alastInsertId				=	$this->common_model->addData('home_slider_image',$param);
					$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
				else:
					$Id							=	$this->request->getPost('CurrentDataID');
					//$param['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['modified_date']				= 	date('Y-m-d h:i:s');
					$this->common_model->editData('home_slider_image',$param,'id',(int)$Id);
					$this->session->setFlashdata('alert_success',lang('statictext_lang.updatesuccess'));
				endif;
				return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
			endif;
		endif;
		$this->layouts->set_title('Manage Home Page Slider Image');
		$this->layouts->admin_view('homepageimageslider/addeditdata',array(),$data);
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
		$this->common_model->editData('home_slider_image',$param,'id',(int)$changeStatusId);
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

		$this->common_model->deleteData('home_slider_image','id',(int)$deleteId);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.deletesuccess'));

		return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
	}
	public function activedata()
	{  
		if(isset($_POST['activebtn']))
		{
			print_r($this->request->getPost('checkactive'));die;
			//print_r($this->request->getPost('checkactive'));die;
			if(!empty($this->request->getPost('checkactive'))){
              $checkinput= $this->request->getPost('checkactive');
			//  print_r($this->request->getPost('checkinput'));die;
			}else{

			}
		}
		
	}

}