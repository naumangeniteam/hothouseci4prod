<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\EmailtemplateModel;
use App\Libraries\GenerateLogs;

class HomePageController extends BaseController
{
    protected $commonModel;
    protected $smsModel;
    protected $notificationModel;
    protected $emailtemplateModel;
    protected $generatelogs;
    protected $user_agent;
    protected $request_url;
    protected $method_name;

    public function __construct()
    {
        error_reporting(0);

        // Load models
        $this->commonModel        = new CommonModel();
        $this->smsModel           = new SmsModel();
        $this->notificationModel  = new NotificationModel();
        $this->emailtemplateModel = new EmailtemplateModel();

        // Load helpers
        helper(['apidata']);

        // Language
        service('language')->setLocale('api');

        // Set request info
        $this->user_agent  = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $this->request_url = $_SERVER['REQUEST_URI'] ?? '';
        $this->method_name = $_SERVER['QUERY_STRING'] ?? '';

        // Library
        $this->generatelogs = new GenerateLogs(['type' => 'lowers']);
    }

    /**
     * Get all home page data
     */
    public function getHomePageList()
    {
        $apiHeaderData = getApiHeaderData();
        $this->generatelogs->putLog('APP', logOutPut($this->request->getPost()));

        $result = [];

        if (requestAuthenticate(APIKEY, 'GET')) {

            // Banner Section
            $where['where'] = ['page_name' => 'Home Page', 'is_active' => '1'];
            $result['banner'] = $this->commonModel->getData('multiple', 'banner_tbl as ftable', $where, 'id DESC', 6, 0);

            // About Section
            $where1['where'] = "is_active = '1'";
            $result['about'] = $this->commonModel->getData('multiple', 'about_us_tbl as ftable', $where1, 'id DESC', 6, 0);

            // // Slider Section
            $where2['where'] = "is_active = '1'";
            $result['slider_tbl'] = $this->commonModel->getData('multiple', 'slider_tbl as ftable', $where2);

            // // Home Image Section
            $where3['where'] = "is_active = '1'";
            $result['home_image'] = $this->commonModel->getData('multiple', 'home_image as ftable', $where3);

            // // Blog Section
            $where4['where'] = ['is_home' => '1', 'is_active' => '1'];
            $result['blog_tbl'] = $this->commonModel->getData('multiple', 'blog_tbl as ftable', $where4, 'position ASC');

            if (!empty($result['blog_tbl'])) {
                $where44['where'] = ["page_title" => $result['blog_tbl'][0]["page_title"], 'is_active' => '1'];
                $result['blog_tbl1'] = $this->commonModel->getData('multiple', 'blog_tbl as ftable', $where44);
            }

            // // SEO Section
            $where5['where'] = ['page_name' => 'Home Page', 'is_active' => '1'];
            $result['seo_section'] = $this->commonModel->getData('single', 'seo_tbl as ftable', $where5);

            // Event + Venue Section
            $where63['where'] = ['start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'), 'is_active' => '1'];
            $event_tbldata = $this->commonModel->getData('multiple', 'event_tbl as ftable', $where63, 'event_title ASC');

            if (!empty($event_tbldata)) {
				$eventId = $event_tbldata[0]['venue_id'];

				$joinQuery = "
					SELECT e.*, v.* 
					FROM event_tbl e
					LEFT JOIN venue_tbl v ON e.venue_id = v.id
					WHERE e.venue_id = ?
					AND e.start_date = ?
					AND e.end_date = ?
					AND e.is_active = '1'
				";

				$result['event_tbl'] = $this->commonModel->executeCustomQuery(
					$joinQuery,
					[$eventId, date('Y-m-d'), date('Y-m-d')]
				);
			}


            // // Footer Section
            $where556['where'] = "is_active = '1'";
            $result['footer_tbl'] = $this->commonModel->getData('multiple', 'footer_tbl as ftable', $where556);

            // Home Slider Image
            $where5568['where'] = "is_active = '1'";
            $result['home_slider_image'] = $this->commonModel->getData('multiple', 'home_slider_image as ftable', $where5568, 'position DESC');

            $result['datae'] = date('Y-m-d');

            return $this->response->setJSON(outPut(1, lang('statictext_lang.SUCCESS_CODE'), lang('statictext_lang.SUCCESS_MSG'), $result));
        }

        return $this->response->setJSON(outPut(0, lang('statictext_lang.FORBIDDEN_CODE'), lang('statictext_lang.FORBIDDEN_MSG'), $result));
    }

    /**
     * Subscribe form submission
     */
    public function formSubmit()
    {
        $apiHeaderData = getApiHeaderData();
        $this->generatelogs->putLog('APP', logOutPut($this->request->getPost()));

        $result = [];

        if (requestAuthenticate(APIKEY, 'POST')) {

            $name  = $this->request->getPost('name');
            $email = $this->request->getPost('email');

            if (empty($name)) {
                return $this->response->setJSON(outPut(0, lang('statictext_lang.NAME_EMPTY'), $result));
            }

            if (empty($email)) {
                return $this->response->setJSON(outPut(0, lang('statictext_lang.EMAIL_EMPTY'), $result));
            }

            $insert_data = [
                'name'          => $name,
                'email'         => $email,
                'status'        => 'A',
                'creation_date' => date('Y-m-d H:i:s'),
            ];

            $insert_id = $this->commonModel->addData('subscribe_tbl', $insert_data);

            if (!empty($insert_id)) {
                return $this->response->setJSON(outPut(1, lang('statictext_lang.data_added'), $insert_data));
            }

            return $this->response->setJSON(outPut(0, lang('statictext_lang.data_not_insert'), $insert_data));
        }

        return $this->response->setJSON(outPut(0, lang('statictext_lang.FORBIDDEN_CODE'), lang('statictext_lang.FORBIDDEN_MSG'), $result));
    }
}
