<?php

namespace App\Controllers\hhjsitemgmt;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

use App\Models\AdminModel;
use App\Models\EmailtemplateModel;
use App\Models\SmsModel;
use App\Models\NotificationModel;
use App\Models\CommonModel;
use App\Models\FrontModel;
use App\Libraries\Layouts;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Controllers\BaseController;


class Adminmanageeventreport extends BaseController
{

	protected $admin_model;
    protected $emailTemplateModel;
    protected $smsModel;
    protected $notificationModel;
    protected $common_model;
    protected $elastichh;
	protected $frontModel;
	protected $session;
	protected $layouts;

	public function  __construct()
	{
		
		error_reporting(0);

		service('language')->setLocale('admin');
		helper(['common','general']);
		$this->admin_model = new AdminModel();
		$this->emailTemplateModel = new EmailtemplateModel();
        $this->smsModel = new SmsModel();
        $this->notificationModel = new NotificationModel();
        $this->common_model = new CommonModel();
        $this->layouts = new Layouts();
        $this->session = session();

	}

	function index()
	{

		$date = date("Y-m-d");
		$date = strtotime(date("Y-m-d", strtotime($date)) . "-2 months");
		$date = date("Y-m-d", $date);
		$this->admin_model->authCheck('view_data');
		$this->admin_model->getPermissionType($data);
		$data['error'] 						= 	'';
		$data['activeMenu'] 				= 	'adminmanageeventreport';
		$data['activeSubMenu'] 				= 	'adminmanageeventreport';
	
		if ($this->request->getGet('searchValue')) :
			$sValue							=	$this->request->getGet('searchValue');
			$whereCon['like'] = [
				'ftable.event_title' => $sValue
			  ];									  
			  $data['searchField'] = $sField ?? '';
			$data['searchValue'] 			= 	$sValue;
		else :
			$whereCon['like']		 		= 	"";
			$data['searchField'] 			= 	'';
			$data['searchValue'] 			= 	'';
		endif;
	
		$whereCon['where']		 			= 	"start_date >= '$date' ";
		$shortField 						= 	"ftable.event_id DESC";
		$baseUrl 							= 	base_url() . 'hhjsitemgmt/adminmanageeventreport/index';
		$this->session->set('userILCADMData', currentFullUrl());
		$qStringdata						=	explode('?', currentFullUrl());
		$suffix								= 	$qStringdata[1] ? '?' . $qStringdata[1] : '';
		$tblName 							= 	'event_tbl as ftable';
		$con = '';
		
		$totalRows = $this->common_model->getData_event_report('count', $tblName, $whereCon, $shortField, '0', '0');

		if($this->request->getGet('showLength') == 'All'):
			$perPage	 					= 	$totalRows;
			
			$data['perpage'] 				= 	$this->request->getGet('showLength');  
		elseif($this->request->getGet('showLength')):
			$perPage	 					= 	$this->request->getGet('showLength'); 
			// echo"<pre>";print_r($perPage);die;
			$data['perpage'] 				= 	$this->request->getGet('showLength'); 
		else:
			$perPage	 					= 	SHOW_NO_OF_DATA;
			$data['perpage'] 				= 	SHOW_NO_OF_DATA; 
		endif;
		$data['perpage'] = $perPage;

		// ✅ Get the `page` parameter from the GET request
		$page = $this->request->getGet('page') ?? 1;
        $page = max(1, (int)$page);
	
		// ✅ Generate pagination links
		$suffix = '?'.http_build_query($_GET); // Preserve filters in pagination
		$data['PAGINATION'] = adminPagination($baseUrl, $suffix, $totalRows, $perPage, $page);
		//echo"<pre>";print_r($data['PAGINATION']);die;
		$data['forAction'] = $baseUrl;
		if ($totalRows > 0) {
			// ✅ Ensure `$page` is always at least 1
			$page = $this->request->getGet('page') ?? 1;
			$page = max(1, (int)$page); // Ensure page starts from at least 1
		
			// ✅ Fix the offset for fetching paginated data
			$offset = ($page - 1) * $perPage;
		
			// ✅ Correct Serial Number Calculation
			$first = $offset + 1;
			$data['first'] = $first;
		
			$last = min($first + $perPage - 1, $totalRows);
			$data['noOfContent'] = "Showing $first-$last of $totalRows items";
		} else {
			$data['first'] = 1;
			$data['noOfContent'] = '';
			$offset = 0;
		}
		
		$sortField = $this->request->getPost('sort_by') ?: 'event_title';
		$sortOrder = $this->request->getPost('order') ?: 'asc';
		$shortField = $sortField . ' ' . $sortOrder;
		
		$data['ALLDATA'] 			    = 	$this->common_model->getData_event_report('multiple', $tblName, $whereCon, $shortField, $perPage, $offset);
		//in ci4 alice not used so in this fun use table name festival_tbl
		$data['ALLDATAFESTIVAL'] 		= 	$this->common_model->getData_festival('multiple', $tblName, $whereCon, $shortField, $perPage, $offset);
		
		// echo"<pre>";print_r($data['ALLDATAFESTIVAL']);die;
		// $result2 = [];
		// if (count($data['ALLDATAFESTIVAL'])) {
		// 	foreach ($data['ALLDATAFESTIVAL'] as $alldata) {
		// 		$result2[$alldata['venue_title']][$alldata['location_name']][] = $alldata;
		// 	}
		// }
		// $result = [];
		// if (count($data['ALLDATA'])) {
		// 	foreach ($data['ALLDATA'] as $alldata) {
		// 		$result[$alldata['venue_title']][$alldata['location_name']][] = $alldata;
		// 	}
		// }
		// $data['result'] = $result;
		// $data['result2'] = $result2;
		$result = [];


		// Organize ALLDATAFESTIVAL
		if (count($data['ALLDATAFESTIVAL'])) {
			foreach ($data['ALLDATAFESTIVAL'] as $alldata) {
				$result[$alldata['venue_title']][$alldata['location_name']][] = $alldata;
			}
		}

		// Organize ALLDATA and merge it with ALLDATAFESTIVAL entries
		if (count($data['ALLDATA'])) {
			foreach ($data['ALLDATA'] as $alldata) {
				$result[$alldata['venue_title']][$alldata['location_name']][] = $alldata;
			}
		}

		$data['result'] = $result; // Pass this merged result to the view
	
		$data['venues'] = $this->common_model->getCategory();
		$data['locations'] = $this->common_model->getLocation();

		$data['events'] = $this->common_model->totalEventsBySearch('multiple', $tblName, $whereCon, $shortField);
		
		$data['trashevent'] = $this->common_model->totalTrashevent();

		
		$this->layouts->set_title('Manage Event Report');
		$this->layouts->admin_view('eventreport/index', array(), $data);
	}

