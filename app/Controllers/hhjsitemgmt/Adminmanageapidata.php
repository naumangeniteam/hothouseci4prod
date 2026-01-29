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
use App\Libraries\Elastichh;
class Adminmanageapidata extends BaseController
{
	protected $admin_model;
    protected $emailTemplateModel;
    protected $smsModel;
    protected $notificationModel;
	protected $common_model;
	protected $session;
	protected $layouts;
	private $validation;
	private $venusBasedOnStates;
	private $lang;
	protected $uri;
	protected $elastichh_lib;
	public function  __construct()
	{
		$this->validation = service('validation');
		$this->admin_model = new AdminModel();
		$this->emailTemplateModel = new EmailtemplateModel();
        $this->smsModel = new SmsModel();
        $this->notificationModel = new NotificationModel();
		$this->common_model = new CommonModel();
		error_reporting(0);
	
	   $this->lang = service('language'); 
       $this->lang->setLocale('admin');
		$this->layouts = new Layouts();
		$this->session = session();
		$this->uri = service('uri');
		helper(['common','general']);
		// $this->load->library('elastichh');
		// require_once APPPATH . 'controllers/front/Ticketmaster.php'; 
		$this->venusBasedOnStates = [
            'NY' => 5, 'NJ' => 17, 'CT' => 16, 'PA' => 18, 'MA' => 25,
            'MD' => 21, 'DC' => 28, 'RI' => 26, 'DE' => 22, 'MI' => 21,
            'WA' => 28
        ];
		$this->elastichh_lib = new  Elastichh();
	}

	/* * *********************************************************************
	 * * Function name 	: index
	 * * Developed By 	: Megha Kumari
	 * * Purpose  		: This function used for IL Talks
	 * * Date 			: 27 JUNE 2022
	 * * **********************************************************************/

	public function index()
	{
	
		$all_ven =	$this->common_model->venue();
		$date = date("Y-m-d");
		$date = strtotime(date("Y-m-d", strtotime($date)) . "-2 months");
		$date = date("Y-m-d", $date);
	
		$this->admin_model->authCheck('view_data');
		$this->admin_model->getPermissionType($data);
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanageapidata';
		$data['activeSubMenu'] 				= 	'adminmanageapidata';

		$where['where'] = ["is_imported_event_tbl" => 0];
		$tbl            =    'ticketmaster_event_tbl as ftable';
		$uriSegment = getUrlSegment();
		if ($this->request->getUri()->getSegment($uriSegment)) :
			$page = $this->request->getUri()->getSegment($uriSegment);
		else :
			$page = 0;
		endif;
		$baseUrl 							= 	base_url() . 'hhjsitemgmt/adminmanageapidata/index';
		$this->session->set('userILCADMData', currentFullUrl());
		$qStringdata						=	explode('?', currentFullUrl());
		$suffix								= 	$qStringdata[1] ? '?' . $qStringdata[1] : '';
		
		$totalRows 	= 		$this->common_model->getLocAPIData('count',$tbl,$where, 0,0);
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
		// echo "<br>Page = " .$page;
		
		$arrDataReturn = $this->common_model->getLocAPIData('multiple', $tbl, $where,$perPage, $page,$offset);
	

		
		$ALLDATA=array();
		
		$sel_venue="<select id='select_venue'><option selected value=''>Select Venue</option>";
		foreach ($all_ven as $ven) {
			$op = "<option value='" . $ven['id'] . "'>" . $ven['venue_title'] . "</option>";
			$sel_venue.= $op;
		}
		$sel_venue.="</select>";
		$data['sel_venue']=$sel_venue;
		if (!empty($arrDataReturn)) {
			
			foreach ($arrDataReturn as $arrEventData) {
				// get a single object of an event from the json
				$arrCompleteSingleEventData = json_decode($arrEventData['all_data'], true);
				$param['event_id'] = $arrEventData['event_id'];
				if (!empty($arrCompleteSingleEventData['_embedded']['venues'])) {
				
					$param['latitude']  =  !empty($arrCompleteSingleEventData['_embedded']['venues'][0]['location']['latitude']) ? $arrCompleteSingleEventData['_embedded']['venues'][0]['location']['latitude'] : 0;
					$param['longitude'] =     !empty($arrCompleteSingleEventData['_embedded']['venues'][0]['location']['longitude']) ? $arrCompleteSingleEventData['_embedded']['venues'][0]['location']['longitude'] : 0;
					$param['location_name']  =     $arrCompleteSingleEventData['_embedded']['venues'][0]['name'];
					$param['location_address'] =     !empty($arrCompleteSingleEventData['_embedded']['venues'][0]['address']['line1']) ? $arrCompleteSingleEventData['_embedded']['venues'][0]['address']['line1'] : "";
				}
				$param['match_loc'] = null;
				$param['loc_id'] = null;
				$param['db_venue'] = null;
				$param['match_add'] = null;
				$param['match_loc_lat'] = null;
				$param['match_loc_long'] = null;
				if($param['location_name'])
				{
					die;
				$dbdata = $this->searchElasticLocations($param['location_name']);
							if (!empty($dbdata)) {
									$param['match_loc'] = $dbdata[0]['_source']['location_name'] ?? '';
									$param['match_add'] = $dbdata[0]['_source']['location_address'] ?? '';
									$param['loc_id'] = $dbdata[0]['_id'] ?? '';
									$param['db_venue'] =$dbdata[0]['_source']['venue_id'] ?? '';
									$param['match_loc_lat'] = $dbdata[0]['_source']['location_lat'] ?? '';
									$param['match_loc_long'] = $dbdata[0]['_source']['location_long'] ?? '';
								}
				}
				
				$param['event_title']  = $arrCompleteSingleEventData['name'];
				$param['event_source'] = "ticketmaster";
				$param['start_date'] = !empty($arrCompleteSingleEventData['dates']['start']['localDate']) ? $arrCompleteSingleEventData['dates']['start']['localDate'] : "";
				$param['end_date']     = !empty($arrCompleteSingleEventData['dates']['end']['localDate']) ? $arrCompleteSingleEventData['dates']['end']['localDate'] : $param['start_date'];
				$param['event_start_time']    =     !empty($arrCompleteSingleEventData['dates']['start']['localTime']) ? $arrCompleteSingleEventData['dates']['start']['localTime'] : "";
				$param['event_end_time']    =     !empty($arrCompleteSingleEventData['dates']['end']['localTime']) ? date("H:i", strtotime($arrCompleteSingleEventData['dates']['end']['localTime'])) : "";
				$param['event_start_time'] = date("H:i", strtotime($param['event_start_time']));
				$combined_date_and_time = $param['start_date'] . ' ' . $param['event_start_time'];
				// $param['date'] = strtotime($combined_date_and_time);
				$param['venue_id']                =     $this->venusBasedOnStates[$arrCompleteSingleEventData['_embedded']['venues'][0]['state']['stateCode']];
				// $param['venue_id']                =    $arrCompleteSingleEventData['_embedded']['venues'][0]['state']['stateCode'];
				$varDescription = "";
				if (!empty($arrCompleteSingleEventData['info'])) {
					$varDescription = $arrCompleteSingleEventData['info'];
				}
				if (!empty($arrCompleteSingleEventData['info'])) {
					$varDescription = $varDescription . "<br/>" . $arrCompleteSingleEventData['pleaseNote'];
				}
				$param['description'] =  $varDescription;
					$ALLDATA[]=$param;
				
			}
			$data['ALLDATA']=$ALLDATA;
			$this->layouts->set_title('Manage Api Data');
			// echo "<pre>";
		
			$this->layouts->admin_view('api_data/manage_api_import', array(), $data);
		}
		else{
			$this->layouts->admin_view('api_data/manage_api_import', array(),array());
		}
		
	
	}	// END OF FUNCTION



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
		// echo"<pre>";
		// print_r($_POST);die;
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanageapidata';
		$data['activeSubMenu'] 				= 	'adminmanageapidata';
		$data['EDITDATA']		=	$this->common_model->getDataByParticularField('event_tbl', 'event_id', (int)$editId);
		$data['EDITDATAEVENT']	=	$this->common_model->getAllDataByParticularField('event_tags_tbl', 'event_id', (int)$editId);
		// Fetch jazz types related to the event
		$data['SELECTED_JAZZ_TYPES'] = $this->common_model->getAllDataByParticularField('event_jazz_tbl', 'event_id', (int)$editId);
		$data['EDITDATA']['jazz_types_id'] = []; 
		if ($editId) :
			$this->admin_model->authCheck('edit_data');
			$data['EDITDATA']		=	$this->common_model->getDataByParticularField('event_tbl', 'event_id', (int)$editId);
			$data['EDITDATAEVENT']	=	$this->common_model->getAllDataByParticularField('event_tags_tbl', 'event_id', (int)$editId);
			// Fetch jazz types related to the event
			$data['SELECTED_JAZZ_TYPES'] = $this->common_model->getAllDataByParticularField('event_jazz_tbl', 'event_id', (int)$editId);

