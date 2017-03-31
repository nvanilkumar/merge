<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Edit the city name aginst the state
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 22nd Aug 2009
******************************************************************************************************************************************/

	session_start();
	
	include_once("MT/cGlobali.php");
	 include 'loginchk.php';
	   include_once 'includes/common_functions.php';

	
	$Global = new cGlobali();
	 $commonFunctions=new functions();
	 
	  $currencyList = $commonFunctions->getCurrencyList($Global);

	if($_POST['Submit'] == "Update")
	{
		
		$EventId = $_POST['EventId'];
		$Id= $_REQUEST[Id];
		$Label = $_POST['Label'];
		$Amount = $_POST['Amount'];
              $Type = $_POST['Type'];
		$Label = trim($Label);
		$Label = strtolower($Label);
		$Id= $_REQUEST[Id];
		// MAKE ALL FIRST LETTERS OF EACH WORD CAPITAL
		$names = explode(" ",$Label);
		foreach($names as $key => $val)
		{
			$words[] = ucfirst($val);
		}
		$Label = implode(" ",$words);
		
		try
		{
			        $inssql="UPDATE eventextracharge SET eventid='".$EventId."',label='".$Label."',value='".$Amount."',type='".$Type."' WHERE id=".$Id;
				 $Id1=$Global->ExecuteQuery($inssql);
	
		//	if($Id1 > 0)
		//	{
				header("location:extracharges.php");
		//	}
		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}
	}// END IF update
	
		$Id = $_GET['id'];
		
	
		
		//Query For State List
		$StatesQuery = "SELECT * FROM eventextracharge WHERE id = '".$Id."'";
		$StateList = $Global->SelectQuery($StatesQuery);
		
		for($i = 0; $i <  count($StateList); $i++)
		{
			$EventId = $StateList[$i]['eventid'];
			$Label = $StateList[$i]['label'];
		    $Amount = $StateList[$i]['value'];
            $Type = $StateList[$i]['type'];
			$Currencyid = $StateList[$i]['currencyid'];
		}
                $selectSaleCount="SELECT SUM(quantity) as saleqty FROM eventsignup WHERE eventid='".$EventId."' and transactionstatus='success' and paymentstatus NOT IN('Canceled','Refunded','PartialRefund')";
                $responseSaleCount=$Global->SelectQuery($selectSaleCount);
                $disableFields=false;
                if(count($responseSaleCount)>0 && $responseSaleCount[0]['saleqty']>0){
                    $disableFields=true;
                }
	
	include 'templates/extracharges_edit.tpl.php';
?>