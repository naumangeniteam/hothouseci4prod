<?php
namespace App\Controllers\front;

use App\Controllers\BaseController;

use \DrewM\MailChimp\MailChimp;

class Event_dice  extends BaseController
{
    private $arrLocations = [
        'new-york',
        'los-angeles'
    ];
    private $varBaseUrl = "https://dice.fm/_next/data/7GHzo7wJXmhTBlJwHUAKz/browse/";
    public function  __construct()
	{
		
		//error_reporting(E_ALL ^ E_NOTICE);  
		error_reporting(0);
		$this->load->model(array('admin_model', 'front_model', 'emailtemplate_model', 'sms_model', 'notification_model', 'common_model'));
		$this->lang = service('language'); 
$this->lang->setLocale('front');
		helper('common');
	}
    public function index(){
        $this->layouts->front_view('dice_event', array(), []);
        // $arrAllDiceLoc = $this->arrLocations;
        // echo "https://dice.fm/_next/data/mdyfxrMsF3MgmhvK1m36H/browse/new-york/music/gig/jazz.json";exit;
        // // foreach($arrAllDiceLoc as $key=>$val){
        //     //$url = $this->varBaseUrl."{$val}/music/gig/jazz.json";
        //     $curl = curl_init();
        //     curl_setopt_array($curl, array(
        //     CURLOPT_URL => "https://dice.fm/_next/data/mdyfxrMsF3MgmhvK1m36H/browse/rome/music/gig/jazz.json",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => false,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'GET',
        //     CURLOPT_HTTPHEADER=>array(
        //         'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:133.0) Gecko/20100101 Firefox/133.0'
        //     )
        //     ));
        //     $response = curl_exec($curl);
        //     curl_close($curl);
        //    
        //     exit;

        // }

    }
}