<?php
use CodeIgniter\Pager\Pager;
/*
* Get url segment for pagination
*/
// if (!function_exists('getUrlSegment')) {
// 	function getUrlSegment(){
// 		/////////////   Localhost 		/////////////////
// 		if($_SERVER['SERVER_NAME']=='localhost'):
// 			$urlSegment	=	4;
// 		else:
// 			$urlSegment	=	4;
// 		endif;
// 		return $urlSegment;
// 	}
// }   //giving problem in dashboard Adminmanagepublishevent  

if (!function_exists('getUrlSegment')) {
    function getUrlSegment()
    {
        $uri = service('request')->getUri();
        $totalSegments = $uri->getTotalSegments();
        // return ($totalSegments >= 3) ? 3 : $totalSegments; // Adjust based on total segments
		return ($totalSegments > 1) ? $totalSegments : 1;
    }
}


/*
* Get current base path
*/
if (!function_exists('getCurrentBasePath')) {
	function getCurrentBasePath(){
		// return base_url();
		return rtrim(base_url(), '/');
	}
}
/**
 * Process date for display
 * 
 * @param string $dateString Date string in Y-m-d format
 * @param string $format PHP date format
 * @return string Formatted date
 */
function formatSafeDate($dateString, $format = 'Y-m-d')
{
    if (empty($dateString)) {
        return date($format);
    }
    
    $date = \DateTime::createFromFormat('Y-m-d', $dateString);
    return ($date instanceof \DateTime) ? $date->format($format) : date($format);
}

/*
* Get current base path
*/
if (!function_exists('getCurrentControllerPath')) {
    function getCurrentControllerPath($postfixUrl = '')
    {
        $uri = service('uri'); // Get current URI service
        $functionArray = [
            'index', 'addeditdata', 'deletedata', 'changestatus', 'imageUpload',
            'imageDelete', 'deleteContent', 'memberDelete', 'viewdata',
            'changedatastatus', 'getdatabyajax'
        ];

       // Get total segment count
	   $totalSegments = $uri->getTotalSegments();

	   // Get URI segments safely (only if they exist)
	   $segment1 = $totalSegments >= 1 ? $uri->getSegment(1) : '';
	   $segment2 = $totalSegments >= 2 ? $uri->getSegment(2) : '';
	   $segment3 = $totalSegments >= 3 ? $uri->getSegment(3) : '';

	   // Base URL
	   $baseUrl = getCurrentBasePath();
      // Handle cases based on segment availability
	  if (!empty($segment3) && in_array($segment3, $functionArray)) {
		return rtrim($baseUrl . '/' . $segment1 . '/' . $segment2 . '/' . $postfixUrl, '/');
	}

	if (!empty($segment2) && in_array($segment2, $functionArray)) {
		return rtrim($baseUrl . '/' . $segment1 . '/' . $postfixUrl, '/');
	}

	return rtrim($baseUrl . '/' . $postfixUrl, '/');
        // return rtrim($baseUrl . '/' . $segment1 . '/' . $postfixUrl, '/');
    }
}



/*
* Get current dashboard base path
*/
if (!function_exists('getCurrentDashboardPath')) {
	function getCurrentDashboardPath($postfixUrl=''){
		// $CI =& get_instance();
		// $CI = \Config\Services::request();
		$uri = service('uri');
		$baseUrl 					=	getCurrentBasePath();
		$baseUrl 					=	$baseUrl.$uri->segment(1).'/'.$postfixUrl;
		return $baseUrl;
	}
}

/*
 * show status
 */
if (!function_exists('showStatus')) {
	function showStatus($text='') {
		$statusArray	=	[
			'1'=>'<label class="badge badge-light-success">'.lang('statictext_lang.active').'</label>',
			'0'=>'<label class="badge badge-light-danger">'.lang('statictext_lang.inactive').'</label>',
			'2'=>'<label class="badge badge-light-danger">'.lang('statictext_lang.trash').'</label>',
			'B'=>'<label class="badge badge-light-danger">'.lang('statictext_lang.block').'</label>',
			'D'=>'<label class="badge badge-light-danger">'.lang('statictext_lang.deleted').'</label>',
			'Y'=>'<label class="badge badge-light-success">'.lang('statictext_lang.active').'</label>',
			'N'=>'<label class="badge badge-light-danger">'.lang('statictext_lang.inactive').'</label>',
			'R'=>'<label class="badge badge-light-danger">'.lang('statictext_lang.inactive').'</label>',
			't'=>'<label class="badge badge-light-success">'.lang('statictext_lang.active').'</label>',
			'f'=>'<label class="badge badge-light-danger">'.lang('statictext_lang.inactive').'</label>',
		];
		return $statusArray[$text];
	}
}


/*
 * show payment status
 */
if (!function_exists('showPaymentStatus')) {
	function showPaymentStatus($text='') {
		$statusArray	=	array('N'=>'<label class="badge badge-light-danger">'.lang('statictext_lang.pending').'</label>',
								  'Y'=>'<label class="badge badge-light-success">'.lang('statictext_lang.released').'</label>'
								  );
		return $statusArray[$text];
	}
}

/*
 * show payment status
 */
if (!function_exists('showAcountVerifiedStatus')) {
	function showAcountVerifiedStatus($text='') {
		$statusArray	=	array('N'=>'<label class="badge badge-light-danger">'.lang('statictext_lang.account_pending').'</label>',
								  'Y'=>'<label class="badge badge-light-success">'.lang('statictext_lang.account_verified').'</label>'
								  );
		return $statusArray[$text];
	}
}

/*
 * show payment status
 */

