<?php 
//error_reporting(1);

$ipAccess = array('14.142.45.226','172.40.1.30','10.218.160.66','52.53.221.104');
if(!in_array($_SERVER['HTTP_X_FORWARDED_FOR'],$ipAccess)){
	exit("Sorry, You can't access this file.");
}

if(isset($_GET['cron']) && $_GET['cron']==1){
	define("crontab",true);
}else{ define("crontab",false); }


?>
