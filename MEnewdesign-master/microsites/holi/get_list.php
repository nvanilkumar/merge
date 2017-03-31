<?php
session_start();
include("../MT/cGlobali.php"); 
$Globali = new cGlobali();



if(isset($_REQUEST['queryString'])) {
			$queryString = $_REQUEST['queryString'];
			
			// Is the string length greater than 0?
			
			if(strlen($queryString) >0) {
				
$SubCatISql=" and SubCategoryId in (143,164) ";
	
$sql = "select CONCAT(e.Title, ', in ', c.City) as label, e.Id from events as e 
	Inner join Cities as c on e.CityId=c.Id 
	where 1 and  e.Title LIKE '%$queryString%' and (e.StartDt > ADDDATE(now(), INTERVAL -10 DAY))  and e.Published=1 and e.Private=0  $SubCatISql $conCity $st limit 15";
	
	
	
//echo $sql."<br><br>";
$Cname = $Globali->SelectQuery($sql);


$TCname=count($Cname);

echo json_encode($Cname);

				
				
				
			} else {
				// Dont do anything.
			} // There is a queryString.
		} else {
			echo 'There should be no direct access to this script!';
		}


?>