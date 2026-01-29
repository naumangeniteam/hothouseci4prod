<?php

use CodeIgniter\Router\RouteCollection;
use CodeIgniter\Config\Services;
use Config\Database;

$routes = Services::routes();
$routes->setAutoRoute(false);

if (php_sapi_name() === 'cli-server') {
	$_SERVER['REQUEST_URI'] = $_SERVER['PATH_INFO'] ?? '/';
}
/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('/', 'Home::index');
$routes->get('home', 'Home::index');
// if (strpos($_SERVER['REQUEST_URI'], 'api/')) :
if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'api/') !== false) :

	###############################################################################################
	#################################		API FOR APP		  #####################################
	###############################################################################################
	///////////////////////////				USER			///////////////////////////////////////
	$routes->group('api', function ($routes) {
		$routes->get('getHomePageData', 'api\HomePageController::getHomePageList');
		$routes->get('getAboutPageData', 'api\AboutPageController::getAboutPageList');
		$routes->get('getContactPageData', 'api\ContactPageController::getContactPageList');
		$routes->get('getHowToGetPageData', 'api\HowToGetPageController::getHowToGetPageList');
		$routes->get('getPreviousPageData', 'api\PreviousPageController::getPreviousPageList');
		$routes->get('getVenuePageData', 'api\VenueController::getVenuePageList');
		$routes->get('getSubmitEventPageData', 'api\SubmitEventController::getSubmitEventPageList');
		$routes->get('getBlogPageData', 'api\GetBlogPageData::getblogpageList');
		$routes->get('getCalendarPageData', 'api\GetCalendarPageData::getcalendarpageList');

		$routes->post('FooterForm', 'api\AboutPageController::formSubmit');
		$routes->post('Location', 'api\SubmitEventController::getLocation');
		$routes->post('submit-event-form', 'api\SubmitEventController::submiteventform');
		$routes->post('postCalendarPageData', 'api\GetCalendarPageData::postcalendarpage');
		$routes->post('getBlogDetailPageData', 'api\GetBlogDetailPageData::getblogdetailbyid');
		$routes->post('getLocationPageData', 'api\GetLocationPageData::getlocationpageList');
		$routes->post('getEventDetailPageData', 'api\GetEventDetailPageData::geteventdetailbyid');
		$routes->post('getSearchPageData', 'api\GetSearchPageData::getsearchbyid');
	});
