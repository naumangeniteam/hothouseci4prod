<?php

namespace App\Controllers\front;
use App\Controllers\BaseController;
use Elastic\Elasticsearch;
use App\Models\CommonModel;
use App\Models\FrontModel;
use App\Libraries\Layouts;
use App\Libraries\Elastichh;
use App\Models\ElasticModel;

class Elastic extends BaseController
{

	protected $common_model;
    protected $layouts;
	protected $elastic_model;
	protected $validation;
	protected $session;
	protected $front_model;
	protected $elastichh;

	public function  __construct()
	{
		
		error_reporting(E_ALL ^ E_NOTICE);
		error_reporting(1);
		// $this->load->helper('url');
		// $this->load->library('elastichh');
		$this->elastichh= new Elastichh();
		$this->common_model = new CommonModel();
		$this->front_model = new FrontModel();
		$this->elastic_model = new ElasticModel();
        $this->layouts = new Layouts();
		$this->session = session();
		helper('common');
	}

	public function index()
	{
		// $arrGet = $this->request->getPost();
		// if (!empty($arrGet['e_id']) && !empty($arrGet['e_delete'])) {
		// 	$response = $this->elastichh->deleteSingleEventFromIndex('events', $arrGet['e_id']);
		// } else if (!empty($arrGet['e_id'])) {
		// 	$eventsData = $this->elastic_model->getEventFromId($arrGet['e_id']);
		// 	$response = $this->elastichh->addUpdateSingleEvent($eventsData);
		// } else if (!empty($arrGet['f_index'])) {
		// 	$this->elastichh->deleteIndex('festivals');
			// $response = $this->elastichh->index_add_mapping_festivals();
		// }else {
		// 	$this->elastichh->deleteIndex('events');
		// 	$response = $this->elastichh->index_add_mapping();
		// }
		 try {
        // Initialize the elastichh library if not already done
        $this->elastichh = new \App\Libraries\Elastichh();
        
        // Create the index and mapping
        $result = $this->elastichh->index_add_mapping();
        
        // Format response for display
        $response = [
            'success' => true,
            'message' => 'Events index created successfully',
            'data' => $result->asArray()
        ];
        
        // Return as JSON for API calls or as formatted HTML for browser
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($response);
        } else {
            // Format for browser display
            $output = '<h1>Elasticsearch Response</h1>';
            $output .= '<pre>' . json_encode($response, JSON_PRETTY_PRINT) . '</pre>';
            return $output;
        }
    } catch (\Exception $e) {
        $error = [
            'success' => false,
            'message' => 'Error creating index: ' . $e->getMessage()
        ];
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($error);
        } else {
            $output = '<h1>Elasticsearch Error</h1>';
            $output .= '<pre>' . json_encode($error, JSON_PRETTY_PRINT) . '</pre>';
            return $output;
        }
    }
		echo "<pre>";
		print_r($response);
		echo "</pre>";
	}

	public function add()
	{
		 try {
        $festParam = $this->request->getVar('fest'); // works for GET and POST

        $isFestivalRequest = !empty($festParam) && $festParam == 1;
        
        // Default to adding events if no specific request is provided
        if ($isFestivalRequest) {
            $reindexData = $this->elastic_model->getFestivalDataForIndexing();
            $results = $this->elastichh->fullReIndexingOfFestivals($reindexData);
            $message = 'Festivals data indexed successfully';
        } else {
            $eventsData = $this->elastic_model->getEventDataForIndexing();
            $results = $this->elastichh->fullReIndexingOfEvents($eventsData);
            $message = 'Events data indexed successfully';
        }
        
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $results
        ];
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($response);
        } else {
            $output = '<h1>Elasticsearch Indexing Response</h1>';
            $output .= '<pre>' . json_encode($response, JSON_PRETTY_PRINT) . '</pre>';
            return $output;
        }
    } catch (\Exception $e) {
        $error = [
            'success' => false,
            'message' => 'Error indexing data: ' . $e->getMessage()
        ];
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($error);
        } else {
            $output = '<h1>Elasticsearch Indexing Error</h1>';
            $output .= '<pre>' . json_encode($error, JSON_PRETTY_PRINT) . '</pre>';
            return $output;
        }
    }
	}

	public function list()
	{

		    try {
        $arrGet = $this->request->getPost() ?? [];
        
        // Set default parameters if no parameters are passed
        if (empty($arrGet)) {
            // Create a simple "match_all" query
            $arrGet = [
                'query' => [
                    'match_all' => new \stdClass()
                ],
                'size' => 50  // limit number of results
            ];
        }
        
        $eventsResponse = $this->elastichh->eventSearchListingData($arrGet); 
        
        // Format response for display
        $response = [
            'success' => true,
            'message' => 'Events retrieved successfully',
            'data' => $eventsResponse['hits'] ?? []
        ];
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($response);
        } else {
            // Format for browser display
            $output = '<h1>Elasticsearch Search Results</h1>';
            $output .= '<pre>' . json_encode($response, JSON_PRETTY_PRINT) . '</pre>';
            return $output;
        }
    } catch (\Exception $e) {
        $error = [
            'success' => false,
            'message' => 'Error retrieving data: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($error);
        } else {
            $output = '<h1>Elasticsearch Error</h1>';
            $output .= '<pre>' . json_encode($error, JSON_PRETTY_PRINT) . '</pre>';
            return $output;
        }
    }
	}

	public function search()
	{
		$arrGet = $this->request->getPost();
		$eventsResponse = $this->elastichh->eventSearchListingData($arrGet);
		$eventsData = $eventsResponse['hits']['hits'];
		echo "<pre>";
		print_r($eventsData);
		echo "</pre>";
		die();
	}

	public function global_search($keyword = '')
{
      // Get banner data
    $where['where'] = ['page_name' => 'Home Page', 'is_active' => '1'];
    $tbl = 'banner_tbl as ftable';
    $data['banner'] = $this->common_model->getData('multiple', $tbl, $where);
    
    // Get keyword from URL segment or query string
    if (!empty($keyword)) {
        $data['keyword'] = urldecode($keyword);
    }
    
    // Query string parameter has higher priority
    if ($this->request->getGet('keyword')) {
        $data['keyword'] = $this->request->getGet('keyword');
    }
    
    // Store in session for persistence
    if (!empty($data['keyword'])) {
        $this->session->set('search_keyword', $data['keyword']);
    }
    
    // Get location data
    $where5['where'] = "is_active = '1'";
    $tbl5 = 'venue_tbl as ftable';
    $field = 'position';
    $fieldName = 'is_active';
    $fieldValue = '1';
    $shortField5 = 'id,venue_title , image';
    $data['location_tbl'] = $this->common_model->getLastOrderByFields1('multiple', $field, $tbl5, $fieldName, $fieldValue, $shortField5);

    // Get venue data
    $where51['where'] = ['is_active' => '1'];
    $tbl51 = 'event_location_tbl as ftable';
    $shortField51 = 'location_name ASC';
    $data['venue_tbl'] = $this->common_model->getData('multiple', $tbl51, $where51, $shortField51);
    
    // Get jazz types
    $data['jazzType'] = $this->common_model->getCategoryJazz();
    
    // Load the view
    $this->layouts->front_view('global-search', array(), $data);
}



	public function multipleDataList()
	{
		$eventsResponse = $this->elastichh->eventSearchListingData();
		$eventsData = $eventsResponse['hits']['hits'];
		echo "<pre>";
		print_r($eventsData);
		echo "</pre>";
		die();
	}