	// public function generate_report()
	// {
	// 	$date = date("Y-m-d");
	// 	$date = strtotime(date("Y-m-d", strtotime($date)) . "-2 months");
	// 	$date = date("Y-m-d", $date);
	// 	$this->admin_model->authCheck('view_data');
	// 	$this->admin_model->getPermissionType($data);
	// 	$data['error'] 						= 	'';
	// 	$data['activeMenu'] 				= 	'adminmanageeventreport';
	// 	$data['activeSubMenu'] 				= 	'adminmanageeventreport';

	// 	if ($this->request->getGet('searchValue')) :
	// 		$sValue							=	$this->request->getGet('searchValue');
	// 		$whereCon['like']			 	= 	"(ftable.event_title LIKE '%" . $sValue . "%'
	// 											)";
	// 		$data['searchField'] 			= 	$sField;
	// 		$data['searchValue'] 			= 	$sValue;
	// 	else :
	// 		$whereCon['like']		 		= 	"";
	// 		$data['searchField'] 			= 	'';
	// 		$data['searchValue'] 			= 	'';
	// 	endif;

	// 	$whereCon['where']		 			= 	"start_date >= '$date' ";
	// 	$shortField 						= 	"ftable.event_id DESC";
	// 	$baseUrl 							= 	base_url() . 'hhjsitemgmt/adminmanageeventreport/index';
	// 	$this->session->set('userILCADMData', currentFullUrl());
	// 	$qStringdata						=	explode('?', currentFullUrl());
	// 	$suffix								= 	$qStringdata[1] ? '?' . $qStringdata[1] : '';
	// 	$tblName 							= 	'event_tbl as ftable';
	// 	$con = '';

	// 	$sortField = $this->request->getPost('sort_by') ?: 'event_title';
	// 	$sortOrder = $this->request->getPost('order') ?: 'asc';
	// 	$shortField = $sortField . ' ' . $sortOrder;

	// 	$data['ALLDATA'] 					= 	$this->common_model->getData_event_report('multiple', $tblName, $whereCon, $shortField, $perPage, $page);
	// 	$result = [];
	// 	if (count($data['ALLDATA'])) {
	// 		foreach ($data['ALLDATA'] as $alldata) {
	// 			$result[$alldata['venue_title']][$alldata['location_name']][] = $alldata;
	// 		}
	// 	}
	// 	$data['result'] = $result;
	// 	// echo "<pre>";print_r($data['result']);die;
	// 	$this->layouts->set_title('Generate Report');
	// 	$this->layouts->admin_view('eventreport/generateReport', array(), $data);
	// }

	public function generate_report()
	{
		$date = date("Y-m-d");
		$date = strtotime(date("Y-m-d", strtotime($date)) . "-2 months");
		$date = date("Y-m-d", $date);
		$this->admin_model->authCheck('view_data');
		$this->admin_model->getPermissionType($data);
		$data['error'] = '';
		$data['activeMenu'] = 'adminmanageeventreport';
		$data['activeSubMenu'] = 'adminmanageeventreport';

		if ($this->request->getGet('searchValue')) :
			$sValue = $this->request->getGet('searchValue');
			$whereCon['like'] = "(ftable.event_title LIKE '%" . $sValue . "%')";
			$data['searchField'] = $sField;
			$data['searchValue'] = $sValue;
		else :
			$whereCon['like'] = "";
			$data['searchField'] = '';
			$data['searchValue'] = '';
		endif;

		$whereCon['where'] = "start_date >= '$date' ";
		$shortField = "ftable.event_id DESC";
		$baseUrl = base_url() . 'hhjsitemgmt/adminmanageeventreport/index';
		$this->session->set('userILCADMData', currentFullUrl());
		$qStringdata = explode('?', currentFullUrl());
		$suffix = $qStringdata[1] ? '?' . $qStringdata[1] : '';
		$tblName = 'event_tbl as ftable';
		$con = '';

		$sortField = $this->request->getPost('sort_by') ?: 'event_title';
		$sortOrder = $this->request->getPost('order') ?: 'asc';
		$shortField = $sortField . ' ' . $sortOrder;

		$data['ALLDATA'] = $this->common_model->getData_event_report('multiple', $tblName, $whereCon, $shortField, $perPage, $page);
		$result = [];

		if (count($data['ALLDATA'])) {
			foreach ($data['ALLDATA'] as $alldata) {
				// Build the result array with location_name as key
				$result[$alldata['venue_title']][$alldata['location_name']][] = $alldata;
			}

			// Sort the location_name alphabetically for each venue_title
			foreach ($result as $venue_title => &$locations) {
				ksort($locations); // Sorts the location_name alphabetically

				// Sort each location's events by start_date in ascending order
				foreach ($locations as &$events) {
					usort($events, function ($a, $b) {
						return strtotime($a['start_date']) - strtotime($b['start_date']);
					});
				}
			}
		}

		$data['result'] = $result;

		// echo "<pre>";print_r($data['result']);die;
		$this->layouts->set_title('Generate Report');
		$this->layouts->admin_view('eventreport/generateReport', array(), $data);
	}




