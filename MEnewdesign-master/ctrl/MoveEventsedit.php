<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Display Cancel Transactions
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 26th Aug 2009
 *	2.	Added the new filed IsFamous in db which is used to display the Famous Events on the front end.
 * 		The check box property checked shows the event is famous, visible on front end and vice versa.
******************************************************************************************************************************************/
	
	session_start();
	$uid =	$_SESSION['uid'];
	
	 include 'loginchk.php';
	
	
	include_once("MT/cGlobali.php");
	
	$Global = new cGlobali();
   $EventId=$_REQUEST[EventId];
   
   if($_REQUEST['Submit']=="Submit")
   {
   $orgId=$_REQUEST[orgid];
   if($EventId !="" &&  $orgId !=0)
   {
        $sqlUpdate="update event set ownerid='".$orgId."' where deleted = 0 and id=".$EventId;
        $res=$Global->ExecuteQuery($sqlUpdate);
        //checking collabrater status
        $collebratorQuery = "SELECT `id` from collaborator where eventid="
                .$EventId ." and userid=".$orgId ." limit 1" ; 
        $collebratorRES = $Global->SelectQuery($collebratorQuery);
        if(count($collebratorRES) >0){
            $sqlUpdate="update collaborator set deleted='1' where id=".$collebratorRES[0]['id'];
            $res=$Global->ExecuteQuery($sqlUpdate);
        }
        
      
   }
   if($res)
   {?>
   <script>
   window.location="MoveEvents.php?Msg=Done";
   </script>
   <?php }else {
   ?>
   <script>
   window.location="MoveEvents.php?Msg=Fail";
   </script>
   <?php 
   }
   }
	
//	$EventQuery = "SELECT * from events where Id=".$EventId ; 
   $EventQuery = "SELECT `title`,`startdatetime`,`cityid`,`ownerid` from event where deleted = 0 and id=".$EventId ; 
		$EventQueryRES = $Global->SelectQuery($EventQuery);
		
		$Org="select u.email,u.id from user u,organizer o where u.id=o.userid order by u.email ASC";
		$OrgRes= $Global->SelectQuery($Org);
		
	include 'templates/MoveEventsedit.tpl.php';	
?>