public function global_search_filters() {
     try {
        $dateSelected = date('Y-m-d', strtotime($this->request->getPost('Selected_Date_') ?: 'now'));
        $arrPost = $this->request->getPost();
        $arrPost['size'] = 21;
        
        // If there's no search term but we had one in the session, use that
        if (empty($arrPost['event_title']) && $this->session->has('search_keyword')) {
            $arrPost['event_title'] = $this->session->get('search_keyword');
        }
        
        // Get events data
        $eventsResponse = $this->elastichh->eventSearchListingData($arrPost);
        $eventsData = $eventsResponse['hits'];
        $data['event_tbl'] = !empty($eventsData['hits']) ? $eventsData['hits'] : [];
    
        // Process each event to ensure date objects are valid
        foreach ($data['event_tbl'] as &$event) {
            if (isset($event['_source']['start_date'])) {
                $this->processDateInfo($event);
            }
        }
    
        // Get festivals data
        $festivalsResponse = $this->elastichh->festivalSearchListingData($arrPost);
        $festivalsData = $festivalsResponse['hits'];
        $data['festival_tbl'] = !empty($festivalsData['hits']) ? $festivalsData['hits'] : [];
        
        // Process festival dates
        foreach ($data['festival_tbl'] as &$festival) {
            if (isset($festival['_source']['start_date'])) {
                $this->processDateInfo($festival);
            }
        }
        
        // Return JSON response
        $html = view('front/global_serach_filter_elastic', $data);
        return $this->response->setJSON([
            'data' => $html, 
            'selected_date' => $dateSelected,
            'count' => [
                'events' => count($data['event_tbl']),
                'festivals' => count($data['festival_tbl'])
            ]
        ]);
    } catch (\Exception $e) {
        log_message('error', 'Search error: ' . $e->getMessage());
        return $this->response->setJSON([
            'error' => 'Search failed',
            'data' => '<div class="alert alert-danger">Search service is currently unavailable.</div>'
        ]);
    }
}
// Helper method to process date information
private function processDateInfo(&$item) 
{
    $start_date = $item['_source']['start_date'] ?? '';
    $date = \DateTime::createFromFormat('Y-m-d', $start_date);
    
    if ($date instanceof \DateTime) {
        $item['_source']['month_num'] = $date->format('m');
        $item['_source']['year'] = $date->format('Y');
        $item['_source']['day'] = $date->format('d');
        $item['_source']['month_name'] = $date->format('F');
    } else {
        // Default values if date is invalid
        $item['_source']['month_num'] = date('m');
        $item['_source']['year'] = date('Y');
        $item['_source']['day'] = date('d');
        $item['_source']['month_name'] = date('F');
    }
}

	public function calendar_filter_artist()
	{
		$arrPost = $this->request->getPost();
		$eventsResponse = $this->elastichh->eventSearchListingData($arrPost);
		$eventsData = $eventsResponse['hits'];

		// Set the current date for the view - this was missing
		$dateSelected = date('Y-m-d', strtotime($_POST['Selected_Date_'] ?? 'now'));

		$data['event_tbl'] = !empty($eventsData['hits']) ? $eventsData['hits'] : [];

		// Add the missing datae variable that the view is expecting
		$data['datae'] = $dateSelected;

		// Process dates for events if needed
		foreach ($data['event_tbl'] as &$event) {
			if (isset($event['_source']['start_date'])) {
				$this->processDateInfo($event);
			}
		}

		$html = view('front/calender_filter1', $data, true);

		return $this->response->setJSON([
			'data' => $html,
			'selected_date' => $dateSelected
		]);
	}

	public function calendar()
	{
		$data = array();
		/********************************************Banner Section******************************/
		$where['where'] 		=	['page_name' => 'Calender',  'is_active' => '1']; //"status = 'A'";
		$tbl 					=	'banner_tbl as ftable';
		$shortField 			=	'id DESC';

		$shortField1 			=	'type_name ASC';
		$data['banner'] 		= 	$this->common_model->getData('multiple', $tbl, $where);
		/********************************************About Section******************************/
		$where1['where'] 		=	"is_active = '1'"; //"status = 'A'";
		$tbl1 					=	'about_us_tbl as ftable';
		$shortField12 			=	'id DESC';

		$shortField123 			=	'type_name ASC';
		$data['about'] 		= 	$this->common_model->getData('multiple', $tbl1, $where1, $shortField12, 6, 0);
		/********************************************Our Team Section******************************/
		$where2['where'] 		=	"is_active = '1'"; //"status = 'A'";
		$tbl2 					=	'about_team_tbl as ftable';
		$shortField2 			=	'id DESC';

		$shortField222			=	'type_name ASC';
		$data['about_team_tbl'] 		= 	$this->common_model->getData('multiple', $tbl2, $where2, $shortField2, 6, 0);


		/********************************************Img Section******************************/
		$where3['where'] 		=	"is_active = '1'"; //"status = 'A'";
		$tbl3 					=	'home_image as ftable';
		$shortField2 			=	'id DESC';

		$shortField3			=	'type_name ASC';
		$data['home_image'] 		= 	$this->common_model->getData('multiple', $tbl3, $where3);


		/********************************************Our Partners Section******************************/
		$where2['where'] 		=	array('is_active' => 1, 'page' => 2); //"status = 'A'";
		$tbl2 					=	'slider_tbl as ftable';
		$data['slider_tbl'] 		= 	$this->common_model->getData('multiple', $tbl2, $where2);

		/********************************************Seo Section******************************/
		$where5['where'] 		=	['page_name' => 'Calendar Page',  'is_active' => '1'];
		$tbl5 					=	'seo_tbl as ftable';
		$shortField5			=	'id DESC';

		$shortField6 			=	'type_name ASC';
		$data['seo_section'] 		= 	$this->common_model->getData('single', $tbl5, $where5);

		/********************************************Location******************************/

		$where5['where'] 		=	"is_active = '1'"; //"status = 'A'";
		$tbl5 					=	'venue_tbl as ftable';
		$field 					=	'position';
		$fieldName 					=	'is_active';
		$fieldValue                        = '1';

		$shortField5			=	'id,venue_title , image';
		$data['location_tbl'] 		= 	$this->common_model->getLastOrderByFields1('multiple', $field, $tbl5, $fieldName, $fieldValue, $shortField5);

		/********************************************Venue******************************/

		$where51['where'] 		=	['is_active' => '1'];
		$tbl51					=	'event_location_tbl as ftable';
		//$shortField5			=	'id DESC';

		$shortField51 			=	'location_name ASC';
		$data['venue_tbl'] 		= 	$this->common_model->getData('multiple', $tbl51, $where51, $shortField51);


		/********************************************Artist******************************/

			/*	$where51['where'] 		=	[ 'is_active' =>'1'];
		$tbl51					=	'event_tbl as ftable';
		//$shortField5			=	'id DESC';
	   
		$shortField51 			=	'event_title ASC';
		$data['artist_name'] 		= 	$this->common_model->getData('multiple', $tbl51,$where51,$shortField51)*/;


		//$data['artist_name'] = $this->db->select('*')->from('event_tbl')->where('is_active','1')->order_by('event_title')->group_by('event_title')->get()->result_array();

		$data['artist_name'] 		= 	$this->front_model->event_artist();
		//  echo'<pre>';
		//  print_r($data['artist_name']);die;

		$data['event_tags'] 		= 	$this->front_model->event_artist1();
		//  echo'<pre>';
		//  print_r($data['event_tags']);die;
		//echo $this->db->last_query();die;
		// /* echo'<pre>';
		//  print_r($data['artist_name']);die;*/


		/********************************************Subscribe form******************************/
		if ($this->request->getPost('Savesubsc')) :

			$error					=	'NO';
			$this->form_validation->set_rules('email', 'Email Address', 'required');
			$this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_message('trim|required', 'Enter %s');

			if ($this->form_validation->run() && $error == 'NO') :


				$param['email']				= 	$this->request->getPost('email');
				$param['name']				= 	$this->request->getPost('name');
				$param['creation_date']				= 	date('Y-m-d h:i:s');
				$param['ip_address'] 		=	currentIp();
				$param['status']			=	'A';
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
				$subscribe = $this->common_model->subscribeEmail($param['email']);
				if (empty($subscribe)) {
					$alastInsertId				=	$this->common_model->addData('subscribe_tbl', $param);
					//$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
					$this->session->setFlashdata('alert_success', 'Details Submitted Successfully');
				} else {
					$this->session->setFlashdata('alert_error', 'Email Id already used');
				}
				redirect('calendar');
			else :
				$this->session->setFlashdata('alert_error', 'Please Enter All Details');
			endif;
		endif;

		/********************************************Event List Section******************************/

		/*$tbl6 					=	'event_tbl as ftable';
		  //$shortField6 = 'ftable.event_start_time DESC'; 
		 $where2['where'] 		=	"is_active = '1' AND date != ''";
		 $wcon['where_gte']     =   array('start_date' => date('Y-m-d') , 'end_date' => date('Y-m-d'));
		 $shortField77			=	'type_name ASC';
		 $data['event_tbl'] 	= 	$this->common_model->getData('multiple', $tbl6,$where2);*/

		$data['datae'] 		=   date('Y-m-d');

		$data['jazzType']      = $this->common_model->getCategoryJazz();
		//  echo "<pre>";print_r( $data['jazzType']);die;
		$data['artistType']      = $this->common_model->getCategoryArtist();
		// echo "<pre>";print_r( $data['artistType']);die;

		$this->layouts->set_title($data['seo_section']['title']);
		$this->layouts->set_description($data['seo_section']['description']);
		$this->layouts->set_keyword($data['seo_section']['keywords']);
		$this->layouts->front_view('calendar_elastic', array(), $data);
	}
}
