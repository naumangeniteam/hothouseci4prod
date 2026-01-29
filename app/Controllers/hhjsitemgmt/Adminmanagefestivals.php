<?php

namespace App\Controllers\hhjsitemgmt;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\EmailtemplateModel;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\CommonModel;
use App\Libraries\Layouts;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use CodeIgniter\Files\File;

class Adminmanagefestivals extends BaseController
{
	protected $admin_model;
	// protected $emailTemplateModel;
	//protected $smsModel;
	//protected $notificationModel;
	protected $common_model;
	protected $layouts;
	protected $session;
	protected $uri;
	protected $lang;
	public function __construct()
	{
		$this->admin_model = new AdminModel();
		// $this->emailTemplateModel = new EmailtemplateModel();
		// $this->smsModel = new SmsModel();
		// $this->notificationModel = new NotificationModel();
		$this->common_model = new CommonModel();
		$this->session = session();
		helper(['common', 'url', 'form' , 'general']); // Load helpers
		$this->layouts = new Layouts();
		$this->uri = service('uri');
		$this->lang = service('language');
		$this->lang->setLocale('admin');
	}

	public function index()
	{

		$date = date("Y-m-d");
		$date = strtotime(date("Y-m-d", strtotime($date)) . "-2 months");
		$date = date("Y-m-d", $date);
		$this->admin_model->authCheck('view_data');
		$this->admin_model->getPermissionType($data);
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanagefestivals';
		$data['activeSubMenu'] 				= 	'adminmanagefestivals';

		if($this->request->getGet('name')):
			$sValue							=	$this->request->getGet('name');
			
			$whereCon['like'] = [
			  'ftable.festival_name' => $sValue
			];
												
			$data['searchField'] = $sField ?? '';
			$data['searchValue'] 			= 	$sValue;
		else:
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;
		// $whereCon['where']		 			= 	"start_date >= '$date' ";
		$shortField 						= 	"ftable.festival_id DESC";
		$baseUrl 							= 	base_url() . 'hhjsitemgmt/adminmanagefestivals/index';
		$this->session->set('userILCADMData', currentFullUrl());
		$qStringdata						=	explode('?', currentFullUrl());
		// $suffix								= 	$qStringdata[1] ? '?' . $qStringdata[1] : '';
		$suffix = isset($qStringdata[1]) && !empty($qStringdata[1]) ? '?' . $qStringdata[1] : '';
		
		$tblName 							= 	'festival_tbl as ftable';
		$con 								= 	'';
		//echo"<pre>";print_r($tblName);die;
		$totalRows 							= 	$this->common_model->getData_festival('count', $tblName, $whereCon, $shortField, '0', '0');
		
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
		
		$data['ALLDATA'] 					= 	$this->common_model->getData_festival('multiple', $tblName, $whereCon, $shortField, $perPage, $offset);
		// echo"<pre>";print_r($data['ALLDATA']['$perPage']);die;
		$data['venues']            = $this->common_model->getCategory();
		$data['jazzTypes']         = $this->common_model->getCategoryJazz();
		// echo"<pre>";print_r($data['jazzTypes']);die;
		$data['artistTypes']       = $this->common_model->getCategoryArtist();
		$data['festivals']         = $this->common_model->totalFestivals();
		$data['trashfestival']     = $this->common_model->totalTrashFestival();
		$data['publishfestival']   = $this->common_model->totalPublisFestival();
		$this->layouts->set_title('Manage Festival');
		$this->layouts->admin_view('festivals/index', array(), $data);
	}
	// public function index()
	// {
	// 	$data = [];
	// 	$date = date("Y-m-d", strtotime("-2 months"));

	// 	// ✅ Authentication Check
	// 	if (!is_cli()) { // Skip authentication if running in CLI mode
	// 		$this->adminModel->authCheck('view_data');
	// 		$this->adminModel->getPermissionType($data);
	// 	}

	// 	$data['error'] = '';
	// 	$data['activeMenu'] = 'adminmanagefestivals';
	// 	$data['activeSubMenu'] = 'adminmanagefestivals';

	// 	// ✅ Handle Search (Only if not CLI)
	// 	$whereCon = [];
	// 	if (!is_cli() && $this->request->getGet('searchValue')) {
	// 		$searchValue = $this->request->getGet('searchValue');
	// 		$whereCon['like'] = "(ftable.festival_name LIKE '%" . esc($searchValue) . "%')";
	// 		$data['searchValue'] = $searchValue;
	// 	} else {
	// 		$whereCon['like'] = "";
	// 		$data['searchValue'] = '';
	// 	}

	// 	$shortField = "ftable.festival_id DESC";
	// 	$baseUrl = is_cli() ? '' : base_url('hhjsitemgmt/adminmanagefestivals/index');

	// 	if (!is_cli()) {
	// 		$this->session->set('userILCADMData', current_url());
	// 	}

	// 	$tblName = 'festival_tbl as ftable';
	// 	$totalRows = $this->commonModel->getData_festival('count', $tblName, $whereCon, $shortField, 0, 0);

	// 	// ✅ Pagination Handling (CLI-friendly)
	// 	$perPage = is_cli() ? 100 : ($this->request->getGet('showLength') ?? 10);
	// 	if ($perPage === 'All') {
	// 		$perPage = $totalRows;
	// 	}
	// 	$data['perpage'] = $perPage;
	// 	//echo"<pre>";print_r($data['perpage']);die;
	// 	// ✅ Get Current Page (Handle CLI Mode)
	// 	$page = 0;
	// 	if (!is_cli()) {
	// 		$uri = service('uri');
	// 		$page = (int) ($uri->getSegment(4) ?? 0);
	// 	}

	// 	$data['PAGINATION'] = adminPagination($baseUrl, '', $totalRows, $perPage, $page);

	// 	// ✅ Data Fetching
	// 	if ($totalRows) {
	// 		$first = (int)($page) + 1;
	// 		$data['first'] = $first;
	// 		$last = min($first + $perPage - 1, $totalRows);
	// 		$data['noOfContent'] = "Showing {$first}-{$last} of {$totalRows} items";
	// 	} else {
	// 		$data['first'] = 1;
	// 		$data['noOfContent'] = '';
	// 	}

	// 	// ✅ Fetch Data
	// 	$data['ALLDATA'] = $this->commonModel->getData_festival('multiple', $tblName, $whereCon, $shortField, $perPage, $page);

	// 	if (!is_cli()) { // Skip unnecessary DB calls in CLI mode
	// 		//echo"here1";die;
	// 		$data['venues'] = $this->commonModel->getCategory();
	// 		//echo"here2";die;

	// 		$data['jazzTypes'] = $this->commonModel->getCategoryJazz();
	// 		//echo"here3";die;
	// 		$data['artistTypes'] = $this->commonModel->getCategoryArtist();
	// 		//echo"here4";die;
	// 		$data['festivals'] = $this->commonModel->totalFestivals();
	// 		//echo"here5";die;
	// 		$data['trashfestival'] = $this->commonModel->totalTrashFestival();
	// 		//echo"here6";die;
	// 		$data['publishfestival'] = $this->commonModel->totalPublisFestival();
	// 		// echo"here7";die;
	// 	}
	//    // ✅ Load View (Only in Web Mode)
	// 	if (!is_cli()) {

	// 		$this->layouts->set_title('Manage Festival');

	// 		return $this->layouts->admin_view('festivals/index', [], $data);
	// 		//echo"here6345sdf";die;
	// 	}

	// 	// // ✅ CLI Output
	// 	// if (is_cli()) {
	// 	// 	echo "Festival Management Data:\n";
	// 	// 	print_r($data['ALLDATA']); // Output data in CLI
	// 	// }
	// }

	// END OF FUNCTION

