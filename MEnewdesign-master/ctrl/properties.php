   <?php 
    @session_start();
   
    
 include 'loginchk.php';

 $uid =    $_SESSION['uid'];
    
    include_once("MT/cGlobal.php");
	
	
	$Global = new cGlobal();
    
   

   
    
if($_REQUEST['cityid']!="")
{
$ct=" AND CityId=".$_REQUEST['cityid'];
}else{
$ct="";
}

        
$EventsQuery = "SELECT Id, Name, Address, StateId, CityId,UserId FROM venues_property where 1 $ct ORDER BY Name ASC";
$EventsOfMonth = $Global->SelectQuery($EventsQuery);

$sqlcity="select distinct(c.Id),c.City from Cities as c,venues_property as v where c.Id=v.CityId";
 $ResCity = $Global->SelectQuery($sqlcity);
    
    include 'templates/properties.tpl.php';    
?>