			// Convert selected jazz types to array for easy checking
			if (!empty($data['SELECTED_JAZZ_TYPES']) && is_array($data['SELECTED_JAZZ_TYPES'])) {
				$data['EDITDATA']['jazz_types_id'] = array_column($data['SELECTED_JAZZ_TYPES'], 'event_jazz_types_id');
			} else {
				$data['EDITDATA']['jazz_types_id'] = []; // Set an empty array to prevent errors
			}
		// echo "<pre>";print_r($data['EDITDATA']);die;
		else :
			$this->admin_model->authCheck('add_data');
		endif;

		if ($this->request->getPost('SaveChanges')) :

			$error					=	'NO';
			
	
			$postData = $this->request->getPost();
			$error = 'NO';
		
			// Define validation rules
			$rules = [
				'event_title'      => 'required',
				'start_date'       => 'required',
				'location_address' => 'required',
				'venue_id'         => 'required',
			];
		
			// Custom error messages
			$messages = [
				'event_title' => ['required' => 'Event Title is required.'],
				'start_date' => ['required' => 'Start Date is required.'],
				'location_address' => ['required' => 'Location Address is required.'],
				'venue_id' => ['required' => 'Venue is required.'],
			];
		
			// Image validation (only required if adding a new event)
			if (empty($_FILES['event_source_image']['name'])) {
				$rules['event_source_image'] = 'uploaded[event_source_image]|is_image[event_source_image]|max_size[event_source_image,2048]';
				$messages['event_source_image'] = [
					'uploaded' => 'Event Image is required.',
					'is_image' => 'Please upload a valid image file.',
					'max_size' => 'Image size must be under 2MB.'
				];
			}
		    $validation = \Config\Services::validation(); 
			// Set validation rules
			$validation->setRules($rules, $messages);
			if(!$this->validate($rules, $messages)){
			// if (!$validation->withRequest($this->request)->run()) {
			// dd($validation);
			// die;
				return redirect()->back()->withInput()->with('validation', $validation);
			}
		
			// File Upload Handling
			$param = [];
		
