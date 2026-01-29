<?php
namespace App\Controllers\front;
use App\Controllers\BaseController;


use \DrewM\MailChimp\MailChimp;

class New_events  extends BaseController
{

    public function  __construct()
    {
        
        //error_reporting(E_ALL ^ E_NOTICE);  
        error_reporting(0);
        $this->load->model(array('admin_model', 'emailtemplate_model', 'sms_model', 'notification_model', 'common_model'));
        $this->lang = service('language'); 
$this->lang->setLocale('front');
        helper('common');
    }

    public function index()
    {
        $data = array();
        /********************************************Banner Section******************************/
        $where['where']         =    ['page_name' => 'Calender',  'is_active' => '1']; //"status = 'A'";
        $tbl                     =    'banner_tbl as ftable';
        $shortField             =    'id DESC';

        $shortField1             =    'type_name ASC';
        $data['banner']         =     $this->common_model->getData('multiple', $tbl, $where);
        /********************************************About Section******************************/
        $where1['where']         =    "is_active = '1'"; //"status = 'A'";
        $tbl1                     =    'about_us_tbl as ftable';
        $shortField12             =    'id DESC';

        $shortField123             =    'type_name ASC';
        $data['about']         =     $this->common_model->getData('multiple', $tbl1, $where1, $shortField12, 6, 0);
        /********************************************Our Team Section******************************/
        $where2['where']         =    "is_active = '1'"; //"status = 'A'";
        $tbl2                     =    'about_team_tbl as ftable';
        $shortField2             =    'id DESC';

        $shortField222            =    'type_name ASC';
        $data['about_team_tbl']         =     $this->common_model->getData('multiple', $tbl2, $where2, $shortField2, 6, 0);

        /********************************************Img Section******************************/
        $where3['where']         =    "is_active = '1'"; //"status = 'A'";
        $tbl3                     =    'home_image as ftable';
        $shortField2             =    'id DESC';

        $shortField3            =    'type_name ASC';
        $data['home_image']         =     $this->common_model->getData('multiple', $tbl3, $where3);


        /********************************************Seo Section******************************/
        $where5['where']         =    ['page_name' => 'Calendar Page',  'is_active' => '1'];
        $tbl5                     =    'seo_tbl as ftable';
        $shortField5            =    'id DESC';

        $shortField6             =    'type_name ASC';
        $data['seo_section']         =     $this->common_model->getData('single', $tbl5, $where5);

        /********************************************Location******************************/

        $where5['where']         =    "is_active = '1'"; //"status = 'A'";
        $tbl5                     =    'venue_tbl as ftable';
        $field                     =    'position';
        $fieldName                     =    'is_active';
        $fieldValue                        = '1';

        $shortField5            =    'id,venue_title , image';
        $data['location_tbl']         =     $this->common_model->getLastOrderByFields1('multiple', $field, $tbl5, $fieldName, $fieldValue, $shortField5);

        /********************************************Venue******************************/

        $where51['where']         =    ['is_active' => '1'];
        $tbl51                    =    'event_location_tbl as ftable';
        //$shortField5			=	'id DESC';

        $shortField51             =    'location_name ASC';
        $data['venue_tbl']         =     $this->common_model->getData('multiple', $tbl51, $where51, $shortField51);



        /********************************************Our Partners Section******************************/
        $where2['where']         =    "is_active = '1'"; //"status = 'A'";
        $tbl2                     =    'slider_tbl as ftable';
        $shortField2             =    'id DESC';

        $shortField2            =    'type_name ASC';
        $data['slider_tbl']         =     $this->common_model->getData('multiple', $tbl2, $where2);


        /********************************************Artist******************************/

            /*	$where51['where'] 		=	[ 'is_active' =>'1'];
		$tbl51					=	'event_tbl as ftable';
		//$shortField5			=	'id DESC';
	   
		$shortField51 			=	'event_title ASC';
		$data['artist_name'] 		= 	$this->common_model->getData('multiple', $tbl51,$where51,$shortField51)*/;

        //$data['artist_name'] = $this->db->select('*')->from('event_tbl')->where('is_active','1')->order_by('event_title')->group_by('event_title')->get()->result_array();

        $data['artist_name']          =     $this->front_model->event_artist();

        $data['event_tags']          =     $this->front_model->event_artist1();

        //  echo $this->db->last_query();die;

        /********************************************Subscribe form******************************/
        if ($this->request->getPost('Savesubsc')) :

            $error                    =    'NO';
            $this->form_validation->set_rules('email', 'Email Address', 'required');
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_message('trim|required', 'Enter %s');

            if ($this->form_validation->run() && $error == 'NO') :


                $param['email']                =     $this->request->getPost('email');
                $param['name']                =     $this->request->getPost('name');
                $param['creation_date']                =     date('Y-m-d h:i:s');
                $param['status']            =    'A';
                $param['ip_address'] 		=	currentIp();
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
                    $alastInsertId                =    $this->common_model->addData('subscribe_tbl', $param);
                    //$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
                    $this->session->setFlashdata('alert_success', 'Details Submitted Successfully');
                } else {
                    $this->session->setFlashdata('alert_error', 'Email Id already used');
                }
                redirect('new_events');
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

        $data['datae']         =   date('Y-m-d');

        $data['jazzType']      = $this->common_model->getCategoryJazz();
        //  echo "<pre>";print_r( $data['jazzType']);die;
        $data['artistType']      = $this->common_model->getCategoryArtist();
        // echo "<pre>";print_r( $data['artistType']);die;



