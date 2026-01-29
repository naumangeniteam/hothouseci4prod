<?php
namespace App\Controllers\api;

use App\Controllers\BaseController;

use App\Models\CommonModel;
use App\Libraries\GenerateLogs;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\EmailtemplateModel;
use App\Models\PaginationModel;

class GetSearchPageData extends BaseController {
	
	protected $commonModel;
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
		
		// Load models by instantiation
		$this->commonModel        = new CommonModel();
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
	 * * Function name : getsearchbyid
	 * * Developed By : Megha Kumari
	 * * Purpose  : This function used for get blog  page List
	 * * Date : 01 May 2023
	 * * **********************************************************************/
	public function getsearchbyid()
	{	

		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP', logOutPut($this->request->getPost()));
		$result = [];

		if (requestAuthenticate(APIKEY, 'POST')) {

			$pageTitle = $this->request->getPost('page_title');
		
			if (empty($pageTitle)) {
				return $this->response->setJSON(outPut(0, lang('statictext_lang.SUCCESS_CODE'), lang('statictext_lang.STATE_ID_EMPTY'), $result));
			} else {
		
				// Get active sliders
				$where2['where'] = ['is_active' => '1'];
				$tbl2 = 'slider_tbl as ftable';
				$result['slider_tbl'] = $this->commonModel->getData('multiple', $tbl2, $where2);
		
				// Get blogs matching page_title
				$where637['where'] = ['page_title' => $pageTitle];
				$tbl67 = 'blog_tbl as ftable';
				$result['blog_tbl'] = $this->commonModel->getData('multiple', $tbl67, $where637);
		
				// Get events for today's date
				$today = date('Y-m-d');
				$where635['where'] = ['start_date' => $today];
				$tbl656 = 'event_tbl as ftable';
				$result['event_tbl_list'] = $this->commonModel->getData('multiple', $tbl656, $where635);
		
				// Get SEO section
				$where5['where'] = ['page_name' => 'Calendar Page', 'is_active' => '1'];
				$tbl5 = 'seo_tbl as ftable';
				$result['seo_section'] = $this->commonModel->getData('single', $tbl5, $where5);
		
				return $this->response->setJSON(outPut(1, lang('statictext_lang.SUCCESS_CODE'), lang('statictext_lang.SUCCESS_MSG'), $result));
			}
		
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