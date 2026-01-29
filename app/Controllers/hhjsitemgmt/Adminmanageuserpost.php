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
class Adminmanageuserpost extends BaseController
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
		$this->uri = service('uri');
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
		//$this->load->library('elastichh');
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
		$data['activeMenu'] 				= 	'adminmanageuserpost';
		$data['activeSubMenu'] 				= 	'adminmanageuserpost';

		if ($this->request->getGet('searchValue')) :
			$sValue							=	$this->request->getGet('searchValue');
			$whereCon['like']			 	= 	"(ftable.event_title LIKE '%" . $sValue . "%'
												)";
			$whereCon['like'] = ['ftable.event_title' => $sValue];			
			$data['searchField'] = $sField ?? '';
			$data['searchValue'] 			= 	$sValue;
		else :
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;

		$whereCon['where']		 			= 	'';
		$shortField 						= 	"ftable.event_id DESC";

		$baseUrl 							= 	getCurrentControllerPath('index');
		$this->session->set('userILCADMData', currentFullUrl());
		$qStringdata						=	explode('?', currentFullUrl());
		$suffix								= 	$qStringdata[1] ? '?' . $qStringdata[1] : '';
		$tblName 							= 	'event_tbl as ftable';
		$con 								= 	'';
		//echo $tblName; die();
		$totalRows 							= 	$this->common_model->getData_submittedEvent('count', $tblName, $whereCon, $shortField, '0', '0');

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

		$data['ALLDATA'] 					= 	$this->common_model->getData_submittedEvent('multiple', $tblName, $whereCon, $shortField, $perPage, $offset);
		//echo "<pre>"; print_r($data['ALLDATA']); die();
		$this->layouts->set_title('Manage Event Submitted List');
		$this->layouts->admin_view('event_submitted_list/index', array(), $data);
	}	// END OF FUNCTION


	public function addeditdata($editId = '')
	{

		$editId = $this->uri->getSegment(4);
		// echo"<pre>";
		// print_r($_POST);die;
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanageuserpost';
		$data['activeSubMenu'] 				= 	'adminmanageuserpost';
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

			$error					=	'NO';
			$validation = \Config\Services::validation();

			$rules = [
				'event_title' => [
					'rules' => 'trim|required',
					'errors' => [
						'required' => 'The Event Title field is required.'
					]
				],
				'save_location_id' => [
					'rules' => 'trim|required',
					'errors' => [
						'required' => 'Please select a Location.'
					]
				],
				'start_date' => [
					'rules' => 'trim|required',
					'errors' => [
						'required' => 'The Start Date field is required.'
					]
				],
				'end_date' => [
					'rules' => 'trim|required',
					'errors' => [
						'required' => 'The End Date field is required.'
					]
				],
				'location_name' => [
					'rules' => 'trim|required',
					'errors' => [
						'required' => 'The Location Name field is required.'
					]
				],
				'location_address' => [
					'rules' => 'trim|required',
					'errors' => [
						'required' => 'The Location Address field is required.'
					]
				],
				'latitude' => [
					'rules' => 'trim|required',
					'errors' => [
						'required' => 'Latitude is required.'
					]
				],
				'longitude' => [
					'rules' => 'trim|required',
					'errors' => [
						'required' => 'Longitude is required.'
					]
				],
				'venue_id' => [
					'rules' => 'trim|required',
					'errors' => [
						'required' => 'Please select a Venue.'
					]
				],
				'user_first_name' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Contact Person First Name is required.'
					]
				],
				'user_last_name' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Contact Person Last Name is required.'
					]
				],
				'email' => [
					'rules' => 'required|valid_email',
					'errors' => [
						'required' => 'Contact Email is required.',
						'valid_email' => 'Please enter a valid email address.'
					]
				],
				// 'image' => [
				// 	'rules' => 'uploaded[image]|is_image[image]|max_size[image,2048]|mime_in[image,image/jpg,image/jpeg,image/png,image/gif,image/webp]',
				// 	'errors' => [
				// 		'uploaded' => 'Please select an Image.',
				// 		'is_image' => 'Only image files are allowed.',
				// 		'max_size' => 'Image size should not exceed 2MB.',
				// 		'mime_in' => 'Invalid image format. Allowed: jpg, jpeg, png, gif, webp.'
				// 	]
				// ],
				// 'cover_image' => [
				// 	'rules' => 'uploaded[cover_image]|is_image[cover_image]|max_size[cover_image,2048]|mime_in[cover_image,image/jpg,image/jpeg,image/png,image/gif,image/webp]',
				// 	'errors' => [
				// 		'uploaded' => 'Please select a Cover Image.',
				// 		'is_image' => 'Only image files are allowed.',
				// 		'max_size' => 'Cover Image size should not exceed 2MB.',
				// 		'mime_in' => 'Invalid cover image format. Allowed: jpg, jpeg, png, gif, webp.'
				// 	]
				// ]
			];

			if (!$this->validate($rules)) {
				
				return redirect()->back()->withInput()->with('validation', $validation);
			}
			// $this->form_validation->set_rules('image','Image', 'required');
			// $this->form_validation->set_rules('cover_image','Cover Image', 'required');
			// $this->form_validation->set_rules('jazz_types_id', 'Jazz Type', 'trim|required');
			// $this->form_validation->set_rules('virtual_event_price', 'Virtual Event Price', 'required');
			// $this->form_validation->set_rules('virtual_event_link', 'Virtual Event Link', 'required');


			// if (!empty($_FILES['image']['name'])) {
			// 	$config = [
			// 		'upload_path'   => './assets/front/img/eventimage',
			// 		'allowed_types' => 'jpg|png|gif|jpeg|webp',
			// 		//'encrypt_name'  => TRUE
			// 	];
			// 	$this->load->library('upload', $config);
			// 	$image = '';
			// 	if ($this->upload->do_upload('image')) {
			// 		$img_data = $this->upload->data();
			// 		$param['image'] = $img_data['file_name'];
			// 	}
			// }
			// if (!empty($_FILES['cover_image']['name'])) {
			// 	$config = [
			// 		'upload_path'   => './assets/front/img/eventimage',
			// 		'allowed_types' => 'jpg|png|gif|jpeg|webp',
			// 		//'encrypt_name'  => TRUE
			// 	];
			// 	$this->load->library('upload', $config);
			// 	$image = '';
			// 	if ($this->upload->do_upload('cover_image')) {
			// 		$img_data = $this->upload->data();
			// 		$param['cover_image'] = $img_data['file_name'];
			// 	}
			// }

			// 			$imageFile = $this->request->getFile('image');
			// $coverFile = $this->request->getFile('cover_image');

			// $uploadPath = './assets/front/img/eventimage/';

			// // Process Image Upload
			// if ($imageFile->isValid() && !$imageFile->hasMoved()) {
			//     $newImageName = $imageFile->getRandomName();
			//     $imageFile->move($uploadPath, $newImageName);
			//     $param['image'] = $newImageName;
			// }

			// // Process Cover Image Upload
			// if ($coverFile->isValid() && !$coverFile->hasMoved()) {
			//     $newCoverName = $coverFile->getRandomName();
			//     $coverFile->move($uploadPath, $newCoverName);
			//     $param['cover_image'] = $newCoverName;
			// }

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

						$param['url']				        =   $this->request->getPost('url');
						$param['event_types']				=   $this->request->getPost('event_types');
						$param['user_first_name']		    = 	$this->request->getPost('user_first_name');
						$param['user_last_name']		    = 	$this->request->getPost('user_last_name');
						$param['email']				        = 	$this->request->getPost('email');
						$param['cover_url']				    =   $this->request->getPost('cover_url');
						$param['video']				        =   $this->request->getPost('video');
						$param['video2']				    =   $this->request->getPost('video2');
						$param['video3']				    =   $this->request->getPost('video3');
						$param['qr_code_link']			    =   $this->request->getPost('qr_code_link');
						$param['buy_now_link']			    =   $this->request->getPost('buy_now_link');
						$param['reserve_seat_link']			=   $this->request->getPost('reserve_seat_link');
						// $param['event_tags']			    =   $this->request->getPost('event_tags');
						$param['no_of_repeat']				= 	intOrNull($this->request->getPost('no_of_repeat'));
						$param['location_name']				= 	$this->request->getPost('location_name');
						$param['location_address']			= 	$this->request->getPost('location_address');
						$param['latitude']					= 	$this->request->getPost('latitude');
						$param['longitude']					= 	$this->request->getPost('longitude');
						$param['website']					= 	$this->request->getPost('website');
						$param['phone_number']				= 	$this->request->getPost('phone_number');
						$param['venue_id']					= 	$this->request->getPost('venue_id');
						//$param['jazz_types_id']				= 	$this->request->getPost('jazz_types_id');
						$param['artist_id']				    = 	intOrNull($this->request->getPost('artist_id'));
						$param['virtual_event_price']	    = 	floatOrNull($this->request->getPost('virtual_event_price'));
						$param['virtual_event_link']	    = 	$this->request->getPost('virtual_event_link');
						$param['cover_charge']				= 	$this->request->getPost('cover_charge');
						$param['set_time']					= 	$this->request->getPost('set_time');
						$param['time_permission']			= 	$this->request->getPost('time_permission');
						$param['repeating_event']			= 	$this->request->getPost('repeating_event');
						$param['free_concert'] = $this->request->getPost('free_concert') ? 1 : 0;
						$param['frequecy']					= 	$this->request->getPost('frequecy');
						$param['ip_address']				=	currentIp();
						$param['created_by']				=	(int)$this->session->get('ILCADM_ADMIN_ID');
						$param['creation_date']				= 	date('Y-m-d h:i:s');
						$param['is_active']					=	isset($data['EDITDATA']['is_active']) ? $data['EDITDATA']['is_active'] : '0';
						$param['is_boosted']				=	isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
						$param['is_featured ']				=	isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';
						$param['is_front']			        =	'1';
						$param['added_by']				= 	'user';

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
						
						// 	if(!empty($elast_event_data)){
						// 		$this->elastichh->addUpdateSingleEvent($elast_event_data);
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

					$param['url']				        =   $this->request->getPost('url');
					$param['event_types']				=   $this->request->getPost('event_types');
					$param['user_first_name']		    = 	$this->request->getPost('user_first_name');
					$param['user_last_name']		    = 	$this->request->getPost('user_last_name');
					$param['email']				        = 	$this->request->getPost('email');
					$param['cover_url']				    =   $this->request->getPost('cover_url');
					$param['video']				        =   $this->request->getPost('video');
					$param['video2']				    =   $this->request->getPost('video2');
					$param['video3']				    =   $this->request->getPost('video3');
					$param['qr_code_link']			    =   $this->request->getPost('qr_code_link');
					$param['buy_now_link']			    =   $this->request->getPost('buy_now_link');
					$param['reserve_seat_link']			=   $this->request->getPost('reserve_seat_link');
					// $param['event_tags']			    =   $this->request->getPost('event_tags');
					$param['no_of_repeat']				= 	intOrNull($this->request->getPost('no_of_repeat'));
					$param['location_name']				= 	$this->request->getPost('location_name');
					$param['location_address']			= 	$this->request->getPost('location_address');
					$param['latitude']					= 	$this->request->getPost('latitude');
					$param['longitude']					= 	$this->request->getPost('longitude');
					$param['website']					= 	$this->request->getPost('website');
					$param['phone_number']				= 	$this->request->getPost('phone_number');
					$param['venue_id']					= 	$this->request->getPost('venue_id');
					//$param['jazz_types_id']				= 	$this->request->getPost('jazz_types_id');
					$param['artist_id']				    = 	intOrNull($this->request->getPost('artist_id'));
					$param['virtual_event_price']	    = 	floatOrNull($this->request->getPost('virtual_event_price'));
					$param['virtual_event_link']	    = 	$this->request->getPost('virtual_event_link');
					$param['cover_charge']				= 	$this->request->getPost('cover_charge');
					$param['set_time']					= 	$this->request->getPost('set_time');
					$param['time_permission']			= 	$this->request->getPost('time_permission');
					$param['repeating_event']			= 	$this->request->getPost('repeating_event');
					$param['free_concert'] = $this->request->getPost('free_concert') ? 1 : 0;
					$param['frequecy']					= 	$this->request->getPost('frequecy');
					$param['ip_address']				=	currentIp();
					$param['created_by']				=	(int)$this->session->get('ILCADM_ADMIN_ID');
					$param['creation_date']				= 	date('Y-m-d h:i:s');
					$param['is_active']					=	isset($data['EDITDATA']['is_active']) ? $data['EDITDATA']['is_active'] : '0';
					$param['is_boosted']				=	isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
					$param['is_featured ']				=	isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';
					$param['is_front']			    =	'1';
					$param['added_by']				= 	'user';



					// echo"<pre>";print_r($param['event_start_time']);die;
					$checkEvent						    =	$this->common_model->checkEvent($param['save_location_id'], $param['start_date'], $param['end_date'], $param['event_start_time'], $param['event_end_time']);
					if ($checkEvent) {
						$this->session->setFlashdata('alert_error', 'Event with same location, date and time already exists');
					} else {
						$alastInsertId						=	$this->common_model->addData('event_tbl', $param);
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
					
					// 	if(!empty($elast_event_data)){
					// 		$this->elastichh->addUpdateSingleEvent($elast_event_data);
					// 	}
					// }

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
						$param['event_end_time']            = 	$hour_end . ':' . $min_end . ' ' . $event_end_M;

						$combined_date_and_time = $param['start_date'] . ' ' . $param['event_start_time'];
						$param['date'] = strtotime($combined_date_and_time);
						$param['url']				        =   $this->request->getPost('url');
						$param['event_types']				=   $this->request->getPost('event_types');
						$param['user_first_name']		    = 	$this->request->getPost('user_first_name');
						$param['user_last_name']		    = 	$this->request->getPost('user_last_name');
						$param['email']				        = 	$this->request->getPost('email');
						$param['cover_url']				    =   $this->request->getPost('cover_url');
						$param['video']				        =   $this->request->getPost('video');
						$param['video2']				    =   $this->request->getPost('video2');
						$param['video3']				    =   $this->request->getPost('video3');
						$param['qr_code_link']			    =   $this->request->getPost('qr_code_link');
						$param['buy_now_link']			    =   $this->request->getPost('buy_now_link');
						$param['reserve_seat_link']			=   $this->request->getPost('reserve_seat_link');
						// $param['event_tags']			    =   $this->request->getPost('event_tags');
						$param['no_of_repeat']				= 	intOrNull($this->request->getPost('no_of_repeat'));
						$param['location_name']				= 	$this->request->getPost('location_name');
						$param['location_address']			= 	$this->request->getPost('location_address');
						$param['latitude']					= 	$this->request->getPost('latitude');
						$param['longitude']					= 	$this->request->getPost('longitude');
						$param['website']					= 	$this->request->getPost('website');
						$param['phone_number']				= 	$this->request->getPost('phone_number');
						$param['venue_id']					= 	$this->request->getPost('venue_id');
						//$param['jazz_types_id']				= 	$this->request->getPost('jazz_types_id');
						$param['artist_id']				    = 	intOrNull($this->request->getPost('artist_id'));
						$param['virtual_event_price']	    = 	floatOrNull($this->request->getPost('virtual_event_price'));
						$param['virtual_event_link']	    = 	$this->request->getPost('virtual_event_link');
						$param['cover_charge']				= 	$this->request->getPost('cover_charge');
						$param['set_time']					= 	$this->request->getPost('set_time');
						$param['time_permission']			= 	$this->request->getPost('time_permission');
						$param['repeating_event']			= 	$this->request->getPost('repeating_event');
						$param['free_concert'] = $this->request->getPost('free_concert') ? 1 : 0;
						$param['frequecy']					= 	$this->request->getPost('frequecy');
						$param['ip_address']				=	isset($data['EDITDATA']['ip_address']) ? $data['EDITDATA']['ip_address'] : '0';
						$param['created_by']				=	(int)$this->session->get('ILCADM_ADMIN_ID');
						$param['creation_date']				= 	isset($data['EDITDATA']['creation_date']) ? $data['EDITDATA']['creation_date'] : '0';
						$param['is_active']					=	isset($data['EDITDATA']['is_active']) ? $data['EDITDATA']['is_active'] : '0';
						$param['is_boosted']				=	isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
						$param['is_featured ']				=	isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';
						$param['is_front']			    =	'1';
						$param['added_by']				= 	'user';

						$param['virtual_event']	    = 	$this->request->getPost('virtual_event');
						$param['boost_days']	    = 	isset($data['EDITDATA']['boost_days']) ? $data['EDITDATA']['boost_days'] : '0';
						if(!empty($data['EDITDATA']['boost_date'])) {
							$param['boost_date']	= $data['EDITDATA']['boost_date'];
						}
						$param['requested_boost']	    = 	isset($data['EDITDATA']['requested_boost']) ? $data['EDITDATA']['requested_boost'] : '0';
						$param['event_source']	    = 	isset($data['EDITDATA']['event_source']) ? $data['EDITDATA']['event_source'] : '0';
						$param['event_source_id']	    = 	isset($data['EDITDATA']['event_source_id']) ? $data['EDITDATA']['event_source_id'] : '0';
						$param['event_source_image']	    = 	isset($data['EDITDATA']['event_source_image']) ? $data['EDITDATA']['event_source_image'] : '0';
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
						// if($alastInsertId){
						// 	$elast_event_data = $this->elastic_model->getEventFromId($alastInsertId);
					
						// 	if(!empty($elast_event_data)){
						// 		$this->elastichh->addUpdateSingleEvent($elast_event_data);
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

					$param['url']				        =   $this->request->getPost('url');
					$param['event_types']				=   $this->request->getPost('event_types');
					$param['user_first_name']		    = 	$this->request->getPost('user_first_name');
					$param['user_last_name']		    = 	$this->request->getPost('user_last_name');
					$param['email']				        = 	$this->request->getPost('email');
					$param['cover_url']				    =   $this->request->getPost('cover_url');
					$param['video']				        =   $this->request->getPost('video');
					$param['video2']				    =   $this->request->getPost('video2');
					$param['video3']				    =   $this->request->getPost('video3');
					$param['qr_code_link']			    =   $this->request->getPost('qr_code_link');
					$param['buy_now_link']			    =   $this->request->getPost('buy_now_link');
					$param['reserve_seat_link']			=   $this->request->getPost('reserve_seat_link');
					// $param['event_tags']			    =   $this->request->getPost('event_tags');
					$param['no_of_repeat']				= 	intOrNull($this->request->getPost('no_of_repeat'));
					$param['location_name']				= 	$this->request->getPost('location_name');
					$param['location_address']			= 	$this->request->getPost('location_address');
					$param['latitude']					= 	$this->request->getPost('latitude');
					$param['longitude']					= 	$this->request->getPost('longitude');
					$param['website']					= 	$this->request->getPost('website');
					$param['phone_number']				= 	$this->request->getPost('phone_number');
					$param['venue_id']					= 	$this->request->getPost('venue_id');
					//$param['jazz_types_id']				= 	$this->request->getPost('jazz_types_id');
					$param['artist_id']				    = 	intOrNull($this->request->getPost('artist_id'));
					$param['virtual_event_price']	    = 	floatOrNull($this->request->getPost('virtual_event_price'));
					$param['virtual_event_link']	    = 	$this->request->getPost('virtual_event_link');
					$param['cover_charge']				= 	$this->request->getPost('cover_charge');
					$param['set_time']					= 	$this->request->getPost('set_time');
					$param['time_permission']			= 	$this->request->getPost('time_permission');
					$param['repeating_event']			= 	$this->request->getPost('repeating_event');
					$param['free_concert'] = $this->request->getPost('free_concert') ? 1 : 0;
					$param['frequecy']					= 	$this->request->getPost('frequecy');
					$param['ip_address']				=	isset($data['EDITDATA']['ip_address']) ? $data['EDITDATA']['ip_address'] : '0';
					$param['created_by']				=	(int)$this->session->get('ILCADM_ADMIN_ID');
					$param['creation_date']				= 	isset($data['EDITDATA']['creation_date']) ? $data['EDITDATA']['creation_date'] : '0';
					$param['is_active']					=	isset($data['EDITDATA']['is_active']) ? $data['EDITDATA']['is_active'] : '0';
					$param['is_boosted']				=	isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
					$param['is_featured ']				=	isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';
					$param['is_front']			        =	'1';
					$param['added_by']				= 	'user';

					$param['virtual_event']	    = 	$this->request->getPost('virtual_event');
					$param['boost_days']	    = 	isset($data['EDITDATA']['boost_days']) ? $data['EDITDATA']['boost_days'] : '0';
					if(!empty($data['EDITDATA']['boost_date'])) {
						$param['boost_date']	= $data['EDITDATA']['boost_date'];
					}
					$param['requested_boost']	    = 	isset($data['EDITDATA']['requested_boost']) ? $data['EDITDATA']['requested_boost'] : '0';
					$param['event_source']	    = 	isset($data['EDITDATA']['event_source']) ? $data['EDITDATA']['event_source'] : '0';
					$param['event_source_id']	    = 	isset($data['EDITDATA']['event_source_id']) ? $data['EDITDATA']['event_source_id'] : '0';
					$param['event_source_image']	    = 	isset($data['EDITDATA']['event_source_image']) ? $data['EDITDATA']['event_source_image'] : '0';
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
				
					// 	if(!empty($elast_event_data)){
					// 		$this->elastichh->addUpdateSingleEvent($elast_event_data);
					// 	}
					// }

					$this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));
				}
				$this->session->setFlashdata('alert_success', lang('statictext_lang.updatesuccess'));
			endif;
			// echo"<pre>";print_r($_POST);die;
			return redirect()->to(getCurrentControllerPath('index'));

		endif;
		$data['location'] = $this->common_model->getLocation(false);
		$data['venues'] = $this->common_model->getCategory(false);
		// echo "<pre>"; print_r($data['venues']); die();
		$data['jazzTypes'] = $this->common_model->getCategoryJazz(false);

		// echo "<pre>"; print_r($data['jazzTypes']); die();
		$data['artistTypes'] = $this->common_model->getCategoryArtist(false);
		// echo "<pre>"; print_r($data['artistTypes']); die();
		$this->layouts->set_title('Manage Event Submitted List');
		$this->layouts->admin_view('event_submitted_list/addeditdata', array(), $data);
	}

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

		$param = $this->common_model->getEventData((int)$changeStatusId);

		// Send email based on the updated event details
		if ($statusType == 1) {
			// also add it in elastic index 

			// if($changeStatusId){
			// 	$elast_event_data = $this->elastic_model->getEventFromId($changeStatusId);
			
			// 	if(!empty($elast_event_data)){
			// 		$this->elastichh->addUpdateSingleEvent($elast_event_data);
			// 	}
			// }
			$this->emailTemplateModel->sendEventActive($param);//commeting as id gets deleted and user gets old id
		}
		// echo"<pre>";print_r($eventDetails);die;

		$this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}

	/***********************************************************************
	 ** Function name 	: deletedata
	 ** Developed By 	: Afsar Ali
	 ** Purpose  		: This function used for delete data
	 ** Date 			: 27 JUNE 2022
	 ************************************************************************/
	// function deletedata($deleteId='')
	// {  
	// 	$this->admin_model->authCheck('delete_data');

	// 	$this->common_model->deleteData('event_tbl','id',(int)$deleteId);
	// 	$this->session->setFlashdata('alert_success',lang('statictext_lang.deletesuccess'));

	// 	return redirect()->to(correctLink('userILCADMData',getCurrentControllerPath('index')));
	// }
	function deletedata($deleteId = '')
	{
		$deleteId = $this->uri->getSegment(4);
		$this->admin_model->authCheck('delete_data');

		$this->common_model->deleteData('event_tbl', 'event_id', (int)$deleteId);
		// $this->elastichh->deleteSingleEventFromIndex('events', (int)$deleteId);

		$this->session->setFlashdata('alert_success', lang('statictext_lang.deletesuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
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


	function mutlipleChangeStatus()
	{
		$changeStatusIds = json_decode($_POST['changeStatusIds']);
		$statusType = $_POST['statusType'];
		// echo $statusType; die;
		// print_r($changeStatusIds); die;
		if ($statusType !== "permanentdelete") {
			$this->admin_model->authCheck('edit_data');
			// foreach ($changeStatusIds as $changeStatusId) {
			// 	// $param['is_active'] = $statusType;
			// 	if ($statusType == 'inactive') {
			// 		$param['is_active'] = "0";
			// 	} else if ($statusType == 'unboost') {
			// 		$param['is_boosted'] = "0";
			// 	} else if ($statusType == 'boost') {
			// 		$param['is_boosted'] = "1";
			// 	} else if ($statusType == 'unfeatured') {
			// 		$param['is_featured'] = "0";
			// 	} else if ($statusType == 'featured') {
			// 		$param['is_featured'] = "1";
			// 	}

			// 	if ($statusType == 'importEvent' || $statusType == 'active') {
			// 		// $param['is_active'] = "1";$param['is_imported'] = "1";
			// 		$param['is_imported'] = "1";
			// 		$this->common_model->editData('event_tbl', $param, 'event_id', (int)$changeStatusId);
			// 		$param['is_active'] = "1";
			// 		$this->common_model->editData('event_tbl', $param, 'event_id', (int)$changeStatusId);

			// 		// if($changeStatusId){
			// 		// 	$elast_event_data = $this->elastic_model->getEventFromId($changeStatusId);
		
			// 		// 	if(!empty($elast_event_data)){
			// 		// 		$this->elastichh->addUpdateSingleEvent($elast_event_data);
			// 		// 	}
			// 		// }
			// 		// $this->emailTemplateModel->sendEventActive($param);//commeting as id gets deleted and user gets old id
			// 	}


			// 	$this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));
			// }
			foreach ($changeStatusIds as $changeStatusId) {
				$param = []; // Reset array for each loop
			
				if ($statusType == 'inactive') {
					$param['is_active'] = "0";
				} elseif ($statusType == 'unboost') {
					$param['is_boosted'] = "0";
				} elseif ($statusType == 'boost') {
					$param['is_boosted'] = "1";
				} elseif ($statusType == 'unfeatured') {
					$param['is_featured'] = "0";
				} elseif ($statusType == 'featured') {
					$param['is_featured'] = "1";
				} elseif ($statusType == 'importEvent' ) {
					$param['is_imported'] = "1";
					
				} elseif ($statusType == 'active' ) {
					$param['is_active'] = "1";
				}
				if (!empty($param)) {
					$this->common_model->editData('event_tbl', $param, 'event_id', (int)$changeStatusId);
				}
			}
			
		} else {
			foreach ($changeStatusIds as $changeStatusId) {
				$this->admin_model->authCheck('delete_data');
				$this->common_model->deleteData('event_tbl', 'event_id', (int)$changeStatusId);
				// $this->elastichh->deleteSingleEventFromIndex('events', (int)$changeStatusId);

				$this->session->setFlashdata('alert_success', lang('statictext_lang.deletesuccess'));
			}
		}

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

	function updateStatus($id = '')
	{
		$id = $this->uri->getSegment(4); 
		
		$param['is_active'] = '2';

		$whereCon = "event_id = '" . $id . "' ";
		$this->common_model->editMultipleDataByMultipleCondition('event_tbl', $param, $whereCon);
		$this->session->setFlashdata('alert_success', lang('statictext_lang.deletesuccess'));

		return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
	}

	// public function export_excel()
	// {
	// 	require_once APPPATH . 'third_party/classes/PHPExcel.php';
	// 	$eventData = $this->admin_model->getEvents();
	// 	usort($eventData, function ($a, $b) {
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
	// 		->setCellValue('A1', 'ID')
	// 		->setCellValue('B1', 'User First Name')
	// 		->setCellValue('C1', 'User Last Name')
	// 		->setCellValue('D1', 'Email');

	// 	$row = 2;
	// 	foreach ($eventData as $item) {
	// 		$objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $item['event_id']);
	// 		$objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $item['user_first_name']);
	// 		$objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $item['user_last_name']);
	// 		$objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $item['email']);
	// 		$row++;
	// 	}

	// 	$objPHPExcel->setActiveSheetIndex(0);

	// 	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	// 	header('Content-Disposition: attachment;filename="exported_data.xlsx"');
	// 	header('Cache-Control: max-age=0');

	// 	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	// 	$objWriter->save('php://output');
	// 	exit;
	// }
	public function export_excel() {
		//require_once APPPATH . 'third_party/classes/PHPExcel.php';
		require_once ROOTPATH . 'vendor/autoload.php'; // Ensure autoload is included

		// ✅ Fetch Data
		$eventData = $this->admin_model->getEvents();

        // ✅ Sort Data by `creation_date` (latest first)
        usort($eventData, function($a, $b) {
            return strtotime($b['creation_date']) - strtotime($a['creation_date']);
        });

        // ✅ Create a New Spreadsheet
        $objPHPExcel = new Spreadsheet();
		
	
		// ✅ Set Document Properties
		$objPHPExcel->getProperties()->setCreator("Your Name")
			 ->setLastModifiedBy("Your Name")
			 ->setTitle("Exported Data")
			 ->setSubject("Exported Data")
			 ->setDescription("Excel file generated from exported data")
			 ->setKeywords("excel phpspreadsheet codeigniter")
			 ->setCategory("Data Export");
        
	     // ✅ Set Active Sheet and Headers
		 $objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'ID')
			->setCellValue('B1', 'User First Name')
			->setCellValue('C1', 'User Last Name')
			->setCellValue('D1', 'Email');

		// ✅ Insert Data into Sheet
        $row = 2;
        foreach ($eventData as $item) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $item['event_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $item['user_first_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $item['user_last_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $item['email']);
            $row++;
        }
	
		 // ✅ Set Active Sheet Index
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
