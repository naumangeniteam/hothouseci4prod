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
use DateTime;
use Exception;

class Adminmanagelivestream extends BaseController
{
	protected $admin_model;
	protected $emailTemplateModel;
	protected $smsModel;
	protected $notificationModel;
	protected $common_model;
	protected $session;
	protected $layouts;
	protected $lang;
	protected $uri;
	public function  __construct()
	{
		$this->admin_model = new AdminModel();
		$this->emailTemplateModel = new EmailtemplateModel();
		$this->smsModel = new SmsModel();
		$this->notificationModel = new NotificationModel();
		$this->common_model = new CommonModel();
		error_reporting(0);
		// $this->load->model(array('admin_model', 'emailtemplate_model', 'sms_model', 'notification_model', 'elastic_model'));
		$this->lang = service('language');
		$this->lang->setLocale('admin');
		$this->layouts = new Layouts();
		$this->session = session();
		helper(['common', 'general']);
		// $this->load->library('elastichh');
		$this->uri = service('uri');
	}

	/* * *********************************************************************
	 * * Function name 	: index
	 * * Developed By 	: Megha Kumari
	 * * Purpose  		: This function used for IL Talks
	 * * Date 			: 27 JUNE 2022
	 * * **********************************************************************/

	public function index()
	{
		// echo"here"; die;
		// echo"<pre>";
		// print_r($_POST);die;
		$date = date("Y-m-d");
		// $date = strtotime(date("Y-m-d", strtotime($date)) . "-2 months");
		// $date = date("Y-m-d", $date);
		$this->admin_model->authCheck('view_data');
		$this->admin_model->getPermissionType($data);
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanagelivestream';
		$data['activeSubMenu'] 				= 	'adminmanagelivestream';

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

		//$whereCon['where']		 			= 	"start_date >= '$date' ";
		$whereCon['where'] = "start_date >= '$date' AND live_stream = 1";
		$shortField 						= 	"ftable.event_id DESC";
		$baseUrl 							= 	base_url() . 'hhjsitemgmt/adminmanagelivestream/index';
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

		$data['ALLDATA'] 					= 	$this->common_model->getData_event('multiple', $tblName, $whereCon, $shortField, $perPage, $offset);
		// echo"<pre>";print_r($data['ALLDATA']);die;
		$data['venues'] = $this->common_model->getCategory();
		$data['cities'] = $this->common_model->getCategoryCity();
		$data['states'] = $this->common_model->getCategoryState();
		// echo"<pre>";print_r($data['cities']);die;
		$data['jazzTypes'] = $this->common_model->getCategoryJazz();
		$data['artistTypes'] = $this->common_model->getCategoryArtist(false);
		// echo "<pre>"; print_r($data['artistTypes']); die();
		$data['events'] = $this->common_model->totalEvents();
		// echo"<pre>";print_r($data['events']);die;
		$data['newEvents'] = $this->common_model->newEventsForCurrentMonth();
		// $data['newEventsCount'] = count($data['newEvents']);
		$data['trashevent'] = $this->common_model->totalTrashevent();
		$data['newTrashEvents'] = $this->common_model->trashEventsCurrentMonth();
		$data['publishevent'] = $this->common_model->totalPublishevent();
		$this->layouts->set_title('Manage Live Stream');
		$this->layouts->admin_view('live_stream/index', array(), $data);
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
		$data['activeMenu'] 				= 	'eventmanagement';
		$data['activeSubMenu'] 				= 	'eventmanagement';
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
		if ($editId) :
			$this->admin_model->authCheck('edit_data');
			
		// echo "<pre>";print_r($data['EDITDATA']);die;
		else :
			$this->admin_model->authCheck('add_data');
		endif;

		if ($this->request->getPost('SaveChanges')) :

			$validation = \Config\Services::validation();
    $postData = $this->request->getPost();
    $error = 'NO';

    // Define validation rules
    $rules = [
        'event_title'      => 'trim|required',
        'save_location_id' => 'trim|required',
        'start_date'       => 'trim|required',
        'end_date'         => 'trim|required',
        'location_name'    => 'trim|required',
        'location_address' => 'trim|required',
        'latitude'         => 'trim|required',
        'longitude'        => 'trim|required',
        'venue_id'         => 'trim|required',
    ];

    // Custom error messages
    $messages = [
        'event_title'      => ['required' => 'Event Title is required.'],
        'save_location_id' => ['required' => 'Location is required.'],
        'start_date'       => ['required' => 'Start Date is required.'],
        'end_date'         => ['required' => 'End Date is required.'],
        'location_name'    => ['required' => 'Location Name is required.'],
        'location_address' => ['required' => 'Location Address is required.'],
        'latitude'         => ['required' => 'Latitude is required.'],
        'longitude'        => ['required' => 'Longitude is required.'],
        'venue_id'         => ['required' => 'Venue is required.'],
    ];

    // Image validation (only required if adding a new event)
    // if (empty($_FILES['image']['name']) && empty($postData['CurrentDataID'])) {
    //     $rules['image'] = 'uploaded[image]|is_image[image]|max_size[image,2048]';
    //     $messages['image'] = [
    //         'uploaded' => 'Event Image is required.',
    //         'is_image' => 'Please upload a valid image file.',
    //         'max_size' => 'Image size must be under 2MB.'
    //     ];
    // }

    if (empty($_FILES['cover_image']['name']) && empty($postData['CurrentDataID'])) {
        $rules['cover_image'] = 'uploaded[cover_image]|is_image[cover_image]|max_size[cover_image,2048]';
        $messages['cover_image'] = [
            'uploaded' => 'Cover Image is required.',
            'is_image' => 'Please upload a valid image file.',
            'max_size' => 'Image size must be under 2MB.'
        ];
    }

    // Set validation rules
    $validation->setRules($rules, $messages);

    if (!$validation->withRequest($this->request)->run()) {
		// dd($validation);
		// die;
        return redirect()->back()->withInput()->with('validation', $validation);
    }

    // File Upload Handling
    $param = [];

    // Upload Event Image
    $file = $this->request->getFile('image');
    if ($file && $file->isValid() && !$file->hasMoved()) {
        $newName = $file->getRandomName();
        $file->move('assets/front/img/eventimage', $newName);
        $param['image'] = $newName;
    } elseif (!empty($postData['existing_image'])) {
        $param['image'] = $postData['existing_image']; // Retain existing image
    }

    // Upload Cover Image
    $coverFile = $this->request->getFile('cover_image');
    if ($coverFile && $coverFile->isValid() && !$coverFile->hasMoved()) {
        $newCoverName = $coverFile->getRandomName();
        $coverFile->move('assets/front/img/eventimage', $newCoverName);
        $param['cover_image'] = $newCoverName;
    } elseif (!empty($postData['cover_existing_image'])) {
        $param['cover_image'] = $postData['cover_existing_image']; // Retain existing cover image
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
							$param['time_permission']			= 	$this->request->getPost('time_permission');
							if ($param['time_permission'] == 'Yes') {

								try {
									$start_time = new DateTime("{$hour}:{$min} {$event_start_M}");

									$start_time->modify('+1 hour 30 minutes');

									$param['event_end_time'] = $start_time->format('h:i A');  // Format as HH:MM AM/PM
								} catch (Exception $e) {
									$param['event_end_time'] = 'Invalid time format';
								}
							} else {
								$param['event_end_time'] = $hour_end . ':' . $min_end . ' ' . $event_end_M;
							}
							// $param['event_end_time']            = 	$hour_end . ':' . $min_end . ' ' . $event_end_M;


							$combined_date_and_time = $param['start_date'] . ' ' . $param['event_start_time'];
							$param['date'] = strtotime($combined_date_and_time);

							$param['event_types']				=   $this->request->getPost('event_types');
							$param['url']				        =   $this->request->getPost('url');
							$param['cover_url']				    =   $this->request->getPost('cover_url');
							$param['cover_image']			    =   $this->request->getPost('cover_image');
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

							$param['repeating_event']			= 	$this->request->getPost('repeating_event');
							$param['live_stream'] = $this->request->getPost('live_stream') ? 1 : 0;
							$param['frequecy']					= 	$this->request->getPost('frequecy');
							$param['ip_address']				=	currentIp();
							$param['created_by']				=	(int)$this->session->get('ILCADM_ADMIN_ID');
							$param['creation_date']				= 	date('Y-m-d h:i:s');
							$param['is_active']					=	'1';
							$param['is_boosted']				=	isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
							$param['is_featured ']				=	isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';
							// Collect and encode the selected jazz types


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
							$jazz_types_ids = $this->request->getPost('jazz_types_id'); // Get the array of selected jazz types
							if (!empty($jazz_types_ids)) {
								foreach ($jazz_types_ids as $jazz_type_id) {
									$jazz_data = [
										'event_id' => $alastInsertId, // ID of the newly created event
										'event_jazz_types_id' => $jazz_type_id, // ID of the selected jazz type
									];
									$this->common_model->addData('event_jazz_tbl', $jazz_data); // Insert into event_jazz_tbl
								}
							}
							if ($alastInsertId) {
								// $elast_event_data = $this->elastic_model->getEventFromId($alastInsertId);
								
								// if (!empty($elast_event_data)) {
								// 	$this->elastichh->addUpdateSingleEvent($elast_event_data);
								// }
							}

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
						// $param['event_end_time']            = 	$hour_end . ':' . $min_end . ' ' . $event_end_M;
						$param['time_permission']			= 	$this->request->getPost('time_permission');
						if ($param['time_permission'] == 'Yes') {

							try {
								$start_time = new DateTime("{$hour}:{$min} {$event_start_M}");

								$start_time->modify('+1 hour 30 minutes');

								$param['event_end_time'] = $start_time->format('h:i A');  // Format as HH:MM AM/PM
							} catch (Exception $e) {

								$param['event_end_time'] = 'Invalid time format';
							}
						} else {

							$param['event_end_time'] = $hour_end . ':' . $min_end . ' ' . $event_end_M;
						}

						$combined_date_and_time = $param['start_date'] . ' ' . $param['event_start_time'];
						$param['date'] = strtotime($combined_date_and_time);

						$param['event_types']				=   $this->request->getPost('event_types');
						$param['url']				        =   $this->request->getPost('url');
						$param['cover_url']				    =   $this->request->getPost('cover_url');
						$param['cover_image']				=  $this->request->getPost('cover_image');
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

						$param['repeating_event']			= 	$this->request->getPost('repeating_event');
						$param['live_stream'] = $this->request->getPost('live_stream') ? 1 : 0;
						$param['frequecy']					= 	$this->request->getPost('frequecy');
						$param['ip_address']				=	currentIp();
						$param['created_by']				=	(int)$this->session->get('ILCADM_ADMIN_ID');
						$param['creation_date']				= 	date('Y-m-d h:i:s');
						$param['is_active']					=	isset($data['EDITDATA']['is_active']) ? $data['EDITDATA']['is_active'] : '1';
						$param['is_imported']					=	isset($data['EDITDATA']['is_imported']) ? $data['EDITDATA']['is_imported'] : 0;
						$param['is_boosted']				=	isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
						$param['is_featured ']				=	isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';
						$param['event_source']				= 	isset($data['EDITDATA']['event_source']) ? $data['EDITDATA']['event_source'] : '';
						$param['event_source_id']			= 	isset($data['EDITDATA']['event_source_id']) ? $data['EDITDATA']['event_source_id'] : '';
						$param['event_source_image']		= 	isset($data['EDITDATA']['event_source_image']) ? $data['EDITDATA']['event_source_image'] : '';
						// Collect and encode the selected jazz types
						// Get the array of selected jazz types from the form



						// If you want to store the comma-separated string for other purposes, you can do so
						// For example, if you need to save it in a different table or for further processing
						// $param['jazz_types'] = $jazz_types_string; // Just an example if you want to store it somewhere

						//echo"<pre>";print_r($param['jazz_types_id']);die;
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
						// Get the array of selected jazz types from the form


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
						if ($alastInsertId) {
							// $elast_event_data = $this->elastic_model->getEventFromId($alastInsertId);
						
							// if (!empty($elast_event_data)) {
							// 	$this->elastichh->addUpdateSingleEvent($elast_event_data);
							// }
						}


						// $jazz_types_ids = $this->request->getPost('jazz_types_id'); // Get the array of selected jazz types


						// echo "<pre>";print_r($_POST);die;
						$this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));
					}

				else :

					$this->common_model->deleteData('event_tbl', 'event_id', (int)$editId);
					$this->common_model->deleteData('event_jazz_tbl', 'event_id', (int)$editId);
					// $this->elastichh->deleteSingleEventFromIndex('events', (int)$editId);

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
							$param['time_permission']			= 	$this->request->getPost('time_permission');
							if ($param['time_permission'] == 'Yes') {

								try {
									$start_time = new DateTime("{$hour}:{$min} {$event_start_M}");

									$start_time->modify('+1 hour 30 minutes');

									$param['event_end_time'] = $start_time->format('h:i A');  // Format as HH:MM AM/PM
								} catch (Exception $e) {

									$param['event_end_time'] = 'Invalid time format';
								}
							} else {

								$param['event_end_time'] = $hour_end . ':' . $min_end . ' ' . $event_end_M;
							}
							// $param['event_end_time']            = 	$hour_end . ':' . $min_end . ' ' . $event_end_M;

							$combined_date_and_time = $param['start_date'] . ' ' . $param['event_start_time'];
							$param['date'] = strtotime($combined_date_and_time);

							$param['event_types']				=   $this->request->getPost('event_types');
							$param['url']				        =   $this->request->getPost('url');
							$param['cover_url']				    =   $this->request->getPost('cover_url');
							$param['cover_image']				    =   $this->request->getPost('cover_image');
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
							// $param['time_permission']			= 	$this->request->getPost('time_permission');
							$param['repeating_event']			= 	$this->request->getPost('repeating_event');
							$param['live_stream'] = $this->request->getPost('live_stream') ? 1 : 0;
							$param['frequecy']					= 	$this->request->getPost('frequecy');
							$param['ip_address']				=	currentIp();
							$param['created_by']				=	(int)$this->session->get('ILCADM_ADMIN_ID');
							$param['creation_date']				= 	isset($data['EDITDATA']['creation_date']) ? $data['EDITDATA']['creation_date'] : '0';
							// $param['is_active']					=	'1';
							$param['is_active']				=	isset($data['EDITDATA']['is_active']) ? $data['EDITDATA']['is_active'] : '0';
							$param['is_boosted']				=	isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
							$param['is_featured ']				=	isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';
							$param['is_imported']					=	isset($data['EDITDATA']['is_imported']) ? $data['EDITDATA']['is_imported'] : 0;

							$param['virtual_event']	    = 	$this->request->getPost('virtual_event');
							$param['boost_days']	    = 	isset($data['EDITDATA']['boost_days']) ? $data['EDITDATA']['boost_days'] : '0';
							$param['boost_date']	    = 	isset($data['EDITDATA']['boost_date']) ? $data['EDITDATA']['boost_date'] : '0';
							$param['requested_boost']	    = 	isset($data['EDITDATA']['requested_boost']) ? $data['EDITDATA']['requested_boost'] : '0';
							$param['ticket_status_code']	    = 	isset($data['EDITDATA']['ticket_status_code']) ? $data['EDITDATA']['ticket_status_code'] : '0';

							$param['event_source']				= 	isset($data['EDITDATA']['event_source']) ? $data['EDITDATA']['event_source'] : '';
							$param['event_source_id']			= 	isset($data['EDITDATA']['event_source_id']) ? $data['EDITDATA']['event_source_id'] : '';
							$param['event_source_image']		= 	isset($data['EDITDATA']['event_source_image']) ? $data['EDITDATA']['event_source_image'] : '';
							// Collect and encode the selected jazz types


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
							if ($alastInsertId) {
								// $elast_event_data = $this->elastic_model->getEventFromId($alastInsertId);
								
								// if (!empty($elast_event_data)) {
								// 	$this->elastichh->addUpdateSingleEvent($elast_event_data);
								// }
							}

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
						$param['time_permission']			= 	$this->request->getPost('time_permission');
						if ($param['time_permission'] == 'Yes') {

							try {
								$start_time = new DateTime("{$hour}:{$min} {$event_start_M}");

								$start_time->modify('+1 hour 30 minutes');

								$param['event_end_time'] = $start_time->format('h:i A');
							} catch (Exception $e) {

								$param['event_end_time'] = 'Invalid time format';
							}
						} else {

							$param['event_end_time'] = $hour_end . ':' . $min_end . ' ' . $event_end_M;
						}
						// $param['event_end_time']            = 	$hour_end . ':' . $min_end . ' ' . $event_end_M;

						$combined_date_and_time = $param['start_date'] . ' ' . $param['event_start_time'];
						$param['date'] = strtotime($combined_date_and_time);

						$param['event_types']				=   $this->request->getPost('event_types');
						$param['url']				        =   $this->request->getPost('url');
						$param['cover_url']				    =   $this->request->getPost('cover_url');
						$param['cover_image']				    =   $this->request->getPost('cover_image');
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

						$param['repeating_event']			= 	$this->request->getPost('repeating_event');
						$param['live_stream'] = $this->request->getPost('live_stream') ? 1 : 0;
						$param['frequecy']					= 	$this->request->getPost('frequecy');
						$param['ip_address']				=	currentIp();
						$param['created_by']				=	(int)$this->session->get('ILCADM_ADMIN_ID');
						$param['creation_date']				= 	isset($data['EDITDATA']['creation_date']) ? $data['EDITDATA']['creation_date'] : '0';
						// $param['is_active']					=	'1';
						$param['is_active']				=	isset($data['EDITDATA']['is_active']) ? $data['EDITDATA']['is_active'] : '0';
						$param['is_boosted']				=	isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
						$param['is_featured ']				=	isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';
						$alastInsertId						=	$this->common_model->addData('event_tbl', $param);
						$param['is_imported']					=	isset($data['EDITDATA']['is_imported']) ? $data['EDITDATA']['is_imported'] : 0;
						$param['virtual_event']	    = 	$this->request->getPost('virtual_event');
						$param['boost_days']	    = 	isset($data['EDITDATA']['boost_days']) ? $data['EDITDATA']['boost_days'] : '0';
						$param['boost_date']	    = 	isset($data['EDITDATA']['boost_date']) ? $data['EDITDATA']['boost_date'] : '0';
						$param['requested_boost']	    = 	isset($data['EDITDATA']['requested_boost']) ? $data['EDITDATA']['requested_boost'] : '0';
						$param['ticket_status_code']	    = 	isset($data['EDITDATA']['ticket_status_code']) ? $data['EDITDATA']['ticket_status_code'] : '0';

						$param['event_source']				= 	isset($data['EDITDATA']['event_source']) ? $data['EDITDATA']['event_source'] : '';
						$param['event_source_id']			= 	isset($data['EDITDATA']['event_source_id']) ? $data['EDITDATA']['event_source_id'] : '';
						$param['event_source_image']		= 	isset($data['EDITDATA']['event_source_image']) ? $data['EDITDATA']['event_source_image'] : '';

						// echo"<pre>"; print_r($param); die;

						// Prepare the event data
						$param['jazz_types_id'] = $jazz_types_json; // Store JSON in the jazz_types_id column

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
						if ($alastInsertId) {
							// $elast_event_data = $this->elastic_model->getEventFromId($alastInsertId);
							
							// if (!empty($elast_event_data)) {
							// 	$this->elastichh->addUpdateSingleEvent($elast_event_data);
							// }
						}
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
		$data['eventTypes'] = $this->common_model->getEventlocationtype();
		// echo "<pre>"; print_r($data['eventTypes']); die();
		$this->layouts->set_title('Manage Live Stream');
		$this->layouts->admin_view('live_stream/addeditdata', array(), $data);
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

		$this->common_model->editData('event_tbl', $param, 'event_id', (int)$changeStatusId);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}

	function deletedata($deleteId = '')
	{
		$deleteId = $this->uri->getSegment(4); 
		$this->admin_model->authCheck('delete_data');

		$this->common_model->deleteData('event_tbl', 'event_id', (int)$deleteId);
		$this->common_model->deleteData('event_tags_tbl', 'event_id', (int)$deleteId);
		// $this->elastichh->deleteSingleEventFromIndex('events', (int)$deleteId);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.deletesuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}

	// function mutlipleChangeStatus()
	// {
	// 	$changeStatusIds = json_decode($_POST['changeStatusIds']);
	// 	$statusType = $_POST['statusType'];
	// 	// echo $statusType; die;
	// 	// print_r($changeStatusIds); die;
	// 	if ($statusType !== "permanentdelete") {
	// 		$this->admin_model->authCheck('edit_data');
	// 		foreach ($changeStatusIds as $changeStatusId) {
	// 			// $param['is_active'] = $statusType;
	// 			if ($statusType == 'inactive') {
	// 				$param['is_active'] = "0";
	// 			} else if ($statusType == 'active') {
	// 				$param['is_active'] = "1";
	// 			} else if ($statusType == 'unboost') {
	// 				$param['is_boosted'] = "0";
	// 			} else if ($statusType == 'boost') {
	// 				$param['is_boosted'] = "1";
	// 			} else if ($statusType == 'unfeatured') {
	// 				$param['is_featured'] = "0";
	// 			} else if ($statusType == 'featured') {
	// 				$param['is_featured'] = "1";
	// 			}

	// 			$this->common_model->editData('event_tbl', $param, 'event_id', (int)$changeStatusId);
	// 			$this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));
	// 		}
	// 	} else {
	// 		foreach ($changeStatusIds as $changeStatusId) {
	// 			$this->admin_model->authCheck('delete_data');
	// 			$this->common_model->deleteData('event_tbl', 'event_id', (int)$changeStatusId);
	// 			$this->elastichh->deleteSingleEventFromIndex('events', (int)$changeStatusId);

	// 			$this->session->setFlashdata('alert_success', lang('statictext_lang.deletesuccess'));
	// 		}
	// 	}

	// 	return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	// }

	// function statusboost($changeStatusId = '', $statusType = '')
	// {
	// 	$this->admin_model->authCheck('edit_data');
	// 	$param['is_boosted']		=	$statusType;

	// 	$this->common_model->editData('event_tbl', $param, 'event_id', (int)$changeStatusId);
	// 	$this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));

	// 	return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	// }

	// function statusfeatured($changeStatusId = '', $statusType = '')
	// {
	// 	$this->admin_model->authCheck('edit_data');
	// 	$param['is_featured']		=	$statusType;

	// 	$this->common_model->editData('event_tbl', $param, 'event_id', (int)$changeStatusId);
	// 	$this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));

	// 	return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	// }

	/***********************************************************************
	 ** Function name 	: deletedata
	 ** Developed By 	: Megha Kumari
	 ** Purpose  		: This function used for delete data
	 ** Date 			: 27 JUNE 2022
	 ************************************************************************/


	// function location()
	// {
	// 	$location_id = $_GET['LocationId'];
	// 	$dataQuery = $this->db->select('*')->from('event_location_tbl')->where('id', $location_id)->get()->row();
	// 	echo json_encode(array('location_name' => $dataQuery->location_name, 'location_address' => $dataQuery->location_address, 'latitude' => $dataQuery->latitude, 'longitude' => $dataQuery->longitude, 'website' => $dataQuery->website, 'phone_number' => $dataQuery->phone_number, 'venue_id' => $dataQuery->venue_id, 'jazz_types_id' => $dataQuery->jazz_types_id, 'event_location_type_id' => $dataQuery->event_location_type_id,'artist_id' => $dataQuery->artist_id));
	// }
	// function location()
	// {
	// 	$location_id = $_GET['LocationId'];
	// 	$dataQuery = $this->db->select('event_location_tbl.*, event_location_type.name AS event_location_type_name')
	// 		->from('event_location_tbl')
	// 		->join('event_location_type', 'event_location_type.id = event_location_tbl.event_location_type_id', 'left')
	// 		->where('event_location_tbl.id', $location_id)
	// 		->get()
	// 		->row();

	// 	echo json_encode(array(
	// 		'location_name' => $dataQuery->location_name,
	// 		'location_address' => $dataQuery->location_address,
	// 		'latitude' => $dataQuery->latitude,
	// 		'longitude' => $dataQuery->longitude,
	// 		'website' => $dataQuery->website,
	// 		'phone_number' => $dataQuery->phone_number,
	// 		'venue_id' => $dataQuery->venue_id,
	// 		'jazz_types_id' => $dataQuery->jazz_types_id,
	// 		'event_location_type_id' => $dataQuery->event_location_type_id,
	// 		'event_location_type_name' => $dataQuery->event_location_type_name, // Include the name here
	// 		'artist_id' => $dataQuery->artist_id
	// 	));
	// }


	/***********************************************************************
	 ** Function name 	: updateStatus
	 ** Developed By 	: Megha Kumari
	 ** Purpose  		: This function used for delete data
	 ** Date 			: 27 JUNE 2022
	 ************************************************************************/
	// function updateStatus($id = '')
	// {

	// 	$param['is_active'] = '2';

	// 	$whereCon = "event_id = '" . $id . "' ";
	// 	$this->common_model->editMultipleDataByMultipleCondition('event_tbl', $param, $whereCon);
	// 	$this->session->setFlashdata('alert_success', lang('statictext_lang.deletesuccess'));

	// 	return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	// }

	/***********************************************************************
	 ** Function name 	: updateStatus
	 ** Developed By 	: Megha Kumari
	 ** Purpose  		: This function used for delete data
	 ** Date 			: 27 JUNE 2022
	 ************************************************************************/
	// function duplicate($id = '')
	// {

	// 	$data = $this->db->select('*')->from('event_tbl')->where('event_id', $id)->get()->row();

	// 	$param['event_title']				    = 	$data->event_title;
	// 	$param['save_location_id']				= 	$data->save_location_id;
	// 	$param['description']					= 	$data->description;
	// 	$param['start_date']					= 	$data->start_date;
	// 	$param['end_date']						= 	$data->end_date;
	// 	$param['no_of_repeat']					= 	$data->no_of_repeat;
	// 	$param['no_of_copy']					= 	$data->no_of_copy + 1;
	// 	$param['location_name']					= 	$data->location_name;
	// 	$param['location_address']				= 	$data->location_address;
	// 	$param['latitude']						= 	$data->latitude;
	// 	$param['longitude']						= 	$data->longitude;
	// 	$param['time_permission']				= 	$data->time_permission;
	// 	$param['website']						= 	$data->website;
	// 	$param['phone_number']					= 	$data->phone_number;
	// 	$param['venue_id']						= 	$data->venue_id;
	// 	$param['jazz_types_id']					= 	$data->jazz_types_id;
	// 	$param['cover_charge']					= 	$data->cover_charge;
	// 	$param['url']				            =   $data->url;
	// 	$param['cover_url']				        =   $data->cover_url;
	// 	$param['cover_image']				    =   $data->cover_image;
	// 	$param['video']				            =   $data->video;
	// 	$param['video2']				        =   $data->video2;
	// 	$param['video3']				        =   $data->video3;
	// 	$param['qr_code_link']				    =   $data->qr_code_link;
	// 	$param['buy_now_link']			        =   $data->buy_now_link;
	// 	$param['reserve_seat_link']			    =   $data->reserve_seat_link;
	// 	// $param['event_tags']			        =   $data->event_tags;
	// 	$param['event_types']                   =    $data->event_types;
	// 	$param['set_time']						= 	$data->set_time;
	// 	$param['event_start_time']				=   $data->event_start_time;
	// 	$param['event_end_time']				= 	$data->event_end_time;
	// 	$param['repeating_event']				= 	$data->repeating_event;
	// 	$param['frequecy']						= 	$data->frequecy;
	// 	$param['ip_address']					=	currentIp();
	// 	$param['created_by']					=	(int)$this->session->get('ILCADM_ADMIN_ID');
	// 	$param['creation_date']					= 	date('Y-m-d h:i:s');
	// 	$param['is_active']					    =	'1';
	// 	$param['is_boosted']				    =	isset($data->is_boosted) ? $data->is_boosted : '0';
	// 	$param['is_featured ']				    =	isset($data->is_featured) ? $data->is_featured : '0';
	// 	// echo"here";die;

	// 	$alastInsertId						=	$this->common_model->addData('event_tbl', $param);
	// 	if ($alastInsertId) {
	// 		$elast_event_data = $this->elastic_model->getEventFromId($alastInsertId);
	
	// 		if (!empty($elast_event_data)) {
	// 			$this->elastichh->addUpdateSingleEvent($elast_event_data);
	// 		}
	// 	}


	// 	$this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));

	// 	return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	// }

	// public function boostDays()
	// {
	// 	$boost_days = $this->request->getPost('boost_days');
	// 	$boost_date = $this->request->getPost('boost_date');
	// 	$event_id = $this->request->getPost('event_id');
	// 	$is_boosted = $this->request->getPost('is_boosted');

	// 	// Check if boost_days and boost_date are provided
	// 	// if (!empty($boost_days) && !empty($boost_date)) {
	// 	$param['boost_days'] = $boost_days;
	// 	$param['boost_date'] = $boost_date;
	// 	$param['is_boosted'] = $is_boosted;

	// 	if (empty($event_id)) {
	// 		$lastInsertId = $this->common_model->addData('event_tbl', $param);

	// 		if ($lastInsertId) {
	// 			echo json_encode(array('event_id' => $lastInsertId, 'days' => $boost_days, 'date' => $boost_date));
	// 		} else {
	// 			echo json_encode(array());
	// 		}
	// 	} else {
	// 		$updateStatus = $this->common_model->editData('event_tbl', $param, 'event_id', $event_id);

	// 		if ($updateStatus) {
	// 			echo json_encode(array('event_id' => $event_id, 'days' => $boost_days, 'date' => $boost_date));
	// 		} else {
	// 			echo json_encode(array());
	// 		}
	// 	}
	// 	// } else {
	// 	// echo json_encode(array('message' => 'No data to save'));
	// 	// }
	// }

	// public function import()
	// {
	// 	require_once APPPATH . 'third_party/classes/PHPExcel.php';

	// 	$data['error'] = '';
	// 	$data['activeMenu'] = 'eventmanagement';
	// 	$data['activeSubMenu'] = 'eventmanagement';

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
	// 				redirect(correctLink('userILCADMData', getCurrentControllerPath('event/import')));
	// 			} else {
	// 				$data = array('upload_data' => $this->upload->data());
	// 				$file_path = './assets/admin/document/' . $data['upload_data']['file_name'];
	// 				$objPHPExcel = PHPExcel_IOFactory::load($file_path);
	// 				$sheet = $objPHPExcel->getActiveSheet();
	// 				$highestRow = $sheet->getHighestRow();

	// 				// Insert filename into import table
	// 				$import_data = array(
	// 					'import_file' => $data['upload_data']['file_name'],
	// 					'is_active' => "1"
	// 				);
	// 				$this->common_model->addData('import_tbl', $import_data);

	// 				for ($row = 2; $row <= $highestRow; $row++) {

	// 					$start_date_value = $sheet->getCellByColumnAndRow(7, $row)->getValue();
	// 					$end_date_value = $sheet->getCellByColumnAndRow(8, $row)->getValue();

	// 					$start_date = $this->parseDate($start_date_value);
	// 					$end_date = $this->parseDate($end_date_value);

	// 					$event_start_time_excel = $sheet->getCellByColumnAndRow(9, $row)->getValue();
	// 					$event_start_time = PHPExcel_Style_NumberFormat::toFormattedString($event_start_time_excel, 'hh:mm:ss');

	// 					$event_end_time_excel = $sheet->getCellByColumnAndRow(10, $row)->getValue();
	// 					$event_end_time = PHPExcel_Style_NumberFormat::toFormattedString($event_end_time_excel, 'hh:mm:ss');

	// 					$is_active_excel = $sheet->getCellByColumnAndRow(15, $row)->getValue();
	// 					$is_active = "1";

	// 					$virtual_event_cell = $sheet->getCellByColumnAndRow(26, $row);
	// 					$virtual_event_price_cell = $sheet->getCellByColumnAndRow(27, $row);
	// 					$virtual_event_link_cell = $sheet->getCellByColumnAndRow(28, $row);


	// 					$event_data = array(
	// 						'event_title'  => $sheet->getCellByColumnAndRow(0, $row)->getValue(),
	// 						'description'  => $sheet->getCellByColumnAndRow(1, $row)->getValue(),
	// 						'location_name' => $sheet->getCellByColumnAndRow(2, $row)->getValue(),
	// 						'location_address' => $sheet->getCellByColumnAndRow(3, $row)->getValue(),
	// 						'latitude' => $sheet->getCellByColumnAndRow(4, $row)->getValue(),
	// 						'longitude' => $sheet->getCellByColumnAndRow(5, $row)->getValue(),
	// 						'venue_id' => $sheet->getCellByColumnAndRow(6, $row)->getValue(),
	// 						'start_date' => $start_date,
	// 						'end_date' =>  $end_date,
	// 						'event_start_time' => $event_start_time,
	// 						'event_end_time' => $event_end_time,
	// 						'time_permission' => $sheet->getCellByColumnAndRow(11, $row)->getValue(),
	// 						'repeating_event' => $sheet->getCellByColumnAndRow(12, $row)->getValue(),
	// 						'website' => $sheet->getCellByColumnAndRow(13, $row)->getValue(),
	// 						'phone_number' => $sheet->getCellByColumnAndRow(14, $row)->getValue(),
	// 						'is_active' => $is_active,
	// 						'set_time' => $sheet->getCellByColumnAndRow(16, $row)->getValue(),
	// 						'cover_charge' => $sheet->getCellByColumnAndRow(17, $row)->getValue(),
	// 						'buy_now_link' => $sheet->getCellByColumnAndRow(18, $row)->getValue(),
	// 						'reserve_seat_link' => $sheet->getCellByColumnAndRow(19, $row)->getValue(),
	// 						'event_tags' => $sheet->getCellByColumnAndRow(20, $row)->getValue(),
	// 						'jazz_types_id' => $sheet->getCellByColumnAndRow(21, $row)->getValue(),
	// 						'video' => $sheet->getCellByColumnAndRow(22, $row)->getValue(),
	// 						'video2' => $sheet->getCellByColumnAndRow(23, $row)->getValue(),
	// 						'video3' => $sheet->getCellByColumnAndRow(24, $row)->getValue(),
	// 						'artist_id' => $sheet->getCellByColumnAndRow(25, $row)->getValue(),
	// 						'virtual_event' => $virtual_event_cell !== null ? $virtual_event_cell->getValue() : null,
	// 						'virtual_event_price' => $virtual_event_price_cell !== null ? $virtual_event_price_cell->getValue() : null,
	// 						'virtual_event_link' => $virtual_event_link_cell !== null ? $virtual_event_link_cell->getValue() : null,

	// 					);

	// 					$save_location_id = $event_data['location_name'];
	// 					$location_data = $this->common_model->getDataByParticularField('event_location_tbl', 'location_name', $save_location_id);

	// 					if ($location_data) {
	// 						$event_data['save_location_id'] = $location_data['id'];
	// 					} else {
	// 						$event_data['save_location_id'] = '';
	// 					}

	// 					$venue_id = $event_data['venue_id'];
	// 					$venue_data = $this->common_model->getDataByParticularField('venue_tbl', 'venue_title', $venue_id);

	// 					if ($venue_data) {
	// 						$event_data['venue_id'] = $venue_data['id'];
	// 					} else {
	// 						$event_data['venue_id'] = '';
	// 					}
	// 					unset($event_data['venue_title']);

	// 					$jazz_types_id = $event_data['jazz_types_id'];
	// 					$jazz_type_data = $this->common_model->getDataByParticularField('jazz_types', 'name', $jazz_types_id);

	// 					if ($jazz_type_data) {
	// 						$event_data['jazz_types_id'] = $jazz_type_data['id'];
	// 					} else {
	// 						$event_data['jazz_types_id'] = '';
	// 					}


	// 					$artist_id = $event_data['artist_id'];
	// 					$artist_data = $this->common_model->getDataByParticularField('artist_tbl', 'artist_name', $artist_id);

	// 					if ($artist_data) {
	// 						$event_data['artist_id'] = $artist_data['id'];
	// 					} else {
	// 						$event_data['artist_id'] = '';
	// 					}

	// 					$this->common_model->addData('event_tbl', $event_data);
	// 				}

	// 				$this->session->setFlashdata('alert_success', 'Events imported successfully.');

	// 				redirect(correctLink('userILCADMData', getCurrentControllerPath('event/import')));
	// 			}

	// 		endif;
	// 	endif;

	// 	$this->layouts->set_title('Manage Import');
	// 	$this->layouts->admin_view('event/import');
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
}
