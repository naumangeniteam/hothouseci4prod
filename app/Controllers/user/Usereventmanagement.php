<?php
namespace App\Controllers\user;

use App\Controllers\BaseController;

class Usereventmanagement extends BaseController
{

    public function  __construct()
    {
        
        error_reporting(0);
        $this->load->model(array('admin_model', 'emailtemplate_model', 'sms_model', 'notification_model', 'elastic_model'));
        // $this->lang = service('language'); 
$this->lang->setLocale('admin');
        helper('common');
        //$this->load->library('elastichh');
    }



    public function index()
    {
        //echo"here"; die;
        $date = date("Y-m-d");
        $user_id = $this->session->get('user_id'); // Get user_id from session
        // echo"<pre>";print_r($user_id );die;
        $data['error']                         =     '';
        $data['activeMenu']                 =     'usereventmanagement';
        $data['activeSubMenu']                 =     'usereventmanagement';

        if ($this->request->getGet('searchValue')) :
            $sValue                            =    $this->request->getGet('searchValue');
            $whereCon['like']                 =     "(ftable.event_title LIKE '%" . $sValue . "%'
												)";
            $data['searchField']             =     $sField;
            $data['searchValue']             =     $sValue;
        else :
            $whereCon['like']                 =     "";
            $data['searchField']             =     '';
            $data['searchValue']             =     '';
        endif;

        //$whereCon['where']		 			= 	"start_date >= '$date' ";
        $whereCon['where'] = "ftable.start_date >= '$date' AND ftable.created_by = '$user_id'";
        $shortField                         =     "ftable.user_event_id DESC";
        $baseUrl                             =     base_url() . 'user/eventmanagement/index';
        $this->session->set('userILCADMData', currentFullUrl());
        $qStringdata                        =    explode('?', currentFullUrl());
        $suffix                                =     $qStringdata[1] ? '?' . $qStringdata[1] : '';
        $tblName                             =     'user_event_tbl as ftable';
        $con                                 =     '';
        $totalRows                             =     $this->common_model->getData_event('count', $tblName, $whereCon, $shortField, '0', '0');

        if ($this->request->getGet('showLength') == 'All') :
            $perPage                         =     $totalRows;
            $data['perpage']                 =     $this->request->getGet('showLength');
        elseif ($this->request->getGet('showLength')) :
            $perPage                         =     $this->request->getGet('showLength');
            $data['perpage']                 =     $this->request->getGet('showLength');
        else :
            $perPage                         =     20;
            $data['perpage']                 =     20;
        endif;
        $uriSegment                         =     getUrlSegment();
        $data['PAGINATION']                    =    adminPagination($baseUrl, $suffix, $totalRows, $perPage, $uriSegment);

        if ($this->request->getUri()->getSegment($uriSegment)) :
            $page = $this->request->getUri()->getSegment($uriSegment);
        else :
            $page = 0;
        endif;

        $data['forAction']                     =     $baseUrl;
        if ($totalRows) :
            $first                            =    (int)($page) + 1;
            $data['first']                    =    $first;

            if ($data['perpage'] == 'All') :
                $pageData                     =    $totalRows;
            else :
                $pageData                     =    $data['perpage'];
            endif;

            $last                            =    ((int)($page) + $pageData) > $totalRows ? $totalRows : ((int)($page) + $pageData);
            $data['noOfContent']            =    'Showing ' . $first . '-' . $last . ' of ' . $totalRows . ' items';
        else :
            $data['first']                    =    1;
            $data['noOfContent']            =    '';
        endif;

