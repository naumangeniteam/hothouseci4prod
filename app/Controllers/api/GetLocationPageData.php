<?php
namespace App\Controllers\api;

use App\Controllers\BaseController;

use App\Models\CommonModel;
use App\Libraries\GenerateLogs;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\EmailtemplateModel;

class GetLocationPageData extends BaseController {
	
	protected $commonModel;
	protected $postdata;
    protected $user_agent;
    protected $request_url;
    protected $method_name;
	protected $smsModel;
    protected $notificationModel;
    protected $emailTemplateModel;
    protected $generatelogs;
	protected $db;
	
	public function  __construct() 	
	{ 
		// Turn off error reporting or adjust as needed
		error_reporting(0);
		
		// Load models by instantiation
		$this->commonModel        = new CommonModel();
		$this->smsModel = new SmsModel();
		$this->notificationModel = new NotificationModel();
		$this->emailTemplateModel = new EmailTemplateModel();
		$this->db = \Config\Database::connect();

		// Load helper 
		helper('apidata');

		// Language service
		service('language')->setLocale('api');

		// Set request info
		$this->user_agent  = $_SERVER['HTTP_USER_AGENT'] ?? '';
		$this->request_url = $_SERVER['REQUEST_URI'] ?? '';
		$this->method_name = $_SERVER['QUERY_STRING'] ?? '';

		// Library
		$this->generatelogs = new GenerateLogs(['type' => 'lowers']);
	}

	
	/* * *********************************************************************
	 * * Function name : getlocationpageList
	 * * Developed By : Megha Kumari
	 * * Purpose  : This function used for get Location  page List
	 * * Date : 01 May 2023
	 * * **********************************************************************/
	public function getlocationpageList()
	{	

		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP', logOutPut($this->request->getPost()));
		$result = [];

		if (requestAuthenticate(APIKEY, 'POST')) {

			$search_term = $this->request->getPost("location_name");
			$result = [];
		
			$eventLocationBuilder = $this->db->table('event_location_tbl');
			$eventLocationBuilder->where('location_name', $search_term);
			$eventLocationBuilder->where('is_active', '1');
			$query1 = $eventLocationBuilder->get();
		
			if ($query1->getNumRows() > 0) {
				$result["event_location_tbl1"] = $query1->getResultArray();
		
			} else {
				$venueBuilder = $this->db->table('venue_tbl');
				$venueBuilder->where('venue_title', $search_term);
				$venueBuilder->where('is_active', '1');
				$query2 = $venueBuilder->get();
		
				if ($query2->getNumRows() > 0) {
					$result["venue_tbl"] = $query2->getResultArray();
		
					$tbl64 = "event_location_tbl as ftable";
					$shortField774 = "location_name ASC";
					$where634["where"] = [
						"venue_id" => $result["venue_tbl"][0]["id"],
						"is_active" => "1"
					];
		
					$result["event_location_tbl1"] = $this->commonModel->getData("multiple", $tbl64, $where634, $shortField774);
				}
			}
		
			return $this->response->setJSON(outPut(1, lang('statictext_lang.SUCCESS_CODE'), lang('statictext_lang.SUCCESS_MSG'), $result));
		
		} else {
			return $this->response->setJSON(outPut(0, lang('statictext_lang.FORBIDDEN_CODE'), lang('statictext_lang.FORBIDDEN_MSG'), []));
		}
        
	}
/* * *********************************************************************
		* * Function name : formsubmit
		* * Developed By : Megha Kumari
		* * Purpose  : This function used for submit Subscribe Form.
		* * Date : 02 May 2023
		* * **********************************************************************/
    public function formSubmit()
    {
        $apiHeaderData = getApiHeaderData();
        $this->generatelogs->putLog('APP', logOutPut($this->request->getPost()));

        $result = [];

        if (requestAuthenticate(APIKEY, 'POST')) {
            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');

            if (empty($name)) {
                return $this->response->setJSON(outPut(0, lang('statictext_lang.NAME_EMPTY'), $result));
            }

            if (empty($email)) {
                return $this->response->setJSON(outPut(0, lang('statictext_lang.EMAIL_EMPTY'), $result));
            }

            $insertData = [
                'name'          => $name,
                'email'         => $email,
                'status'        => 'A',
                'creation_date' => date('Y-m-d H:i:s'),
            ];

            $insertId = $this->commonModel->addData('subscribe_tbl', $insertData);

            if (!empty($insertId)) {
                return $this->response->setJSON(outPut(1, lang('statictext_lang.data_added'), $insertData));
            }

            return $this->response->setJSON(outPut(0, lang('statictext_lang.data_not_insert'), $insertData));
        }

        return $this->response->setJSON(outPut(0, lang('statictext_lang.FORBIDDEN_CODE'), lang('statictext_lang.FORBIDDEN_MSG'), $result));
    }
	
}