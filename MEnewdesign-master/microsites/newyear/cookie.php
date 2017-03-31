<?php 
//$LogDetails.= "$IP - Inside Cookie : ".number_format((microtime(true)-$starttime),10)."\r\n";
//$starttime=microtime(true);
include_once("../MT/cGlobali.php");
$Globali = new cGlobali();

include_once '../includes/functions.php';
$commonFunctions=new functions();
$_GET=$commonFunctions->stripData($_GET);
$_POST=$commonFunctions->stripData($_POST);
$_REQUEST=$commonFunctions->stripData($_REQUEST);


//$LogDetails.= "$IP - After Cookie Include and DB object call : ".number_format((microtime(true)-$starttime),10)."\r\n";
//$starttime=microtime(true);




$cityname=isset($_REQUEST['cityname'])?$_REQUEST['cityname']:'';
$categoryname=isset($_REQUEST['categoryname'])?$_REQUEST['categoryname']:'';
$city="All Cities";
$cat=NULL;
$catId='';
$city1=NULL;
$CityId=NULL;
$conCat=NULL;


if(isset($_COOKIE['CityName']) && $cityname=="" ) { 
   if($_COOKIE['CityName']=="Other")
   {
	$city="Other";
	$conCity=" AND CityId not in(47,14,37,39,77,448) And StateId not in (53,11) ";   
   }else{
     $sql="SELECT Id,City from  Cities WHERE City ='".$Globali->dbconn->real_escape_string($commonFunctions->variable_filter($_COOKIE['CityName']))."'";
	 $res=$Globali->SelectQuery($sql);
	 if(!empty($res)){
       $city=$res[0]['City'];
	$CityId=$res[0]['Id'];
	if($CityId!="" && $CityId!=26132){
	$conCity=" AND CityId='".$Globali->dbconn->real_escape_string($CityId)."'";
	}
	 }
   }
} else if(isset($_COOKIE['CityName']) && $cityname!="") {
    
      if($cityname=="India"){
       $city="All Cities";
	   $conCity="";
	   	$month = 2592000 + time(); 
	setcookie("CityName", $city, $month); 

      } else  if($cityname=="Other"){
       $city="Other";
	   $conCity=" AND CityId not in(47,14,37,39,77,448) And StateId not in (53,11) "; 
	   	$month = 2592000 + time(); 
	setcookie("CityName", $city, $month); 

      }
	  else  if($cityname=="Goa"){
       $city="Goa";
	   	$month = 2592000 + time(); 
		setcookie("CityName", $city, $month); 

      }
	  
	  else{
      $sql="SELECT Id,City from  Cities WHERE City = '".$Globali->dbconn->real_escape_string($commonFunctions->variable_filter($cityname))."'";
	 $res=$Globali->SelectQuery($sql);
	  $city=$res[0]['City'];
	$CityId=$res[0]['Id'];
	if($CityId!="" && $CityId!=26132){
	$month = 2592000 + time(); 
	setcookie("CityName", $city, $month);
	$conCity=" AND CityId='".$Globali->dbconn->real_escape_string($CityId)."'";
      }
}	
}else if(!(isset($_COOKIE['CityName'])) && $cityname!="") {
    
      if($cityname=="India"){
       $city="All Cities";
	   $conCity="";
	   	$month = 2592000 + time(); 
	setcookie("CityName", $city, $month); 

      }
	  else  if($cityname=="Other"){
       $city="Other";
	   $conCity=" AND CityId not in(47,14,37,39,77,448) And StateId not in (53,11) "; 
	   	$month = 2592000 + time(); 
	setcookie("CityName", $city, $month); 
      }
	  else{
     $sql="SELECT Id,City from  Cities WHERE City = '".$Globali->dbconn->real_escape_string($commonFunctions->variable_filter($cityname))."'";
	 $res=$Globali->SelectQuery($sql);
	  $city=$res[0]['City'];
	$CityId=$res[0]['Id'];
	if($CityId!="" && $CityId!=26132){
	$month = 2592000 + time(); 
	setcookie("CityName", $city, $month); 
	$conCity=" AND CityId='".$Globali->dbconn->real_escape_string($CityId)."'";
      }
}	
} else if(!(isset($_COOKIE['CityName'])) && $cityname=="" && $categoryname=="") {


//$LogDetails.= "$IP - Till Ip City service : ".number_format((microtime(true)-$starttime),10)."\r\n";
//$starttime=microtime(true);


	







	if($CityId!="" && ($CityId==14 || $CityId==37 || $CityId==39 ||  $CityId==47 || $CityId==77 || $CityId==38)){
       $month = 2592000 + time(); 
	setcookie("CityName", $city, $month); 
	$conCity=" AND CityId='".$Globali->dbconn->real_escape_string($CityId)."'";
	}else{ 
 $city="All Cities";
	   $conCity="";
	   	$month = 2592000 + time(); 
	setcookie("CityName", $city, $month);
  }
  
  
  //$LogDetails.= "$IP - After Ip City service : ".number_format((microtime(true)-$starttime),10)."\r\n";
//$starttime=microtime(true);
  
}

