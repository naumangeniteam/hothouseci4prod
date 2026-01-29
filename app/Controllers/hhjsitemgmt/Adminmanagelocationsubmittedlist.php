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

class Adminmanagelocationsubmittedlist extends BaseController
{
	protected $admin_model;
	protected $emailTemplateModel;
	protected $smsModel;
	protected $notificationModel;
	protected $common_model;
	protected $session;
	protected $layouts;
	protected $db;
	protected $lang;
	protected $validation;
	public function  __construct()
	{
		$this->admin_model = new AdminModel();
		$this->emailTemplateModel = new EmailtemplateModel();
		$this->smsModel = new SmsModel();
		$this->notificationModel = new NotificationModel();
		$this->common_model = new CommonModel();
		$this->layouts = new Layouts();
		$this->session = session();
		$this->db = \Config\Database::connect();
		$this->lang = service('language');
		$this->lang->setLocale('admin');
		$this->validation = service('validation');
		//error_reporting(E_ALL ^ E_NOTICE);  
		error_reporting(0);
		helper(['common', 'general']);
	}

	/* * *********************************************************************
	 * * Function name 	: index
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function used for IL Talks
	 * * Date 			: 27 JUNE 2022
	 * * **********************************************************************/

	public function locationUpdate()
	{
		$this->db->select('event_location_tbl.*');
		$this->db->from('event_location_tbl');
		$query = $this->db->get();
		$results = $query->result_array();
		// echo"<pre>";print_r($results);die;
		$apiKey = getenv('API_KEY');

		foreach ($results as $result) :
			$location_address = $result['location_address'];
			$locationData = $this->getCityStateFromAddress($location_address, $apiKey);

			echo "<pre>";
			echo "Address: " . $location_address . "\n";
			if ($locationData) {
				echo "City: " . $locationData['city'] . "\n";
				echo "State: " . $locationData['state'] . "\n";

				$data = array(
					'city' => $locationData['city'],
					'state' => $locationData['state']
				);
				$this->db->where('location_address', $location_address);
				$this->db->update('event_location_tbl', $data);

				echo "City and State updated in database\n";
			} else {
				echo "City and State not found\n";
			}
		endforeach;
	}

	private function getCityStateFromAddress($address, $apiKey)
	{
		$address = urlencode($address);
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$apiKey}";

		$response = file_get_contents($url);
		$response = json_decode($response, true);

		if ($response['status'] == 'OK') {
			$addressComponents = $response['results'][0]['address_components'];
			$city = '';
			$state = '';

			foreach ($addressComponents as $component) {
				if (in_array('locality', $component['types'])) {
					$city = $component['long_name'];
				}
				if (in_array('administrative_area_level_1', $component['types'])) {
					$state = $component['long_name'];
				}
			}

			return ['city' => $city, 'state' => $state];
		}

