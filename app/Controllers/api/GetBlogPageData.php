<?php
namespace App\Controllers\api;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Libraries\GenerateLogs;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\EmailtemplateModel;
use App\Models\PaginationModel;

class GetBlogPageData extends BaseController {
	
    protected $commonModel;
	protected $smsModel;
    protected $notificationModel;
    protected $emailTemplateModel;
    protected $paginationModel;	
	protected $generatelogs;
    protected $user_agent;
    protected $request_url;
    protected $method_name;
	
	public function  __construct() 	
	{ 
          error_reporting(0);

		  // Load models by instantiation
		  $this->commonModel        = new CommonModel();
		  $this->smsModel = new SmsModel();
		  $this->notificationModel = new NotificationModel();
		  $this->emailTemplateModel = new EmailTemplateModel();
		  $this->paginationModel = new PaginationModel();

		  // Load helper functions
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
	 * * Function name : getblogpageList
	 * * Developed By : Megha Kumari
	 * * Purpose  : This function used for get blog  page List
	 * * Date : 01 May 2023
	 * * **********************************************************************/
	public function getblogpageList()
	{	
		$apiHeaderData = getApiHeaderData();
		$this->generatelogs->putLog('APP', logOutPut($this->request->getPost()));
		$result = [];	
		if (requestAuthenticate(APIKEY, 'GET')) {

			// Banner Section
			$where['where'] = ['page_name' => 'Blog Page', 'is_active' => '1'];
			$result['banner'] = $this->commonModel->getData('multiple', 'banner_tbl as ftable', $where, 'id DESC', 6, 0);

			
			// SEO Section
			$where5['where'] = ['page_name' => 'Blog Page', 'is_active' => '1'];
			$result['seo_section'] = $this->commonModel->getData('single', 'seo_tbl as ftable', $where5);
			
			// Pagination for blog posts
			$page = $this->request->getPost('page') ?? 1;
			$perPage = 6;
			$offset = ($page - 1) * $perPage;
			$whereBlog = ['is_active' => '1'];
			$totalResults = $this->commonModel->getCount('blog_tbl', $whereBlog);
			$totalPages = ceil($totalResults / $perPage);

			if ($page > $totalPages) {
				return $this->response->setJSON(outPut(0, lang('statictext_lang.page'), $result));
			} else {
				$result['blog_tbl'] = $this->commonModel->getData('multiple', 'blog_tbl as ftable', ['where' => $whereBlog], 'id DESC', $perPage, $offset);
				$result['current_page'] = (int)$page;
				$result['total_page'] = $totalPages;
	
				return $this->response->setJSON(outPut(1, lang('statictext_lang.SUCCESS_CODE'), lang('statictext_lang.SUCCESS_MSG'), $result));
			}
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