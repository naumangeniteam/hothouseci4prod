<?php
namespace App\Controllers\api;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Libraries\GenerateLogs;

class SubmitEventController extends BaseController
{
    protected $commonModel;
    protected $generatelogs;
    protected $lang;

    public function __construct()
    {
        // Disable error reporting (optional)
        error_reporting(0);

        // Load helper
        helper('apidata');

        // Load language service
        $this->lang = service('language');
        $this->lang->setLocale('api');

        // Initialize models and libraries
        $this->commonModel = new CommonModel();
        $this->generatelogs = new GenerateLogs(['type' => 'lowers']);
    }

    // GET Submit Event Page Data
    public function getSubmitEventPageList()
    {
        $this->generatelogs->putLog('APP', logOutPut($_POST));
        $result = [];

        if (requestAuthenticate(APIKEY, 'GET')) {
            // Banner Section
            $where = ['page_name' => 'Submit Event'];
            $result['banner'] = $this->commonModel->getData('multiple', 'banner_tbl as ftable', ['where' => $where], 'id DESC', 6, 0);

            // Slider Section
            $where2 = ['where' => "is_active = '1'"];
            $result['slider_tbl'] = $this->commonModel->getData('multiple', 'slider_tbl as ftable', $where2);

            // Location and Venues
            $result['location'] = $this->commonModel->getLocation();
            $result['venues'] = $this->commonModel->getCategory();

             return $this->response->setJSON( outPut(1, lang('statictext_lang.SUCCESS_CODE'), lang('statictext_lang.SUCCESS_MSG'), $result));
        } else {
             return $this->response->setJSON( outPut(0, lang('statictext_lang.FORBIDDEN_CODE'), lang('statictext_lang.FORBIDDEN_MSG'), $result));
        }
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

    // POST Get Location
    public function getLocation()
    {
        $this->generatelogs->putLog('APP', logOutPut($this->request->getPost()));
        $result = [];

        if (requestAuthenticate(APIKEY, 'POST')) {

            $location_name = $this->request->getPost('location_name');

			if (empty($location_name)) {
				return $this->response->setJSON(outPut(0, lang('statictext_lang.SUCCESS_CODE'), lang('statictext_lang.LOCATION_EMPTY'), $result));
			}

            $where = $this->commonModel->getDataByParticularField('event_location_tbl', 'location_name', $location_name);
            $result['dataQuery'] = $this->commonModel->getData('multiple', 'event_location_tbl as ftable', ['where' => $where]);

            return $this->response->setJSON(
                outPut(1, lang('statictext_lang.SUCCESS_CODE'), lang('statictext_lang.SUCCESS_MSG'), $result)
            );
        } else {
            return $this->response->setJSON(
                outPut(0, lang('statictext_lang.FORBIDDEN_CODE'), lang('statictext_lang.FORBIDDEN_MSG'), $result)
            );
        }
    }

    // POST Submit Event Form
    public function submitEventForm()
    {
        $this->generatelogs->putLog('APP', logOutPut($this->request->getPost()));
        $result = [];

        if (!requestAuthenticate(APIKEY, 'POST')) {
            return $this->response->setJSON(
                outPut(0, lang('statictext_lang.FORBIDDEN_CODE'), lang('statictext_lang.FORBIDDEN_MSG'), $result)
            );
        }

        $requiredFields = [
            'event_title', 'event_descri', 'event_start', 'event_end', 'event_time', 'event_end_time',
            'radio-group', 'save_location_id', 'event_venue', 'event_address', 'latitude', 'longitude',
            'venue_website', 'venue_phone', 'venue_id', 'cover', 'contact_person', 'contact_lastname', 'email'
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->request->getPost($field))) {
                return $this->response->setJSON(
                    outPut(0, lang('statictext_lang.FORBIDDEN_CODE'), lang('statictext_lang.' . $field), $result)
                );
            }
        }

        $insert_data = [
            'event_title' => $this->request->getPost('event_title'),
            'description' => $this->request->getPost('event_descri'),
            'start_date' => $this->request->getPost('event_start'),
            'end_date' => $this->request->getPost('event_end'),
            'event_start_time' => $this->request->getPost('event_time'),
            'event_end_time' => $this->request->getPost('event_end_time'),
            'repeating_event' => $this->request->getPost('radio-group'),
            'save_location_id' => $this->request->getPost('save_location_id'),
            'location_name' => $this->request->getPost('event_venue'),
            'location_address' => $this->request->getPost('event_address'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
            'website' => $this->request->getPost('venue_website'),
            'phone_number' => $this->request->getPost('venue_phone'),
            'venue_id' => $this->request->getPost('venue_id'),
            'cover_charge' => $this->request->getPost('cover'),
            'contact_person' => $this->request->getPost('contact_person'),
            'contact_lastname' => $this->request->getPost('contact_lastname'),
            'email' => $this->request->getPost('email'),
            'is_active' => '1',
            'creation_date' => date('Y-m-d H:i:s'),
        ];

        $insert_id = $this->commonModel->addData('submit_event_tbl', $insert_data);
        return $this->response->setJSON(
            outPut(1, lang('statictext_lang.SUCCESS_CODE'), lang('statictext_lang.SUCCESS_MSG'), $insert_data)
        );
    }
}
