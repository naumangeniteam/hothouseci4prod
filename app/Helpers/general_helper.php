<?php
// make friendly url using any string
if (!function_exists('friendlyURL')) {
	function friendlyURL($inputString){
		$url = strtolower($inputString);
		$patterns = $replacements = array();
		$patterns[0] = '/(&amp;|&)/i';
		$replacements[0] = '-and-';
		$patterns[1] = '/[^a-zA-Z01-9]/i';
		$replacements[1] = '-';
		$patterns[2] = '/(-+)/i';
		$replacements[2] = '-';
		$patterns[3] = '/(-$|^-)/i';
		$replacements[3] = '';
		$url = preg_replace($patterns, $replacements, $url);
	return $url;
	}
}

if (!function_exists('sanitizedNumber')) {
	function replaceSpecialCharInUrl($str)
	{
		$find     =   array("\t","\n"," ");
		$replace  =   array("","","");
		return str_replace($find,$replace,$str);
	}
}

// sanitized number :  function auto remove unwanted character form given value 
if (!function_exists('sanitizedNumber')) {
	function sanitizedNumber($_input) 
	{ 
		return (float) preg_replace('/[^0-9.]*/','',$_input); 
	}
}

// sanitized filename :  function auto remove unwanted character form given file name
if (!function_exists('sanitizedFilename')) {
	function sanitizedFilename($filename){
		$sanitized = preg_replace('/[^a-zA-Z0-9-_\.]/','', $filename);
		return $sanitized;
	}
}

// check, is file exist in folder or not
if (!function_exists('fileExist')) {
	function fileExist($source='', $file='', $defalut=''){
		if(!$file) return base_url().$source.$defalut;
			
		if(file_exists(FCPATH.$source.$file)):
			return base_url().$source.$file;
		else:
			return base_url().$source.$defalut;
		endif;
	}
}

// check, is file exist in folder or not
if (!function_exists('checkFileExist')) {
	function checkFileExist($source=''){
		if(file_exists(FCPATH.$source)):
			return base_url().$source;
		else:
			return false;
		endif;
	}
}

if (!function_exists('myExplode')) {
	function myExplode($string){
		if($string):
			$array = explode(",",$string);
			return $array;
		else:
			return '';
		endif;
	}
}

/*
 * Show correct image
 */
if (!function_exists('correctImage')) {
	function correctImage($imageurl, $type = '') {
		if($imageurl <> ""):
			if($type=='original'):
				$imageurl = str_replace('/thumb','',$imageurl);
			elseif($type):
				$imageurl = str_replace('thumb',$type,$imageurl);
			endif;
		else:
			$imageurl  = 'assets/admin/images/user-avatar-big-01.jpg';
		endif;
		return trim($imageurl);
	}
}

/*
 * Encription
 */
if (!function_exists('manojEncript')) {
	function manojEncript($text) {
		$text	=	('MANOJ').$text.('KUMAR');
		return	base64_encode($text);
	}
}

/*
 * Decription
 */
if (!function_exists('manojDecript')) {
	function manojDecript($text) {
		$text	=	base64_decode($text);
		$text	=	str_replace(('MANOJ'),'',$text);
		$text	=	str_replace(('KUMAR'),'',$text);
		return $text;
	}
}

/*
 * Encryption
 */
if (!function_exists('manojEncrypt')) {
	function manojEncrypt($text) {
		$text	=	('MANOJ').$text.('KUMAR');
		return	base64_encode($text);
	}
}
/*
 * Decryption
 */
if (!function_exists('manojDecrypt')) {
	function manojDecrypt($text) {
		$text	=	base64_decode($text);
		$text	=	str_replace(('MANOJ'),'',$text);
		$text	=	str_replace(('KUMAR'),'',$text);
		return $text;
	}
}
/*
 * Word Limiter
 */
define("STRING_DELIMITER", " ");
if (!function_exists('wordLimiter')){
	function wordLimiter($str, $limit = 10){
		$str = strip_tags($str); 
		if (stripos($str, STRING_DELIMITER)){
			$ex_str = explode(STRING_DELIMITER, $str);
			if (count($ex_str) > $limit){
				for ($i = 0; $i < $limit; $i++){
					$str_s.=$ex_str[$i].'&nbsp;';
				}
				return $str_s.'...';
			}else{
				return $str;
			}
		}else{
			return $str;
		}
	}
}

if (!function_exists('currentDateTime')) {
	function currentDateTime() {
		return time();
	}
}

