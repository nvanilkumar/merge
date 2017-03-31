<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	It displays the Functions list.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 24th Aug 2009
******************************************************************************************************************************************/
	session_start();
		
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();	
        include 'loginchk.php';

	if($_POST['Submit'] == "Delete")
	{
		include_once("MT/cFunctions.php");	//include the cFunction MT
		$delFunctionIds = $_POST['function'];		//get the list of functions in array of delFunctionIds
		
		foreach($delFunctionIds as $Id)			//get the function id one by one to delete
		{
			try
			{	
				$objFunctions = new cFunctions($Id);
				$Id = $objFunctions->Delete();
				if($Id > 0)
				{	
					//delete successful statement
				}
			}
			catch (Exception $Ex)
			{
				echo $Ex->getMessage();
			}
		}
	}// END IF delete
	
	//Query For All Functions
	$FunctionQuery = "SELECT * FROM functions ORDER BY Function ASC"; //using all
	$FunctionList = $Global->SelectQuery($FunctionQuery);

	include 'templates/editfunction.tpl.php';
?>