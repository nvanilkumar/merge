   <?php 
    @session_start();
   
    
 include 'loginchk.php';

 $uid =    $_SESSION['uid'];
    
    include_once("MT/cGlobal.php");
	
	
	$Global = new cGlobal();
    
    if(isset($_REQUEST['newStatus']))
    {
        
    
        $VId = $_REQUEST['VId'];
        $IsFamous = $_REQUEST['newStatus'];
        
        $update_query="UPDATE venues_details SET Priority='".$IsFamous."' WHERE Id='".$VId."'";
        $Global->ExecuteQuery($update_query);    
       // mysql_close();
    }
   
    
        if(isset($_REQUEST['newNotMoreStatus']))
    {
        
    
        $VId = $_REQUEST['VId'];
        $newNotMoreStatus = $_REQUEST['newNotMoreStatus'];
        
         $update_query="UPDATE venues_details SET Banner='".$newNotMoreStatus."' WHERE Id='".$VId."'"; 
        $Global->ExecuteQuery($update_query);    
       // mysql_close();
    }
	

   
    
if($_REQUEST['cityid']!="")
{
$ct=" AND CityId=".$_REQUEST['cityid'];
}else{
$ct="";
}

        
$EventsQuery = "SELECT Id, VenueTitle, VenueAddress, Priority, Banner,UserId FROM venues_details where 1 $ct ORDER BY VenueTitle ASC";
$EventsOfMonth = $Global->SelectQuery($EventsQuery);

$sqlcity="select distinct(c.Id),c.City from Cities as c,venues_details as v where c.Id=v.CityId";
 $ResCity = $Global->SelectQuery($sqlcity);
    
    include 'templates/venuebanners.tpl.php';    
?>
