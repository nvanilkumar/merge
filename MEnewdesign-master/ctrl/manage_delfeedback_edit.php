<?php
session_start();
	
	 include 'loginchk.php';

	$uid =	$_SESSION['uid'];
	
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();

          
       $base_path = '/home/meraeven/public_html/meraevents_dev/';
	$msgActionStatus = '';

	 $Id = $_REQUEST['editid'];  //Banner Id
	
	//Update Banner Information

	if($_REQUEST['Update'] == 'UpdateFeedBack')
	{
	    /* if($_FILES['fileLogoImage']['error']==0)	
		 {
		 	$sFileName = $_FILES['fileLogoImage']['name'];
			move_uploaded_file($_FILES['fileLogoImage']['tmp_name'],$base_path."images/".$_FILES['fileLogoImage']['name']);
			$sFileName = "/images/".$sFileName;
		 }else{
		 $sFileName=$_REQUEST[editlogo];
		 }*/
	
		 $sqlup="update delfeedbacks set vFName='".$_REQUEST[txtName]."',Title='".$_REQUEST[txtTitle]."',vEmail='".$_REQUEST[txtemail]."',vMobile='".$_REQUEST[txtMobile]."',tComment='".$_REQUEST[txtComment]."',eStatus='".$_REQUEST[eStatus]."' where Id=$Id";
         $res=$Global->ExecuteQuery($sqlup);
					
		if($res)
		{
			$msgActionStatus = "FeedBack Updated Successfully.";
		?>
			<script>
				window.location="manage_delfeedback.php";
			</script>
		<?php
		}
	}
	
	//Query For All Banners List
	$TestQuery = "SELECT * FROM delfeedbacks WHERE Id='".$Id."'";
	$ResTestQuery = $Global->SelectQuery($TestQuery);
	
	include 'templates/manage_delfeedback_edit.tpl.php';
?>