else :
	###############################################################################################
	#################################		WEBSITE ADMIN	  #####################################
	###############################################################################################
	$route['default_controller'] 									= 	'Home/index';
	$route['404_override'] 											= 	'';
	$route['translate_uri_dashes'] 									= 	FALSE;

	// $curUrl						=	strpos($_SERVER['REQUEST_URI'], '/?') ? explode('/?', $_SERVER['REQUEST_URI']) : explode('?', $_SERVER['REQUEST_URI']);
	$curUrl = isset($_SERVER['REQUEST_URI'])
		? (strpos($_SERVER['REQUEST_URI'], '/?') ? explode('/?', $_SERVER['REQUEST_URI']) : explode('?', $_SERVER['REQUEST_URI']))
		: ['/'];
	$curUrl						=	explode('/', $curUrl[0]);
	if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost') :	/////////////   Localhost 		/////////////////
		// $firstSlug				=	isset($curUrl[2]) ? $curUrl[2] : '';
		// $secondSlug				=	isset($curUrl[3]) ? $curUrl[3] : '';
		// $thirdSlug				=	isset($curUrl[4]) ? $curUrl[4] : '';
		// $fourthlug				=	isset($curUrl[5]) ? $curUrl[5] : '';
		$firstSlug				=	isset($curUrl[1]) ? $curUrl[1] : '';
		$secondSlug				=	isset($curUrl[2]) ? $curUrl[2] : '';
		$thirdSlug				=	isset($curUrl[3]) ? $curUrl[3] : '';
		$fourthlug				=	isset($curUrl[4]) ? $curUrl[4] : '';

	else : 	/////////////   SERVER		/////////////////	
		$firstSlug				=	isset($curUrl[1]) ? $curUrl[1] : '';
		$secondSlug				=	isset($curUrl[2]) ? $curUrl[2] : '';
		$thirdSlug				=	isset($curUrl[3]) ? $curUrl[3] : '';
	endif;


	if (isset($firstSlug) && $firstSlug == 'hhjsitemgmt') :
		// Admin logout route
		$routes->get('hhjsitemgmt/logout', 'hhjsitemgmt\Login::logout');

		// Group for hhjsitemgmt (no auth required)
		$routes->group('hhjsitemgmt', function ($routes) {
			$routes->get('/', 'hhjsitemgmt\Login::index');
			$routes->get('login', 'hhjsitemgmt\Login::index', ['as' => 'submit_login']);
			$routes->post('login', 'hhjsitemgmt\Login::index');
			$routes->get('login-verify-otp', 'hhjsitemgmt\Login::loginverifyotp');
			$routes->get('resend-otp', 'hhjsitemgmt\Login::resendotp');
			$routes->add('forgot-password', 'hhjsitemgmt\Login::forgotpassword');
			$routes->add('password-recover', 'hhjsitemgmt\Login::passwordrecover');
			$routes->add('reset-password', 'hhjsitemgmt\Login::resetpassword');
			$routes->post('update-password', 'hhjsitemgmt\Login::updatePassword');
			
		});
		$routes->group('hhjsitemgmt', ['filter' => 'authCheck'], function ($routes) {
			$routes->get('maindashboard', 'hhjsitemgmt\Account::maindashboard');
			$routes->get('dashboard', 'hhjsitemgmt\Account::dashboard');
			$routes->get('profile', 'hhjsitemgmt\Account::profile');
			$routes->get('editprofile', 'hhjsitemgmt\Account::editprofile');
			$routes->add('editprofile/(:any)', 'hhjsitemgmt\Account::editprofile/$1');
			$routes->get('change-password', 'hhjsitemgmt\Account::changepassword');
			$routes->get('change-password/(:any)', 'hhjsitemgmt\Account::changepassword/$1');
			$routes->post('change-password/(:any)', 'hhjsitemgmt\Account::changepassword/$1');
			$routes->add('change-password-verify-otp', 'hhjsitemgmt\Account::changepasswordverifyotp');
			$routes->get('Adminmanageapidata/location_mapping', 'hhjsitemgmt\Adminmanageapidata::location_mapping');

			$routes->add('(:any)', 'hhjsitemgmt\DynamicController::index/$1');
			$routes->add('(:any)/(:any)', 'hhjsitemgmt\DynamicController::index/$1/$2');
			$routes->add('(:any)/(:any)/(:any)', 'hhjsitemgmt\DynamicController::index/$1/$2/$3');
			$routes->add('(:any)/(:any)/(:any)/(:any)', 'hhjsitemgmt\DynamicController::index/$1/$2/$3/$4');

			// Get the current URL path (removing "hhjsitemgmt/" if exists)
			// $newCurUrl = explode('/hhjsitemgmt/', $_SERVER['REQUEST_URI']);
			// $classFunction = isset($newCurUrl[1]) ? $newCurUrl[1] : '';

			// $classFunction = strpos($classFunction, '/?') ? explode('/?', $classFunction) : explode('?', $classFunction);

			// if (!empty($classFunction[0])) {
			// 	$defaultClassArray = ['department', 'designation', 'subadmin'];
			// 	$classArray = explode('/', $classFunction[0]); // Split route by "/"

			// 	$classArray = explode('/', trim($classFunction[0], '/'));
			// 	$controller = $classArray[0] ?? '';
			// 	$method = $classArray[1] ?? 'index';

			// 	if ($controller) {
			// 		$controllerClass = "hhjsitemgmt\\" . ucfirst($controller);

			// 		log_message('debug', "Trying to load controller: App\\Controllers\\$controllerClass::$method");

			// 		if (class_exists("App\\Controllers\\$controllerClass")) {
			// 			// Build base route string (controller only)
			// 			$routeBase = $controller;
			// 			if ($method !== 'index') {
			// 				$routeBase .= '/' . $method;
			// 			}

			// 			// Now register routes
			// 			$routes->add("$routeBase/(:num)/(:any)/(:any)", "$controllerClass::$method/$1/$2/$3");
			// 			$routes->add("$routeBase/(:num)/(:any)", "$controllerClass::$method/$1/$2");
			// 			$routes->add("$routeBase/(:num)", "$controllerClass::$method/$1");
			// 			$routes->add("$routeBase", "$controllerClass::$method");

			// 			// If method is "index", also allow route without "/index"
			// 			if ($method === 'index') {
			// 				$routes->add($controller, "$controllerClass::index");
			// 			}
			// 		} else {
			// 			log_message('error', "Controller '$controllerClass' not found.");
			// 		}
			// 	}
			// }
		});
	else :


		////////////////////	HOME	////////////////////
		$routes->get('home', 'Home::index');
		$routes->post('home', 'Home::index');

		$routes->get('hh_jazz_guide', 'Home::hh_jazz_guide');
		$routes->post('save-advertise-detail', 'Home::save_advertise_detail');
		$routes->get('archive-events', 'Home::archive_events');
		$routes->get('inactive-events-festivals', 'Home::inactive_events_festivals');
		$routes->get('delete-event-index/(:any)', 'Home::delete_event_index');

		$routes->post('save-report-problem', 'Home::save_report_problem');


		$route['listing'] 	        = 	'home/listing';
		$route['hotspots'] 	        = 	'home/hotspots';
		$route['current_isssue_of_magazine'] 	        = 	'home/current_isssue_of_magazine';
		$route['current_issue'] 	        = 	'home/current_issue';

		$route['test'] 							= 	'home/test';
		////////////////////	ABOUT	////////////////////
		$route['about'] 						= 	'front/about/index';
		////////////////////	how-to-get-hh	////////////////////
		$routes->get('how_to_get_hh', 'front\How_to_get_hh::index');

		//////////////////// Event Types ////////////////////
		$routes->get('new_events', 'front\New_events::index');
		$routes->post('event-filter-artist', 'front\New_events::event_filter_artist');

		////////////////////	calendar	////////////////////
		$routes->get('calendar', 'front\Calendar::index');
		$routes->get('calendar/getdate', 'front\Calendar::getDate');
		$routes->post('calendar-filter-artist', 'front\Calendar::calendar_filter_artist');

		$routes->get('calendar-filter1', 'front\Calendar::calendar_filter1');
		// $routes->get('calendar-filter-all', 'front\Calendar::calendar_filter_all');
		$routes->get('calendar-filter-venue', 'front\Calendar::calendar_filter_venue');
		// $routes->get('calendar-filter-artist', 'front\Calendar::filter_artist');
		$routes->get('calendar-filter-jazz', 'front\Calendar::calendar_filter_jazz');
		$routes->post('calendar-filter-artist', 'front\Calendar::calendar_filter_artist');
		// $routes->get('calendar-filter-artist', 'front\Calendar::calendar_filter_artist');


		$routes->get('thumbnail-full-image', 'front\Calendar::thumbnail_full_image');
		$routes->get('get-artist-data', 'front\Calendar::get_artist_data');
		$routes->get('global-search', 'front\Calendar::global_search');

		$routes->get('venue', 'front\Venue::index');
		$routes->get('location', 'front\Location::index');

		$routes->get('festivals', 'front\Festivals::index');
		$routes->add('festivals-filter-artist', 'front\Festivals::festivals_filter_artist');

		$routes->get('contact_us', 'front\Contact_us::index');
		$routes->get('submit_event', 'front\Submit_event::index');
		$routes->post('submit_event', 'front\Submit_event::index');
		$routes->get('submit_venue', 'front\Submit_venue::index');
		$routes->post('submit_venue', 'front\Submit_venue::index', ['as' => 'submit_venue']);

		$routes->get('elastic', 'front\Elastic::index');
		$routes->get('elastic/testConnection', 'front\Elastic::testConnection');
		$routes->get('elastic/add', 'front\Elastic::add');
		$routes->get('elastic/list', 'front\Elastic::list');
		$routes->get('elastic/search', 'front\Elastic::search');

		$routes->get('thumbnail-full-image', 'front\Calendar::thumbnail_full_image');
		$routes->get('get-artist-data', 'front\Calendar::get_artist_data');

		$routes->get('get-venue-location', 'front\Submit_event::location');

		$routes->post('save-artist-name', 'front\Submit_event::save_artist_name');
		////////////////////	venue	////////////////////
		$routes->get('venue', 'front\Venue::index');
		// $routes->get('location', 'front\Location::index'); 
		$routes->get('front/location', 'front\Location::index');
		////////////////////	Festivals	////////////////////

		$routes->get('festivals', 'front\Festivals::index');
		$routes->add('festivals-filter-artist', 'front\Festivals::festivals_filter_artist');

		$routes->get('festivals-filter', 'front\Festivals::festivals_filter');
		$routes->get('festivals-filter1', 'front\Festivals::festivals_filter1');
		$routes->get('festivals-filter-venue', 'front\Festivals::festivals_filter_venue');
		// $routes->get('festivals-filter-artist', 'front\Festivals::filter_artisted');
		$routes->get('festivals-filter-jazz', 'front\Festivals::festivals_filter_jazz');

		$routes->post('global-search-festivals-name', 'front\Festivals::global_search_festivals_name');
		// $route['global-search-festivals'] 			        = 	'front/elastic/global_search_festivals';
		$routes->get('global-search-festivals', 'front\Elastic::global_search_festivals');

		////////////////////	LOGIN	////////////////////
		$routes->get('login', 'front\Login::index');
		$routes->get('logout', 'front\Login::logout');

		////////////////////	SIGNUP	////////////////////
		$routes->get('sign-up', 'front\Signup::index');
		$routes->get('submit_festival', 'front\Submit_festival::index');
		$routes->post('submit_festival', 'front\Submit_festival::index');
		$routes->get('submit_festival/location', 'front\Submit_festival::location');

		////////////////////	submit-event	////////////////////
		$routes->get('submit_event', 'front\Submit_event::index');
		$routes->post('submit_event', 'front\Submit_event::index');
		// $route['submit_venue'] 					= 	'front/submit_venue/index';
		$routes->get('submit_venue', 'front\Submit_venue::index');
		$routes->post('submit_venue', 'front\Submit_venue::index', ['as' => 'submit_venue']);
		//$route['submit_event_test'] 							= 	'front/Submit_event/testm';
		////////////////////	Contact Us	////////////////////
		$routes->get('contact_us', 'front\Contact_us::index');
		////////////////////	Magazine Locations	////////////////////
		$routes->get('magazine_locations', 'front\Magazine_locations::index');

		//////////////////// Previous issues ////////////////////
		$routes->get('previous_issue', 'front\Previous_issue::index');
		$routes->get('search', 'front\Search::index');
		// $routes->get('location', 'front\Location::index');



		$routes->get('location', 'front\Location::index');

		////////////////////Blog Page///////////////////
		$route['bloglist'] 							= 	'front/bloglist/index';
		$route['bloglist/(:num)'] 				    = 	'front/bloglist/index';
		$routes->get('blog_detail/(:any)', 'front\Blog_detail::index/$1');
		$routes->get('event_detail/(:any)', 'front\Event_detail::index/$1');

		$routes->get('festival_detail/(:any)', 'front\Festival_detail::index/$1');
		$routes->get('privacy-policy', 'Home::privacypolicy');
		$routes->get('refund-policy', 'Home::refundpolicy');
		// $route['get-permission-data'] 			= 	'admin/usermanagement/get_permission_data';
		$route['mutlipleChangeStatus']              =   'admin/eventmanagement/mutlipleChangeStatus';
		$route['mutlipleChangeStatus']              =   'admin/adminmanagefestivals/mutlipleChangeStatus';
		$route['mutlipleChangeStatus']              =   'admin/adminmanageuserpost/mutlipleChangeStatus';
		$route['mutlipleChangeStatus']              =   'admin/adminmanagearchived/mutlipleChangeStatus';
		$route['mutlipleChangeStatus']              =   'admin/adminmanageapiimport/mutlipleChangeStatus';

		$route['boostDays']                         =   'admin/adminmanageuserpost/boostDays';
		$route['boostDays']                         =   'admin/eventmanagement/boostDays';

		$routes->post('mutlipleChangeStatus/event',      'Admin\EventManagement::mutlipleChangeStatus');
		$routes->post('mutlipleChangeStatus/festival',   'Admin\AdminManageFestivals::mutlipleChangeStatus');
		$routes->post('mutlipleChangeStatus/post',       'Admin\AdminManageUserPost::mutlipleChangeStatus');
		$routes->post('mutlipleChangeStatus/archived',   'Admin\AdminManageArchived::mutlipleChangeStatus');
		$routes->post('mutlipleChangeStatus/apiimport',  'Admin\AdminManageApiImport::mutlipleChangeStatus');

		// $route['elastic']                         =   'front/elastic/index';
		$routes->get('elastic', 'front\Elastic::index');
		$routes->get('elastic/testConnection', 'front\Elastic::testConnection');
		$routes->get('elastic', 'front\Elastic::index');
		$routes->get('elastic/add', 'front\Elastic::add');
		$routes->get('elastic/list', 'front\Elastic::list');
		$routes->get('elastic/search', 'front\Elastic::search');
		$routes->get('elastic/multipleData', 'front\Elastic::multipleData');
		$routes->get('elastic/multipleDataList', 'front\Elastic::multipleDataList');
		$routes->get('elastic/elastic-search', 'front\Elastic::elastic_search');
		$routes->get('elastic/calendar', 'front\Elastic::calendar');
		// $routes->post('calendar-filter-artist', 'front\Elastic::calendar_filter_artist');

		$routes->get('event_scrapping', 'front\Event_scrapping::index');
		$routes->get('event_scrapping_detail/(:any)', 'front\Event_scrapping::event_scrapping_detail/$1');
		$routes->get('import-tk-events', 'front\Ticketmaster::importEvents');
		$routes->get('earch/calendar-filter-artist', 'front\Elastic::calendar_filter_artist');
		// $route['global-search/(:any)'] 			               = 	'front/elastic/global_search/$1';
		$routes->get('global-search/(:any)', 'front\Elastic::global_search/$1');
		$routes->post('global-search-filters', 'front\Elastic::global_search_filters');
		$routes->get('hhjsitemgmt/adminmanageeventreport/download_pdf', 'hhjsitemgmt\Adminmanageeventreport::download_pdf');
		$routes->get('hhjsitemgmt/adminmanageeventreport/download_new_pdf', 'hhjsitemgmt\Adminmanageeventreport::download_new_pdf');
		$routes->get('hhjsitemgmt/adminmanageeventreport/export_excel', 'hhjsitemgmt\AdminManageEventReport::export_excel', ['as' => 'export_excel']);
		$routes->get('hhjsitemgmt/adminmanageeventreport/generate_report', 'hhjsitemgmt\AdminManageEventReport::generate_report', ['as' => 'generate_report']);
		$routes->get('scrapping/dice-data', 'front\Event_diceindex::index');
	endif;
endif;
