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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Adminmanagesubsc extends BaseController {
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
		helper(['common','general']);
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
		$data['activeMenu'] 				= 	'adminmanagesubsc';
		$data['activeSubMenu'] 				= 	'adminmanagesubsc';
		
		if($this->request->getGet('searchValue')):
			$sValue							=	$this->request->getGet('searchValue');
			
			$whereCon['like'] = [
			  'ftable.email' => $sValue,
			   'ftable.name'  => $sValue
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
		$tblName 							= 	'subscribe_tbl as ftable';
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
		// echo "<pre>"; print_r($data['ALLDATA']); die();
		$this->layouts->set_title('Manage Subscribe  email');
		$this->layouts->admin_view('subscribe/index',array(),$data);
	}	// END OF FUNCTION

	/***********************************************************************
	** Function name 	: deletedata
	** Developed By 	: Megha Kumari
	** Purpose  		: This function used for delete data
	** Date 			: 28/01/23
	************************************************************************/

	function deletedata($deleteId='')
	{  
		$uri = service('uri');
		$deleteId = $uri->getSegment(4);  
		$uri = service('uri');
		$deleteId = $uri->getSegment(4);  
		$this->admin_model->authCheck('delete_data');

		$this->common_model->deleteData('subscribe_tbl','id',(int)$deleteId);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.deletesuccess'));
		
		return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
	}
	
	// public function export_excel() {
	// 	require_once APPPATH . 'third_party/classes/PHPExcel.php';
  
	// 	  $subdata = $this->admin_model->getsubs();
	// 	//   echo"<pre>";print_r($subdata);die;
	  
	// 	  // Create a new PHPExcel object
	// 	  $objPHPExcel = new PHPExcel();
	  
	// 	  // Set document properties
	// 	  $objPHPExcel->getProperties()->setCreator("Your Name")
	// 									 ->setLastModifiedBy("Your Name")
	// 									 ->setTitle("Exported Data")
	// 									 ->setSubject("Exported Data")
	// 									 ->setDescription("Excel file generated from exported data")
	// 									 ->setKeywords("excel phpexcel codeigniter")
	// 									 ->setCategory("Data Export");
		  
	// 	  // Set column headers
	// 	  $objPHPExcel->setActiveSheetIndex(0)
	// 				  ->setCellValue('A1', 'ID')
	// 				  ->setCellValue('B1', 'Name')
	// 				  ->setCellValue('C1', 'Email')
	// 				  ->setCellValue('D1', 'Creation Date')
	// 				  ->setCellValue('E1', 'Status');
		  
	// 	  $row = 2;
	// 	  foreach ($subdata as $item) {
	// 		  $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $item['id']);
	// 		  $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $item['name']);
	// 		  $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $item['email']);
	// 		  $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $item['creation_date']);
	// 		  $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $item['status']);
	// 		  $row++;
	// 	  }
		  
	// 	  // Set active sheet index to the first sheet
	// 	  $objPHPExcel->setActiveSheetIndex(0);
		  
	// 	  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	// 	  header('Content-Disposition: attachment;filename="exported_data.xlsx"');
	// 	  header('Cache-Control: max-age=0');
		  
	// 	  // Save Excel file to PHP output
	// 	  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	// 	  $objWriter->save('php://output');
		  
	// 	  exit;
	// }


	public function export_excel() {
	
		require_once ROOTPATH . 'vendor/autoload.php';

		// ✅ Fetch data from the model and sort by 'creation_date' in descending order
       
		$subdata = $this->admin_model->getsubs();

        usort($subdata, function($a, $b) {
            return strtotime($b['creation_date']) - strtotime($a['creation_date']);
        });

        // ✅ Create a new Spreadsheet object
        $objPHPExcel = new Spreadsheet();
		
		// ✅ Set document properties
		$objPHPExcel->getProperties()->setCreator("Your Name")
		  ->setLastModifiedBy("Your Name")
		  ->setTitle("Exported Data")
		  ->setSubject("Exported Data")
		  ->setDescription("Excel file generated from exported data")
		  ->setKeywords("excel phpspreadsheet codeigniter")
		  ->setCategory("Data Export");
	
		// ✅ Set column headers
		$objPHPExcel->setActiveSheetIndex(0)
		 ->setCellValue('A1', 'ID')
		 ->setCellValue('B1', 'Name')
		 ->setCellValue('C1', 'Email')
		 ->setCellValue('D1', 'Creation Date')
		 ->setCellValue('E1', 'Status');

		 // ✅ Insert Data into Sheet
		 $row = 2;
		 foreach ($subdata as $item) {
			 $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $item['id']);
			 $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $item['name']);
			 $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $item['email']);
			 $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $item['creation_date']);
			 $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $item['status']);
			 $row++;
		}
	    // ✅ Set active sheet index to the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // ✅ Headers for File Download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="exported_data.xlsx"');
        header('Cache-Control: max-age=0');

        // ✅ Save Excel File and Output to Browser
        $objWriter = new Xlsx($objPHPExcel);
        $objWriter->save('php://output');

        exit;
	}
}