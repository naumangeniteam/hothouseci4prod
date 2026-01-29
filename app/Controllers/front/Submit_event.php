<?php

namespace App\Controllers\front;
use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\EmailtemplateModel;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\CommonModel;
use App\Libraries\Layouts;
use \DrewM\MailChimp\MailChimp;

class Submit_event extends BaseController
{
	protected $adminModel;
    protected $emailtemplateModel;
    protected $smsModel;
    protected $notificationModel;
    protected $common_model;
    protected $layouts;
	protected $validation;
	protected $db;
	protected $session;

	public function  __construct()
	{
		$this->db = \Config\Database::connect();
		// error_reporting(E_ALL ^ E_NOTICE);  
        // error_reporting(E_ALL);
		error_reporting(0);
		
		// $this->adminModel = new AdminModel();
        $this->emailtemplateModel = new EmailtemplateModel();
        // $this->smsModel = new SmsModel();
        // $this->notificationModel = new NotificationModel();
        $this->common_model = new CommonModel();
        $this->layouts = new Layouts();
        helper(['common']);
        $this->session =service('session');
		$this->validation = service('validation');
	}

	/* * *********************************************************************
	 * * Function name 	: index
	 * * Developed By 	: Megha Kumari
	 * * Purpose  		: This function for home page data
	 * * Date 			: 18/01/23
	 * * **********************************************************************/
	public function index()
	{
		
		$data = [];
		
		if (!$this->common_model) {
            throw new \Exception("Model not loaded properly!");
        }
		
		/******************************************** Banner Section ******************************/
        $data['banner'] = $this->common_model->getData(
            'multiple', 
            'banner_tbl', 
            ['page_name' => 'Submit Event', 'is_active' => '10'], 
            'id DESC', 
            6, 
            0
        );

        /******************************************** Our Partners Section ******************************/
        $data['slider_tbl'] = $this->common_model->getData(
            'multiple', 
            'slider_tbl', 
            ['is_active' => '1']
        );
		$data['location']       = $this->common_model->getLocation();
		$data['venues']         = $this->common_model->getCategory();
		$data['jazzTypes']      = $this->common_model->getCategoryJazz();
		$data['artistTypes'] = $this->common_model->getCategoryArtist();
		// echo "<pre>";print_r($_POST);die;
		helper('captcha');
		if ($this->request->getPost('captcha')) {
            if ($this->request->getPost('SaveChanges')) {
                $error = 'NO';

                // ✅ Validation Rules
                $this->validation->setRules([
                    'captcha' => [
                        'label' => 'Captcha',
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Please enter the {field}.'
                        ]
                    ],
                    'event_title' => [
                        'label' => 'Event Title',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} is required.'
                        ]
                    ],
                    'start_date' => [
                        'label' => 'Event Start Date',
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Please provide the {field}.'
                        ]
                    ],
                    'end_date' => [
                        'label' => 'Event End Date',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} cannot be empty.'
                        ]
                    ],
                    'repeating_event' => [
                        'label' => 'Repeating Event',
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Please select a {field}.'
                        ]
                    ],
                    'save_location_id' => [
                        'label' => 'Saved Venue',
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Choose a {field}.'
                        ]
                    ],
                    'location_name' => [
                        'label' => 'Event Venue Name',
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Enter the {field}.'
                        ]
                    ],
                    'location_address' => [
                        'label' => 'Event Venue Address',
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Enter the {field}.'
                        ]
                    ],
                    'latitude' => [
                        'label' => 'Latitude',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} is required.'
                        ]
                    ],
                    'longitude' => [
                        'label' => 'Longitude',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} is required.'
                        ]
                    ],
                    'venue_id' => [
                        'label' => 'Venue Selection',
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Please select a {field}.'
                        ]
                    ],
                    'contact_person' => [
                        'label' => 'Contact Person First Name',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} cannot be empty.'
                        ]
                    ],
                    'contact_lastname' => [
                        'label' => 'Contact Person Last Name',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} cannot be empty.'
                        ]
                    ],
                    'email' => [
                        'label' => 'Contact Email',
                        'rules' => 'required|valid_email',
                        'errors' => [
                            'required' => 'Enter {field}.',
                            'valid_email' => 'Please enter a valid {field}.'
                        ]
                    ]
                ]);
                

                // ✅ Spam Trap Handling
                $trap = $this->request->getPost('trap');
                if (!empty(trim($trap))) {
                    $this->session->setFlashdata('error', 'Spam detected. Submission rejected.');
                    return redirect()->to('submit_event');
                }
                
                if (!$this->validation->withRequest($this->request)->run()) {
                    $errors = $this->validation->getErrors();
                    log_message('error', 'Submit Event validation failed: ' . json_encode($errors));
                    $this->session->setFlashdata('validation', $this->validation);
                    return redirect()->to('submit_event')->withInput();
                    
                }
                

                if ($this->validation->withRequest($this->request)->run() && $error == 'NO') {
                    // ✅ Collect Form Data
                    $param = [
                        'event_title'       => $this->request->getPost('event_title'),
                        'start_date'        => $this->request->getPost('start_date'),
                        'end_date'          => $this->request->getPost('end_date'),
                        'event_start_time'  => $this->request->getPost('event_start_hour') . ':' .
                                               $this->request->getPost('event_start_min') . ' ' .
                                               $this->request->getPost('event_start_M'),
                        'event_end_time'    => $this->request->getPost('event_end_hour') . ':' .
                                               $this->request->getPost('event_end_min') . ' ' .
                                               $this->request->getPost('event_end_M'),
                        'date'              => strtotime($this->request->getPost('start_date') . ' ' .
                                               $this->request->getPost('event_start_hour') . ':' .
                                               $this->request->getPost('event_start_min') . ' ' .
                                               $this->request->getPost('event_start_M')),
                        'repeating_event'   => $this->request->getPost('repeating_event'),
                        'save_location_id'  => $this->request->getPost('save_location_id'),
                        'location_name'     => $this->request->getPost('location_name'),
                        'location_address'  => $this->request->getPost('location_address'),
                        'latitude'          => $this->request->getPost('latitude'),
                        'longitude'         => $this->request->getPost('longitude'),
                        'venue_id'          => $this->request->getPost('venue_id'),
                        // 'contact_person'    => $this->request->getPost('contact_person'),
                        // 'contact_lastname'  => $this->request->getPost('contact_lastname'),
                        'email'             => $this->request->getPost('email'),
                        'ip_address'        => $this->request->getIPAddress(),
                        'creation_date'     => date('Y-m-d h:i:s'),
                        'is_active'         => '0',
                        'is_front'          => '1',
                        'added_by'			=> 	'user',
                        'description'	    => 	$this->request->getPost('description'),
                        'website'           => $this->request->getPost('website'),
                        'phone_number'      => $this->request->getPost('phone_number')
                        
                    ];

                    // ✅ Check Date Difference (Max 90 Days)
                    if ((strtotime($param['end_date']) - strtotime($param['start_date'])) / (60 * 60 * 24) > 90) {
                        $this->session->setFlashdata('error', 'The difference between start and end dates cannot be more than 90 days.');
                        return redirect()->to('submit_event');
                    }
                    $event_tags_input = $this->request->getPost('event_tags');

                    // ✅ Insert Data into Database
                    $lastInsertId = $this->common_model->addData('event_tbl', $param);

                    log_message('info', "New event submitted. ID: $lastInsertId, Title: {$param['event_title']}, By Email: {$param['email']}");
                
					if ($event_tags_input) {
						$event_tags_array = array_map('trim', explode(',', $event_tags_input));
						if (count($event_tags_array)) {
							foreach ($event_tags_array as $event_tag) {
								$p_array = [
									'event_id' => $lastInsertId,
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
								'event_id' => $lastInsertId,
								'event_jazz_types_id' => (int)$jazz_type_id, // Cast to integer
							];
							// Insert into event_jazz_tbl
							$this->common_model->addData('event_jazz_tbl', $jazz_data);
						}
					}

                    if ($lastInsertId) {
                        $this->session->setFlashdata('success', 'Details Submitted Successfully. Please give us 24 hours to approve the posting.');
                        return redirect()->to('submit_event');
                    } else {
                        $this->session->setFlashdata('error', 'Please try again.');
                    }
                }
            }
        } else {
            $data['captchaerror'] = "Captcha doesn't match.";
        }

		// echo "<pre>";print_r($_POST);die;

		/********************************************Subscribe form******************************/
		if ($this->request->getPost('Savesubsc')) {
            $error = 'NO';

            // ✅ Validation Rules
            $this->validation->setRules([
                'email' => 'required|valid_email',
                'name'  => 'required'
            ]);

            if ($this->validation->withRequest($this->request)->run() && $error == 'NO') {
                $param = [
                    'email'         => $this->request->getPost('email'),
                    'name'          => $this->request->getPost('name'),
                    'creation_date' => date('Y-m-d h:i:s'),
                    'status'        => 'A',
                    'ip_address'    => $this->request->getIPAddress()
                ];
				//Mail Chimp API Code
				$email =  $param['email'];
				$first_name = $param['name'];
				$last_name = '';

				$api_key = getenv('MAILCHIMP_API_KEY'); // YOUR API KEY

				// server name followed by a dot. 
				// We use us13 because us13 is present in API KEY
				$server = 'us3.';

				$list_id = 'f15ad682db'; // YOUR LIST ID

				$auth = base64_encode('user:' . $api_key);

				$data = array(
					'apikey'        => $api_key,
					'email_address' => $email,
					'status'        => 'subscribed',
					'merge_fields'  => array(
						'FNAME' => $first_name,
						'LNAME'    => $last_name
					)
				);
				$json_data = json_encode($data);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://' . $server . 'api.mailchimp.com/3.0/lists/' . $list_id . '/members');
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Authorization: Basic ' . $auth
				));
				curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

				$result = curl_exec($ch);
				// echo"<pre>";print_r($result);die;

				//	$subscribe = $this->db->select('id')->from('subscribe_tbl')->where('email',$param['email'])->get()->row();
				$subscribe = $this->common_model->subscribeEmail($param['email']);

				if (empty($subscribe)) {
					$alastInsertId		=	$this->common_model->addData('subscribe_tbl', $param);
					//$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
					$this->session->setFlashdata('alert_success', 'Details Submitted Successfully');
				} else {
					$this->session->setFlashdata('alert_error', 'Email Id already used');
				}
				   return redirect()->to('submit_event');
            } else {
                $this->session->setFlashdata('alert_error', 'Please Enter All Details');
            }
		}
		
		/********************************************Seo Section******************************/

		$where5['where'] 		=	['page_name' => 'Submit Event Page',  'is_active' => '1'];
		$tbl5 					=	'seo_tbl as ftable';
		$shortField5			=	'id DESC';

		$shortField6 			=	'type_name ASC';
		$data['seo_section'] 	= 	$this->common_model->getData('single', $tbl5, $where5);


		$this->layouts->set_title($data['seo_section']['title']);
		$this->layouts->set_description($data['seo_section']['description']);
		$this->layouts->set_keyword($data['seo_section']['keywords']);
		$this->layouts->front_view('submit_event', array(), $data);
	}

	/* * *********************************************************************
	 * * Function name 	: location
	 * * Developed By 	: Ritu Mishra
	 * * Purpose  		: This function for get venue location
	 * * Date 			: 15 Mar 2023
	 * * **********************************************************************/
	function location()
	{
		$location_id = $this->request->getGet('LocationId'); // Use CI4's request method

        // if (!$location_id) {
        //     return $this->response->setJSON(['error' => 'Location ID is required']);
        // }

        $query = $this->db->table('event_location_tbl')
            ->where('id', $location_id)
            ->where('is_active', '1')
            ->get()
            ->getRow(); // Get single row

        if ($query) {
            return $this->response->setJSON([
                'location_name'   => $query->location_name,
                'location_address'=> $query->location_address,
                'latitude'        => $query->latitude,
                'longitude'       => $query->longitude,
                'website'         => $query->website,
                'phone_number'    => $query->phone_number,
                'venue_id'        => $query->venue_id
            ]);
        } else {
            return $this->response->setJSON(['error' => 'No active location found']);
        }
	}

	public function testm()
	{
		$email =  'testdfdfdfdfd@gmail.com';
		$first_name = 'test23423424';
		$last_name = '';

		$api_key = getenv('MAILCHIMP_API_KEY'); // YOUR API KEY

		// server name followed by a dot. 
		// We use us13 because us13 is present in API KEY
		$server = 'us3.';

		$list_id = 'f15ad682db'; // YOUR LIST ID

		$auth = base64_encode('user:' . $api_key);

		$data = array(
			'apikey'        => $api_key,
			'email_address' => $email,
			'status'        => 'subscribed',
			'merge_fields'  => array(
				'FNAME' => $first_name,
				'LNAME'    => $last_name
			)
		);
		$json_data = json_encode($data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://' . $server . 'api.mailchimp.com/3.0/lists/' . $list_id . '/members');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Basic ' . $auth
		));
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

		$result = curl_exec($ch);
	
	}

	// public function save_artist_name()
	// {
	// 	$this->form_validation->set_rules('artist_name', 'Artist Name', 'required');
	// 	$this->form_validation->set_rules('artist_url', 'Thumb Image Url');
	// 	$this->form_validation->set_rules('cover_url', 'Cover Image Url');
	// 	$this->form_validation->set_rules('buy_now_link', 'Buy Link');
	// 	$this->form_validation->set_rules('website_link', 'Website Link');
	// 	$this->form_validation->set_rules('artist_bio', 'Artist Bio', 'required');

	// 	if ($this->form_validation->run() == FALSE) {
	// 		$this->session->setFlashdata('error_msg', 'Failed to submit artist details');
	// 		echo json_encode(array('success' => false, 'message' => 'Failed to submit artist details'));
	// 	} else {
	// 		$param['artist_name']  = $this->request->getPost('artist_name');
	// 		$param['artist_url']   = $this->request->getPost('artist_url');
	// 		$param['cover_url']    = $this->request->getPost('cover_url');
	// 		$param['buy_now_link'] = $this->request->getPost('buy_now_link');
	// 		$param['website_link'] = $this->request->getPost('website_link');
	// 		$param['artist_bio']   = $this->request->getPost('artist_bio');
	// 		$param['is_active']    = '0';

	// 		if (empty($this->request->getPost('CurrentDataID'))) {
	// 			$lastInsertId = $this->common_model->addData('artist_tbl', $param);

	// 			if ($lastInsertId) {
	// 				$this->session->setFlashdata('success_msg', 'Artist Details Submitted Successfully');
	// 				echo json_encode(array(
	// 					'success' => true,
	// 					'id' => $lastInsertId,
	// 					'name' => $param['artist_name'],
	// 				));
	// 			} else {
	// 				$this->session->setFlashdata('error_msg', 'Failed to submit artist details');
	// 				echo json_encode(array('success' => false, 'message' => 'Failed to submit artist details'));
	// 			}
	// 		}
	// 	}
	// }
	public function save_artist_name()
{
   
    

    // $trap = $this->request->getPost('trap');

    // if (!empty($trap)) {
    //     $this->session->setFlashdata('error_msg', 'Spam detected. Submission rejected.');
    //     return redirect()->to('submit_event');
    // }

    // Fetch jazz types related to the event
    $data['SELECTED_JAZZ_TYPES'] = $this->common_model->getAllDataByParticularField('event_jazz_tbl', 'event_id');
 
  if (!empty($data['SELECTED_JAZZ_TYPES']) && is_array($data['SELECTED_JAZZ_TYPES'])) {
				$data['EDITDATA']['jazz_types_id'] = array_column($data['SELECTED_JAZZ_TYPES'], 'event_jazz_types_id');
			} else {
				$data['EDITDATA']['jazz_types_id'] = []; // Set an empty array to prevent errors
			}
   
    
    // Validation Rules
    $this->validation->setRules([
        'artist_name' => ['label' => 'Artist Name', 'rules' => 'required'],
        'artist_url' => ['label' => 'Thumb Image Url', 'rules' => 'permit_empty'],
        'cover_url' => ['label' => 'Cover Image Url', 'rules' => 'permit_empty'],
        'buy_now_link' => ['label' => 'Buy Link', 'rules' => 'permit_empty'],
        'website_link' => ['label' => 'Website Link', 'rules' => 'permit_empty'],
        'artist_bio' => ['label' => 'Artist Bio', 'rules' => 'required'],
    ]);

    if (!$this->validation->withRequest($this->request)->run()) {
        $this->session->setFlashdata('error_msg', 'Failed to submit artist details');
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to submit artist details',
        ]);
    }