        $data['ALLDATA']                     =     $this->common_model->getData_event('multiple', $tblName, $whereCon, $shortField, $perPage, $page);
        // echo"<pre>";print_r($data['ALLDATA']);die;
        $data['venues'] = $this->common_model->getCategory();
        $data['cities'] = $this->common_model->getCategoryCity();
        $data['states'] = $this->common_model->getCategoryState();
        // echo"<pre>";print_r($data['cities']);die;
        $data['jazzTypes'] = $this->common_model->getCategoryJazz();
        $data['artistTypes'] = $this->common_model->getCategoryArtist();
        // echo "<pre>"; print_r($data['artistTypes']); die();
        $data['events'] = $this->common_model->totalEvents();
        // echo"<pre>";print_r($data['events']);die;
        $data['newEvents'] = $this->common_model->newEventsForCurrentMonth();
        // $data['newEventsCount'] = count($data['newEvents']);
        $data['trashevent'] = $this->common_model->totalTrashevent();
        $data['newTrashEvents'] = $this->common_model->trashEventsCurrentMonth();
        $data['publishevent'] = $this->common_model->totalPublishevent();
        $this->layouts->set_title('Manage Event');
        $this->layouts->user_view('user_event/index', array(), $data);
    }



    public function addeditdata($editId = '')
    {
       // echo"<pre>"; print_r($editId);die;
        $data['error']                         =     '';
        $data['activeMenu']                 =     'usereventmanagement';
        $data['activeSubMenu']                 =     'usereventmanagement';

        if ($editId) :
            $data['EDITDATA']        =    $this->common_model->getDataByParticularField('user_event_tbl', 'user_event_id', (int)$editId);
            //echo"<pre>";print_r( $data['EDITDATA']);die;
            $data['EDITDATAEVENT']    =    $this->common_model->getAllDataByParticularField('user_event_tags_tbl', 'user_event_id', (int)$editId);
            $data['SELECTED_JAZZ_TYPES'] = $this->common_model->getAllDataByParticularField('user_event_jazz_tbl', 'user_event_id', (int)$editId);
            if (!empty($data['SELECTED_JAZZ_TYPES']) && is_array($data['SELECTED_JAZZ_TYPES'])) {
				$data['EDITDATA']['jazz_types_id'] = array_column($data['SELECTED_JAZZ_TYPES'], 'event_jazz_types_id');
			} else {
				$data['EDITDATA']['jazz_types_id'] = []; // Set an empty array to prevent errors
			}
        endif;
        if ($this->request->getPost('SaveChanges')) :
           // echo "<pre>"; print_r($this->request->getPost()); die;
            $error                    =    'NO';
            $this->form_validation->set_rules('event_title', 'event Title', 'trim|required');
            $this->form_validation->set_rules('save_location_id', 'Location', 'trim|required');
            $this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
            $this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
            $this->form_validation->set_rules('location_name', 'Location Name', 'trim|required');
            $this->form_validation->set_rules('location_address', 'Location Address', 'trim|required');
            $this->form_validation->set_rules('latitude', 'Latitude', 'trim|required');
            $this->form_validation->set_rules('longitude', 'Longitude', 'trim|required');
            $this->form_validation->set_rules('venue_id', 'Venue', 'trim|required');

            if ($this->form_validation->run() && $error == 'NO') :
                if (!empty($_FILES['image']['name'])) {
                    $config = [
                        'upload_path'   => './assets/front/img/eventimage',
                        'allowed_types' => 'jpg|png|gif|jpeg|webp',
                    ];
                    $this->load->library('upload', $config);
                    $image = '';
                    if ($this->upload->do_upload('image')) {
                        $img_data = $this->upload->data();
                        $param['image'] = $img_data['file_name'];
                    }
                }
                if (!empty($_FILES['cover_image']['name'])) {
                    $config = [
                        'upload_path'   => './assets/front/img/eventimage',
                        'allowed_types' => 'jpg|png|gif|jpeg|webp',
                    ];
                    $this->load->library('upload', $config);
                    $image = '';
                    if ($this->upload->do_upload('cover_image')) {
                        $img_data = $this->upload->data();
                        $param['cover_image'] = $img_data['file_name'];
                    } else {
                        if (!empty($cover_existing_image)) {
                            $param['cover_image'] = $cover_existing_image; // Use the existing image
                        }
                    }
                }
                $num_weeks = $this->request->getPost('no_of_repeat');
                $start_date = $this->request->getPost('start_date');

                $endd_date = $this->request->getPost('end_date');
                $week_dates = array();
                // common in all 
                $param['event_title']                =     $this->request->getPost('event_title');
                $hour                                 =     $this->request->getPost('event_start_hour');
                $min                                =     $this->request->getPost('event_start_min');
                $event_start_M                        =     $this->request->getPost('event_start_M');
                $hour_end                             =     $this->request->getPost('event_end_hour');
                $min_end                            =     $this->request->getPost('event_end_min');
                $event_end_M                        =     $this->request->getPost('event_end_M');
                $param['save_location_id']            =     $this->request->getPost('save_location_id');
                $param['description']                =     $this->request->getPost('description');
                $param['event_start_time']            =     $hour . ':' . $min . ' ' . $event_start_M;
                $param['event_end_time']            =     $hour_end . ':' . $min_end . ' ' . $event_end_M;
                $combined_date_and_time = $param['start_date'] . ' ' . $param['event_start_time'];
                $param['date'] = strtotime($combined_date_and_time);
                $param['event_types']                =   $this->request->getPost('event_types');
                $param['url']                        =   $this->request->getPost('url');
                $param['cover_url']                    =   $this->request->getPost('cover_url');
                $param['cover_image']                =   $this->request->getPost('cover_image');
                $param['video']                        =   $this->request->getPost('video');
                $param['video2']                    =   $this->request->getPost('video2');
                $param['video3']                    =   $this->request->getPost('video3');
                $param['qr_code_link']                =   $this->request->getPost('qr_code_link');
                $param['buy_now_link']                =   $this->request->getPost('buy_now_link');
                $param['reserve_seat_link']            =   $this->request->getPost('reserve_seat_link');
                $param['no_of_repeat']                =     $this->request->getPost('no_of_repeat');
                $param['location_name']                =     $this->request->getPost('location_name');
                $param['location_address']            =     $this->request->getPost('location_address');
                $param['latitude']                    =     $this->request->getPost('latitude');
                $param['longitude']                    =     $this->request->getPost('longitude');
                $param['website']                    =     $this->request->getPost('website');
                $param['phone_number']                =     $this->request->getPost('phone_number');
                $param['venue_id']                    =     $this->request->getPost('venue_id');
                $param['artist_id']                    =     $this->request->getPost('artist_id');
                $param['virtual_event_price']        =     $this->request->getPost('virtual_event_price');
                $param['virtual_event_link']        =     $this->request->getPost('virtual_event_link');
                $param['cover_charge']                =     $this->request->getPost('cover_charge');
                $param['set_time']                    =     $this->request->getPost('set_time');
                $param['time_permission']            =     $this->request->getPost('time_permission');
                $param['repeating_event']            =     $this->request->getPost('repeating_event');
                $param['frequecy']                    =     $this->request->getPost('frequecy');
                $param['ip_address']                =    currentIp();
                $param['created_by']                =    (int)$this->session->get('user_id');
               // echo "<pre>"; print_r($this->request->getPost()); die;
                if ($this->request->getPost('CurrentDataID') == '') :
                   // echo"herere1";die;
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
                            $param['start_date']                =     $week_start;
                            $param['end_date']                    =     $week_start;
                            $param['creation_date']                =     date('Y-m-d h:i:s');
                            $param['is_active']                    =    '1';
                            $param['is_boosted']                =    isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
                            $param['is_featured ']                =    isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';

                            $alastInsertId                        =    $this->common_model->addData('user_event_tbl', $param);
                            $this->processEventDetails($alastInsertId, false);
                            $this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));
                        }
                    } else {
                        $param['start_date']                =     $this->request->getPost('start_date');
                        $param['end_date']                    =     $this->request->getPost('end_date');
                        $param['creation_date']                =     date('Y-m-d h:i:s');
                        $param['is_active']                    =    isset($data['EDITDATA']['is_active']) ? $data['EDITDATA']['is_active'] : '1';
                        $param['is_imported']                =    isset($data['EDITDATA']['is_imported']) ? $data['EDITDATA']['is_imported'] : 0;
                        $param['is_boosted']                =    isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
                        $param['is_featured ']                =    isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';
                        $param['event_source']                =     isset($data['EDITDATA']['event_source']) ? $data['EDITDATA']['event_source'] : '';
                        $param['event_source_id']            =     isset($data['EDITDATA']['event_source_id']) ? $data['EDITDATA']['event_source_id'] : '';
                        $param['event_source_image']        =     isset($data['EDITDATA']['event_source_image']) ? $data['EDITDATA']['event_source_image'] : '';

                        $checkEvent                            =    $this->common_model->checkEvent($param['save_location_id'], $param['start_date'], $param['end_date'], $param['event_start_time'], $param['event_end_time']);
                        if ($checkEvent) {
                            $this->session->setFlashdata('alert_error', 'Event with same location, date and time already exists');
                        } else {
                            $alastInsertId                    =    $this->common_model->addData('user_event_tbl', $param);
                        }
                        $this->processEventDetails($alastInsertId, false);
                        $this->session->setFlashdata('alert_success', lang('statictext_lang.addsuccess'));
                    }
                else :
                   
                    if ($this->request->getPost('repeating_event') == 'Yes') {
                        for ($i = 0; $i < $num_weeks; $i++) {
                            if ($this->request->getPost('frequecy') == 'weekly') {
                                $week_start = date("Y-m-d", strtotime("+" . $i . " week", strtotime($start_date)));
                                $week_end = date("Y-m-d", strtotime("+" . ($i + 1) . " week - 1 day", strtotime($start_date)));
                                $week_endd = date("Y-m-d", strtotime("+" . $i . " week", strtotime($endd_date)));
                                $week_dates[$i] =  $week_start;
                                $param['start_date']                =     $week_start;
                                $param['end_date']                    =     $week_endd;
                            } else {
                                $week_start = date("Y-m-d", strtotime("+" . $i . " day", strtotime($start_date)));
                                $week_endd = date("Y-m-d", strtotime("+" . $i . " day", strtotime($endd_date)));
                                $week_dates[$i] = $week_start;
                                $param['start_date']                =     $week_start;
                                $param['end_date']                    =     $week_endd;
                            }
                            $param['creation_date']                =     isset($data['EDITDATA']['creation_date']) ? $data['EDITDATA']['creation_date'] : '0';
                            $param['is_active']                =    isset($data['EDITDATA']['is_active']) ? $data['EDITDATA']['is_active'] : '0';
                            $param['is_boosted']                =    isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
                            $param['is_featured ']                =    isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';
                            $param['is_imported']                    =    isset($data['EDITDATA']['is_imported']) ? $data['EDITDATA']['is_imported'] : 0;
                            $param['virtual_event']        =     $this->request->getPost('virtual_event');
                            $param['boost_days']        =     isset($data['EDITDATA']['boost_days']) ? $data['EDITDATA']['boost_days'] : '0';
                            $param['boost_date']        =     isset($data['EDITDATA']['boost_date']) ? $data['EDITDATA']['boost_date'] : '0';
                            $param['requested_boost']        =     isset($data['EDITDATA']['requested_boost']) ? $data['EDITDATA']['requested_boost'] : '0';
                            $param['ticket_status_code']        =     isset($data['EDITDATA']['ticket_status_code']) ? $data['EDITDATA']['ticket_status_code'] : '0';
                            $param['event_source']                =     isset($data['EDITDATA']['event_source']) ? $data['EDITDATA']['event_source'] : '';
                            $param['event_source_id']            =     isset($data['EDITDATA']['event_source_id']) ? $data['EDITDATA']['event_source_id'] : '';
                            $param['event_source_image']        =     isset($data['EDITDATA']['event_source_image']) ? $data['EDITDATA']['event_source_image'] : '';

                            $alastInsertId                        =    $this->common_model->editData('user_event_tbl', $param, 'user_event_id', (int)$editId);

                            $this->processEventDetails($editId, true);
                            $this->session->setFlashdata('alert_success', lang('statictext_lang.updatesuccess'));
                        }
                    } else {
                        //echo"herere2";die;
                        $param['start_date']                =     $this->request->getPost('start_date');
                        $param['end_date']                    =     $this->request->getPost('end_date');
                        $param['creation_date']                =     isset($data['EDITDATA']['creation_date']) ? $data['EDITDATA']['creation_date'] : '0';
                        $param['is_active']                =    isset($data['EDITDATA']['is_active']) ? $data['EDITDATA']['is_active'] : '0';
                        $param['is_boosted']                =    isset($data['EDITDATA']['is_boosted']) ? $data['EDITDATA']['is_boosted'] : '0';
                        $param['is_featured ']                =    isset($data['EDITDATA']['is_featured']) ? $data['EDITDATA']['is_featured'] : '0';
                        $param['is_imported']                    =    isset($data['EDITDATA']['is_imported']) ? $data['EDITDATA']['is_imported'] : 0;
                        $param['virtual_event']        =     $this->request->getPost('virtual_event');
                        $param['boost_days']        =     isset($data['EDITDATA']['boost_days']) ? $data['EDITDATA']['boost_days'] : '0';
                        $param['boost_date']        =     isset($data['EDITDATA']['boost_date']) ? $data['EDITDATA']['boost_date'] : '0';
                        $param['requested_boost']        =     isset($data['EDITDATA']['requested_boost']) ? $data['EDITDATA']['requested_boost'] : '0';
                        $param['ticket_status_code']        =     isset($data['EDITDATA']['ticket_status_code']) ? $data['EDITDATA']['ticket_status_code'] : '0';
                        $param['event_source']                =     isset($data['EDITDATA']['event_source']) ? $data['EDITDATA']['event_source'] : '';
                        $param['event_source_id']            =     isset($data['EDITDATA']['event_source_id']) ? $data['EDITDATA']['event_source_id'] : '';
                        $param['event_source_image']        =     isset($data['EDITDATA']['event_source_image']) ? $data['EDITDATA']['event_source_image'] : '';

                        $alastInsertId                        =    $this->common_model->editData('user_event_tbl', $param, 'user_event_id', (int)$editId);

                        $this->processEventDetails($editId, true);
                        $this->session->setFlashdata('alert_success', lang('statictext_lang.updatesuccess'));
                    }

                endif;
                // echo"<pre>";print_r($_POST);die;
                return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
            endif;
        endif;
        $data['location'] = $this->common_model->getLocation();
        $data['venues'] = $this->common_model->getCategory();
        // echo "<pre>"; print_r($data['venues']); die();
        $data['jazzTypes'] = $this->common_model->getCategoryJazz();
        // echo "<pre>"; print_r($data['jazzTypes']); die();
        $data['artistTypes'] = $this->common_model->getCategoryArtist();
        $data['eventTypes'] = $this->common_model->getEventlocationtype();
        // echo "<pre>"; print_r($data['eventTypes']); die();

        $this->layouts->set_title('Manage Event');
        //echo"hererere";die;
        $this->layouts->user_view('user_event/addeditdata', array(), $data);
    }

    private function processEventDetails($eventId, $isEdit = false)
    {
        
        // Process event tags
        $event_tags_input = $this->request->getPost('event_tags');
       // echo"<pre>";print_r($event_tags_input);die;
        if ($isEdit && $event_tags_input) {
            // Delete existing event tags only if new tags are provided
            $this->common_model->deleteUserEventTags((int)$eventId);
        }
        if ($event_tags_input) {
            $event_tags_array = array_map('trim', explode(',', $event_tags_input));
            foreach ($event_tags_array as $event_tag) {
                $p_array = [
                    'user_event_id' => $eventId,
                    'event_tags' => $event_tag,
                    'is_active' => '1',
                ];
                $this->common_model->addData('user_event_tags_tbl', $p_array);
            }
        }

        // Process jazz types
        $jazz_types_ids = $this->request->getPost('jazz_types_id');
        if ($isEdit && !empty($jazz_types_ids)) {
            // Delete existing jazz data only if new data is provided
            $this->common_model->deleteUserEventJazzs((int)$eventId);
        }
        if (!empty($jazz_types_ids)) {
            foreach ($jazz_types_ids as $jazz_type_id) {
                $jazz_data = [
                    'user_event_id' => $eventId,
                    'event_jazz_types_id' => (int)$jazz_type_id,
                ];
                $this->common_model->addData('user_event_jazz_tbl', $jazz_data);
            }
        }

        // Update ElasticSearch data
        // $elast_event_data = $this->elastic_model->getEventFromId($eventId);
        // if (!empty($elast_event_data)) {
        // 	$this->elastichh->addUpdateSingleEvent($elast_event_data);
        // }
    }




    function changestatus($changeStatusId = '', $statusType = '')
    {
        //$this->admin_model->authCheck('edit_data');
        $param['is_active']        =    $statusType;

        $this->common_model->editData('user_event_tbl', $param, 'user_event_id', (int)$changeStatusId);
        $this->session->setFlashdata('alert_success', lang('statictext_lang.statussuccess'));

        return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
    }

    function deletedata($deleteId = '')
    {
       // $this->admin_model->authCheck('delete_data');

        $this->common_model->deleteData('user_event_tbl', 'user_event_id', (int)$deleteId);
        $this->common_model->deleteData('user_event_tags_tbl', 'user_event_id', (int)$deleteId);
        $this->common_model->deleteData('user_event_jazz_tbl', 'user_event_id', (int)$deleteId);
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



    // function updateStatus($id = '')
    // {

    // 	$param['is_active'] = '2';

    // 	$whereCon = "event_id = '" . $id . "' ";
    // 	$this->common_model->editMultipleDataByMultipleCondition('event_tbl', $param, $whereCon);
    // 	$this->session->setFlashdata('alert_success', lang('statictext_lang.deletesuccess'));

    // 	return redirect()->to(correctLink('userILCADMData', getCurrentControllerPath('index')));
    // }


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
    // 	$this->common_model->addData('event_tbl', $param);

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
    // 	$this->layouts->user_view('event/import');
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
