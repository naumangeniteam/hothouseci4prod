<?php

namespace App\Controllers\api;

use App\Controllers\BaseController;

use App\Models\CommonModel;
use App\Libraries\GenerateLogs;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\EmailtemplateModel;

class GetBlogDetailPageData extends BaseController {
			
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

		// Instantiate models
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
	 * * Function name : getblogdetailbyid
	 * * Developed By : Megha Kumari
	 * * Purpose  : This function used for get blog detail  page List
	 * * Date : 01 May 2023
	 * * **********************************************************************/
	public function getblogdetailbyid()
	{	

		$apiHeaderData 		=	getApiHeaderData();
		$this->generatelogs->putLog('APP', logOutPut($this->request->getPost()));
		$result = [];
        
		if (requestAuthenticate(APIKEY, 'POST')) {

			$slug = $this->request->getPost('slug');

			if (empty($slug)) {
				// 'STATE_ID_EMPTY' language line
				return $this->response->setJSON(outPut(0, lang('statictext_lang.SUCCESS_CODE'), lang('statictext_lang.STATE_ID_EMPTY'), $result));
			}
			
			// Assuming common_model is properly initialized or converted to CI4 model
			$blogData = $this->commonModel->getDataByParticularField('blog_tbl', 'slug', $slug);
			
			$where1['where'] = $blogData;
			$result['blog'] = $this->commonModel->getData('multiple', 'blog_tbl as ftable', $where1);

			// Banner Section
			$where['where'] = ['page_name' => 'About Page', 'is_active' => '1'];
			$result['banner'] = $this->commonModel->getData('multiple', 'banner_tbl as ftable', $where, 'id DESC', 6, 0);

			// SEO Section
			$where5 = ['where' => ['page_name' => 'Blog Page', 'is_active' => '1']];
			$tbl5 = 'seo_tbl as ftable';
			$result['seo_section'] = $this->commonModel->getData('single', 'seo_tbl as ftable', $where5);

			return $this->response->setJSON(outPut(1, lang('statictext_lang.SUCCESS_CODE'), lang('statictext_lang.SUCCESS_MSG'), $result));
		} else {
			return $this->response->setJSON(outPut(0, lang('statictext_lang.FORBIDDEN_CODE'), lang('statictext_lang.FORBIDDEN_MSG'), $result));
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