if (!function_exists('showDate')) {
	function showDate($time='') {
		if($time):
			return date("Y-m-d",$time);
		else:
			return false;
		endif;
	}
}

if (!function_exists('showTime')) {
	function showTime($time='') {
		if($time):
			return date("H:i:s",$time);
		else:
			return false;
		endif;
	}
}

if (!function_exists('showDateTime')) {
	function showDateTime($time='') {
		if($time):
			return date("d-m-Y H:i:s",$time);
		else:
			return false;
		endif;
	}
}


if (!function_exists('currentIp')) {
	function currentIp() {
		return $_SERVER['REMOTE_ADDR']=='::1'?'192.168.1.100':$_SERVER['REMOTE_ADDR'];
	}
}

if (!function_exists('generateRandomString')) {
	function generateRandomString($length = 10, $mode="n") {
		$characters = "";
		if(strpos($mode,"s")!==false){$characters.="abcdefghjklmnpqrstuvwxyz";}
		if(strpos($mode,"l")!==false){$characters.="ABCDEFGHJKLMNPQRSTUVWXYZ";}
		if(strpos($mode,"n")!==false){$characters.="0123456789";}
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		if(strlen($randomString) < $length):
			generateRandomString($length,$mode);
		else:
			return $randomString;
		endif;
	}
}

if (!function_exists('displayPrice')) {
	function displayPrice($price) {
		if(checkFloat($price)):
			return "Rs. ".number_format($price, 2, '.', '');
		else:
			return "Rs. ".number_format($price);
		endif;
	}
}

if (!function_exists('numberFormat')) {
	function numberFormat($price) {
		if(checkFloat($price)):
			return number_format($price, 2, '.', '');
		else:
			return number_format($price, 2, '.', '');
		endif;
	}
}

if (!function_exists('displayPercent')) {
	function displayPercent($price) {
		if(checkFloat($price)):
			return number_format($price, 2, '.', '').'%';
		else:
			return number_format($price).'%';
		endif;
	}
}

if (!function_exists('calculatePercent')) {
	function calculatePercent($prevpr,$curpr) {
		$val = $prevpr-$curpr;
		if($val<=0):
			$amountper = 0;
		else:
			$amountper = (($prevpr-$curpr)/($prevpr))*100;
		endif;
		if($amountper==100):
			$amountper = 0;
		endif;
		if(checkFloat($amountper)):
			return number_format($amountper,2, '.', '');
		else:
			return number_format($amountper);
		endif;
	}
}

if (!function_exists('checkFloat')) {
	function checkFloat($s_value) {
		$regex = '/^\s*[+\-]?(?:\d+(?:\.\d*)?|\.\d+)\s*$/';
		return preg_match($regex, $s_value);
	}
}

/*
 * Get session data
 */

if (!function_exists('sessionData')) {
    function sessionData($text)
    {
        $session = session(); // Use CI4 session service
        return $session->get($text); // Fetch session data
    }
}

/*
 * Get correct link
 */
if (!function_exists('correctLink')) {
	function correctLink($text='',$link='') {
		return	sessionData($text)?sessionData($text):$link;
	}
}

/*
 * Get full url
 */
 
if (!function_exists('currentFullUrl')) {
    function currentFullUrl()
    {
        $request = \Config\Services::request();
        $url = current_url(); // Get the base URL of the current page
        $queryString = $request->getServer('QUERY_STRING'); // Get query string

        return !empty($queryString) ? $url . '?' . $queryString : $url;
    }
}


if (!function_exists('generateUniqueId')) {
	function generateUniqueId($currentId = 1) {
		$newId		=	10000000000000+$currentId;
		return $newId;
	}
}

if (!function_exists('generateToken')) {
	function generateToken() {
		$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";	
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < 32; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}

/*
 * convert time to date
 */
if (!function_exists('convertTimeToDate')) {
	function convertTimeToDate($time='') {
		return $time?date('d-m-Y',$time):'';
	}
}

/*
 * convert date to time
 */
if (!function_exists('convertDateToTime')) {
	function convertDateToTime($date='') {
		return $date?strtotime($date):'';
	}
}

/*
 * Show Current MK Time
 */
if (!function_exists('getCurrentMKTime')) {
	function getCurrentMKTime()
	{  
		return mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));;
	}
}

/*
* Get diff Bet Two Date
*/
if (!function_exists('diffBetTwoDate')) {
	function diffBetTwoDate($firstDate='',$secondDate=''){
		$daysLeft 		= 	abs($firstDate - $secondDate);
		$days 			= 	$daysLeft/(60*60*24);
		return $days;
	}
}

