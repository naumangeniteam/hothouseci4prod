<?php
namespace App\Controllers\front;

use App\Controllers\BaseController;

use \DrewM\MailChimp\MailChimp;
class Calendar4324 extends BaseController {

	public function  __construct() 
	{ 
		
		//error_reporting(E_ALL ^ E_NOTICE);  
		error_reporting(0);  
		$this->load->model(array('admin_model','front_model','emailtemplate_model','sms_model','notification_model','common_model'));
		$this->lang = service('language'); 
$this->lang->setLocale('front');
		helper('common');
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
	$where['where'] 		=	['page_name' => 'Calender' ,  'is_active' =>'1'];//"status = 'A'";
	$tbl 					=	'banner_tbl as ftable';
	$shortField 			=	'id DESC';

	$shortField1 			=	'type_name ASC';
	 $data['banner'] 		= 	$this->common_model->getData('multiple', $tbl,$where);
/********************************************About Section******************************/
       $where1['where'] 		=	"is_active = '1'";//"status = 'A'";
		$tbl1 					=	'about_us_tbl as ftable';
		$shortField12 			=	'id DESC';

		$shortField123 			=	'type_name ASC';
		 $data['about'] 		= 	$this->common_model->getData('multiple', $tbl1,$where1,$shortField12,6,0);
		 /********************************************Our Team Section******************************/
		 $where2['where'] 		=	"is_active = '1'";//"status = 'A'";
		 $tbl2 					=	'about_team_tbl as ftable';
		 $shortField2 			=	'id DESC';
 
		 $shortField222			=	'type_name ASC';
		  $data['about_team_tbl'] 		= 	$this->common_model->getData('multiple', $tbl2,$where2,$shortField2,6,0);
	 
 /********************************************Seo Section******************************/
 $where5['where'] 		=	['page_name' => 'Calendar Page',  'is_active' =>'1'];
 $tbl5 					=	'seo_tbl as ftable';
 $shortField5			=	'id DESC';

 $shortField6 			=	'type_name ASC';
 $data['seo_section'] 		= 	$this->common_model->getData('single', $tbl5,$where5);

 /********************************************Location******************************/
 
  $where5['where'] 		=	"is_active = '1'";//"status = 'A'";
	 $tbl5 					=	'venue_tbl as ftable';
	 $field 					=	'position';
	 $fieldName 					=	'is_active';
	 $fieldValue                        ='1';	

	 $shortField5			=	'id,venue_title , image';
	 $data['location_tbl'] 		= 	$this->common_model->getLastOrderByFields1('multiple',$field, $tbl5,$fieldName,$fieldValue,$shortField5);

	   /********************************************Venue******************************/

	   $where51['where'] 		=	[ 'is_active' =>'1'];
	   $tbl51					=	'event_location_tbl as ftable';
	   //$shortField5			=	'id DESC';
	  
	   $shortField51 			=	'location_name ASC';
	   $data['venue_tbl'] 		= 	$this->common_model->getData('multiple', $tbl51,$where51,$shortField51);


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
  if($this->request->getPost('Savesubsc')):
			
		$error					=	'NO';
			$this->form_validation->set_rules('email', 'Email Address', 'required');
			$this->form_validation->set_rules('name', 'Name', 'required');
		   $this->form_validation->set_message('trim|required', 'Enter %s');
		
		if($this->form_validation->run() && $error == 'NO'): 
				
			
			    $param['email']				= 	$this->request->getPost('email');	
			    $param['name']				= 	$this->request->getPost('name');	
				$param['creation_date']				= 	date('Y-m-d h:i:s');
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
                
                $auth = base64_encode( 'user:'.$api_key );
                
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
                curl_setopt($ch, CURLOPT_URL, 'https://'.$server.'api.mailchimp.com/3.0/lists/'.$list_id.'/members');
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                    'Authorization: Basic '.$auth));
                curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_POST, true);    
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                
                $result = curl_exec($ch);
				 $subscribe = $this->common_model->subscribeEmail($param['email']);
		if(empty($subscribe)){
		    	$alastInsertId				=	$this->common_model->addData('subscribe_tbl',$param);
			//$this->session->setFlashdata('alert_success',lang('statictext_lang.addsuccess'));
			$this->session->setFlashdata('alert_success', 'Details Submitted Successfully');
		}
		else{
		    $this->session->setFlashdata('alert_error', 'Email Id already used');
		}
				redirect('calendar');
		else:
		$this->session->setFlashdata('alert_error','Please Enter All Details');
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

		$this->layouts->set_title($data['seo_section']['title']);
		$this->layouts->set_description($data['seo_section']['description']);
		$this->layouts->set_keyword($data['seo_section']['keywords']);
		 $this->layouts->front_view('calendar',array(),$data);
	}