	public function download_pdf()
	{
		$date = date("Y-m-d");
		$date = strtotime(date("Y-m-d", strtotime($date)) . "-2 months");
		$date = date("Y-m-d", $date);
		$this->admin_model->authCheck('view_data');
		$this->admin_model->getPermissionType($data);
		$data['error'] = '';
		$data['activeMenu'] = 'adminmanageeventreport';
		$data['activeSubMenu'] = 'adminmanageeventreport';

		if ($this->request->getGet('searchValue')) {
			$sValue = $this->request->getGet('searchValue');
			$whereCon['like'] = "(ftable.event_title LIKE '%" . $sValue . "%')";
			$data['searchField'] = $sField;
			$data['searchValue'] = $sValue;
		} else {
			$whereCon['like'] = "";
			$data['searchField'] = '';
			$data['searchValue'] = '';
		}

		$whereCon['where'] = "start_date >= '$date'";
		$tblName = 'event_tbl as ftable';
		$shortField = $this->request->getPost('sort_by') ?: 'event_title';
		$sortOrder = $this->request->getPost('order') ?: 'asc';
		$shortField = $shortField . ' ' . $sortOrder;

		// Fetch data
		$data['ALLDATA'] = $this->common_model->getData_event_report('multiple', $tblName, $whereCon, $shortField);
		$result = [];

		// Process data
		if (count($data['ALLDATA'])) {
			foreach ($data['ALLDATA'] as $alldata) {
				// Build the result array with location_name as key
				$result[$alldata['venue_title']][$alldata['location_name']][] = $alldata;
			}

			// Sort the location_name and events
			foreach ($result as $venue_title => &$locations) {
				ksort($locations); // Sorts the location_name alphabetically
				foreach ($locations as &$events) {
					usort($events, function ($a, $b) {
						return strtotime($a['start_date']) - strtotime($b['start_date']);
					});
				}
			}
		}

		$data['result'] = $result;

		// Create HTML content
		$html = '
    <style>
        .all-data {
            width: 58%;
            margin-left: 56px;
        }
        .venue-title {
            margin-top: 20px;
            margin-bottom: 20px;
            font-size: 1.5em;
            display: flex;
            gap: 6px;
            align-items: baseline;
            border: 1px solid black;
            width: 50%;
            color: #fff;
            background: #000;
            padding: 2px;
            justify-content: center;
        }
			.header {
			background-color: black;  /* Black background */
			text-align: center;       /* Center the text */
			padding: 20px;           /* Add some padding for aesthetics */
		}

		.header-title {
			color: white;            /* White text color */
			font-weight: bold;       /* Bold text */
			margin: 0;               /* Remove default margin */
		}
        .venue-t {
            font-weight: bold;
            font-size: 15px;
        }
        .event-details {
            margin-left: 20px;
        }
        .event-address,
        .event-contact {
            margin-bottom: 5px;
        }
        .al-locats {
            font-size: 16px;
        }
        .report-al {
            font-size: 8pt;
        }
        .web-al {
            color: #000;
        }
        .web-al:hover {
            color: #000;
        }
        @media print {
            body {
                visibility: hidden;
                margin: 0;
                padding: 0;
                width: 100%;
            }
            #section-to-print {
                visibility: visible;
                position: absolute;
                margin: 0 auto;
                padding: 0;
            }
            .venue-title {
                text-align: center;
            }
            .location-section {
                float: left;
                margin: 30px;
            } 
        }
        @page {
            size: auto;
            margin: 10mm;
        }
    </style>
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                           <div class="header">
							<h1 class="header-title">CALENDAR OF EVENTS</h1>
						</div>
					</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="container all-data" id="section-to-print">';

