<?php
// API access key from Google API's Console
define( 'API_ACCESS_KEY', 'AAAAlWN_DCc:APA91bE8eN_e1DL0MZVqJlaQGrVQ3Z5Ljd-T5KuFIgS2vx-c1zGqqATNBneCViV0oMaovZmZaCqhux_kK4bjpvzBG8RsOt1ZUjvw7cdfHXEJjmvUhZIOWHyPJiIqXtdcdGJMQiGmYHj8' );
$registrationIds = '/topics/globalPush';
// prep the bundle
$msg = array
(
    'type' => "Info",
    'message' => "Siap Bantuan Segera datang"
);
$fields = array
(
	'to' => $registrationIds,
	'data' => $msg
);
 
$headers = array
(
	'Authorization: key=' . API_ACCESS_KEY,
	'Content-Type: application/json'
);
 
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
echo $result;
?>