public function getdate(){

	$htlm ='';
	$MonthDate=array();
	
	$year = $_GET['year'];
	$month = $_GET['month'];
	//$year = "2023";
	//$month = "1";
	$what = isset($_REQUEST['what'])?$_REQUEST['what']: "" ;
	if(1){
		//echo $year;exit;
		$lastDayOfMonth = date("t",strtotime("{$year}-{$month}")) ;
$month_str = date("F, Y",strtotime("{$year}-{$month}")) ;
		
		$DateArr = array();
		for($d=1; $d<=$lastDayOfMonth; $d++)
		{
			$time=strtotime("{$year}-{$month}-{$d}");
			$MonthDate[$d]=date('D', $time);
		}

		foreach($MonthDate as $date=>$day){

			if(date("Y")==$year && date("m")==$month) {
				if($date == date('d')){
					$cls = 'evo_day has_events on_focus';
				}
				elseif($date < date('d')){
					$cls = 'evo_day';
				}else{
					$cls = 'evo_day has_events';
				}
			}
			else {
				$cls = ($date==1) ? 'evo_day has_events on_focus' : 'evo_day has_events';
			}
			$dateA = $year.'-'.$month.'-'.$date;
		
			$currentDate = date('Y-m-d');
			//echo $dateA;die;
			if(date('Y-m-d', strtotime($dateA)) == $currentDate){
				

				$DateArr['dates'][]= '<div class="item" id="'.date('Y-m-d', strtotime($dateA)).'" style="background-color:grey;display: table-cell;" date="'.$dateA.'" onclick="change('.$year.','.$month.','.$date.')"><div class="day-box '.$cls.'"><p>'.$day.'</p><h3>'.$date.'</h3></div></div>';
			}
			else{
				$DateArr['dates'][]= '<div class="item" id="'.date('Y-m-d', strtotime($dateA)).'" style="display: table-cell;" date="'.$dateA.'" onclick="change('.$year.','.$month.','.$date.')"><div class="day-box '.$cls.'"><p>'.$day.'</p><h3>'.$date.'</h3></div></div>';
			}
			//$DateArr['dates'][]= '<p class="'.$cls.'" date="'.$dateA.'" onclick="clearValue();getEvent(\''.$dateA.'\',\'\',\'\'); "> <span class="evo_day_name" >'.$day.'</span><span class="evo_day_num">'.$date.'</span> </p>';
			//$DateArr['dates'][]= '<div class="item" style="display: table-cell;" date="'.$dateA.'" onclick="change('.$year.','.$month.','.$date.')"><div class="day-box '.$cls.'"><p>'.$day.'</p><h3>'.$date.'</h3></div></div>';
		}
		$NxtM = $month+1 ;
		$PreM = $month-1 ;
		$DateArr['next'] =("'".$year."','".$NxtM."'");
		$DateArr['prev']= ("'".$year."','".$PreM."'");
		if($NxtM == 13){
			$DateArr['next'] = ("'".($year+1)."','1'");
			}
		if($PreM <= 0){
			$DateArr['prev'] = ("'".($year-1)."','12'");
			}
		$DateArr['str'] = $month_str ;
		echo json_encode($DateArr);
	}
}
	/***********************************************************************
	** Function name : calendar_filter
	** Developed By : Ritu Mishra
	** Purpose  : This function used for filter calendar
	** Date : 14 April 2023
	************************************************************************/
	public function calendar_filter() {
		//$date = $_POST['start_date'];
		$date = date('Y-m-d', strtotime($_POST['start_date']));
		//print_r(date('Y-m-d', strtotime($date)));die;
		$select_date = $_POST['selected_date'];
		//$data['event_tbl'] = $this->db->select('*')->from('event_tbl')->where('start_date',$date)->get()->result_array();
		$data['event_tbl'] = $this->front_model->get_events($date);
		 $data['datae'] = $_POST['selected_date'];
		$html = $this->load->view('front/calender_filter', $data, TRUE);
		echo json_encode(array('data'=>$html, 'selected_date'=>$select_date));
  	}
    public function calendar_filter1() {


        $today_date=date('Y-m-d');
		$select_data = $_POST['optionValue'];
		$dateSelected = $_POST['Selected_Date_'];

		//$event_location = $this->db->select('*')->from('event_location_tbl')->where('venue_id',$select_data)->get()->result();
		//$event_location = $this->front_model->getLocationData($select_data);
		//$html_location.="<option>Select Venue</option>";
		/*foreach($event_location as $location){
			$html_location.= "<option value=".$location->id."'>".$location->location_name."</option>";
		} */

		//print_r($html_location);die;
		$data['event_tbl'] = $this->front_model->get_events1($select_data,$today_date,$dateSelected);
		//echo $this->db->last_query();die;
		//echo'<pre>';
		//print_r($data['event_tbl']);die;
		 $data['datae'] = date('Y-m-d');
		$html = $this->load->view('front/calender_filter1', $data, TRUE);


		//datata   
		
		///

		echo json_encode(array('data'=>$html, 'selected_date'=>$select_date));
  	}

	 /* public function calendar_filter_all() {
		

        $today_date=date('Y-m-d');
		$select_data = $_POST['optionValue'];
		$select_data_location = $_POST['optionValueLocation'];

		$dateSelected = $_POST['Selected_Date_'];

		

		//print_r($html_location);die;
		$data['event_tbl'] = $this->front_model->get_events1($select_data,$today_date,$dateSelected,$select_data_location);
		//echo $this->db->last_query();die;
		//echo'<pre>';
		//print_r($data['event_tbl']);die;
		 $data['datae'] = date('Y-m-d');
		$html = $this->load->view('front/calender_filter1', $data, TRUE);


		//datata   
		
		///

		echo json_encode(array('data'=>$html, 'selected_date'=>$select_date));
  	}*/
  	
  	  public function calendar_filter_venue() {
		

        $today_date=date('Y-m-d');
		$select_data = $_POST['optionValue'];
		$select_data_location = $_POST['optionValueLocation'];

		$dateSelected = $_POST['Selected_Date_'];

		

		//print_r($html_location);die;
		$data['event_tbl'] = $this->front_model->get_events1($select_data,$today_date,$dateSelected,$select_data_location);
		//echo $this->db->last_query();die;
		//echo'<pre>';
		//print_r($data['event_tbl']);die;
		 $data['datae'] = date('Y-m-d');
		// $data['event_tbl'] = array_unique($data['event_tbl']);
		$html = $this->load->view('front/calender_filter1', $data, TRUE);


		//datata   
		
		///

		echo json_encode(array('data'=>$html, 'selected_date'=>$select_date));
  	}

    /***********************************************************************
	** Function name : global_search
	** Developed By : Ritu Mishra
	** Purpose  : This function used for global search
	** Date : 20 Aug 2023
	************************************************************************/

	    public function filter_artist() {
		$artist = $_POST['keyword'];
		//print_r($artist);die;
        $today_date=date('Y-m-d');
		$select_data = $_POST['optionValue'];
		$select_data_location = $_POST['optionValueLocation'];

		$dateSelected = $_POST['Selected_Date_'];
		//print_r($dateSelected);die;

		//$artist = $_POST['optionValueArtist'];

		//print_r($artist);die;
		$data['event_tbl'] = $this->front_model->searchartist($dateSelected,$artist);
		 $data['datae'] = date('Y-m-d');
		$html = $this->load->view('front/calender_filter1', $data, TRUE);

		echo json_encode(array('data'=>$html, 'selected_date'=>$select_date));
  	}

	  public function filter_artist1() {
		$artist = $_POST['keyword'];
		//print_r($artist);die;
        $today_date=date('Y-m-d');
		$select_data = $_POST['optionValue'];
		$select_data_location = $_POST['optionValueLocation'];

		$dateSelected = $_POST['Selected_Date_'];
		//print_r($dateSelected);die;

		//$artist = $_POST['optionValueArtist'];

		//print_r($artist);die;
		$data['event_tbl'] = $this->front_model->searchartisted($dateSelected,$artist);
		 $data['datae'] = date('Y-m-d');
		$html = $this->load->view('front/calender_filter1', $data, TRUE);

		echo json_encode(array('data'=>$html, 'selected_date'=>$select_date));
  	}
  	

	
	/***********************************************************************
	** Function name : global_search
	** Developed By : Ritu Mishra
	** Purpose  : This function used for global search
	** Date : 20 Aug 2023
	************************************************************************/