		if (!empty($data['result'])) {
			foreach ($data['result'] as $venueTitle => $locations) {
				$firstVenue = true;
				foreach ($locations as $locationName => $events) {
					$firstLocation = true;
					if ($firstVenue) {
						$html .= '<h2 class="venue-title text-center">' . $venueTitle . '</h2>';
						$firstVenue = false;
					}
					$eventDetails = '';
					foreach ($events as $event) {
						$startDate = date('M j', strtotime($event['start_date']));
						$endDate = !empty($event['end_date']) ? date('M j', strtotime($event['end_date'])) : '';

						$dateRange = $startDate;
						if ($startDate != $endDate && !empty($endDate)) {
							$dateRange .= ' - ' . $endDate;
						}

						$eventStartTime = strtotime($event['event_start_time']);
						$eventEndTime = !empty($event['event_end_time'])
							? strtotime($event['event_end_time'])
							: strtotime('+1 hour 30 minutes', $eventStartTime);
						  

						// Format the times
						$formattedStartTime = date('g:ia', $eventStartTime);
						$formattedEndTime = date('g:ia', $eventEndTime);
						// echo"<pre>"; print_r($formattedEndTime); die;

						// if (isset($formattedEndTime) && $formattedEndTime === '1:00pm') {
						// 	// Recalculate the end time based on the raw start time
						// 	$eventEndTime = strtotime('+1 hour 30 minutes', $eventStartTime);
						// 	$formattedEndTime = date('g:ia', $eventEndTime); // Reformat after calculation
						// }
						if (isset($event['time_permission']) && $event['time_permission'] === 'Yes') {
							// Recalculate the end time based on the raw start time
							$eventEndTime = strtotime('+1 hour 30 minutes', $eventStartTime);
							$formattedEndTime = date('g:ia', $eventEndTime); // Reformat after calculation
						}

						
						$eventDetails .= '<br>' . date('D', strtotime($event['start_date'])) . ' : ' . $dateRange . ' - ' . $formattedStartTime . ' & ' . $formattedEndTime . ' . ' . htmlspecialchars($event['cover_charge']) . ' <strong>' . htmlspecialchars($event['event_title']) . '</strong>; ';

						// $eventDetails .= '<br>' . date('D', strtotime($event['start_date'])) . ' : ' . $dateRange . ' - ' . date('g:ia', strtotime($event['event_start_time'])) . ' - ' . date('g:ia', strtotime($event['event_end_time'])) . ' . ' . $event['cover_charge'] . ' <b>' . $event['event_title'] . '</b>; ';
					}
					if ($firstLocation) {
						$html .= '<div class="location-section mb-2">
                                <p class="report-al">
                                    <strong class="al-locats">' . $locationName . ':</strong>
                                    ' . $events[0]['location_address'] . ' . 
                                    <a class="web-al" href="' . rtrim($events[0]['website'], '/') . '" target="_blank">' . rtrim($events[0]['website'], '/') . '</a> . 
                                    ' . $events[0]['phone_number'] . '.
                                    ' . $eventDetails . '
                                </p>
                            </div>';
						$firstLocation = false;
					}
				}
			}
		} else {
			$html .= '<div class="no-data"><p>No Data Available</p></div>';
		}

		$html .= '</div></div></div></div></div></div>';

		// Initialize Dompdf
		$options = new Options();
		$options->set('defaultFont', 'Arial');
		$options->set('isHtml5ParserEnabled', true);
		$dompdf = new Dompdf($options);

		// Load HTML into Dompdf
		$dompdf->loadHtml($html);

		// echo $html;
		// die;

		// Set paper size and orientation
		$dompdf->setPaper('A4', 'portrait');

		// Render the PDF
		$dompdf->render();

