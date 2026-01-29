<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\EmailtemplateModel;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\CommonModel;
use App\Libraries\Layouts;

class Event_detail extends BaseController
{
    protected $adminModel;
    protected $emailtemplateModel;
    protected $smsModel;
    protected $notificationModel;
    protected $commonModel;
    protected $layouts;
    protected $lang;

    public function __construct()
    {
        // 

        // $this->adminModel = new AdminModel();
        // $this->emailtemplateModel = new EmailtemplateModel();
        // $this->smsModel = new SmsModel();
        // $this->notificationModel = new NotificationModel();
        $this->commonModel = new CommonModel();
        $this->layouts = new Layouts();
        $this->lang = service('language'); 
        $this->lang->setLocale('front');
        helper(['common', 'form', 'url']);
        service('session');
    }

    public function index($editId = '')
    {
		
        $data = [];

        /********************************************Banner Section******************************/
        $where['where'] = ['page_name' => 'Calender', 'is_active' => '1'];
        $tbl = 'banner_tbl as ftable';
        $data['banner'] = $this->commonModel->getData('multiple', $tbl, $where);

        /********************************************About Section******************************/
        $where1['where'] = "is_active = '1'";
        $tbl1 = 'about_us_tbl as ftable';
        $data['about'] = $this->commonModel->getData('multiple', $tbl1, $where1, 'id DESC', 6, 0);

        /********************************************Our Team Section******************************/
        $where2['where'] = "is_active = '1'";
        $tbl2 = 'about_team_tbl as ftable';
        $data['about_team_tbl'] = $this->commonModel->getData('multiple', $tbl2, $where2, 'id DESC', 6, 0);

        /********************************************Seo Section******************************/
        $where5['where'] = ['page_name' => 'Calendar Page', 'is_active' => '1'];
        $tbl5 = 'seo_tbl as ftable';
        $data['seo_section'] = $this->commonModel->getData('single', $tbl5, $where5);

        /********************************************Subscribe form******************************/
        if ($this->request->getPost('Savesubsc')) {

            $error = 'NO';
            $validation = \Config\Services::validation();
            $validation->setRules([
                'email' => 'required',
                'name'  => 'required'
            ]);

            if ($validation->withRequest($this->request)->run() && $error == 'NO') {
                $param['email'] = $this->request->getPost('email');
                $param['name'] = $this->request->getPost('name');
                $param['creation_date'] = date('Y-m-d h:i:s');
                $param['status'] = 'A';
                $param['ip_address'] = $this->request->getIPAddress();


                //Mail Chimp API Code
				$email =  $param['email'];
				$first_name = $param['name'];
				$last_name = '';

				$api_key = getenv('MAILCHIMP_API_KEY'); // YOUR API KEY

				// server name followed by a dot. 
				// We use us13 because us13 is present in API KEY
				$server = 'us3.';

				$list_id = 'f15ad682db'; // YOUR LIST ID

				$auth = base64_encode('user:' . $api_key);

				$data = array(
					'apikey'        => $api_key,
					'email_address' => $email,
					'status'        => 'subscribed',
					'merge_fields'  => array(
						'FNAME' => $first_name,
						'LNAME'    => $last_name
					)
				);
				$json_data = json_encode($data);

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


                $subscribe = $this->commonModel->subscribeEmail($param['email']);
                if (empty($subscribe)) {
                    $this->commonModel->addData('subscribe_tbl', $param);
                    session()->setFlashdata('alert_success', 'Details Submitted Successfully');
                } else {
                    session()->setFlashdata('alert_error', 'Email Id already used');
                }
                return redirect()->to('event_detail');
            } else {
                session()->setFlashdata('alert_error', 'Please Enter All Details');
            }
        }

        /********************************************Event List Section******************************/
        $db = \Config\Database::connect();
        $builder = $db->table('event_tbl');
        $builder->select('event_tbl.*, event_tbl.cover_image as event_image, event_tbl.cover_url as event_cover, 
                          artist_tbl.artist_name, artist_tbl.artist_image, artist_tbl.artist_url, artist_tbl.cover_image, 
                          artist_tbl.cover_url, artist_tbl.buy_now_link, artist_tbl.website_link, artist_tbl.artist_bio');
        $builder->join('artist_tbl', 'artist_tbl.id = event_tbl.artist_id', 'left');
        $builder->where('event_tbl.event_id', $editId);
        $builder->where('event_tbl.is_active', "1");
        $query1 = $builder->get();

        $data["event_tbl"] = $query1->getResultArray();

        if (!empty($data["event_tbl"])) {
            foreach ($data['event_tbl'] as &$event) {
                $event_id = $event['event_id'];

                // Fetch Event Tags
                $builder = $db->table('event_tags_tbl')->select('event_tags')->where('event_id', $event_id);
                $event_tag_query = $builder->get()->getResultArray();
                $event_tag_names = [];
                foreach ($event_tag_query as $event_data) {
                    $event_tag_names[] = $event_data['event_tags'];
                }
                $event['event_tags'] = array_unique($event_tag_names);

                // Fetch Jazz Types
                $builder = $db->table('event_jazz_tbl')
                    ->select('jazz_types.name')
                    ->join('jazz_types', 'jazz_types.id = event_jazz_tbl.event_jazz_types_id', 'left')
                    ->where('event_jazz_tbl.event_id', $event_id);
                $jazz_type_query = $builder->get()->getResultArray();

                $jazz_type_names = [];
                foreach ($jazz_type_query as $jazz_type) {
                    $jazz_type_names[] = $jazz_type['name'];
                }
                $event['jazz_types'] = $jazz_type_names;
            }
        } else {
            $builder = $db->table('submit_event_tbl');
            $builder->select('submit_event_tbl.*, artist_tbl.artist_name, artist_tbl.artist_image, artist_tbl.artist_url, 
                              artist_tbl.cover_image, artist_tbl.cover_url, artist_tbl.buy_now_link, artist_tbl.website_link, 
                              artist_tbl.artist_bio');
            $builder->join('artist_tbl', 'artist_tbl.id = submit_event_tbl.artist_id', 'left');
            $builder->where('submit_event_tbl.event_id', $editId);
            $builder->where('submit_event_tbl.is_active', "1");
            $query2 = $builder->get();
            $data["event_tbl"] = $query2->getResultArray();
        }

        if (empty($data["event_tbl"])) {
            // Handle if no rows found
        }
		
        $this->layouts->set_title($data['seo_section']['title'] ?? ''); 
	
		$this->layouts->set_description($data['seo_section']['description']?? '');
		$this->layouts->set_keyword($data['seo_section']['keywords']?? '');
		
		$this->layouts->front_view('event_detail', array(), $data);
		return "hhhhhdsfdh";
    }
}
