<?php
// defined("BASEPATH") or exit("No direct script access allowed");
namespace App\Controllers\front;
use App\Models\AdminModel;
use App\Models\EmailtemplateModel;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\CommonModel;
use App\Models\FrontModel;
use App\Libraries\Layouts;
use App\Libraries\Elastichh;
use App\Controllers\BaseController;
class Location extends BaseController
{
    protected $adminModel;
    protected $emailTemplateModel;
    protected $smsModel;
    protected $notificationModel;
    protected $common_model;
    protected $frontModel;
    protected $session;
    protected $layouts;
    protected $elastichh;
    protected $lang;
    public function __construct()
    {
        // error_reporting(E_ALL);
        // error_reporting(0);
        $this->adminModel = new AdminModel();
        $this->emailTemplateModel = new EmailtemplateModel();
        $this->smsModel = new SmsModel();
        $this->notificationModel = new NotificationModel();
        $this->common_model = new CommonModel();
        $this->frontModel = new FrontModel();
        $this->lang = service('language'); 
        $this->lang->setLocale('front');
        $this->layouts = new Layouts();
        $this->session = session();
        helper('common');
        // $this->load->library("pagination");
    }

/* * *********************************************************************
     * * Function name 	: index
     * * Developed By 	: Megha Kumari
     * * Purpose  		: This function for home page data
     * * Date 			: 18/01/23
     * * **********************************************************************/
    public function index()
    {
        // die;
        $data = [];
        $request = service('request'); // CI4 request instance
        $db= db_connect();
        if ($request->getGet('keyword')) {
            $search_term = $request->getGet('keyword');
            $data['Get_id'] = $search_term;

            $data["event_location_tbl1"] = $db->table('event_location_tbl')
                ->like('location_name', $search_term)
                ->where('is_active', "1")
                ->orderBy('location_name', 
                'ASC')
                ->get()
                ->getResultArray();
        } elseif ($request->getGet('keyword1')) {
            $search_term = $request->getGet('keyword1');
            $data['Get_id'] = $search_term;

            $venueQuery = $db->table('venue_tbl')
                ->where('venue_title', $search_term)
                ->where('is_active', "1")
                ->get()
                ->getResultArray();

            $data["venue_tbl"] = $venueQuery;

            if (!empty($data["venue_tbl"])) {
                $venue_id = $data["venue_tbl"][0]["id"];

                $tbl64 = "event_location_tbl as ftable";
                $shortField774 = "location_name ASC";
                $where634["where"] = ["venue_id" => $venue_id, 'is_active' => '1'];

                $data["event_location_tbl1"] = $this->common_model->getData("multiple", $tbl64, $where634, $shortField774);
            } else {
                $data["event_location_tbl1"] = [];
            }
        }
       
        if ($request->getPost("Savesubsc")) {
            $error = "NO";
        
            // Validation Rules
            $rules = [
                'email' => 'required|valid_email',
                'name' => 'required'
            ];
        
            // Custom Error Messages
            $messages = [
                'email' => [
                    'required' => 'Email Address is required',
                    'valid_email' => 'Enter a valid email address'
                ],
                'name' => [
                    'required' => 'Name is required'
                ]
            ];
        
            if (!$this->validate($rules, $messages)) {
                $this->session->setFlashdata("alert_error", "Please Enter All Details");
                return redirect()->back()->withInput();
            }
        
            // Data to Insert
            $param = [
                "email" => $this->request->getPost("email"),
                "name" => $this->request->getPost("name"),
                "creation_date" => date("Y-m-d H:i:s"),
                "status" => "A",
                "ip_address" => $this->request->getIPAddress()
            ];
        
            // Insert into database
            $alastInsertId = $this->common_model->addData("subscribe_tbl", $param);
        
            // Success Message
            $this->session->setFlashdata("alert_success", "Details Submitted Successfully");
        
            return redirect()->to("how_to_get_hh");
        }

        $where5["where"] = ["page_name" => "Calendar Page", 'is_active' =>'1'];
        $tbl5 = "seo_tbl as ftable";
        $shortField5 = "id DESC";

        $shortField656 = "type_name ASC";
        $data["seo_section"] = $this->common_model->getData(
            "single",
            $tbl5,
            $where5
        );

        $this->layouts->set_title($data["seo_section"]["title"]);
        $this->layouts->set_description($data["seo_section"]["description"]);
        $this->layouts->set_keyword($data["seo_section"]["keywords"]);
        $this->layouts->front_view("location", [], $data);
    }