//$LogDetails.= "$IP - After City Cookie : ".number_format((microtime(true)-$starttime),10)."\r\n";
//$starttime=microtime(true);
/*------------------ Catgeory Cookie-------------------*/
 
if(isset($_COOKIE['CatName']) && $categoryname=="") { 
  
  
    $sqlcat="SELECT Id,CatName from  category WHERE CatName ='".$Globali->dbconn->real_escape_string($_COOKIE['CatName'])."'";
	 $rescat=$Globali->SelectQuery($sqlcat);
	if(!empty($rescat)){
    	$cat=$rescat[0]['CatName'];
		$catId=$rescat[0]['Id'];
		if($catId!=""){
		$conCat=" AND CategoryId='".$Globali->dbconn->real_escape_string($catId)."'";
		}
	}
} else if(isset($_COOKIE['CatName']) && $categoryname!="") {
      if($categoryname=="All"){
       $cat="All Categories";
	   $conCat="";
	   	$month = 2592000 + time(); 
	setcookie("CatName", $cat, $month); 

      }else{
    $sqlcat="SELECT Id,CatName from  category WHERE CatName='".$Globali->dbconn->real_escape_string($commonFunctions->variable_filter($categoryname))."'";
	 $rescat=$Globali->SelectQuery($sqlcat);
	 if(!empty($rescat)){
   	 	$cat=$rescat[0]['CatName'];
		$catId=$rescat[0]['Id'];
		if($catId!=""){
		$month = 2592000 + time(); 
		setcookie("CatName", $cat, $month); 
		$conCat=" AND CategoryId='".$Globali->dbconn->real_escape_string($catId)."'";
      	}
	 }
}	
}else if(!(isset($_COOKIE['CatName'])) && $categoryname!="") {
    
      if($categoryname=="All"){
       $cat="All Categories";
	   $conCat="";
	   	$month = 2592000 + time(); 
	setcookie("CatName", $cat, $month); 

      }else{
     $sqlcat="SELECT Id,CatName from  category WHERE CatName='".$Globali->dbconn->real_escape_string($commonFunctions->variable_filter($categoryname))."'";
	 $rescat=$Globali->SelectQuery($sqlcat);
	 if(!empty($rescat)){
		$cat=$rescat[0]['CatName'];
		$catId=$rescat[0]['Id'];
		if($catId!=""){
		$month = 2592000 + time(); 
		setcookie("CatName", $cat, $month); 
		$conCat=" AND CategoryId='".$Globali->dbconn->real_escape_string($catId)."'";
		  }
	 }
}	
} else if(!(isset($_COOKIE['CatName'])) && $categoryname=="") {
	 $cat="All Categories";
	   $conCat="";
	   	$month = 2592000 + time(); 
	setcookie("CatName", $cat, $month); 
	}

//$LogDetails.= "$IP - End of Cookie : ".number_format((microtime(true)-$starttime),10)."\r\n";
//$starttime=microtime(true);
$cookie_city=$city;//adding to use it footer SEO related links -pH
if($cat== "All Categories")
{
    $cookie_cat="all";
}
else{
$cookie_cat=$cat;//adding to use it footer SEO related links -pH
}



?>