		// Output the generated PDF to the browser
		$dompdf->stream("event_report.pdf", array("Attachment" => 1));
	}


	// public function download_new_pdf()
	// {
	// 	$date = date("Y-m-d");
	// 	$date = strtotime(date("Y-m-d", strtotime($date)) . "-2 months");
	// 	$date = date("Y-m-d", $date);
	// 	$this->admin_model->authCheck('view_data');
	// 	$this->admin_model->getPermissionType($data);
	// 	$data['error'] = '';
	// 	$data['activeMenu'] = 'adminmanageeventreport';
	// 	$data['activeSubMenu'] = 'adminmanageeventreport';

	// 	// Search logic
	// 	if ($this->request->getGet('searchValue')) {
	// 		$sValue = $this->request->getGet('searchValue');
	// 		$whereCon['like'] = "(ftable.event_title LIKE '%" . $sValue . "%')";
	// 		$data['searchField'] = $sField;
	// 		$data['searchValue'] = $sValue;
	// 	} else {
	// 		$whereCon['like'] = "";
	// 		$data['searchField'] = '';
	// 		$data['searchValue'] = '';
	// 	}

	// 	$whereCon['where'] = "start_date >= '$date'";
	// 	$tblName = 'event_tbl as ftable';
	// 	$shortField = $this->request->getPost('sort_by') ?: 'event_title';
	// 	$sortOrder = $this->request->getPost('order') ?: 'asc';
	// 	$shortField = $shortField . ' ' . $sortOrder;

	// 	// Fetch data
	// 	$data['ALLDATA'] = $this->common_model->getData_event_report('multiple', $tblName, $whereCon, $shortField);
	// 	$result = [];

	// 	if (count($data['ALLDATA'])) {
	// 		foreach ($data['ALLDATA'] as $alldata) {
	// 			// Build the result array with location_name as key
	// 			$result[$alldata['venue_title']][$alldata['location_name']][] = $alldata;
	// 		}

	// 		// Sort the location_name and events
	// 		foreach ($result as $venue_title => &$locations) {
	// 			ksort($locations); // Sorts the location_name alphabetically
	// 			foreach ($locations as &$events) {
	// 				usort($events, function ($a, $b) {
	// 					return strtotime($a['start_date']) - strtotime($b['start_date']);
	// 				});
	// 			}
	// 		}
	// 	}

	// 	$data['result'] = $result;
	// 	$format = $this->request->getPost('format') ?: 'pdf'; 

	// 	// Create HTML for PDF
	// 	// $htmlContent = $this->generateHtmlContent($data);

	// 	// Check format (pdf, txt, docx, rtf)
	// 	// $format = $this->request->getPost('format') ?: 'pdf';

	// 	// switch ($format) {
	// 	// 	case 'pdf':
	// 	// 		$this->generatePdf($htmlContent);
	// 	// 		break;
	// 	// 	case 'txt':
	// 	// 		$this->generatePlainTxt($htmlContent);
	// 	// 		break;
	// 	// 	case 'docx':
	// 	// 		$this->generateDocx($htmlContent);
	// 	// 		break;
	// 	// 	case 'rtf':
	// 	// 		$this->generateRtf($htmlContent);
	// 	// 		break;
	// 	// $format = $this->request->getPost('format') ?: 'pdf';
	// 	// switch ($format) {
	// 	// 	case 'pdf':
	// 	// 		$htmlContent = $this->generateHtmlContent($data);
	// 	// 		$this->generatePdf($htmlContent);
	// 	// 		break;
	// 	// 	case 'txt':
	// 	// 		$plainText = $this->generatePlainTxt($data);
	// 	// 		$this->outputPlainTxt($plainText);
	// 	// 		break;
	// 	// 	case 'docx':
	// 	// 		$htmlContent = $this->generateHtmlContent($data);
	// 	// 		$this->generateDocx($htmlContent);
	// 	// 		break;
	// 	// 	case 'rtf':
	// 	// 		$htmlContent = $this->generateHtmlContent($data);
	// 	// 		$this->generateRtf($htmlContent);
	// 	// 		break;
	// 	// }
	// 	$this->downloadReport($data, $format);
	// }

	public function download_new_pdf()
	{
		// Step 1: Get query parameters from the request
		$event_title = $this->request->getPost('event_title');
		$start_date = $this->request->getPost('start_date') ?: date("Y-m-d", strtotime("-2 months"));
		$end_date = $this->request->getPost('end_date');
		$venue_id = $this->request->getPost('venue_id');
		$location_name = $this->request->getPost('location_name');

		$this->admin_model->authCheck('view_data');
		$this->admin_model->getPermissionType($data);

		// Step 2: Construct the WHERE conditions dynamically
		$whereCon = [];
		if ($event_title) {
			$whereCon['like'] = "(ftable.event_title LIKE '%" . $event_title . "%')";
		}
		$whereCon['where'] = "start_date >= '$start_date'";
		if ($end_date) {
			$whereCon['where'] .= " AND start_date <= '$end_date'";
		}
		if ($venue_id) {
			$whereCon['where'] .= " AND venue_id = '$venue_id'";
		}
		if ($location_name) {
			$whereCon['where'] .= " AND location_name LIKE '%$location_name%'";
		}

		$tblName = 'event_tbl as ftable';
		$sortField = $this->request->getPost('sort_by') ?: 'event_title';
		$sortOrder = $this->request->getPost('order') ?: 'asc';
		$shortField = $sortField . ' ' . $sortOrder;

		// Step 3: Fetch filtered data
		$data['ALLDATA'] = $this->common_model->getData_event_report('multiple', $tblName, $whereCon, $shortField);
		$result = [];

		if (count($data['ALLDATA'])) {
			foreach ($data['ALLDATA'] as $alldata) {
				$result[$alldata['venue_title']][$alldata['location_name']][] = $alldata;
			}

			// Sort locations and events
			foreach ($result as $venue_title => &$locations) {
				ksort($locations);
				foreach ($locations as &$events) {
					usort($events, function ($a, $b) {
						return strtotime($a['start_date']) - strtotime($b['start_date']);
					});
				}
			}
		}

		$data['result'] = $result;

		// Step 4: Get the requested format (pdf, txt, etc.)
		$format = $this->request->getPost('format') ?: 'pdf';

		// Step 5: Call the appropriate download function based on the format
		$this->downloadReport($data, $format);
	}

	// }

	private function downloadReport($data, $format)
	{
		// Generate content based on the requested format
		$content = '';
		switch ($format) {
			case 'pdf':
				$htmlContent = $this->generateHtmlContent($data);
				$this->generatePdf($htmlContent);
				break;

			case 'txt':
				$content = $this->generatePlainTxt($data);
				$this->outputPlainTxt($content);
				break;

			case 'docx':
				$htmlContent = $this->generateHtmlContent($data);
				$this->generateDocx($htmlContent);
				break;

			case 'rtf':
				$htmlContent = $this->generateHtmlContent($data);
				$this->generateRtf($htmlContent);
				break;
		}
	}

	// Helper function to create HTML content
	private function generateHtmlContent($data)
	{
		$html = '
     <style>
        .all-data {
            width: 58%;
            margin-left: 56px;
        }
        .venue-title {
            margin-top: 20px;
            margin-bottom: 20px;
            font-size: 1.5em;
            display: flex;
            gap: 6px;
            align-items: baseline;
            border: 1px solid black;
            width: 50%;
            color: #fff;
            background: #000;
            padding: 2px;
            justify-content: center;
        }
        .header {
            background-color: black;
            text-align: center;
            padding: 20px;
        }
        .header-title {
            color: white;
            font-weight: bold;
            margin: 0;
        }
        .event-details {
            margin-left: 20px;
        }
        .event-address,
        .event-contact {
            margin-bottom: 5px;
        }
        .al-locats {
            font-size: 16px;
        }
        .report-al {
            font-size: 8pt;
        }
        .web-al {
            color: #000;
        }
        .web-al:hover {
            color: #000;
        }
        @media print {
            body {
                visibility: hidden;
                margin: 0;
                padding: 0;
                width: 100%;
            }
            #section-to-print {
                visibility: visible;
                position: absolute;
                margin: 0 auto;
                padding: 0;
            }
            .venue-title {
                text-align: center;
            }
            .location-section {
                float: left;
                margin: 30px;
            } 
        }
        @page {
            size: auto;
            margin: 10mm;
        }
     </style>
     <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="header">
                                <h1 class="header-title">CALENDAR OF EVENTS</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="container all-data" id="section-to-print">';

		if (!empty($data['result'])) {
			foreach ($data['result'] as $venueTitle => $locations) {
				$html .= '<h2 class="venue-title text-center">' . htmlspecialchars($venueTitle) . '</h2>';
				foreach ($locations as $locationName => $events) {
					$eventDetails = '';
					foreach ($events as $event) {
						$startDate = date('M j', strtotime($event['start_date']));
						$endDate = !empty($event['end_date']) ? date('M j', strtotime($event['end_date'])) : '';

						$dateRange = $startDate;
						if ($startDate != $endDate && !empty($endDate)) {
							$dateRange .= ' - ' . $endDate;
						}
						$eventStartTime = strtotime($event['event_start_time']);
						$eventEndTime = !empty($event['event_end_time'])
							? strtotime($event['event_end_time'])
							: strtotime('+1 hour 30 minutes', $eventStartTime);
						  

						// Format the times
						$formattedStartTime = date('g:ia', $eventStartTime);
						$formattedEndTime = date('g:ia', $eventEndTime);
						// echo"<pre>"; print_r($event['time_permission']); die;

						if (isset($event['time_permission']) && $event['time_permission'] === 'Yes') {
							// Recalculate the end time based on the raw start time
							$eventEndTime = strtotime('+1 hour 30 minutes', $eventStartTime);
							$formattedEndTime = date('g:ia', $eventEndTime); // Reformat after calculation
						}

						
						$eventDetails .= '<br>' . date('D', strtotime($event['start_date'])) . ' : ' . $dateRange . ' - ' . $formattedStartTime . ' & ' . $formattedEndTime . ' . ' . htmlspecialchars($event['cover_charge']) . ' <strong>' . htmlspecialchars($event['event_title']) . '</strong>; ';

						// $eventDetails .= '<br>' . $dateRange . ' - ' . date('g:ia', strtotime($event['event_start_time'])) . ' - ' . date('g:ia', strtotime($event['event_end_time'])) . ' . ' . htmlspecialchars($event['cover_charge']) . ' <b>' . htmlspecialchars($event['event_title']) . '</b>; ';
					}
					$html .= '<div class="location-section mb-2">
                            <p class="report-al">
                                <strong class="al-locats">' . htmlspecialchars($locationName) . ':</strong>
                                ' . htmlspecialchars($events[0]['location_address']) . ' . 
                                <a class="web-al" href="' . htmlspecialchars(rtrim($events[0]['website'], '/')) . '" target="_blank">' . htmlspecialchars(rtrim($events[0]['website'], '/')) . '</a> . 
                                ' . htmlspecialchars($events[0]['phone_number']) . '.
                                ' . $eventDetails . '
                            </p>
                          </div>';
				}
			}
		} else {
			$html .= '<div class="no-data"><p>No Data Available</p></div>';
		}

		$html .= '</div></div></div></div></div></div>';

		return $html;
	}

	// Function to generate and download PDF
	private function generatePdf($htmlContent)
	{
		$options = new Options();
		$options->set('defaultFont', 'Arial');
		$options->set('isHtml5ParserEnabled', true);
		$dompdf = new Dompdf($options);
		$dompdf->loadHtml($htmlContent);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$dompdf->stream("event_report.pdf", array("Attachment" => 1));
	}

	// private function generatePlainTxt($data)
	// {
	// 	// Initialize plain text variable
	// 	$plainText = '';
	//         //  echo"<pre>"; print_r($data['result']); die;
	// 	// Check if data is not empty
	// 	if (!empty($data['result'])) {
	// 		foreach ($data['result'] as $venueTitle => $locations) { //loop of venue
	// 			$firstLocation = true; // Flag to check if it's the first location

	// 			foreach ($locations as $locationName => $events) { //loop of venue location

	// 				// Start building the line with venue title and location details
	// 				if ($firstLocation) {
	// 					$plainText .= $events[0]['position'] . ' - ' . $venueTitle .  ":\n"; // Only show venue title for the first location
	// 					$firstLocation = false; // Set flag to false for subsequent locations
	// 				}

	// 				$plainText .= $locationName . ": " .
	// 					htmlspecialchars($events[0]['location_address']) . ". " .
	// 					htmlspecialchars(rtrim($events[0]['website'], '/')) . ". " .
	// 					htmlspecialchars($events[0]['phone_number']) . ". ";

	// 				// Initialize arrays to collect dates and event titles
	// 				$dates = [];
	// 				$eventTitles = [];
	// 				$coverCharge = ''; // Reset for each location

	// 				// Add event details
	// 				foreach ($events as $event) { //loop of venue location event
	// 					$startDate = date('M j', strtotime($event['start_date']));
	// 					$endDate = !empty($event['end_date']) ? date('M j', strtotime($event['end_date'])) : '';
	// 					$dateRange = $startDate;

	// 					if ($startDate != $endDate && !empty($endDate)) {
	// 						// $dateRange .= ' - ' . $endDate;
	// 					}
	// 					// Collect dates and event details
	// 					$dates[] = $startDate . '-' . $endDate;
	// 					$eventTitles[] = htmlspecialchars($event['event_title']);
	// 					$coverCharge = htmlspecialchars($event['cover_charge']); // Assuming this is the same for all events

	// 				}
	// 				echo"<pre>"; print_r($events); die;

	// 				// Remove duplicate event titles and join them
	// 				$uniqueEventTitles = array_unique($eventTitles);
	// 				$eventDetails = implode(', ', $uniqueEventTitles);

	// 				// Collect unique dates and group by month
	// 				$monthGroupedDates = [];
	// 				foreach ($dates as $date) {
	// 					$dateParts = explode(' ', $date);
	// 					$month = $dateParts[0]; // Get month name 
	// 					$dayRange = isset($dateParts[2]) ? $dateParts[1] . ' - ' . $dateParts[2] : $dateParts[1];

	// 					if (!isset($monthGroupedDates[$month])) {
	// 						$monthGroupedDates[$month] = [];
	// 					}
	// 					$monthGroupedDates[$month][] = $dayRange;
	// 				}

	// 				// Format grouped dates
	// 				$formattedDates = [];
	// 				foreach ($monthGroupedDates as $month => $days) {
	// 					$formattedDates[] = $month . ' ' . implode(', ', $days); 
	// 				}

	// 				// Combine everything for the final line
	// 				$plainText .= implode(', ', $formattedDates) . ": " .
	// 					date('g:ia', strtotime($events[0]['event_start_time'])) . " - " .
	// 					date('g:ia', strtotime($events[0]['event_end_time'])) . ". " 
	// 					. $eventDetails . ". " . $coverCharge . ". ";

	// 				// Add a blank line between locations (if needed)
	// 				$plainText .= "\n"; // This line can be adjusted based on your formatting needs
	// 			}

	// 			$plainText .= "\n";
	// 		}
	// 	} else {
	// 		$plainText .= "No Data Available\n"; // If no data, add this message
	// 	}

	// 	echo $plainText ;die;

	// 	// Set headers for the text file download
	// 	header('Content-Type: text/plain');
	// 	header('Content-Disposition: attachment; filename="event_report.txt"');

	// 	// Output the plain text
	// 	echo $plainText;
	// 	exit;
	// }
	//     private function generatePlainTxt($data)
	// {
	//     // Initialize plain text variable
	//     $plainText = '';

	//     // Check if data is not empty
	//     if (!empty($data['result'])) {
	//         foreach ($data['result'] as $venueTitle => $locations) { // Loop through venues
	//             $firstLocation = true; // Track first location to print venue title

	//             foreach ($locations as $locationName => $events) { // Loop through venue locations

	//                 // Print venue title only once for the first location
	//                 if ($firstLocation) {
	//                     $plainText .= $events[0]['position'] . ' - ' . $venueTitle . ":\n";
	//                     $firstLocation = false;
	//                 }

	//                 // Print the location details
	//                 $plainText .= "Location: " . $locationName . "\n";
	//                 $plainText .= "Address: " . htmlspecialchars($events[0]['location_address']) . "\n";
	//                 $plainText .= "Website: " . htmlspecialchars(rtrim($events[0]['website'], '/')) . "\n";
	//                 $plainText .= "Phone: " . htmlspecialchars($events[0]['phone_number']) . "\n\n";

	//                 // Group dates for each unique event title
	//                 $eventGroups = [];
	//                 foreach ($events as $event) {
	//                     $eventTitle = htmlspecialchars($event['event_title']);
	//                     $eventGroups[$eventTitle][] = date('M j', strtotime($event['start_date']));
	//                 }

	//                 // Loop through each unique event and print its details
	//                 foreach ($eventGroups as $eventTitle => $dates) {
	//                     // Print event title once
	//                     $plainText .= "Event: " . $eventTitle . "\n";

	//                     // Print all dates when the event occurs
	//                     $uniqueDates = array_unique($dates);
	//                     $plainText .= "Dates: " . implode(', ', $uniqueDates) . "\n";

	//                     // Use time from the first occurrence of the event
	//                     $startTime = date('g:ia', strtotime($events[0]['event_start_time']));
	//                     $endTime = date('g:ia', strtotime($events[0]['event_end_time']));
	//                     $plainText .= "Time: " . $startTime . " - " . $endTime . "\n";

	//                     // Assume the same cover charge for all instances of the event
	//                     $coverCharge = htmlspecialchars($events[0]['cover_charge']);
	//                     $plainText .= "Cover Charge: " . $coverCharge . "\n\n";
	//                 }

	//                 // Add a separator between locations
	//                 $plainText .= "-----------------------------\n";
	//             }
	//         }
	//     } else {
	//         $plainText .= "No Data Available\n"; // If no data, add this message
	//     }

	//     // Set headers for the text file download
	//     header('Content-Type: text/plain');
	//     header('Content-Disposition: attachment; filename="event_report.txt"');

	//     // Output the plain text
	//     echo $plainText;
	//     exit;
	// }

	private function generatePlainTxt($data)
	{
		// Initialize plain text variable
		$plainText = '';

		// Check if data is not empty
		if (!empty($data['result'])) {
			foreach ($data['result'] as $venueTitle => $locations) { // Loop through venues
				$firstLocation = true; // Track first location to print the venue title
				$total_locations = count($locations);
				foreach ($locations as $locationName => $events) { // Loop through venue locations
					// echo "<pre>";
					// print_r($events);
					// echo "</pre>";
					// Print venue title only once for the first location
					if ($firstLocation) {
						$plainText .=  $events[0]['position'] . " - " . $venueTitle . ":\n"; // Print venue title
						$firstLocation = false;
					}

					// Collect and print the location details in a single line
					$plainText .= htmlspecialchars($locationName) . ": " .
						htmlspecialchars($events[0]['location_address']) . ". " .
						htmlspecialchars(trim($events[0]['phone_number'])) . ". ";
						if(!empty($events[0]['website'])){
							$plainText .= htmlspecialchars(rtrim($events[0]['website'], '/')) . ". ";
						}
						// ;

					// Group events by title and collect unique dates for each
					$eventGroups = [];
					foreach ($events as $k=>$event) {
						$eventTitle = htmlspecialchars($event['event_title']);
						if($k==0){
							$eventGroups[$eventTitle][] = date('M j', strtotime($event['start_date']));
						}else{
							$eventGroups[$eventTitle][] = date('j', strtotime($event['start_date']));
						}
						
					}
					// echo "<pre>";
					// print_r($eventGroups);
					// echo "</pre>";
					// Print event details in the required format
					foreach ($eventGroups as $eventTitle => $dates) {
						// Collect unique dates and format them
						$uniqueDates = array_unique($dates);
						$formattedDates = implode(', ', $uniqueDates);

						// Use the time from the first occurrence of the event
						$startTime = date('g:ia', strtotime($events[0]['event_start_time']));
						$endTime = date('g:ia', strtotime($events[0]['event_end_time']));
						$cover_charge = $events[0]['cover_charge'];
						// Print event dates, time, and title in a single line
						$plainText .= $formattedDates . ": " . $startTime . "-" . $endTime . " " .
							$eventTitle;
							if(!empty($cover_charge)){
								$plainText .= ", ".$cover_charge;
							}
						$plainText .= ";";	
					}

					// Add a blank line after each location
					$plainText .= "\n";
					if ($events === end($locations)) {
						$plainText .= "\n";
					}
				}
			}
		} else {
			$plainText .= "No Data Available\n";
		}
		// echo $plainText;die;
		return $plainText;
	}

	private function outputPlainTxt($plainText)
	{
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename="event_report.txt"');
		echo $plainText;
		exit;
	}






	// Function to generate and download DOCX using PhpOffice\PhpWord
	private function generateDocx($data)
	{
		$phpWord = new PhpWord();
		$section = $phpWord->addSection();

		foreach ($data['result'] as $venueTitle => $locations) {
			$section->addTitle($venueTitle, 1);
			foreach ($locations as $locationName => $events) {
				$section->addText($locationName);
				foreach ($events as $event) {
					$section->addText($event['event_title'] . ' - ' . $event['start_date']);
				}
			}
		}

		$tempFile = tempnam(sys_get_temp_dir(), 'word');
		$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save($tempFile);

		header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
		header('Content-Disposition: attachment; filename="event_report.docx"');
		readfile($tempFile);
		unlink($tempFile);
		exit;
	}

	// Function to generate and download RTF
	private function generateRtf($htmlContent)
	{
		$plainText = strip_tags($htmlContent);
		$rtfContent = "{\\rtf1\\ansi\\ansicpg1252\\deff0\\nouicompat\\deflang1033 " . $plainText . "}";

		header('Content-Type: application/rtf');
		header('Content-Disposition: attachment; filename="event_report.rtf"');
		echo $rtfContent;
		exit;
	}

	public function export_excel()
	{
		require_once ROOTPATH . 'vendor/autoload.php';

		$eventData = $this->admin_model->getFilteredEventsForExport($_GET);

		// echo"<pre>";print_r($eventData);die;

		$objPHPExcel = new Spreadsheet();

		// ✅ Set document properties
		$objPHPExcel->getProperties()->setCreator("Your Name")
			->setLastModifiedBy("Your Name")
			->setTitle("Exported Events")
			->setSubject("Exported Events Data")
			->setDescription("Excel file generated from exported events data")
			->setKeywords("excel phpexcel codeigniter")
			->setCategory("Data Export");

		// ✅ Set column headers
		$objPHPExcel->setActiveSheetIndex(0)
		 ->setCellValue('A1', 'Event ID')
			->setCellValue('B1', 'Event Title')
			->setCellValue('C1', 'Description')
			->setCellValue('D1', 'Location')
			->setCellValue('E1', 'Venue')
			->setCellValue('F1', 'Artist')
			->setCellValue('G1', 'Website')
			// ->setCellValue('G1', 'State')
			// ->setCellValue('H1', 'City')
			->setCellValue('H1', 'Start Date')
			->setCellValue('I1', 'Start Time')
			->setCellValue('J1', 'End Date')
			->setCellValue('K1', 'Cover Charge');

		

		$row = 2;
		foreach ($eventData as $event) {
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $event['event_id']);
			$objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $event['event_title']);
			$objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $event['description']);
			$objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $event['location_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $event['venue_title']);
			$objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $event['artist_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $event['website']);
			// $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $event['state']);
			// $objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $event['city']);
			$objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $event['start_date']);
			$objPHPExcel->getActiveSheet()->setCellValue('I' . $row, $event['event_start_time']);
			if ($event['start_date'] != $event['end_date']) {
				$objPHPExcel->getActiveSheet()->setCellValue('J' . $row, $event['end_date']);
			} else {
				$objPHPExcel->getActiveSheet()->setCellValue('J' . $row, '');
			}
			$objPHPExcel->getActiveSheet()->setCellValue('K' . $row, $event['cover_charge']);


			$row++;
	   }
	   $objPHPExcel->setActiveSheetIndex(0);
	   $objPHPExcel->getActiveSheet()->setTitle('Events Data');
		
	    // ✅ Headers for File Download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="exported_events.xlsx"');
        header('Cache-Control: max-age=0');

        // ✅ Save Excel File and Output to Browser
        $objWriter = new Xlsx($objPHPExcel);
        $objWriter->save('php://output');

        exit;
	}
}
