<?php
session_start();
$uid =	$_SESSION['uid'];
	
 include 'loginchk.php';
 include 'includes/common_functions.php';
 include_once("MT/cGlobali.php");
	
	$Global = new cGlobali();
	$commonFunctions=new functions();
	if(isset($_REQUEST['uid'])){
	
  $url = _HTTP_SITE_ROOT."/api/user/adminSession"; 
// prepare the body data. Example is JSON here
$data = json_encode(array(
'organizerId' => $_REQUEST['uid']
));
// set up the request context
$options = ["http" => [
"method" => "POST",
"header" => ["Authorization: token " . $Authcode,
"Content-Type: application/json"],
"content" => $data
]];
$context = stream_context_create($options);
// make the request
$response = file_get_contents($url, false, $context);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$data);  //Post Fields
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = array();
$headers[] = 'Authorization: token' . $Authcode;
$headers[] = 'Content-Type: application/json' ;

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$server_output = curl_exec ($ch);

curl_close ($ch);

//print  $server_output ;
//print_r($_SESSION); exit;  
header("Location:"._HTTP_SITE_ROOT."/dashboard ");

}else{
	echo "Error in Loading";
}

?>