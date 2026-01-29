<?php
namespace App\Controllers\api;

use App\Controllers\BaseController;

use App\Models\CommonModel;
use App\Models\FrontModel;
use App\Libraries\GenerateLogs;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\EmailtemplateModel;

class GetCalendarPageData extends BaseController {
	
	protected $commonModel;
	protected $FrontModel;
	protected $postdata;
    protected $user_agent;
    protected $request_url;
    protected $method_name;
	protected $smsModel;
    protected $notificationModel;
    protected $emailTemplateModel;
    protected $generatelogs;
	
	public function  __construct() 	
	{ 
		// Turn off error reporting or adjust as needed
		error_reporting(0);

		// Instantiate models
		// Load models by instantiation
		$this->commonModel        = new CommonModel();
		$this->FrontModel        = new FrontModel();
		$this->smsModel = new SmsModel();
		$this->notificationModel = new NotificationModel();
		$this->emailTemplateModel = new EmailTemplateModel();

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
	 * * Function name : getcalendarpageList
	 * * Developed By : Megha Kumari
	 * * Purpose  : This function used for get all Calender  page List
	 * * Date : 01 May 2023
	 * * **********************************************************************/
	public function getcalendarpageList()
	{	

		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP', logOutPut($this->request->getPost()));
		$result = [];

		if (requestAuthenticate(APIKEY, 'GET')) {

			// Banner Section
			$where['where'] = ['page_name' => 'Calender', 'is_active' => '1'];
			$tbl = 'banner_tbl as ftable';
			$result['banner'] = $this->commonModel->getData('multiple', $tbl, $where);
		
			// About Section
			$where1['where'] = ['is_active' => '1'];
			$tbl1 = 'about_us_tbl as ftable';
			$result['about'] = $this->commonModel->getData('multiple', $tbl1, $where1, 'id DESC', 6, 0);
		
			// Our Team Section
			$where2['where'] = ['is_active' => '1'];
			$tbl2 = 'about_team_tbl as ftable';
			$result['about_team_tbl'] = $this->commonModel->getData('multiple', $tbl2, $where2, 'id DESC', 6, 0);
		
			// Seo Section
			$where5['where'] = ['page_name' => 'Calendar Page', 'is_active' => '1'];
			$tbl5 = 'seo_tbl as ftable';
			$result['seo_section'] = $this->commonModel->getData('single', $tbl5, $where5);
		
			// Venue Section
			$where51['where'] = ['is_active' => '1'];
			$tbl51 = 'venue_tbl as ftable';
			$result['venue_tbl'] = $this->commonModel->getData('multiple', $tbl51, $where51);
		
			// Event List Section
			$tbl6 = 'event_tbl as ftable';
			$whereEvent['where'] = [
				'start_date' => date('Y-m-d'),
				'end_date' => date('Y-m-d'),
				'is_active' => '1'
			];
			$result['event_tbl'] = $this->commonModel->getData('multiple', $tbl6, $whereEvent, 'event_title DESC');
		
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
	/* * *********************************************************************
		* * Function name : postcalendarpage
		* * Developed By : Megha Kumari
		* * Purpose  : This function used for Post Calender.
		* * Date : 02 May 2023
		* * **********************************************************************/
	public function postcalendarpage()
	{	
		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP', logOutPut($this->request->getPost()));
		$result = [];

		if (requestAuthenticate(APIKEY, 'POST')) {

			$startDate = $this->request->getPost('start_date');
			$date = $startDate ? date('Y-m-d', strtotime($startDate)) : null;
	
			$selectedDate = $this->request->getPost('selected_date');
	
			$result['event_tbl'] = $this->FrontModel->get_events($date);
			$result['datae'] = $selectedDate;
	
			return $this->response->setJSON(outPut(1, lang('statictext_lang.SUCCESS_CODE'), lang('statictext_lang.SUCCESS_MSG'), $result));
		} else {
	
			return $this->response->setJSON(outPut(0, lang('statictext_lang.FORBIDDEN_CODE'), lang('statictext_lang.FORBIDDEN_MSG'), $result));	
		}

	}	
}