if (!function_exists('diffBetTwoStandardDate')) {
	function diffBetTwoStandardDate($firstDate='',$secondDate=''){
		$daysLeft 		= 	abs($secondDate - $firstDate);
		$days 			= 	$daysLeft/(60*60*24);
		return $days;
	}
}

/*
* Get day range list between two date
*/
if (!function_exists('dateRangeBetweenTwoDate')) {
	function dateRangeBetweenTwoDate($first='',$last='',$step='+1 day',$outputFormat='d-m-Y') {
		$dates		= 	array();
		$current 	= 	$first;
		$last 		= 	$last;
		while($current <= $last):
			$dates[] 	= 	date($outputFormat,$current);
			$current 	= 	strtotime($step,$current);
		endwhile;
		return $dates;
	}
}


if (!function_exists('array_sort_by_column')) {
	function array_sort_by_column(&$arr, $col, $dir = SORT_ASC, $type = SORT_NUMERIC) {
	    $sort_col = array();	
	    foreach ($arr as $key=> $row) {
	        $sort_col[$key] = $row[$col];
	    }
	    $sort_col = array_map('strtolower', $sort_col);
	    array_multisort($sort_col, $dir, $type, $arr);
	}
}

if (!function_exists('showImage')) {
	function showImage($path='') {
	    if($path <> ""):
	    	$image 	=	base_url().$path;
	    endif;
	    return $image;
	}
}
/*
 * convert time to date Format
 */
if (!function_exists('convertTimeToDateFormat')) {
	function convertTimeToDateFormat($time='') {
		return $time?date('Y-m-d',$time):'';
	}
}

/*
 * Change ddmmyy to yymmdd
 */
if (!function_exists('TimetoMMYY')) {
	function TimetoMMYY($date) {
		$datedata			=	explode('-',$date); 
		if(isset($datedata[0]) && isset($datedata[1]) && isset($datedata[2])):
			return $date?date('F Y',strtotime($datedata[2].'-'.$datedata[1].'-'.$datedata[0])):'';
		else:
			return $date?date('F Y',$date):'';
		endif;
	}
}

/*
 * Change ddmmyy to yymmdd
 */
if (!function_exists('DDMMYYtoYYMMDD')) {
	function DDMMYYtoYYMMDD($date) {
		if($date):
			$datedata			=	explode('-',$date);
			$datedata			=	$datedata[2].'-'.$datedata[1].'-'.$datedata[0];
		else:
			$datedata			=	'';
		endif;
		return $datedata;
	}
}

/*
 * Change yymmdd to ddmmyy
 */
if (!function_exists('YYMMDDtoDDMMYY')) {
	function YYMMDDtoDDMMYY($date) {
		if($date && $date != '1970-01-01 00:00:00' && $date != '0000-00-00 00:00:00'):
			$datedata			=	explode(' ',$date);
			$datedata			=	explode('-',$datedata[0]);
			$datedata			=	$datedata[2].'-'.$datedata[1].'-'.$datedata[0];
		else:
			$datedata			=	'';
		endif;
		return $datedata;
	}
}
	
/*
 * show price format
 */
if (!function_exists('showPriceFormat')) {
	function showPriceFormat($price=''){
		return number_format($price,2);//$price>0?$price:'0.00';
	}
}	

/*
 * show correct public profile image
 */
if (!function_exists('showProductPrice')) {
	function showProductPrice($price=''){
		return number_format($price,2);//$price>0?$price:'0.00';
	}
}
/*
 * show correct files
 */
if (!function_exists('showCorrectFile')) {
	function showCorrectFile($fileLink) {
		if($fileLink):
			$fileLinkArray 	=	explode('assets/',$fileLink);
			$fileLink 		=	fileBaseUrl.'assets/'.$fileLinkArray[1];
		endif;
		return $fileLink;
	}
}

/*
 * generate session id
 */
if (!function_exists('generateSessionId')) {
	function generateSessionId(){
		$uniqueId = uniqid(rand(),TRUE);
		return md5($uniqueId);
	}
}

if (!function_exists('generateCODTnxId')) {
	function generateCODTnxId() {
		$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";	
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < 15; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return 'COD'.$randomString;
	}
}

if (!function_exists('generateAffiliateId')) {
	function generateAffiliateId() {
		$characters = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZ";	
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < 6; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return 'AP'.$randomString;
	}
}