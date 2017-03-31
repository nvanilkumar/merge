<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Add the city against the state.
 *	It checkes the city name against the state already exist or not if it shows the error message.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 22nd Aug 2009
******************************************************************************************************************************************/
	
	session_start();
	include 'loginchk.php';
        include_once 'includes/common_functions.php';
	include_once("MT/cGlobali.php");
	
	
	$Global = new cGlobali();	
        $commonFunctions=new functions();
	$msgStateCityExist = '';
	if(!empty($_REQUEST['EventId'])){
           $query="SELECT id FROM event WHERE id=".$_REQUEST['EventId']." and deleted=1";
           $outputEvent=$Global->SelectQuery($query);
           if(!$outputEvent){
                $currencyList = $commonFunctions->getCurrencyList($Global);
	if($_POST['Submit'] == "Add")
	{
            $EventId = $_POST['EventId'];
            $Label = $_POST['Label'];
            $Amount = $_POST['Amount'];
            $Type = $_POST['Type'];
            if ($Type == 2) {
                $currencyId = $_POST['ex_currency'];
            }
            else {
                $currencyId = 0;
            }
            $Label = trim($Label);
            $Label = strtolower($Label);
		
		// MAKE ALL FIRST LETTERS OF EACH WORD CAPITAL
		$names = explode(" ",$Label);
		foreach($names as $key => $val)
		{
			$words[] = ucfirst($val);
		}
		$Label = implode(" ",$words);
		
		$CityQuery = "SELECT id FROM eventextracharge WHERE eventid = ".$EventId." and status=1 and deleted=0 and label='".$Label."'";
                if($Type==2){
                    $CityQuery.=" AND currencyid=".$currencyId;
                }
		$CityId = $Global->SelectQuery($CityQuery);                    
		if(count($CityId) > 0)
		{
			$msgStateCityExist = 'Label for this Event already exist!';
		}
		else
		{
			try
			{	
				$inssql="INSERT INTO eventextracharge(eventid,label,`value`,`type`, currencyid, createdby, modifiedby ) values('".$EventId."','".$Label."','".$Amount."','".$Type."', '".$currencyId."','".$_SESSION['uid']."',0) "; 
				$Id=$Global->ExecuteQuery($inssql);
				if($Id)
				{
					header("location:extracharges.php");
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}
		}	
	
	}// END IF Add
		
	        }}
	
	$EventId=(isset($_GET['EventId']))?$_GET['EventId']:NULL;
	

	include 'templates/addextracharges.tpl.php';
?>