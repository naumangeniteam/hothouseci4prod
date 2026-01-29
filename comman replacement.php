    <?php

    use App\Models\AdminModel;
    use App\Models\EmailtemplateModel;
    use App\Models\SmsModel;
    use App\Models\NotificationModel;
    use App\Models\CommonModel;
    use App\Models\FrontModel;
    use App\Libraries\Layouts;
    use App\Libraries\Elastichh;

        protected $adminModel;
        protected $emailTemplateModel;
        protected $smsModel;
        protected $notificationModel;
        protected $common_model;

        protected $frontModel;
        protected $session;
        protected $layouts;
        protected $elastichh;


        $this->adminModel = new AdminModel();
            
            

            $this->emailTemplateModel = new EmailtemplateModel();
            $this->smsModel = new SmsModel();
            $this->notificationModel = new NotificationModel();
            $this->common_model = new CommonModel();
            $this->frontModel = new FrontModel();
            // Load Library
            $this->elastichh = new Elastichh(); // Custom Library

            // Load Helper
            helper('common','url'); 
            $this->layouts = new Layouts();
            $this->session = session();




            replace this

            if($this->request->getPost('Savesubsc')):
                
                $error					=	'NO';
                    $this->form_validation->set_rules('email', 'Email Address', 'required');
                    $this->form_validation->set_rules('name', 'Name', 'required');
                $this->form_validation->set_message('trim|required', 'Enter %s');
                
                if($this->form_validation->run() && $error == 'NO'): 
                    $param['email']				= 	$this->request->getPost('email');	
                    $param['name']				= 	$this->request->getPost('name');	
                    $param['creation_date']				= 	date('Y-m-d h:i:s');
                    $param['status']			=	'A'; 
                    $param['ip_address'] 		=	currentIp();
                    
                    

                else:
                    $this->session->setFlashdata('alert_error','Please enter all details');
                    endif;
                endif; 


                <?= session()->getFlashdata('errors')['name']; ?>


                replace with  1st way 
                protected $validation;
                $this->validation = service('validation');
                if ($this->validate($rules) && $error == 'NO') :
                else:
                    return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
                        
                endif;


                OR 2nd way 
                $validation = \Config\Services::validation(); 
                
                if ($this->request->getPost('Savesubsc')) {
                    $validationRules = [
                        'email' => 'required|valid_email',
                        'name'  => 'required'
                    ];
        
                    if (!$this->validate($validationRules)) {
                        $this->session->setFlashdata('alert_error', 'Please enter all details');
                        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
                    }
        
                    $param = [
                        'email'         => $this->request->getPost('email'),
                        'name'          => $this->request->getPost('name'),
                        'creation_date' => date('Y-m-d H:i:s'),
                        'status'        => 'A',
                        'ip_address'    =>$this->request->getIPAddress()
                    ];



                    ....
                    ...

                    return redirect()->to('/');  // replace with given link
            }


            if (!$this->validate($validationRules)) {
                return redirect()->back()->withInput()
                    ->with('validation', $this->validation); // ✅ Store validation errors
            }

    or way 3


    $validation = \Config\Services::validation(); 
                $validationRules = [
                        'title' => 'trim|required|max_length[256]',
                        'content'  => 'trim|required'
                    ];
                    $validation->setRules($validationRules);
                    if (!$validation->withRequest($this->request)->run()) {
                        // $this->session->setFlashdata('alert_error', 'Please enter all details');
                        return redirect()->back()->withInput()->with('validation',$validation);
                    }



            in view 
            <?php $validation = session()->getFlashdata('validation'); 
            
            or
            $validation = session()->get('validation'); 
            ?>

            <div class="col-md-12">
        <!-- Event Title -->
        <div class="form-group <?= (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('event_title') ? 'error' : '' ?>">
            <input type="text" 
                name="event_title" 
                value="<?= old('event_title') ? old('event_title') : (isset($EDITDATA['event_title']) ? stripslashes($EDITDATA['event_title']) : '') ?>"
                class="form-control" 
                placeholder="Event title" 
                required>
                
            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('event_title')) : ?>
                <span for="event_title" generated="true" class="help-inline text-danger">
                    <?= session()->getFlashdata('validation')->getError('event_title'); ?>
                
                </span>
            <?php endif; ?>
        </div>
    </div>

    session('validation') && session('validation')->hasError

    in Controllers


    redirect('submit_venue');
    return redirect()->to('submit_venue');



    auramaticstechnologies@gmail.com

    DrUP6u&L#is


    $this->layouts = new Layouts();
            $this->session = session();
            helper(['common','general']);


            <?php
    $db = \Config\Database::connect();
    ?>
    <?php
            // Fetch Venue Name
            $query = $db->table('venue_tbl')
                ->select('venue_title, position')
                ->where('id', $ALLDATAINFO['venue_id'])
                ->get()
                ->getRow();
            $getVenuName = $query->venue_title ?? 'N/A';
        


        // Fetch Event Location Name
        $query = $db->table('event_location_type')
            ->select('name, is_active')
            ->where('id', $ALLDATAINFO['event_location_type_id'])
            ->get()
            ->getRow();
        $getEventLocationName = $query->name ?? 'N/A';

        
        $query = $db->table('event_location_tbl')
    ->select('state')
    ->where('id', $ALLDATAINFO['save_location_id'])
    ->get()
    ->getRow();
    $getStateName = $query->venue_title ?? 'N/A';


        $query = $db->table('event_location_tbl')
                ->select('city')
                ->where('id', $ALLDATAINFO['save_location_id'])
                ->get()
                ->getRow();
            $getCityName = $query->venue_title ?? 'N/A';

            
            $query = $db->table('artist_tbl')
            ->select('artist_name')
            ->where('id', $ALLDATAINFO['artist_id'])
            ->get()
            ->getRow();
        $getartistName = $query->name ?? 'N/A';

        {ASSET_INCLUDE_URL}

        <?= esc($ASSET_INCLUDE_URL) ?>



        <?php $request = service('request'); ?>


        <?= isset($EDITDATA) && $EDITDATA ? 'Edit' : 'Add' ?>

        $EDITDATAEVENT ?? []



        protected $uri;
        $this->uri = service('uri');


        function changestatus($changeStatusId='',$statusType='')
        {  
        
            $changeStatusId = $this->uri->getSegment(4); 
            $statusType = $this->uri->getSegment(5);
        }
            function deletedata($deleteId='')
        {  
            
            $deleteId = $this->uri->getSegment(4);
        }


        use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


    if (!$this->validate($validationRules)) {
        $this->session->setFlashdata('alert_error', 'Please enter all details');
        return redirect()->back()->withInput()->with('validation', $this->validation);
    }



    for elastic

    http://localhost:8080/hhjsitemgmt/Adminmanageapidata/location_mapping