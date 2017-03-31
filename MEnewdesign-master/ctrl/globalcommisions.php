<?php

/* * ****************************************************************************************************************************************
 * 	File deatils:
 * 	Add the city against the state.
 * 	It checkes the city name against the state already exist or not if it shows the error message.
 * 	
 * 	Created / Updated on:
 * 	1.	Using the MT the file is updated on 22nd Aug 2009
 * **************************************************************************************************************************************** */

session_start();
include 'loginchk.php';

include_once("MT/cGlobali.php");


$Global = new cGlobali();

$msgStateCityExist = '';

if ($_POST['Submit'] == "Update") {
    $Ebs = $_POST['Ebs'];
    $Paypal = $_POST['Paypal'];
    $ServiceTax = $_POST['ServiceTax'];
    $Cod = $_POST['Cod'];
    $Counter = $_POST['Counter'];
    $MEeffort = $_POST['MEeffort'];
    $mts = date('Y-m-d H:i:s');
	
	  $upsql = "UPDATE commission
          SET value = 
           CASE 
           WHEN type =1 THEN '".$Ebs.
        "' WHEN type =2 THEN '".$Cod.
		 "' WHEN type =3 THEN '".$Counter.
		  "' WHEN type =4 THEN '".$Paypal.
		   "' WHEN type =5 THEN '".$Ebs.
		    "' WHEN type =6 THEN '".$Ebs.
			 "' WHEN type =11 THEN '".$MEeffort.
			 "' WHEN type =12 THEN '".$ServiceTax."'".
         " END
        where global=1 and type !=7";
           
      if ($Global->ExecuteQuery($upsql)) {
        $_SESSION['GCupdated'] = true;
    }
}// END IF Add
//Query For State List

$sql = "select `type`,`value` from `commission` where global = 1";
$recComm = $Global->SelectQuery($sql);
for ($i = 0; $i < count($recComm); $i++) {
    if ($recComm[$i]['type'] == 1) {
        $Ebs = $recComm[$i]['value'];
    }
    if ($recComm[$i]['type'] == 2) {
        $Cod = $recComm[$i]['value'];
    }
    if ($recComm[$i]['type'] == 3) {
        $Counter = $recComm[$i]['value'];
    }
    if ($recComm[$i]['type'] == 4) {
        $Paypal = $recComm[$i]['value'];
    }
	if($recComm[$i]['type'] == 11){
		$MEeffort = $recComm[$i]['value'];
	}
	if($recComm[$i]['type'] == 12){
	$ServiceTax=$recComm[$i]['value'];
	}
}
//select c.value,pg.name from commission as c
//join paymentgateway as pg on pg.id=c.type
//where c.global=1



include 'templates/globalcommisions.tpl.php';
?>