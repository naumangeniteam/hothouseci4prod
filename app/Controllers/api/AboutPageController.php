<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\EmailtemplateModel;
use App\Libraries\GenerateLogs;

class AboutPageController extends BaseController
{
    protected $smsModel;
    protected $notificationModel;
    protected $emailtemplateModel;
    protected $commonModel;
    protected $generatelogs;
    protected $lang;
    protected $userAgent;
    protected $requestUrl;
    protected $methodName;

    public function __construct()
    {
        // Load Models
        $this->smsModel = new SmsModel();
        $this->notificationModel = new NotificationModel();
        $this->emailtemplateModel = new EmailtemplateModel();
        $this->commonModel = model('CommonModel'); // Assuming you have CommonModel

        // Load Helpers
        helper(['apidata']);

        // Language
        $this->lang = service('language');
        $this->lang->setLocale('api');

        // Capture Request Info
        $this->userAgent  = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $this->requestUrl = $_SERVER['REQUEST_URI'] ?? '';
        $this->methodName = $_SERVER['QUERY_STRING'] ?? '';

        // Load Custom Library
        $this->generatelogs = new GenerateLogs(['type' => 'lowers']);
    }

    /**
     * Get all About Page List
     */
    public function getAboutPageList()
    {
        $apiHeaderData = getApiHeaderData();
        $this->generatelogs->putLog('APP', logOutPut($this->request->getPost()));

        $result = [];

        if (requestAuthenticate(APIKEY, 'GET')) {
            // Banner Section
            $where = ['page_name' => 'About Page', 'is_active' => '1'];
            $result['banner'] = $this->commonModel->getData('multiple', 'banner_tbl as ftable', ['where' => $where], 'id DESC', 6, 0);

            // About Section
            $result['about'] = $this->commonModel->getData('multiple', 'about_us_tbl as ftable', ['where' => "is_active = '1'"], 'id DESC', 6, 0);

            // Our Team Section
            $result['about_team_tbl'] = $this->commonModel->getData('multiple', 'about_team_tbl as ftable', ['where' => "is_active = '1'"], 'id DESC', 6, 0);

            // SEO Section
            $result['seo_section'] = $this->commonModel->getData('single', 'seo_tbl as ftable', ['where' => $where]);

            return $this->response->setJSON(outPut(1, lang('statictext_lang.SUCCESS_CODE'), lang('statictext_lang.SUCCESS_MSG'), $result));
        }

        return $this->response->setJSON(outPut(0, lang('statictext_lang.FORBIDDEN_CODE'), lang('statictext_lang.FORBIDDEN_MSG'), $result));
    }

    /**
     * Submit Subscribe Form
     */
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
