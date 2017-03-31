<?php

/* * ********************************************************************************************** 
 * 	Page Details : Cron Job to update the event count for sub categories
 * 	Created by Gautam / Last Updation Details : 

 * ********************************************************************************************** */

include('../ctrl/MT/cGlobali.php');
include_once '../ctrl/includes/common_functions.php';

$commonFunctions = new functions();
$_GET = $commonFunctions->stripData($_GET, 1);

if ($_GET['runNow'] == 1) {

   ini_set('memory_limit', '300M');
   $cGlobali= new cGlobali();
   $selectQuery1 = "SELECT COUNT(id) as eventCount,subcategoryid
                     FROM event
                     WHERE subcategoryid > 0
                     AND enddatetime > now()
                     AND status = 1
                     AND deleted = 0
                     GROUP BY subcategoryid;";
   $selectQueryRe1 = $cGlobali->SelectQuery($selectQuery1, MYSQLI_ASSOC);
   $selectQuery2 = "UPDATE subcategory SET `order` = 0,modifiedby=".$commonFunctions->getCronUserDetails()." WHERE status = 1;";
   $selectQueryRe2 = $cGlobali->ExecuteQuery($selectQuery2);
   
   foreach($selectQueryRe1 as $data) {
      $selectQuery3 = "UPDATE subcategory SET `order` = ".$data['eventCount']." WHERE id = ".$data['subcategoryid'].";";
      $selectQueryRe3 = $cGlobali->ExecuteQuery($selectQuery3);
   }
   echo 'Updated successfully';exit;
   mysql_close();
}
?>