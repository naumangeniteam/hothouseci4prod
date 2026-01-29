<?php
namespace App\Libraries;
defined('BASEPATH') or exit('No direct script access allowed');

class Permission
{
    protected $CI;

    public function __construct()
    {
        $this->CI = & get_instance();
        
    }

    public function checkPermission()
    {
        $this->load->model(array('admin_model'));
		$this->load->database();
        $url = $this->CI->uri->uri_string();
        $userId = $this->getUserId();
        $permissionData = $this->admin_model->getPermissionData($userId,$url);
        $data['permissionData'] = $permissionData;
        if($permissionData && count($permissionData)){
            return true;
        }
        show_error('You do not have permission to access this page.', 403);
    }
}