	/* * *********************************************************************
	 * * Function name 	: addeditdata
	 * * Developed By 	: Megha Kumari
	 * * Purpose  		: This function used for add edit data
	 * * Date 			: 27 JUNE 2022
	 * * **********************************************************************/
	public function addeditdata($editId = '')
	{
		$uri = service('uri');
		$editId = $uri->getSegment(4);

		$isEdit = !empty($editId) && $editId > 0;

		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanagefestivals';
		$data['activeSubMenu'] 				= 	'adminmanagefestivals';
		$data['EDITDATA']		=	$this->common_model->getDataByParticularField('festival_tbl', 'festival_id', (int)$editId);
		$data['EDITDATALINEUP']		=	$this->common_model->getDataByMultipleParticularField('lineup_tbl', 'festival_id', (int)$editId);

		if ($editId) :
			$this->admin_model->authCheck('edit_data');
		// echo"<pre>";print_r($data['EDITDATALINEUP']);die;
		else :
			$this->admin_model->authCheck('add_data');
		endif;

		if ($this->request->getPost('SaveChanges')) :

			$validation = \Config\Services::validation();
			$postData = $this->request->getPost();
			$error = 'NO';

			// Define validation rules
			$rules = [
				'festival_name' => 'trim|required',
				'start_date'    => 'trim|required',
				'end_date'      => 'trim|required',
				'venue_id'      => 'trim|required'
			];

			$messages = [
				'festival_name' => ['required' => 'Festival Name is required.'],
				'start_date'    => ['required' => 'Start Date is required.'],
				'end_date'      => ['required' => 'End Date is required.'],
				'venue_id'      => ['required' => 'Venue is required.']
			];

			// Image validation (only required for new entries)
			if (!$isEdit && empty($_FILES['image']['name'])) {
				$rules['image'] = 'uploaded[image]|is_image[image]|max_size[image,2048]';
				$messages['image'] = [
					'uploaded' => 'Festival Image is required.',
					'is_image' => 'Please upload a valid image file.',
					'max_size' => 'Image size must be under 2MB.'
				];
			}

			// Set validation rules and messages
			$validation->setRules($rules, $messages);

			if (!$validation->withRequest($this->request)->run()) {
				return redirect()->back()->withInput()->with('validation', $validation);
			}

			// File Upload Handling
			$param = [];

			// Upload Image
			$file = $this->request->getFile('image');
			if ($file && $file->isValid() && !$file->hasMoved()) {
				$newName = $file->getRandomName();
				$file->move('assets/front/img/festivalimage', $newName);
				$param['image'] = $newName;
			}

			$num_weeks = $this->request->getPost('no_of_repeat');
			$start_date = $this->request->getPost('start_date');

			$endd_date = $this->request->getPost('end_date');
			$week_dates = array();
			if ($this->request->getPost('CurrentDataID') == '') :
				if ($this->request->getPost('repeating_event') == 'Yes') {
					for ($i = 0; $i < $num_weeks; $i++) {
						if ($this->request->getPost('frequecy') == 'weekly') {
							$week_start = date("Y-m-d", strtotime("+" . $i . " week", strtotime($start_date)));
							$week_end = date("Y-m-d", strtotime("+" . ($i + 1) . " week - 1 day", strtotime($start_date)));
							$week_dates[$i] =  $week_start;
						} else {
							$week_start = date("Y-m-d", strtotime("+" . $i . " day", strtotime($start_date)));
							$week_dates[$i] = $week_start;
						}


						$param['festival_name']				= 	$this->request->getPost('festival_name');
						$hour 								= 	$this->request->getPost('event_start_hour');
						$min								= 	$this->request->getPost('event_start_min');
						$event_start_M						= 	$this->request->getPost('event_start_M');
						$hour_end 							= 	$this->request->getPost('event_end_hour');
						$min_end							= 	$this->request->getPost('event_end_min');
						$event_end_M						= 	$this->request->getPost('event_end_M');
						$param['save_location_id']			= 	$this->request->getPost('save_location_id');
						$param['start_date']				= 	$week_start;
						$param['end_date']					= 	$week_start;
						
						$hour = is_array($hour) ? $hour[0] : $hour;
						$min = is_array($min) ? $min[0] : $min;
						$event_start_M = is_array($event_start_M) ? $event_start_M[0] : $event_start_M;
						$hour_end = is_array($hour_end) ? $hour_end[0] : $hour_end;
						$min_end = is_array($min_end) ? $min_end[0] : $min_end;
						$event_end_M = is_array($event_end_M) ? $event_end_M[0] : $event_end_M;
						$start_time = $hour && $min && $event_start_M ? "$hour:$min $event_start_M" : null;
						$end_time   = $hour_end && $min_end && $event_end_M ? "$hour_end:$min_end $event_end_M" : null;

						$param['start_time'] = $start_time;
						$param['end_time']   = $end_time;

						if($start_time && $param['start_date']) {
							$combined_date_and_time = $param['start_date'] . ' ' . $start_time;
							$param['date'] = strtotime($combined_date_and_time);
						}

						// $param['image'] = str_replace(' ','_',$this->request->getPost('image'));
						$param['city_state_name']		    = 	$this->request->getPost('city_state_name');
						$param['media_video_link']		    = 	$this->request->getPost('media_video_link');
						$param['lineup']				    = 	$this->request->getPost('lineup');
						$param['summary']				    = 	$this->request->getPost('summary');
						$param['year']				        = 	$this->request->getPost('year');
						$param['location_name']				= 	$this->request->getPost('location_name');
						$param['location_address']			= 	$this->request->getPost('location_address');
						$param['latitude']					= 	$this->request->getPost('latitude');
						$param['longitude']					= 	$this->request->getPost('longitude');
						$param['website']					= 	$this->request->getPost('website');
						$param['phone_number']				= 	$this->request->getPost('phone_number');
						$param['venue_id']					= 	$this->request->getPost('venue_id');
						if ($this->request->getPost('artist_id') && is_array($this->request->getPost('artist_id'))) {
							$param['artist_id'] = json_encode($this->request->getPost('artist_id'));
							// echo"<pre>";print_r($param['artist_id']);die;
						}

						if ($this->request->getPost('jazz_types_id') && is_array($this->request->getPost('jazz_types_id'))) {
							$param['jazz_types_id'] = json_encode($this->request->getPost('jazz_types_id'));
						}

						$param['set_time']					= 	$this->request->getPost('set_time');
						$param['time_permission']			= 	$this->request->getPost('time_permission');
						$param['repeating_event']			= 	$this->request->getPost('repeating_event');

						$param['ip_address']				=	currentIp();
						$param['created_by']				=	(int)$this->session->get('ILCADM_ADMIN_ID');
						$param['creation_date']				= 	date('Y-m-d h:i:s');
						$param['is_active']					=	'1';
						$param['is_boosted']				=	isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
						$param['is_featured ']				=	isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';

						$alastInsertId						=	$this->common_model->addData('festival_tbl', $param);

						$start_times = $this->request->getPost('event_start_hour')??[];
						$end_times = $this->request->getPost('event_end_hour');
						$locations = $this->request->getPost('location');
						$days = $this->request->getPost('day');
						$artist_ids = $this->request->getPost('artist_id')??[];
						$jazz_ids = $this->request->getPost('jazz_types_id');

						// Ensure artist_ids is an array
						if ($this->request->getPost('artist_id') && is_array($this->request->getPost('artist_id'))) {
							$artist_ids = $this->request->getPost('artist_id');
						}

						// Ensure jazz_ids is an array
						if ($this->request->getPost('jazz_types_id') && is_array($this->request->getPost('jazz_types_id'))) {
							$jazz_ids = $this->request->getPost('jazz_types_id');
						}

						if (is_array($start_times) && is_array($artist_ids) && count($start_times) > 0)
						{
							foreach ($start_times as $index => $start_time) {
								if (!empty($start_time) && !empty($end_times[$index]) && !empty($locations[$index]) && !empty($days[$index])) {
									$start_datetime = $this->request->getPost('start_date') . ' ' . $start_time . ':' . $this->request->getPost('event_start_min')[$index] . ' ' . $this->request->getPost('event_start_M')[$index];
									$end_datetime = $this->request->getPost('end_date') . ' ' . $end_times[$index] . ':' . $this->request->getPost('event_end_min')[$index] . ' ' . $this->request->getPost('event_end_M')[$index];


									$processed_artist_ids = [];
									foreach ($artist_ids[$index] as $artist_data) {
										if (is_numeric($artist_data)) {

											$artist_id = $artist_data;
											$artist_name = '';
										} else {

											$artist_name = $artist_data;


											$artist = $this->common_model->getSingleData('artist_tbl', ['artist_name' => $artist_name]);
											if (!$artist) {

												$artist_data = [
													'artist_name' => $artist_name,
													'is_active' => 1
												];
												$artist_id = $this->common_model->addData('artist_tbl', $artist_data);
												if (!$artist_id) {
													error_log('Failed to add artist: ' . $artist_name);
													continue;
												}
											} else {
												$artist_id = $artist->id;
											}
										}
										$processed_artist_ids[] = $artist_id;
									}

									$p_array = [
										'festival_id' => $alastInsertId,
										'start_time' => $start_datetime,
										'end_time' => $end_datetime,
										'location' => $locations[$index],
										'day' => $days[$index],
										'artist_id' => json_encode($processed_artist_ids),
										'jazz_types_id' => json_encode($jazz_ids[$index])
									];

									$save_lineup = $this->common_model->addData('lineup_tbl', $p_array);
									if (!$save_lineup) {
										error_log('Failed to save lineup data for index: ' . $index);
										continue;
									}
								}
							}
						}


						$this->session->setFlashdata('alert_success', lang('statictext_lang.statictext_lang.addsuccess'));
					}
				} else {

					$param['festival_name']				= 	$this->request->getPost('festival_name');
					$hour 								= 	$this->request->getPost('event_start_hour');
					$min								= 	$this->request->getPost('event_start_min');
					$event_start_M						= 	$this->request->getPost('event_start_M');
					$hour_end 							= 	$this->request->getPost('event_end_hour');
					$min_end							= 	$this->request->getPost('event_end_min');
					$event_end_M						= 	$this->request->getPost('event_end_M');
					$param['save_location_id']			= 	$this->request->getPost('save_location_id');
					$param['start_date']				= 	$this->request->getPost('start_date');
					$param['end_date']					= 	$this->request->getPost('end_date');

					$hour = is_array($hour) ? $hour[0] : $hour;
					$min = is_array($min) ? $min[0] : $min;
					$event_start_M = is_array($event_start_M) ? $event_start_M[0] : $event_start_M;
					$hour_end = is_array($hour_end) ? $hour_end[0] : $hour_end;
					$min_end = is_array($min_end) ? $min_end[0] : $min_end;
					$event_end_M = is_array($event_end_M) ? $event_end_M[0] : $event_end_M;
					$start_time = $hour && $min && $event_start_M ? "$hour:$min $event_start_M" : null;
					$end_time   = $hour_end && $min_end && $event_end_M ? "$hour_end:$min_end $event_end_M" : null;

					$param['start_time'] = $start_time;
					$param['end_time']   = $end_time;

					if($start_time && $param['start_date']) {
						$combined_date_and_time = $param['start_date'] . ' ' . $start_time;
						$param['date'] = strtotime($combined_date_and_time);
					}
					// $param['image'] = str_replace('','_',$this->request->getPost('image'));
					// echo"<pre>";print_r($param['image']);die;
					$param['city_state_name']		    = 	$this->request->getPost('city_state_name');
					$param['media_video_link']		    = 	$this->request->getPost('media_video_link');
					$param['lineup']				    = 	$this->request->getPost('lineup');
					$param['summary']				    = 	$this->request->getPost('summary');
					$param['year']				        = 	$this->request->getPost('year');

					$param['location_name']				= 	$this->request->getPost('location_name');
					$param['location_address']			= 	$this->request->getPost('location_address');
					$param['latitude']					= 	$this->request->getPost('latitude');
					$param['longitude']					= 	$this->request->getPost('longitude');
					$param['website']					= 	$this->request->getPost('website');
					$param['phone_number']				= 	$this->request->getPost('phone_number');
					$param['venue_id']					= 	$this->request->getPost('venue_id');
					if ($this->request->getPost('artist_id') && is_array($this->request->getPost('artist_id'))) {
						$param['artist_id'] = json_encode($this->request->getPost('artist_id'));
					}
					if ($this->request->getPost('jazz_types_id') && is_array($this->request->getPost('jazz_types_id'))) {
						$param['jazz_types_id'] = json_encode($this->request->getPost('jazz_types_id'));
					}

					$param['set_time']					= 	$this->request->getPost('set_time');
					$param['ip_address']				=	currentIp();
					$param['created_by']				=	(int)$this->session->get('ILCADM_ADMIN_ID');
					$param['creation_date']				= 	date('Y-m-d h:i:s');
					$param['is_active']					=	'1';
					$param['is_boosted']				=	isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
					$param['is_featured ']				=	isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';

					$checkFestival						    =	$this->common_model->checkFestival($param['festival_name'], $param['start_date'], $param['end_date']);
					if ($checkFestival) {
						$this->session->setFlashdata('alert_error', 'Festival with same name and date already exists');
					} else {
						$alastInsertId	=	$this->common_model->addData('festival_tbl', $param);
					}

					$start_times = $this->request->getPost('event_start_hour');
					$end_times = $this->request->getPost('event_end_hour');
					$locations = $this->request->getPost('location');
					$days = $this->request->getPost('day');
					$artist_ids = $this->request->getPost('artist_id');
					$jazz_ids = $this->request->getPost('jazz_types_id');

					if ($this->request->getPost('artist_id') && is_array($this->request->getPost('artist_id'))) {
						$artist_ids = $this->request->getPost('artist_id');
					}

					if ($this->request->getPost('jazz_types_id') && is_array($this->request->getPost('jazz_types_id'))) {
						$jazz_ids = $this->request->getPost('jazz_types_id');
					}

					if (is_array($start_times) && is_array($artist_ids) && count($start_times) > 0)
					{
						foreach ($start_times as $index => $start_time) {
							if (!empty($start_time) && !empty($end_times[$index]) && !empty($locations[$index]) && !empty($days[$index])) {
								$start_datetime = $this->request->getPost('start_date') . ' ' . $start_time . ':' . $this->request->getPost('event_start_min')[$index] . ' ' . $this->request->getPost('event_start_M')[$index];
								$end_datetime = $this->request->getPost('end_date') . ' ' . $end_times[$index] . ':' . $this->request->getPost('event_end_min')[$index] . ' ' . $this->request->getPost('event_end_M')[$index];


								$processed_artist_ids = [];
								foreach ($artist_ids[$index] as $artist_data) {
									if (is_numeric($artist_data)) {

										$artist_id = $artist_data;
										$artist_name = '';
									} else {

										$artist_name = $artist_data;


										$artist = $this->common_model->getSingleData('artist_tbl', ['artist_name' => $artist_name]);
										if (!$artist) {

											$artist_data = [
												'artist_name' => $artist_name,
												'is_active' => 1
											];
											$artist_id = $this->common_model->addData('artist_tbl', $artist_data);
											if (!$artist_id) {
												error_log('Failed to add artist: ' . $artist_name);
												continue;
											}
										} else {
											$artist_id = $artist->id;
										}
									}
									$processed_artist_ids[] = $artist_id;
								}

								$p_array = [
									'festival_id' => $alastInsertId,
									'start_time' => $start_datetime,
									'end_time' => $end_datetime,
									'location' => $locations[$index],
									'day' => $days[$index],
									'artist_id' => json_encode($processed_artist_ids),
									'jazz_types_id' => json_encode($jazz_ids[$index])
								];

								$save_lineup = $this->common_model->addData('lineup_tbl', $p_array);
								if (!$save_lineup) {
									error_log('Failed to save lineup data for index: ' . $index);
									continue;
								}
							}
						}
					}


					// echo"<pre>";print_r($_POST);die;


					$this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));
				}
			else :
				$this->common_model->deleteData('festival_tbl', 'festival_id', (int)$editId);
				$this->common_model->deleteData('lineup_tbl', 'festival_id', (int)$editId); //in ci3 its deleteId that is undefined

				if ($this->request->getPost('repeating_event') == 'Yes') {
					for ($i = 0; $i < $num_weeks; $i++) {
						if ($this->request->getPost('frequecy') == 'weekly') {
							$week_start = date("Y-m-d", strtotime("+" . $i . " week", strtotime($start_date)));
							$week_end = date("Y-m-d", strtotime("+" . ($i + 1) . " week - 1 day", strtotime($start_date)));

							$week_endd = date("Y-m-d", strtotime("+" . $i . " week", strtotime($endd_date)));
							$week_dates[$i] =  $week_start;

							$param['start_date']				= 	$week_start;
							//$param['end_date']					= 	$week_end;
							$param['end_date']					= 	$week_endd;
						} else {
							$week_start = date("Y-m-d", strtotime("+" . $i . " day", strtotime($start_date)));
							$week_endd = date("Y-m-d", strtotime("+" . $i . " day", strtotime($endd_date)));
							$week_dates[$i] = $week_start;
							$param['start_date']				= 	$week_start;
							//$param['end_date']					= 	$this->request->getPost('end_date');
							$param['end_date']					= 	$week_endd;
						}

						$param['festival_name']				= 	$this->request->getPost('festival_name');
						$hour 								= 	$this->request->getPost('event_start_hour');
						$min								= 	$this->request->getPost('event_start_min');
						$event_start_M						= 	$this->request->getPost('event_start_M');
						$hour_end 							= 	$this->request->getPost('event_end_hour');
						$min_end							= 	$this->request->getPost('event_end_min');
						$event_end_M						= 	$this->request->getPost('event_end_M');
						$param['save_location_id']			= 	$this->request->getPost('save_location_id');

						// checking if values are arrays and getting the first element
						$hour = is_array($hour) ? $hour[0] : $hour;
						$min = is_array($min) ? $min[0] : $min;
						$event_start_M = is_array($event_start_M) ? $event_start_M[0] : $event_start_M;
						$hour_end = is_array($hour_end) ? $hour_end[0] : $hour_end;
						$min_end = is_array($min_end) ? $min_end[0] : $min_end;
						$event_end_M = is_array($event_end_M) ? $event_end_M[0] : $event_end_M;

						$start_time = $hour && $min && $event_start_M ? "$hour:$min $event_start_M" : null;
						$end_time   = $hour_end && $min_end && $event_end_M ? "$hour_end:$min_end $event_end_M" : null;

						$param['start_time'] = $start_time;
						$param['end_time']   = $end_time;

						if($start_time && $param['start_date']) {
							$combined_date_and_time = $param['start_date'] . ' ' . $start_time;
							$param['date'] = strtotime($combined_date_and_time);
						}

						// $param['image'] = str_replace(' ','_',$this->request->getPost('image'));
						$param['city_state_name']		    = 	$this->request->getPost('city_state_name');
						$param['media_video_link']		    = 	$this->request->getPost('media_video_link');
						$param['lineup']				    = 	$this->request->getPost('lineup');
						$param['summary']				    = 	$this->request->getPost('summary');
						$param['year']				        = 	$this->request->getPost('year');
						$param['location_name']				= 	$this->request->getPost('location_name');
						$param['location_address']			= 	$this->request->getPost('location_address');
						$param['latitude']					= 	$this->request->getPost('latitude');
						$param['longitude']					= 	$this->request->getPost('longitude');
						$param['website']					= 	$this->request->getPost('website');
						$param['phone_number']				= 	$this->request->getPost('phone_number');
						$param['venue_id']					= 	$this->request->getPost('venue_id');
						if ($this->request->getPost('artist_id') && is_array($this->request->getPost('artist_id'))) {
							$param['artist_id'] = json_encode($this->request->getPost('artist_id'));
							// echo"<pre>";print_r($param['artist_id']);die;
						}
						if ($this->request->getPost('jazz_types_id') && is_array($this->request->getPost('jazz_types_id'))) {
							$param['jazz_types_id'] = json_encode($this->request->getPost('jazz_types_id'));
						}

						$param['cover_charge']				= 	$this->request->getPost('cover_charge');
						$param['set_time']					= 	$this->request->getPost('set_time');
						$param['time_permission']			= 	$this->request->getPost('time_permission');
						$param['repeating_event']			= 	$this->request->getPost('repeating_event');
						$param['frequecy']					= 	$this->request->getPost('frequecy');
						$param['ip_address']				=	currentIp();
						$param['created_by']				=	(int)$this->session->get('ILCADM_ADMIN_ID');
						$param['creation_date']				= 	date('Y-m-d h:i:s');
						$param['is_active']					=	'1';
						$param['is_boosted']				=	isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
						$param['is_featured ']				=	isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';
						$alastInsertId						=	$this->common_model->addData('festival_tbl', $param);

						$start_times = $this->request->getPost('event_start_hour');
						$end_times = $this->request->getPost('event_end_hour');
						$locations = $this->request->getPost('location');
						$days = $this->request->getPost('day');
						$artist_ids = $this->request->getPost('artist_id');
						$jazz_ids = $this->request->getPost('jazz_types_id');

						// Ensure artist_ids is an array
						if ($this->request->getPost('artist_id') && is_array($this->request->getPost('artist_id'))) {
							$artist_ids = $this->request->getPost('artist_id');
						}

						// Ensure jazz_ids is an array
						if ($this->request->getPost('jazz_types_id') && is_array($this->request->getPost('jazz_types_id'))) {
							$jazz_ids = $this->request->getPost('jazz_types_id');
						}

						if (is_array($start_times) && is_array($artist_ids) && count($start_times) > 0)
						{
							foreach ($start_times as $index => $start_time) {
								if (!empty($start_time) && !empty($end_times[$index]) && !empty($locations[$index]) && !empty($days[$index])) {
									$start_datetime = $this->request->getPost('start_date') . ' ' . $start_time . ':' . $this->request->getPost('event_start_min')[$index] . ' ' . $this->request->getPost('event_start_M')[$index];
									$end_datetime = $this->request->getPost('end_date') . ' ' . $end_times[$index] . ':' . $this->request->getPost('event_end_min')[$index] . ' ' . $this->request->getPost('event_end_M')[$index];


									$processed_artist_ids = [];
									foreach ($artist_ids[$index] as $artist_data) {
										if (is_numeric($artist_data)) {

											$artist_id = $artist_data;
											$artist_name = '';
										} else {

											$artist_name = $artist_data;


											$artist = $this->common_model->getSingleData('artist_tbl', ['artist_name' => $artist_name]);
											if (!$artist) {

												$artist_data = [
													'artist_name' => $artist_name,
													'is_active' => 1
												];
												$artist_id = $this->common_model->addData('artist_tbl', $artist_data);
												if (!$artist_id) {
													error_log('Failed to add artist: ' . $artist_name);
													continue;
												}
											} else {
												$artist_id = $artist->id;
											}
										}
										$processed_artist_ids[] = $artist_id;
									}

									$p_array = [
										'festival_id' => $alastInsertId,
										'start_time' => $start_datetime,
										'end_time' => $end_datetime,
										'location' => $locations[$index],
										'day' => $days[$index],
										'artist_id' => json_encode($processed_artist_ids),
										'jazz_types_id' => json_encode($jazz_ids[$index])
									];

									$save_lineup = $this->common_model->addData('lineup_tbl', $p_array);
									if (!$save_lineup) {
										error_log('Failed to save lineup data for index: ' . $index);
										continue;
									}
								}
							}
						}



						$this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));
					}
				} else {


					$param['festival_name']				= 	$this->request->getPost('festival_name');
					$hour 								= 	$this->request->getPost('event_start_hour');
					$min								= 	$this->request->getPost('event_start_min');
					$event_start_M						= 	$this->request->getPost('event_start_M');
					$hour_end 							= 	$this->request->getPost('event_end_hour');
					$min_end							= 	$this->request->getPost('event_end_min');
					$event_end_M						= 	$this->request->getPost('event_end_M');
					$param['save_location_id']			= 	$this->request->getPost('save_location_id');

					$param['start_date']				= 	$this->request->getPost('start_date');
					$param['end_date']					= 	$this->request->getPost('end_date');
				
					// checking if values are arrays and getting the first element
					$hour = is_array($hour) ? $hour[0] : $hour;
					$min = is_array($min) ? $min[0] : $min;
					$event_start_M = is_array($event_start_M) ? $event_start_M[0] : $event_start_M;
					$hour_end = is_array($hour_end) ? $hour_end[0] : $hour_end;
					$min_end = is_array($min_end) ? $min_end[0] : $min_end;
					$event_end_M = is_array($event_end_M) ? $event_end_M[0] : $event_end_M;
					$start_time = $hour && $min && $event_start_M ? "$hour:$min $event_start_M" : null;
					$end_time   = $hour_end && $min_end && $event_end_M ? "$hour_end:$min_end $event_end_M" : null;

					$param['start_time'] = $start_time;
					$param['end_time']   = $end_time;

					if($start_time && $param['start_date']) {
						$combined_date_and_time = $param['start_date'] . ' ' . $start_time;
						$param['date'] = strtotime($combined_date_and_time);
					}


					// $param['image'] = str_replace(' ','_',$this->request->getPost('image'));
					// echo"<pre>";print_r($param['image']);die;
					$param['city_state_name']		    = 	$this->request->getPost('city_state_name');
					$param['media_video_link']		    = 	$this->request->getPost('media_video_link');
					$param['lineup']				    = 	$this->request->getPost('lineup');
					$param['summary']				    = 	$this->request->getPost('summary');
					$param['year']				        = 	$this->request->getPost('year');
					$param['location_name']				= 	$this->request->getPost('location_name');
					$param['location_address']			= 	$this->request->getPost('location_address');
					$param['latitude']					= 	$this->request->getPost('latitude');
					$param['longitude']					= 	$this->request->getPost('longitude');
					$param['website']					= 	$this->request->getPost('website');
					$param['phone_number']				= 	$this->request->getPost('phone_number');
					$param['venue_id']					= 	$this->request->getPost('venue_id');
					if ($this->request->getPost('artist_id') && is_array($this->request->getPost('artist_id'))) {
						$param['artist_id'] = json_encode($this->request->getPost('artist_id'));
						// echo"<pre>";print_r($param['artist_id']);die;
					}
					if ($this->request->getPost('jazz_types_id') && is_array($this->request->getPost('jazz_types_id'))) {
						$param['jazz_types_id'] = json_encode($this->request->getPost('jazz_types_id'));
					}

					$param['set_time']					= 	$this->request->getPost('set_time');
					$param['ip_address']				=	currentIp();
					$param['created_by']				=	(int)$this->session->get('ILCADM_ADMIN_ID');
					$param['creation_date']				= 	date('Y-m-d h:i:s');
					$param['is_active']					=	'1';
					$param['is_boosted']				=	isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
					$param['is_featured ']				=	isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';
					$alastInsertId						=	$this->common_model->addData('festival_tbl', $param);

					$start_times = $this->request->getPost('event_start_hour');
					$end_times = $this->request->getPost('event_end_hour');
					$locations = $this->request->getPost('location');
					$days = $this->request->getPost('day');
					$artist_ids = $this->request->getPost('artist_id');
					$jazz_ids = $this->request->getPost('jazz_types_id');

					// Ensure artist_ids is an array
					if ($this->request->getPost('artist_id') && is_array($this->request->getPost('artist_id'))) {
						$artist_ids = $this->request->getPost('artist_id');
					}

					// Ensure jazz_ids is an array
					if ($this->request->getPost('jazz_types_id') && is_array($this->request->getPost('jazz_types_id'))) {
						$jazz_ids = $this->request->getPost('jazz_types_id');
					}

					if (is_array($start_times) && is_array($artist_ids) && count($start_times) > 0)
 					{
						foreach ($start_times as $index => $start_time) {
							if (!empty($start_time) && !empty($end_times[$index]) && !empty($locations[$index]) && !empty($days[$index])) {
								$start_datetime = $this->request->getPost('start_date') . ' ' . $start_time . ':' . $this->request->getPost('event_start_min')[$index] . ' ' . $this->request->getPost('event_start_M')[$index];
								$end_datetime = $this->request->getPost('end_date') . ' ' . $end_times[$index] . ':' . $this->request->getPost('event_end_min')[$index] . ' ' . $this->request->getPost('event_end_M')[$index];


								$processed_artist_ids = [];
								foreach ($artist_ids[$index] as $artist_data) {
									if (is_numeric($artist_data)) {

										$artist_id = $artist_data;
										$artist_name = '';
									} else {

										$artist_name = $artist_data;


										$artist = $this->common_model->getSingleData('artist_tbl', ['artist_name' => $artist_name]);
										if (!$artist) {

											$artist_data = [
												'artist_name' => $artist_name,
												'is_active' => 1
											];
											$artist_id = $this->common_model->addData('artist_tbl', $artist_data);
											if (!$artist_id) {
												error_log('Failed to add artist: ' . $artist_name);
												continue;
											}
										} else {
											$artist_id = $artist->id;
										}
									}
									$processed_artist_ids[] = $artist_id;
								}

								$p_array = [
									'festival_id' => $alastInsertId,
									'start_time' => $start_datetime,
									'end_time' => $end_datetime,
									'location' => $locations[$index],
									'day' => $days[$index],
									'artist_id' => json_encode($processed_artist_ids),
									'jazz_types_id' => json_encode($jazz_ids[$index])
								];

								$save_lineup = $this->common_model->addData('lineup_tbl', $p_array);
								if (!$save_lineup) {
									error_log('Failed to save lineup data for index: ' . $index);
									continue;
								}
							}
						}
					}

					$this->common_model->deleteAllLineup($editId);

					$this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));
				}

				$this->session->setFlashdata('alert_success', lang('statictext_lang.updatesuccess'));
			endif;
			// echo"<pre>";print_r($_POST);die;
			return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
		endif;

		$data['location']          = $this->common_model->getLocation(false);
		// echo"<pre>"; print_r($data['location']); die;
		$data['venues']            = $this->common_model->getCategory(false);
		$data['jazzTypes']         = $this->common_model->getCategoryJazz(false);
		$data['artistTypes']       = $this->common_model->getCategoryArtist(false);
		$this->layouts->set_title('Manage Festivals');
		$this->layouts->admin_view('festivals/addeditdata', array(), $data);
	}	// END OF FUNCTION	

	/***********************************************************************
	 ** Function name : changestatus
	 ** Developed By : Megha Kumari
	 ** Purpose  : This function used for change status
	 ** Date : 25 JUNE 2022
	 ************************************************************************/

	function changestatus($changeStatusId = '', $statusType = '')
	{
		$changeStatusId = $this->uri->getSegment(4);
		$statusType = $this->uri->getSegment(5);
		$this->admin_model->authCheck('edit_data');
		$param['is_active']		=	$statusType;

		$this->common_model->editData('festival_tbl', $param, 'festival_id', (int)$changeStatusId);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}

	function mutlipleChangeStatus()
	{
		$changeStatusIds = json_decode($_POST['changeStatusIds']);
		$statusType = $_POST['statusType'];
		// echo $statusType; die;
		// print_r($changeStatusIds); die;
		if ($statusType !== "permanentdelete") {
			$this->admin_model->authCheck('edit_data');
			foreach ($changeStatusIds as $changeStatusId) {
				// $param['is_active'] = $statusType;
				if ($statusType == 'inactive') {
					$param['is_active'] = "0";
				} else if ($statusType == 'active') {
					$param['is_active'] = "1";
				} else if ($statusType == 'unboost') {
					$param['is_boosted'] = "0";
				} else if ($statusType == 'boost') {
					$param['is_boosted'] = "1";
				} else if ($statusType == 'unfeatured') {
					$param['is_featured'] = "0";
				} else if ($statusType == 'featured') {
					$param['is_featured'] = "1";
				}

				$this->common_model->editData('festival_tbl', $param, 'festival_id', (int)$changeStatusId);
				$this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));
			}
		} else {
			foreach ($changeStatusIds as $changeStatusId) {
				$this->admin_model->authCheck('delete_data');
				$this->common_model->deleteData('festival_tbl', 'festival_id', (int)$changeStatusId);
				$this->session->setFlashdata('alert_success', lang('statictext_lang.deletesuccess'));
			}
		}

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}


	function statusboost($changeStatusId = '', $statusType = '')
	{
		$changeStatusId = $this->uri->getSegment(4);
		$statusType = $this->uri->getSegment(5);
		$this->admin_model->authCheck('edit_data');
		$param['is_boosted']		=	$statusType;

		$this->common_model->editData('festival_tbl', $param, 'festival_id', (int)$changeStatusId);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}

	function statusfeatured($changeStatusId = '', $statusType = '')
	{
		$changeStatusId = $this->uri->getSegment(4);
		$statusType = $this->uri->getSegment(5);
		$this->admin_model->authCheck('edit_data');
		$param['is_featured']		=	$statusType;

		$this->common_model->editData('festival_tbl', $param, 'festival_id', (int)$changeStatusId);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}

	/***********************************************************************
	 ** Function name 	: deletedata
	 ** Developed By 	: Megha Kumari
	 ** Purpose  		: This function used for delete data
	 ** Date 			: 27 JUNE 2022
	 ************************************************************************/
	function deletedata($deleteId = '')
	{
		$deleteId = $this->uri->getSegment(4);
		$this->admin_model->authCheck('delete_data');

		$this->common_model->deleteData('festival_tbl', 'festival_id', (int)$deleteId);
		$this->common_model->deleteData('lineup_tbl', 'festival_id', (int)$deleteId);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.deletesuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}

	function location()
	{
		$location_id = $_GET['LocationId'];
		$dataQuery = $this->db->select('*')->from('event_location_tbl')->where('id', $location_id)->get()->row();
		echo json_encode(array('location_name' => $dataQuery->location_name, 'location_address' => $dataQuery->location_address, 'latitude' => $dataQuery->latitude, 'longitude' => $dataQuery->longitude, 'website' => $dataQuery->website, 'phone_number' => $dataQuery->phone_number, 'venue_id' => $dataQuery->venue_id, 'artist_id' => $dataQuery->artist_id));
	}

	/***********************************************************************
	 ** Function name 	: updateStatus
	 ** Developed By 	: Megha Kumari
	 ** Purpose  		: This function used for delete data
	 ** Date 			: 27 JUNE 2022
	 ************************************************************************/
	function updateStatus($id = '')
	{
		$id = $this->uri->getSegment(4);
		$param['is_active'] = '2';

		// $whereCon = "festival_id = '" . $id . "' ";
		$whereCon = ['festival_id' => $id];
		$this->common_model->editMultipleDataByMultipleCondition('festival_tbl', $param, $whereCon);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.updatesuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}

	/***********************************************************************
	 ** Function name 	: updateStatus
	 ** Developed By 	: Megha Kumari
	 ** Purpose  		: This function used for delete data
	 ** Date 			: 27 JUNE 2022
	 ************************************************************************/
	function duplicate($id = '')
	{

		$uri = service('uri');
		$id = $uri->getSegment(4);
		$db = \Config\Database::connect();
		$builder = $db->table('festival_tbl');
		$builder->where('festival_id', $id);
		$query = $builder->get();
		$data = $query->getRow();
		// $data = $this->db->select('*')->from('festival_tbl')->where('festival_id', $id)->get()->row();

		$param['festival_name']				    = 	$data->festival_name;
		// $param['save_location_id']				= 	$data->save_location_id;
		$param['start_date']					= 	$data->start_date;
		$param['end_date']						= 	$data->end_date;
		$param['no_of_copy']					= 	$data->no_of_copy + 1;
		// $param['location_name']					= 	$data->location_name;
		$param['location_address']				= 	$data->location_address;
		$param['latitude']						= 	$data->latitude;
		$param['longitude']						= 	$data->longitude;
		$param['website']						= 	$data->website;
		$param['lineup']						= 	$data->lineup;
		$param['phone_number']					= 	$data->phone_number;
		// $param['venue_id']						= 	$data->venue_id;
		$param['artist_id']						= 	$data->artist_id;
		$param['set_time']						= 	$data->set_time;
		$param['start_time']				    =   $data->start_time;
		$param['end_time']				        = 	$data->end_time;
		$param['media_video_link']				= 	$data->media_video_link;
		$param['city_state_name']				= 	$data->city_state_name;
		$param['ip_address']					=	currentIp();
		$param['created_by']					=	(int)$this->session->get('ILCADM_ADMIN_ID');
		$param['creation_date']					= 	date('Y-m-d h:i:s');
		$param['is_active']					    =	'1';
		$param['is_boosted']				    =	isset($data->is_boosted) ? $data->is_boosted : '0';
		$param['is_featured ']				    =	isset($data->is_featured) ? $data->is_featured : '0';
		// echo"here";die;
		$this->common_model->addData('festival_tbl', $param);

		$this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}


	// public function import()
	// {

		// 	require_once APPPATH . 'third_party/classes/PHPExcel.php';

		// 	$data['error'] = '';
		// 	$data['activeMenu'] = 'adminmanagefestivals';
		// 	$data['activeSubMenu'] = 'adminmanagefestivals';

		// 	if ($editId) :
		// 		$this->admin_model->authCheck('edit_data');
		// 		$data['EDITDATA'] = $this->common_model->getDataByParticularField('import_tbl', 'id', (int)$editId);

		// 	else :
		// 		$this->admin_model->authCheck('add_data');
		// 	endif;

		// 	if ($this->request->getPost('SaveChanges')) :
		// 		$error = 'NO';

		// 		$this->form_validation->set_rules('import_file', 'Import File', 'trim|required|max_length[256]');

		// 		if ($this->form_validation->run() && $error == 'NO') :

		// 			$config['upload_path']          = './assets/admin/document/';
		// 			$config['allowed_types']        = 'xls|xlsx|csv';
		// 			$config['overwrite']            = TRUE;
		// 			$this->load->library('upload', $config);

		// 			if (!$this->upload->do_upload('import_file')) {
		// 				$error = array('error' => $this->upload->display_errors());
		// 				$this->session->setFlashdata('alert_error', $error['error']);
		// 				redirect(correctLink('userILCADMData', getCurrentControllerPath('festivals/import')));
		// 			} else {
		// 				$data = array('upload_data' => $this->upload->data());
		// 				$file_path = './assets/admin/document/' . $data['upload_data']['file_name'];
		// 				$objPHPExcel = PHPExcel_IOFactory::load($file_path);
		// 				$sheet = $objPHPExcel->getActiveSheet();
		// 				$highestRow = $sheet->getHighestRow();

		// 				$import_data = array(
		// 					'import_file' => $data['upload_data']['file_name'],
		// 					'is_active' => "1"
		// 				);
		// 				$this->common_model->addData('import_tbl', $import_data);

		// 				for ($row = 2; $row <= $highestRow; $row++) {

		// 					$festival_name  = $sheet->getCellByColumnAndRow(0, $row)->getValue();
		// 					$location_name = $sheet->getCellByColumnAndRow(1, $row)->getValue();
		// 					$location_address = $sheet->getCellByColumnAndRow(2, $row)->getValue();
		// 					$latitude = $sheet->getCellByColumnAndRow(3, $row)->getValue();
		// 					$longitude = $sheet->getCellByColumnAndRow(4, $row)->getValue();
		// 					$venue_id = $sheet->getCellByColumnAndRow(5, $row)->getValue();
		// 					$website = $sheet->getCellByColumnAndRow(8, $row)->getValue();
		// 					$phone_number = $sheet->getCellByColumnAndRow(9, $row)->getValue();
		// 					$year = $sheet->getCellByColumnAndRow(12, $row)->getValue();
		// 					$Lineup = $sheet->getCellByColumnAndRow(13, $row)->getValue();
		// 					$Summary = $sheet->getCellByColumnAndRow(14, $row)->getValue();

		// 					$start_date_value = $sheet->getCellByColumnAndRow(6, $row)->getValue();
		// 					$end_date_value = $sheet->getCellByColumnAndRow(7, $row)->getValue();

		// 					$start_date = $this->parseDate($start_date_value);
		// 					$end_date = $this->parseDate($end_date_value);

		// 					$is_active_excel = $sheet->getCellByColumnAndRow(10, $row)->getValue();
		// 					$is_active = "1";

		// 					$artists = array();
		// 					$artist_name = $sheet->getCellByColumnAndRow(11, $row)->getValue();
		// 					if (!empty($artist_name)) {

		// 						if (strpos($artist_name, ',') !== false) {
		// 							$artist_names = explode(',', $artist_name);
		// 							$artist_names = array_map('trim', $artist_names);
		// 							$artists = array_merge($artists, $artist_names);
		// 						} else {
		// 							$artists[] = $artist_name;
		// 						}
		// 					}
		// 					$artists_ids = array();

		// 					foreach ($artists as $artist_name) {
		// 						$this->db->select('id');
		// 						$this->db->from('artist_tbl');
		// 						$this->db->where('artist_name', $artist_name);

		// 						$query = $this->db->get();
		// 						if ($query->num_rows() > 0) {
		// 							$row = $query->row();
		// 							$artist_id = $row->id;
		// 							$artists_ids[] = $artist_id;
		// 						}
		// 					}
		// 					$artist_ids_json = json_encode($artists_ids);
		// 					$festival_data = array(
		// 						'festival_name'  => $festival_name,
		// 						'location_name' => $location_name,
		// 						'location_address' => $location_address,
		// 						'latitude' => $latitude,
		// 						'longitude' => $longitude,
		// 						'venue_id' => $venue_id,
		// 						'start_date' => $start_date,
		// 						'end_date' =>  $end_date,
		// 						'website' => $website,
		// 						'phone_number' => $phone_number,
		// 						'is_active' => $is_active,
		// 						'artist_id' => $artist_ids_json,
		// 						'year' => $year,
		// 						'Lineup' => $Lineup,
		// 						'Summary' => $Summary,
		// 					);

		// 					$save_location_id = $festival_data['location_name'];
		// 					$location_data = $this->common_model->getDataByParticularField('event_location_tbl', 'location_name', $save_location_id);

		// 					if ($location_data) {
		// 						$festival_data['save_location_id'] = $location_data['id'];
		// 					} else {
		// 						$festival_data['save_location_id'] = '';
		// 					}

		// 					$venue_id = $festival_data['venue_id'];
		// 					$venue_data = $this->common_model->getDataByParticularField('venue_tbl', 'venue_title', $venue_id);

		// 					if ($venue_data) {
		// 						$festival_data['venue_id'] = $venue_data['id'];
		// 					} else {
		// 						$festival_data['venue_id'] = '';
		// 					}
		// 					unset($festival_data['venue_title']);

		// 					// $artist_id = $festival_data['artist_id'];
		// 					// $artist_data = $this->common_model->getDataByParticularField('artist_tbl', 'artist_name', $artist_id);

		// 					// if ($artist_data) {
		// 					// 	$festival_data['artist_id'] = $artist_data['id'];
		// 					// } else {
		// 					// 	$festival_data['artist_id'] = '';
		// 					// }
		// 					// echo"<pre>";print_r($festival_data);die;

		// 					$this->common_model->addData('festival_tbl', $festival_data);
		// 				}

		// 				$this->session->setFlashdata('alert_success', 'Festivals imported successfully.');
		// 				redirect(correctLink('userILCADMData', getCurrentControllerPath('festivals/import')));
		// 			}

		// 		endif;
		// 	endif;

		// 	$this->layouts->set_title('Manage Import');
		// 	$this->layouts->admin_view('festivals/import');
	// }

	// function parseDate($date_value)
	// {
		// 	$date_value = str_replace('/', '-', $date_value);
		// 	$date_parts = explode('-', $date_value);

		// 	if (count($date_parts) === 3) {
		// 		$month = $date_parts[0];
		// 		$day = $date_parts[1];
		// 		$year = $date_parts[2];

		// 		if (strlen($year) === 2) {
		// 			$year = ($year >= 70) ? '19' . $year : '20' . $year;
		// 		}

		// 		$formatted_date = sprintf('%02d-%02d-%02d', $year, $month, $day);

		// 		return $formatted_date;
		// 	} else {
		// 		return null;
		// 	}
	// }


	// public function import()
	// {
	// 	require_once APPPATH . 'third_party/classes/PHPExcel.php';

	// 	$data['error'] = '';
	// 	$data['activeMenu'] = 'adminmanagefestivals';
	// 	$data['activeSubMenu'] = 'adminmanagefestivals';

	// 	if ($editId) :
	// 		$this->admin_model->authCheck('edit_data');
	// 		$data['EDITDATA'] = $this->common_model->getDataByParticularField('import_tbl', 'id', (int)$editId);
	// 	else :
	// 		$this->admin_model->authCheck('add_data');
	// 	endif;

	// 	if ($this->request->getPost('SaveChanges')) :
	// 		$error = 'NO';

	// 		$this->form_validation->set_rules('import_file', 'Import File', 'trim|required|max_length[256]');

	// 		if ($this->form_validation->run() && $error == 'NO') :

	// 			$config['upload_path'] = './assets/admin/document/';
	// 			$config['allowed_types'] = 'xls|xlsx|csv';
	// 			$config['overwrite'] = TRUE;
	// 			$this->load->library('upload', $config);

	// 			if (!$this->upload->do_upload('import_file')) {
	// 				$error = array('error' => $this->upload->display_errors());
	// 				$this->session->setFlashdata('alert_error', $error['error']);
	// 				redirect(correctLink('userILCADMData', getCurrentControllerPath('festivals/import')));
	// 			} else {
	// 				$data = array('upload_data' => $this->upload->data());
	// 				$file_path = './assets/admin/document/' . $data['upload_data']['file_name'];
	// 				$objPHPExcel = PHPExcel_IOFactory::load($file_path);
	// 				$sheet = $objPHPExcel->getActiveSheet();
	// 				$highestRow = $sheet->getHighestRow();

	// 				$import_data = array(
	// 					'import_file' => $data['upload_data']['file_name'],
	// 					'is_active' => "1"
	// 				);
	// 				$this->common_model->addData('import_tbl', $import_data);

	// 				for ($row = 2; $row <= $highestRow; $row++) {
	// 					$festival_name = $sheet->getCellByColumnAndRow(0, $row)->getValue();
	// 					// echo "Festival Name: $festival_name <br>";
	// 					$website = $sheet->getCellByColumnAndRow(2, $row)->getValue();
	// 					$lineup = $sheet->getCellByColumnAndRow(3, $row)->getValue();
	// 					$city_state_name = $sheet->getCellByColumnAndRow(4, $row)->getValue();
	// 					$summary = $sheet->getCellByColumnAndRow(5, $row)->getValue();
	// 					$latLong = $this->getLatLongByCity($city_state_name);
	// 					$latitude = $latLong ? $latLong['lat'] : NULL;
	// 					$longitude = $latLong ? $latLong['lng'] : NULL;
	// 					$is_active = '1';

	// 					$festival_dates = explode(';', $sheet->getCellByColumnAndRow(1, $row)->getValue());

	// 					for ($row = 2; $row <= $highestRow; $row++) {
	// 						$festival_name = $sheet->getCellByColumnAndRow(0, $row)->getValue();
	// 						$website = $sheet->getCellByColumnAndRow(2, $row)->getValue();
	// 						$lineup = $sheet->getCellByColumnAndRow(3, $row)->getValue();
	// 						$city_state_name = $sheet->getCellByColumnAndRow(4, $row)->getValue();
	// 						$summary = $sheet->getCellByColumnAndRow(5, $row)->getValue();
	// 						$latLong = $this->getLatLongByCity($city_state_name);
	// 						$latitude = $latLong ? $latLong['lat'] : NULL;
	// 						$longitude = $latLong ? $latLong['lng'] : NULL;
	// 						$is_active = '1';

	// 						$festival_dates = explode(';', $sheet->getCellByColumnAndRow(1, $row)->getValue());

	// 						foreach ($festival_dates as $date_range) {
	// 							$date_range = preg_replace('/\s+/', ' ', trim($date_range));
	// 							$date_parts = explode('-', $date_range);

	// 							if (isset($date_parts[0]) && $date_parts[0] != '') {
	// 								$start_date_obj = date_create_from_format('M d', trim($date_parts[0]));
	// 								$start_date = $start_date_obj ? $start_date_obj->format('Y-m-d') : null;
	// 							} else {
	// 								$start_date = null;
	// 							}

	// 							if (isset($date_parts[1]) && $date_parts[1] != '') {
	// 								$end_date_obj = date_create_from_format('M d', trim($date_parts[1]));
	// 								$end_date = $end_date_obj ? $end_date_obj->format('Y-m-d') : null;
	// 							} else {
	// 								$end_date = null;
	// 							}

	// 							$festival_data = array(
	// 								'festival_name' => $festival_name,
	// 								'website' => $website,
	// 								'lineup' => $lineup,
	// 								'city_state_name' => $city_state_name,
	// 								'summary' => $summary,
	// 								'latitude' => $latitude,
	// 								'longitude' => $longitude,
	// 								'start_date' => $start_date,
	// 								'end_date' => $end_date,
	// 								'is_active' => $is_active,
	// 								'creation_date' => date('Y-m-d H:i:s')
	// 							);

	// 							$existing_festival = $this->common_model->getDataByCondition('festival_tbl', array('festival_name' => $festival_name, 'start_date' => $start_date, 'end_date' => $end_date));

	// 							if (!$existing_festival) {
	// 								// Import the festival data
	// 								$this->common_model->addData('festival_tbl', $festival_data);
	// 							}
	// 						}
	// 					}
	// 				}
	// 				// die;

	// 				$this->session->setFlashdata('alert_success', 'Festivals imported successfully.');

	// 				redirect(correctLink('userILCADMData', getCurrentControllerPath('festivals/import')));
	// 			}

	// 		endif;
	// 	endif;

	// 	$this->layouts->set_title('Manage Import');
	// 	$this->layouts->admin_view('festivals/import');
	// }
	public function import()
	{
		require_once ROOTPATH . 'vendor/autoload.php';
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanagefestivals';
		$data['activeSubMenu'] 				= 	'adminmanagefestivals';
		// echo"herere";die;
		// echo "Request Method: " . $this->request->getMethod() . "<br>";

		// Debugging: Check if SaveChanges is received
		//echo "SaveChanges Value: " . $this->request->getPost('SaveChanges') . "<br>";

		// Stop execution
		//die();
		if ($this->request->getMethod() === 'Post' && $this->request->getPost('SaveChanges') !== null) {
			echo"herere1";die;
			$error = 'NO';
            
			$file = $this->request->getFile('import_file');

			if (!$file->isValid()) {
				$this->session->setFlashdata('alert_error', 'Invalid file uploaded.');
				return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('festivals/import')));
			}

			// Validate file type
			$allowedTypes = ['xls', 'xlsx', 'csv'];
			$fileExtension = $file->getExtension();

			if (!in_array($fileExtension, $allowedTypes)) {
				$this->session->setFlashdata('alert_error', 'Invalid file type. Only XLS, XLSX, and CSV allowed.');
				return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('festivals/import')));
			}

			// Move file to uploads folder
			$newName = $file->getRandomName();
			$file->move('./assets/admin/document/', $newName);
			$filePath = './assets/admin/document/' . $newName;

			// Load Excel file
			$spreadsheet = IOFactory::load($filePath);
			$sheet = $spreadsheet->getActiveSheet();
			$highestRow = $sheet->getHighestRow();

			// Save import record
			$import_data = [
				'import_file' => $newName,
				'is_active' => "1"
			];
			$this->common_model->addData('import_tbl', $import_data);

			// Loop through the spreadsheet rows
			for ($row = 2; $row <= $highestRow; $row++) {
				$festival_name = trim($sheet->getCellByColumnAndRow(0, $row)->getValue());
				$website = trim($sheet->getCellByColumnAndRow(2, $row)->getValue());
				$lineup = trim($sheet->getCellByColumnAndRow(3, $row)->getValue());
				$city_state_name = trim($sheet->getCellByColumnAndRow(4, $row)->getValue());
				$summary = trim($sheet->getCellByColumnAndRow(5, $row)->getValue());

				$latLong = $this->getLatLongByCity($city_state_name);
				$latitude = $latLong ? $latLong['lat'] : NULL;
				$longitude = $latLong ? $latLong['lng'] : NULL;
				$is_active = '1';

				// Extract festival dates
				$festival_dates = explode(';', $sheet->getCellByColumnAndRow(1, $row)->getValue());

				foreach ($festival_dates as $date_range) {
					$date_range = preg_replace('/\s+/', ' ', trim($date_range));
					$date_parts = explode('-', $date_range);

					$start_date = isset($date_parts[0]) ? $this->convertDate($date_parts[0]) : null;
					$end_date = isset($date_parts[1]) ? $this->convertDate($date_parts[1]) : null;

					$festival_data = [
						'festival_name' => $festival_name,
						'website' => $website,
						'lineup' => $lineup,
						'city_state_name' => $city_state_name,
						'summary' => $summary,
						'latitude' => $latitude,
						'longitude' => $longitude,
						'start_date' => $start_date,
						'end_date' => $end_date,
						'is_active' => $is_active,
						'creation_date' => date('Y-m-d H:i:s')
					];

					$existing_festival = $this->common_model->getDataByCondition('festival_tbl', [
						'festival_name' => $festival_name,
						'start_date' => $start_date,
						'end_date' => $end_date
					]);

					if (!$existing_festival) {
						$this->common_model->addData('festival_tbl', $festival_data);
					}
				}
			}

			$this->session->setFlashdata('alert_success', 'Festivals imported successfully.');
			return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('festivals/import')));
		}
       
		$this->layouts->set_title('Manage Import');
		//echo"herere1xx";die;
		$this->layouts->admin_view('festivals/import',array(),  $data);
	}

	// Helper function to convert date format
	private function convertDate($dateStr)
	{
		$dateStr = trim($dateStr);
		if (!$dateStr) return null;

		// Try different formats
		$formats = ['M d', 'd M', 'd-m', 'M-d'];
		foreach ($formats as $format) {
			$dateObj = date_create_from_format($format, $dateStr);
			if ($dateObj) {
				return $dateObj->format('Y-m-d');
			}
		}

		return null;
	}

	private function getLatLongByCity($city)
	{
		
		$apiKey = getenv('API_KEY');
		$city = urlencode($city);
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address={$city}&key={$apiKey}";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		curl_close($ch);

		$data = json_decode($response);

		if ($data->status == 'OK') {
			$latitude = $data->results[0]->geometry->location->lat;
			$longitude = $data->results[0]->geometry->location->lng;
			return array('lat' => $latitude, 'lng' => $longitude);
		} else {
			return false;
		}
	}
}
