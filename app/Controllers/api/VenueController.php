<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Libraries\GenerateLogs;

class VenueController extends BaseController
{
    protected $commonModel;
    protected $generatelogs;
	protected $user_agent;
	protected $request_url;
	protected $method_name;
	
    public function __construct()
    {
        error_reporting(0);

        helper('apidata');

        service('language')->setLocale('api');

        $this->commonModel  = new CommonModel();
        $this->generatelogs = new GenerateLogs(['type' => 'lowers']);

        $this->user_agent   = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $this->request_url  = $_SERVER['REDIRECT_URL'] ?? '';
        $this->method_name  = $_SERVER['REDIRECT_QUERY_STRING'] ?? '';
    }

    /**
     * Get Venue Page Data
     */
    public function getVenuePageList()
    {
        $this->generatelogs->putLog('APP', logOutPut($this->request->getPost()));
        $result = [];

        if (requestAuthenticate(APIKEY, 'GET')) {

            // Banner Section
            $result['banner'] = $this->commonModel->getData(
                'multiple',
                'banner_tbl',
                ['where' => ['page_name' => 'Venues', 'is_active' => '1']],
                'id DESC',
                6
            );

            // About Section
            $result['about'] = $this->commonModel->getData(
                'multiple',
                'about_us_tbl',
                ['where' => ['is_active' => '1']],
                'id DESC',
                6
            );

            // Our Team Section
            $result['about_team_tbl'] = $this->commonModel->getData(
                'multiple',
                'about_team_tbl',
                ['where' => ['is_active' => '1']],
                'id DESC',
                6
            );

            // Slider / Partners Section
            $result['slider_tbl'] = $this->commonModel->getData(
                'multiple',
                'slider_tbl',
                ['where' => ['is_active' => '1']]
            );

            // Venue Section
            $result['venue_tbl'] = $this->commonModel->getLastOrderByFields1(
                'multiple',
                'position',
                'venue_tbl',
                'is_active',
                '1',
                'venue_title, image, id'
            );

            // SEO Section
            $result['seo_section'] = $this->commonModel->getData(
                'single',
                'seo_tbl',
                ['where' => ['page_name' => 'Venue Page', 'is_active' => '1']]
            );

            return $this->response->setJSON(
                outPut(1, lang('statictext_lang.SUCCESS_CODE'), lang('statictext_lang.SUCCESS_MSG'), $result)
            );
        }

        return $this->response->setJSON(
            outPut(0, lang('statictext_lang.FORBIDDEN_CODE'), lang('statictext_lang.FORBIDDEN_MSG'), $result)
        );
    }

    /**
     * Subscribe Form Submission
     */
    public function formSubmit()
    {
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
            } else {
                return $this->response->setJSON(outPut(0, lang('statictext_lang.data_not_insert'), $insert_data));
            }
        }

        return $this->response->setJSON(
            outPut(0, lang('statictext_lang.FORBIDDEN_CODE'), lang('statictext_lang.FORBIDDEN_MSG'), $result)
        );
    }
}
