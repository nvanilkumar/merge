<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Manage Popular Events
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is creaed on 02nd Oct 2009
 *		i. MT - cPopEvents
 *		ii. Table Name - PopEvents
 *		iii. Fields List -  Id, Title, FileName, URL, Active, SeqNo
******************************************************************************************************************************************/
	
	session_start();
		
	 include 'loginchk.php';

	$uid =	$_SESSION['uid'];
	
	include_once("MT/cGlobal.php");
	include_once("MT/cPopEvents.php");
    include_once("../connection.php"); 

	$Global = new cGlobal();

	$base_path = '/home/meraeven/public_html';		
	$msgActionStatus = '';
	
	//Delete the Popular Events
	if(isset($_REQUEST['delete'])	)
	{
		$Id = $_REQUEST['delete'];
		
		try
		{	
			$objPopEvents = new cPopEvents($Id);
			if($objPopEvents->Delete())
			{	
				//delete successful statement
				$msgActionStatus = "Popular Event Deleted Successfully.";
			}
		}
		catch (Exception $Ex)
		{
			echo $Ex->getMessage();
		}
	}
	
	//Add new Popular Event
	if(isset($_POST['Submit']) == 'Upload')
	{
		if($_FILES['filePopEventsImage']['error']==0)			//If file is attached.
		{
			$sFileName = $_FILES['filePopEventsImage']['name'];
			move_uploaded_file($_FILES['filePopEventsImage']['tmp_name'],$base_path."/images/PopEvents/".$_FILES['filePopEventsImage']['name']);
		
			$sTitle = addslashes(trim($_POST['txtTitle']));		
			$sFileName = "/images/PopEvents/".$sFileName;
			$lUserId = $_POST['SerEventName'];
            $sURL = $_POST['txtURL'];  
			$sActive = 1;
			$sSeqNo = $_POST['txtSeqNo'];
			
			$objPopEvents = new cPopEvents(0, $sTitle, $sFileName, $lUserId, $sURL, $sActive, $sSeqNo);
       		if($objPopEvents->Save())
			{
				//successfully Popular Event uploaded!
				$msgActionStatus = "New Popular Event Added Successfully.";
			}
		}
	}
	
	//Change Pop Events Status (Active / Inactive)
     //echo "<pre>"; print($_REQUEST);exit;
	if($_REQUEST['Edit'] == 'ChangeStatus')
	{
		$Id = $_REQUEST['Id'];
		$sActive = $_REQUEST['Active'];
		
		$objPopEvents = @new cPopEvents($Id);
		$objPopEvents -> Load();
					
		$objPopEvents -> Active = $sActive;
					
		if($objPopEvents -> Save())
		{
			$msgActionStatus = "Popular Event Status Changed.";
		}
	}
	
    //Query For All Popular Events List
//     $SelectOrgNames1="SELECT * FROM orgdispname where Active=1  ORDER BY orgDispName ASC";
        $SelectOrgNames1="SELECT `Id`,`orgDispName` FROM orgdispname where Active=1  ORDER BY orgDispName ASC";
     $OrgNames1=$Global->SelectQuery($SelectOrgNames1); 
	//Query For All Popular Events List
	$PopEventsQuery = "SELECT * FROM PopEvents"; //using all -pH
	$PopEventsList = $Global->SelectQuery($PopEventsQuery);
	
	include 'templates/manage_PopEvents.tpl.php';
?>