if (!function_exists('showPropertyStatus')) {
	function showPropertyStatus($text='') {
		$statusArray	=	array('p'=>'<label class="badge badge-light-danger">'.lang('statictext_lang.property_pending').'</label>',
								  'IV'=>'<label class="badge badge-light-success">'.lang('statictext_lang.inspector_verify').'</label>',
								  'IR'=>'<label class="badge badge-light-success">'.lang('statictext_lang.inspector_reject').'</label>',
								  'AV'=>'<label class="badge badge-light-success">'.lang('statictext_lang.admin_verify').'</label>',
								  'AR'=>'<label class="badge badge-light-success">'.lang('statictext_lang.admin_reject').'</label>'
								  );
		return $statusArray[$text];
	}
}

/*
 * show payment status
 */
if (!function_exists('showPaymentStatusSimple')) {
	function showPaymentStatusSimple($text='') {
		$statusArray	=	array('0'=>lang('statictext_lang.initialize'),
								  '1'=>lang('statictext_lang.success'),
								  '2'=>lang('statictext_lang.cancel'),
								  '3'=>lang('statictext_lang.error'),
								  '4'=>lang('statictext_lang.refund'));
		return $statusArray[$text];
	}
}
/*
 * show Aprrove Status
 */
if (!function_exists('showApproveStatus')) {
	function showApproveStatus($text='') {
		$statusArray	=	array('A'=>'<label class="badge badge-light-success">'.lang('statictext_lang.publish').'</label>',
								  'I'=>'<label class="badge badge-light-danger">'.lang('statictext_lang.unpublish').'</label>',
								  'D'=>'<label class="badge badge-light-danger">'.lang('statictext_lang.reject').'</label>');
		return $statusArray[$text];
	}
}

/*
 * show Aprrove Status
 */
if (!function_exists('showAccountActiveStatus')) {
	function showAccountActiveStatus($text='') {
		$statusArray	=	array('1'=>'<label class="badge badge-light-success">'.lang('statictext_lang.login').'</label>',
								  '0'=>'<label class="badge badge-light-danger">'.lang('statictext_lang.logout').'</label>');
		return $statusArray[$text];
	}
}

/*
* Generate admin Pagination
*/
if (!function_exists('adminPagination')) {
    function adminPagination($url = '', $suffix = '', $rowsCount = 0, $perPage = 10, $uriSegment = 0)
    {
		
		        $pager = \Config\Services::pager(); // ✅ Load pagination service

        // ✅ Pagination config
        $config = [
			'baseURL'       => $url . $suffix, // Ensure suffix is added
            'totalRows'     => $rowsCount,
            'perPage'       => $perPage,
            'uriSegment'    => $uriSegment, // Correct segment for pagination
           'fullTagOpen'    => '<ul class="pagination justify-content-center">', // Add Bootstrap classes
        'fullTagClose'   => '</ul>',
        'numTagOpen'     => '<li class="page-item">',
        'numTagClose'    => '</li>',
        'curTagOpen'     => '<li class="page-item active"><a class="page-link" href="#">',
        'curTagClose'    => '</a></li>',
        'nextLink'       => 'Next',
        'nextTagOpen'    => '<li class="page-item next">',
        'nextTagClose'   => '</li>',
        'prevLink'       => 'Previous',
        'prevTagOpen'    => '<li class="page-item previous">',
        'prevTagClose'   => '</li>',
        'firstLink'      => 'First',
        'firstTagOpen'   => '<li class="page-item previous">',
        'firstTagClose'  => '</li>',
        'lastLink'       => 'Last',
        'lastTagOpen'    => '<li class="page-item next">',
        'lastTagClose'   => '</li>',
        'attributes'     => ['class' => 'page-link'], // Bootstrap class for links
    ];

    return $pager->makeLinks($uriSegment, $perPage, $rowsCount, 'default_full');
    }
}

if (!function_exists('sanitizedFieldName')) {
	function sanitizedFieldName($filename){
		$sanitized = preg_replace('/[^a-zA-Z0-9-_\.]/','', $filename);
		return $sanitized;
	}
}
	
/*
 * check Remote File
 */
if (!function_exists('checkRemoteFile')) {
	function checkRemoteFile($url='')
	{
		if($url):
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_NOBODY, 1);
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			if(curl_exec($ch)!==FALSE):
				return true;
			else:
				return false;
			endif;
		else:
			return false;
		endif;
	}
}	

/*
 * Show correct image
 */
if (!function_exists('showCorrectImage')) {
	function showCorrectImage($imageUrl='', $type = '',$showType='') {
		if(checkRemoteFile($imageUrl)): 
			if($type=='original'):
				$imageUrl = str_replace('/thumb','',$imageUrl);
			elseif($type):
				$imageUrl = str_replace('thumb',$type,$imageUrl);
			endif;
		else:	
			if($showType == 'profile'):
				if($type=='original'):
					$imageUrl = base_url().'assets/admin/image/image402x402.jpg';
				elseif($type=='thumb'):
					$imageUrl = base_url().'assets/admin/image/image213X213.jpg';
				endif;
			endif;
		endif;
		return trim($imageUrl);
	}
}

if (! function_exists('intOrNull')) {
    /**
     * Convert empty string or null into NULL, otherwise cast to int
     */
    function intOrNull($value)
    {
        return ($value === '' || $value === null) ? null : (int) $value;
    }
}

if (! function_exists('floatOrNull')) {
	/**
	 * Convert empty string or null into NULL, otherwise cast to float
	 */
	function floatOrNull($value)
	{
		return ($value === '' || $value === null) ? null : (float) $value;
	}
}