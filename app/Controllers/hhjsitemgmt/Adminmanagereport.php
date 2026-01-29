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

class Adminmanagereport extends BaseController
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

	function index()
	{
		$date = date("Y-m-d");
		$date = strtotime(date("Y-m-d", strtotime($date)) . "-2 months");
		$date = date("Y-m-d", $date);
		$this->admin_model->authCheck('view_data');
		$this->admin_model->getPermissionType($data);
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanagereport';
		$data['activeSubMenu'] 				= 	'adminmanagereport';

		if ($this->request->getGet('searchValue')) :
			$sValue							=	$this->request->getGet('searchValue');
			$whereCon['like'] = ['ftable.event_title' => $sValue];
			$data['searchField'] = $sField ?? '';
			$data['searchValue'] 			= 	$sValue;
		else :
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;

		$whereCon['where']		 			= 	"start_date >= '$date' ";
		$shortField 						= 	"ftable.event_id DESC";
		$baseUrl 							= 	base_url() . 'hhjsitemgmt/adminmanagereport/index';
		$this->session->set('userILCADMData', currentFullUrl());
		$qStringdata						=	explode('?', currentFullUrl());
		$suffix								= 	$qStringdata[1] ? '?' . $qStringdata[1] : '';
		$tblName 							= 	'event_tbl as ftable';
		$con 								= 	'';
		$totalRows 							= 	$this->common_model->getData_event('count', $tblName, $whereCon, $shortField, '0', '0');

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

		$sortField = $this->request->getPost('sort_by') ?: 'event_title';
		$sortOrder = $this->request->getPost('order') ?: 'asc';
		$shortField = $sortField . ' ' . $sortOrder;

		$data['ALLDATA'] 					= 	$this->common_model->getData_event('multiple', $tblName, $whereCon, $shortField, $perPage, $offset);
		// echo"<pre>";print_r($data['ALLDATA']);die;
		$data['venues'] = $this->common_model->getCategory();
		$data['cities'] = $this->common_model->getCategoryCity();
		$data['states'] = $this->common_model->getCategoryState();
		$data['jazzTypes'] = $this->common_model->getCategoryJazz();
		$data['artistTypes'] = $this->common_model->getCategoryArtist(false);
		$data['locations'] = $this->common_model->getLocation(false);
		$data['events'] = $this->common_model->totalEventsBySearch('multiple', $tblName, $whereCon, $shortField);
		$data['trashevent'] = $this->common_model->totalTrashevent();
		// echo"<pre>";print_r($data['events']);die;
		$this->layouts->set_title('Event Report');
		$this->layouts->admin_view('report/index', array(), $data);
	}

	public function export_excel()
	{
		require_once ROOTPATH . 'vendor/autoload.php';

		$eventData = $this->admin_model->getFilteredEventsForExport($_GET);

		// echo"<pre>";print_r($eventData);die;

		$objPHPExcel = new Spreadsheet();
		// ✅ Set document properties
		$objPHPExcel->getProperties()->setCreator("Your Name")
		  ->setLastModifiedBy("Your Name")
		  ->setTitle("Exported Events")
		  ->setSubject("Exported Events Data")
		  ->setDescription("Excel file generated from exported events data")
		  ->setKeywords("excel phpexcel codeigniter")
		  ->setCategory("Data Export");

		// ✅ Set column headers
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'Event ID')
		->setCellValue('B1', 'Event Title')
		->setCellValue('C1', 'Description')
		->setCellValue('D1', 'Location')
		->setCellValue('E1', 'Venue')
		->setCellValue('F1', 'Artist')
		->setCellValue('G1', 'State')
		->setCellValue('H1', 'City')
		->setCellValue('I1', 'Start Date')
		->setCellValue('J1', 'End Date');


		 // ✅ Insert Data into Sheet
		 $row = 2;
		 foreach ($eventData as $event) {
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $event['event_id']);
			$objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $event['event_title']);
			$objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $event['description']);
			$objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $event['location_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $event['venue_title']);
			$objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $event['artist_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $event['state']);
			$objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $event['city']);
			$objPHPExcel->getActiveSheet()->setCellValue('I' . $row, $event['start_date']);
			$objPHPExcel->getActiveSheet()->setCellValue('J' . $row, $event['end_date']);

			$row++;
		}

		
		$objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('Events Data');

        // ✅ Headers for File Download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="exported_events.xlsx"');
        header('Cache-Control: max-age=0');

        // ✅ Save Excel File and Output to Browser
        $objWriter = new Xlsx($objPHPExcel);
        $objWriter->save('php://output');

        exit;
	}
}
