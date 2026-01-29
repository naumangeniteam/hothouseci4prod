<?php
// if(!defined('BASEPATH')) exit('No direct script access allowed');
namespace App\Models;

use CodeIgniter\Model;
class NotificationModel extends Model
{
	public function __construct()
	{
		 
	}

	/***********************************************************************
	** Function name : sendNotificationToAllUsers
	** Developed By : Ravi Kumar
	** Purpose  : This is get send send Notification To All Users
	** Date : 08 APRIL 2021
	************************************************************************/
	public function sendNotificationToAllUsers($notificationIDs='',$message='',$data=array(),$legencyKey='')
	{
		if(!empty($notificationIDs) && !empty($message) && !empty($data) && !empty($legencyKey)):
			
			$fields 	= 	array('to'=>$notificationIDs,'notification'=>$message,'data'=>$data);
			$headers 	= 	array('Authorization: key='.$legencyKey,'Content-Type:application/json');
				
			#Send Reponse To FireBase Server	
    		$ch = curl_init();
    		curl_setopt( $ch,CURLOPT_URL,'https://fcm.googleapis.com/fcm/send');
    		curl_setopt( $ch,CURLOPT_POST,true);
    		curl_setopt( $ch,CURLOPT_HTTPHEADER,$headers);
    		curl_setopt( $ch,CURLOPT_RETURNTRANSFER,true);
    		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER,true);
    		curl_setopt( $ch,CURLOPT_POSTFIELDS,json_encode($fields));
    		$result = curl_exec($ch);
    		curl_close($ch);
    		//echo "<pre>";print_r($result);die;
			return $result;
		endif;
	}	// END OF FUNCTION
}	