			// Upload Image
			$file = $this->request->getFile('event_source_image');
			if ($file && $file->isValid() && !$file->hasMoved()) {
				$newName = $file->getRandomName();
				$file->move('assets/front/img/eventimage', $newName);
				$param['event_source_image'] = $newName;
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


							$param['event_title']				= 	$this->request->getPost('event_title');
							$hour 								= 	$this->request->getPost('event_start_hour');
							$min								= 	$this->request->getPost('event_start_min');
							$event_start_M						= 	$this->request->getPost('event_start_M');
							$hour_end 							= 	$this->request->getPost('event_end_hour');
							$min_end							= 	$this->request->getPost('event_end_min');
							$event_end_M						= 	$this->request->getPost('event_end_M');
							$param['save_location_id']			= 	$this->request->getPost('save_location_id');
							$param['description']				= 	$this->request->getPost('description');
							$param['start_date']				= 	$week_start;
							$param['end_date']					= 	$week_start;
							$param['event_start_time']			= 	$hour . ':' . $min . ' ' . $event_start_M;
							$param['event_end_time']            = 	$hour_end . ':' . $min_end . ' ' . $event_end_M;


							$combined_date_and_time = $param['start_date'] . ' ' . $param['event_start_time'];
							$param['date'] = strtotime($combined_date_and_time);

							$param['event_types']				=   $this->request->getPost('event_types');
							$param['url']				        =   $this->request->getPost('url');
							$param['cover_url']				    =   $this->request->getPost('cover_url');
							$param['video']				        =   $this->request->getPost('video');
							$param['video2']				    =   $this->request->getPost('video2');
							$param['video3']				    =   $this->request->getPost('video3');
							$param['qr_code_link']			    =   $this->request->getPost('qr_code_link');
							$param['buy_now_link']			    =   $this->request->getPost('buy_now_link');
							$param['reserve_seat_link']			=   $this->request->getPost('reserve_seat_link');
							// $param['event_tags']			    =   $this->request->getPost('event_tags');
							$param['no_of_repeat']				= 	$this->request->getPost('no_of_repeat');
							$param['location_name']				= 	$this->request->getPost('location_name');
							$param['location_address']			= 	$this->request->getPost('location_address');
							$param['latitude']					= 	$this->request->getPost('latitude');
							$param['longitude']					= 	$this->request->getPost('longitude');
							$param['website']					= 	$this->request->getPost('website');
							$param['phone_number']				= 	$this->request->getPost('phone_number');
							$param['venue_id']					= 	$this->request->getPost('venue_id');
							//$param['jazz_types_id']				= 	$this->request->getPost('jazz_types_id');
							$param['artist_id']				    = 	$this->request->getPost('artist_id');
							$param['virtual_event_price']	    = 	$this->request->getPost('virtual_event_price');
							$param['virtual_event_link']	    = 	$this->request->getPost('virtual_event_link');
							$param['cover_charge']				= 	$this->request->getPost('cover_charge');
							$param['set_time']					= 	$this->request->getPost('set_time');
							$param['time_permission']			= 	$this->request->getPost('time_permission');
							$param['repeating_event']			= 	$this->request->getPost('repeating_event');
							$param['free_concert'] = $this->request->getPost('free_concert') ? 1 : 0;
							$param['frequecy']					= 	$this->request->getPost('frequecy');
							$param['event_source']				= 	$this->request->getPost('event_source');
							$param['event_source_id']			= 	$this->request->getPost('event_source_id');
							$param['event_source_image']		= 	$this->request->getPost('event_source_image');
							$param['ip_address']				=	currentIp();
							$param['created_by']				=	(int)$this->session->get('ILCADM_ADMIN_ID');
							$param['creation_date']				= 	date('Y-m-d h:i:s');
							$param['is_active']					=	isset($data['EDITDATA']['is_active']) ? $data['EDITDATA']['is_active'] : '0';
							$param['is_boosted']				=	isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
							$param['is_featured']				=	isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';
							$param['is_imported']				=	isset($data['EDITDATA']['is_imported']) ? $data['EDITDATA']['is_imported'] : '0';
							$alastInsertId						=	$this->common_model->addData('event_tbl', $param);

							$event_tags_input = $this->request->getPost('event_tags');

							if ($event_tags_input) {
								$event_tags_array = array_map('trim', explode(',', $event_tags_input));
								if (count($event_tags_array)) {
									foreach ($event_tags_array as $event_tag) {
										$p_array = [
											'event_id' => $alastInsertId,
											'event_tags' => $event_tag,
											'is_active' => '1'
										];

										$save_events = $this->common_model->addData('event_tags_tbl', $p_array);
									}
								}
							}
							$jazz_types_ids = $this->request->getPost('jazz_types_id'); // This should come from your form
							if (!empty($jazz_types_ids)) {
								// Assuming that 'jazz_types_id' is coming as an array from the form
								foreach ($jazz_types_ids as $jazz_type_id) {
									$jazz_data = [
										'event_id' => $alastInsertId,
										'event_jazz_types_id' => (int)$jazz_type_id, // Cast to integer
									];
									// Insert into event_jazz_tbl
									$this->common_model->addData('event_jazz_tbl', $jazz_data);
								}
							}
							// if($alastInsertId){
							// 	$elast_event_data = $this->elastic_model->getEventFromId($alastInsertId);
						
							// 	// exit;
							// 	if(!empty($elast_event_data)){
							// 		$this->elastichh_lib->addUpdateSingleEvent($elast_event_data);
							// 	}
							// }

							$this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));
						}
					} else {

						$param['event_title']				= 	$this->request->getPost('event_title');
						$hour 								= 	$this->request->getPost('event_start_hour');
						$min								= 	$this->request->getPost('event_start_min');
						$event_start_M						= 	$this->request->getPost('event_start_M');
						$hour_end 							= 	$this->request->getPost('event_end_hour');
						$min_end							= 	$this->request->getPost('event_end_min');
						$event_end_M						= 	$this->request->getPost('event_end_M');
						$param['save_location_id']			= 	$this->request->getPost('save_location_id');
						$param['description']				= 	$this->request->getPost('description');
						$param['start_date']				= 	$this->request->getPost('start_date');
						$param['end_date']					= 	$this->request->getPost('end_date');
						$param['event_start_time']			= 	$hour . ':' . $min . ' ' . $event_start_M;
						$param['event_end_time']            = 	$hour_end . ':' . $min_end . ' ' . $event_end_M;

						$combined_date_and_time = $param['start_date'] . ' ' . $param['event_start_time'];
						$param['date'] = strtotime($combined_date_and_time);

						$param['event_types']				=   $this->request->getPost('event_types');
						$param['url']				        =   $this->request->getPost('url');
						$param['cover_url']				    =   $this->request->getPost('cover_url');
						$param['video']				        =   $this->request->getPost('video');
						$param['video2']				    =   $this->request->getPost('video2');
						$param['video3']				    =   $this->request->getPost('video3');
						$param['qr_code_link']			    =   $this->request->getPost('qr_code_link');
						$param['buy_now_link']			    =   $this->request->getPost('buy_now_link');
						$param['reserve_seat_link']			=   $this->request->getPost('reserve_seat_link');
						// $param['event_tags']			    =   $this->request->getPost('event_tags');
						$param['no_of_repeat']				= 	$this->request->getPost('no_of_repeat');
						$param['location_name']				= 	$this->request->getPost('location_name');
						$param['location_address']			= 	$this->request->getPost('location_address');
						$param['latitude']					= 	$this->request->getPost('latitude');
						$param['longitude']					= 	$this->request->getPost('longitude');
						$param['website']					= 	$this->request->getPost('website');
						$param['phone_number']				= 	$this->request->getPost('phone_number');
						$param['venue_id']					= 	$this->request->getPost('venue_id');
						//$param['jazz_types_id']				= 	$this->request->getPost('jazz_types_id');
						$param['artist_id']				    = 	$this->request->getPost('artist_id');
						$param['virtual_event_price']	    = 	$this->request->getPost('virtual_event_price');
						$param['virtual_event_link']	    = 	$this->request->getPost('virtual_event_link');
						$param['cover_charge']				= 	$this->request->getPost('cover_charge');
						$param['set_time']					= 	$this->request->getPost('set_time');
						$param['time_permission']			= 	$this->request->getPost('time_permission');
						$param['repeating_event']			= 	$this->request->getPost('repeating_event');
						$param['free_concert'] = $this->request->getPost('free_concert') ? 1 : 0;
						$param['frequecy']					= 	$this->request->getPost('frequecy');
						$param['event_source']				= 	$this->request->getPost('event_source');
						$param['event_source_id']			= 	$this->request->getPost('event_source_id');
						$param['event_source_image']		= 	$this->request->getPost('event_source_image');
						$param['ip_address']				=	currentIp();
						$param['created_by']				=	(int)$this->session->get('ILCADM_ADMIN_ID');
						$param['creation_date']				= 	date('Y-m-d h:i:s');
						$param['is_active']					=	isset($data['EDITDATA']['is_active']) ? $data['EDITDATA']['is_active'] : '0';
						$param['is_boosted']				=	isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
						$param['is_featured ']				=	isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';
						$param['is_imported']				=	isset($data['EDITDATA']['is_imported']) ? $data['EDITDATA']['is_imported'] : '0';
						// echo"<pre>";print_r($param['event_start_time']);die;
						$checkEvent						    =	$this->common_model->checkEvent($param['save_location_id'], $param['start_date'], $param['end_date'], $param['event_start_time'], $param['event_end_time']);
						if ($checkEvent) {
							$this->session->setFlashdata('alert_error', 'Event with same location, date and time already exists');
						} else {
							$alastInsertId					=	$this->common_model->addData('event_tbl', $param);
						}

						$event_tags_input = $this->request->getPost('event_tags');

						if ($event_tags_input) {

							$event_tags_array = array_map('trim', explode(',', $event_tags_input));

							if (count($event_tags_array)) {
								foreach ($event_tags_array as $event_tag) {
									$p_array = [
										'event_id' => $alastInsertId,
										'event_tags' => $event_tag,
										'is_active' => '1'
									];
									// echo "<pre>";print_r($p_array);die;
									$save_events = $this->common_model->addData('event_tags_tbl', $p_array);
									// echo "<pre>";print_r($save_events);die;
								}
							}
						}
						$jazz_types_ids = $this->request->getPost('jazz_types_id'); // This should come from your form
						if (!empty($jazz_types_ids)) {
							// Assuming that 'jazz_types_id' is coming as an array from the form
							foreach ($jazz_types_ids as $jazz_type_id) {
								$jazz_data = [
									'event_id' => $alastInsertId,
									'event_jazz_types_id' => (int)$jazz_type_id, // Cast to integer
								];
								// Insert into event_jazz_tbl
								$this->common_model->addData('event_jazz_tbl', $jazz_data);
							}
						}
						// if($alastInsertId){
						// 	$elast_event_data = $this->elastic_model->getEventFromId($alastInsertId);
					
						// 	// exit;
						// 	if(!empty($elast_event_data)){
						// 		$this->elastichh_lib->addUpdateSingleEvent($elast_event_data);
						// 	}
						// }
						// echo "<pre>";print_r($_POST);die;
						$this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));
					}
				else :

					$this->common_model->deleteData('event_tbl', 'event_id', (int)$editId);
					$this->common_model->deleteData('event_jazz_tbl', 'event_id', (int)$editId);


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

							$param['event_title']				= 	$this->request->getPost('event_title');
							$hour 								= 	$this->request->getPost('event_start_hour');
							$min								= 	$this->request->getPost('event_start_min');
							$event_start_M						= 	$this->request->getPost('event_start_M');
							$hour_end 							= 	$this->request->getPost('event_end_hour');
							$min_end							= 	$this->request->getPost('event_end_min');
							$event_end_M						= 	$this->request->getPost('event_end_M');
							$param['save_location_id']			= 	$this->request->getPost('save_location_id');
							$param['description']				= 	$this->request->getPost('description');
							//$param['start_date']				= 	$week_start;
							//$param['end_date']					= 	$week_start;
							$param['event_start_time']			= 	$hour . ':' . $min . ' ' . $event_start_M;
							$param['event_end_time']            = 	$hour_end . ':' . $min_end . ' ' . $event_end_M;

							$combined_date_and_time = $param['start_date'] . ' ' . $param['event_start_time'];
							$param['date'] = strtotime($combined_date_and_time);

							$param['event_types']				=   $this->request->getPost('event_types');
							$param['url']				        =   $this->request->getPost('url');
							$param['cover_url']				    =   $this->request->getPost('cover_url');
							$param['video']				        =   $this->request->getPost('video');
							$param['video2']				    =   $this->request->getPost('video2');
							$param['video3']				    =   $this->request->getPost('video3');
							$param['qr_code_link']			    =   $this->request->getPost('qr_code_link');
							$param['buy_now_link']			    =   $this->request->getPost('buy_now_link');
							$param['reserve_seat_link']			=   $this->request->getPost('reserve_seat_link');
							// $param['event_tags']			    =   $this->request->getPost('event_tags');
							$param['no_of_repeat']				= 	$this->request->getPost('no_of_repeat');
							$param['location_name']				= 	$this->request->getPost('location_name');
							$param['location_address']			= 	$this->request->getPost('location_address');
							$param['latitude']					= 	$this->request->getPost('latitude');
							$param['longitude']					= 	$this->request->getPost('longitude');
							$param['website']					= 	$this->request->getPost('website');
							$param['phone_number']				= 	$this->request->getPost('phone_number');
							$param['venue_id']					= 	$this->request->getPost('venue_id');
							//$param['jazz_types_id']				= 	$this->request->getPost('jazz_types_id');
							$param['artist_id']				    = 	$this->request->getPost('artist_id');
							$param['virtual_event_price']	    = 	$this->request->getPost('virtual_event_price');
							$param['virtual_event_link']	    = 	$this->request->getPost('virtual_event_link');
							$param['cover_charge']				= 	$this->request->getPost('cover_charge');
							$param['set_time']					= 	$this->request->getPost('set_time');
							$param['time_permission']			= 	$this->request->getPost('time_permission');
							$param['repeating_event']			= 	$this->request->getPost('repeating_event');
							$param['free_concert'] = $this->request->getPost('free_concert') ? 1 : 0;
							$param['frequecy']					= 	$this->request->getPost('frequecy');
							$param['event_source']				= 	isset($data['EDITDATA']['event_source']) ? $data['EDITDATA']['event_source'] : '';
							$param['event_source_id']			= 	isset($data['EDITDATA']['event_source_id']) ? $data['EDITDATA']['event_source_id'] : '';
							$param['event_source_image']		= 	isset($data['EDITDATA']['event_source_image']) ? $data['EDITDATA']['event_source_image'] : '';
							$param['ip_address']				=	isset($data['EDITDATA']['ip_address']) ? $data['EDITDATA']['ip_address'] : '0';
							$param['created_by']				=	(int)$this->session->get('ILCADM_ADMIN_ID');
							$param['creation_date']				= 	isset($data['EDITDATA']['creation_date']) ? $data['EDITDATA']['creation_date'] : '0';
							$param['is_active']					=	isset($data['EDITDATA']['is_active']) ? $data['EDITDATA']['is_active'] : '0';
							$param['is_boosted']				=	isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
							$param['is_featured ']				=	isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';
							$param['is_imported']				=	isset($data['EDITDATA']['is_imported']) ? $data['EDITDATA']['is_imported'] : '0';
							$alastInsertId						=	$this->common_model->addData('event_tbl', $param);

							$param['virtual_event']	    = 	$this->request->getPost('virtual_event');
							$param['boost_days']	    = 	isset($data['EDITDATA']['boost_days']) ? $data['EDITDATA']['boost_days'] : '0';
							$param['boost_date']	    = 	isset($data['EDITDATA']['boost_date']) ? $data['EDITDATA']['boost_date'] : '0';
							$param['requested_boost']	    = 	isset($data['EDITDATA']['requested_boost']) ? $data['EDITDATA']['requested_boost'] : '0';
							$param['ticket_status_code']	    = 	isset($data['EDITDATA']['ticket_status_code']) ? $data['EDITDATA']['ticket_status_code'] : '0';

							$event_tags_input = $this->request->getPost('event_tags');

							if ($event_tags_input) {
								$event_tags_array = array_map('trim', explode(',', $event_tags_input));

								if (count($event_tags_array)) {
									foreach ($event_tags_array as $event_tag) {
										$p_array = [
											'event_id' => $alastInsertId,
											'event_tags' => $event_tag,
											'is_active' => '1'
										];

										$save_events = $this->common_model->addData('event_tags_tbl', $p_array);
									}
								}
							}
							$jazz_types_ids = $this->request->getPost('jazz_types_id'); // This should come from your form
							if (!empty($jazz_types_ids)) {
								// Assuming that 'jazz_types_id' is coming as an array from the form
								foreach ($jazz_types_ids as $jazz_type_id) {
									$jazz_data = [
										'event_id' => $alastInsertId,
										'event_jazz_types_id' => (int)$jazz_type_id, // Cast to integer
									];
									// Insert into event_jazz_tbl
									$this->common_model->addData('event_jazz_tbl', $jazz_data);
								}
							}
							// if($alastInsertId){
							// 	$elast_event_data = $this->elastic_model->getEventFromId($alastInsertId);
						
							// 	// exit;
							// 	if(!empty($elast_event_data)){
							// 		$this->elastichh_lib->addUpdateSingleEvent($elast_event_data);
							// 	}
							// }

							$this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));
						}
					} else {


						$param['event_title']				= 	$this->request->getPost('event_title');
						$hour 								= 	$this->request->getPost('event_start_hour');
						$min								= 	$this->request->getPost('event_start_min');
						$event_start_M						= 	$this->request->getPost('event_start_M');
						$hour_end 							= 	$this->request->getPost('event_end_hour');
						$min_end							= 	$this->request->getPost('event_end_min');
						$event_end_M						= 	$this->request->getPost('event_end_M');
						$param['save_location_id']			= 	$this->request->getPost('save_location_id');
						$param['description']				= 	$this->request->getPost('description');
						$param['start_date']				= 	$this->request->getPost('start_date');
						$param['end_date']					= 	$this->request->getPost('end_date');
						$param['event_start_time']			= 	$hour . ':' . $min . ' ' . $event_start_M;
						$param['event_end_time']            = 	$hour_end . ':' . $min_end . ' ' . $event_end_M;

						$combined_date_and_time = $param['start_date'] . ' ' . $param['event_start_time'];
						$param['date'] = strtotime($combined_date_and_time);

						$param['event_types']				=   $this->request->getPost('event_types');
						$param['url']				        =   $this->request->getPost('url');
						$param['cover_url']				    =   $this->request->getPost('cover_url');
						$param['video']				        =   $this->request->getPost('video');
						$param['video2']				    =   $this->request->getPost('video2');
						$param['video3']				    =   $this->request->getPost('video3');
						$param['qr_code_link']			    =   $this->request->getPost('qr_code_link');
						$param['buy_now_link']			    =   $this->request->getPost('buy_now_link');
						$param['reserve_seat_link']			=   $this->request->getPost('reserve_seat_link');
						// $param['event_tags']			    =   $this->request->getPost('event_tags');
						$param['no_of_repeat']				= 	$this->request->getPost('no_of_repeat');
						$param['location_name']				= 	$this->request->getPost('location_name');
						$param['location_address']			= 	$this->request->getPost('location_address');
						$param['latitude']					= 	$this->request->getPost('latitude');
						$param['longitude']					= 	$this->request->getPost('longitude');
						$param['website']					= 	$this->request->getPost('website');
						$param['phone_number']				= 	$this->request->getPost('phone_number');
						$param['venue_id']					= 	$this->request->getPost('venue_id');
						//$param['jazz_types_id']				= 	$this->request->getPost('jazz_types_id');
						$param['artist_id']				    = 	$this->request->getPost('artist_id');
						$param['virtual_event_price']	    = 	$this->request->getPost('virtual_event_price');
						$param['virtual_event_link']	    = 	$this->request->getPost('virtual_event_link');
						$param['cover_charge']				= 	$this->request->getPost('cover_charge');
						$param['set_time']					= 	$this->request->getPost('set_time');
						$param['time_permission']			= 	$this->request->getPost('time_permission');
						$param['repeating_event']			= 	$this->request->getPost('repeating_event');
						$param['free_concert'] = $this->request->getPost('free_concert') ? 1 : 0;
						$param['frequecy']					= 	$this->request->getPost('frequecy');
						$param['event_source']				= 	isset($data['EDITDATA']['event_source']) ? $data['EDITDATA']['event_source'] : '';
						$param['event_source_id']			= 	isset($data['EDITDATA']['event_source_id']) ? $data['EDITDATA']['event_source_id'] : '';
						$param['event_source_image']		= 	isset($data['EDITDATA']['event_source_image']) ? $data['EDITDATA']['event_source_image'] : '';
						$param['ip_address']				=	isset($data['EDITDATA']['ip_address']) ? $data['EDITDATA']['ip_address'] : '0';
						$param['created_by']				=	isset($data['EDITDATA']['created_by']) ? $data['EDITDATA']['created_by'] : '0';
						$param['creation_date']				= 	date('Y-m-d h:i:s');
						$param['is_active']					=	isset($data['EDITDATA']['is_active']) ? $data['EDITDATA']['is_active'] : '0';
						$param['is_boosted']				=	isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
						$param['is_featured ']				=	isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';
						$param['is_imported']				=	isset($data['EDITDATA']['is_imported']) ? $data['EDITDATA']['is_imported'] : '0';

						$param['virtual_event']	    = 	$this->request->getPost('virtual_event');
						$param['boost_days']	    = 	isset($data['EDITDATA']['boost_days']) ? $data['EDITDATA']['boost_days'] : '0';
						$param['boost_date']	    = 	isset($data['EDITDATA']['boost_date']) ? $data['EDITDATA']['boost_date'] : '0';
						$param['requested_boost']	    = 	isset($data['EDITDATA']['requested_boost']) ? $data['EDITDATA']['requested_boost'] : '0';
						$param['ticket_status_code']	    = 	isset($data['EDITDATA']['ticket_status_code']) ? $data['EDITDATA']['ticket_status_code'] : '0';

						$alastInsertId						=	$this->common_model->addData('event_tbl', $param);


						$event_tags_input = $this->request->getPost('event_tags');

						if ($event_tags_input) {
							$event_tags_array = array_map('trim', explode(',', $event_tags_input));

							if (count($event_tags_array)) {
								foreach ($event_tags_array as $event_tag) {
									$p_array = [
										'event_id' => $alastInsertId,
										'event_tags' => $event_tag,
										'is_active' => '1'
									];

									$save_events = $this->common_model->addData('event_tags_tbl', $p_array);
								}
							}
						}
						$jazz_types_ids = $this->request->getPost('jazz_types_id'); // This should come from your form
						if (!empty($jazz_types_ids)) {
							// Assuming that 'jazz_types_id' is coming as an array from the form
							foreach ($jazz_types_ids as $jazz_type_id) {
								$jazz_data = [
									'event_id' => $alastInsertId,
									'event_jazz_types_id' => (int)$jazz_type_id, // Cast to integer
								];
								// Insert into event_jazz_tbl
								$this->common_model->addData('event_jazz_tbl', $jazz_data);
							}
						}
						$this->common_model->deleteEventTags($editId);
						// if($alastInsertId){
						// 	$elast_event_data = $this->elastic_model->getEventFromId($alastInsertId);
					
						// 	// exit;
						// 	if(!empty($elast_event_data)){
						// 		$this->elastichh_lib->addUpdateSingleEvent($elast_event_data);
						// 	}
						// }
						$this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));
					}
					$this->session->setFlashdata('alert_success', lang('statictext_lang.updatesuccess'));
				endif;
				// echo"<pre>";print_r($_POST);die;
				return redirect()->to(getCurrentControllerPath('index'));
				// return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
			endif;
		
		$data['location'] = $this->common_model->getLocation(false);
		$data['venues'] = $this->common_model->getCategory(false);
		// echo "<pre>"; print_r($data['venues']); die();
		$data['jazzTypes'] = $this->common_model->getCategoryJazz(false);
		// echo "<pre>"; print_r($data['jazzTypes']); die();
		$data['artistTypes'] = $this->common_model->getCategoryArtist(false);
		// echo "<pre>"; print_r($data['artistTypes']); die();
		$this->layouts->set_title('Manage Api Import');
		$this->layouts->admin_view('api_data/add_edit_api_data', array(), $data);
	}	// END OF FUNCTION	



	function changestatus($changeStatusId = '', $statusType = '')
	{
		$changeStatusId = $this->uri->getSegment(4); 
            $statusType = $this->uri->getSegment(5);
		$this->admin_model->authCheck('edit_data');
		$param['is_active']		=	$statusType;

		$this->common_model->editData('event_tbl', $param, 'event_id', (int)$changeStatusId);

		if ($statusType == 1) {
			// also add it in elastic index 

			// if($changeStatusId){
			// 	$elast_event_data = $this->elastic_model->getEventFromId($changeStatusId);
		
			// 	// exit;
			// 	if(!empty($elast_event_data)){
			// 		$this->elastichh_lib->addUpdateSingleEvent($elast_event_data);
			// 	}
			// }
			$this->emailTemplateModel->sendEventActive($param);//commeting as id gets deleted and user gets old id
		}
		$this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}

	function updateimport($importedId = '', $statusType = '')
	{
		$this->admin_model->authCheck('edit_data');
		$param['is_imported']		=	$statusType;

		$this->common_model->editData('event_tbl', $param, 'event_id', (int)$importedId);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.importsuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}
	
	function multipleChangeStatus()
		{
			
			$changeStatusIds = json_decode($_POST['selectedData'], true);
			$statusType = $_POST['statusType'];
			$all_ven =	$this->common_model->venue();
			
			if ($statusType == "saveLoc") {
				foreach ($changeStatusIds as $changeStatusId) {
					$venue_id=null;
					$db_loc_id=null;
					if (!empty($changeStatusId)) {
						$tbl            =    'ticketmaster_event_tbl as ftable';
						$arrDataReturn = $this->common_model->getCheckedLocData($tbl, $changeStatusId['event_id']);
						
						if (!empty($arrDataReturn)) {
						
							foreach ($arrDataReturn as $arrEventData) {
								
								// get a single object of an event from the json
								$arrCompleteSingleEventData = json_decode($arrEventData['all_data'], true);$arrCompleteSingleEventData = json_decode($arrEventData['all_data'], true);
								$varLocationId = 0;
								$varJazzTypeId = 0;
								$varArtistId = 0;
								$varIsActive = 0;
	
								if ($changeStatusId['match_sel'] == 'DB Checked') {
									// Agar accept kari to new event banega with the db wali location
									$db_loc_id=$changeStatusId['match_id'];
									$tbl  ='event_location_tbl as ftable';
									$where['where'] = ['id' => $db_loc_id];
									$DbVal = $this->common_model->getData('single', $tbl, $where);
									$param['latitude']  = $DbVal['latitude'];
									$param['longitude'] = $DbVal['longitude'];
									$param['location_name']  =  $DbVal['location_name'];
									$param['location_address'] =  $DbVal['location_address'];
									$venue_id=$DbVal['venue_id'];
									$varLocationId = $db_loc_id;
									if(!empty($arrCompleteSingleEventData['_embedded']['venues'])) {
									
										$api_loc['latitude']  =  !empty($arrCompleteSingleEventData['_embedded']['venues'][0]['location']['latitude']) ? $arrCompleteSingleEventData['_embedded']['venues'][0]['location']['latitude'] : 0;
										$api_loc['longitude'] =     !empty($arrCompleteSingleEventData['_embedded']['venues'][0]['location']['longitude']) ? $arrCompleteSingleEventData['_embedded']['venues'][0]['location']['longitude'] : 0;
										$api_loc['location_name']  =     $arrCompleteSingleEventData['_embedded']['venues'][0]['name'];
										$api_loc['location_address'] =     !empty($arrCompleteSingleEventData['_embedded']['venues'][0]['address']['line1']) ? $arrCompleteSingleEventData['_embedded']['venues'][0]['address']['line1'] : "";
									}
									$api_loc['venue_id'] = $this->venusBasedOnStates[$arrCompleteSingleEventData['_embedded']['venues'][0]['state']['stateCode']];
									$api_loc['event_loc_id'] = $db_loc_id;
									$isLocInserted = $this->common_model->addMatchLocData('matching_loc_data',$api_loc);
								}
								else
								{
									if ($changeStatusId['match_sel'] == 'Venue Selected') {
										// location to api wali hogi but location ka venue location wale table mein jo choose kia vo jaega
										
										$venue_id = $changeStatusId['match_id'];
										$varLocationId = $this->addEventLocations($arrCompleteSingleEventData['_embedded']['venues'][0],$venue_id);
									}
									else
									{
										$varLocationId = $this->addEventLocations($arrCompleteSingleEventData['_embedded']['venues'][0],$venue_id);
										$venue_id =  $this->venusBasedOnStates[$arrCompleteSingleEventData['_embedded']['venues'][0]['state']['stateCode']];
									}
									if(!empty($arrCompleteSingleEventData['_embedded']['venues'])) {
									
										$param['latitude']  =  !empty($arrCompleteSingleEventData['_embedded']['venues'][0]['location']['latitude']) ? $arrCompleteSingleEventData['_embedded']['venues'][0]['location']['latitude'] : 0;
										$param['longitude'] =     !empty($arrCompleteSingleEventData['_embedded']['venues'][0]['location']['longitude']) ? $arrCompleteSingleEventData['_embedded']['venues'][0]['location']['longitude'] : 0;
										$param['location_name']  =     $arrCompleteSingleEventData['_embedded']['venues'][0]['name'];
										$param['location_address'] =     !empty($arrCompleteSingleEventData['_embedded']['venues'][0]['address']['line1']) ? $arrCompleteSingleEventData['_embedded']['venues'][0]['address']['line1'] : "";
									}
									
								}

								// if (in_array($search_title, array_column($all_ven, 'venue_title'))) {
								// 	echo "Venue exists in the array.";
								// } 
								if (!empty($arrCompleteSingleEventData['_embedded']['attractions'])) {
									// insert into artists if that artist is not there
									
									// $obj = new Ticketmaster();
									$varArtistId =  $this->addArtists($arrCompleteSingleEventData['_embedded']['attractions'][0]);
								}
								if (!empty($arrCompleteSingleEventData['classifications'][0]['subGenre'])) {
									// insert into jaz types if that jaz type is not there
									// $obj = new Ticketmaster();
									$varJazzTypeId = $this->addJazzType($arrCompleteSingleEventData['classifications'][0]['subGenre']);
								}
								
								$param['event_title']  = $arrCompleteSingleEventData['name'];
								$param['event_source'] = "ticketmaster";
								$param['event_source_id'] = $arrCompleteSingleEventData['id'];
			
								$param['start_date'] = !empty($arrCompleteSingleEventData['dates']['start']['localDate']) ? $arrCompleteSingleEventData['dates']['start']['localDate'] : "";
								$param['end_date']     = !empty($arrCompleteSingleEventData['dates']['end']['localDate']) ? $arrCompleteSingleEventData['dates']['end']['localDate'] : $param['start_date'];
			
								$param['event_start_time']    =     !empty($arrCompleteSingleEventData['dates']['start']['localTime']) ? $arrCompleteSingleEventData['dates']['start']['localTime'] : "";
								$param['event_end_time']    =     !empty($arrCompleteSingleEventData['dates']['end']['localTime']) ? date("H:i", strtotime($arrCompleteSingleEventData['dates']['end']['localTime'])) : "";
								//echo $param['event_end_time']    =     !empty($arrCompleteSingleEventData['dates']['start']['localTime']) ?$arrCompleteSingleEventData['dates']['end']['localTime'] : "";
								
								if (!empty($arrCompleteSingleEventData['dates']['status']['code'])) {
									$param['ticket_status_code'] = $arrCompleteSingleEventData['dates']['status']['code'];
								}
								$param['event_start_time'] = date("H:i", strtotime($param['event_start_time']));
								$combined_date_and_time = $param['start_date'] . ' ' . $param['event_start_time'];
								$param['date'] = strtotime($combined_date_and_time);
								$param['event_source_image'] = !empty($arrCompleteSingleEventData['images'][0]['url']) ? $arrCompleteSingleEventData['images'][0]['url'] : "";
								$param['cover_url'] = $param['event_source_image'];
								$param['repeating_event']        =     "No";
								$param['save_location_id']        =     $varLocationId;
								$param['website']                =     $arrCompleteSingleEventData['url'];
								$param['phone_number']            =     "";
							
								$param['venue_id']                =     $venue_id;
								//$param['cover_charge']            =     "";
								$param['added_by']                =     'Scraping_bot';
								$param['jazz_types_id']            =      $varJazzTypeId;
								$param['artist_id']                =     $varArtistId;
								
								$varDescription = "";
								if (!empty($arrCompleteSingleEventData['info'])) {
									$varDescription = $arrCompleteSingleEventData['info'];
								}
								if (!empty($arrCompleteSingleEventData['info'])) {
									$varDescription = $varDescription . "<br/>" . $arrCompleteSingleEventData['pleaseNote'];
								}
								$param['description'] =  $varDescription;
								$param['requested_boost']       =  0;
								$param['creation_date']            =     date('Y-m-d h:i:s');
								$param['is_active']                =    '0';
								$param['created_by'] =  'scraping_api';
								$param['is_front']   =  '0';
								// price ranges
								$varprice = "";
								if (!empty($arrCompleteSingleEventData['priceRanges'][0])) {
									$varMin = !empty($arrCompleteSingleEventData['priceRanges'][0]['min']) ? $arrCompleteSingleEventData['priceRanges'][0]['min'] : "";
									$varMax = !empty($arrCompleteSingleEventData['priceRanges'][0]['max']) ? $arrCompleteSingleEventData['priceRanges'][0]['max'] : "";
									if (!empty($varMin))
										$varprice =   ($varMin == $varMax) ? "$" . $varMin : "$" . $varMin . " - $" . $varMax;
								} else {
									$param['cover_charge'] = "Free";
								}
								$param['cover_charge'] = $varprice;
								// check if that event is already in our system or not
								$whereEvent['where'] = [
									'event_title' => $param['event_title'],
									'start_date' => $param['start_date'],
									'location_address'=>$param['location_address'],
									'event_start_time' => $param['event_start_time'] 
								];
								$tblE            =    'event_tbl as ftable';
								// $arrIsEventExistData = $this->common_model->getData('single', $tblE, $whereEvent);
			
								// if (empty($arrIsEventExistData)) {
									// inserted into event table if that is not existed
								// 	echo"<pre>";
								// 	print_r($param);
								// die;
									$isInserted = $this->common_model->addData('event_tbl', $param);
									echo" inserted = ".$isInserted;
									
									if($isInserted)
									{
										$update_imported['is_imported_event_tbl'] =1;
										$update_imported['imported_date_time']=date('Y-m-d H:i:s');
										$this->common_model->editData('ticketmaster_event_tbl', $update_imported, 'event_id', $arrEventData['event_id']);
									}
								// } 
								// else {
								// 	$paramForEditEvent = $param;
								// 	$paramForEditEvent['is_active'] = $arrIsEventExistData['is_active'];
								// 	$paramForEditEvent['creation_date'] = $arrIsEventExistData['creation_date'];
								// 	$paramForEditEvent['is_front'] = $arrIsEventExistData['is_front'];
								// 	$paramForEditEvent['requested_boost'] = $arrIsEventExistData['requested_boost'];
								// 	// update the data 
								// 	$whereT = "event_id={$arrIsEventExistData['event_id']}";
								// 	$this->common_model->updateData('event_tbl', $paramForEditEvent, $whereT);
								// 	echo "already existed" . "<br/>";
								// }
								
							}
						}
					}
					$this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));
				}
			}
			// else
			// {
				// $this->session->setFlashdata('alert_success', lang('statictext_lang.deletesuccess'));
			// }
			// return $this->returnData = [
			// 	'message' => 'test msg Successfully!.'
			// ];
			// return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
		}

	function statusboost($changeStatusId = '', $statusType = '')
	{
		$changeStatusId = $this->uri->getSegment(4); 
		$statusType = $this->uri->getSegment(5);
		$this->admin_model->authCheck('edit_data');
		$param['is_boosted']		=	$statusType;

		$this->common_model->editData('event_tbl', $param, 'event_id', (int)$changeStatusId);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}

	function statusfeatured($changeStatusId = '', $statusType = '')
	{
		$changeStatusId = $this->uri->getSegment(4); 
            $statusType = $this->uri->getSegment(5);
		$this->admin_model->authCheck('edit_data');
		$param['is_featured']		=	$statusType;

		$this->common_model->editData('event_tbl', $param, 'event_id', (int)$changeStatusId);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}

	function statusimported($changeStatusId = '', $statusType = '')
	{
		$changeStatusId = $this->uri->getSegment(4); 
            $statusType = $this->uri->getSegment(5);
		$this->admin_model->authCheck('edit_data');
		$param['is_imported']		=	$statusType;

		$this->common_model->editData('event_tbl', $param, 'event_id', (int)$changeStatusId);
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

		$this->common_model->deleteData('event_tbl', 'event_id', (int)$deleteId);
		$this->common_model->deleteData('event_tags_tbl', 'event_id', (int)$deleteId);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.deletesuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}

	function location()
	{
		$db = \Config\Database::connect();
		$location_id = $this->request->getGet('LocationId');
		$query = $db->table('event_location_tbl')->where('id', $location_id)->get();
		$dataQuery = $query->getRow();
		// echo json_encode(array('location_name' => $dataQuery->location_name, 'location_address' => $dataQuery->location_address, 'latitude' => $dataQuery->latitude, 'longitude' => $dataQuery->longitude, 'website' => $dataQuery->website, 'phone_number' => $dataQuery->phone_number, 'venue_id' => $dataQuery->venue_id, 'jazz_types_id' => $dataQuery->jazz_types_id, 'artist_id' => $dataQuery->artist_id));
		if (!$dataQuery) {
			return $this->response->setJSON(['error' => 'Location not found']);
		}
	
		// Return data as JSON response
		return $this->response->setJSON([
			'location_name'   => $dataQuery->location_name,
			'location_address'=> $dataQuery->location_address,
			'latitude'        => $dataQuery->latitude,
			'longitude'       => $dataQuery->longitude,
			'website'         => $dataQuery->website,
			'phone_number'    => $dataQuery->phone_number,
			'venue_id'        => $dataQuery->venue_id,
			'jazz_types_id'   => $dataQuery->jazz_types_id,
			'artist_id'       => $dataQuery->artist_id
		]);
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

		$whereCon = ["event_id => $id "];
		$this->common_model->editMultipleDataByMultipleCondition('event_tbl', $param, $whereCon);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.deletesuccess'));

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
		$id = $this->uri->getSegment(4);
		$uri = service('uri');
		$id = $uri->getSegment(4);
		$db=\Config\Database::connect();
		$builder = $db->table('event_tbl');
        $builder->where('event_id', $id);
		$query = $builder->get();
		$data =$query->getRow();
		// $data = $this->db->select('*')->from('event_tbl')->where('event_id', $id)->get()->row();

		$param['event_title']				    = 	$data->event_title;
		$param['save_location_id']				= 	$data->save_location_id;
		$param['description']					= 	$data->description;
		$param['start_date']					= 	$data->start_date;
		$param['end_date']						= 	$data->end_date;
		$param['no_of_repeat']					= 	$data->no_of_repeat;
		$param['no_of_copy']					= 	$data->no_of_copy + 1;
		$param['location_name']					= 	$data->location_name;
		$param['location_address']				= 	$data->location_address;
		$param['latitude']						= 	$data->latitude;
		$param['longitude']						= 	$data->longitude;
		$param['time_permission']				= 	$data->time_permission;
		$param['website']						= 	$data->website;
		$param['phone_number']					= 	$data->phone_number;
		$param['venue_id']						= 	$data->venue_id;
		$param['jazz_types_id']					= 	$data->jazz_types_id;
		$param['cover_charge']					= 	$data->cover_charge;
		$param['url']				            =   $data->url;
		$param['cover_url']				        =   $data->cover_url;
		$param['video']				            =   $data->video;
		$param['video2']				        =   $data->video2;
		$param['video3']				        =   $data->video3;
		$param['qr_code_link']				    =   $data->qr_code_link;
		$param['buy_now_link']			        =   $data->buy_now_link;
		$param['reserve_seat_link']			    =   $data->reserve_seat_link;
		// $param['event_tags']			        =   $data->event_tags;
		$param['event_types']                   =    $data->event_types;
		$param['set_time']						= 	$data->set_time;
		$param['event_start_time']				=   $data->event_start_time;
		$param['event_end_time']				= 	$data->event_end_time;
		$param['repeating_event']				= 	$data->repeating_event;
		$param['frequecy']						= 	$data->frequecy;
		$param['event_source']					= 	$data->event_source;
		$param['event_source_id']				= 	$data->event_source_id;
		$param['event_source_image']			= 	$data->event_source_image;
		$param['ip_address']					=	currentIp();
		$param['created_by']					=	(int)$this->session->get('ILCADM_ADMIN_ID');
		$param['creation_date']					= 	date('Y-m-d h:i:s');
		$param['is_active']					    =	isset($data->is_active) ? $data->is_active : '0';
		$param['is_boosted']				    =	isset($data->is_boosted) ? $data->is_boosted : '0';
		$param['is_featured ']				    =	isset($data->is_featured) ? $data->is_featured : '0';
		$param['is_imported']				    =	isset($data->is_imported) ? $data->is_imported : '0';
		// echo"here";die;
		$this->common_model->addData('event_tbl', $param);

		$this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}

	public function boostDays()
	{
		$boost_days = $this->request->getPost('boost_days');
		$boost_date = $this->request->getPost('boost_date');
		$event_id = $this->request->getPost('event_id');
		$is_boosted = $this->request->getPost('is_boosted');

		// Check if boost_days and boost_date are provided
		// if (!empty($boost_days) && !empty($boost_date)) {
		$param['boost_days'] = $boost_days;
		$param['boost_date'] = $boost_date;
		$param['is_boosted'] = $is_boosted;

		if (empty($event_id)) {
			$lastInsertId = $this->common_model->addData('event_tbl', $param);

			if ($lastInsertId) {
				echo json_encode(array('event_id' => $lastInsertId, 'days' => $boost_days, 'date' => $boost_date));
			} else {
				echo json_encode(array());
			}
		} else {
			$updateStatus = $this->common_model->editData('event_tbl', $param, 'event_id', $event_id);

			if ($updateStatus) {
				echo json_encode(array('event_id' => $event_id, 'days' => $boost_days, 'date' => $boost_date));
			} else {
				echo json_encode(array());
			}
		}
		// } else {
		// echo json_encode(array('message' => 'No data to save'));
		// }
	}

	public function import()
	{
		require_once APPPATH . 'third_party/classes/PHPExcel.php';

		$data['error'] = '';
		$data['activeMenu'] = 'eventmanagement';
		$data['activeSubMenu'] = 'eventmanagement';

		if ($editId) :
			$this->admin_model->authCheck('edit_data');
			$data['EDITDATA'] = $this->common_model->getDataByParticularField('import_tbl', 'id', (int)$editId);

		else :
			$this->admin_model->authCheck('add_data');
		endif;

		if ($this->request->getPost('SaveChanges')) :
			$error = 'NO';

			$this->form_validation->set_rules('import_file', 'Import File', 'required|max_length[256]');

			if ($this->form_validation->run() && $error == 'NO') :

				$config['upload_path']          = './assets/admin/document/';
				$config['allowed_types']        = 'xls|xlsx|csv';
				$config['overwrite']            = TRUE;
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('import_file')) {
					$error = array('error' => $this->upload->display_errors());
					$this->session->setFlashdata('alert_error', $error['error']);
					redirect(correctLink('userILCADMData', getCurrentControllerPath('api_data/import')));
				} else {
					$data = array('upload_data' => $this->upload->data());
					$file_path = './assets/admin/document/' . $data['upload_data']['file_name'];
					$objPHPExcel = PHPExcel_IOFactory::load($file_path);
					$sheet = $objPHPExcel->getActiveSheet();
					$highestRow = $sheet->getHighestRow();

					// Insert filename into import table
					$import_data = array(
						'import_file' => $data['upload_data']['file_name'],
						'is_active' => "1"
					);
					$this->common_model->addData('import_tbl', $import_data);

					for ($row = 2; $row <= $highestRow; $row++) {

						$start_date_value = $sheet->getCellByColumnAndRow(7, $row)->getValue();
						$end_date_value = $sheet->getCellByColumnAndRow(8, $row)->getValue();

						$start_date = $this->parseDate($start_date_value);
						$end_date = $this->parseDate($end_date_value);

						$event_start_time_excel = $sheet->getCellByColumnAndRow(9, $row)->getValue();
						$event_start_time = PHPExcel_Style_NumberFormat::toFormattedString($event_start_time_excel, 'hh:mm:ss');

						$event_end_time_excel = $sheet->getCellByColumnAndRow(10, $row)->getValue();
						$event_end_time = PHPExcel_Style_NumberFormat::toFormattedString($event_end_time_excel, 'hh:mm:ss');

						$is_active_excel = $sheet->getCellByColumnAndRow(15, $row)->getValue();
						$is_active = "1";

						$virtual_event_cell = $sheet->getCellByColumnAndRow(26, $row);
						$virtual_event_price_cell = $sheet->getCellByColumnAndRow(27, $row);
						$virtual_event_link_cell = $sheet->getCellByColumnAndRow(28, $row);


						$event_data = array(
							'event_title'  => $sheet->getCellByColumnAndRow(0, $row)->getValue(),
							'description'  => $sheet->getCellByColumnAndRow(1, $row)->getValue(),
							'location_name' => $sheet->getCellByColumnAndRow(2, $row)->getValue(),
							'location_address' => $sheet->getCellByColumnAndRow(3, $row)->getValue(),
							'latitude' => $sheet->getCellByColumnAndRow(4, $row)->getValue(),
							'longitude' => $sheet->getCellByColumnAndRow(5, $row)->getValue(),
							'venue_id' => $sheet->getCellByColumnAndRow(6, $row)->getValue(),
							'start_date' => $start_date,
							'end_date' =>  $end_date,
							'event_start_time' => $event_start_time,
							'event_end_time' => $event_end_time,
							'time_permission' => $sheet->getCellByColumnAndRow(11, $row)->getValue(),
							'repeating_event' => $sheet->getCellByColumnAndRow(12, $row)->getValue(),
							'website' => $sheet->getCellByColumnAndRow(13, $row)->getValue(),
							'phone_number' => $sheet->getCellByColumnAndRow(14, $row)->getValue(),
							'is_active' => $is_active,
							'set_time' => $sheet->getCellByColumnAndRow(16, $row)->getValue(),
							'cover_charge' => $sheet->getCellByColumnAndRow(17, $row)->getValue(),
							'buy_now_link' => $sheet->getCellByColumnAndRow(18, $row)->getValue(),
							'reserve_seat_link' => $sheet->getCellByColumnAndRow(19, $row)->getValue(),
							'event_tags' => $sheet->getCellByColumnAndRow(20, $row)->getValue(),
							'jazz_types_id' => $sheet->getCellByColumnAndRow(21, $row)->getValue(),
							'video' => $sheet->getCellByColumnAndRow(22, $row)->getValue(),
							'video2' => $sheet->getCellByColumnAndRow(23, $row)->getValue(),
							'video3' => $sheet->getCellByColumnAndRow(24, $row)->getValue(),
							'artist_id' => $sheet->getCellByColumnAndRow(25, $row)->getValue(),
							'virtual_event' => $virtual_event_cell !== null ? $virtual_event_cell->getValue() : null,
							'virtual_event_price' => $virtual_event_price_cell !== null ? $virtual_event_price_cell->getValue() : null,
							'virtual_event_link' => $virtual_event_link_cell !== null ? $virtual_event_link_cell->getValue() : null,

						);

						$save_location_id = $event_data['location_name'];
						$location_data = $this->common_model->getDataByParticularField('event_location_tbl', 'location_name', $save_location_id);

						if ($location_data) {
							$event_data['save_location_id'] = $location_data['id'];
						} else {
							$event_data['save_location_id'] = '';
						}

						$venue_id = $event_data['venue_id'];
						$venue_data = $this->common_model->getDataByParticularField('venue_tbl', 'venue_title', $venue_id);

						if ($venue_data) {
							$event_data['venue_id'] = $venue_data['id'];
						} else {
							$event_data['venue_id'] = '';
						}
						unset($event_data['venue_title']);

						$jazz_types_id = $event_data['jazz_types_id'];
						$jazz_type_data = $this->common_model->getDataByParticularField('jazz_types', 'name', $jazz_types_id);

						if ($jazz_type_data) {
							$event_data['jazz_types_id'] = $jazz_type_data['id'];
						} else {
							$event_data['jazz_types_id'] = '';
						}


						$artist_id = $event_data['artist_id'];
						$artist_data = $this->common_model->getDataByParticularField('artist_tbl', 'artist_name', $artist_id);

						if ($artist_data) {
							$event_data['artist_id'] = $artist_data['id'];
						} else {
							$event_data['artist_id'] = '';
						}

						$this->common_model->addData('event_tbl', $event_data);
					}

					$this->session->setFlashdata('alert_success', 'Events imported successfully.');

					redirect(correctLink('userILCADMData', getCurrentControllerPath('api_data/import')));
				}

			endif;
		endif;

		$this->layouts->set_title('Manage Import');
		$this->layouts->admin_view('api_data/import');
	}

	function parseDate($date_value)
	{
		$date_value = str_replace('/', '-', $date_value);
		$date_parts = explode('-', $date_value);

		if (count($date_parts) === 3) {
			$month = $date_parts[0];
			$day = $date_parts[1];
			$year = $date_parts[2];

			if (strlen($year) === 2) {
				$year = ($year >= 70) ? '19' . $year : '20' . $year;
			}

			$formatted_date = sprintf('%02d-%02d-%02d', $year, $month, $day);

			return $formatted_date;
		} else {
			return null;
		}
	}

	public function venusBasedOnStates()
	{
		
	}

	public function location_mapping()
	{
		
		$response = $this->elastichh_lib->index_add_mapping_locations();
		print_r($response);
		
	}

	public function syncLocationsToElastic()
	{
		$db = \Config\Database::connect();
		$query = $db->table('event_location_tbl')->get();
		$locations = $query->getResultArray(); // CI4 method
		// if (empty($locations)) {
		// 	echo "No data found in location_tbl";
		// 	return;
		// }
		if (empty($locations)) {
			return $this->response->setJSON(['message' => 'No data found in event_location_tbl']);
		}

		$response = $this->elastichh_lib->bulkIndexLocations($locations);
	
		// echo "Elasticsearch sync completed!";
		// print_r($response);
		// die;

		return $this->response->setJSON([
			'message' => 'Elasticsearch sync completed!',
			'response' => $response
		]);

	}

	public function showElasticLocations()
	{
		$data['locations'] = $this->elastichh_lib->getAllLocationsFromElastic();
		echo"<pre>";
		print_r($data);
		// die;
		// $this->load->view('location_view', $data);
	}

	public function searchElasticLocations($input)
	{
	
		$results = $this->elastichh_lib->searchLocation($input);
		return $results;
		// echo"<pre>";
		// print_r($results);
	}

	 // add the locations if not existed
	 private function addEventLocations($arrVal, $venue_id=null)
	 {
		$tbl  ='event_location_tbl as ftable';
 		if($venue_id)
			{
				$ven_id = $venue_id;
			}
		else{
				$ven_id = $this->venusBasedOnStates[$arrVal['state']['stateCode']];
			}
			
		if (!empty($arrVal)) {
				$arrDataToinsert = [
					'location_source_id' => $arrVal['id'],
					'location_source' => "ticketmaster",
					'location_name' => $arrVal['name'],
					'description' => "",
					'location_address' => !empty($arrVal['address']['line1']) ? $arrVal['address']['line1'] : "",
					'short_description' => '',
					'latitude' => !empty($arrVal['location']['latitude']) ? $arrVal['location']['latitude'] : 0,
					'longitude' => !empty($arrVal['location']['longitude']) ? $arrVal['location']['longitude'] : 0,
					'zipcode' => !empty($arrVal['postalCode']) ? $arrVal['postalCode'] : '',
					'phone_number' => '',
					'website' => !empty($arrVal['PA']) ? $arrVal['PA'] : "",
					'location_type' => 2,
					'venue_id' => $ven_id,
					'ip_address' => "",
					'location_type' => 2,
					'created_by' => 1,
					'creation_date' => date('Y-m-d H:i:s'),
					'is_active' => 1
				]; 
		} 
	
		$varId = $this->common_model->addData('event_location_tbl', $arrDataToinsert);
		 return $varId;
	 }
 // add the locations if not existed
 private function addEventLocationsCopy($arrVal, $venue_id=null,$db_loc_id=null)
 {
	print_r(" venue_id =".$venue_id);
	print_r("db_loc_id =".$db_loc_id);
	
	$tbl  ='event_location_tbl as ftable';
	 if($db_loc_id)
	 {
		$where['where'] = ['id' => $db_loc_id];
		$DbVal = $this->common_model->getData('single', $tbl, $where);
		$arrDataToinsert = [
			'location_source_id' => $DbVal['location_source_id'],
			'location_source' => $DbVal['location_source'],
			'location_name' => $DbVal['location_name'],
			'description' => $DbVal['description'],
			'location_address' => $DbVal['location_address'],
			'short_description' => $DbVal['short_description'],
			'latitude' => $DbVal['latitude'],
			'longitude' => $DbVal['longitude'],
			'zipcode' => $DbVal['zipcode'],
			'phone_number' => $DbVal['phone_number'],
			'website' => $DbVal['website'],
			'location_type' => $DbVal['location_type'],
			'venue_id' =>$DbVal['venue_id'],
			'ip_address' =>$DbVal['ip_address'],
			'location_type' => $DbVal['location_type'],
			'created_by' => $DbVal['created_by'],
			'creation_date' => $DbVal['creation_date'],
			'is_active' => $DbVal['is_active']
		]; 
	 }
	 else 
	 {
		if($venue_id)
		{
			$ven_id = $venue_id;
		}
		else{
			$ven_id = $this->venusBasedOnStates[$arrVal['state']['stateCode']];
		 }
		
		if (empty($arrVal)) {
			$arrDataToinsert = [
				'location_source_id' => $arrVal['id'],
				'location_source' => "ticketmaster",
				'location_name' => $arrVal['name'],
				'description' => "",
				'location_address' => !empty($arrVal['address']['line1']) ? $arrVal['address']['line1'] : "",
				'short_description' => '',
				'latitude' => !empty($arrVal['location']['latitude']) ? $arrVal['location']['latitude'] : 0,
				'longitude' => !empty($arrVal['location']['longitude']) ? $arrVal['location']['longitude'] : 0,
				'zipcode' => !empty($arrVal['postalCode']) ? $arrVal['postalCode'] : '',
				'phone_number' => '',
				'website' => !empty($arrVal['PA']) ? $arrVal['PA'] : "",
				'location_type' => 2,
				'venue_id' => $ven_id,
				'ip_address' => "",
				'location_type' => 2,
				'created_by' => 1,
				'creation_date' => date('Y-m-d H:i:s'),
				'is_active' => 1
			]; 
		} 
	}
	$varId = $this->common_model->addData('event_location_tbl', $arrDataToinsert);
	 return $varId;
 }
 //add the artists if not exist with us 
 private function addArtists($arrVal)
 {
	 $where['where'] = ['LOWER(artist_name)' => strtolower($arrVal['name'])];
	 $tbl  =    'artist_tbl as ftable';
	 $arrDataReturn = $this->common_model->getData('single', $tbl, $where);
	 $varId = 0;
	 if (empty($arrDataReturn)) {
		 $arrDataToinsert = [
			 'artist_source_id' => $arrVal['id'],
			 'artist_source' => "ticketmaster",
			 'artist_name' => $arrVal['name'],
			 'artist_source_image' => !empty($arrVal['images'][0]['url']) ? $arrVal['images'][0]['url'] : "",
			 'artist_url' => $arrVal['url'],
			 'cover_url' => !empty($arrVal['images'][0]['url']) ? $arrVal['images'][0]['url'] : "",
			 'creation_date' => date('Y-m-d H:i:s'),
			 'is_active' => 1
		 ];
		 if (!empty($arrVal['name'])) {
			 $varId = $this->common_model->addData('artist_tbl', $arrDataToinsert);
		 }
	 } else {
		 $varId = $arrDataReturn['id'];
	 }
	 return $varId;
 }

     // add jazz type if not existed with us
	 private function addJazzType($arrVal)
	 {
		 $where['where'] = ['LOWER(name)' => strtolower($arrVal['name'])];
		 $tbl  =    'jazz_types as ftable';
		 $arrDataReturn = $this->common_model->getData('single', $tbl, $where);
		 if (empty($arrDataReturn)) {
			 $arrDataToinsert = [
				 'jazz_source_id' => $arrVal['id'],
				 'jazz_source' => "ticketmaster",
				 'name' => $arrVal['name'],
				 'is_active' => 1
			 ];
			 $varId = $this->common_model->addData('jazz_types', $arrDataToinsert);
		 } else {
			 $varId = $arrDataReturn['id'];
		 }
		 return $varId;
	 }

	 
}
