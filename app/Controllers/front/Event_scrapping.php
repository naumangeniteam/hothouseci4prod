<?php
namespace App\Controllers\front;

use App\Controllers\BaseController;

use \DrewM\MailChimp\MailChimp;

class Event_scrapping  extends BaseController
{
	private $varEventBritePrivateToken;

	public function  __construct()
	{
		
		error_reporting(E_ALL ^ E_NOTICE);  
		//error_reporting(0);
		$this->varEventBritePrivateToken = getenv('EVENTBRITE_PRIVATE_TOKEN');
		if (empty($this->varEventBritePrivateToken)) {
			//error_log("Eventbrite private token is not set in .env file");
			echo "Eventbrite private token is not set in .env file";
			exit;
		}
		$this->load->model(array('common_model','scraping_model'));
		$this->lang = service('language'); 
$this->lang->setLocale('front');
		helper('common');
	}

	public function index()
	{
		// check put data into main event table
		$varIsInsertToEventTable = $this->request->getPost('dest', true);
		if(!empty($varIsInsertToEventTable) && $varIsInsertToEventTable == 'main_tbl_des_upd'){
			// fetch the data from tmp table whose description not fetched from api
			$where['where'] = ["status_get_description"=>0];
            $tbl =  'eventbrite_ticket_ids as ftable';
            $arrDataReturn = $this->common_model->getData('multiple', $tbl, $where);
			if (!empty($arrDataReturn)) {
				$arrReturnedActualEventData = [];
                foreach ($arrDataReturn as $arrEventData) {
					$arrAllEventsForthisRow = json_decode($arrEventData['event_details_of_all_ids'],true);
					if(!empty($arrAllEventsForthisRow['events'])){
						foreach($arrAllEventsForthisRow['events'] as $arrSingleEventdata){
							$eventId = $arrSingleEventdata['id'];
							$objResponse = $this->fetchEventDetailedDescrioptionById($eventId);
							if(!empty($objResponse)){
								$arrDesResp = json_decode($objResponse,true);
								$this->common_model->updateData('event_tbl', ["description"=>$arrDesResp['description']], "event_source_id={$eventId}");
								$arrReturnedActualEventData[] = $eventId." - updated";
							}
						}
					}
				
					//echo $arrEventData['id']."<br/>";//exit;
					$this->common_model->updateData('eventbrite_ticket_ids', ["status_get_description"=>1], "id={$arrEventData['id']}");
					$arrReturnedActualEventData[] = $arrEventData['id']." - updated";
				}

			}

		}
		else if (!empty($varIsInsertToEventTable) && $varIsInsertToEventTable == 'main_tbl') {
			// send data to main event table from the tmp table
			// fetch the data from tmp table
			$where['where'] = ["status_get_details"=>1];
            $tbl =  'eventbrite_ticket_ids as ftable';
            $arrDataReturn = $this->common_model->getData('multiple', $tbl, $where);
			if (!empty($arrDataReturn)) {
				$arrReturnedActualEventData = [];
                foreach ($arrDataReturn as $arrEventData) {
					$arrAllEventsForthisRow = json_decode($arrEventData['event_details_of_all_ids'],true);
					if(!empty($arrAllEventsForthisRow['events'])){
						foreach($arrAllEventsForthisRow['events'] as $arrSingleEventdata){
							$arrReturnedActualEventData[] = $this->scraping_model->addEventDataFromEventbrite($arrSingleEventdata);
						}
					}
				}
		
			}
		}else if (!empty($varIsInsertToEventTable) && $varIsInsertToEventTable == 'tmp_update_tbl') {
			$where['where'] = ["status_get_details"=>0];
            $tbl =  'eventbrite_ticket_ids as ftable';
            $arrDataReturn = $this->common_model->getData('multiple', $tbl, $where);
			if(!empty($arrDataReturn)){
				foreach($arrDataReturn as $arrVal){
					$arrids = json_decode($arrVal['eventbrite_t_ids'],true);
					if(!empty($arrids['all_ids'])){
						$arrDataToupdate = [
							'event_details_of_all_ids' => $this->fetchEventDetailsByIds(implode(",", $arrids['all_ids'])),
							'status_get_details'=>1
						];
						$whereT = "id={$arrVal['id']}";
                        $this->common_model->updateData('eventbrite_ticket_ids', $arrDataToupdate, $whereT);
						echo $arrVal['id']."updated!.<br/>";
					}
				}
			}
		} else if (empty($varIsInsertToEventTable)) {
			$totalPages = 35;
			$varPage = !empty($this->request->getPost('page', true)) ? $this->request->getPost('page', true) : 1;
			for ($i = $varPage; $i <= $totalPages; $i++) {
				echo "page-" . "$i" . "<br>";
				$html = $this->getScrapedData($i);
				if (!$html) {
					continue;
				}
				$dom = new DOMDocument();
				@$dom->loadHTML($html);
				$xpath = new DOMXPath($dom);

				$events = $xpath->query("//div[contains(@class, 'event-card__vertical')]");
				if ($events->length == 0) {
					continue;
				}
				$seenEvents = [];
				foreach ($events as $event) {
					$eventIdNode = $xpath->query(".//a[contains(@class, 'event-card-link')]", $event);
					$eventId = $eventIdNode->item(0) ? $eventIdNode->item(0)->getAttribute('data-event-id') : '';
					if (empty($eventId) || isset($seenEvents[$eventId])) {
						continue;
					}
					$seenEvents[] = $eventId;
				}
				if (!empty($seenEvents)) {
					$arrEventIds = ['all_ids' => $seenEvents];
					$arrDataToinsert = [
						'eventbrite_t_ids' => json_encode($arrEventIds)
					];
					$lastId = $this->common_model->addData('eventbrite_ticket_ids', $arrDataToinsert);
				} else {
					continue;
				}
			}
		}
	}

	private function getScrapedData($page = 1)
	{
		$url = "https://www.eventbrite.com/d/united-states/all-events/?subcategories=3027&page={$page}";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');
		$html = curl_exec($ch);
		curl_close($ch);
		return $html;
	}

	/**
	 * fetch the description from api
	*/
	private function fetchEventDetailedDescrioptionById($eventIds){
		// get event detail from event brite api
		$url = "https://www.eventbriteapi.com/v3/events/{$eventIds}/description";
		//exit;
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->varEventBritePrivateToken
			),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}

	private function fetchEventDetailsByIds($eventIds)
	{
		// get event detail from event brite api
		$url = "https://www.eventbriteapi.com/v3/events?event_ids={$eventIds}&expand=%2Cticket_availability%2Corganizer%2Cvenue";
		//exit;
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->varEventBritePrivateToken
			),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}
}