public function global_search() {
		$dateSelected = $_POST['Selected_Date_'];
		$keyword = $_POST['keyword'];
		//print_r($keyword);
		//print_r($dateSelected);die;
	
		if (! empty($_POST["keyword"])) {
			//$result = $this->db->select('product_name')->from('products')->like('product_name',$_POST["keyword"])->get()->result_array();
			$result = $this->front_model->search_result($_POST["keyword"]);
			//print_r($result);die;
			if (! empty($result)) {
				?>
				<ul id="country-list">
				<?php foreach($result as $product) {
					
					?>
				<li
						onclick="selectProduct('<?php echo $product["event_title"]; ?>');">
					<?php echo $product["event_title"]; ?>
					</li>
				<?php } // end for ?>

				<!--<select class="form-control" id="artist_name" onchange="selectProduct('<?php echo $result['event_title']; ?>');" style="margin-left: -72px;margin-top: 9px; width: 14%;">
                  <option style="width:14%"  value="">Select artist name</option>

                  <?php foreach($result as $artist):
				  //print_r($artist);
					?>
                     <option style="width:14%"  value="<?=$artist['event_id'] ; ?>"><?=$artist['event_title']?></option>
                  <?php endforeach; ?>
               </select>-->
				</ul>
				<?php } 
				}
	}

	public function global_searched() {
		$dateSelected = $_POST['Selected_Date_'];
		$keyword = $_POST['keyword'];
		//print_r($keyword);
		//print_r($dateSelected);die;
	
		if (! empty($_POST["keyword"])) {
			//$result = $this->db->select('product_name')->from('products')->like('product_name',$_POST["keyword"])->get()->result_array();
			$result = $this->front_model->search_result1($_POST["keyword"]);
			//print_r($result);die;
			if (! empty($result)) {
				?>
				<ul id="country-list">
				<?php foreach($result as $product) {
					
					?>
				<li
						onclick="selectProducts('<?php echo $product["event_tags"]; ?>');">
					<?php echo $product["event_tags"]; ?>
					</li>
				<?php } // end for ?>

				<!--<select class="form-control" id="artist_name" onchange="selectProduct('<?php echo $result['event_title']; ?>');" style="margin-left: -72px;margin-top: 9px; width: 14%;">
                  <option style="width:14%"  value="">Select artist name</option>

                  <?php foreach($result as $artist):
				  //print_r($artist);
					?>
                     <option style="width:14%"  value="<?=$artist['event_id'] ; ?>"><?=$artist['event_title']?></option>
                  <?php endforeach; ?>
               </select>-->
				</ul>
				<?php } 
				}
	}
}
	
	