    /* * *********************************************************************
     * * Function name 	: index
     * * Developed By 	: Megha Kumari
     * * Purpose  		: This function for home page data
     * * Date 			: 18/01/23
     * * **********************************************************************/
  /*  public function index()
    {
        $data = [];
        $data["Get_id"] = $_GET["keyword"];
        $where63["where"] = ["venue_title" => $this->request->getPost("keyword")];
        $tbl6 = "venue_tbl as ftable";
        $shortField6 = "id DESC";

        $shortField77 = "type_name ASC";
        $shortField64 = "id DESC";
        $data["venue_tbl"] = $this->common_model->getData(
            "multiple",
            $tbl6,
            $where63
        );

        $tbl64 = "event_location_tbl as ftable";
        $shortField64 = "id DESC";

        $shortField774 = "location_name ASC";
        $shortField644 = "id DESC";
        $where634["where"] = ["venue_id" => $data["venue_tbl"][0]["id"]];
        $data["event_location_tbl"] = $this->common_model->getData(
            "multiple",
            $tbl64,
            $where634,
            $shortField774
        );

        $tbl645 = "event_location_tbl as ftable";
        $shortField646 = "id DESC";

        $shortField7746 = "location_name DESC";
        $shortField6446 = "id DESC";
        $where6340["where"] = ["venue_id" => $data["venue_tbl"][0]["id"] , 'is_active' =>'1'];
        $data["event_location_tbl1"] = $this->common_model->getData(
            "multiple",
            $tbl645,
            $where6340,
            $shortField7746
        );


$tbl64 = "event_location_tbl as ftable";
        $shortField64 = "id DESC";

        $shortField774 = "location_name ASC";
        $shortField644 = "id DESC";
        $where634["where"] = ["location_name" => $this->request->getPost("keyword") , 'is_active' =>'1'];
        $data["event_location_tblse"] = $this->common_model->getData(
            "multiple",
            $tbl64,
            $where634,
            $shortField774
        ); 
        
        $tbl645 = "event_location_tbl as ftable";
        $shortField646 = "id DESC";

        $shortField7746 = "location_name DESC";
        $shortField6446 = "id DESC";
        $where6340["where"] = ["location_name" => $this->request->getPost("keyword") , 'is_active' =>'1'];
        $data["event_location_tbl1se"] = $this->common_model->getData(
            "multiple",
            $tbl645,
            $where6340,
            $shortField7746
        );

        $this->load->library("googlemaps");
        $config["center"] =
            '$data["event_location_tbl"][0]["latitude"], $data["event_location_tbl"][0]["longitude"]';
        $config["zoom"] = "auto";
        $this->googlemaps->initialize($config);
        $marker = [];
        $marker["position"] = "137.429, -122.1419";
        $this->googlemaps->add_marker($marker);
        $data["map"] = $this->googlemaps->create_map();

          if ($this->request->getPost("Savesubsc")):
            $error = "NO";
            $this->form_validation->set_rules(
                "email",
                "Email Address",
                "required"
            );
            $this->form_validation->set_rules("name", "Name", "required");
            $this->form_validation->set_message("trim|required", "Enter %s");

            if ($this->form_validation->run() && $error == "NO"):
                $param["email"] = $this->request->getPost("email");
                $param["name"] = $this->request->getPost("name");
                $param["creation_date"] = date("Y-m-d h:i:s");
                $param["status"] = "A";
                $alastInsertId = $this->common_model->addData(
                    "subscribe_tbl",
                    $param
                );
                $this->session->setFlashdata(
                    "alert_success",
                    lang("addsuccess")
                );
                $this->session->setFlashdata(
                    "alert_success",
                    "Details Submitted Successfully"
                );
                redirect("how_to_get_hh");
            else:
                $this->session->setFlashdata(
                    "alert_error",
                    "Please Enter All Details"
                );
            endif;
        endif;

        $where5["where"] = ["page_name" => "Calendar Page"];
        $tbl5 = "seo_tbl as ftable";
        $shortField5 = "id DESC";

        $shortField656 = "type_name ASC";
        $data["seo_section"] = $this->common_model->getData(
            "single",
            $tbl5,
            $where5
        );

        $this->layouts->set_title($data["seo_section"]["title"]);
        $this->layouts->set_description($data["seo_section"]["description"]);
        $this->layouts->set_keyword($data["seo_section"]["keywords"]);
        $this->layouts->front_view("location", [], $data);
    }*/
}
