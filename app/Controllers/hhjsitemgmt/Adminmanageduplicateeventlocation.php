<?php

namespace App\Controllers\hhjsitemgmt;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\EmailtemplateModel;
use App\Models\CommonModel;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Libraries\Layouts;

class Adminmanageduplicateeventlocation extends BaseController
{
    protected $admin_model;
    protected $emailTemplateModel;
    protected $common_model;
    protected $smsModel;
    protected $notificationModel;
    protected $session;
    protected $layouts;
    protected $lang;

    public function __construct()
    {

        $this->admin_model          = new AdminModel();
        $this->emailTemplateModel  = new EmailtemplateModel();
        $this->common_model         = new CommonModel();
        $this->layouts = new Layouts();
        $this->smsModel            = new SmsModel();
        $this->notificationModel   = new NotificationModel();
        $this->session             = session();

        helper(['common','general','url', 'form']);

        // Set error reporting
        error_reporting(0);
        $this->lang = service('language'); 
        $this->lang->setLocale('admin');
    }

    /**
     * --------------------------------------------------------------------------
     * INDEX — Manage Duplicate Event Location (Converted from CI3)
     * --------------------------------------------------------------------------
     */
    public function index()
{
    $this->admin_model->authCheck('view_data');
    $this->admin_model->getPermissionType($data);

    $data['error']         = '';
    $data['activeMenu']    = 'adminmanageduplicateeventlocation';
    $data['activeSubMenu'] = 'adminmanageduplicateeventlocation';

    // ---------------------------------------------------------
    // 🔍 FILTER CONDITIONS
    // ---------------------------------------------------------
    $whereCon = [];

    // 1️⃣ General Search
    $sValue = $this->request->getGet('searchValue');
    if (!empty($sValue)) {
        $whereCon['like'][] = ['ftable.location_name' => $sValue];
        $whereCon['like'][] = ['ftable.location_address' => $sValue];
        $data['searchValue'] = $sValue;
    } else {
        $data['searchValue'] = '';
    }

    // 2️⃣ County Filter
    $selectedCounty = $this->request->getGet('county');
    if (!empty($selectedCounty)) {
        $whereCon['like'][] = ['ftable.county' => $selectedCounty];
        $data['countyValue'] = $selectedCounty;
    } else {
        $data['countyValue'] = '';
    }

    // 3️⃣ Venue Filter (Exact Match)
    $selectedVenue = $this->request->getGet('venue_id');
    if (!empty($selectedVenue)) {
        $whereCon['where']['ftable.venue_id'] = $selectedVenue;
        $data['venue_idValue'] = $selectedVenue;
    } else {
        $data['venue_idValue'] = '';
    }

    // ---------------------------------------------------------
    // 🔢 SORTING
    // ---------------------------------------------------------
    $sortField = $this->request->getGet('sort_by') ?? 'venue_id';
    $sortOrder = $this->request->getGet('order') ?? 'desc';
    $shortField = $sortField . ' ' . $sortOrder;

    // ---------------------------------------------------------
    // 📄 PAGINATION
    // ---------------------------------------------------------
    $tblName = 'event_location_tbl as ftable';
    $totalRows = $this->common_model->getDuplicateEventLocations('count', $tblName, $whereCon, $shortField, 0, 0);

    if ($this->request->getGet('showLength') == 'All') {
        $perPage = $totalRows;
        $data['perpage'] = 'All';
    } elseif ($this->request->getGet('showLength')) {
        $perPage = (int)$this->request->getGet('showLength');
        $data['perpage'] = $perPage;
    } else {
        $perPage = SHOW_NO_OF_DATA;
        $data['perpage'] = SHOW_NO_OF_DATA;
    }

    // ✅ Current Page (default to 1)
    $page = (int)($this->request->getGet('page') ?? 1);
    $page = max(1, $page);

    // ✅ Calculate Offset
    $offset = ($page - 1) * $perPage;

    // ✅ Build pagination links (keeping filters in URL)
    $baseUrl = getCurrentControllerPath('index');
    $this->session->set('userILCADMData', currentFullUrl());
    $suffix = '?' . http_build_query($_GET);

    $data['PAGINATION'] = adminPagination($baseUrl, $suffix, $totalRows, $perPage, $page);
    $data['forAction']  = $baseUrl;

    // ---------------------------------------------------------
    // 📊 DATA FETCH
    // ---------------------------------------------------------
    if ($totalRows > 0) {
        $first = $offset + 1;
        $last  = min($offset + $perPage, $totalRows);
        $data['first'] = $first;
        $data['noOfContent'] = "Showing $first-$last of $totalRows items";
    } else {
        $data['first'] = 1;
        $data['noOfContent'] = '';
    }

    $data['ALLDATA'] = $this->common_model->getDuplicateEventLocations(
        'multiple',
        $tblName,
        $whereCon,
        $shortField,
        $perPage,
        $offset
    );

    // ---------------------------------------------------------
    // 📚 SUPPORT DATA
    // ---------------------------------------------------------
    $data['venues'] = $this->common_model->getCategory();
    $data['counties'] = $this->common_model->getCounty();
    $data['event_location_types'] = $this->common_model->getEventlocationtype();

    // ---------------------------------------------------------
    // 🖥️ VIEW
    // ---------------------------------------------------------
    $this->layouts->set_title('Manage Duplicate Event Locations');
    $this->layouts->admin_view('duplicateeventlocation/index', [], $data);
}

    

    /**
     * --------------------------------------------------------------------------
     * Delete Data
     * --------------------------------------------------------------------------
     */
    public function delete($deleteId = null)
    {
        $this->admin_model->authCheck('delete_data');

        if (!empty($deleteId)) {
            $this->common_model->deleteData('event_location_tbl', 'id', (int)$deleteId);
            $this->session->setFlashdata('alert_success', lang('deletesuccess'));
        }

        return redirect()->to(getCurrentControllerPath('index'));
    }
}
