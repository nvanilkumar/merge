<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	Edit Popular Event Information
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 02nd Oct 2009
 *
******************************************************************************************************************************************/
	
	session_start();
		
	include 'loginchk.php';

	$uid =	$_SESSION['uid'];
	
	include_once("MT/cGlobal.php");
	include_once("MT/cPopEvents.php");

	$Global = new cGlobal();

	$base_path = '/home/meraeven/public_html';		
	$msgActionStatus = '';

	$Id = $_REQUEST['Id'];//PopEvents Id
	
	//Update Popular Event Information
	if($_REQUEST['Update'] == 'UpdatePopEvents')
	{
		$sActive = $_REQUEST['Active'];
	
		if($_FILES['filePopEventsImage']['error']==0)			//If file is attached.
		{
			$sFileName = $_FILES['filePopEventsImage']['name'];
			move_uploaded_file($_FILES['filePopEventsImage']['tmp_name'],$base_path."/images/PopEvents/".$_FILES['filePopEventsImage']['name']);
			$sFileName = "/images/PopEvents/".$sFileName;	
		}
		else
		{
			$objPopEvents = @new cPopEvents($Id);
			$objPopEvents -> Load();
			$sFileName = $objPopEvents->FileName;
		}
		
		$sTitle = addslashes(trim($_POST['txtTitle']));	
        $lUserId = $_POST['SerEventName'];	
		$sURL = trim($_POST['txtURL']);
		$sSeqNo = $_POST['txtSeqNo'];

		$UpPopEvents = new cPopEvents($Id, $sTitle, $sFileName, $lUserId, $sURL, $sActive, $sSeqNo);
					
		if($UpPopEvents->Save())
		{
			$msgActionStatus = "Popular Event Updated Successfully.";
		?>
			<script>
				window.location="manage_PopEvents.php";
			</script>
		<?php
		}
	}
//	 $SelectOrgNames1="SELECT * FROM orgdispname where Active=1  ORDER BY orgDispName ASC";
        $SelectOrgNames1="SELECT `Id`,`orgDispName` FROM orgdispname where Active=1  ORDER BY orgDispName ASC";
     $OrgNames1=$Global->SelectQuery($SelectOrgNames1); 
	//Query For All Popular Events List
	$PopEventsQuery = "SELECT * FROM PopEvents WHERE Id='".$Id."'"; //using all  -pH
	$PopEventsList = $Global->SelectQuery($PopEventsQuery);
	
	include 'templates/manage_PopEvents_edit.tpl.php';
?>