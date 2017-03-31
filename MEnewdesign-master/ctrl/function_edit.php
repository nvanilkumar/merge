<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Edit the Function name
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 24th Aug 2009
******************************************************************************************************************************************/

	session_start();
	
	include_once("MT/cGlobal.php");
	include_once("MT/cFunctions.php");
	 include 'loginchk.php';

	$Global = new cGlobal();
	
	if($_POST['Submit'] == "Update")
	{
		$function = $_POST['eventfunction'];
		$function = trim($function);
//		$function = strtoupper($function);//strtolower($function);
              $CategoryId= $_POST['CategoryId'];
		
		// MAKE ALL FIRST LETTERS OF EACH WORD CAPITAL
//		$names = explode(" ",$function);
//		foreach($names as $key => $val)
//		{
//			$words[] = ucfirst($val);
//		}
//		$function = implode(" ",$words);
		$function = str_replace("&","AND",$function);
		$Id = $_POST['function_id'];

		try
		{
			$objFunctions = new cFunctions($Id, $function, $CategoryId);
			$Id = $objFunctions->Save();
	
			if($Id > 0)
			{
				header("location:editfunction.php");
			}
		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}
	}//ENDS update the existing function name
	else
	{
		$Id = $_GET['id'];
		
		//Query For Function Details
		//$functionQuery = "SELECT * FROM functions WHERE Id = '".$Id."'";
                $functionQuery = "SELECT `Function`,`CategoryId` FROM functions WHERE Id = '".$Id."'"; 
		$FunctionList = $Global->SelectQuery($functionQuery);
		
		for($i = 0; $i < count($FunctionList); $i++)
		{
			$function = $FunctionList[$i]['Function'];
                     $CategoryId = $FunctionList[$i]['CategoryId'];
		}
	}// END Get Function Details
        $CategoriesQuery = "SELECT Id, Category FROM categories";
	      $CategoriesRES = $Global->SelectQuery($CategoriesQuery);


	include 'templates/function_edit.tpl.php';
?>