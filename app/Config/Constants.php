<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

defined('SERVER_NAME') || define('SERVER_NAME', $_SERVER['SERVER_NAME'] ?? 'localhost');
defined('HTTP_HOST')   || define('HTTP_HOST', $_SERVER['HTTP_HOST'] ?? 'localhost');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code
/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

defined('SHOW_NO_OF_DATA')      OR 	define('SHOW_NO_OF_DATA', 10); // show no of data in table

defined('MAIL_FROM_MAIL')      OR define('MAIL_FROM_MAIL', 'manoj.kumar@algosoft.co'); 
defined('MAIL_SITE_FULL_NAME') OR define('MAIL_SITE_FULL_NAME', 'Indian Legal'); 

defined('ANDRIOD_API_ACCESS_KEY')		OR 	define('ANDRIOD_API_ACCESS_KEY','');
defined('IOS_API_ACCESS_KEY')			OR 	define('IOS_API_ACCESS_KEY','');

defined('SMS_AUTH_KEY')     			OR 	define('SMS_AUTH_KEY','');
defined('SMS_SENDER')     				OR 	define('SMS_SENDER','IndianLegal');
defined('SMS_COUNTRY_CODE')     		OR 	define('SMS_COUNTRY_CODE','91');
defined('SMS_SEND_STATUS')     			OR 	define('SMS_SEND_STATUS','NO');

defined('CURRENT_DATE')					OR 	define('CURRENT_DATE',date('Y-m-d'));
defined('CURRENT_TIME')					OR 	define('CURRENT_TIME',time());

defined('MAX_LOGIN_ATTEMPT')     		OR 	define('MAX_LOGIN_ATTEMPT','3');
defined('LOGIN_REATTEMPT_TIME')     	OR 	define('LOGIN_REATTEMPT_TIME','86400');

defined('APIKEY')     					OR 	define('APIKEY',md5("IndianLegal".date('Y-m-d')));
defined('APIDATE')     					OR 	define('APIDATE',date('Y-m-d'));

/////////////   Localhost 		/////////////////
if($_SERVER['SERVER_NAME']=='localhost'):
	defined('TIME_DIFFRENCE')			OR 	define('TIME_DIFFRENCE','0'); 
	defined('MAIN_URL')     			OR 	define('MAIN_URL','http://'.$_SERVER['HTTP_HOST'].'/hothousejazz.com/');
	$fileBaseUrl 						=	'http://'.$_SERVER['HTTP_HOST'].'/hothousejazz.com/';
	$fileFCPATH 						=	$_SERVER['DOCUMENT_ROOT'].'/hothousejazz.com/';

	$baseUrlForWebview					=	'http://'.$_SERVER['HTTP_HOST'].'/hothousejazz.com/';
	$baseUrlForCommonWeb				=	'http://'.$_SERVER['HTTP_HOST'].'/hothousejazz.com/';
///////////// 	/////////////////	
else: 
	defined('TIME_DIFFRENCE')			OR 	define('TIME_DIFFRENCE','1800');
	defined('MAIN_URL')     			OR 	define('MAIN_URL','http://'.$_SERVER['HTTP_HOST'].'/');
	$fileBaseUrl 						=	'https://'.$_SERVER['HTTP_HOST'].'/';
	$fileFCPATH 						=	$_SERVER['DOCUMENT_ROOT'].'/hothousejazz.com/';

	$baseUrlForWebview					=	'http://'.$_SERVER['HTTP_HOST'].'/';
	$baseUrlForCommonWeb				=	'http://'.$_SERVER['HTTP_HOST'].'/'; 
endif;

defined('DEFAULT_TIMEZONE')     		OR 	define('DEFAULT_TIMEZONE','Asia/Dubai');
defined('CURR_TIMEZONE')      	   		OR 	define('CURR_TIMEZONE', 'Asia/Dubai'); // set default time zone

defined('fileBaseUrl')     				OR 	define('fileBaseUrl',$fileBaseUrl);
defined('fileFCPATH')     				OR 	define('fileFCPATH',$fileFCPATH);

defined('baseUrlForWebview')     		OR 	define('baseUrlForWebview',$baseUrlForWebview);
defined('baseUrlForCommonWeb')     		OR 	define('baseUrlForCommonWeb',$baseUrlForCommonWeb);