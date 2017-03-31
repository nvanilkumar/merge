<?php
if(PHP_SAPI == "cli")
{
$_SERVER['DOCUMENT_ROOT'] = dirname(dirname(__FILE__));
}
else{
	include($_SERVER['DOCUMENT_ROOT']."/crons/commondbdetails.php");
}




/* * ********************************************************************************************** 
 * 	Page Details : Cron Job Transactions happen yesterday and Send Mail
 * 	Created by Sunil / Last Updation Details : 

 * ********************************************************************************************** */
include($_SERVER['DOCUMENT_ROOT'].'/ctrl/MT/cGlobali.php');
//include('../ctrl/includes/commondbdetails.php');
include_once $_SERVER['DOCUMENT_ROOT'].'/ctrl/includes/common_functions.php';
$Global = new cGlobali();
$commonFunctions = new functions();


$_GET = $commonFunctions->stripData($_GET, 1);
$_POST = $commonFunctions->stripData($_POST, 1);
$_REQUEST = $commonFunctions->stripData($_REQUEST, 1);

//error_reporting(-1);
//ini_set('display_errors',1);


$categories = array(
				"Entertainment"=>1,
				"Professional"=>2,
				"Training"=>3,
				"Campus"=>4,
				"Spiritual"=>5,
				"Trade-Shows"=>6,
				"NewYear"=>8,
				"Sports"=>9
			  );
	
$APIurl = "/api/event/sitemapevents";
	
$eventListArray = array();
//$eventListArray['countryId'] =14;
$eventListArray['day'] = 6;
$eventListArray['limit'] = 10000;
$eventListArray['eventMode']=0;
$eventListArray['ticketSoldout']=0;	

$year = date("Y");

foreach($categories as $catName=>$catId)
{
	$eventListArray['categoryId']=$catId;
	$eventListArray['year']=$year;
	
	$eventsdata = $commonFunctions->makeSolrCall($eventListArray,$APIurl);
	//var_dump($eventsdata); exit;
	//print_r(json_decode($eventsdata,true)); exit; 
	$eventsdata = json_decode($eventsdata,true); 
	//exit;
	
	
	$eventsList = $eventsdata['response']['eventList'];
	
	
	
	$xml='<?xml version="1.0" encoding="UTF-8"?>
	<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
	
	foreach($eventsList as $key=>$value){
		// [url] = content url
		$url = $value['eventUrl'];
		// [time] = content date
		$lastmodEx = explode(' ',$value['mts']);
		
		// NO CHANGES BELOW
		$xml.=
		'<url>
		 <loc>' . $url .'</loc>
		 <lastmod>'. $lastmodEx[0] .'</lastmod>
		 <changefreq>daily</changefreq>
		 <priority>0.8</priority>
		 </url>
		';
	}
	$xml.='</urlset>';
	
	$filename = $_SERVER['DOCUMENT_ROOT']."/sitemap/".$catName."-events-".$year.".xml";
	$file= fopen($filename, "w");
	fwrite($file, $xml);

}
?>