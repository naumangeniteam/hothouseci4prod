<?php
namespace App\Controllers\front;
use App\Controllers\BaseController;


use \DrewM\MailChimp\MailChimp;

class Magazine_locations extends BaseController
{

    public function  __construct()
    {
        
        //error_reporting(E_ALL ^ E_NOTICE);  
        error_reporting(0);
        $this->load->model(array('admin_model', 'emailtemplate_model', 'sms_model', 'notification_model', 'common_model'));
        $this->lang = service('language'); 
$this->lang->setLocale('front');
        helper('common');
    }

    public function index()
    {
        $data = array();

        $zipcode = $this->request->getPost('zipcode');

        if (!empty($zipcode)) {

            $data['location_tbl'] = $this->common_model->getLocationsWithinRadius($zipcode, 50);

            // echo"<pre>";print_r( $data['location_tbl']);die;

        } else {
           
            $data['location_tbl'] = $this->common_model->getMapLoc();

            // echo"<pre>";print_r( $data['location_tbl']);die;
        }

        $this->layouts->front_view('magazine_locations', array(), $data);
    }
}
