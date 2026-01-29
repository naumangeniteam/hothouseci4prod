<?php

namespace App\Controllers\front;
use \DrewM\MailChimp\MailChimp;
use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Libraries\Layouts;
use App\Models\FrontModel;
use App\Libraries\Elastichh;
use App\Models\ElasticModel;

class Calendar extends BaseController
{
	protected $common_model;
    protected $layouts;
	protected $validation;
	protected $session;
	protected $front_model;
	protected $lang;
	protected $elastic_model;
	protected $elastichh_lib;

	public function  __construct()
	{
		
		error_reporting(E_ALL ^ E_NOTICE);  
		// error_reporting(0);
		$this->lang = service('language'); 
		$this->lang->setLocale('front');
		$this->common_model = new CommonModel();
		$this->front_model = new FrontModel();
        $this->layouts = new Layouts();
		$this->session = session();
		helper('common');
		// $this->elastic_model = new ElasticModel();
		$this->elastichh_lib = new  Elastichh();
	}

	/* * *********************************************************************
	 * * Function name 	: index
	 * * Developed By 	: Megha Kumari
	 * * Purpose  		: This function for home page data
	 * * Date 			: 18/01/23
	 * * **********************************************************************/
	public function index()
	{
		$data = array();
		/********************************************Banner Section******************************/
		$where['where'] 		=	['page_name' => 'Calender',  'is_active' => true]; //"status = 'A'";
		$tbl 					=	'banner_tbl as ftable';
		$shortField 			=	'id DESC';

		$shortField1 			=	'type_name ASC';
		$data['banner'] 		= 	$this->common_model->getData('multiple', $tbl, $where);
		/********************************************About Section******************************/
		$where1['where'] 		=	"is_active = true"; //"status = 'A'";
		$tbl1 					=	'about_us_tbl as ftable';
		$shortField12 			=	'id DESC';

		$shortField123 			=	'type_name ASC';
		$data['about'] 		= 	$this->common_model->getData('multiple', $tbl1, $where1, $shortField12, 6, 0);
		/********************************************Our Team Section******************************/
		$where2['where'] 		=	"is_active = true"; //"status = 'A'";
		$tbl2 					=	'about_team_tbl as ftable';
		$shortField2 			=	'id DESC';

		$shortField222			=	'type_name ASC';
		$data['about_team_tbl'] 		= 	$this->common_model->getData('multiple', $tbl2, $where2, $shortField2, 6, 0);


		/********************************************Img Section******************************/
		$where3['where'] 		=	"is_active = true"; //"status = 'A'";
		$tbl3 					=	'home_image as ftable';
		$shortField2 			=	'id DESC';

		$shortField3			=	'type_name ASC';
		$data['home_image'] 		= 	$this->common_model->getData('multiple', $tbl3, $where3);

		
		/********************************************Our Partners Section******************************/
		$where2['where'] 		=	array('is_active'=>true,'page'=>2); //"status = 'A'";
		$tbl2 					=	'slider_tbl as ftable';
		$data['slider_tbl'] 		= 	$this->common_model->getData('multiple', $tbl2, $where2);

		/********************************************Seo Section******************************/
		$where5['where'] 		=	['page_name' => 'Calendar Page',  'is_active' => true];
		$tbl5 					=	'seo_tbl as ftable';
		$shortField5			=	'id DESC';

		$shortField6 			=	'type_name ASC';
		$data['seo_section'] 		= 	$this->common_model->getData('single', $tbl5, $where5);

		/********************************************Location******************************/

		$where5['where'] 		=	"is_active = true"; //"status = 'A'";
		$tbl5 					=	'venue_tbl as ftable';
		$field 					=	'position';
		$fieldName 					=	'is_active';
		$fieldValue                        = 't';

		$shortField5			=	'id,venue_title , image';
		$data['location_tbl'] 		= 	$this->common_model->getLastOrderByFields1('multiple', $field, $tbl5, $fieldName, $fieldValue, $shortField5);

		/********************************************Venue******************************/

		$where51['where'] 		=	['is_active' => 1];
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


		$data['event_tags'] 		= 	$this->front_model->event_artist1();

		
		
		/********************************************Subscribe form******************************/
		if ($this->request->getPost('Savesubsc')) {

			$validationRules = [
				'email' => 'required|valid_email',
				'name'  => 'required'
			];
		
				if (!$this->validate($validationRules)) {
					$this->session->setFlashdata('alert_error', 'Please enter all details');
					return redirect()->to('/');
				}
		
				$param = [
					'email'         => $this->request->getPost('email'),
					'name'          => $this->request->getPost('name'),
					'creation_date' => date('Y-m-d H:i:s'),
					'status'        => 'A',
					'ip_address'    =>$this->request->getIPAddress()
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
				$subscribe = $this->common_model->subscribeEmail($param['email']);
				if (empty($subscribe)) {
					$alastInsertId				=	$this->common_model->addData('subscribe_tbl', $param);
					//$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
					$this->session->setFlashdata('alert_success', 'Details Submitted Successfully');
				} else {
					$this->session->setFlashdata('alert_error', 'Email Id already used');
				}
				return redirect()->to('/calendar');
			}

		/********************************************Event List Section******************************/

		/*$tbl6 					=	'event_tbl as ftable';
		  //$shortField6 = 'ftable.event_start_time DESC'; 
		 $where2['where'] 		=	"is_active = '1' AND date != ''";
		 $wcon['where_gte']     =   array('start_date' => date('Y-m-d') , 'end_date' => date('Y-m-d'));
		 $shortField77			=	'type_name ASC';
		 $data['event_tbl'] 	= 	$this->common_model->getData('multiple', $tbl6,$where2);*/

		$data['datae'] 		=   date('Y-m-d');

		$data['jazzType']      = $this->common_model->getCategoryJazz(false);

		$data['artistType']      = $this->common_model->getCategoryArtist(false);


		$this->layouts->set_title($data['seo_section']['title']);
		$this->layouts->set_description($data['seo_section']['description']);
		$this->layouts->set_keyword($data['seo_section']['keywords']);
		$this->layouts->front_view('calendar', array(), $data);
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
					$itemHtml .= ' title="Past dates are clickable"';
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
	/***********************************************************************
	 ** Function name : calendar_filter
	 ** Developed By : Ritu Mishra
	 ** Purpose  : This function used for filter calendar
	 ** Date : 14 April 2023
	 ************************************************************************/
	public function calendar_filter()
	{
		//$date = $_POST['start_date'];
		$date = date('Y-m-d', strtotime($_POST['start_date']));
	
		$select_date = $_POST['selected_date'];
		//$data['event_tbl'] = $this->db->select('*')->from('event_tbl')->where('start_date',$date)->get()->result_array();
		$data['event_tbl'] = $this->front_model->get_events($date);

		$data['datae'] = $_POST['selected_date'];
		// $html = $this->load->view('front/calender_filter', $data, TRUE);
		// echo json_encode(array('data' => $html, 'selected_date' => $select_date));
	}
	public function calendar_filter1()
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

	
		$data['event_tbl'] = $this->front_model->get_events1($select_data, $today_date, $dateSelected);
	
		$data['datae'] = date('Y-m-d');
		// $html = $this->load->view('front/calender_filter1', $data, TRUE);

		//datata   

		// echo json_encode(array('data' => $html, 'selected_date' => $select_date));
	}

	/* public function calendar_filter_all() {
		

        $today_date=date('Y-m-d');
		$select_data = $_POST['optionValue'];
		$select_data_location = $_POST['optionValueLocation'];

		$dateSelected = $_POST['Selected_Date_'];

		

	
		$data['event_tbl'] = $this->front_model->get_events1($select_data,$today_date,$dateSelected,$select_data_location);
	
		 $data['datae'] = date('Y-m-d');
		$html = $this->load->view('front/calender_filter1', $data, TRUE);


		//datata   
		
		///

		echo json_encode(array('data'=>$html, 'selected_date'=>$select_date));
  	}*/

	/***********************************************************************
	 ** Function name : global_search
	 ** Developed By : Ritu Mishra
	 ** Purpose  : This function used for global search
	 ** Date : 20 Aug 2023
	 ************************************************************************/

	public function filter_artist()
	{
		$artist = $_POST['keyword'];

		$today_date = date('Y-m-d');
		$select_data = $_POST['optionValue'];
		$select_data_location = $_POST['optionValueLocation'];

		$dateSelected = $_POST['Selected_Date_'];


		//$artist = $_POST['optionValueArtist'];

		$data['event_tbl'] = $this->front_model->searchartist($dateSelected, $artist);
		$data['datae'] = date('Y-m-d');
		$html = $this->load->view('front/calender_filter1', $data, TRUE);

		echo json_encode(array('data' => $html, 'selected_date' => $select_date));
	}

	public function filter_artist1()
	{
		$artist = $_POST['keyword'];
		//print_r($artist);die;
		$today_date = date('Y-m-d');
		$select_data = $_POST['optionValue'];
		$select_data_location = $_POST['optionValueLocation'];

		$dateSelected = $_POST['Selected_Date_'];
		//print_r($dateSelected);die;

		//$artist = $_POST['optionValueArtist'];

		//print_r($artist);die;
		$data['event_tbl'] = $this->front_model->searchartisted($dateSelected, $artist);
		$data['datae'] = date('Y-m-d');
		$html = $this->load->view('front/calender_filter1', $data, TRUE);

		echo json_encode(array('data' => $html, 'selected_date' => $select_date));
	}



	/***********************************************************************
	 ** Function name : global_search
	 ** Developed By : Ritu Mishra
	 ** Purpose  : This function used for global search
	 ** Date : 20 Aug 2023
	 ************************************************************************/
	public function global_search()
	{
		$dateSelected = $_POST['Selected_Date_'];
		$keyword = $_POST['keyword'];
		//print_r($keyword);
		//print_r($dateSelected);die;

		if (!empty($_POST["keyword"])) {
			//$result = $this->db->select('product_name')->from('products')->like('product_name',$_POST["keyword"])->get()->result_array();
			$result = $this->front_model->search_result($_POST["keyword"]);
			//print_r($result);die;
			if (!empty($result)) {
?>
				<ul id="country-list">
					<?php foreach ($result as $product) {

					?>
						<li style="cursor: pointer" onclick="selectProduct('<?php echo $product["event_title"]; ?>');">
							<?php echo $product["event_title"]; ?>
						</li>
					<?php } // end for 
					?>

					<!--<select class="form-control" id="artist_name" onchange="selectProduct('<?php echo $result['event_title']; ?>');" style="margin-left: -72px;margin-top: 9px; width: 14%;">
                  <option style="width:14%"  value="">Select artist name</option>

                  <?php foreach ($result as $artist) :
						print_r($artist);
					?>
                     <option style="width:14%"  value="<?= $artist['event_id']; ?>"><?= $artist['event_title'] ?></option>
                  <?php endforeach; ?>
               </select>-->
				</ul>
			<?php }
		}
	}

	public function global_searched()
	{
		$dateSelected = $_POST['Selected_Date_'];
		$keyword = $_POST['keyword'];
		//print_r($keyword);
		//print_r($dateSelected);die;

		if (!empty($_POST["keyword"])) {
			//$result = $this->db->select('product_name')->from('products')->like('product_name',$_POST["keyword"])->get()->result_array();
			$result = $this->front_model->search_result1($_POST["keyword"]);
			// echo"<pre>";
			// print_r($result);die;
			if (!empty($result)) {
			?>
				<ul id="country-list">
					<?php foreach ($result as $product) {

					?>
						<li onclick="selectProducts('<?php echo $product["event_tags"]; ?>');">
							<?php echo $product["event_tags"]; ?>
						</li>
					<?php } // end for 
					?>

					<!--<select class="form-control" id="artist_name" onchange="selectProduct('<?php echo $result['event_title']; ?>');" style="margin-left: -72px;margin-top: 9px; width: 14%;">
                  <option style="width:14%"  value="">Select artist name</option>

                  <?php foreach ($result as $artist) :
						//print_r($artist);
					?>
                     <option style="width:14%"  value="<?= $artist['event_id']; ?>"><?= $artist['event_title'] ?></option>
                  <?php endforeach; ?>
               </select>-->
				</ul>
<?php }
		}
	}

	public function thumbnail_full_image()
	{
		$eventId = $_GET['eventId'];
		$artistId = $_GET['artistId'];
		$artistData = $this->front_model->artist_detail($artistId);
		$result = $this->front_model->event_detail($eventId);
		$data['artist_tbl'] = $artistData;
		$data['event_tbl'] = $result;
		$where['where'] 		=	['page_name' => 'Calender',  'is_active' => '1']; //"status = 'A'";
		$tbl 					=	'banner_tbl as ftable';
		$data['banner'] 		= 	$this->common_model->getData('multiple', $tbl, $where);
		// echo "<pre>";print_r($artistData);die;
		$this->layouts->front_view('thumbnail-full-image', array(), $data);
	}

	public function get_artist_data()
{
    $request = service('request'); // Get request instance
    $eventId = $request->getGet('id'); // Fetch the 'id' from the GET request

    if (!$eventId) {
        return $this->response->setJSON(['error' => 'Event ID is missing']);
    }

    // Fetch event details using front_model
    $result = $this->front_model->event_detail($eventId);

    if (!$result) {
        return $this->response->setBody('Error: Event not found.');
        echo 'Error: Event not found.';
    }

    $db = \Config\Database::connect(); // Load database

    // Fetch venue name
    $getVenuName = $db->table('venue_tbl')->select('venue_title')->where('id', $result[0]['venue_id'])->get()->getRow();

    // Fetch jazz type name
    $getJazzName = $db->table('jazz_types')->select('name')->where('id', $result[0]['jazz_types_id'])->get()->getRow();

    // Fetch artist details
    $getArtistData = $db->table('artist_tbl')->select('artist_name, cover_image, cover_url, buy_now_link, website_link, artist_bio, artist_url, artist_image')
        ->where('id', $result[0]['artist_id'])->get()->getRow();

    $html_content = '<div class="calendar-box"><div class="row">';

    if (!empty($getArtistData->artist_image)) {
        $html_content .= '<div class="col-12 front-imgs1">
                            <img style="width: 50%;" src="' . base_url("assets/front/img/artistimage/" . $getArtistData->artist_image) . '" >
                          </div>';
    }

    if (!empty($getArtistData->cover_image)) {
        $html_content .= '<div class="col-12 front-imgs">
                            <img style="width: 50%;" src="' . base_url("assets/front/img/artistimage/" . $getArtistData->cover_image) . '" >
                          </div>';
    }

    $b_now = '';
    if (!empty($result[0]['buy_now_link'])) {
        $b_now = '<p><a target="_blank" href="' . esc($result[0]['buy_now_link']) . '">Buy Now</a></p>';
    }

    $html_content .= '<div class="col-12">';

    if (!empty($getArtistData->artist_name)) {
        $html_content .= '<p>' . esc($getArtistData->artist_name) . '</p>';
    }

    if (!empty($getArtistData->artist_bio)) {
        $html_content .= '<p>' . esc($getArtistData->artist_bio) . '</p>';
    }

    $html_content .= '<div class="artist-ra">';
    if (!empty($getArtistData->buy_now_link)) {
        $html_content .= '<p><a target="_blank" href="' . esc($getArtistData->buy_now_link) . '">Buy Link</a></p>';
    }

    if (!empty($getArtistData->website_link)) {
        $html_content .= '<p><a target="_blank" href="' . esc($getArtistData->website_link) . '">Website Link</a></p>';
    }
    $html_content .= '</div>';

    $html_content .= '</div></div></div>';

    // return $this->response->setBody($html_content);
    echo $html_content; 
}

	
	public function calendar_filter_venue()
	{

		$today_date = date('Y-m-d');
		$select_data = $_POST['optionValue'];
		$select_data_location = $_POST['optionValueLocation'];

		$dateSelected = $_POST['Selected_Date_'];

		//print_r($html_location);die;
		$data['event_tbl'] = $this->front_model->get_events1($select_data, $today_date, $dateSelected, $select_data_location);
		//echo $this->db->last_query();die;
		//echo'<pre>';
		//print_r($data['event_tbl']);die;
		$data['datae'] = date('Y-m-d');
		// $data['event_tbl'] = array_unique($data['event_tbl']);
		// $html = $this->load->view('front/calender_filter1', $data, TRUE);

		// echo json_encode(array('data' => $html, 'selected_date' => $select_date));
	}


	public function calendar_filter_jazz()
	{
		$today_date = date('Y-m-d');
		$select_data = $_POST['optionValue'];
		$jazzId = $_POST['optionValueLocation'];
		$dateSelected = $_POST['Selected_Date_'];
		$select_data_location =null;
		$data['event_tbl'] = $this->front_model->get_events1($select_data, $today_date, $dateSelected, $select_data_location, $jazzId);
		//echo $this->db->last_query();die;
		// echo'<pre>';
		// print_r($data['event_tbl']);die;
		$data['datae'] = date('Y-m-d');
		// $data['event_tbl'] = array_unique($data['event_tbl']);
		// $html = $this->load->view('front/calender_filter1', $data, TRUE);
		// echo json_encode(array('data' => $html, 'selected_date' => $select_date));
	}

	public function calendar_filter_artist()
	{
		$arrPost = $this->request->getPost();
		$eventsResponse = $this->elastichh_lib->eventSearchListingData($arrPost);
		$eventsData = $eventsResponse['hits'];
		$dateSelected = date('Y-m-d', strtotime($_POST['Selected_Date_']));

		if ($_POST['Selected_Date_'] == '') {
			$dateSelected = date('Y-m-d');
		}

		$data['event_tbl'] = !empty($eventsData['hits']) ? $eventsData['hits'] : [];
		// echo "<pre>";
		// print_r($data['event_tbl']);
		// echo "</pre>";

		// $html = $this->load->view('front/calender_filter1', $data, TRUE);
		// $html = $this->layouts->front_view('calender_filter1', array(), $data);
		// echo json_encode(array('data' => $html, 'selected_date' => $dateSelected));
		$html = view('front/calender_filter1', $data);

		// Send JSON response with HTML included
		return $this->response->setJSON([
			'data'          => $html,
			'selected_date' => $dateSelected,
		]);
	}

}
