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

class Adminmanageadvertisements extends BaseController {

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
		$data['activeMenu'] 				= 	'adminmanageadvertisements';
		$data['activeSubMenu'] 				= 	'adminmanageadvertisements';
		
	 	$sValue = $this->request->getGet('searchValue') ?? '';
		if (!empty($sValue)) {
			$whereCon['like'] = ["ftable.name" => $sValue];
		} else {
			$whereCon['like'] = [];
		}
		$data['searchValue'] = $sValue;
				
		$whereCon['where']		 			= 	"";		
		$shortField 						= 	"ftable.advertise_id DESC";
		
		$baseUrl 							= 	getCurrentControllerPath('index');
		$this->session->set('userILCADMData',currentFullUrl());
		$qStringdata						=	explode('?',currentFullUrl());
		$suffix								= 	$qStringdata[1]?'?'.$qStringdata[1]:'';
		$tblName 							= 	'advertise_tbl as ftable';
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
		// Fetch paginated data
		$data['ALLDATA'] = $this->common_model->getData('multiple', $tblName, $whereCon, $shortField, $perPage, $offset);

		//echo "<pre>"; print_r($data['ALLDATA']); die();
		$this->layouts->set_title('User Submitted Advertisements');
		$this->layouts->admin_view('user_submitted_advertisements/index',array(),$data);
	}	// END OF FUNCTION
	
	

	function deletedata($deleteId='')
	{  
		$uri = service('uri');
		$deleteId = $uri->getSegment(4);
		$this->admin_model->authCheck('delete_data');
		$this->common_model->deleteData('advertise_tbl','advertise_id',(int)$deleteId);
		$this->session->setFlashdata('alert_success',lang('statictext_lang.deletesuccess'));
		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
		
	}

	// public function export_excel()
	// {

	// 	require_once APPPATH . 'third_party/classes/PHPExcel.php';

	// 	$AdvertisementData = $this->admin_model->getManageAdvertisementsForExport($_GET);

	// 	// echo"<pre>";print_r($AdvertisementData);die;

	// 	$objPHPExcel = new PHPExcel();
	// 	$objPHPExcel->getProperties()->setCreator("Your Name")
	// 		->setLastModifiedBy("Your Name")
	// 		->setTitle("Exported Events")
	// 		->setSubject("Exported Events Data")
	// 		->setDescription("Excel file generated from exported events data")
	// 		->setKeywords("excel phpexcel codeigniter")
	// 		->setCategory("Data Export");

	// 	$objPHPExcel->setActiveSheetIndex(0)
	// 		->setCellValue('A1', 'Advertise ID')
	// 		->setCellValue('B1', 'Name')
	// 		->setCellValue('C1', 'Venue/Location')
	// 		->setCellValue('D1', 'Phone Number/Email')
	// 		->setCellValue('E1', 'Advertising Interest')
	// 		->setCellValue('F1', 'Details')
	// 		->setCellValue('G1', 'Submitted on');

	// 	$row = 2;

	// 	foreach ($AdvertisementData as $Advertisement) {
	// 		$objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $Advertisement['advertise_id']);
	// 		$objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $Advertisement['name']);
	// 		$objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $Advertisement['venue_name'] .  ' - ' . $Advertisement['location_name']);
	// 		$objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $Advertisement['phone_number'].  ' - ' . $Advertisement['email']);
	// 		$objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $Advertisement['advertising_interest']);
	// 		$objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $Advertisement['inquiry']);
	// 		$objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $Advertisement['creation_date']);
	// 		$row++;
	// 	}

	// 	$objPHPExcel->setActiveSheetIndex(0);
	// 	$objPHPExcel->getActiveSheet()->setTitle('Advertisement Data');

	// 	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	// 	header('Content-Disposition: attachment;filename="exported_advertise.xlsx"');
	// 	header('Cache-Control: max-age=0');

	// 	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	// 	$objWriter->save('php://output');

	// 	exit;
	// }

	public function export_excel()
	{
		// ✅ Load PhpSpreadsheet classes
		require_once ROOTPATH . 'vendor/autoload.php'; // Ensure autoload is included

		

		// ✅ Fetch Data
		$AdvertisementData = $this->admin_model->getManageAdvertisementsForExport($this->request->getGet());

		// ✅ Create Spreadsheet
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// ✅ Set Properties
		$spreadsheet->getProperties()->setCreator("Your Name")
			->setLastModifiedBy("Your Name")
			->setTitle("Exported Events")
			->setSubject("Exported Events Data")
			->setDescription("Excel file generated from exported events data")
			->setKeywords("excel phpspreadsheet codeigniter")
			->setCategory("Data Export");

		// ✅ Set Column Headers
		$sheet->setCellValue('A1', 'Advertise ID')
			->setCellValue('B1', 'Name')
			->setCellValue('C1', 'Venue/Location')
			->setCellValue('D1', 'Phone Number/Email')
			->setCellValue('E1', 'Advertising Interest')
			->setCellValue('F1', 'Details')
			->setCellValue('G1', 'Submitted on');

		// ✅ Insert Data
		$row = 2;
		foreach ($AdvertisementData as $Advertisement) {
			$sheet->setCellValue('A' . $row, $Advertisement['advertise_id']);
			$sheet->setCellValue('B' . $row, $Advertisement['name']);
			$sheet->setCellValue('C' . $row, $Advertisement['venue_name'] . ' - ' . $Advertisement['location_name']);
			$sheet->setCellValue('D' . $row, $Advertisement['phone_number'] . ' - ' . $Advertisement['email']);
			$sheet->setCellValue('E' . $row, $Advertisement['advertising_interest']);
			$sheet->setCellValue('F' . $row, $Advertisement['inquiry']);
			$sheet->setCellValue('G' . $row, $Advertisement['creation_date']);
			$row++;
		}

		// ✅ Set Sheet Title
		$spreadsheet->getActiveSheet()->setTitle('Advertisement Data');

		// ✅ Prepare for Download
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="exported_advertise.xlsx"');
		header('Cache-Control: max-age=0');

		// ✅ Save and Output
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');

		exit;
	}


}