$lastInsertId=0;
    // Prepare data for insertion
    $param = [
        'artist_name' => $this->request->getPost('artist_name'),
        'artist_url' => $this->request->getPost('artist_url'),
        'cover_url' => $this->request->getPost('cover_url'),
        'buy_now_link' => $this->request->getPost('buy_now_link'),
        'website_link' => $this->request->getPost('website_link'),
        'artist_bio' => $this->request->getPost('artist_bio'),
        'is_active' => '0',
    ];
    if (empty($this->request->getPost('CurrentDataID'))) {
        $this->emailtemplateModel->sendnewArtistEmail($param);
        $lastInsertId = $this->common_model->addData('artist_tbl', $param);

        if ($lastInsertId) {
            $this->emailtemplateModel->sendnewArtistEmail($param);

            $this->session->setFlashdata('success', 'Thank you for adding a new artist. Your request has been received and will be processed within 24-48 hours. After that, you can add the event.');

            return $this->response->setJSON([
                'success' => true,
            ]);
        } else {
            $this->session->setFlashdata('error', 'Failed to submit artist details');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to submit artist details',
            ]);
        }
    }

    // Handle Jazz Types ID insertion
    $jazz_types_ids = $this->request->getPost('jazz_types_id');

    if (!empty($jazz_types_ids)) {
        foreach ($jazz_types_ids as $jazz_type_id) {
            $jazz_data = [
                'event_id' => $lastInsertId,
                'event_jazz_types_id' => (int)$jazz_type_id,
            ];
            $this->common_model->addData('event_jazz_tbl', $jazz_data);
        }
    }
}

}