		return null;
	}

	public function updateStateAndCounty()
	{
		// Fetch all locations with addresses
		$tblName = 'event_location_tbl as ftable';
		$whereCon['where'] = "";
		$shortField = "ftable.id DESC";

		// Fetch all location data
		$locations = $this->common_model->getData('multiple', $tblName, $whereCon, $shortField, '0', '0');

		foreach ($locations as $location) {
			$address = $location['location_address'];

			// Call Geocoding API to get state and county
			$geocodingResponse = $this->getGeocodingData($address);

			if ($geocodingResponse) {
				// Extract state and county from the response
				$state = $geocodingResponse['state'] ?? '';
				$county = $geocodingResponse['county'] ?? '';

				// Update the database with state and county
				$updateData = [
					'state' => $state,
					'county' => $county
				];
				$this->common_model->updateData('event_location_tbl', $updateData, ['id' => $location['id']]);
			}
		}
	}

	// Function to get state and county using Geocoding API
	private function getGeocodingData($address)
	{
		// Use Google Geocoding API or any other Geocoding service to get state and county from the address
		$apiKey = getenv('API_KEY');
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . $apiKey;

		$response = file_get_contents($url);
		$data = json_decode($response, true);

		if (!empty($data['results'])) {
			// Extract state and county from the API response
			$components = $data['results'][0]['address_components'];
			$state = '';
			$county = '';

			foreach ($components as $component) {
				if (in_array('administrative_area_level_1', $component['types'])) {
					$state = $component['long_name'];
				}

				if (in_array('administrative_area_level_2', $component['types'])) {
					$county = $component['long_name'];
				}
			}

			return ['state' => $state, 'county' => $county];
		}

		return null;
	}


	public function index()
	{

		$this->admin_model->authCheck('view_data');
		$this->admin_model->getPermissionType($data);
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanagelocationsubmittedlist';
		$data['activeSubMenu'] 				= 	'adminmanagelocationsubmittedlist';

		
		if ($this->request->getGet('searchValue')) :
			$sValue							=	$this->request->getGet('searchValue');
			$whereCon['like'] = [
				'ftable.location_name' => $sValue,
				'ftable.state' => $sValue,
				'ftable.county' => $sValue,
				'ftable.location_address' => $sValue
			  ];	
			$data['searchField'] = $sField ?? '';

			$data['searchValue'] 			= 	$sValue;
		else :
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;

		if ($this->request->getGet('county')) {
			$selectedCounty = $this->request->getGet('county');
			// echo"<pre>";print_r($selectedCounty);die;
			if (!empty($selectedCounty)) {
				$whereCon['like'] = [
					'ftable.county' => $selectedCounty
				  ];
			}
			$data['county'] 			= 	 $sField ?? '';
			$data['countyValue'] 			= 	$selectedCounty; // Store selected county for repopulation
		} else {
			$data['county'] = '';  // No county selected
		}

		if ($this->request->getGet('location_name')) {
			$selectedlocation = $this->request->getGet('coulocation_namenty');
			// echo"<pre>";print_r($selectedCounty);die;
			if (!empty($selectedCounty)) {
				$whereCon['like'] = [
					'ftable.location_name' => $selectedlocation
				  ];
			}
			$data['location'] 			= 	 $sField ?? '';
			$data['locationValue'] 			= 	$selectedlocation; // Store selected county for repopulation
		} else {
			$data['location'] = '';  // No county selected
		}

		// Additional condition
		$whereCon['where'] = "location_source = 'front_user' ";

		// $whereConditions['where'] = ['location_source' => 'front_user'];

		// Query to get venue positions
		$venueBuilder = $this->db->table('venue_tbl');
		$venueBuilder->select('venue_tbl.position AS venue_position')
			->join('event_location_tbl as ftable', 'venue_tbl.id = ftable.venue_id', 'inner');

		$get_venues = $venueBuilder->get()->getResultArray();

		// Sorting order
		$shortField = "ftable.id DESC";
		$baseUrl = getCurrentControllerPath('index');
		$this->session->set('userILCADMData', currentFullUrl());

		$qStringdata = explode('?', currentFullUrl());
		$suffix = isset($qStringdata[1]) ? '?' . $qStringdata[1] : '';

		$tblName = 'event_location_tbl as ftable';

		// Get total rows (assuming common_model is adjusted for CI4)
		$totalRows = $this->common_model->getData('count', $tblName, $whereCon, $shortField, '0', '0');
      
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

		$sortField = $this->request->getPost('sort_by') ?: 'venue_id';
		$sortOrder = $this->request->getPost('order') ?: 'desc';
		$shortField = $sortField . ' ' . $sortOrder;

		$data['locations'] = $this->common_model->getLocation(); // added by PP in ci4
		$data['ALLDATA'] 					= 	$this->common_model->getData('multiple', $tblName, $whereCon, $shortField, $perPage, $offset);
		$data['venues'] = $this->common_model->getCategory();
		$data['counties'] = $this->common_model->getCounty();
		$data['event_location_types'] = $this->common_model->getEventlocationtype();
		//echo "<pre>"; print_r($data['venues']); die();
		$this->layouts->set_title('Manage Location Submitted List');
		$this->layouts->admin_view('location_submitted_list/index', array(), $data);
	}	// END OF FUNCTION

	/* * *********************************************************************
	 * * Function name 	: addeditdata
	 * * Developed By 	: Afsar Ali
	 * * Purpose  		: This function used for add edit data
	 * * Date 			: 27 JUNE 2022
	 * * **********************************************************************/
	public function addeditdata($editId = '')
	{
		$uri = service('uri');
		$editId = $uri->getSegment(4);
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanageeventlocation';
		$data['activeSubMenu'] 				= 	'adminmanageeventlocation';

		if ($editId) :
			$this->admin_model->authCheck('edit_data');
			$data['EDITDATA']		=	$this->common_model->getDataByParticularField('event_location_tbl', 'id', (int)$editId);
		else :
			$this->admin_model->authCheck('add_data');
		endif;
		if ($this->request->getPost('SaveChanges')) :
			$error					=	'NO';
			$validation = \Config\Services::validation();
			$rules = [
				'location_name'         => 'trim|required',
				'location_address'      => 'trim|required|max_length[256]',
				'county'                => 'trim|required',
				'state'                 => 'trim|required',
				'longitude'             => 'trim|required',
				'latitude'              => 'trim|required|max_length[256]',
				'venue_id'              => 'trim|required',
				'event_location_type_id' => 'trim|required',
			];
		
			// Custom error messages
			$messages = [
				'location_name' => ['required' => 'Enter Name'],
				'location_address' => [
					'required' => 'Enter Location Address',
					'max_length' => 'Location Address must not exceed 256 characters'
				],
				'county' => ['required' => 'Enter County'],
				'state' => ['required' => 'Enter State'],
				'longitude' => ['required' => 'Enter Longitude'],
				'latitude' => [
					'required' => 'Enter Latitude',
					'max_length' => 'Latitude must not exceed 256 characters'
				],
				'venue_id' => ['required' => 'Select Venue Category'],
				'event_location_type_id' => ['required' => 'Select Event Location Type'],
			];
		
			// Set validation rules
			$validation->setRules($rules, $messages);
		
			if (!$validation->withRequest($this->request)->run()) {
				
				return redirect()->back()->withInput()->with('validation', $this->validation);
			}
			
				$param['location_name']				= 	$this->request->getPost('location_name');
				$param['location_address']				= 	$this->request->getPost('location_address');
				$param['county']				= 	$this->request->getPost('county');
				$param['state']				= 	$this->request->getPost('state');
				$param['longitude']				= 	$this->request->getPost('longitude');
				$param['latitude']				= 	$this->request->getPost('latitude');
				$param['website']				= 	$this->request->getPost('website');
				$param['phone_number']				= 	$this->request->getPost('phone_number');
				$param['venue_id']				= 	$this->request->getPost('venue_id');
				$param['event_location_type_id']				= 	$this->request->getPost('event_location_type_id');
			
				if (trim($this->request->getPost('CurrentDataID')) == '') :
					$param['ip_address']		=	currentIp();
					//$param['creation_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['created_by']		=	(int)$this->session->get('ILCADM_ADMIN_ID');
					$param['creation_date']				= 	date('Y-m-d h:i:s');
					$param['is_active']			=	'1';
					$alastInsertId				=	$this->common_model->addData('event_location_tbl', $param);
				
					$this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));
				else :
					$Id							=	$this->request->getPost('CurrentDataID');
					//$param['update_date']		=	(int)$this->timezone->utc_time();//currentDateTime();
					$param['modified_date']				= 	date('Y-m-d h:i:s');
					$this->common_model->editData('event_location_tbl', $param, 'id', (int)$Id);
					//also update related locations
					$this->common_model->editData('event_tbl', array('venue_id' => $this->request->getPost('venue_id')), 'save_location_id', (int)$Id);
					$this->session->setFlashdata('alert_success', lang('statictext_lang.updatesuccess'));
				endif;
				return redirect()->to(getCurrentControllerPath('index'));
			
		endif;
		$data['venues'] = $this->common_model->getCategory(false);
		$data['states'] = $this->common_model->getState(false);
		
		$data['event_location_types'] = $this->common_model->getEventlocationtype();
		$this->layouts->set_title('Manage Event Location');
		$this->layouts->admin_view('location_submitted_list/addeditdata', array(), $data);
	}	// END OF FUNCTION	


	/***********************************************************************
	 ** Function name : changestatus
	 ** Developed By : Manoj Kumar
	 ** Purpose  : This function used for change status
	 ** Date : 25 JUNE 2022
	//  ************************************************************************/
	// function changestatus($changeStatusId = '', $statusType = '')
	// {
	// 	$this->admin_model->authCheck('edit_data');
	// 	$param['is_active']		=	$statusType;
	// 	if ($param['is_active'] == 1) { // Check if the status is active
	// 		$this->emailTemplateModel->sendVenueactiveEmail($param); // Send the email
	// 	}

	// 	$this->common_model->editData('event_location_tbl', $param, 'id', (int)$changeStatusId);
	// 	$this->session->setFlashdata('alert_success',lang('statictext_lang.statussuccess'));

	// 	return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	// }

	public function changestatus($changeStatusId = '', $statusType = '')
	{
		$uri = service('uri');
		$changeStatusId = $uri->getSegment(4);
		$statusType = $uri->getSegment(5);
		$this->admin_model->authCheck('edit_data');
		$param['is_active'] = $statusType;
		$venueDetails = $this->common_model->getDataByParticularField('event_location_tbl', 'id', $changeStatusId);

		if (!empty($venueDetails)) {
			$emailParam = [
				'contact_person_name' => $venueDetails['contact_person_name'],
				'contact_person_email' => $venueDetails['contact_person_email'],
				'location_name' => $venueDetails['location_name'],
			];

			if ($param['is_active'] == 1) {
				$this->emailTemplateModel->sendVenueactiveEmail($emailParam);
			}
		}

		$this->common_model->editData('event_location_tbl', $param, 'id', (int)$changeStatusId);
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

		$this->common_model->deleteData('event_location_tbl', 'id', (int)$deleteId);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.deletesuccess'));
		return redirect()->to(getCurrentControllerPath('index'));
	}

	function moveToVenue()
	{
		//echo"herere1";die;
		$changeStatusIds = json_decode($_POST['changeStatusIds']);
		//echo "<pre>"; print_r($_POST['venueId']); die();
		$param['venue_id'] = $_POST['venueId'];
		// echo $statusType;
		// print_r($changeStatusIds); die;
		foreach ($changeStatusIds as $changeStatusId) {

			//change venue of this location
			$this->common_model->editData('event_location_tbl', $param, 'id', (int)$changeStatusId);
			//change venue of all events on this location
			$this->common_model->editData('event_tbl', $param, 'save_location_id', (int)$changeStatusId);
			$this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));
		}
		return redirect()->to(getCurrentControllerPath('index'));
	}
	// public function export_excel() {
	// 	require_once APPPATH . 'third_party/classes/PHPExcel.php';
	// 	$eventData = $this->admin_model->getEventLocations();
	// 	usort($eventData, function($a, $b) {
	// 		return strtotime($b['creation_date']) - strtotime($a['creation_date']);
	// 	});

	// 	$objPHPExcel = new PHPExcel();

	// 	$objPHPExcel->getProperties()->setCreator("Your Name")
	// 		->setLastModifiedBy("Your Name")
	// 		->setTitle("Exported Data")
	// 		->setSubject("Exported Data")
	// 		->setDescription("Excel file generated from exported data")
	// 		->setKeywords("excel phpexcel codeigniter")
	// 		->setCategory("Data Export");

	// 	$objPHPExcel->setActiveSheetIndex(0)
	// 		 ->setCellValue('A1', 'S.No')
	// 		->setCellValue('B1', 'Location Name')
	// 		->setCellValue('C1', 'Location Address')
	// 		->setCellValue('D1', 'Contact Person Name')
	// 		->setCellValue('E1', 'Contact Person Email')
	// 		->setCellValue('F1', 'Contact Person Phone Number')
	// 		->setCellValue('G1', 'Contact Person Title');

	// 	$row = 2;
	// 	$serialNumber = 1; // Initialize serial number
	// 	echo"<pre>";print_r($eventData);die;
	// 	foreach ($eventData as $item) {
	// 	    $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $serialNumber);
	// 		$objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $item['location_name']);
	// 		$objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $item['location_address']);
	// 		$objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $item['contact_person_name']);
	// 		$objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $item['contact_person_email']);
	// 		$objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $row, $item['contact_person_phone_number'], PHPExcel_Cell_DataType::TYPE_STRING); // Format as text
	// 		$objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $item['contact_person_title']);
	// 		$row++;
	// 		$serialNumber++; // Increment serial number
	// 	}

	// 	$objPHPExcel->setActiveSheetIndex(0);

	// 	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	// 	header('Content-Disposition: attachment;filename="exported_data.xlsx"');
	// 	header('Cache-Control: max-age=0');

	// 	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	// 	$objWriter->save('php://output');
	// 	exit;
	// }
	public function export_excel()
	{
		require_once APPPATH . 'third_party/classes/PHPExcel.php';
		$eventData = $this->admin_model->getEventLocations();
		usort($eventData, function ($a, $b) {
			return strtotime($b['creation_date']) - strtotime($a['creation_date']);
		});

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()->setCreator("Your Name")
			->setLastModifiedBy("Your Name")
			->setTitle("Exported Data")
			->setSubject("Exported Data")
			->setDescription("Excel file generated from exported data")
			->setKeywords("excel phpexcel codeigniter")
			->setCategory("Data Export");

		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'S.No')
			->setCellValue('B1', 'Location Name')
			->setCellValue('C1', 'Location Address')
			->setCellValue('D1', 'Contact Person Name')
			->setCellValue('E1', 'Contact Person Email')
			->setCellValueExplicit('F1', 'Contact Person Phone Number')
			->setCellValue('G1', 'Contact Person Title');

		$row = 2;
		$serialNumber = 1;
		foreach ($eventData as $item) {
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $serialNumber);
			$objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $item['location_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $item['location_address']);
			$objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $item['contact_person_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $item['contact_person_email']);
			$objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $row, $item['contact_person_phone_number'], PHPExcel_Cell_DataType::TYPE_STRING); // Format as text
			$objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $item['contact_person_title']);
			$row++;
			$serialNumber++;
		}

		// Auto-size columns to fit content
		foreach (range('A', 'G') as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}

		$objPHPExcel->setActiveSheetIndex(0);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="exported_data.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
}