        $this->layouts->set_title($data['seo_section']['title']);
        $this->layouts->set_description($data['seo_section']['description']);
        $this->layouts->set_keyword($data['seo_section']['keywords']);
        $this->layouts->front_view('new_events', array(), $data);
    }

    public function getdate()
	{

		$htlm = '';
		$MonthDate = array();

		$year = $_GET['year'];
		$month = $_GET['month'];
		//$year = "2023";
		//$month = "1";
		$what = isset($_REQUEST['what']) ? $_REQUEST['what'] : "";
		if (1) {
			//echo $year;exit;
			$lastDayOfMonth = date("t", strtotime("{$year}-{$month}"));
			$month_str = date("F, Y", strtotime("{$year}-{$month}"));

			$DateArr = array();
			for ($d = 1; $d <= $lastDayOfMonth; $d++) {
				$time = strtotime("{$year}-{$month}-{$d}");
				$MonthDate[$d] = date('D', $time);
			}

			foreach ($MonthDate as $date => $day) {
				$dateA = $year . '-' . $month . '-' . $date;
				$cls = '';
			
				if (date("Y") == $year && date("m") == $month) {
					if ($date == date('d')) {
						$cls = 'evo_day has_events on_focus';
					} elseif ($date < date('d')) {
						$cls = 'evo_day';
					} else {
						$cls = 'evo_day has_events';
					}
				} else {
					$cls = ($date == 1) ? 'evo_day has_events on_focus' : 'evo_day has_events';
				}
			
				// Determine if the date is past the current date
				$isPastDate = (strtotime($dateA) < strtotime(date('Y-m-d')));
			
				// Check if it's today's date
				$isToday = (date('Y-m-d', strtotime($dateA)) == date('Y-m-d'));
			
				// Construct the HTML for each date item
				$itemHtml = '<div class="item';
				if ($isToday) {
					$itemHtml .= ' on_focus';
				}
				if ($isPastDate) {
					$itemHtml .= ' past-date"'; // Add class for past dates
				} else {
					$itemHtml .= '" style="cursor: pointer;" onclick="change(' . $year . ',' . $month . ',' . $date . ')"';
				}
				$itemHtml .= ' id="' . date('Y-m-d', strtotime($dateA)) . '" date="' . $dateA . '">';
				$itemHtml .= '<div class="day-box ' . $cls . '"><p>' . $day . '</p><h3>' . $date . '</h3></div>';
				$itemHtml .= '</div>';
			
				// Add the constructed item HTML to $DateArr['dates']
				$DateArr['dates'][] = $itemHtml;
			}
			
			$NxtM = $month + 1;
			$PreM = $month - 1;
			$DateArr['next'] = ("'" . $year . "','" . $NxtM . "'");
			$DateArr['prev'] = ("'" . $year . "','" . $PreM . "'");
			if ($NxtM == 13) {
				$DateArr['next'] = ("'" . ($year + 1) . "','1'");
			}
			if ($PreM <= 0) {
				$DateArr['prev'] = ("'" . ($year - 1) . "','12'");
			}
			$DateArr['str'] = $month_str;
			echo json_encode($DateArr);
		}
	}

    public function new_event_filter1()
	{

		$today_date = date('Y-m-d');
		$select_data = $_POST['optionValue'];
		$dateSelected = $_POST['Selected_Date_'];

		//$event_location = $this->db->select('*')->from('event_location_tbl')->where('venue_id',$select_data)->get()->result();
		//$event_location = $this->front_model->getLocationData($select_data);
		//$html_location.="<option>Select Venue</option>";
		/*foreach($event_location as $location){
			$html_location.= "<option value=".$location->id."'>".$location->location_name."</option>";
		} */

		//print_r($html_location);die;
		$data['event_tbl'] = $this->front_model->get_events1($select_data, $today_date, $dateSelected);
	
		$data['datae'] = date('Y-m-d');
		$html = $this->load->view('front/new_event_filter1', $data, TRUE);

		echo json_encode(array('data' => $html, 'selected_date' => $select_date));
	}

    public function event_filter_artist()
	{
		$dateSelected = date('Y-m-d', strtotime($_POST['Selected_Date_']));
		
		if ($_POST['Selected_Date_'] == '') {
			$dateSelected = date('Y-m-d');
		}

		$event_tags = isset($_POST['event_tags']) ? $_POST['event_tags'] : null;

		if ($event_tags) {
			$this->db->select('event_id');
			$this->db->from('event_tags_tbl');
			$this->db->like('event_tags', $event_tags);
			$event_tag_query = $this->db->get();
			$tags = $event_tag_query->result_array();
			$_POST['event_ids'] = array_column($tags, 'event_id');
		}

	
		if (isset($event_tags) && trim($event_tags) != '' && empty($_POST['event_ids'])) {
			$data['event_tbl'] = array();
		} else {
			
            $data['get_new_events']      = $this->front_model->get_new_events($dateSelected, $_POST);
			
		}

		$data['datae'] = date('Y-m-d');

		foreach ($data['event_tbl'] as &$event) {
			$event_id = $event['event_id'];

			$this->db->select('*');
			$this->db->from('event_tags_tbl');
			$this->db->where('event_id', $event_id);
			$event_tag_query = $this->db->get();

			$events_data = $event_tag_query->result_array();
			$event_tag_names = [];
			foreach ($events_data as $event_data) {
				$event_tag_names[] = $event_data['event_tags'];
			}
			$event_tag_names = array_unique($event_tag_names);

			$event['event_tags'] = $event_tag_names;
		}

		// echo'<pre>';
        //  print_r($data);die;
		$html = $this->load->view('front/new_event_filter1', $data, TRUE);

		echo json_encode(array('data' => $html, 'selected_date' => $dateSelected));
	}
}
