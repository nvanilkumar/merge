<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Add the New Function Name
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 24th Aug 2009
******************************************************************************************************************************************/
	session_start();
	include 'loginchk.php';
		
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();	

	$msgFunctionExist = '';

	if($_POST['Submit'] == "Add")
	{
		$function = $_POST['event_function'];
		$function = trim($function); 
//		$function = strtoupper($function);//strtolower($function);
//		$function = ucfirst($function);
              $CategoryId= $_POST['CategoryId'];
		
		// MAKE ALL FIRST LETTERS OF EACH WORD CAPITAL
//		$names = explode(" ",$function);
//		foreach($names as $key => $val)
//		{
//			$words[] = ucfirst($val);
//		}
//		$function = implode(" ",$words);
		$function = str_replace("&","AND",$function);
		include_once("MT/cFunctions.php");		//include the cFunctions MT
		
		$functionQuery = "SELECT Id FROM functions WHERE Function = '".$function."' and CategoryId=".$CategoryId;

		$funId = $Global->SelectQuery($functionQuery); //Returns the Function Id if exist
		
		if(count($funId) > 0)
		{
			$msgFunctionExist = 'Function Name already exist!';
		}
		else
		{
			try
			{
				$objFunctions = new cFunctions(0, $function, $CategoryId);
				
				if($objFunctions->Save())
				{
					header("location:editfunction.php");
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}	
		}
	}//ENDS Add new function
       $CategoriesQuery = "SELECT Id, Category FROM categories";
	      $CategoriesRES = $Global->SelectQuery($CategoriesQuery);

	
	include 'templates/addfunction.tpl.php';
?>