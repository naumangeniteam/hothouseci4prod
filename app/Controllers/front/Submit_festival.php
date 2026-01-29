<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\EmailtemplateModel;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\CommonModel;
use App\Libraries\Layouts;
use \DrewM\MailChimp\MailChimp;

class Submit_festival extends BaseController
{
    protected $adminModel;
    protected $emailtemplateModel;
    protected $smsModel;
    protected $notificationModel;
    protected $common_model;
    protected $layouts;
    protected $validation;
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        error_reporting(0);
        
        $this->emailtemplateModel = new EmailtemplateModel();
        $this->common_model = new CommonModel();
        $this->layouts = new Layouts();
        helper(['common']);
        $this->session = service('session');
        $this->validation = service('validation');
    }

    public function index()
    {
        $data = [];

        if (!$this->common_model) {
            throw new \Exception("Model not loaded properly!");
        }

        /******************************************** Banner Section ******************************/
        $data['banner'] = $this->common_model->getData(
            'multiple', 
            'banner_tbl', 
            ['page_name' => 'Submit Festival', 'is_active' => '10'], 
            'id DESC', 
            6, 
            0
        );

        /******************************************** Our Partners Section ******************************/
        $data['slider_tbl'] = $this->common_model->getData(
            'multiple', 
            'slider_tbl', 
            ['is_active' => '1']
        );

        $data['location'] = $this->common_model->getLocation();
        $data['venues'] = $this->common_model->getCategory();
        $data['jazzTypes'] = $this->common_model->getCategoryJazz();
        $data['artistTypes'] = $this->common_model->getCategoryArtist();

        helper('captcha');
        if ($this->request->getPost('captcha')) {
            if ($this->request->getPost('SaveChanges')) {
                $error = 'NO';

                //  Validation Rules
                $this->validation->setRules([
                    'captcha' => [
                        'label' => 'Captcha',
                        'rules' => 'required',
                        'errors' => [
                            'required' => 'Please enter the {field}.'
                        ]
                    ],
                    'festival_title' => [
                        'label' => 'Festival Title',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} is required.'
                        ]
                    ],
                    'festival_description' => [
                        'label' => 'Festival Description',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} is required.'
                        ]
                    ],
                    'festival_start_date' => [
                        'label' => 'Festival Start Date',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} is required.'
                        ]
                    ],
                    'festival_end_date' => [
                        'label' => 'Festival End Date',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} is required.'
                        ]
                    ],
                    'festival_website' => [
                        'label' => 'Website',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} is required.'
                        ]
                    ],
                    'festival_headliners' => [
                        'label' => 'Headliners',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} is required.'
                        ]
                    ],
                    'festival_lineup' => [
                        'label' => 'Lineup',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} is required.'
                        ]
                    ],
                    'festival_city' => [
                        'label' => 'Festival City',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} is required.'
                        ]
                    ],
                    'festival_state' => [
                        'label' => 'Festival State',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} is required.'
                        ]
                    ],
                    'festival_ticket_price' => [
                        'label' => 'Ticket Price Range',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} is required.'
                        ]
                    ],
                    'venue_id' => [
                        'label' => 'Select Location',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} is required.'
                        ]
                    ],
                    'festival_contact_title' => [
                        'label' => 'Contact Title',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} is required.'
                        ]
                    ],
                    'festival_contact_name' => [
                        'label' => 'Contact Name',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} is required.'
                        ]
                    ],
                    'festival_contact_email' => [
                        'label' => 'Contact Email',
                        'rules' => 'required|valid_email',
                        'errors' => [
                            'required' => '{field} is required.',
                            'valid_email' => 'Please enter a valid {field}.'
                        ]
                    ],
                    'festival_contact_phone' => [
                        'label' => 'Contact Phone',
                        'rules' => 'required',
                        'errors' => [
                            'required' => '{field} is required.'
                        ]
                    ]
                ]);

                // Custom validation for venue address
                if (!$this->check_venue_address()) {
                    $this->session->setFlashdata('error', 'Venue Address is required. Either select a saved venue or enter a new address manually.');
                    return redirect()->to('submit_festival');
                }

                // Custom validation for image
                if (!$this->validate_image()) {
                    $this->session->setFlashdata('error', 'Image is required and must be a file of type: jpg, jpeg, png, gif.');
                    return redirect()->to('submit_festival');
                }

                //  Spam Trap Handling
                $trap = $this->request->getPost('trap');
                if (!empty(trim($trap))) {
                    $this->session->setFlashdata('error', 'Spam detected. Submission rejected.');
                    return redirect()->to('submit_festival');
                }

                if ($this->validation->withRequest($this->request)->run() && $error == 'NO') {
                    //  Handle File Upload
                    $uploadedFileName = '';
                    if (!empty($_FILES['image']['name'])) {
                        $file = $this->request->getFile('image');
                        if ($file->isValid() && !$file->hasMoved()) {
                            $newName = $file->getRandomName();
                            $file->move(FCPATH . 'assets/front/img/festivalimage', $newName);
                            $uploadedFileName = $newName;
                        }
                    }

                    //  Collect Form Data
                    $param = [
                        'festival_name' => $this->request->getPost('festival_title'),
                        'summary' => $this->request->getPost('festival_description'),
                        'start_date' => $this->request->getPost('festival_start_date'),
                        'end_date' => $this->request->getPost('festival_end_date'),
                        'date_info' => $this->request->getPost('festival_date_info'),
                        'festival_website' => $this->request->getPost('festival_website'),
                        'headliners' => $this->request->getPost('festival_headliners'),
                        'lineup' => $this->request->getPost('festival_lineup'),
                        'city' => $this->request->getPost('festival_city'),
                        'state' => $this->request->getPost('festival_state'),
                        'ticket_price' => $this->request->getPost('festival_ticket_price'),
                        'latitude' => $this->request->getPost('latitude'),
                        'longitude' => $this->request->getPost('longitude'),
                        'save_location_id' => $this->request->getPost('save_location_id') ?: null,
                        'venue_id' => $this->request->getPost('venue_id'),
                        'contact_title' => $this->request->getPost('festival_contact_title'),
                        'contact_name' => $this->request->getPost('festival_contact_name'),
                        'contact_email' => $this->request->getPost('festival_contact_email'),
                        'contact_phone' => $this->request->getPost('festival_contact_phone'),
                        'is_advertise' => $this->request->getPost('festival_advertise'),
                        'direct_list' => $this->request->getPost('direct_list'),
                        'creation_date' => date('Y-m-d H:i:s'),
                        'added_by' => 'user'
                    ];

                    // Add image if uploaded
                    if ($uploadedFileName) {
                        $param['image'] = $uploadedFileName;
                    }

                    // Handle location data
                    if (!empty($this->request->getPost('save_location_id'))) {
                        $param['location_address'] = $this->request->getPost('location_address');
                        $param['latitude'] = $this->request->getPost('location_latitude');
                        $param['longitude'] = $this->request->getPost('location_longitude');
                        $param['phone_number'] = $this->request->getPost('phone_number');
                    } else {
                        $param['location_address'] = $this->request->getPost('venue_address');
                        $param['latitude'] = $this->request->getPost('latitude');
                        $param['longitude'] = $this->request->getPost('longitude');
                    }

                    $param['location_name'] = $this->request->getPost('location_name') ?: null;
                    $param['website'] = $this->request->getPost('location_website') ?: null;
                    $param['phone_number'] = $this->request->getPost('location_phone_number') ?: null;

                    //  Insert Data into Database
                    $lastInsertId = $this->common_model->addData('festival_tbl', $param);

                    if ($lastInsertId) {
                        // Send emails
                        $this->emailtemplateModel->sendnewFestivalEmail($param);
                        $this->emailtemplateModel->sendFestivalEmailToCustomer($param);
                        $this->session->setFlashdata('success', 'Festival Details Submitted Successfully. Please give us 24 hours to approve the posting.');
                    } else {
                        $this->session->setFlashdata('error', 'There was a problem saving your submission. Please try again.');
                    }
                    return redirect()->to('submit_festival');
                }
            }
        } else {
            $data['captchaerror'] = "Captcha doesn't match.";
        }

        /********************************************Subscribe form******************************/
        if ($this->request->getPost('Savesubsc')) {
            $error = 'NO';

            //  Validation Rules
            $this->validation->setRules([
                'email' => 'required|valid_email',
                'name' => 'required'
            ]);

            if ($this->validation->withRequest($this->request)->run() && $error == 'NO') {
                $param = [
                    'email' => $this->request->getPost('email'),
                    'name' => $this->request->getPost('name'),
                    'creation_date' => date('Y-m-d h:i:s'),
                    'status' => 'A',
                    'ip_address' => $this->request->getIPAddress()
                ];

                // Mail Chimp API Code
                $email = $param['email'];
                $first_name = $param['name'];
                $last_name = '';

                $api_key = getenv('MAILCHIMP_API_KEY');
                $server = 'us3.';
                $list_id = 'f15ad682db';
                $auth = base64_encode('user:' . $api_key);

                $data_mailchimp = array(
                    'apikey' => $api_key,
                    'email_address' => $email,
                    'status' => 'subscribed',
                    'merge_fields' => array(
                        'FNAME' => $first_name,
                        'LNAME' => $last_name
                    )
                );
                $json_data = json_encode($data_mailchimp);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://' . $server . 'api.mailchimp.com/3.0/lists/' . $list_id . '/members');
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Authorization: Basic ' . $auth
                ));
                curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

                $result = curl_exec($ch);

                $subscribe = $this->common_model->subscribeEmail($param['email']);

                if (empty($subscribe)) {
                    $alastInsertId = $this->common_model->addData('subscribe_tbl', $param);
                    $this->session->setFlashdata('alert_success', 'Details Submitted Successfully');
                } else {
                    $this->session->setFlashdata('alert_error', 'Email Id already used');
                }
                return redirect()->to('submit_festival');
            } else {
                $this->session->setFlashdata('alert_error', 'Please Enter All Details');
            }
        }

        /********************************************Seo Section******************************/
        $data['seo_section'] = $this->common_model->getData(
            'single', 
            'seo_tbl', 
            ['page_name' => 'Submit Festival Page', 'is_active' => '1']
        );

        $this->layouts->set_title($data['seo_section']['title']);
        $this->layouts->set_description($data['seo_section']['description']);
        $this->layouts->set_keyword($data['seo_section']['keywords']);
        $this->layouts->front_view('submit_festival', array(), $data);
    }

    public function location()
    {
        $location_id = $this->request->getGet('LocationId');

        $query = $this->db->table('event_location_tbl')
            ->where('id', $location_id)
            ->where('is_active', '1')
            ->get()
            ->getRow();

        if ($query) {
            return $this->response->setJSON([
                'location_name' => $query->location_name,
                'location_address' => $query->location_address,
                'latitude' => $query->latitude,
                'longitude' => $query->longitude,
                'website' => $query->website,
                'phone_number' => $query->phone_number,
                'venue_id' => $query->venue_id
            ]);
        } else {
            return $this->response->setJSON(['error' => 'No active location found']);
        }
    }

    private function check_venue_address()
    {
        $venue_address = $this->request->getPost('venue_address');
        $save_location_id = $this->request->getPost('save_location_id');

        if (empty($venue_address) && empty($save_location_id)) {
            return false;
        }

        return true;
    }

    private function validate_image()
    {
        if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'jfif'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed_types)) {
                return false;
            }

            return true;
        } else {
            return false;
        }
    }
}