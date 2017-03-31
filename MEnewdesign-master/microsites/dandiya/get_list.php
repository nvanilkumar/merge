<?php
session_start();
include("../MT/cGlobali.php"); 
$Globali = new cGlobali();



if(isset($_REQUEST['queryString'])) {
			$queryString = $_REQUEST['queryString'];
			
			// Is the string length greater than 0?
			
			if(strlen($queryString) >0) {
				
/* dandiya Category Id */

$CaId=" and CategoryId=1 ";
$SubCat=' and subcategoryid=8 ';

$sql = "select CONCAT(e.Title, ', in ', c.City) as label, e.Id from events as e 
	Inner join Cities as c on e.CityId=c.Id 
	where 1 and  e.Title LIKE '%$queryString%' and (e.StartDt > ADDDATE(now(), INTERVAL -10 DAY))  and e.Published=1 and e.Private=0  $CaId $SubCat $conCity $st limit 10";
	
	
	
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